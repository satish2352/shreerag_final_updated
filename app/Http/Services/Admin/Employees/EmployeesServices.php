<?php
namespace App\Http\Services\Admin\Employees;
use App\Http\Repository\Admin\Employees\EmployeesRepository;
use Carbon\Carbon;
use App\Models\ {
    EmployeesModel
    };

use Config;
class EmployeesServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new EmployeesRepository();
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
            // dd($path);
            $path = Config::get('DocumentConstant.EMPLOYEES_ADD');
            $ImageName = $last_id['ImageName'];
            uploadImage($request, 'image', $path, $ImageName);
           
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
            $path = Config::get('DocumentConstant.EMPLOYEES_ADD');
            if ($request->hasFile('image')) {
                if ($return_data['image']) {
                    if (file_exists_view(Config::get('DocumentConstant.EMPLOYEES_DELETE') . $return_data['image'])) {
                        removeImage(Config::get('DocumentConstant.EMPLOYEES_DELETE') . $return_data['image']);
                    }

                }
                if ($request->hasFile('image')) {
                    $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->file('image')->extension();
                    
                } else {
                    
                }                
                // $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->image->extension();
                uploadImage($request, 'image', $path, $englishImageName);
                $slide_data = EmployeesModel::find($return_data['last_insert_id']);
                $slide_data->emp_image = $englishImageName;
                $slide_data->save();
            }          
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
    public function deleteById($emp_id)
    {
        try {
            $delete = $this->repo->deleteById($emp_id);
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