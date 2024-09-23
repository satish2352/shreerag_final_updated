<?php

namespace App\Http\Controllers\Organizations\Productions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Productions\ProductionServices;
use App\Http\Controllers\Organizations\Productions\AllListController;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    PartItem
};
class ProductionController extends Controller
{ 
    private $listapi;
    public function __construct(AllListController $listapi){
        $this->service = new ProductionServices();
        $this->listapiservice = new AllListController();
    }
    
    
    public function acceptdesign($id){
        try {
            $acceptdesign = base64_decode($id);
           
            $update_data = $this->service->acceptdesign($acceptdesign);
            // dd($update_data);
            // die();
            return redirect('proddept/list-accept-design');
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function rejectdesignedit($idtoedit){
        try {
            
            return view('organizations.productions.product.reject-design', compact('idtoedit'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function rejectdesign(Request $request){
        try {
            $update_data = $this->service->rejectdesign($request);
            return redirect('proddept/list-reject-design');
        } catch (\Exception $e) {
            return $e;
        }
    } 
    // public function acceptProductionCompleted($id){
    //     try {
    //         // $accepted = base64_decode($id);
    //         $update_data = $this->service->acceptProductionCompleted($id);
    //         return redirect('proddept/list-final-production-completed', compact('update_data'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 
    public function acceptProductionCompleted($id){
        try {
            $update_data = $this->service->acceptProductionCompleted($id);
    
            // Redirect to the specified URL without passing the array
            return redirect('proddept/list-final-production-completed')->with('status', $update_data);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function editProduct($id) {
        try {
            $editData = $this->service->editProduct($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            
            return view('organizations.productions.product.edit-recived-bussinesswise', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
    public function updateProductMaterial(Request $request) {
        $rules = [
        ];
    
        $messages = [
        ];
    
        $validation = Validator::make($request->all(), $rules, $messages);
    
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
    
        try {
            $updateData = $this->service->updateProductMaterial($request);
    // dd($updateData);
    // die();
            if ($updateData['status'] == 'success') {
                return redirect('proddept/list-material-recived')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
    
}