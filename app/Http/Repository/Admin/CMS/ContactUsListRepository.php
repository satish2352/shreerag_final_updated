<?php
namespace App\Http\Repository\Admin\CMS;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    ContactUs
};
use Config;

class ContactUsListRepository {

    public function getAll() {
        try {
            return ContactUs::orderBy( 'id', 'desc' )->get();
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getById( $id ){
        try {
            $contactus_data = ContactUs::find( $id );
            if ( $contactus_data ) {
                return $contactus_data;
            } else {
                return null;
            }
        } catch ( \Exception $e ) {
            return $e;
            return [
                'msg' => 'Failed to get by id Scolarship List.',
                'status' => 'error'
            ];
        }
    }
    public function deleteById( $id ) {
        try {
            $deleteDataById = ContactUs::find( $id );

            if ( $deleteDataById ) {
                $deleteDataById->delete();
                return $deleteDataById;
            } else {
                return null;
            }
        } catch ( \Exception $e ) {
            return $e;
        }
    }
}