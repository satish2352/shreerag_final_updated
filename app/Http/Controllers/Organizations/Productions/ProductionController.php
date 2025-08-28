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
use App\Models\ {
    PartItem,
    UnitMaster
}
;

class ProductionController extends Controller
 {
    private $listapi;
    public function __construct( AllListController $listapi ) {
        $this->service = new ProductionServices();
        $this->listapiservice = new AllListController();
    }

    public function acceptdesign( $id ) {
        try {
            $acceptdesign = base64_decode( $id );

            $update_data = $this->service->acceptdesign( $acceptdesign );
            return redirect( 'proddept/list-accept-design' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function rejectdesignedit( $idtoedit ) { //checked
        try {
            return view( 'organizations.productions.product.reject-design', compact( 'idtoedit' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function rejectdesign( Request $request ) { //checked
        try {
            $update_data = $this->service->rejectdesign( $request );
            return redirect( 'proddept/list-reject-design' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function editProductQuantityTracking( $id ) {
        try {
            $editData = $this->service->editProductQuantityTracking( $id );
            $dataOutputPartItem = PartItem::where( 'is_active', true )->get();

            return view( 'organizations.productions.product.edit-recived-bussinesswise-quantity-tracking', [
                'productDetails' => $editData[ 'productDetails' ],
                'dataGroupedById' => $editData[ 'dataGroupedById' ],
                'dataOutputPartItem' => $dataOutputPartItem,
                'id' => $id
            ] );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function acceptProductionCompleted( Request $request, $id )
 {
        try {
            // Get the completed quantity from the form request
            $completed_quantity = $request->input( 'completed_quantity' );

            // Call the service layer with both $id and $completed_quantity
            $update_data = $this->service->acceptProductionCompleted( $id, $completed_quantity );

            return redirect( 'proddept/list-final-production-completed' )->with( 'update_data', $update_data );

        } catch ( \Exception $e ) {
            return redirect()->back()->with( 'error', 'An error occurred: ' . $e->getMessage() );
        }
    }
    public function editProduct($id) {
        try {
            $editData = $this->service->editProduct($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            // $dataOutputUser = User::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.productions.product.edit-recived-inprocess-production-material', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster'=>$dataOutputUnitMaster,
                // 'dataOutputUser'=>$dataOutputUser,
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
    
            if ($updateData['status'] == 'success') {
                return redirect('proddept/list-material-received')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function destroyAddmoreStoreItem( Request $request )
    {
   
           $delete_data_id = $request->delete_id;
           // Get the delete ID from the request
   
           try {
               $delete_record = $this->service->destroyAddmoreStoreItem( $delete_data_id );
               if ( $delete_record ) {
                   $msg = $delete_record[ 'msg' ];
                   $status = $delete_record[ 'status' ];
                   if ( $status == 'success' ) {
                       return redirect( 'proddept/list-material-received' )->with( compact( 'msg', 'status' ) );
                   } else {
                       return redirect()->back()->withInput()->with( compact( 'msg', 'status' ) );
                   }
               }
           } catch ( \Exception $e ) {
               return $e;
           }
       }
}