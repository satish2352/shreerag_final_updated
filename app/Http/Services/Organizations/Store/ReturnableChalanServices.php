<?php
namespace App\Http\Services\Organizations\Store;
use App\Http\Repository\Organizations\Store\ReturnableChalanRepository;
use Carbon\Carbon;
use App\Models\ {
    ReturnableChalan
    };

use Config;
    class ReturnableChalanServices 
    {
        protected $repo;
        public function __construct(){
        $this->repo = new ReturnableChalanRepository();
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
            $result = $this->repo->submitBOMToOwner($request);
            if ($result['status'] === 'success') {
                return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id){
        try {
            $data = $this->repo->submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id);
          
            return $data;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getPurchaseOrderDetails($id){
        try {
            $result = $this->repo->getPurchaseOrderDetails($id);
            return $result;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateAll($request){
        try {
            $return_data = $this->repo->updateAll($request);
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Slide Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Slide Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    public function getById($id){
        try {
           $data_output = $this->repo->getById($id);
           return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function deleteById($id){
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
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