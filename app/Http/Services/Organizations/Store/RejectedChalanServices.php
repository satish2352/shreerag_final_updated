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

    public function getAll()
{
    try {
        $data = $this->repo->getAll();
        return $data;
    } catch (\Exception $e) {
    
        return $e; 
    }
}

    public function getDetailsForPurchase($id)
    {
        try {
            $data = $this->repo->getDetailsForPurchase($id);
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function storeRejectedChalan($request)
    {
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

}