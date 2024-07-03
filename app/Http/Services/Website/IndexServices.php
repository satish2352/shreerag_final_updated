<?php
namespace App\Http\Services\Website;

use App\Http\Repository\Website\IndexRepository;

// use App\Marquee;
use Carbon\Carbon;


class IndexServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
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