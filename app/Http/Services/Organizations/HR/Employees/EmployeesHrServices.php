<?php
namespace App\Http\Services\Organizations\HR\Employees;
use App\Http\Repository\Organizations\HR\Employees\EmployeesHrRepository;
use Carbon\Carbon;
use App\Models\ {
    EmployeesModel
};

use Config;

class EmployeesHrServices
 {
    protected $repo;

    public function __construct() {
        $this->repo = new EmployeesHrRepository();
    }

    public function getAll() {
        try {
            return $this->repo->getAll();
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function index() {
        $data_users = $this->repo->getUsersList();
        return $data_users;
    }

    public function register( $request ) {
        try {

            $chk_dup = $this->repo->checkDupCredentials( $request );
            if ( sizeof( $chk_dup )>0 ){
                return [ 'status'=>'failed', 'msg'=>'Registration Failed. The name has already been taken.' ];
            } else {
                $last_id = $this->repo->register( $request );
            
                if ( $last_id ) {
                    return [ 'status' => 'success', 'msg' => 'User Added Successfully.' ];
                } else {
                    return [ 'status' => 'error', 'msg' => 'User get Not Added.' ];
                }

            }

        } catch ( Exception $e ) {
            return [ 'status' => 'error', 'msg' => $e->getMessage() ];
        }

    }

    public function update( $request ) {
        $user_register_id = $this->repo->update( $request );
        return [ 'status'=>'success', 'msg'=>'Data Updated Successful.' ];
    }

    public function editUsers( $request ) {
        $data_users = $this->repo->editUsers( $request );
        return $data_users;
    }

    public function deleteById( $id ){
        try {
            $delete = $this->repo->deleteById( $id );
            if ( $delete ) {
                return [ 'status' => 'success', 'msg' => 'Deleted Successfully.' ];
            } else {
                return [ 'status' => 'error', 'msg' => ' Not Deleted.' ];
            }

        } catch ( Exception $e ) {
            return [ 'status' => 'error', 'msg' => $e->getMessage() ];
        }

    }

    public function getById($id){
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showParticularDetails($id){
        try {
            return $this->repo->showParticularDetails($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getProfile( $request ) {
        $data_users = $this->repo->getProfile( $request );
        return $data_users;
    }

}