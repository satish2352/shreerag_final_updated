<?php

namespace App\Support;

/**
 * BomTotalCalculator — server-side mirror of the JS formula in
 * resources/views/organizations/common/bom-material-items-modal.blade.php.
 *
 * JavaScript reference (lines 653-672 of the modal):
 *   PIECE_UNITS      = ['NOS','PCS','SET','EACH']
 *   isPieceUnit(u)   = PIECE_UNITS.includes(u.trim().toUpperCase())
 *   t                = parseInt(trolley_qty) || 1;  if (t < 1) t = 1;
 *   baseMultiplier   = isPieceUnit(unit) ? quantity : mtr_for_01_nos_trolley
 *   rowTotal         = rate * baseMultiplier * t
 *   finalTotal       = SUM(rowTotal across all active rows)
 *
 * Unit-name note (project memory project_shreerag_bom_unit_naming):
 *   Excel uploads use MTR/NOS; the master uses METER/NOS.
 *   Detection is case-insensitive; anything NOT in PIECE_UNITS is treated
 *   as a length-based unit.
 *
 * T-2026-046: Created to fix EstimationController and BomMaterialItemsRepository
 *             which previously used the naive formula rate × quantity, ignoring
 *             trolley_qty and the unit-aware multiplier.
 *
 * Data-migration note: rows previously saved with the wrong total_estimation_amount
 * will self-heal on the next BOM save (saveItemsWithExceedCheck is called on every
 * save). No Artisan migration command is required.
 */
class BomTotalCalculator
{
    /** Unit names treated as piece/count units (case-insensitive after trim). */
    private const PIECE_UNITS = ['NOS', 'PCS', 'SET', 'EACH'];

    /**
     * Compute the per-row total using the unit-aware formula.
     *
     * @param  float|int|string|null  $rate
     * @param  float|int|string|null  $quantity             used when unit is a piece unit
     * @param  float|int|string|null  $mtrFor01NosTrolley   used when unit is a length unit
     * @param  string                 $unitName             e.g. "NOS", "MTR", "METER", "nos"
     * @param  int|string|null        $trolleyQty           number of trolleys (clamped to >= 1)
     * @return float
     */
    public static function rowTotal(
        $rate,
        $quantity,
        $mtrFor01NosTrolley,
        string $unitName,
        $trolleyQty
    ): float {
        $r = (float) ($rate ?? 0);

        return $r * self::scaledQuantity($quantity, $mtrFor01NosTrolley, $unitName, $trolleyQty);
    }

    /**
     * Unit-aware base multiplier BEFORE trolley scaling — piece units use `quantity`,
     * length/raw units use `mtrFor01NosTrolley`. This is the "per 1 trolley" figure.
     *
     * T-2026-059: extracted out of rowTotal() so the Store -> Purchase requisition
     * write path (StoreController) can compute a unit-aware REQUIRED QUANTITY (not
     * just a money total) using the exact same unit-detection rule, without
     * duplicating the piece/length branch anywhere else.
     *
     * @param  float|int|string|null  $quantity
     * @param  float|int|string|null  $mtrFor01NosTrolley
     * @param  string                 $unitName
     * @return float
     */
    public static function baseMultiplier($quantity, $mtrFor01NosTrolley, string $unitName): float
    {
        return self::isPieceUnit($unitName)
            ? (float) ($quantity ?? 0)
            : (float) ($mtrFor01NosTrolley ?? 0);
    }

    /**
     * Unit-aware, trolley-scaled TOTAL quantity actually required across ALL
     * trolleys for this order — i.e. baseMultiplier() × max(1, trolleyQty).
     *
     * This is the canonical "quantity sent to Purchase" figure (T-2026-059):
     * previously the write path used the raw per-1-trolley BOM quantity verbatim,
     * which under-stated the real requirement whenever trolleyQty > 1.
     *
     * @param  float|int|string|null  $quantity
     * @param  float|int|string|null  $mtrFor01NosTrolley
     * @param  string                 $unitName
     * @param  int|string|null        $trolleyQty
     * @return float
     */
    public static function scaledQuantity($quantity, $mtrFor01NosTrolley, string $unitName, $trolleyQty): float
    {
        // Mirror JS: parseInt(trolley_qty) || 1; if (t < 1) t = 1;
        $t = max(1, (int) $trolleyQty);

        return self::baseMultiplier($quantity, $mtrFor01NosTrolley, $unitName) * $t;
    }

    /**
     * Canonical shortage relationship: shortage = max(0, requiredQty - availableQty).
     * `availableQty` is a real, physical stock count and must NEVER be trolley-scaled;
     * only `requiredQty` (typically the output of scaledQuantity()) is.
     *
     * @param  float|int|string|null  $requiredQty
     * @param  float|int|string|null  $availableQty
     * @return float
     */
    public static function shortage($requiredQty, $availableQty): float
    {
        return max(0.0, (float) ($requiredQty ?? 0) - (float) ($availableQty ?? 0));
    }

