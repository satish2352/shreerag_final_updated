<?php
namespace App\Http\Services\Website;

use App\Http\Repository\Website\ProductServicesRepository;

// use App\Marquee;
use Carbon\Carbon;


class ProductServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new ProductServicesRepository();
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
    
    
    
}