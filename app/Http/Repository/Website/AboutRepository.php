<?php

namespace App\Http\Repository\Website;

use App\Models\{
    DirectorDesk,
    VisionMission,
    Team
};

class AboutRepository
{


    public function getAllDirectorDesk()
    {
        try {
            $data_output = DirectorDesk::where('is_active', '=', true);
            $data_output =  $data_output->select('description', 'image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllVisionMission()
    {
        try {
            $data_output = VisionMission::where('is_active', '=', true);
            $data_output =  $data_output->select('vision_description', 'mission_description', 'vision_image', 'mission_image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllTeam()
    {
        try {
            $data_output = Team::where('is_active', '=', true);
            $data_output =  $data_output->select('name', 'position', 'image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
