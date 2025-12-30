<?php

namespace App\Http\Services\Organizations\Estimation;

use App\Http\Repository\Organizations\Estimation\EstimationRepository;
use Exception;
use App\Models\{
    DesignRevisionForProd
};

use Illuminate\Support\Facades\Config;

class EstimationServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {
        $this->repo = new EstimationRepository();
    }

    public function updateAll($request)
    { //checked
        try {
            $return_data = $this->repo->updateAll($request);

            $productName = $return_data['product_name'];
            // $formattedProductName = preg_replace('/_+/', '_', $productName);

            $formattedProductName = preg_replace('/[^A-Za-z0-9_-]/', '_', $productName);
            $formattedProductName = preg_replace('/_+/', '_', $formattedProductName);
            $path = Config::get('FileConstant.DESIGNS_ADD');
            if ($request->hasFile('bom_image')) {
                if ($return_data['bom_image']) {
                    if (file_exists(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['bom_image'])) {
                        removeImage(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['bom_image']);
                    }
                }
                $marathiImageName = $return_data['last_insert_id'] . '_' . $formattedProductName . '_' . rand(100000, 999999) . '.' . $request->bom_image->extension();
                uploadImage($request, 'bom_image', $path, $marathiImageName);
                $slide_data = DesignRevisionForProd::find($return_data['last_insert_id']);
                $slide_data->bom_image = $marathiImageName;
                $slide_data->save();
            }
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Estimation Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Estimation  Not Updated.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function sendToProduction($id)
    { //checked
        try {
            $data_output =  $this->repo->sendToProduction($id);
            return $data_output;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage(),
            ];
        }
    }





    public function updateRevisedEstimation($request)
    { //checked
        try {

            $return_data = $this->repo->updateRevisedEstimation($request);

            $productName = $return_data['product_name'];
            // $formattedProductName = preg_replace('/_+/', '_', $productName);

            $formattedProductName = preg_replace('/[^A-Za-z0-9_-]/', '_', $productName);
            $formattedProductName = preg_replace('/_+/', '_', $formattedProductName);

            $path = Config::get('FileConstant.DESIGNS_ADD');


            if ($request->hasFile('bom_image')) {
                if ($return_data['bom_image']) {
                    if (file_exists(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['bom_image'])) {
                        removeImage(Config::get('DocumentConstant.DESIGNS_DELETE') . $return_data['bom_image']);
                    }
                }
                $marathiImageName = $return_data['last_insert_id'] . '_' . $formattedProductName . '_' . rand(100000, 999999) . '.' . $request->bom_image->extension();
                uploadImage($request, 'bom_image', $path, $marathiImageName);
                $slide_data = DesignRevisionForProd::find($return_data['last_insert_id']);
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
    public function acceptdesign($id)
    {
        try {
            $data_output = $update_data = $this->repo->acceptdesign($id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function rejectdesign($request)
    {
        try {
            $update_data = $this->repo->rejectdesign($request);
            return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function acceptProductionCompleted($id, $completed_quantity)
    {
        try {
            $update_data = $this->repo->acceptProductionCompleted($id, $completed_quantity);

            return $update_data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function destroyAddmoreStoreItem($id)
    {
        try {
            $delete = $this->repo->destroyAddmoreStoreItem($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function editProductQuantityTracking($id)
    {
        try {
            $data_output = $this->repo->editProductQuantityTracking($id);
            return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function editProduct($id)
    {
        try {
            $data_output = $this->repo->editProduct($id);

            return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function updateProductMaterial($request)
    {
        try {
            $result = $this->repo->updateProductMaterial($request);
            return $result;
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
