<?php
namespace App\Http\Services\Organizations\Report;
use App\Http\Repository\Organizations\Report\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Config;
class ReportServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new ReportRepository();

    }
    public function getAllReceivedFromFianance(){
        try {
          $data_output = $this->repo->getAllReceivedFromFianance();
     
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
       public function getCompletedProductList($request){
        try {
            $data_output = $this->repo->getCompletedProductList($request);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }   
      public function listDesignReport($request){
        try {
            $data_output = $this->repo->listDesignReport($request);
          
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
      public function getProductionReport($request)
{
    try {
        return $this->repo->getProductionReport($request);
    } catch (\Exception $e) {
        return [
            'data' => [],
            'pagination' => null,
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}
public function getSecurityReport($request){
        try {
            $data_output = $this->repo->getSecurityReport($request);
       
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
public function getGRNReport($request){
    try {
        $data_output = $this->repo->getGRNReport($request);
        return $data_output;
    } catch (\Exception $e) {
        throw $e; // propagate error
    }
}
public function getConsumptionReport($request)
{
    try {
        $data_output =  $this->repo->getConsumptionReport($request);
        
          return $data_output;
    } catch (\Exception $e) {
        throw $e; // Let the controller handle it
    }
}

public function getConsumptionMaterialList($id){
    try {
        $data_output = $this->repo->getConsumptionMaterialList($id);
            // dd($data_output,"dtykkkkkkkkkkkkkkkk");
        return $data_output;
    } catch (\Exception $e) {
        throw $e; // propagate error
    }
}
public function listItemStockReport($request)
{
    try {
        $data_output =  $this->repo->listItemStockReport($request);
        
          return $data_output;
    } catch (\Exception $e) {
        throw $e; // Let the controller handle it
    }
} 

public function listLogisticsReport($request)
{
    try {
        $data_output =  $this->repo->listLogisticsReport($request);
        
          return $data_output;
    } catch (\Exception $e) {
        throw $e; // Let the controller handle it
    }
} 
}