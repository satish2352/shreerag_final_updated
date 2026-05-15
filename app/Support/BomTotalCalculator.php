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
        // Mirror JS: parseInt(trolley_qty) || 1; if (t < 1) t = 1;
        $t = max(1, (int) $trolleyQty);
        $r = (float) ($rate ?? 0);

        $baseMultiplier = self::isPieceUnit($unitName)
            ? (float) ($quantity ?? 0)
            : (float) ($mtrFor01NosTrolley ?? 0);

        return $r * $baseMultiplier * $t;
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
