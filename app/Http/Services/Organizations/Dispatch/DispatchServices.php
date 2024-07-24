<?php
namespace App\Http\Services\Organizations\Dispatch;
use App\Http\Repository\Organizations\Dispatch\DispatchRepository;
use Carbon\Carbon;

use Config;
class DispatchServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new DispatchRepository();

    }
    public function storeDispatch($request)
    {
        try {
            $data = $this->repo->storeDispatch($request);
          
            if ($data) {
                return ['status' => 'success', 'msg' => 'Rejected Chalan Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Rejected Chalan Not Added.'];
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}