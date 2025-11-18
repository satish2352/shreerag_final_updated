<?php

namespace App\Http\Services\Organizations\Dispatch;

use App\Http\Repository\Organizations\Dispatch\AllListRepository;
use Exception;

class AllListServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {

        $this->repo = new AllListRepository();
    }


    public function getAllReceivedFromFianance()
    {
        try {
            $data_output = $this->repo->getAllReceivedFromFianance();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatch()
    {
        try {
            $data_output = $this->repo->getAllDispatch();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatchClosedProduct()
    {
        try {
            $data_output = $this->repo->getAllDispatchClosedProduct();
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
