<?php
namespace App\Http\Services\Organizations\Business;

use App\Http\Repository\Organizations\Business\BusinessRepository;
use Carbon\Carbon;
use Config;

class BusinessServices
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new BusinessRepository();
    }

    public function getAll()
    {
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request)
    {
        try {
            $result = $this->repo->addAll($request);
            if ($result['status'] === 'success') {
                return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
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
    {
        try {
            $return_data = $this->repo->updateAll($request);
            if ($return_data['status'] == 'success') {
                return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => $return_data['msg']];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    

    public function deleteByIdAddmore($id){
        try {
            $delete = $this->repo->deleteByIdAddmore($id);
         
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }

    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function acceptPurchaseOrder($id, $business_id)
    {
        try {
            $acceptPurchaseOrder = $this->repo->acceptPurchaseOrder($id, $business_id);
         
            return $acceptPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function rejectedPurchaseOrder($id, $business_id)
    {
        try {
            $rejectedPurchaseOrder = $this->repo->rejectedPurchaseOrder($id, $business_id);
    //   dd($rejectedPurchaseOrder);
    //   die();
            return $rejectedPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getPurchaseOrderBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderBusinessWise($purchase_order_id);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function acceptPurchaseOrderPaymentRelease($id, $business_id)
    {
        try {
            $acceptPurchaseOrderPaymentRelease = $this->repo->acceptPurchaseOrderPaymentRelease($id, $business_id);
        //  dd($acceptPurchaseOrderPaymentRelease);
        //  die();
            return $acceptPurchaseOrderPaymentRelease;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}