<?php

namespace App\Http\Services\Organizations\Dispatch;

use App\Http\Repository\Organizations\Dispatch\DispatchRepository;
use Exception;

class DispatchServices
{
    protected $repo;
    protected $service;

    public function __construct()
    {

        $this->repo = new DispatchRepository();
    }
    public function storeDispatch($request)
    {
        try {
            $data = $this->repo->storeDispatch($request);
            if ($data) {
                return ['status' => 'success', 'msg' => 'Dispatch completed successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Dispatch Not Added.'];
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
