<?php
namespace App\Http\Services\Organizations\Productions;
use App\Http\Repository\Organizations\Productions\ProductionRepository;
use Carbon\Carbon;
use App\Models\ {
    DesignModel
    };

use Config;
    class ProductionServices
    {
        protected $repo;
        public function __construct(){
        $this->repo = new ProductionRepository();
    }




    public function acceptdesign($id){
        try {
           $data_output = $update_data = $this->repo->acceptdesign($id);
           return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function rejectdesign($request) {
        try {
            $update_data = $this->repo->rejectdesign($request);
            return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    } 

    // public function acceptProductionCompleted($id, $completed_quantity){
    //     try {
    //        $update_data = $this->repo->acceptProductionCompleted($id, $completed_quantity);
       
    //        return $update_data;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 
    public function acceptProductionCompleted($id, $completed_quantity)
{
    try {
        $update_data = $this->repo->acceptProductionCompleted($id, $completed_quantity);
       
        return $update_data;
    } catch (\Exception $e) {
        return $e;
    }
}

    public function editProduct($id) {
        try {
            $data_output = $this->repo->editProduct($id);
            // dd($data_output);
            // die();
return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function editProductQuantityTracking($id) {
        try {
            $data_output = $this->repo->editProductQuantityTracking($id);
       
return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function updateProductMaterial($request) {
        try {
            $result = $this->repo->updateProductMaterial($request);
            // dd($result);
            // die();
            return $result;
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    
    
}