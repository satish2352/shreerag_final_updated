<?php
namespace App\Http\Services\Organizations\Purchase;
use App\Http\Repository\Organizations\Purchase\PurchaseOrderRepository;
use Carbon\Carbon;
use App\Models\ {
    DesignModel
    };

use Config;
    class PurchaseOrderServices
    {
        protected $repo;
        public function __construct(){
        $this->repo = new PurchaseOrderRepository();
    }

    public function getDetailsForPurchase($id){
        try {
            $data = $this->repo->getDetailsForPurchase($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }
   
    public function submitBOMToOwner($request){
        try {
            $data = $this->repo->submitBOMToOwner($request);
            // dd($data);
            // die();
            if ($data) {
                return ['status' => 'success', 'msg' => 'Purchase Order Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Purchase Order Not Added.'];
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function addAll($request)
    // {
    //     try {
    //         $result = $this->repo->addAll($request);
    //         if ($result['status'] === 'success') {
    //             return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
    //         }  
    //     } catch (Exception $e) {
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //     }      
    // }
    

    
    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($id)
    {
        try {
            $submitAndSentEmailToTheVendorFinalPurchaseOrder = $this->repo->submitAndSentEmailToTheVendorFinalPurchaseOrder($id);
            return $submitAndSentEmailToTheVendorFinalPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getById($id){
        try {
            $result = $this->repo->getById($id);
            // dd($result); // Dump the result
            // die();
            return $result;
        } catch (\Exception $e) {
            dd($e); // Dump the exception
            return $e;
        }
    }
    public function updateAll($request){
        try {
            $return_data = $this->repo->updateAll($request);
            // dd($return_data);
            // die();
        
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Slide Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Slide Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }

    public function deleteByIdAddmore($id){
        try {
            $delete = $this->repo->deleteByIdAddmore($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }
}