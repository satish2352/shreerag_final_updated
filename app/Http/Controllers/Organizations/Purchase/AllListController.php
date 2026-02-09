<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\AllListServices;
// use App\Http\Controllers\Organizations\Purchase\PDF;
use App\Http\Controllers\Exports\PurchaseReportExport;
use App\Http\Controllers\Exports\PurchasePartyReportExport;
use App\Http\Controllers\Exports\FollowUpReportExport;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use App\Models\{
    Business,
    NotificationStatus,
    Vendors
};

class AllListController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new AllListServices();
    }
      private function timeStamp()
        {
            return now()->format('Y-m-d_H-i-s');
        }
    // public function getAllListMaterialReceivedForPurchase()
    // {

    //     try {
    //         $data_output = $this->service->getAllListMaterialReceivedForPurchase();

    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_details = $data->business_details_id;

    //                 if (!empty($business_details)) {
    //                     $update_data['purchase_is_view'] = '1';
    //                     NotificationStatus::where('purchase_is_view', '0')
    //                         ->where('business_details_id', $business_details)
    //                         ->update($update_data);
    //                 }
    //             }
    //         }

    //         return view('organizations.purchase.list.list-bom-material-recived-for-purchase', compact('data_output'));
    //         // return view( 'organizations.purchase.forms.send-vendor-details-for-purchase', compact( 'data_output' ) );
    //     } catch (\Exception $e) {

    //         return $e;
    //     }
    // }

    // public function getAllListApprovedPurchaseOrder(Request $request)
    // {
    //     try {
    //         $data_output = $this->service->getAllListApprovedPurchaseOrder();
    //         if ($data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_id = $data->id;

    //                 if (!empty($business_id)) {
    //                     $update_data['purchase_order_is_accepted_by_view'] = '1';
    //                     NotificationStatus::where('purchase_order_is_accepted_by_view', '0')
    //                         ->where('business_details_id', $business_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.purchase.list.list-purchase-order-approved-need-to-check', [
    //                 'data_output' => [],
    //                 'message' => 'No data found'
    //             ]);
    //         }
    //         return view('organizations.purchase.list.list-purchase-order-approved-need-to-check', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    // public function getPurchaseOrderSentToOwnerForApprovalBusinesWise($id)
    // {
    //     try {
    //         $data_output = $this->service->getPurchaseOrderSentToOwnerForApprovalBusinesWise($id);

    //         if ($data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_details_id = $data->business_details_id;

    //                 if (!empty($business_details_id)) {
    //                     $update_data['purchase_is_view'] = '1';
    //                     NotificationStatus::where('purchase_is_view', '0')
    //                         ->where('business_details_id', $business_details_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.purchase.list.list-purchase-order-need-to-check', [
    //                 'data_output' => [],
    //                 'message' => 'No data found for designs received for correction',
    //             ]);
    //         }
    //         return view('organizations.purchase.list.list-purchase-order-approved-need-to-check-businesswise', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
