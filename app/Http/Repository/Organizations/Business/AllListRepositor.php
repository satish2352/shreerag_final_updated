<?php
namespace App\Http\Repository\Organizations\Business;
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


    public function getAllListForwardedToDesign(){
        try {

            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
            $data_output= DesignModel::leftJoin('businesses', function($join) {
                $join->on('designs.business_id', '=', 'businesses.id');
              })
              ->leftJoin('business_application_processes', function($join) {
                $join->on('designs.business_id', '=', 'business_application_processes.business_id');
              })
              ->whereIn('business_application_processes.design_status_id',$array_to_be_check)
              ->where('businesses.is_active',true)
              ->select(
                  'businesses.id',
                  'businesses.title',
                  'businesses.descriptions',
                  'businesses.remarks',
                  'businesses.is_active',
                  'designs.business_id',
                  'designs.created_at'

              )->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListCorrectionToDesignFromProduction(){
        try {

            $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED')];

            $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
                $join->on('business_application_processes.business_id', '=', 'production.business_id');
              })
              ->leftJoin('designs', function($join) {
                $join->on('business_application_processes.business_id', '=', 'designs.business_id');
              })
              ->leftJoin('businesses', function($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
              })
              ->leftJoin('design_revision_for_prod', function($join) {
                $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
              })
              ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
              ->where('businesses.is_active',true)
              ->select(
                  'businesses.id',
                  'businesses.title',
                  'businesses.descriptions',
                  'businesses.remarks',
                  'businesses.is_active',
                  'production.business_id',
                  'design_revision_for_prod.reject_reason_prod',
                  'designs.bom_image',
                  'designs.design_image'

              )->get();
            //   dd($data_output);
            return $data_output;
        } catch (\Exception $e) {
            dd($e);
            return $e;
        }
    } 

}