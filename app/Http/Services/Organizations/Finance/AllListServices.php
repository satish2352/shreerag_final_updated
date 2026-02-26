<?php

namespace App\Http\Services\Organizations\Finance;

use App\Http\Repository\Organizations\Finance\AllListRepository;
use Exception;

class AllListServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {

        $this->repo = new AllListRepository();
    }
    public function getAllListBusinessDetails()
    {
        try {
            $data_output = $this->repo->getAllListBusinessDetails();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListSRAndGRNGeanrated()
    {
        try {
            $data_output = $this->repo->getAllListSRAndGRNGeanrated();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSRAndGRNGeanratedBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getAllListSRAndGRNGeanratedBusinessWise($id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listAcceptedGrnSrnFinance($purchase_orders_id)
    {
        try {
            return $this->repo->listAcceptedGrnSrnFinance($purchase_orders_id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listPOSentForApprovaTowardsOwner()
    {
        try {
            $data_output = $this->repo->listPOSentForApprovaTowardsOwner();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listPOSanctionAndNeedToDoPaymentToVendor()
    {
        try {
            $data_output = $this->repo->listPOSanctionAndNeedToDoPaymentToVendor();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListBusinessReceivedFromLogistics()
    {
        try {
            $data_output = $this->repo->getAllListBusinessReceivedFromLogistics();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListBusinessFianaceSendToDispatch()
    {
        try {
            $data_output = $this->repo->getAllListBusinessFianaceSendToDispatch();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
