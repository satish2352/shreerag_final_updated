<?php
namespace App\Http\Services\Website;
use App\Http\Repository\Website\ContactUsRepository;
use Carbon\Carbon;

class ContactUsServices
{
	protected $repo;
    public function __construct()
    {
        $this->repo = new ContactUsRepository();
    }
    public function addAll($request){
        try {
            $add_contact = $this->repo->addAll($request);
            if ($add_contact) {
                return ['status' => 'success', 'msg' => 'Contact Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Contact get Not Added.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    } 
}