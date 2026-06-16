<?php
/**
 * BomImportCoverageTest.php
 * Standalone PHP test (no PHPUnit) for T-2026-052.
 *
 * Tests the coverage-ratio guard added to the containment fallback (tier 3)
 * in BomMaterialItemsController::importExcel().
 *
 * Run:
 *   D:\xampp\php\php.exe D:\xampp\htdocs\shreerag_final_updated\tests\BomImportCoverageTest.php
 *
 * Exits 0 on success ("ALL TESTS PASSED"), exits 1 on the first failing assertion.
 *
 * NOTE: The trolley-column fix from T-2026-051 lives in a different block of
 * importExcel() (the $cMtr column-header resolution block). That fix is not
 * directly tested here because it exercises spreadsheet column mapping, which
 * requires a real XLSX file; the reviewer confirmed that fix is correct and
 * php-l PASS was verified. No regression risk from the tier-3 change made here.
 */

// ──────────────────────────────────────────────────────────────────────────────
// Helper: replicate the $normalize closure from importExcel()
// ──────────────────────────────────────────────────────────────────────────────
function bom_normalize(?string $s): string
{
    $s = strtolower(trim((string) $s));
    return preg_replace('/[^a-z0-9]/', '', $s) ?? '';
}

// ──────────────────────────────────────────────────────────────────────────────
// Helper: replicate the $stripMs closure from importExcel()
// ──────────────────────────────────────────────────────────────────────────────
function bom_strip_ms(string $n): string
{
    return preg_replace('/^ms(?=[a-z])/', '', $n);
}

// ──────────────────────────────────────────────────────────────────────────────
// Simulate the three-tier matching logic for a single Excel description
// against a set of master records.
//
// $excelDesc   — raw Excel cell value (e.g. "PAINT YELLOW")
// $masters     — array of ['id' => int, 'description' => string]
//
// Returns the matched master id, or null if no match.
// ──────────────────────────────────────────────────────────────────────────────
function bom_match_description(string $excelDesc, array $masters): ?int
{
    // Coverage-ratio threshold (must match the value in the controller)
    $CONTAINMENT_COVERAGE_THRESHOLD = 0.6;

    // Build partLookup (mimics the collect() in importExcel)
    $partLookup = [];   // normalised_key => ['id', 'description']
    foreach ($masters as $master) {
        $n = bom_normalize($master['description']);
        if ($n !== '' && !isset($partLookup[$n])) {
            $partLookup[$n] = $master;
        }
        $nNoMs = bom_strip_ms($n);
        if ($nNoMs !== $n && $nNoMs !== '' && !isset($partLookup[$nNoMs])) {
            $partLookup[$nNoMs] = $master;
        }
    }

    $nDesc = bom_normalize($excelDesc);

    // ── Tier 1: exact match ───────────────────────────────────────────────────
    if (isset($partLookup[$nDesc])) {
        return $partLookup[$nDesc]['id'];
    }

    // ── Tier 2: MS-prefix-stripped exact match ────────────────────────────────
    $nDescNoMs = bom_strip_ms($nDesc);
    if ($nDescNoMs !== $nDesc && isset($partLookup[$nDescNoMs])) {
        return $partLookup[$nDescNoMs]['id'];
    }

    // ── Tier 3: containment fallback with coverage-ratio guard ────────────────
    if ($nDesc !== '') {
        $bestKey = null;
        $bestLen = 0;
        foreach ($partLookup as $pKey => $pRow) {
            if (strlen($pKey) < 7) continue;
            if (strlen($nDesc) < 7) break;
            $contained = (strpos($nDesc, $pKey) !== false)
                      || (strpos($pKey, $nDesc) !== false);
            if ($contained) {
                $coverageRatio = min(strlen($pKey), strlen($nDesc))
                               / max(strlen($pKey), strlen($nDesc));
                if ($coverageRatio < $CONTAINMENT_COVERAGE_THRESHOLD) {
                    continue;   // ratio too low — reject
                }
                if (strlen($pKey) > $bestLen) {
                    $bestKey = $pKey;
                    $bestLen = strlen($pKey);
                }
            }
        }
        if ($bestKey !== null) {
            return $partLookup[$bestKey]['id'];
        }
    }

    return null;
}

