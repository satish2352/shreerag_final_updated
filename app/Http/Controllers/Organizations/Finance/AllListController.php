<?php

namespace App\Http\Controllers\Organizations\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Finance\AllListServices;
use Exception;
use App\Models\{
    NotificationStatus
};

class AllListController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new AllListServices();
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
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->business_details_id;

                    if (!empty($business_details_id)) {
                        $update_data['logistics_to_fianance_visible'] = '1';
                        NotificationStatus::where('logistics_to_fianance_visible', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.finance.list.list-business-received-from-logistics', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
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