    /**
     * Compute the EFFECTIVE (always unit-aware + trolley-scaled) required quantity
     * for a requisition_items-shaped row, transparently handling both:
     *   - post-T-2026-059 rows ($isAlreadyScaled=true): required_quantity was already
     *     computed via scaledQuantity() at write time — used as-is, never re-scaled
     *     (re-scaling would double-count the trolley factor).
     *   - legacy pre-T-2026-059 rows ($isAlreadyScaled=false): required_quantity was
     *     written on the OLD raw, non-unit-aware, per-1-trolley basis — this
     *     retroactively (DISPLAY ONLY, never persisted back) recomputes the correct
     *     figure so old sent requisitions stop under-representing multi-trolley
     *     orders too. Never call this for a value that is about to be saved back
     *     into a mutable/editable row (see StoreController::showBomInventoryCheck()
     *     for the draft-row exception).
     *
     * @param  float|int|string|null  $requiredQuantity     row's stored required_quantity
     * @param  float|int|string|null  $mtrFor01NosTrolley    row's stored mtr_for_01_nos_trolley
     * @param  string                 $unitName
     * @param  int|string|null        $rowTrolleyQty         row's own trolley_qty column (post-fix rows)
     * @param  int|string|null        $fallbackTrolleyQty    project's CURRENT trolley_qty — used only
     *                                                        when the row has no trolley_qty of its own
     * @param  bool                   $isAlreadyScaled       the row's is_qty_trolley_scaled flag
     * @return float
     */
    public static function effectiveRequiredQuantity(
        $requiredQuantity,
        $mtrFor01NosTrolley,
        string $unitName,
        $rowTrolleyQty,
        $fallbackTrolleyQty,
        bool $isAlreadyScaled
    ): float {
        if ($isAlreadyScaled) {
            return (float) ($requiredQuantity ?? 0);
        }

        $trolleyQty = $rowTrolleyQty ?: ($fallbackTrolleyQty ?: 1);

        return self::scaledQuantity($requiredQuantity, $mtrFor01NosTrolley, $unitName, $trolleyQty);
    }

    /**
     * Same transparent legacy/post-fix handling as effectiveRequiredQuantity(), but
     * returns the resulting shortage (required - available, floored at 0).
     * `$availableQuantity` is never scaled (physical stock count).
     */
    public static function effectiveShortageQuantity(
        $availableQuantity,
        $requiredQuantity,
        $mtrFor01NosTrolley,
        string $unitName,
        $rowTrolleyQty,
        $fallbackTrolleyQty,
        bool $isAlreadyScaled
    ): float {
        $effectiveRequired = self::effectiveRequiredQuantity(
            $requiredQuantity, $mtrFor01NosTrolley, $unitName, $rowTrolleyQty, $fallbackTrolleyQty, $isAlreadyScaled
        );

        return self::shortage($effectiveRequired, $availableQuantity);
    }

    /**
     * Compute the final BOM total by summing rowTotal across all active items.
     *
     * Each item must expose: rate, quantity, mtr_for_01_nos_trolley, unit, unit_id.
     * Supports both Eloquent model objects and plain arrays.
     * If `unit` is empty, falls back to a UnitMaster lookup via `unit_id`.
     *
     * @param  \Illuminate\Support\Collection|array  $items
     * @param  int                                   $trolleyQty  from designs.trolley_qty (default 1)
     * @return float
     */
    public static function finalTotal($items, int $trolleyQty = 1): float
    {
        $total = 0.0;

        foreach ($items as $item) {
            if (is_array($item)) {
                $unitName = (string) ($item['unit'] ?? '');
                if ($unitName === '' && !empty($item['unit_id'])) {
                    $um       = \App\Models\UnitMaster::find((int) $item['unit_id']);
                    $unitName = $um ? (string) ($um->name ?? $um->unit_name ?? '') : '';
                }
                $rate = $item['rate'] ?? 0;
                $qty  = $item['quantity'] ?? 0;
                $mtr  = $item['mtr_for_01_nos_trolley'] ?? 0;
            } else {
                $unitName = (string) ($item->unit ?? '');
                if ($unitName === '' && !empty($item->unit_id)) {
                    $um       = \App\Models\UnitMaster::find((int) $item->unit_id);
                    $unitName = $um ? (string) ($um->name ?? $um->unit_name ?? '') : '';
                }
                $rate = $item->rate ?? 0;
                $qty  = $item->quantity ?? 0;
                $mtr  = $item->mtr_for_01_nos_trolley ?? 0;
            }

            $total += self::rowTotal($rate, $qty, $mtr, $unitName, $trolleyQty);
        }

        return $total;
    }

    /**
     * Returns true if $unitName (case-insensitive, trimmed) is in PIECE_UNITS.
     * Anything NOT in this list is treated as a length-based unit.
     */
    public static function isPieceUnit(string $unitName): bool
    {
        return in_array(strtoupper(trim($unitName)), self::PIECE_UNITS, true);
    }
}
