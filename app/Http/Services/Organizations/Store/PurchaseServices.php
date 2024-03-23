<?php
namespace App\Http\Services\Organizations\Store;
use App\Http\Repository\Organizations\Store\PurchaseRepository;
use Carbon\Carbon;
use App\Models\ {
    DesignModel
    };

use Config;
    class PurchaseServices
    {
        protected $repo;
        public function __construct(){
        $this->repo = new PurchaseRepository();
    }


    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }


public function addAll($request)
{
    try {
        $result = $this->repo->addAll($request);
        if ($result['status'] === 'success') {
            return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
        } else {
            return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
        }  
    } catch (Exception $e) {
        return ['status' => 'error', 'msg' => $e->getMessage()];
    }      
}



    public function getById($id){
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateAll($request){
        try {
            $return_data = $this->repo->updateAll($request);
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Data  Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }

    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            // dd($delete);
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