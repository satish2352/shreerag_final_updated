<?php
namespace App\Http\Services\Admin\CMS;
use App\Http\Repository\Admin\CMS\TestimonialRepository;
use Carbon\Carbon;
use App\Models\ {
    Testimonial
    };

use Config;
class TestimonialServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new TestimonialRepository();
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
            $path = Config::get('DocumentConstant.TESTIMONIAL_ADD');
            $ImageName = $last_id['ImageName'];
            uploadImage($request, 'image', $path, $ImageName);
           
            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data get Not Added.'];
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
          
            $path = Config::get('DocumentConstant.TESTIMONIAL_ADD');
            if ($request->hasFile('image')) {
                if ($return_data['image']) {
                    if (file_exists_view(Config::get('DocumentConstant.SERVICES_DELETE') . $return_data['image'])) {
                        removeImage(Config::get('DocumentConstant.SERVICES_DELETE') . $return_data['image']);
                    }

                }
                if ($request->hasFile('image')) {
                    $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->file('image')->extension();
                    
                    // Rest of your code...
                } else {
                    // Handle the case where 'image' key is not present in the request.
                    // For example, you might want to skip the file handling or return an error message.
                }                
                // $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->image->extension();
                uploadImage($request, 'image', $path, $englishImageName);
                $aboutus_data = Testimonial::find($return_data['last_insert_id']);
                $aboutus_data->image = $englishImageName;
                $aboutus_data->save();
              
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


   
    public function updateOne($id){
        return $this->repo->updateOne($id);
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