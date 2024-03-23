<?php
namespace App\Http\Services\Admin\Departments;
use App\Http\Repository\Admin\Departments\DepartmentsRepository;
use Carbon\Carbon;
use App\Models\ {
    EmployeesModel
    };

use Config;
class DepartmentsServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new DepartmentsRepository();
    }


    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request){
      try {
          $last_id = $this->repo->addAll($request);
          if ($last_id) {
              return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
          } else {
              return ['status' => 'error', 'msg' => ' Data Not Added.'];
          }  
      } catch (Exception $e) {
          return ['status' => 'error', 'msg' => $e->getMessage()];
      }      
    }
    public function getById($emp_id){
        try {
            return $this->repo->getById($emp_id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateAll($request){
        try {
            $return_data = $this->repo->updateAll($request);
            
      
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Data  Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }


   
    // public function updateOne($id){
    //     return $this->repo->updateOne($id);
    // }   
    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }
}