public function getAllListMaterialReceivedForPurchase()
{
    try {
        $data_output = $this->service->getAllListMaterialReceivedForPurchase();

        if ($data_output->isNotEmpty()) {
            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('purchase_is_view', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['purchase_is_view' => 1]);
            }
        }

        return view('organizations.purchase.list.list-bom-material-recived-for-purchase', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function getAllListApprovedPurchaseOrder(Request $request)
{
    try {
        $data_output = $this->service->getAllListApprovedPurchaseOrder();

        if (!($data_output instanceof \Illuminate\Support\Collection) || $data_output->isEmpty()) {
            return view('organizations.purchase.list.list-purchase-order-approved-need-to-check', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        // Prefer business_details_id if present in result set
        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        // Fallback: if your service really returns id as business_details_id
        // $bdIds = $data_output->pluck('id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('purchase_order_is_accepted_by_view', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['purchase_order_is_accepted_by_view' => 1]);
        }

        return view('organizations.purchase.list.list-purchase-order-approved-need-to-check', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function getPurchaseOrderSentToOwnerForApprovalBusinesWise($id)
{
    try {
        $data_output = $this->service->getPurchaseOrderSentToOwnerForApprovalBusinesWise($id);

        if (!($data_output instanceof \Illuminate\Support\Collection) || $data_output->isEmpty()) {
            return view('organizations.purchase.list.list-purchase-order-need-to-check', [
                'data_output' => [],
                'message' => 'No data found',
            ]);
        }

        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('purchase_is_view', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['purchase_is_view' => 1]);
        }

        return view('organizations.purchase.list.list-purchase-order-approved-need-to-check-businesswise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}

    public function getAllListPurchaseOrderMailSentToVendor(Request $request)
    {
        try {

            $data_output = $this->service->getAllListPurchaseOrderMailSentToVendor();

            return view('organizations.purchase.list.list-purchase-order-approved-sent-to-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListPurchaseOrderMailSentToVendorBusinessWise($id)
    {
        try {
            $data_output = $this->service->getAllListPurchaseOrderMailSentToVendorBusinessWise($id);

            return view('organizations.purchase.list.list-purchase-order-approved-sent-to-vendor-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListSubmitedPurchaeOrderByVendor(Request $request)
    {
        try {

            $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendor();


            return view('organizations.purchase.list.list-all-po-sent-to-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function getAllListSubmitedPurchaeOrderByVendorBusinessWise($id)
    // {
    //     try {
    //         $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendorBusinessWise($id);
    //         if ($data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_id = $data->id;

    //                 if (!empty($business_id)) {
    //                     $update_data['po_send_to_vendor'] = '1';
    //                     NotificationStatus::where('po_send_to_vendor', '0')
    //                         ->where('business_details_id', $business_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.purchase.list.list-all-po-sent-to-vendor-businesswise', [
    //                 'data_output' => [],
    //                 'message' => 'No data found'
    //             ]);
    //         }
    //         return view('organizations.purchase.list.list-all-po-sent-to-vendor-businesswise', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
public function getAllListSubmitedPurchaeOrderByVendorBusinessWise($id)
{
    try {
        $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendorBusinessWise($id);

        if (!($data_output instanceof \Illuminate\Support\Collection) || $data_output->isEmpty()) {
            return view('organizations.purchase.list.list-all-po-sent-to-vendor-businesswise', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        // Prefer business_details_id if your service returns it
        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        // If your service DOES NOT have business_details_id and id actually equals business_details_id, then use:
        // $bdIds = $data_output->pluck('id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('po_send_to_vendor', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['po_send_to_vendor' => 1]);
        }

        return view('organizations.purchase.list.list-all-po-sent-to-vendor-businesswise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}

    public function getAllListPurchaseOrderTowardsOwner(Request $request)
    {
        try {
            $data_output = $this->service->getAllListPurchaseOrderTowardsOwner();



            return view('organizations.purchase.list.list-purchase-order-need-to-check', compact('data_output'));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching purchase orders: ' . $e->getMessage());
            return view('organizations.purchase.list.list-purchase-order-need-to-check', [
                'data_output' => [],
                'message' => 'An error occurred while fetching purchase orders.',
            ]);
        }
    }

    public function getPurchaseReport(Request $request)
    {
        $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        // 🔹 If export is requested
        if ($request->filled('export_type')) {
            $data = $this->service->getPurchaseReport($request)['data'];

            if ($request->export_type == 1) {
                $pdf = Pdf::loadView('exports.purchase-report-pdf', ['data' => $data]);
                return $pdf->download("PurchaseReport_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new PurchaseReportExport($data), "PurchaseReport_{$this->timeStamp()}.xlsx");
            }
        }

        // 🔹 Normal view load
        return view('organizations.report.purchase-report', compact('getProjectName'));
    }
    public function getPurchaseReportAjax(Request $request)
    {
        try {
            $data = $this->service->getPurchaseReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.purchase_report', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape'); // <-- Landscape

                return $pdf->download("PurchaseReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new PurchaseReportExport($data['data']), "PurchaseReport_{$this->timeStamp()}.xlsx");
            }

            // Normal AJAX response
            return response()->json([
                'status' => true,
                'data' => $data['data'],
                'pagination' => $data['pagination']
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPurchasePartyReport(Request $request)
    {
        $getVendorName = Vendors::whereNotNull('vendor_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('vendor_name', 'id');

        if ($request->filled('export_type')) {
            $data = $this->service->getPurchasePartyReport($request)['data'];

            if ($request->export_type == 1) {
                $pdf = Pdf::loadView('exports.party-wise-report', ['data' => $data])
                    ->setPaper('a3', 'landscape');
                return $pdf->download("PartyWiseReport_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new PurchasePartyReportExport($data), "PartyWiseReport_{$this->timeStamp()}.xlsx");
            }
        }

        return view('organizations.report.party-wise-report', compact('getVendorName'));
    }

    public function getPurchasePartyReportAjax(Request $request)
    {
        try {
            $response = $this->service->getPurchasePartyReport($request);

            if (isset($response['status']) && $response['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message']
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $response['data'],
                'pagination' => $response['pagination']
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function FollowUpReport(Request $request)
    {
        $getVendorName = Vendors::whereNotNull('vendor_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('vendor_name', 'id');

        if ($request->filled('export_type')) {
            $data = $this->service->getPurchasePartyReport($request)['data'];

            if ($request->export_type == 1) {
                $pdf = Pdf::loadView('exports.follow-up-report', ['data' => $data])
                    ->setPaper('a3', 'landscape');
                return $pdf->download("FollowUpReport_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new FollowUpReportExport($data), "FollowUpReport_{$this->timeStamp()}.xlsx");
            }
        }

        return view('organizations.report.follow-up-report', compact('getVendorName'));
    }

    public function FollowUpReportAjax(Request $request)
    {
        try {
            $response = $this->service->getFollowUpReport($request);

            if (isset($response['status']) && $response['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message']
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $response['data'],
                'pagination' => $response['pagination']
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
