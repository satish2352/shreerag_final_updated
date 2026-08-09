<?php

namespace App\Http\Repository\Organizations\Common;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BomMaterialItem;
use App\Models\PartItem;
use App\Models\UnitMaster;
use App\Models\EstimationModel;
use App\Models\BusinessDetails;
use App\Models\BusinessApplicationProcesses;
use App\Models\AdminView;
use App\Models\NotificationStatus;
use App\Support\BomTotalCalculator;

class BomMaterialItemsRepository
{
    /**
     * Fetch active (non-deleted) BOM items for a given business_details_id + design_id.
     * JOINs to tbl_part_item and tbl_unit so the response includes part_item_id and unit_id.
     * Used by all GET endpoints (design, estimation, owner, production).
     */
    public function getItems(int $businessDetailsId, int $designId): array
    {
        try {
            return BomMaterialItem::where('bom_material_items.business_details_id', $businessDetailsId)
                ->where('bom_material_items.design_id', $designId)
                ->where('bom_material_items.is_deleted', 0)
                ->orderBy('bom_material_items.serial_no')
                ->orderBy('bom_material_items.id')
                ->select([
                    'bom_material_items.id',
                    'bom_material_items.business_id',
                    'bom_material_items.business_details_id',
                    'bom_material_items.design_id',
                    'bom_material_items.estimation_id',
                    'bom_material_items.serial_no',
                    'bom_material_items.part_item_id',
                    'bom_material_items.product_description',
                    'bom_material_items.length',
                    'bom_material_items.quantity',
                    'bom_material_items.total_in_mm',
                    'bom_material_items.mtr_for_01_nos_trolley',
                    'bom_material_items.rate',
                    'bom_material_items.unit',
                    'bom_material_items.unit_id',
                    'bom_material_items.created_by',
                    'bom_material_items.created_dept_role_id',
                    'bom_material_items.is_active',
                    'bom_material_items.is_deleted',
                    'bom_material_items.created_at',
                    'bom_material_items.updated_at',
                ])
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsRepository::getItems error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch contextual header data for the BOM modal.
     * Joins businesses_details -> businesses -> estimation (LEFT JOIN)
     * and LEFT JOIN designs to retrieve trolley_qty.
     *
     * Returns:
     *   title              — always "MATERIAL INDENT"
     *   bom_reference      — "Bill of Material:- {product_name}"
     *   customer_name      — businesses.title
     *   project_name       — businesses.project_name
     *   date               — today's date in DD-MM-YYYY
     *   total_qty          — businesses_details.quantity (business order qty, NOT trolley count)
     *   estimation_amount  — estimation.total_estimation_amount (null if no estimation row)
     *   trolley_qty        — designs.trolley_qty (default 1 for old rows without a value)
     */
    public function getContext(int $businessDetailsId): array
    {
        try {
            $row = DB::table('businesses_details as bd')
                ->join('businesses as b', 'b.id', '=', 'bd.business_id')
                ->leftJoin('estimation as e', 'e.business_details_id', '=', 'bd.id')
                ->leftJoin('designs as d', function ($join) {
                    $join->on('d.business_details_id', '=', 'bd.id')
                         ->where('d.is_deleted', '=', 0);
                })
                ->where('bd.id', $businessDetailsId)
                ->where('bd.is_deleted', 0)
                ->select([
                    'bd.product_name',
                    'bd.quantity as parent_quantity',
                    'bd.total_amount as business_limit',
                    'b.title as customer_name',
                    'b.project_name',
                    'e.total_estimation_amount',
                    DB::raw('COALESCE(d.trolley_qty, 1) as trolley_qty'),
                ])
                ->first();

            if (!$row) {
                return [
                    'title'              => 'MATERIAL INDENT',
                    'bom_reference'      => 'Bill of Material:-',
                    'customer_name'      => '',
                    'project_name'       => '',
                    'date'               => now()->format('d-m-Y'),
                    'total_qty'          => '',
                    'estimation_amount'  => null,
                    'business_limit'     => null,
                    'trolley_qty'        => 1,
                ];
            }

            return [
                'title'              => 'MATERIAL INDENT',
                'bom_reference'      => 'Bill of Material:- ' . ($row->product_name ?? ''),
                'customer_name'      => $row->customer_name ?? '',
                'project_name'       => $row->project_name ?? '',
                'date'               => now()->format('d-m-Y'),
                'total_qty'          => $row->parent_quantity ?? '',
                'estimation_amount'  => $row->total_estimation_amount,
                'business_limit'     => $row->business_limit,
                'trolley_qty'        => (int) ($row->trolley_qty ?? 1),
            ];
        } catch (\Exception $e) {
            Log::error('BomMaterialItemsRepository::getContext error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upsert BOM items within a DB transaction.
     * - Existing rows (id provided) are updated.
     * - New rows (id null) are inserted.
     * - Rows listed in $deletedIds are soft-deleted.
     * - product_description is RE-FETCHED from PartItem master (canonical name).
     * - unit (text) is RE-FETCHED from UnitMaster (canonical name).
     *
     * @param int        $businessId
     * @param int        $businessDetailsId
     * @param int        $designId
     * @param int|null   $estimationId       null for design-dept saves
     * @param int        $userId             session user id
     * @param int        $deptRoleId         3=design, 15=estimation
     * @param array      $items              array of item arrays from request
     * @param array      $deletedIds         ids to soft-delete
     * @return array                         saved items (with part_item_id + unit_id)
     */

    /**
     * Resolve a BOM row's description to an EXISTING PartItem master record.
     *
     * T-2026-019 originally auto-CREATED a `tbl_part_item` row (part_number 'AUTO-BOM')
     * for any description that matched nothing. T-2026-062 removed that: a BOM upload is
     * a design document, not an authority to define store inventory, and the auto-create
     * silently filled the part-item master with one placeholder record per unmatched
     * description — including typos and scratch text ("sdsdsdsfsdgfsdgfdg", "ARNAV") —
     * each with a fake part number, HSN id 1, group id 1 and zero rate. Those placeholders
     * then compete with the real master rows in every part-item dropdown and search.
     *
     * An unmatched row is now simply left unlinked (part_item_id NULL). The BOM row itself
     * still saves in full, and the modal keeps showing its orange "Not in store" badge on
     * every reload, so whoever owns the master data can add the part deliberately (with a
     * real part number, HSN, group and rate) or point the row at an existing item.
     *
     * Lookup order:
     *   1. Within-batch cache $batchCache (key = lowercase-trimmed description).
     *   2. Existing active tbl_part_item by lowercase-trimmed description.
     * Returns 0 when nothing matches — callers persist that as part_item_id NULL.
     *
     * @param  string  $description   Raw product description from the BOM row
     * @param  array   &$batchCache   In-memory map: normalizedDesc => part_item_id
     * @return int                    Matched PartItem id, or 0 when unmatched
     */
    private function resolvePartItemId(string $description, array &$batchCache): int
    {
        $normalizedKey = strtolower(trim($description));

        if ($normalizedKey === '') {
            return 0;
        }

        // Check batch cache first (within same transaction). Misses are cached as 0 too, so
        // a BOM repeating the same unmatched description on many rows costs one query
        // rather than one per row.
        if (!array_key_exists($normalizedKey, $batchCache)) {
            $batchCache[$normalizedKey] = self::matchPartItemIdByDescription($description);
        }

        return $batchCache[$normalizedKey];
    }

    /**
     * Case-insensitive, whitespace-tolerant lookup of a description against the ACTIVE
     * part-item master. Returns the matched id, or 0 when the description is in no master
     * record ("not in store").
     *
     * Static and public because this single definition of "does this description exist in
     * the master?" is shared by two callers that must never disagree:
     *   - resolvePartItemId() above, on the write path;
     *   - BomMaterialItemsController::validateSaveRequest(), which REJECTS the save when
     *     this returns 0 (T-2026-063).
     * If the validator used a different matching rule than the writer, a row could pass
     * validation and still land unlinked, or be rejected despite being perfectly matchable.
     */
    public static function matchPartItemIdByDescription(string $description): int
    {
        $normalizedKey = strtolower(trim($description));
        if ($normalizedKey === '') {
            return 0;
        }

        $existing = PartItem::whereRaw('LOWER(TRIM(description)) = ?', [$normalizedKey])
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->first();

        return $existing ? (int) $existing->id : 0;
    }

    public function saveItems(
        int $businessId,
        int $businessDetailsId,
        int $designId,
        ?int $estimationId,
        int $userId,
        int $deptRoleId,
        array $items,
        array $deletedIds
    ): array {
        return DB::transaction(function () use (
            $businessId,
            $businessDetailsId,
            $designId,
            $estimationId,
            $userId,
            $deptRoleId,
            $items,
            $deletedIds
        ) {
            // Soft-delete removed rows
            if (!empty($deletedIds)) {
                BomMaterialItem::where('business_details_id', $businessDetailsId)
                    ->where('design_id', $designId)
                    ->whereIn('id', $deletedIds)
                    ->where('is_deleted', 0)
                    ->update([
                        'is_deleted'  => 1,
                        'updated_at'  => now(),
                    ]);
            }

            $savedItems = [];
            // T-2026-062: per-transaction lookup cache for description -> PartItem id
            $batchCache = [];

            foreach ($items as $item) {
                $existingId  = isset($item['id']) && !empty($item['id']) ? (int) $item['id'] : null;
                $partItemId  = (int) ($item['part_item_id'] ?? 0);
                $unitId      = (int) ($item['unit_id'] ?? 0);

                // T-2026-062: match against the EXISTING master only. An unmatched row is
                // saved with part_item_id NULL and keeps its "Not in store" badge — the
                // part-item master is never written to from a BOM upload.
                if ($partItemId <= 0) {
                    $partItemId = $this->resolvePartItemId(
                        trim($item['product_description'] ?? ''),
                        $batchCache
                    );
                }

                // Re-fetch canonical names from master (do NOT trust client snapshot)
                $partItemRecord  = PartItem::where('id', $partItemId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->first();
                $canonicalDescription = $partItemRecord ? $partItemRecord->description : trim($item['product_description'] ?? '');

                $unitRecord = UnitMaster::where('id', $unitId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->first();
                $canonicalUnit = $unitRecord ? $unitRecord->name : trim($item['unit'] ?? '');

                $payload = [
                    'business_id'            => $businessId,
                    'business_details_id'    => $businessDetailsId,
                    'design_id'              => $designId,
                    'estimation_id'          => $estimationId,
                    'serial_no'              => (int) ($item['serial_no'] ?? 1),
                    'part_item_id'           => $partItemId > 0 ? $partItemId : null,
                    'product_description'    => $canonicalDescription,
                    'length'                 => isset($item['length']) && $item['length'] !== '' ? (float) $item['length'] : null,
                    'quantity'               => (float) $item['quantity'],
                    'total_in_mm'            => isset($item['total_in_mm']) && $item['total_in_mm'] !== '' ? (float) $item['total_in_mm'] : null,
                    'mtr_for_01_nos_trolley' => isset($item['mtr_for_01_nos_trolley']) && $item['mtr_for_01_nos_trolley'] !== '' ? (float) $item['mtr_for_01_nos_trolley'] : null,
                    'rate'                   => isset($item['rate']) && $item['rate'] !== '' && $item['rate'] !== null ? (float) $item['rate'] : null,
                    'unit_id'                => $unitId > 0 ? $unitId : null,
                    'unit'                   => $canonicalUnit !== '' ? $canonicalUnit : null,
                    'created_by'             => $userId,
                    'created_dept_role_id'   => $deptRoleId,
                    'is_active'              => 1,
                    'is_deleted'             => 0,
                    'updated_at'             => now(),
                ];

                if ($existingId) {
                    // Update existing row (must belong to same business_details_id + design_id, not deleted)
                    $row = BomMaterialItem::where('id', $existingId)
                        ->where('business_details_id', $businessDetailsId)
                        ->where('design_id', $designId)
                        ->where('is_deleted', 0)
                        ->first();

                    if ($row) {
                        $row->fill($payload);
                        $row->save();
                        $savedItems[] = $row->toArray();
                    } else {
                        // Row not found or already deleted — insert as new
                        $payload['created_at'] = now();
                        $new = BomMaterialItem::create($payload);
                        $savedItems[] = $new->toArray();
                    }
                } else {
                    // New row
                    $payload['created_at'] = now();
                    $new = BomMaterialItem::create($payload);
                    $savedItems[] = $new->toArray();
                }
            }

            return $savedItems;
        });
    }

    /**
     * Save BOM items for the ESTIMATION department and then auto-trigger the
     * exceed-approval flow if BOM Final Total > business_details.total_amount.
     *
     * Only called from BomMaterialItemsController::estimationSaveItems().
     * The designer save path uses saveItems() directly and never calls this method.
     *
     * Returns an extended array:
     *   items            — saved BomMaterialItem rows
     *   bom_final_total  — float: unit-aware SUM across active rows (T-2026-046)
     *   business_limit   — float|null: businesses_details.total_amount
     *   exceed_triggered — bool: whether the exceed flow was fired
     *   message          — human-readable summary string
     *
     * T-2026-046: Added $trolleyQty parameter so the BOM total is computed with
     * the unit-aware formula (BomTotalCalculator) instead of the naive rate × quantity.
     * Previously persisted value was wrong; rows self-heal on next BOM save.
     *
     * @param int        $businessId
     * @param int        $businessDetailsId
     * @param int        $designId
     * @param int|null   $estimationId
     * @param int        $userId
     * @param int        $deptRoleId         must be 15 (estimation)
     * @param array      $items
     * @param array      $deletedIds
     * @param string|null $exceedReason      optional reason provided by estimator in modal
     * @param int        $trolleyQty         from designs.trolley_qty (already saved by controller before this call)
     * @return array
     */
    public function saveItemsWithExceedCheck(
        int $businessId,
        int $businessDetailsId,
        int $designId,
        ?int $estimationId,
        int $userId,
        int $deptRoleId,
        array $items,
        array $deletedIds,
        ?string $exceedReason = null,
        int $trolleyQty = 1
    ): array {
        return DB::transaction(function () use (
            $businessId,
            $businessDetailsId,
            $designId,
            $estimationId,
            $userId,
            $deptRoleId,
            $items,
            $deletedIds,
            $exceedReason,
            $trolleyQty
        ) {
            // ----------------------------------------------------------------
            // 1. Save items (same logic as saveItems, inline to share transaction)
            // ----------------------------------------------------------------
            if (!empty($deletedIds)) {
                BomMaterialItem::where('business_details_id', $businessDetailsId)
                    ->where('design_id', $designId)
                    ->whereIn('id', $deletedIds)
                    ->where('is_deleted', 0)
                    ->update([
                        'is_deleted' => 1,
                        'updated_at' => now(),
                    ]);
            }

            $savedItems = [];
            // T-2026-062: per-transaction lookup cache for description -> PartItem id
            $batchCacheExceed = [];

            foreach ($items as $item) {
                $existingId = isset($item['id']) && !empty($item['id']) ? (int) $item['id'] : null;
                $partItemId = (int) ($item['part_item_id'] ?? 0);
                $unitId     = (int) ($item['unit_id'] ?? 0);

                // T-2026-062: match against the EXISTING master only — never create. See
                // resolvePartItemId() and the identical block in saveItems() above.
                if ($partItemId <= 0) {
                    $partItemId = $this->resolvePartItemId(
                        trim($item['product_description'] ?? ''),
                        $batchCacheExceed
                    );
                }

                $partItemRecord = PartItem::where('id', $partItemId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->first();
                $canonicalDescription = $partItemRecord
                    ? $partItemRecord->description
                    : trim($item['product_description'] ?? '');

                $unitRecord = UnitMaster::where('id', $unitId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->first();
                $canonicalUnit = $unitRecord ? $unitRecord->name : trim($item['unit'] ?? '');

                $payload = [
                    'business_id'            => $businessId,
                    'business_details_id'    => $businessDetailsId,
                    'design_id'              => $designId,
                    'estimation_id'          => $estimationId,
                    'serial_no'              => (int) ($item['serial_no'] ?? 1),
                    'part_item_id'           => $partItemId > 0 ? $partItemId : null,
                    'product_description'    => $canonicalDescription,
                    'length'                 => isset($item['length']) && $item['length'] !== '' ? (float) $item['length'] : null,
                    'quantity'               => (float) $item['quantity'],
                    'total_in_mm'            => isset($item['total_in_mm']) && $item['total_in_mm'] !== '' ? (float) $item['total_in_mm'] : null,
                    'mtr_for_01_nos_trolley' => isset($item['mtr_for_01_nos_trolley']) && $item['mtr_for_01_nos_trolley'] !== '' ? (float) $item['mtr_for_01_nos_trolley'] : null,
                    'rate'                   => isset($item['rate']) && $item['rate'] !== '' && $item['rate'] !== null ? (float) $item['rate'] : null,
                    'unit_id'                => $unitId > 0 ? $unitId : null,
                    'unit'                   => $canonicalUnit !== '' ? $canonicalUnit : null,
                    'created_by'             => $userId,
                    'created_dept_role_id'   => $deptRoleId,
                    'is_active'              => 1,
                    'is_deleted'             => 0,
                    'updated_at'             => now(),
                ];

                if ($existingId) {
                    $row = BomMaterialItem::where('id', $existingId)
                        ->where('business_details_id', $businessDetailsId)
                        ->where('design_id', $designId)
                        ->where('is_deleted', 0)
                        ->first();

                    if ($row) {
                        $row->fill($payload);
                        $row->save();
                        $savedItems[] = $row->toArray();
                    } else {
                        $payload['created_at'] = now();
                        $new = BomMaterialItem::create($payload);
                        $savedItems[] = $new->toArray();
                    }
                } else {
                    $payload['created_at'] = now();
                    $new = BomMaterialItem::create($payload);
                    $savedItems[] = $new->toArray();
                }
            }

            // ----------------------------------------------------------------
            // 2. Compute BOM Final Total from all ACTIVE rows for this bd+design
            //    (includes rows saved above + any prior rows not in this batch)
            // ----------------------------------------------------------------
            $allActiveItems = BomMaterialItem::where('business_details_id', $businessDetailsId)
                ->where('design_id', $designId)
                ->where('is_deleted', 0)
                ->get();

            // T-2026-046: Use unit-aware formula matching the JS modal.
            // piece units (NOS/PCS/SET/EACH) → rate × quantity × trolleyQty
            // length units (MTR/METER/etc.)   → rate × mtr_for_01_nos_trolley × trolleyQty
            // $trolleyQty was already clamped (>=1) and saved to designs.trolley_qty
            // by BomMaterialItemsController::estimationSaveItems() before this call.
            $bomFinalTotal = BomTotalCalculator::finalTotal($allActiveItems, $trolleyQty);

            // ----------------------------------------------------------------
            // 3. Fetch business limit and estimation row
            // ----------------------------------------------------------------
            $businessDetails = BusinessDetails::find($businessDetailsId);
            $businessLimit   = $businessDetails ? floatval($businessDetails->total_amount ?? 0) : null;

            $estimation = EstimationModel::where('business_details_id', $businessDetailsId)
                ->where('is_deleted', 0)
                ->first();

            $exceedTriggered = false;
            $message         = 'BOM items saved.';

            if ($estimation) {
                // Always sync total_estimation_amount to BOM Final Total
                $estimation->total_estimation_amount = $bomFinalTotal;

                if ($businessLimit !== null && $bomFinalTotal > $businessLimit) {
                    // ---- EXCEED PATH ----
                    $remark = !empty($exceedReason)
                        ? $exceedReason
                        : 'Auto-flagged: BOM Final Total ₹' . number_format($bomFinalTotal, 2)
                          . ' exceeds Business Limit ₹' . number_format($businessLimit, 2);

                    $estimation->is_exceed_pending       = 1;
                    $estimation->exceed_remark           = $remark;
                    // Clear any prior owner suggestion when restarting exceed flow
                    $estimation->owner_suggested_amount  = null;
                    $estimation->owner_suggestion_remark = null;
                    $estimation->owner_suggested_at      = null;
                    $estimation->owner_suggested_by      = null;
                    $estimation->save();

                    // Update BAP
                    $bap = BusinessApplicationProcesses::where('business_details_id', $businessDetailsId)
                        ->where('is_deleted', 0)
                        ->first();
                    if ($bap) {
                        $bap->bom_estimation_send_to_owner = 1300;
                        $bap->off_canvas_status            = 50;
                        $bap->save();
                    }

                    // Dual-table notification update
                    AdminView::where('business_details_id', $businessDetailsId)->update([
                        'off_canvas_status' => 50,
                        'is_view'           => 0,
                    ]);
                    NotificationStatus::where('business_details_id', $businessDetailsId)->update([
                        'off_canvas_status' => 50,
                    ]);

                    $exceedTriggered = true;
                    $message = 'BOM Final Total ₹' . number_format($bomFinalTotal, 2)
                        . ' exceeds Business Limit ₹' . number_format($businessLimit, 2)
                        . '. Approval request sent to Owner.';
                } else {
                    // ---- WITHIN LIMIT PATH ----
                    // Clear any stale exceed state in case a prior BOM save had triggered it
                    $estimation->is_exceed_pending       = 0;
                    $estimation->exceed_remark           = null;
                    $estimation->owner_suggested_amount  = null;
                    $estimation->owner_suggestion_remark = null;
                    $estimation->owner_suggested_at      = null;
                    $estimation->owner_suggested_by      = null;
                    $estimation->save();

                    // If BAP was at 1300 (exceed pending), revert to normal BOM-sent-to-owner state
                    $bap = BusinessApplicationProcesses::where('business_details_id', $businessDetailsId)
                        ->where('is_deleted', 0)
                        ->first();
                    if ($bap && $bap->bom_estimation_send_to_owner == 1300) {
                        $bap->bom_estimation_send_to_owner = config('constants.ESTIMATION_DEPARTMENT.BOM_ESTIMATION_SEND_TO_OWNER'); // 1149
                        $bap->off_canvas_status            = 28;
                        $bap->save();

                        AdminView::where('business_details_id', $businessDetailsId)->update([
                            'off_canvas_status' => 28,
                            'is_view'           => 0,
                        ]);
                        NotificationStatus::where('business_details_id', $businessDetailsId)->update([
                            'off_canvas_status' => 28,
                        ]);
                    }

                    $message = 'BOM items saved. Total ₹' . number_format($bomFinalTotal, 2) . ' is within the business limit.';
                }
            }
            // If no estimation row exists yet, we still save BOM items but skip exceed logic.
            // The estimation row is created when the estimator first submits the estimation form.

            return [
                'items'            => $savedItems,
                'bom_final_total'  => $bomFinalTotal,
                'business_limit'   => $businessLimit,
                'exceed_triggered' => $exceedTriggered,
                'message'          => $message,
            ];
        });
    }
}
