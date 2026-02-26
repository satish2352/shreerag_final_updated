<?php

namespace App\Http\Controllers\Organizations\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Finance\AllListServices;
use Exception;
use App\Models\{
    NotificationStatus,
    AdminView
};

class AllListController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new AllListServices();
    }
    public function getAllListBusinessDetails(Request $request)
    {
        try {
            $data_output = $this->service->getAllListBusinessDetails();

            $update_data_admin['is_view'] = '1';
            AdminView::where('off_canvas_status', 11)
                ->where('is_view', '0')
                ->update($update_data_admin);
            return view('organizations.finance.list.list-business-for-finance', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSRAndGRNGeanrated(Request $request)
    {
        try {
            $data_output = $this->service->getAllListSRAndGRNGeanrated();

            return view('organizations.finance.list.list-sr-and-gr-genrated-business', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListSRAndGRNGeanratedBusinessWise($id)
    {
        try {
            $data_output = $this->service->getAllListSRAndGRNGeanratedBusinessWise($id);

            return view('organizations.finance.list.list-sr-and-gr-genrated-business-wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listAcceptedGrnSrnFinance($purchase_orders_id)
    {
        try {
            $data_output = $this->service->listAcceptedGrnSrnFinance($purchase_orders_id);
            //, compact( 'data_output' )
            return view('organizations.finance.list.list-material-details-sr-and-gr-genrated-business', compact('purchase_orders_id'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listPOSentForApprovaTowardsOwner(Request $request)
    {
        try {
            $data_output = $this->service->listPOSentForApprovaTowardsOwner();

            return view('organizations.finance.list.list-po-submited-for-sanction-towards-owner', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listPOSanctionAndNeedToDoPaymentToVendor(Request $request)
    {
        try {
            $data_output = $this->service->listPOSanctionAndNeedToDoPaymentToVendor();

            return view('organizations.finance.list.list-po-sanction-and-need-to-do-payment-to-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListBusinessReceivedFromLogistics()
    {
        try {
            $data_output = $this->service->getAllListBusinessReceivedFromLogistics();

            if ($data_output->isEmpty()) {
                return view('organizations.finance.list.list-business-received-from-logistics', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('logistics_to_fianance_visible', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['logistics_to_fianance_visible' => 1]);
            }

            return view('organizations.finance.list.list-business-received-from-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListBusinessFianaceSendToDispatch()
    {
        try {
            $data_output = $this->service->getAllListBusinessFianaceSendToDispatch();

            return view('organizations.finance.list.list-business-send-to-dispatch', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}
