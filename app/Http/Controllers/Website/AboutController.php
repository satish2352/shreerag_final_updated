<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Website\AboutServices;
use Session;
use Validator;

class AboutController extends Controller
 {
    public function __construct()
 {
        $this->service = new AboutServices();
    }

    public function index( Request $request )
 {
        try {
            $data_director_desk = $this->service->getAllDirectorDesk();
            $data_output_vision_mission = $this->service->getAllVisionMission();
            $data_output_team = $this->service->getAllTeam();

            return view( 'website.pages.about', compact( 'data_director_desk', 'data_output_vision_mission', 'data_output_team' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

}

