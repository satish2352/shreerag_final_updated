<?php
namespace App\Http\Services\Organizations\Store;

use App\Http\Repository\Organizations\Store\StoreRepository;
use Carbon\Carbon;
use App\Models\{
    DesignModel
};

use Config;

class StoreServices
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new StoreRepository();
    }

    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $update_data = $this->repo->orderAcceptedAndMaterialForwareded($id);
            return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function storeRequesition($request)
    {
        try {
            $last_id = $this->repo->storeRequesition($request);
            $path = Config::get('FileConstant.REQUISITION_ADD');
            $ImageName = $last_id['ImageName'];
          
            uploadImage($request, 'bom_file_req', $path, $ImageName);
            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data Not Added.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }


    public function genrateStoreReciptAndForwardMaterialToTheProduction($purchase_orders_id, $business_id)
    {
        try {
            $update_data = $this->repo->genrateStoreReciptAndForwardMaterialToTheProduction($purchase_orders_id, $business_id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function editProductMaterialWiseAdd($id) {
        try {
            $data_output = $this->repo->editProductMaterialWiseAdd($id);
return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function updateProductMaterialWiseAdd($request) {
        try {
            $result = $this->repo->updateProductMaterialWiseAdd($request);
            return $result;
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function editProduct($id) {
        try {
            $data_output = $this->repo->editProduct($id);
       
return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
 
    public function updateProductMaterial($request) {
        try {
            $result = $this->repo->updateProductMaterial($request);
            return $result;
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


}