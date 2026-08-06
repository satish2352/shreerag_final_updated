<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Support\BomTotalCalculator;

/**
 * Unit tests for BomTotalCalculator — the server-side mirror of the JS formula
 * in resources/views/organizations/common/bom-material-items-modal.blade.php.
 *
 * T-2026-046: Created alongside the fix for the wrong Total Estimation Amount
 * in EstimationController and BomMaterialItemsRepository.
 *
 * JS reference formula:
 *   PIECE_UNITS    = ['NOS','PCS','SET','EACH']
 *   t              = parseInt(trolley_qty) || 1;  if (t < 1) t = 1;
 *   baseMultiplier = isPieceUnit(unit) ? quantity : mtr_for_01_nos_trolley
 *   rowTotal       = rate * baseMultiplier * t
 */
class BomTotalCalculatorTest extends TestCase
{
    // ------------------------------------------------------------------
    // rowTotal() tests
    // ------------------------------------------------------------------

    /** Piece unit NOS: baseMultiplier = quantity. Rate×qty×trolley = 300×2×8 = 4800. */
    public function test_piece_unit_nos_uses_quantity(): void
    {
        $result = BomTotalCalculator::rowTotal(300, 2, 5, 'NOS', 8);
        $this->assertEqualsWithDelta(4800.0, $result, 0.001);
    }

    /** Length unit MTR: baseMultiplier = mtr_for_01_nos_trolley. Rate×mtr×trolley = 100×3×4 = 1200. */
    public function test_length_unit_mtr_uses_mtr_for_01_nos_trolley(): void
    {
        $result = BomTotalCalculator::rowTotal(100, 99, 3, 'MTR', 4);
        $this->assertEqualsWithDelta(1200.0, $result, 0.001);
    }

    /** METER (master unit name, not MTR) is also a length unit. 50×2×3 = 300. */
    public function test_meter_unit_is_also_length(): void
    {
        $result = BomTotalCalculator::rowTotal(50, 10, 2, 'METER', 3);
        $this->assertEqualsWithDelta(300.0, $result, 0.001);
    }

    /** trolley_qty = 0 must be clamped to 1 (mirrors JS: parseInt(0) || 1 = 1). */
    public function test_zero_trolley_qty_clamped_to_1(): void
    {
        // 200 × 3 × 1 = 600
        $result = BomTotalCalculator::rowTotal(200, 3, 4, 'NOS', 0);
        $this->assertEqualsWithDelta(600.0, $result, 0.001);
    }

    /** Negative trolley_qty also clamped to 1. */
    public function test_negative_trolley_qty_clamped_to_1(): void
    {
        $result = BomTotalCalculator::rowTotal(200, 3, 4, 'NOS', -5);
        $this->assertEqualsWithDelta(600.0, $result, 0.001);
    }

    /** Zero quantity with piece unit → 0. */
    public function test_zero_quantity_returns_zero(): void
    {
        $result = BomTotalCalculator::rowTotal(300, 0, 0, 'NOS', 5);
        $this->assertEqualsWithDelta(0.0, $result, 0.001);
    }

    /** Zero rate always → 0. */
    public function test_zero_rate_returns_zero(): void
    {
        $result = BomTotalCalculator::rowTotal(0, 10, 3, 'MTR', 4);
        $this->assertEqualsWithDelta(0.0, $result, 0.001);
    }

    /** Lowercase unit name 'nos' detected as piece unit. */
    public function test_lowercase_unit_nos_detected_as_piece(): void
    {
        // 100 × qty=5 × trolley=2 = 1000
        $result = BomTotalCalculator::rowTotal(100, 5, 20, 'nos', 2);
        $this->assertEqualsWithDelta(1000.0, $result, 0.001);
    }

    /** Leading and trailing whitespace in unit name is trimmed. */
    public function test_unit_name_with_leading_trailing_whitespace(): void
    {
        $result = BomTotalCalculator::rowTotal(100, 5, 20, '  NOS  ', 2);
        $this->assertEqualsWithDelta(1000.0, $result, 0.001);
    }

    /** PCS is a piece unit. */
    public function test_pcs_is_piece_unit(): void
    {
        // 100 × qty=2 × trolley=3 = 600
        $result = BomTotalCalculator::rowTotal(100, 2, 99, 'PCS', 3);
        $this->assertEqualsWithDelta(600.0, $result, 0.001);
    }

    /** SET is a piece unit. */
    public function test_set_is_piece_unit(): void
    {
        $result = BomTotalCalculator::rowTotal(100, 2, 99, 'SET', 3);
        $this->assertEqualsWithDelta(600.0, $result, 0.001);
    }

    /** EACH is a piece unit. */
    public function test_each_is_piece_unit(): void
    {
        $result = BomTotalCalculator::rowTotal(100, 2, 99, 'EACH', 3);
        $this->assertEqualsWithDelta(600.0, $result, 0.001);
    }

