<?php
namespace App\Http\Repository\Organizations\Designers;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd
    };
use Config;

class AllListRepositor  {


  public function getAllListDesignRecievedForCorrection(){
    try {

        $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

        $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
          })
          ->leftJoin('designs', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
          })
          ->leftJoin('businesses_details', function($join) {
            $join->on('production.business_details_id', '=', 'businesses_details.id');
        })
          ->leftJoin('businesses', function($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
          ->leftJoin('design_revision_for_prod', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
          })
          ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
          ->where('businesses.is_active',true)
          ->select(
              'businesses.id',
              'businesses.title',
              'businesses.customer_po_number',
              'businesses_details.product_name',
              'businesses_details.quantity',
              'businesses_details.description',
              'businesses.remarks',
              'businesses.is_active',
              'production.business_id',
              'design_revision_for_prod.reject_reason_prod',
              'design_revision_for_prod.id as design_revision_for_prod_id',
              // 'design_revision_for_prod.id as reject_reason_prod',
              'designs.bom_image',
              'designs.design_image'

          )
          ->distinct('design_revision_for_prod.id')
          ->get();
        return $data_output;
    } catch (\Exception $e) {
        
        return $e;
    }
}

}