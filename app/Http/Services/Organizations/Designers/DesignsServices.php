<?php
namespace App\Http\Services\Organizations\Designers;
use App\Http\Repository\Organizations\Designers\DesignsRepository;
use Carbon\Carbon;
use App\Models\ {
    DesignModel
    };

use Config;
    class DesignsServices
    {
        protected $repo;
        public function __construct(){
        $this->repo = new DesignsRepository();
    }

    
    public function getAllNewRequirement(){
        try {
            return $this->repo->getAllNewRequirement();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
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
           
            $last_id = $this->repo->updateAll($request);
            $path = Config::get('FileConstant.DESIGNS_ADD');
            $designImageName = $last_id['designImageName'];
            $bomImageName = $last_id['bomImageName'];
            uploadImage($request, 'design_image', $path, $designImageName);
            uploadImage($request, 'bom_image', $path, $bomImageName);

            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data get Not Added.'];
            } 
        } catch (Exception $e) {
            // If an exception occurs, return error response with the error message
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function updateReUploadDesign($request){
        try {
           
            $last_id = $this->repo->updateReUploadDesign($request);
            $path = Config::get('FileConstant.DESIGNS_ADD');
            $designImageName = $last_id['designImageName'];
            $bomImageName = $last_id['bomImageName'];
            uploadImage($request, 'design_image', $path, $designImageName);
            uploadImage($request, 'bom_image', $path, $bomImageName);

            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data get Not Added.'];
            } 
        } catch (Exception $e) {
            // If an exception occurs, return error response with the error message
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}