    /** Unknown unit (e.g. 'KG') falls through to length-unit path. */
    public function test_unknown_unit_treated_as_length(): void
    {
        // KG not in PIECE_UNITS → uses mtr. 50 × mtr=4 × trolley=2 = 400
        $result = BomTotalCalculator::rowTotal(50, 10, 4, 'KG', 2);
        $this->assertEqualsWithDelta(400.0, $result, 0.001);
    }

    // ------------------------------------------------------------------
    // finalTotal() tests
    // ------------------------------------------------------------------

    /**
     * Screenshot fixture: MODEL 9 BUMPER + one length-unit row.
     * NOS row:  300 × qty=2   × 8 = 4800
     * MTR row:  100 × mtr=3   × 8 = 2400
     * Total = 7200
     */
    public function test_final_total_sums_mixed_rows(): void
    {
        $items = collect([
            (object)[
                'rate'                  => 300,
                'quantity'              => 2,
                'mtr_for_01_nos_trolley' => 0,
                'unit'                  => 'NOS',
                'unit_id'               => null,
            ],
            (object)[
                'rate'                  => 100,
                'quantity'              => 99,
                'mtr_for_01_nos_trolley' => 3,
                'unit'                  => 'MTR',
                'unit_id'               => null,
            ],
        ]);

        $result = BomTotalCalculator::finalTotal($items, 8);
        $this->assertEqualsWithDelta(7200.0, $result, 0.001);
    }

    /** finalTotal with empty collection returns 0. */
    public function test_final_total_empty_collection_returns_zero(): void
    {
        $result = BomTotalCalculator::finalTotal(collect([]), 5);
        $this->assertEqualsWithDelta(0.0, $result, 0.001);
    }

    /** finalTotal defaults trolleyQty to 1 when not passed. */
    public function test_final_total_default_trolley_qty_is_1(): void
    {
        $items = collect([
            (object)[
                'rate'                  => 200,
                'quantity'              => 3,
                'mtr_for_01_nos_trolley' => 0,
                'unit'                  => 'NOS',
                'unit_id'               => null,
            ],
        ]);
        // 200 × 3 × 1 = 600
        $result = BomTotalCalculator::finalTotal($items);
        $this->assertEqualsWithDelta(600.0, $result, 0.001);
    }

    /** finalTotal accepts plain array items (not just objects). */
    public function test_final_total_accepts_array_items(): void
    {
        $items = [
            [
                'rate'                  => 500,
                'quantity'              => 2,
                'mtr_for_01_nos_trolley' => 0,
                'unit'                  => 'NOS',
                'unit_id'               => null,
            ],
        ];
        // 500 × 2 × 4 = 4000
        $result = BomTotalCalculator::finalTotal($items, 4);
        $this->assertEqualsWithDelta(4000.0, $result, 0.001);
    }

    // ------------------------------------------------------------------
    // isPieceUnit() tests
    // ------------------------------------------------------------------

    public function test_is_piece_unit_returns_true_for_nos(): void
    {
        $this->assertTrue(BomTotalCalculator::isPieceUnit('NOS'));
        $this->assertTrue(BomTotalCalculator::isPieceUnit('nos'));
        $this->assertTrue(BomTotalCalculator::isPieceUnit(' NOS '));
    }

    public function test_is_piece_unit_returns_false_for_mtr(): void
    {
        $this->assertFalse(BomTotalCalculator::isPieceUnit('MTR'));
        $this->assertFalse(BomTotalCalculator::isPieceUnit('METER'));
        $this->assertFalse(BomTotalCalculator::isPieceUnit('mtr'));
    }

    public function test_is_piece_unit_returns_false_for_empty_string(): void
    {
        $this->assertFalse(BomTotalCalculator::isPieceUnit(''));
    }

    // ------------------------------------------------------------------
    // baseMultiplier() tests (T-2026-059)
    // ------------------------------------------------------------------

    /** Piece unit: baseMultiplier = quantity, mtr_for_01_nos_trolley is ignored. */
    public function test_base_multiplier_piece_unit_uses_quantity(): void
    {
        $result = BomTotalCalculator::baseMultiplier(6, 99, 'NOS');
        $this->assertEqualsWithDelta(6.0, $result, 0.001);
    }

    /** Length unit: baseMultiplier = mtr_for_01_nos_trolley, quantity is ignored. */
    public function test_base_multiplier_length_unit_uses_mtr_for_01_nos_trolley(): void
    {
        $result = BomTotalCalculator::baseMultiplier(99, 4, 'MTR');
        $this->assertEqualsWithDelta(4.0, $result, 0.001);
    }

