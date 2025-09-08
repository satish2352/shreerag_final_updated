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
            // dd($data_output);
            // die();
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
        $data_output =  $this->repo->getProductionReport($request);
        
        return $data_output;
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
        return $this->repo->listLogisticsReport($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listFinanceReport($request)
{
    try {
        $data_output = $this->repo->listFinanceReport($request);
       
        return $data_output;
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listVendorPaymentReport($request)
{
    try {
        $data_output = $this->repo->listVendorPaymentReport($request);
    //   dd($data_output);
    //   die();
        return $data_output;
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listDispatchReport($request)
{
    try {
        return $this->repo->listDispatchReport($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listPendingDispatchReport($request)
{
    try {
        $data_output =  $this->repo->listPendingDispatchReport($request);
        return $data_output;
    } catch (\Exception $e) {
        throw $e;
    }
}

public function listDispatchBarChartProductWise($request)
{
    try {
        return $this->repo->listDispatchBarChartProductWise($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listDispatchBarChart($request)
{
    try {
        return $this->repo->listDispatchBarChart($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listVendorWise($request)
{
    try {
        return $this->repo->listVendorWise($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listVendorThroughTakenMaterial($request)
{
    try {
        return $this->repo->listVendorThroughTakenMaterial($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function listVendorThroughTakenMaterialVendorId($request, $id)
{
    return $this->repo->listVendorThroughTakenMaterialVendorId($request, $id);
}
public function getStockItem($request)
{
    try {
        return $this->repo->getStockItem($request);
    } catch (\Exception $e) {
        throw $e;
    }
}
public function getStoreItemStockList($request)
{
    try {
        return $this->repo->getStoreItemStockList($request);
    } catch (\Exception $e) {
        throw $e;
    }
}

}