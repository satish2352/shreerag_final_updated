<?php
namespace App\Http\Services\Organizations\Finance;

use App\Http\Repository\Organizations\Finance\FinanceRepository;
use Carbon\Carbon;
use App\Models\{
    DesignModel
};

use Config;

class FinanceServices
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new FinanceRepository();
    }

    // public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    // {
    //     try {
    //         $update_data = $this->repo->forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id);
    //         dd($update_data);
    //         die();
    //         return $update_data; 
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    {
        try {
            $update_data = $this->repo->forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id);
        //   dd($update_data);
        //   die();
            return $update_data; 
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function forwardedPurchaseOrderPaymentToTheVendor($purchase_order_id, $business_id)
    {
        try {
            $update_data = $this->repo->forwardedPurchaseOrderPaymentToTheVendor($purchase_order_id, $business_id);

        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function addAll($request)
    // {
    //     try {
    //         $last_id = $this->repo->addAll($request);

    //         $path = Config::get('FileConstant.REQUISITION_ADD');
    //         $ImageName = $last_id['ImageName'];
    //         uploadImage($request, 'bom_file', $path, $ImageName);

    //         if ($last_id) {
    //             return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => ' Data Not Added.'];
    //         }
    //     } catch (Exception $e) {
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //     }
    // }


    // public function genrateStoreReciptAndForwardMaterialToTheProduction($id)
    // {
    //     try {
    //         $update_data = $this->repo->genrateStoreReciptAndForwardMaterialToTheProduction($id);
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

}