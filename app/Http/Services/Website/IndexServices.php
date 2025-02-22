<?php
namespace App\Http\Services\Website;
use App\Http\Repository\Website\IndexRepository;
use Carbon\Carbon;

class IndexServices
{

	protected $repo;
    public function __construct()
    {
        $this->repo = new IndexRepository();
    } 
    public function getAllTestimonial(){
        try {
            return $this->repo->getAllTestimonial();
        } catch (\Exception $e) {
            return $e;
        }
    }     
}