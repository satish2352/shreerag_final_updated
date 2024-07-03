<?php
namespace App\Http\Services\Website;

use App\Http\Repository\Website\AboutRepository;

// use App\Marquee;
use Carbon\Carbon;


class AboutServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new AboutRepository();
    } 

    public function getAllDirectorDesk()
    {
        try {
            return $this->repo->getAllDirectorDesk();
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

    public function getAllTeam()
    {
        try {
            return $this->repo->getAllTeam();
        } catch (\Exception $e) {
            return $e;
        }
    } 
    
    
    
}