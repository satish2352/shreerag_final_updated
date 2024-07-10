<?php
namespace App\Http\Services\Organizations\Security;

use App\Http\Repository\Organizations\Security\GatepassRepository;
use Carbon\Carbon;
use App\Models\{
    Gatepass
};

use Config;

class GatepassServices
{
    protected $repo;
    public function __construct()
    {
        $this->repo = new GatepassRepository();
    }


    public function getAll()
    {
        try {
            $data = $this->repo->getAll();
            return $data; // Add this line to return the data
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function addAll($request)
    {
        try {
            $last_id = $this->repo->addAll($request);
            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data Not Added.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }


    public function getById($id)
    {
        try {
            $result = $this->repo->getById($id);
            return $result;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function updateAll($request)
    {
        try {
            $return_data = $this->repo->updateAll($request);

            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Data  Not Updated.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}