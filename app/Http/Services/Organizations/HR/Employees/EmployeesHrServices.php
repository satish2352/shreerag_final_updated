<?php
namespace App\Http\Services\Organizations\HR\Employees;
use App\Http\Repository\Organizations\HR\Employees\EmployeesHrRepository;
use Carbon\Carbon;
use App\Models\ {
    EmployeesModel
    };

use Config;
class EmployeesHrServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new EmployeesHrRepository();
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
            // dd($last_id);
            $path = Config::get('DocumentConstant.EMPLOYEES_ADD');
            $ImageName = $last_id['ImageName'];
            uploadImage($request, 'emp_image', $path, $ImageName);
           
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

    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            // dd($delete);
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