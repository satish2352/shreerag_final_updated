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

    /**
     * Handle estimation submission when amount exceeds the business total_amount limit.
     * Saves the exceeded amount, flags the row, and notifies the owner (status 1300).
     */
    public function updateEstimationExceed($request)
    {
        try {
            $return_data = $this->repo->updateEstimationExceed($request);

            if (isset($return_data['status']) && $return_data['status'] === 'error') {
                return $return_data;
            }

            $productName = $return_data['product_name'];
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

            return ['status' => 'success', 'msg' => 'Estimation submitted. Owner has been notified to review the exceeded amount.'];
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    /**
     * Estimator accepts the owner-suggested amount and re-enters the standard 1149 flow.
     */
    public function acceptOwnerSuggestedAmount($business_details_id)
    {
        try {
            return $this->repo->acceptOwnerSuggestedAmount($business_details_id);
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    /**
     * Owner submits a counter-amount for an exceeded estimation.
     */
    public function ownerSubmitSuggestedAmount($request)
    {
        try {
            return $this->repo->ownerSubmitSuggestedAmount($request);
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    /**
     * Get list of pending exceed-requests for the owner dashboard.
     */
    public function getExceedPendingForOwner()
    {
        try {
            return $this->repo->getExceedPendingForOwner();
        } catch (Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get details for the owner's suggest-amount form.
     */
    public function getExceedDetailForOwner($business_details_id)
    {
        try {
            return $this->repo->getExceedDetailForOwner($business_details_id);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get list of estimations where owner has suggested an amount (status 1301).
     */
    public function getExceedOwnerSuggested()
    {
        try {
            return $this->repo->getExceedOwnerSuggested();
        } catch (Exception $e) {
            return collect([]);
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
