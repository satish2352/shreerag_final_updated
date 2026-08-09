<?php

namespace App\Http\Controllers\Organizations\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Organizations\Common\BomMaterialItemsService;
use App\Models\BomMaterialItem;
use App\Models\EstimationModel;
use App\Models\PartItem;
use App\Models\UnitMaster;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BomMaterialItemsController extends Controller
{
    protected BomMaterialItemsService $service;

    public function __construct()
    {
        $this->service = new BomMaterialItemsService();
    }

    /**
     * POST /common/import-bom-excel
     * Parses an uploaded BOM Excel (xls/xlsx) and APPENDS rows into bom_material_items
     * for the given business_details_id + design_id. The modal then displays them
     * for preview / edit / save.
     *
     * Expected sheet layout (matches the user's MATERIAL INDENT template):
     *   - First few rows: title / customer / date metadata (skipped)
     *   - Header row contains: Number | Product Description | Length | Quantity |
     *                          Total in mm | Mtr for 01 Nos Trolley | Unit | Rate
     *   - Data rows below the header
     *
     * Recognises common header variants:
     *   - "Mtr for 01 Nos Trolley" / "QTY FOR SINGLE TROLLEY" / "Mtr per Trolley"
     *   - "Unit" / "UOM"
     * Unit synonym resolution: "MTR" matches a master unit named "METER" and vice versa.
     * Part-item description matching is fuzzy and bidirectional (master ⊆ excel OR excel ⊆ master).
     *
     * Returns JSON with the inserted row count so the caller can refresh the modal.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file'          => 'required|file|mimes:xls,xlsx|max:5120',
            'business_id'         => 'required|integer|min:1',
            'business_details_id' => 'required|integer|min:1',
            'design_id'           => 'required|integer|min:1',
            'dept_role_id'        => 'nullable|integer',
        ]);

        $businessId        = (int) $request->input('business_id');
        $businessDetailsId = (int) $request->input('business_details_id');
        $designId          = (int) $request->input('design_id');
        $deptRoleId        = (int) ($request->input('dept_role_id') ?: 3); // default = design
        $userId            = (int) (session('user_id') ?: 0);

        try {
            $spreadsheet = IOFactory::load($request->file('excel_file')->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false); // 0-indexed

            // ---- Locate the header row by scanning for "Product Description" ----
            // Header cells can be multi-line ("L in mm\n(Approx)") — collapse any
            // whitespace runs (newlines, tabs, multi-space) into a single space so
            // the alias lookup below is forgiving.
            $headerIdx   = -1;
            $colMap      = [];
            $normFn      = fn($v) => trim((string) preg_replace('/\s+/u', ' ', strtolower((string) $v)));
            foreach ($rows as $i => $row) {
                $normalized = array_map($normFn, $row);
                if (in_array('product description', $normalized, true)) {
                    $headerIdx = $i;
                    foreach ($normalized as $cIdx => $label) {
                        if ($label === '' || $label === null) continue;
                        $colMap[$label] = $cIdx;
                    }
                    break;
                }
            }

            if ($headerIdx === -1) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Could not locate header row. Excel must contain a row with "Product Description" header.',
                ], 422);
            }

            // ---- Resolve column indexes (tolerant to header label variations) ----
            $col = function (array $aliases) use ($colMap) {
                foreach ($aliases as $a) {
                    if (isset($colMap[strtolower($a)])) return $colMap[strtolower($a)];
                }
                return null;
            };
            $cSerial  = $col(['number', 'sr', 'sr.', 'sr no', 'sr. no.']);
            $cDesc    = $col(['product description']);
            $cLen     = $col(['length']);
            $cQty     = $col(['quantity', 'qty']);
            $cTotQty  = $col(['total quantity', 'total qty', 'total quantity ']);
            $cTotMm   = $col([
                'total in mm', 'total mm',
                'l in mm', 'l in mm (approx)',
                'length in mm', 'length mm',
                'len in mm', 'l mm',
            ]);
            $cMtr     = $col([
                'mtr for 01 nos trolley',
                'mtr for 01 nos',
                'mtr for 1 nos trolley',
                'mtr per trolley',
                'qty for single trolley',
                'qty for 1 trolley',
                'qty for 01 trolley',
                'qty for 1 nos trolley',
                'qty for 01 nos trolley',
                // singular "NO" variants (e.g. "QTY FOR 1 NO TROLLEY")
                'qty for 1 no trolley',
                'qty for 01 no trolley',
                'qty for single no trolley',
                'mtr for 1 no trolley',
                'mtr for 01 no trolley',
                'quantity for single trolley',
                'mtr/nos for single trolley',
                'mtr/nos per trolley',
                'mtr per single trolley',
            ]);
            // Regex fallback: match "qty/mtr ... 1 no(s) trolley" or "... single ... trolley"
            // to handle future header wording variations without silent failure.
            if ($cMtr === null) {
                foreach ($colMap as $label => $idx) {
                    if (preg_match('/(?:mtr|qty|quantity)\b.*\b0*1\s*nos?\s*trolley/i', $label)
                        || preg_match('/(?:mtr|qty|quantity)\b.*\bsingle\b.*\btrolley/i', $label)) {
                        $cMtr = $idx;
                        break;
                    }
                }
            }
            $cUnit    = $col(['unit', 'uom', 'u.o.m', 'u.o.m.']);
            $cRate    = $col(['rate']);

            // Column G is the column immediately after $cMtr — contains the N-trolley total.
            // Its header text contains the trolley count, e.g. "QTY FOR 08 NOS TROLLEY" → 8.
            $cNTrolley = ($cMtr !== null) ? $cMtr + 1 : null;

            if ($cDesc === null || ($cQty === null && $cTotQty === null)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Excel must contain at least Product Description and Quantity columns.',
                ], 422);
            }

            // ---- Cache part-item & unit masters for fast lookup ----
            // The Excel descriptions and the part-master descriptions don't match
            // exactly:  Excel "SQ PIPE 40X40X3MM"  vs  master "MS SQ PIPE 40 X 40 X 3 MM".
            // We build TWO lookup keys per master row so both spellings hit:
            //   (1) loose normalised  → lowercase + strip ALL non-alphanumeric chars
            //   (2) same as (1) but with leading "ms" prefix dropped
            // Includes basic_rate so we can fall back to it when the Excel Rate cell is blank.
            $normalize = function (?string $s): string {
                $s = strtolower(trim((string) $s));
                // strip everything that isn't a letter or digit (spaces, dots, dashes, parens, etc.)
                return preg_replace('/[^a-z0-9]/', '', $s) ?? '';
            };
            $stripMs = function (string $n): string {
                // remove a leading "ms" only when followed by another letter/word boundary
                return preg_replace('/^ms(?=[a-z])/', '', $n);
            };

            $partLookup = collect();
            PartItem::where('is_active', true)
                ->where('is_deleted', false)
                ->select('id', 'description', 'basic_rate')
                ->get()
                ->each(function ($p) use ($partLookup, $normalize, $stripMs) {
                    $n = $normalize($p->description);
                    if ($n !== '' && !$partLookup->has($n))           $partLookup->put($n, $p);
                    $nNoMs = $stripMs($n);
                    if ($nNoMs !== $n && $nNoMs !== '' && !$partLookup->has($nNoMs)) {
                        $partLookup->put($nNoMs, $p);
                    }
                });

            $unitLookup = UnitMaster::where('is_active', true)
                ->where('is_deleted', false)
                ->select('id', 'name')
                ->get()
                ->keyBy(fn($u) => strtolower(trim($u->name)));

            // ---- Unit synonym resolver ----
            // Excel templates often write short abbreviations ("MTR", "NOS") while the
            // master table stores full words ("METER", "NUMBERS"). This helper tries a
            // direct lookup, then expands the Excel value to its synonyms, then checks
            // whether the Excel value itself IS a synonym of a master name.
            $UNIT_SYNONYMS = [
                'mtr'    => ['meter', 'metre', 'mtrs', 'meters'],
                'meter'  => ['mtr', 'metre', 'mtrs', 'meters'],
                'metre'  => ['mtr', 'meter', 'mtrs', 'meters'],
                'nos'    => ['no', 'nos.', 'numbers', 'pcs', 'pieces', 'each'],
                'pcs'    => ['piece', 'pieces', 'nos'],
                'kg'     => ['kgs', 'kilogram', 'kilograms'],
                'kgs'    => ['kg', 'kilogram', 'kilograms'],
                'ft'     => ['feet', 'foot'],
                'feet'   => ['ft', 'foot'],
            ];
            $resolveUnitId = function (string $txt) use ($unitLookup, $UNIT_SYNONYMS): ?int {
                $key = strtolower(trim($txt));
                if ($key === '') return null;
                // 1) direct match
                if ($u = $unitLookup->get($key)) return (int) $u->id;
                // 2) synonym expansion — try each synonym of the Excel value
                if (isset($UNIT_SYNONYMS[$key])) {
                    foreach ($UNIT_SYNONYMS[$key] as $syn) {
                        if ($u = $unitLookup->get($syn)) return (int) $u->id;
                    }
                }
                // 3) reverse synonym — Excel text appears in the synonym list of some master
                foreach ($UNIT_SYNONYMS as $canon => $syns) {
                    if (in_array($key, $syns, true) && ($u = $unitLookup->get($canon))) {
                        return (int) $u->id;
                    }
                }
                return null;
            };

            // Existing serial_no max so appended rows continue numbering
            $maxSerial = (int) BomMaterialItem::where('business_details_id', $businessDetailsId)
                ->where('design_id', $designId)
                ->where('is_deleted', 0)
                ->max('serial_no');
            $nextSerial = $maxSerial + 1;

            // ---- Index of existing rows for smart-merge: key = "desc|length" ----
            // Same description + same length → quantities are summed (no duplicate row).
            // Different description-or-length → appended as a new row.
            $existing = BomMaterialItem::where('business_details_id', $businessDetailsId)
                ->where('design_id', $designId)
                ->where('is_deleted', 0)
                ->get();

            $mergeKey = function (?string $desc, $len): string {
                $d = strtolower(trim((string) $desc));
                $l = ($len === null || $len === '' || !is_numeric($len)) ? '' : rtrim(rtrim(number_format((float) $len, 3, '.', ''), '0'), '.');
                return $d . '|' . $l;
            };

            $existingByKey = [];
            foreach ($existing as $row) {
                $existingByKey[$mergeKey($row->product_description, $row->length)] = $row;
            }

            $inserted = 0;
            $merged   = 0;
            $skipped  = 0;

            // ---- Parse trolley count from spreadsheet ----
            // Primary source: the column G (cNTrolley) header cell text.
            //   Pattern: /(\d+)\s*(?:no[s]?\s+)?trolley/i  e.g. "QTY FOR 08 NOS TROLLEY" → 8, "QTY FOR 36 NO TROLLEY" → 36
            // Fallback: scan rows ABOVE the header for metadata matching "TROLLEY QTY:- 08 NOS".
            //   Pattern: /trolley\s*(?:qty|quantity)\s*[:\-]*\s*(\d+)/i
            // Default: 1 (single-trolley — preserves backward-compatible behaviour).
            $trolleyQty = 1;

            // Primary: column G header cell text
            if ($cNTrolley !== null && isset($rows[$headerIdx][$cNTrolley])) {
                $headerCellText = strtolower(trim((string) $rows[$headerIdx][$cNTrolley]));
                if (preg_match('/(\d+)\s*(?:no[s]?\s+)?trolley/i', $headerCellText, $m)) {
                    $parsedQty = (int) $m[1];
                    if ($parsedQty >= 1) {
                        $trolleyQty = $parsedQty;
                    }
                }
            }

            // Fallback: scan rows above the header for trolley metadata
            if ($trolleyQty === 1 && $headerIdx > 0) {
                for ($fi = 0; $fi < $headerIdx; $fi++) {
                    foreach ($rows[$fi] as $cellVal) {
                        if ($cellVal === null || $cellVal === '') continue;
                        $cellTxt = strtolower(trim((string) $cellVal));
                        if (preg_match('/trolley\s*(?:qty|quantity)\s*[:\-]*\s*(\d+)/i', $cellTxt, $m2)) {
                            $parsedQty2 = (int) $m2[1];
                            if ($parsedQty2 >= 1) {
                                $trolleyQty = $parsedQty2;
                                break 2;
                            }
                        }
                    }
                }
            }

            if ($trolleyQty < 1) {
                $trolleyQty = 1;
                Log::warning('BomMaterialItemsController::importExcel: trolley count parse yielded < 1, defaulting to 1.');
            }

            DB::transaction(function () use (
                $rows, $headerIdx, $cSerial, $cDesc, $cLen, $cQty, $cTotQty, $cTotMm, $cMtr, $cUnit, $cRate,
                $partLookup, $unitLookup, $resolveUnitId, $businessId, $businessDetailsId, $designId,
                $userId, $deptRoleId, &$inserted, &$merged, &$skipped, &$nextSerial,
                $existingByKey, $mergeKey, $normalize, $stripMs
            ) {
                $now = now();

                // Forward-fill state — Excel often shows Unit / Mtr-per-trolley only on
                // the FIRST row of each product group (the cells below it look "empty"
                // because of merged cells). We carry the value down as long as the
                // Product Description stays the same.
                $lastDesc      = null;
                $lastUnitTxt   = '';
                $lastMtr       = null;

                for ($i = $headerIdx + 1; $i < count($rows); $i++) {
                    $row     = $rows[$i];
                    $desc    = trim((string) ($row[$cDesc] ?? ''));
                    $qtyR    = $cQty    !== null ? ($row[$cQty]    ?? null) : null;
                    $totQtyR = $cTotQty !== null ? ($row[$cTotQty] ?? null) : null;
                    $qty     = is_numeric($qtyR) ? (float) $qtyR : null;

                    // Fall back to "total Quantity" (column E) when column D is blank/zero.
                    if (($qty === null || $qty <= 0) && is_numeric($totQtyR) && (float) $totQtyR > 0) {
                        $qty = (float) $totQtyR;
                    }

                    // Skip rows that have no description, or that have a description
                    // but no usable quantity in either quantity column (D or E).
                    // Non-BOM footer rows like "Prepared By:-" have null in both columns
                    // and should not appear in the modal.
                    if ($desc === '' || $qty === null) {
                        $skipped++;
                        continue;
                    }

                    $lenVal = self::numOrNull($cLen !== null ? ($row[$cLen] ?? null) : null);
                    $key    = $mergeKey($desc, $lenVal);

                    // Try the same normalisation pipeline that built the lookup:
                    // 1) plain normalised   2) with "MS" prefix stripped
                    // 3) longest containment fallback — when the Excel description
                    //    contains a master description (e.g. "STACKING CUP(TML STD.)"
                    //    contains master "STACKING CUP"), treat it as a match. Picks
                    //    the longest matching master key to avoid spurious matches
                    //    on short generic words. Comparison is on the normalised
                    //    (lowercase + alphanumeric-only) form, so casing differences
                    //    are inherently inherited.
                    // NOTE: Use $pKey/$pRow (not $key/$row) in the foreach to avoid
                    // shadowing the outer $key (merge-key) and $row (current Excel row).
                    $nDesc   = $normalize($desc);
                    $partRow = $partLookup->get($nDesc);
                    if (!$partRow) {
                        $nDescNoMs = $stripMs($nDesc);
                        if ($nDescNoMs !== $nDesc) {
                            $partRow = $partLookup->get($nDescNoMs);
                        }
                    }
                    if (!$partRow && $nDesc !== '') {
                        // Coverage-ratio threshold for the containment fallback (tier 3).
                        // After a bidirectional containment match is found, the shorter of
                        // the two normalised strings must cover at least this fraction of the
                        // longer one.  Ratio = min(len_a, len_b) / max(len_a, len_b).
                        //
                        // Value 0.6 was chosen so that:
                        //  - "paintyellow" (11) vs "ralbluepaintyellowpaintaspermgistandard" (38)
                        //    → ratio ≈ 0.29  REJECTED  (false positive eliminated)
                        //  - "paintroller" (11) vs "paintroller4" (12)
                        //    → ratio ≈ 0.92  ACCEPTED  (genuine match kept)
                        //  - "paintbrush"  (10) vs "paintbrush1"  (11)
                        //    → ratio ≈ 0.91  ACCEPTED  (genuine match kept)
                        //  - "stackingcup" (11) vs "stackingcupttmlstd" (18)
                        //    → ratio ≈ 0.61  ACCEPTED  (just above threshold, correct)
                        $CONTAINMENT_COVERAGE_THRESHOLD = 0.6;

                        $bestKey = null;
                        $bestLen = 0;
                        foreach ($partLookup as $pKey => $pRow) {
                            // Skip very short generic master keys ("ROD", "NUT", "BAR", etc.)
                            // to avoid false-positive matches on every BOM line that contains
                            // those common words.
                            if (strlen($pKey) < 7) continue;
                            // Excel desc must also be substantial to participate in containment.
                            if (strlen($nDesc) < 7) break;
                            // Bidirectional containment: master ⊆ excel OR excel ⊆ master.
                            // Pick the LONGEST master key matched so we prefer the most-specific
                            // master (e.g. "mssqpipe40x40x3mm" beats "sqpipe" if both match).
                            $contained = (strpos($nDesc, $pKey) !== false)
                                      || (strpos($pKey, $nDesc) !== false);
                            if ($contained) {
                                // Coverage-ratio guard: reject matches where the shorter string
                                // is too small relative to the longer one.  This eliminates the
                                // case where a short Excel term (e.g. "paintyellow", 11 chars)
                                // accidentally matches a long master key that merely contains it
                                // as a substring (e.g. 38 chars → ratio ≈ 0.29 < 0.6 → REJECT).
                                $coverageRatio = min(strlen($pKey), strlen($nDesc))
                                               / max(strlen($pKey), strlen($nDesc));
                                if ($coverageRatio < $CONTAINMENT_COVERAGE_THRESHOLD) {
                                    continue;
                                }
                                if (strlen($pKey) > $bestLen) {
                                    $bestKey = $pKey;
                                    $bestLen = strlen($pKey);
                                }
                            }
                        }
                        if ($bestKey !== null) {
                            $partRow = $partLookup->get($bestKey);
                        }
                    }
                    $partId  = $partRow?->id;

                    $unitTxt = $cUnit !== null ? trim((string) ($row[$cUnit] ?? '')) : '';
                    $totMm   = self::numOrNull($cTotMm !== null ? ($row[$cTotMm] ?? null) : null);
                    $mtr     = self::numOrNull($cMtr   !== null ? ($row[$cMtr]   ?? null) : null);
                    $rateV   = self::numOrNull($cRate  !== null ? ($row[$cRate]  ?? null) : null);

                    // ----- Forward-fill: same product → inherit Unit & Mtr from the
                    //       first row of the group (where Excel actually wrote them). -----
                    $sameProduct = ($lastDesc !== null && strcasecmp($desc, $lastDesc) === 0);

                    if ($unitTxt === '' && $sameProduct && $lastUnitTxt !== '') {
                        $unitTxt = $lastUnitTxt;
                    }
                    if ($mtr === null && $sameProduct && $lastMtr !== null) {
                        $mtr = $lastMtr;
                    }

                    // Update the carry-down anchors (only when this row supplied a value)
                    $lastDesc = $desc;
                    if ($unitTxt !== '') $lastUnitTxt = $unitTxt;
                    if ($mtr     !== null) $lastMtr   = $mtr;
                    if (!$sameProduct) {
                        // New product → reset anchors so a stale Unit doesn't leak across groups
                        if ($unitTxt === '') $lastUnitTxt = '';
                        if ($mtr     === null) $lastMtr   = null;
                    }

                    // Resolve unit_id from the (possibly forward-filled) unit text.
                    // Uses synonym resolution so "MTR" matches a master named "METER" and vice versa.
                    $unitId = $unitTxt !== '' ? $resolveUnitId($unitTxt) : null;

                    // Fall back to tbl_part_item.basic_rate when the Excel Rate is blank/0
                    // and we matched the description to a part master.
                    if (($rateV === null || $rateV == 0.0) && $partRow && $partRow->basic_rate !== null && $partRow->basic_rate !== '') {
                        if (is_numeric($partRow->basic_rate)) {
                            $rateV = (float) $partRow->basic_rate;
                        }
                    }

                    if (isset($existingByKey[$key])) {
                        // Smart-merge: same description + length → sum quantity & total_in_mm.
                        $exRow = $existingByKey[$key];
                        $exRow->quantity    = (float) $exRow->quantity + $qty;
                        if ($totMm !== null) {
                            $exRow->total_in_mm = (float) ($exRow->total_in_mm ?? 0) + $totMm;
                        }
                        // Backfill master FKs / display fields if the existing row was missing them
                        if (!$exRow->part_item_id && $partId) $exRow->part_item_id = $partId;
                        if (!$exRow->unit_id      && $unitId) $exRow->unit_id      = $unitId;
                        if (!$exRow->unit         && $unitTxt !== '') $exRow->unit = $unitTxt;
                        if (($exRow->rate === null || $exRow->rate === '' || (float) $exRow->rate == 0) && $rateV !== null) {
                            $exRow->rate = $rateV;
                        }
                        if (($exRow->mtr_for_01_nos_trolley === null) && $mtr !== null) {
                            $exRow->mtr_for_01_nos_trolley = $mtr;
                        }
                        $exRow->updated_at = $now;
                        $exRow->save();
                        $merged++;
                    } else {
                        $created = BomMaterialItem::create([
                            'business_id'            => $businessId,
                            'business_details_id'    => $businessDetailsId,
                            'design_id'              => $designId,
                            'estimation_id'          => null,
                            'part_item_id'           => $partId,
                            'serial_no'              => $nextSerial++,
                            'product_description'    => $desc,
                            'length'                 => $lenVal,
                            'quantity'               => $qty,
                            'total_in_mm'            => $totMm,
                            'mtr_for_01_nos_trolley' => $mtr,
                            'rate'                   => $rateV,
                            'unit_id'                => $unitId,
                            'unit'                   => $unitTxt !== '' ? $unitTxt : null,
                            'created_by'             => $userId,
                            'created_dept_role_id'   => $deptRoleId,
                            'is_active'              => 1,
                            'is_deleted'             => 0,
                            'created_at'             => $now,
                            'updated_at'             => $now,
                        ]);
                        $existingByKey[$key] = $created; // so further rows in the same Excel merge too
                        $inserted++;
                    }
                }
            });

            // Persist the parsed trolley_qty to the designs row (outside the BOM transaction
            // so it takes effect even if the transaction had nothing to insert/merge).
            DB::table('designs')->where('id', $designId)->update(['trolley_qty' => $trolleyQty]);

            // Build a human-friendly summary line.
            $parts = [];
            if ($inserted > 0) $parts[] = "{$inserted} new";
            if ($merged   > 0) $parts[] = "{$merged} merged";
            if ($skipped  > 0) $parts[] = "{$skipped} skipped";
            $summary = $parts ? implode(', ', $parts) : 'no rows';

            return response()->json([
                'status'      => 'success',
                'message'     => "Excel imported: {$summary}. Review the BOM Items modal and click Save when ready.",
                'inserted'    => $inserted,
                'merged'      => $merged,
                'skipped'     => $skipped,
                'trolley_qty' => $trolleyQty,
            ]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::importExcel: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to parse Excel: ' . $e->getMessage(),
            ], 500);
        }
    }

    /** Helper: cast cell value to float-or-null, treating blank/non-numeric as null. */
    private static function numOrNull($v): ?float
    {
        if ($v === null) return null;
        if (is_string($v) && trim($v) === '') return null;
        return is_numeric($v) ? (float) $v : null;
    }

    /**
     * POST /common/clear-bom-items
     * Soft-deletes ALL bom_material_items for the given business_details_id + design_id.
     * Used by the "Clear All" button next to "Upload BOM Excel" so the user can wipe
     * a bad import and start fresh.
     */
    public function clearItems(Request $request)
    {
        $request->validate([
            'business_details_id' => 'required|integer|min:1',
            'design_id'           => 'required|integer|min:1',
        ]);

        try {
            $deleted = BomMaterialItem::where('business_details_id', (int) $request->input('business_details_id'))
                ->where('design_id', (int) $request->input('design_id'))
                ->where('is_deleted', 0)
                ->update([
                    'is_deleted' => 1,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status'  => 'success',
                'message' => "Cleared {$deleted} BOM row(s).",
                'deleted' => (int) $deleted,
            ]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::clearItems: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to clear BOM rows: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =====================================================================
    // SHARED MASTER DATA ENDPOINTS (accessible by all 4 roles via admin middleware)
    // =====================================================================

    /**
     * GET /common/get-part-items?search=...
     * Returns [{id, name}] from tbl_part_item filtered by is_active=1.
     */
    public function getPartItems(Request $request)
    {
        try {
            $search = trim($request->input('search', ''));
            $query  = PartItem::where('is_active', true)
                ->where('is_deleted', false);

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                        ->orWhere('extra_description', 'like', '%' . $search . '%')
                        ->orWhere('basic_rate', 'like', '%' . $search . '%');
                });
            }

            $items = $query->orderByRaw('LOWER(description) ASC')
                ->select('id', 'description as name', 'basic_rate')
                ->get();

            return response()->json(['status' => 'success', 'items' => $items]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::getPartItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch part items.'], 500);
        }
    }

    /**
     * GET /common/get-units
     * Returns [{id, name}] from tbl_unit filtered by is_active=1.
     */
    public function getUnits(Request $request)
    {
        try {
            $units = UnitMaster::where('is_active', true)
                ->where('is_deleted', false)
                ->orderBy('name')
                ->select('id', 'name')
                ->get();

            return response()->json(['status' => 'success', 'units' => $units]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::getUnits: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch units.'], 500);
        }
    }

    // =====================================================================
    // DESIGN DEPARTMENT
    // =====================================================================

    /**
     * GET /designdept/get-bom-material-items/{businessDetailsId}/{designId}
     * Returns both items and context (for modal header/footer rendering).
     */
    public function designGetItems(string $businessDetailsId, string $designId)
    {
        try {
            $bdId    = (int) base64_decode($businessDetailsId);
            $dId     = (int) base64_decode($designId);
            $items   = $this->service->getItems($bdId, $dId);
            $context = $this->service->getContext($bdId);
            return response()->json(['status' => 'success', 'items' => $items, 'context' => $context]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::designGetItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch BOM items.'], 500);
        }
    }

    /**
     * POST /designdept/save-bom-material-items
     */
    public function designSaveItems(Request $request)
    {
        $validated = $this->validateSaveRequest($request);
        if ($validated !== true) {
            return response()->json(['status' => 'error', 'message' => $validated], 422);
        }

        try {
            $userId = (int) session('user_id');

            // Save trolley_qty if provided (default 1, clamped 1-9999)
            $trolleyQty = (int) ($request->input('trolley_qty') ?: 1);
            if ($trolleyQty < 1)    $trolleyQty = 1;
            if ($trolleyQty > 9999) $trolleyQty = 9999;
            DB::table('designs')->where('id', (int) $request->input('design_id'))->update(['trolley_qty' => $trolleyQty]);

            $items  = $this->service->saveItems(
                (int) $request->input('business_id'),
                (int) $request->input('business_details_id'),
                (int) $request->input('design_id'),
                null,          // estimation_id — not yet assigned at design stage
                $userId,
                3,             // created_dept_role_id = 3 (design)
                $request->input('items', []),
                array_filter(array_map('intval', $request->input('deleted_ids', [])))
            );

            return response()->json([
                'status'      => 'success',
                'message'     => 'BOM items saved.',
                'items'       => $items,
                'trolley_qty' => $trolleyQty,
            ]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::designSaveItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to save BOM items.'], 500);
        }
    }

    // =====================================================================
    // ESTIMATION DEPARTMENT
    // =====================================================================

    /**
     * GET /estimationdept/get-bom-material-items/{businessDetailsId}/{designId}
     * Returns both items and context (for modal header/footer rendering).
     */
    public function estimationGetItems(string $businessDetailsId, string $designId)
    {
        try {
            $bdId    = (int) base64_decode($businessDetailsId);
            $dId     = (int) base64_decode($designId);
            $items   = $this->service->getItems($bdId, $dId);
            $context = $this->service->getContext($bdId);
            return response()->json(['status' => 'success', 'items' => $items, 'context' => $context]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::estimationGetItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch BOM items.'], 500);
        }
    }

    /**
     * POST /estimationdept/save-bom-material-items
     * Sets estimation_id from the estimation record linked to business_details_id.
     * After saving items, auto-triggers the exceed-approval flow when
     * BOM Final Total > business_details.total_amount.
     *
     * Extra accepted field: exceed_reason (nullable string, max 1000)
     *   — used as exceed_remark when provided; otherwise auto-generated.
     */
    public function estimationSaveItems(Request $request)
    {
        $validated = $this->validateSaveRequest($request);
        if ($validated !== true) {
            return response()->json(['status' => 'error', 'message' => $validated], 422);
        }

        // Validate optional exceed_reason
        if ($request->has('exceed_reason') && !is_null($request->input('exceed_reason'))) {
            $exceedReason = trim($request->input('exceed_reason'));
            if (strlen($exceedReason) > 1000) {
                return response()->json(['status' => 'error', 'message' => 'Exceed reason must not exceed 1000 characters.'], 422);
            }
        } else {
            $exceedReason = null;
        }

        try {
            $userId       = (int) session('user_id');
            $bdId         = (int) $request->input('business_details_id');
            $estimationId = null;

            // Save trolley_qty if provided (default 1, clamped 1-9999)
            $trolleyQty = (int) ($request->input('trolley_qty') ?: 1);
            if ($trolleyQty < 1)    $trolleyQty = 1;
            if ($trolleyQty > 9999) $trolleyQty = 9999;
            DB::table('designs')->where('id', (int) $request->input('design_id'))->update(['trolley_qty' => $trolleyQty]);

            // Fetch estimation record to populate estimation_id
            $estimation = EstimationModel::where('business_details_id', $bdId)->first();
            if ($estimation) {
                $estimationId = $estimation->id;
            }

            // Use the exceed-check variant — auto-triggers owner notification when needed.
            // T-2026-046: pass $trolleyQty explicitly so the repository can compute the
            // correct unit-aware BOM total (BomTotalCalculator). $trolleyQty was already
            // clamped (>=1) and saved to designs.trolley_qty just above.
            $result = $this->service->saveItemsWithExceedCheck(
                (int) $request->input('business_id'),
                $bdId,
                (int) $request->input('design_id'),
                $estimationId,
                $userId,
                15,  // created_dept_role_id = 15 (estimation)
                $request->input('items', []),
                array_filter(array_map('intval', $request->input('deleted_ids', []))),
                $exceedReason,
                $trolleyQty
            );

            return response()->json([
                'status'           => 'success',
                'message'          => $result['message'],
                'items'            => $result['items'],
                'bom_final_total'  => $result['bom_final_total'],
                'business_limit'   => $result['business_limit'],
                'exceed_triggered' => $result['exceed_triggered'],
                'trolley_qty'      => $trolleyQty,
            ]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::estimationSaveItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to save BOM items.'], 500);
        }
    }

    // =====================================================================
    // OWNER (view only)
    // =====================================================================

    /**
     * GET /owner/get-bom-material-items/{businessDetailsId}/{designId}
     * Returns both items and context (for modal header/footer rendering).
     */
    public function ownerGetItems(string $businessDetailsId, string $designId)
    {
        try {
            $bdId    = (int) base64_decode($businessDetailsId);
            $dId     = (int) base64_decode($designId);
            $items   = $this->service->getItems($bdId, $dId);
            $context = $this->service->getContext($bdId);
            return response()->json(['status' => 'success', 'items' => $items, 'context' => $context]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::ownerGetItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch BOM items.'], 500);
        }
    }

    // =====================================================================
    // PRODUCTION (view only)
    // =====================================================================

    /**
     * GET /production/get-bom-material-items/{businessDetailsId}/{designId}
     * Returns both items and context (for modal header/footer rendering).
     */
    public function productionGetItems(string $businessDetailsId, string $designId)
    {
        try {
            $bdId    = (int) base64_decode($businessDetailsId);
            $dId     = (int) base64_decode($designId);
            $items   = $this->service->getItems($bdId, $dId);
            $context = $this->service->getContext($bdId);
            return response()->json(['status' => 'success', 'items' => $items, 'context' => $context]);
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsController::productionGetItems: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch BOM items.'], 500);
        }
    }

    // =====================================================================
    // SHARED VALIDATION
    // =====================================================================

    /**
     * Server-side validation for save requests.
     * Returns true on pass, or a string error message on fail.
     *
     * @param  Request $request
     * @return true|string
     */
    private function validateSaveRequest(Request $request)
    {
        if (!$request->has('business_id') || !is_numeric($request->input('business_id'))) {
            return 'Invalid business_id.';
        }
        if (!$request->has('business_details_id') || !is_numeric($request->input('business_details_id'))) {
            return 'Invalid business_details_id.';
        }
        if (!$request->has('design_id') || !is_numeric($request->input('design_id'))) {
            return 'Invalid design_id.';
        }

        $items = $request->input('items', []);
        if (!is_array($items)) {
            return 'Items must be an array.';
        }

        foreach ($items as $index => $item) {
            $rowNum = $index + 1;

            // part_item_id is optional. T-2026-062: when it is missing/zero the repository
            // tries to match product_description against the EXISTING part-item master and,
            // failing that, saves the row unlinked (part_item_id NULL) with its "Not in
            // store" badge — it no longer creates a master row. Either identifier is enough
            // to accept the row: part_item_id > 0 (already matched) OR a non-empty
            // product_description (unmatched, but still a meaningful BOM line).
            $partItemId = isset($item['part_item_id']) ? (int) $item['part_item_id'] : 0;
            $productDescription = trim($item['product_description'] ?? '');
            if ($partItemId <= 0 && $productDescription === '') {
                return "Row {$rowNum}: Product Description is required.";
            }
            // If part_item_id is provided, verify it exists (existing-master path).
            // If it is 0/empty there is nothing to verify — the row saves unlinked.
            if ($partItemId > 0) {
                $partItemExists = PartItem::where('id', $partItemId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->exists();
                if (!$partItemExists) {
                    return "Row {$rowNum}: Selected Part Item does not exist or is inactive.";
                }
            }

            // unit_id: optional — if missing/zero the repository falls back to unit_id=1 (NOS).
            // Validate only when explicitly provided.
            $unitId = isset($item['unit_id']) ? (int) $item['unit_id'] : 0;
            if ($unitId > 0) {
                $unitExists = UnitMaster::where('id', $unitId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->exists();
                if (!$unitExists) {
                    return "Row {$rowNum}: Selected Unit does not exist or is inactive.";
                }
            }

            if (!isset($item['quantity']) || !is_numeric($item['quantity'])) {
                return "Row {$rowNum}: Quantity must be a number.";
            }

            if ((float) $item['quantity'] <= 0) {
                return "Row {$rowNum}: Quantity must be greater than 0.";
            }

            if (isset($item['length']) && $item['length'] !== '' && !is_numeric($item['length'])) {
                return "Row {$rowNum}: Length must be a number.";
            }

            if (isset($item['total_in_mm']) && $item['total_in_mm'] !== '' && !is_numeric($item['total_in_mm'])) {
                return "Row {$rowNum}: Total in mm must be a number.";
            }

            if (isset($item['mtr_for_01_nos_trolley']) && $item['mtr_for_01_nos_trolley'] !== '' && !is_numeric($item['mtr_for_01_nos_trolley'])) {
                return "Row {$rowNum}: Mtr for 01 Nos Trolley must be a number.";
            }

            // rate is nullable; if provided must be numeric and >= 0
            if (isset($item['rate']) && $item['rate'] !== '' && $item['rate'] !== null) {
                if (!is_numeric($item['rate'])) {
                    return "Row {$rowNum}: Rate must be a number.";
                }
                if ((float) $item['rate'] < 0) {
                    return "Row {$rowNum}: Rate must be 0 or greater.";
                }
            }
        }

        return true;
    }
}