    /** Null inputs default to 0 for whichever branch is selected. */
    public function test_base_multiplier_null_inputs_default_to_zero(): void
    {
        $this->assertEqualsWithDelta(0.0, BomTotalCalculator::baseMultiplier(null, 5, 'NOS'), 0.001);
        $this->assertEqualsWithDelta(0.0, BomTotalCalculator::baseMultiplier(5, null, 'MTR'), 0.001);
    }

    // ------------------------------------------------------------------
    // scaledQuantity() tests (T-2026-059)
    // ------------------------------------------------------------------

    /** Piece unit, trolleyQty > 1: quantity × trolleyQty. */
    public function test_scaled_quantity_piece_unit_trolley_qty_greater_than_1(): void
    {
        // 6 (qty) × 3 (trolley) = 18
        $result = BomTotalCalculator::scaledQuantity(6, 99, 'NOS', 3);
        $this->assertEqualsWithDelta(18.0, $result, 0.001);
    }

    /** Piece unit, trolleyQty = 1: quantity is returned unscaled. */
    public function test_scaled_quantity_piece_unit_trolley_qty_1(): void
    {
        $result = BomTotalCalculator::scaledQuantity(6, 99, 'NOS', 1);
        $this->assertEqualsWithDelta(6.0, $result, 0.001);
    }

    /** Length unit, trolleyQty > 1: mtr_for_01_nos_trolley × trolleyQty. */
    public function test_scaled_quantity_length_unit_trolley_qty_greater_than_1(): void
    {
        // 4 (mtr) × 3 (trolley) = 12
        $result = BomTotalCalculator::scaledQuantity(99, 4, 'MTR', 3);
        $this->assertEqualsWithDelta(12.0, $result, 0.001);
    }

    /** Length unit, trolleyQty = 1: mtr_for_01_nos_trolley is returned unscaled. */
    public function test_scaled_quantity_length_unit_trolley_qty_1(): void
    {
        $result = BomTotalCalculator::scaledQuantity(99, 4, 'MTR', 1);
        $this->assertEqualsWithDelta(4.0, $result, 0.001);
    }

    /** trolleyQty of 0/negative is clamped to 1, mirroring rowTotal()'s own clamp. */
    public function test_scaled_quantity_clamps_zero_and_negative_trolley_qty(): void
    {
        $this->assertEqualsWithDelta(6.0, BomTotalCalculator::scaledQuantity(6, 99, 'NOS', 0), 0.001);
        $this->assertEqualsWithDelta(6.0, BomTotalCalculator::scaledQuantity(6, 99, 'NOS', -4), 0.001);
    }

    // ------------------------------------------------------------------
    // shortage() tests (T-2026-059)
    // ------------------------------------------------------------------

    /** required > available: shortage = required - available. */
    public function test_shortage_positive_difference(): void
    {
        $result = BomTotalCalculator::shortage(18, 5);
        $this->assertEqualsWithDelta(13.0, $result, 0.001);
    }

    /** required <= available: shortage floors at 0, never negative. */
    public function test_shortage_floors_at_zero_when_available_exceeds_required(): void
    {
        $result = BomTotalCalculator::shortage(5, 18);
        $this->assertEqualsWithDelta(0.0, $result, 0.001);
    }

    /** required == available: shortage is exactly 0. */
    public function test_shortage_zero_when_equal(): void
    {
        $result = BomTotalCalculator::shortage(10, 10);
        $this->assertEqualsWithDelta(0.0, $result, 0.001);
    }

    /** Null inputs default to 0 on both sides. */
    public function test_shortage_null_inputs_default_to_zero(): void
    {
        $this->assertEqualsWithDelta(0.0, BomTotalCalculator::shortage(null, null), 0.001);
        $this->assertEqualsWithDelta(5.0, BomTotalCalculator::shortage(5, null), 0.001);
    }

    // ------------------------------------------------------------------
    // effectiveRequiredQuantity() tests (T-2026-059)
    // ------------------------------------------------------------------

