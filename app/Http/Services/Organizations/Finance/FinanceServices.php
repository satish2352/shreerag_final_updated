<?php
namespace App\Http\Services\Organizations\Finance;

use App\Http\Repository\Organizations\Finance\FinanceRepository;
use Carbon\Carbon;
use App\Models\{
    DesignModel,
    AdminView
};

use Config;

class FinanceServices
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new FinanceRepository();
    }
    public function forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id)
    {
        try {
            $update_data = $this->repo->forwardPurchaseOrderToTheOwnerForSanction($purchase_orders_id, $business_id);
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
    public function sendToDispatch($id,  $business_details_id){
        try {
           $update_data = $this->repo->sendToDispatch($id,  $business_details_id);
           return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    } 
}