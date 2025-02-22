<?php
namespace App\Http\Services\Organizations\Store;

use App\Http\Repository\Organizations\Store\RejectedChalanRepository;
use Carbon\Carbon;
use App\Models\{
    DesignModel
};

use Config;

class RejectedChalanServices
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new RejectedChalanRepository();
    }
    public function getAll(){
        try {
            $data = $this->repo->getAll();
            return $data;
        } catch (\Exception $e) {
            return $e; 
        }
    }

    public function getDetailsForPurchase($id){
        try {
            $data = $this->repo->getDetailsForPurchase($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function storeRejectedChalan($request){
        try {
            $data = $this->repo->storeRejectedChalan($request);
            if ($data) {
                return ['status' => 'success', 'msg' => 'Rejected Chalan Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Rejected Chalan Not Added.'];
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllRejectedChalanList() {
        try {
            $data = $this->repo->getAllRejectedChalanList();
            return $data;
        } catch (\Exception $e) {
        
            return $e; 
        }
    }
    public function getAllRejectedChalanDetailsList($purchase_orders_id, $id){
        try {
            $data = $this->repo->getAllRejectedChalanDetailsList($purchase_orders_id, $id);
            return $data;
        } catch (\Exception $e) {
            return $e; 
        }
    }
}