<?php
namespace App\Http\Services\Organizations\Purchase;
use App\Http\Repository\Organizations\Purchase\ItemRepository;

use Config;
class ItemServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new ItemRepository();
    }
    public function getAll(){
        try {
            $data_output = $this->repo->getAll();
            return $data_output;
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
    public function getById($id){
        try {
            return $this->repo->getById($id);
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