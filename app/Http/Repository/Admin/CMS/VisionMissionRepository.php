<?php

namespace App\Http\Repository\Admin\CMS;

use App\Models\{
    VisionMission
};

class VisionMissionRepository
{

    public function getAll()
    {
        try {
            $data_output = VisionMission::orderBy('updated_at', 'desc')->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request)
    {
        try {
            $data = array();
            $dataOutput = new VisionMission();
            $dataOutput->vision_description = $request['vision_description'];
            $dataOutput->mission_description = $request['mission_description'];

            $dataOutput->save();

            $last_insert_id = $dataOutput->id;

            $visionImageName = $last_insert_id . '_' . rand(100000, 999999) . '_vision.' . $request->vision_image->extension();
            $missionImageName = $last_insert_id . '_' . rand(100000, 999999) . '_mission.' . $request->mission_image->extension();

            $slide = VisionMission::find($last_insert_id);
            // Assuming $request directly contains the ID
            $slide->vision_image = $visionImageName;
            // Save the image filename to the database
            $slide->mission_image = $missionImageName;
            // Save the image filename to the database
            $slide->save();

            $data['visionImageName'] = $visionImageName;
            $data['missionImageName'] = $missionImageName;
            return $data;
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

    public function getById($id)
    {
        try {
            $dataOutputByid = VisionMission::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by id Data.',
                'status' => 'error'
            ];
        }
    }

    public function updateAll($request)
    {
        try {
            $return_data = array();
            $vision_mission_data = VisionMission::find($request->id);

            if (!$vision_mission_data) {
                return [
                    'msg' => 'Vision Mission not found.',
                    'status' => 'error'
                ];
            }

            // Store the previous image names
            $previousEnglishImage = $vision_mission_data->vision_image;
            $previousMarathiImage = $vision_mission_data->mission_image;

            // Update the fields from the request
            $vision_mission_data->vision_description = $request['vision_description'];
            $vision_mission_data->mission_description = $request['mission_description'];

            $vision_mission_data->save();
            $last_insert_id = $vision_mission_data->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['vision_image'] = $previousEnglishImage;
            $return_data['mission_image'] = $previousMarathiImage;
            return  $return_data;
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Vision Mission.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }
}