    /**
     * isAlreadyScaled = true (post-T-2026-059 row): the stored required_quantity
     * IS the final figure — used as-is, never re-scaled (piece unit).
     */
    public function test_effective_required_quantity_already_scaled_piece_unit_used_verbatim(): void
    {
        // Stored required_quantity is already 18 (6 qty × 3 trolley, applied at write time).
        // rowTrolleyQty/fallbackTrolleyQty must be IGNORED entirely when isAlreadyScaled=true —
        // re-applying trolleyQty=3 here would wrongly produce 54 if the flag were not honoured.
        $result = BomTotalCalculator::effectiveRequiredQuantity(18, 99, 'NOS', 3, 3, true);
        $this->assertEqualsWithDelta(18.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = true (post-T-2026-059 row): also used as-is for a length unit —
     * mtr_for_01_nos_trolley must be ignored entirely (already-scaled rows are final
     * regardless of unit type).
     */
    public function test_effective_required_quantity_already_scaled_length_unit_used_verbatim(): void
    {
        $result = BomTotalCalculator::effectiveRequiredQuantity(12, 4, 'MTR', 3, 3, true);
        $this->assertEqualsWithDelta(12.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = false (legacy pre-T-2026-059 row), piece unit: retroactively
     * recomputed as requiredQuantity (== raw per-1 quantity for a legacy row) × the
     * row's OWN trolley_qty when present.
     */
    public function test_effective_required_quantity_legacy_piece_unit_uses_row_trolley_qty(): void
    {
        // Legacy row: required_quantity stored raw = 6 (per-1-trolley basis), row's own
        // trolley_qty = 4 → effective = 6 × 4 = 24.
        $result = BomTotalCalculator::effectiveRequiredQuantity(6, 99, 'NOS', 4, 1, false);
        $this->assertEqualsWithDelta(24.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = false, length unit: legacy rows scale off
     * mtr_for_01_nos_trolley (NOT required_quantity) once the length branch is
     * selected — matches scaledQuantity()'s own unit-aware branching.
     */
    public function test_effective_required_quantity_legacy_length_unit_uses_mtr_for_01_nos_trolley(): void
    {
        // mtr_for_01_nos_trolley = 4, row trolley_qty = 3 → effective = 4 × 3 = 12
        // (required_quantity=99 here is deliberately a decoy value that must be ignored).
        $result = BomTotalCalculator::effectiveRequiredQuantity(99, 4, 'MTR', 3, 1, false);
        $this->assertEqualsWithDelta(12.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = false, row has no trolley_qty of its own (null/0): falls
     * back to the project's CURRENT trolley_qty.
     */
    public function test_effective_required_quantity_legacy_falls_back_to_project_trolley_qty_when_row_has_none(): void
    {
        // rowTrolleyQty = null → falls back to fallbackTrolleyQty = 5. 6 × 5 = 30.
        $result = BomTotalCalculator::effectiveRequiredQuantity(6, 99, 'NOS', null, 5, false);
        $this->assertEqualsWithDelta(30.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = false, neither the row nor the fallback provides a
     * trolley_qty (both null/0): defaults to 1 (matches scaledQuantity()'s own
     * max(1, ...) clamp).
     */
    public function test_effective_required_quantity_legacy_defaults_to_trolley_qty_1_when_nothing_provided(): void
    {
        $result = BomTotalCalculator::effectiveRequiredQuantity(6, 99, 'NOS', null, null, false);
        $this->assertEqualsWithDelta(6.0, $result, 0.001);
    }

    // ------------------------------------------------------------------
    // effectiveShortageQuantity() tests (T-2026-059)
    // ------------------------------------------------------------------

    /** isAlreadyScaled = true: shortage = stored (final) required - available, floored at 0. */
    public function test_effective_shortage_quantity_already_scaled(): void
    {
        // required (already scaled) = 18, available = 5 → shortage = 13
        $result = BomTotalCalculator::effectiveShortageQuantity(5, 18, 99, 'NOS', 3, 3, true);
        $this->assertEqualsWithDelta(13.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = false, piece unit: shortage is computed against the
     * RETROACTIVELY-scaled required quantity, not the raw stored value.
     */
    public function test_effective_shortage_quantity_legacy_piece_unit_uses_scaled_required(): void
    {
        // Legacy required_quantity=6 raw, row trolley_qty=4 → effective required = 24.
        // available = 9 → shortage = 24 - 9 = 15.
        $result = BomTotalCalculator::effectiveShortageQuantity(9, 6, 99, 'NOS', 4, 1, false);
        $this->assertEqualsWithDelta(15.0, $result, 0.001);
    }

    /**
     * isAlreadyScaled = false, length unit: same retroactive-scaling behaviour
     * via mtr_for_01_nos_trolley instead of required_quantity.
     */
    public function test_effective_shortage_quantity_legacy_length_unit_uses_scaled_mtr(): void
    {
        // mtr_for_01_nos_trolley=4, row trolley_qty=3 → effective required = 12.
        // available = 2 → shortage = 12 - 2 = 10.
        $result = BomTotalCalculator::effectiveShortageQuantity(2, 99, 4, 'MTR', 3, 1, false);
        $this->assertEqualsWithDelta(10.0, $result, 0.001);
    }

    /** effectiveShortageQuantity floors at 0 even in the legacy/rescaled branch. */
    public function test_effective_shortage_quantity_legacy_floors_at_zero(): void
    {
        // Legacy required=6, row trolley_qty=1 → effective required = 6. available = 50
        // (far exceeds required) → shortage must floor at 0, never go negative.
        $result = BomTotalCalculator::effectiveShortageQuantity(50, 6, 99, 'NOS', 1, 1, false);
        $this->assertEqualsWithDelta(0.0, $result, 0.001);
    }
}
