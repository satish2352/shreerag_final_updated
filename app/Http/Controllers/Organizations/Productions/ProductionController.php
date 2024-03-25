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
            return redirect('list-accept-design');
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
            return redirect('list-reject-design');
        } catch (\Exception $e) {
            return $e;
        }
    } 
 
}