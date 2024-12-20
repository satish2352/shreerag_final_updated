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
   
    public function submitBOMToOwner($request)
    {
        try {
            $result = $this->repo->submitBOMToOwner($request);
            $path = Config::get('DocumentConstant.RETURNABLE_CHALAN_ADD');
            $ImageName = $result['ImageName'];
            uploadImage($request, 'image', $path, $ImageName);
            if ($result['status'] === 'success') {
                return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id)
    {
        try {
            $data = $this->repo->submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id);
          
            return $data;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getPurchaseOrderDetails($id)
    {
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
            // dd($return_data);
            // die();
            $path = Config::get('DocumentConstant.RETURNABLE_CHALAN_ADD');
            if ($request->hasFile('image')) {
                if ($return_data['image']) {
                    if (file_exists_view(Config::get('DocumentConstant.RETURNABLE_CHALAN_DELETE') . $return_data['image'])) {
                        removeImage(Config::get('DocumentConstant.RETURNABLE_CHALAN_DELETE') . $return_data['image']);
                    }

                }
                if ($request->hasFile('image')) {
                    $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->file('image')->getClientOriginalExtension();
                    
                } else {
                    
                }                
                uploadImage($request, 'image', $path, $englishImageName);
                $delivery_data = ReturnableChalan::find($return_data['last_insert_id']);
                $delivery_data->image = $englishImageName;
                $delivery_data->save();
            }     
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
    public function deleteById($id)
    {
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
    public function deleteByIdAddmore($id)
    {
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