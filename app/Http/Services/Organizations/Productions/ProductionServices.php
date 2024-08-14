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
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function acceptProductionCompleted($id){
        try {
           $update_data = $this->repo->acceptProductionCompleted($id);
       
           return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    } 
    

}