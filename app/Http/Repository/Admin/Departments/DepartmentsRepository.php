<?php
namespace App\Http\Services\Website\AboutUs;

use App\Http\Repository\Website\AboutUs\VisionMissionRepository;

// use App\Marquee;
use Carbon\Carbon;


class AboutRepository
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new VisionMissionRepository();
    } 

    public function getAllAboutus()
    {
        try {
            return $this->repo->getAllAboutus();
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function getAllVisionMission()
    {
        try {
            return $this->repo->getAllVisionMission();
        } catch (\Exception $e) {
            return $e;
        }
    }   
}