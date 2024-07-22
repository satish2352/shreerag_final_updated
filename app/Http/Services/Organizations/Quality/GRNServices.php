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

    public function getAll()
    {
        try {
            $data = $this->repo->getAll();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDetailsForPurchase($id)
    {
        try {
            $data = $this->repo->getDetailsForPurchase($id);
          
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function storeGRN($request)
    {
        try {
            $data = $this->repo->storeGRN($request);
            // dd($data);
            // die();
            $path = Config::get('DocumentConstant.GRN_ADD');
            $ImageName = $data['ImageName'];
            uploadImage($request, 'image', $path, $ImageName);

            if ($data) {
                return ['status' => 'success', 'msg' => 'GRN Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'GRN Not Added.'];
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListMaterialSentFromQualityBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getAllListMaterialSentFromQualityBusinessWise($id);
        //    dd($data_output);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function addAll($request)
    // {
    //     try {
    //         $result = $this->repo->addAll($request);
    //         if ($result['status'] === 'success') {
    //             return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
    //         }  
    //     } catch (Exception $e) {
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //     }      
    // }

}