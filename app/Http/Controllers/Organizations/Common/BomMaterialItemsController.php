<?php

namespace App\Http\Controllers\Organizations\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Organizations\Common\BomMaterialItemsService;
use App\Models\EstimationModel;
use App\Models\PartItem;
use App\Models\UnitMaster;

class BomMaterialItemsController extends Controller
{
    protected BomMaterialItemsService $service;

    public function __construct()
    {
        $this->service = new BomMaterialItemsService();
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
                ->limit(50)
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
                'status'  => 'success',
                'message' => 'BOM items saved.',
                'items'   => $items,
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

            // Fetch estimation record to populate estimation_id
            $estimation = EstimationModel::where('business_details_id', $bdId)->first();
            if ($estimation) {
                $estimationId = $estimation->id;
            }

            // Use the exceed-check variant — auto-triggers owner notification when needed
            $result = $this->service->saveItemsWithExceedCheck(
                (int) $request->input('business_id'),
                $bdId,
                (int) $request->input('design_id'),
                $estimationId,
                $userId,
                15,  // created_dept_role_id = 15 (estimation)
                $request->input('items', []),
                array_filter(array_map('intval', $request->input('deleted_ids', []))),
                $exceedReason
            );

            return response()->json([
                'status'           => 'success',
                'message'          => $result['message'],
                'items'            => $result['items'],
                'bom_final_total'  => $result['bom_final_total'],
                'business_limit'   => $result['business_limit'],
                'exceed_triggered' => $result['exceed_triggered'],
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

            // part_item_id is required and must exist in the master
            $partItemId = isset($item['part_item_id']) ? (int) $item['part_item_id'] : 0;
            if ($partItemId <= 0) {
                return "Row {$rowNum}: Product Description (Part Item) is required.";
            }
            $partItemExists = PartItem::where('id', $partItemId)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->exists();
            if (!$partItemExists) {
                return "Row {$rowNum}: Selected Part Item does not exist or is inactive.";
            }

            // unit_id is required and must exist in the master
            $unitId = isset($item['unit_id']) ? (int) $item['unit_id'] : 0;
            if ($unitId <= 0) {
                return "Row {$rowNum}: Unit is required.";
            }
            $unitExists = UnitMaster::where('id', $unitId)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->exists();
            if (!$unitExists) {
                return "Row {$rowNum}: Selected Unit does not exist or is inactive.";
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
