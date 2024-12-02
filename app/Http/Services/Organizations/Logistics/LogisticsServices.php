<?php
namespace App\Http\Services\Organizations\Logistics;
use App\Http\Repository\Organizations\Logistics\LogisticsRepository;
use Carbon\Carbon;

use Config;
class LogisticsServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new LogisticsRepository();

    }
    // public function storeLogistics(Request $request)
    // {
    //     try {
    //         $data = $this->repo->storeLogistics($request);
            
    //         if ($data) {
    //             return ['status' => 'success', 'msg' => 'Rejected Chalan Added Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => 'Rejected Chalan Not Added.'];
    //         }
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    public function storeLogistics($request)
    {
        try {
            $data = $this->repo->storeLogistics($request);
            if ($data) {
                return ['status' => 'success', 'msg' => 'Logistics Form Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Logistics Form Not Updated.'];
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function sendToFianance($id,  $business_details_id){
        try {
           $update_data = $this->repo->sendToFianance($id,  $business_details_id);
           return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    } 
    
}