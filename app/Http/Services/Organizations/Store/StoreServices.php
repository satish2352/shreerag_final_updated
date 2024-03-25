<?php
namespace App\Http\Services\Organizations\Store;
use App\Http\Repository\Organizations\Store\StoreRepository;
use Carbon\Carbon;
use App\Models\ {
    DesignModel
    };

use Config;
class StoreServices
{
    protected $repo;
    public function __construct(){
        $this->repo = new StoreRepository();
    }

    public function orderAcceptedAndMaterialForwareded($id){
        try {
            $update_data = $this->repo->orderAcceptedAndMaterialForwareded($id);
        } catch (\Exception $e) {
            return $e;
        }
    } 


  
}