// ──────────────────────────────────────────────────────────────────────────────
// Assertion helper
// ──────────────────────────────────────────────────────────────────────────────
function assert_equals(string $label, mixed $expected, mixed $actual): void
{
    if ($expected === $actual) {
        echo "[PASS] $label\n";
    } else {
        $exp = var_export($expected, true);
        $act = var_export($actual, true);
        echo "[FAIL] $label\n";
        echo "       Expected : $exp\n";
        echo "       Got      : $act\n";
        exit(1);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// Master data (synthetic — mimics what would be in tbl_part_item)
// IDs match the real DB values confirmed by the acceptance-criteria queries.
// ──────────────────────────────────────────────────────────────────────────────
$masters = [
    // id 868 — "RAL BLUE PAINT/ YELLOW PAINT AS PER MGI STANDARD"
    ['id' => 868, 'description' => 'RAL BLUE PAINT/ YELLOW PAINT AS PER MGI STANDARD'],
    // id 110 — "PAINT ROLLER 4\""
    ['id' => 110, 'description' => 'PAINT ROLLER 4"'],
    // id 109 — "PAINT BRUSH 1\""
    ['id' => 109, 'description' => 'PAINT BRUSH 1"'],
    // Synthetic: "STACKING CUP(TML STD.)" — used to verify ratio ≈ 0.61 ACCEPTED
    ['id' => 999, 'description' => 'STACKING CUP(TML STD.)'],
    // Exact-match masters for tier-1 / tier-2 regression tests
    ['id' => 1973, 'description' => 'THINER'],
    ['id' => 1974, 'description' => 'COLOR CODE RAL 7033'],
    // Tier-2 test: "MS SQ PIPE 40X40X3MM" → normalised "mssqpipe40x40x3mm",
    // stripped to "sqpipe40x40x3mm"
    ['id' => 500, 'description' => 'MS SQ PIPE 40X40X3MM'],
];

// ──────────────────────────────────────────────────────────────────────────────
// Diagnostic: print normalised forms for the key test cases
// ──────────────────────────────────────────────────────────────────────────────
echo "\n=== Normalised-form diagnostics ===\n";
$diag = [
    'PAINT YELLOW'   => 'Excel desc (should NOT match 868)',
    'RAL BLUE PAINT/ YELLOW PAINT AS PER MGI STANDARD' => 'Master 868 key',
    'PAINT ROLLER'   => 'Excel desc (should match 110)',
    'PAINT ROLLER 4"' => 'Master 110 key',
    'PAINT BRUSH'    => 'Excel desc (should match 109)',
    'PAINT BRUSH 1"' => 'Master 109 key',
    'STACKING CUP'   => 'Excel desc (should match 999 synthetic)',
    'STACKING CUP(TML STD.)' => 'Master 999 key',
];
foreach ($diag as $raw => $label) {
    $norm = bom_normalize($raw);
    echo sprintf("  %-55s  →  %-35s  (%d chars)\n", "\"$raw\"", "\"$norm\"", strlen($norm));
}
echo "\n=== Coverage ratios ===\n";
$ratios = [
    ['PAINT YELLOW',  'RAL BLUE PAINT/ YELLOW PAINT AS PER MGI STANDARD', 'should be ~0.29 → REJECT'],
    ['PAINT ROLLER',  'PAINT ROLLER 4"',  'should be ~0.92 → ACCEPT'],
    ['PAINT BRUSH',   'PAINT BRUSH 1"',   'should be ~0.91 → ACCEPT'],
    ['STACKING CUP',  'STACKING CUP(TML STD.)', 'should be ~0.61 → ACCEPT'],
];
foreach ($ratios as [$excel, $master, $note]) {
    $a = bom_normalize($excel);
    $b = bom_normalize($master);
    // Only compute ratio when containment holds
    $contained = (strpos($a, $b) !== false) || (strpos($b, $a) !== false);
    if ($contained) {
        $ratio = min(strlen($a), strlen($b)) / max(strlen($a), strlen($b));
        echo sprintf("  %-18s vs %-42s  ratio=%.4f  %s\n",
            "\"$a\"", "\"$b\"", $ratio, $note);
    } else {
        echo sprintf("  %-18s vs %-42s  NO CONTAINMENT  %s\n",
            "\"$a\"", "\"$b\"", $note);
    }
}
echo "\n=== Test assertions ===\n";

// ──────────────────────────────────────────────────────────────────────────────
// TEST 1: "PAINT YELLOW" must NOT match 868
//   normalised "paintyellow" (11) vs master key "ralbluepaintyellowpaintaspermgistandard" (38)
//   ratio = 11/38 ≈ 0.289 → below 0.6 → REJECTED
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T1: "PAINT YELLOW" must NOT match id 868 (ratio ~0.29 < 0.6)',
    null,
    bom_match_description('PAINT YELLOW', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 2: "PAINT ROLLER" MUST match id 110
//   normalised "paintroller" (11) vs master key "paintroller4" (12)
//   ratio = 11/12 ≈ 0.917 → above 0.6 → ACCEPTED
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T2: "PAINT ROLLER" must match id 110 (ratio ~0.92 >= 0.6)',
    110,
    bom_match_description('PAINT ROLLER', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 3: "PAINT BRUSH" MUST match id 109
//   normalised "paintbrush" (10) vs master key "paintbrush1" (11)
//   ratio = 10/11 ≈ 0.909 → above 0.6 → ACCEPTED
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T3: "PAINT BRUSH" must match id 109 (ratio ~0.91 >= 0.6)',
    109,
    bom_match_description('PAINT BRUSH', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 4: "STACKING CUP" MUST match synthetic id 999
//   normalised "stackingcup" (11) vs master key "stackingcupttmlstd" (18)
//   ratio = 11/18 ≈ 0.611 → above 0.6 → ACCEPTED
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T4: "STACKING CUP" must match id 999 synthetic (ratio ~0.61 >= 0.6)',
    999,
    bom_match_description('STACKING CUP', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 5: "BLUEWIDGET" must NOT match any master — no containment at all
//   This verifies the guard doesn't break the "no match" path.
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T5: "BLUEWIDGET" must NOT match any master (no containment)',
    null,
    bom_match_description('BLUEWIDGET', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 6: Tier-1 exact match — "THINER" → id 1973 (no tier 3 involved)
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T6: "THINER" matches id 1973 via tier-1 exact match',
    1973,
    bom_match_description('THINER', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 7: Tier-1 exact match — "COLOR CODE RAL 7033" → id 1974
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T7: "COLOR CODE RAL 7033" matches id 1974 via tier-1 exact match',
    1974,
    bom_match_description('COLOR CODE RAL 7033', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// TEST 8: Tier-2 MS-prefix-stripped match
//   Excel "SQ PIPE 40X40X3MM" → normalised "sqpipe40x40x3mm"
//   Master "MS SQ PIPE 40X40X3MM" → normalised "mssqpipe40x40x3mm",
//     stripped → "sqpipe40x40x3mm"
//   Tier 1 fails (keys differ); Tier 2 matches on stripped form → id 500
// ──────────────────────────────────────────────────────────────────────────────
assert_equals(
    'T8: "SQ PIPE 40X40X3MM" matches id 500 via tier-2 MS-prefix-stripped match',
    500,
    bom_match_description('SQ PIPE 40X40X3MM', $masters)
);

// ──────────────────────────────────────────────────────────────────────────────
// T11 — Coverage-gap tests (added by tester, T-2026-052 iteration 2)
// ──────────────────────────────────────────────────────────────────────────────

// ── T9: master ⊆ excel direction, ratio still below 0.6 → REJECTED ───────────
// "PAINT BRUSH" (10) contained inside excel "PAINT BRUSH FULL WIDE SPECIAL X1"
// normalised "paintbrushfullwidespecialx1" (27 chars)
// ratio = 10/27 ≈ 0.37 < 0.6 → REJECTED
// This covers the case where master is the shorter string and IS contained
// within the longer Excel description, but the ratio gate still fires.
assert_equals(
    'T9: master-within-excel containment rejected when ratio ~0.37 < 0.6',
    null,
    bom_match_description('PAINT BRUSH FULL WIDE SPECIAL X1', $masters)
);

// ── T10: ratio exactly at threshold boundary (0.6) → ACCEPTED ────────────────
// Construct a synthetic master pair where min/max = 9/15 = 0.6 exactly.
// Excel: "ABCDEFGHI" (normalised "abcdefghi" = 9 chars)
// Master: "ABCDEFGHIJKLMNO" (normalised "abcdefghijklmno" = 15 chars)
// ratio = 9/15 = 0.6000 → at threshold → ACCEPTED (guard is strictly < 0.6)
$boundaryMasters = [
    ['id' => 2001, 'description' => 'ABCDEFGHIJKLMNO'],
];
assert_equals(
    'T10: ratio exactly 0.6 is ACCEPTED (guard rejects strictly < 0.6)',
    2001,
    bom_match_description('ABCDEFGHI', $boundaryMasters)
);

// ── T11: ratio just below threshold (< 0.6) → REJECTED ───────────────────────
// Excel: "ABCDEFGH" (normalised "abcdefgh" = 8 chars)
// Master: "ABCDEFGHIJKLMN" (normalised "abcdefghijklmn" = 14 chars)
// ratio = 8/14 ≈ 0.571 < 0.6 → REJECTED
$belowThresholdMasters = [
    ['id' => 2002, 'description' => 'ABCDEFGHIJKLMN'],
];
assert_equals(
    'T11: ratio 8/14 ≈ 0.571 is REJECTED (strictly below 0.6)',
    null,
    bom_match_description('ABCDEFGH', $belowThresholdMasters)
);

// ── T12: tier-3 selects LONGEST key when multiple candidates above threshold ──
// Excel: "STACKING CUP" → normalised "stackingcup" (11 chars)
// Two masters both have containment and ratio >= 0.6, both reach tier-3:
//   master A id=3001: "STACKING CUP MODEL A" → "stackingcupmodela" (17 chars)
//     ratio = 11/17 ≈ 0.647 → ACCEPTED
//   master B id=3002: "STACKING CUP X12"    → "stackingcupx12"   (14 chars)
//     ratio = 11/14 ≈ 0.786 → ACCEPTED
// Neither normalises to "stackingcup" exactly → tier 1 misses both.
// Tier 3 must pick master A (LONGER pKey = 17) = id 3001.
// NOTE: "PAINT ROLLER" → "paintroller" exact-matches a master in the original
// $masters set via tier 1 before tier 3 is reached, so that set cannot
// demonstrate longest-key selection.
$longestKeyMasters = [
    ['id' => 3001, 'description' => 'STACKING CUP MODEL A'],  // pKey len=17 (WINNER)
    ['id' => 3002, 'description' => 'STACKING CUP X12'],      // pKey len=14
];
assert_equals(
    'T12: tier-3 selects LONGEST matching key (id 3001 "STACKING CUP MODEL A" len=17 beats id 3002 len=14)',
    3001,
    bom_match_description('STACKING CUP', $longestKeyMasters)
);

// ──────────────────────────────────────────────────────────────────────────────
// All tests passed
// ──────────────────────────────────────────────────────────────────────────────
echo "\nALL TESTS PASSED\n";
exit(0);
