<?php
namespace App\Http\Services\Organizations\Quality;

use App\Http\Repository\Organizations\Quality\GRNRepository;
use Carbon\Carbon;
use App\Models\{
    DesignModel
};

use Config;
class GRNServices
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new GRNRepository();
    }
    public function getAll(){
        try {
            $data = $this->repo->getAll();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getDetailsForPurchase($id){
        try {
            $data = $this->repo->getDetailsForPurchase($id);
          
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function storeGRN($request){
        try {
            $data = $this->repo->storeGRN($request);
            $path = Config::get('DocumentConstant.GRN_ADD');
            $ImageName = $data['ImageName'];
            uploadImage($request, 'image', $path, $ImageName);
    
            if ($data) {
                return ['status' => 'success', 'msg' => 'GRN Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'GRN Not Added.'];
            }
        } catch (\Exception $e) {
            \Log::error('Error in storeGRN: ' . $e->getMessage());
            return [
                'status' => 'error',
                'msg' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
    public function getAllListMaterialSentFromQualityBusinessWise($request, $id){
        return $this->repo->getAllListMaterialSentFromQualityBusinessWise($request, $id);
    }
    public function getAllRejectedChalanList(){
        try {
            $data = $this->repo->getAllRejectedChalanList();
            return $data;
        } catch (\Exception $e) {
        
            return $e; 
        }
    }
}