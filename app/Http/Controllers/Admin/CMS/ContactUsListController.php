<?php

namespace App\Http\Controllers\Admin\CMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\CMS\ContactUsListServices;
use Session;
use Validator;

class ContactUsListController extends Controller
{
    public function __construct(){
        $this->service = new ContactUsListServices();
        }
        public function index(){
            try {
                $get_contactus= $this->service->getAll();
                return view('admin.cms.contactus.list-contactus-form', compact('get_contactus'));
            } catch (\Exception $e) {
                return $e;
            }
        }
        public function show(Request $request) {
            try {
                $contactus = $this->service->getById($request->show_id);
                return view('admin.cms.contactus.show-contactus-form', compact('contactus'));
            } catch (\Exception $e) {
                return $e;
            }
        }
        public function destroy(Request $request){
            $delete_data_id = base64_decode($request->id);
            try {
                $delete_record = $this->service->deleteById($delete_data_id);
                if ($delete_record) {
                    $msg = $delete_record['msg'];
                    $status = $delete_record['status'];
                    if ($status == 'success') {
                        return redirect('delete-contactus-form')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            } catch (\Exception $e) {
                return $e;
            }
        } 
}
