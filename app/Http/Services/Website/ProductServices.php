<?php

namespace App\Http\Services\Website;

use App\Http\Repository\Website\ProductServicesRepository;
use Exception;

class ProductServices
{

    protected $repo;

    protected $service;

    public function __construct()
    {
        $this->repo = new ProductServicesRepository();
    }
    public function getAllProductLimit()
    {
        try {
            return $this->repo->getAllProductLimit();
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllProduct()
    {
        try {
            return $this->repo->getAllProduct();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllServices()
    {
        try {
            return $this->repo->getAllServices();
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllTeam()
    {
        try {
            return $this->repo->getAllTeam();
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getByIdProducts($id)
    {
        try {
            return $this->repo->getByIdProducts($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
