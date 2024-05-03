<?php
namespace App\Http\Services\Organizations\Purchase;
use App\Http\Repository\Organizations\Purchase\PurchaseRepository;
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

    public function getDetailsForPurchase($id){
        try {
            $data = $this->repo->getDetailsForPurchase($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

   
    public function submitBOMToOwner(){
        try {
            $data = $this->repo->submitBOMToOwner();
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}