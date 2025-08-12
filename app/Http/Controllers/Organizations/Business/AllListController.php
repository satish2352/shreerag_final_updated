<?php

namespace App\Http\Controllers\Organizations\Business;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Business\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    AdminView,
    NotificationStatus
}
;

class AllListController extends Controller
 {

    public function __construct() {
        $this->service = new AllListServices();
    }

    public function getAllListForwardedToDesign( Request $request ) {
        try {
            $data_output = $this->service->getAllListForwardedToDesign();

            $update_data_admin[ 'is_view' ] = '1';
            AdminView::where( 'off_canvas_status', 11 )
            ->where( 'is_view', '0' )
            ->update( $update_data_admin );
            return view( 'organizations.business.list.list-business', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListCorrectionToDesignFromProduction( Request $request ) {
        try {
            $data_output = $this->service->getAllListCorrectionToDesignFromProduction();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-business-design-correction-from-prod', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }

            return view( 'organizations.business.list.list-business-design-correction-from-prod', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function materialAskByProdToStore( Request $request ) {
        try {
            $data_output = $this->service->materialAskByProdToStore();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-material-ask-by-prod-to-store', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-material-ask-by-prod-to-store', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllStoreDeptSentForPurchaseMaterials( Request $request ) {
        try {
            $data_output = $this->service->getAllStoreDeptSentForPurchaseMaterials();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-material-list-from-store-to-purchase', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-material-list-from-store-to-purchase', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListPurchaseOrder( Request $request ) {
        try {
            $data_output = $this->service->getAllListPurchaseOrder();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-purchase-order-need-to-check', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }

            return view( 'organizations.business.list.list-purchase-order-need-to-check', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListApprovedPurchaseOrderOwnerlogin( Request $request ) {
        try {
            $data_output = $this->service->getAllListApprovedPurchaseOrderOwnerlogin();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-purchase-order-approved', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-purchase-order-approved', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function submitFinalPurchaseOrder( $id ) {
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise( $id );
         
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_details_id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_details_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-purchase-order-approved-bussinesswise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-purchase-order-approved-bussinesswise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListRejectedPurchaseOrderOwnerlogin( Request $request ) {
        try {
            $data_output = $this->service->getAllListRejectedPurchaseOrderOwnerlogin();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-purchase-order-rejected', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-purchase-order-rejected', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getPurchaseOrderRejectedBusinessWise( $id ) {
        try {
            $data_output = $this->service->getPurchaseOrderRejectedBusinessWise( $id );
            return view( 'organizations.business.list.list-purchase-order-rejected-bussinesswise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function listPOReceivedForApprovaTowardsOwner( Request $request ) {
        try {
            $data_output = $this->service->listPOReceivedForApprovaTowardsOwner();
            return view( 'organizations.business.list.list-po-received-for-sanction-towards-owner', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function listPOPaymentReleaseByVendor( Request $request ) {
        try {
            $data_output = $this->service->listPOPaymentReleaseByVendor();
            return view( 'organizations.business.list.list-release-approval-payment-by-vendor', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
public function loadDesignSubmittedForEstimation() { //checked
    try {
        $data_output = $this->service->loadDesignSubmittedForEstimation();
        if (is_iterable($data_output) && count($data_output) > 0) {
            foreach ($data_output as $data) {
                $business_id = $data->business_id;

                if (!empty($business_id)) {
                    AdminView::where('is_view', '0')
                        ->where('business_id', $business_id)
                        ->update(['is_view' => '1']);
                }
            }
        } else {
            return view('organizations.business.design.list-bom-received-estimation', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        return view('organizations.business.design.list-bom-received-estimation', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}

     public function loadDesignSubmittedForEstimationBusinessWise( $business_details_id ) {
        try {
            $data_output = $this->service->loadDesignSubmittedForEstimationBusinessWise( $business_details_id );

            return view( 'organizations.business.design.list-bom-received-estimation-business-wise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
public function getAcceptEstimationBOM() {
    try {
        $data_output = $this->service->getAcceptEstimationBOM();
        return view('organizations.business.list.list-bom-accepted', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function getAcceptEstimationBOMBusinessWise($id) {
    try {
        $data_output = $this->service->getAcceptEstimationBOMBusinessWise($id);
        if (is_iterable($data_output) && count($data_output) > 0) {
            foreach ($data_output as $data) {
                $business_id = $data->id;

                if (!empty($business_id)) {
                    NotificationStatus::where('accepted_bom_estimated', '0')
                        ->where('id', $business_id)
                        ->update(['accepted_bom_estimated' => '1']);
                }
            }
        } else {
            return view('organizations.business.list.list-bom-accepted-business-wise', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        return view('organizations.business.list.list-bom-accepted-business-wise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function getRejectEstimationBOM(){ //checked
    try {
        $data_output = $this->service->getRejectEstimationBOM();
        return view('organizations.business.list.list-bom-rejected', compact('data_output'));
    } catch (\Exception $e) {
        \Log::error('Error in getRejectEstimationBOM: ' . $e->getMessage());
        return view('organizations.business.list.list-bom-rejected', [
            'data_output' => [],
            'message' => 'An error occurred while fetching data.'
        ]);
    }
}


public function getRejectEstimationBOMBusinessWise($id) {
    try {
        $data_output = $this->service->getRejectEstimationBOMBusinessWise($id);  

          if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;
                  
                     if ( !empty( $business_id ) ) {
                        $update_data['rejected_bom_estimated'] = '11';
                       
                        NotificationStatus::where('rejected_bom_estimated', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                       
                    }
                }
            } else {
                return view( 'organizations.business.list.list-bom-rejected-business-wise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
        return view('organizations.business.list.list-bom-rejected-business-wise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}

public function getRevisedEstimationBOM(){ //checked
    try {
        $data_output = $this->service->getRevisedEstimationBOM();
        return view('organizations.business.list.list-revised-bom-estimation', compact('data_output'));

    } catch (\Exception $e) {
        \Log::error('Error in getRejectEstimationBOM: ' . $e->getMessage());
        return view('organizations.business.list.list-revised-bom-estimation', [
            'data_output' => [],
            'message' => 'An error occurred while fetching data.'
        ]);
    }
}
public function getRevisedEstimationBOMBusinessWise($id) { //checked
    try {
        $data_output = $this->service->getRevisedEstimationBOMBusinessWise($id);        
        if (is_iterable($data_output) && count($data_output) > 0) {
            foreach ($data_output as $data) {
                $business_id = $data->business_id;

                if (!empty($business_id)) {
                    AdminView::where('is_view', '0')
                        ->where('business_id', $business_id)
                        ->update(['is_view' => '1']);
                }
            }
        } else {
            return view('organizations.business.list.list-revised-bom-estimation-business-wise', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        return view('organizations.business.list.list-revised-bom-estimation-business-wise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
        public function loadDesignSubmittedForProduction() {
        try {
            $data_output = $this->service->loadDesignSubmittedForProduction();
            return view( 'organizations.business.design.list-design-upload', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function loadDesignSubmittedForProductionBusinessWise( $business_id ) { //checked
        try {
            $data_output = $this->service->loadDesignSubmittedForProductionBusinessWise( $business_id );           
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_details_id;

                     if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_details_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.design.list-design-uploaded-owner-business-wise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.design.list-design-uploaded-owner-business-wise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListSubmitedPurchaeOrderByVendorOwnerside() {
        try {
            $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendorOwnerside();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.design.list-design-upload', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-owner-all-po-sent-to-vendor', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getOwnerReceivedGatePass()
 {
        try {
            // Fetch data from the service layer
            $all_gatepass = $this->service->getOwnerReceivedGatePass() ?? collect();
        if ($all_gatepass->isNotEmpty()) {
            foreach ($all_gatepass as $data) {
                $business_id = $data->business_details_id;
                if (!empty($business_id)) {
                    $update_data['is_view'] = '1';
                    AdminView::where('is_view', '0')
                        ->where('business_details_id', $business_id)
                        ->update($update_data);
                }
            }
        }

        // Return the view with gate pass data (empty or not)
        return view('organizations.business.list.list-owner-gatepass', compact('all_gatepass'));
    } catch (\Exception $e) {
        // Log the exception and handle it gracefully
        \Log::error($e->getMessage());
        return back()->withErrors('Something went wrong! Please try again.');
    }
}


    public function getOwnerGRN()
    {
        try {
            $all_gatepass = $this->service->getOwnerGRN();
            return view('organizations.business.list.list-owner-grn', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityToStoreGeneratedGRN()
    {
        try {
            $data_output = $this->service->getAllListMaterialSentFromQualityToStoreGeneratedGRN();
            return view('organizations.business.list.list-owner-checked-material-sent-to-store', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id)
    {
        try {
            $data_output = $this->service->getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id);
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->business_details_id; 
                    if (!empty($business_id)) {
                        $update_data['is_view'] = '1';
                        AdminView::where('is_view', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.business.list.list-owner-checked-material-sent-to-store-businesswise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
            return view('organizations.business.list.list-owner-checked-material-sent-to-store-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListMaterialRecievedToProduction()
    {
        try {
            $data_output = $this->service->getOwnerAllListMaterialRecievedToProduction();
            return view('organizations.business.list.list-owner-recived-material-from-store-dept', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getOwnerAllCompletedProduction(){
        try {
            $data_output = $this->service->getOwnerAllCompletedProduction();
            return view('organizations.business.list.list-owner-production-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerFinalAllCompletedProductionLogistics(){
        try {
            $data_output = $this->service->getOwnerFinalAllCompletedProductionLogistics();
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->business_details_id; 
                    if (!empty($business_id)) {
                        $update_data['is_view'] = '1';
                        AdminView::where('is_view', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.business.list.list-owner-production-completed-received-logistics', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
            return view('organizations.business.list.list-owner-production-completed-received-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListBusinessReceivedFromLogistics()
    {
        try {
            $data_output = $this->service->getOwnerAllListBusinessReceivedFromLogistics();
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->business_details_id; 
                    if (!empty($business_id)) {
                        $update_data['is_view'] = '1';
                        AdminView::where('is_view', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.business.list.list-owner-business-received-from-logistics', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
            return view('organizations.business.list.list-owner-business-received-from-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListBusinessFianaceSendToDispatch()
    {
        try {
            $data_output = $this->service->getOwnerAllListBusinessFianaceSendToDispatch();
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->business_details_id; 
                    if (!empty($business_id)) {
                        $update_data['is_view'] = '1';
                        AdminView::where('is_view', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.business.list.list-owner-business-send-to-dispatch', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
            return view('organizations.business.list.list-owner-business-send-to-dispatch', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listProductDispatchCompletedFromDispatch(){
        try {
            $data_output = $this->service->listProductDispatchCompletedFromDispatch();
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->business_details_id; 
                    if (!empty($business_id)) {
                        $update_data['is_view'] = '1';
                        AdminView::where('is_view', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.business.list.list-dispatch-completed', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
            return view('organizations.business.list.list-dispatch-completed', compact('data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
  
}