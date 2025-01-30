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
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllVisionMission()
 {
        try {
            return $this->repo->getAllVisionMission();
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getById( $id ) {
        try {
            $dataOutputByid = DepartmentsModel::find( $id );
            if ( $dataOutputByid ) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch ( \Exception $e ) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

    public function updateAll( $request ) {
        try {

            $return_data = array();

            $dataOutput = DepartmentsModel::find( $request->id );

            if ( !$dataOutput ) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $dataOutput->department_name = $request->department_name;

            $dataOutput->save();
            $return_data[ 'image' ] = $previousEnglishImage;
            return  $return_data;

        } catch ( \Exception $e ) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function deleteById( $id ) {
        try {
            $deleteDataById = DepartmentsModel::find( $id );
            $deleteDataById->delete();
            return $deleteDataById;

        } catch ( \Exception $e ) {
            return $e;
        }
    }

}