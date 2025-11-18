<?php

namespace App\Http\Services\Organizations\Designers;

use Illuminate\Support\Facades\Log;
use App\Http\Repository\Organizations\Designers\DesignsRepository;
use Exception;
use App\Models\{
    DesignModel
};

use Illuminate\Support\Facades\Config;

class DesignsServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {
        $this->repo = new DesignsRepository();
    }


    public function getAllNewRequirement()
    {   //checked
        try {
            $data_output = $this->repo->getAllNewRequirement();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllNewRequirementBusinessWise($id)
    { //checked
        try {
            $data_output = $this->repo->getAllNewRequirementBusinessWise($id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getById($id)
    {
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateAll($request)
    { //checked
        try {
            $return_data = $this->repo->updateAll($request);
            $productName = $return_data['product_name'];
            $formattedProductName = preg_replace('/_+/', '_', $productName);
            $path = Config::get('FileConstant.DESIGNS_ADD');
            if ($request->hasFile('design_image')) {
                if ($return_data['design_image']) {
                    if (file_exists(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['design_image'])) {
                        removeImage(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['design_image']);
                    }
                }
                $englishImageName = $return_data['last_insert_id'] . '_' . $formattedProductName . '_' . rand(100000, 999999) . '.' . $request->design_image->extension();
                uploadImage($request, 'design_image', $path, $englishImageName);
                $slide_data = DesignModel::find($return_data['last_insert_id']);
                $slide_data->design_image = $englishImageName;
                $slide_data->save();
            }

            if ($request->hasFile('bom_image')) {
                if ($return_data['bom_image']) {
                    if (file_exists(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['bom_image'])) {
                        removeImage(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['bom_image']);
                    }
                }
                $marathiImageName = $return_data['last_insert_id'] . '_' . $formattedProductName . '_' . rand(100000, 999999) . '.' . $request->bom_image->extension();
                uploadImage($request, 'bom_image', $path, $marathiImageName);
                $slide_data = DesignModel::find($return_data['last_insert_id']);
                $slide_data->bom_image = $marathiImageName;
                $slide_data->save();
            }

            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Design Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Design  Not Updated.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getUploadedDesignSendEstimation()
    { //checked
        try {
            return $this->repo->getUploadedDesignSendEstimation();
        } catch (\Exception $e) {
            Log::error('Service getAll() error: ' . $e->getMessage());
            throw $e;
        }
    }
    public function updateReUploadDesign($request)
    { //checked
        try {

            $last_id = $this->repo->updateReUploadDesign($request);
            $path = Config::get('FileConstant.DESIGNS_ADD');
            $designImageName = $last_id['designImageName'];
            // $bomImageName = $last_id['bomImageName'];
            uploadImage($request, 'design_image', $path, $designImageName);
            // uploadImage($request, 'bom_image', $path, $bomImageName);

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
