<?php

namespace App\Http\Services\Website;

use App\Http\Repository\Website\IndexRepository;
use Exception;

class IndexServices
{

    protected $repo;
    protected $service;

    public function __construct()
    {
        $this->repo = new IndexRepository();
    }
    public function getAllTestimonial()
    {
        try {
            return $this->repo->getAllTestimonial();
        } catch (\Exception $e) {
            return $e;
        }
    }
}
