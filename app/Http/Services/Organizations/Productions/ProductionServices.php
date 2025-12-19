<?php

namespace App\Http\Services\Organizations\Productions;

use App\Http\Repository\Organizations\Productions\ProductionRepository;
use Exception;

class ProductionServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {
        $this->repo = new ProductionRepository();
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
    { //checked
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
