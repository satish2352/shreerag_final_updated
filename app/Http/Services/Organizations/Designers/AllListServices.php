<?php

namespace App\Http\Services\Organizations\Designers;

use App\Http\Repository\Organizations\Designers\AllListRepositor;
use Exception;

class AllListServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {

        $this->repo = new AllListRepositor();
    }
    public function acceptdesignbyProduct()
    {
        try {
            $data_output = $this->repo->acceptdesignbyProduct();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListCorrectedDesignSendToProduction()
    {
        try {
            $data_output = $this->repo->getAllListCorrectedDesignSendToProduction();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListDesignRecievedForCorrection()
    {
        try {
            $data_output =  $this->repo->getAllListDesignRecievedForCorrection();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListCorrectionToDesignFromProduction()
    {
        try {
            return $this->repo->getAllListCorrectionToDesignFromProduction();
        } catch (\Exception $e) {
            return $e;
        }
    }
}
