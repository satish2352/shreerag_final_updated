<?php
namespace App\Http\Services\Admin\CMS;
use App\Http\Repository\Admin\CMS\VisionMissionRepository;
use Carbon\Carbon;
use App\Models\ {
    VisionMission
    };

use Config;
class VisionMissionServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new VisionMissionRepository();
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
            $path = Config::get('DocumentConstant.VISION_MISSION_ADD');
            $visionImageName = $last_id['visionImageName'];
            $missionImageName = $last_id['missionImageName'];
            uploadImage($request, 'vision_image', $path, $visionImageName);
            uploadImage($request, 'mission_image', $path, $missionImageName);

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
            
            $path = Config::get('DocumentConstant.VISION_MISSION_ADD');
            if ($request->hasFile('vision_image')) {
                if ($return_data['vision_image']) {
                    if (file_exists_view(Config::get('DocumentConstant.VISION_MISSION_DELETE') . $return_data['vision_image'])) {
                        removeImage(Config::get('DocumentConstant.VISION_MISSION_DELETE') . $return_data['vision_image']);
                    }

                }
                $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_english.' . $request->vision_image->extension();
                uploadImage($request, 'vision_image', $path, $englishImageName);
                $vision_data = VisionMission::find($return_data['last_insert_id']);
                $vision_data->vision_image = $englishImageName;
                $vision_data->save();
            }
    
            if ($request->hasFile('mission_image')) {
                if ($return_data['mission_image']) {
                    if (file_exists_view(Config::get('DocumentConstant.VISION_MISSION_DELETE') . $return_data['mission_image'])) {
                        removeImage(Config::get('DocumentConstant.VISION_MISSION_DELETE') . $return_data['mission_image']);
                    }
                }
                $marathiImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_marathi.' . $request->mission_image->extension();
                uploadImage($request, 'mission_image', $path, $marathiImageName);
                $vision_data = VisionMission::find($return_data['last_insert_id']);
                $vision_data->mission_image = $marathiImageName;
                $vision_data->save();
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
}