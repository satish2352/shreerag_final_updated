<?php

namespace App\Http\Controllers\Organizations\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Report\ReportServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Exports\DesignReportExport;
use App\Http\Controllers\Exports\ProductionReportExport;
use App\Http\Controllers\Exports\SecurityReportExport;
use App\Http\Controllers\Exports\GRNReportExport;
use App\Http\Controllers\Exports\LogisticsReportExport;



use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    Business,
    Vendors,
PurchaseOrdersModel,
BusinessDetails,
PartItem,
UnitMaster
}
;

class ReportController extends Controller
{ 
    public function __construct(){
        $this->service = new ReportServices();
    }
public function getCompletedProductList(Request $request){
    try {
        $data_output = $this->service->getCompletedProductList($request);
          $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        $getProductName = BusinessDetails::whereNotNull('product_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('product_name', 'id');
      return view('organizations.report.list-report-product-completed', [
    'data_output' => $data_output['data'] ?? [],
    'total_count' => $data_output['total_count'] ?? 0,
    'getProjectName' => $getProjectName,
    'getProductName' => $getProductName,
]);

        // return view('organizations.report.list-report-product-completed', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function getCompletedProductListAjax(Request $request){
        try {
            $data = $this->service->getCompletedProductList($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.design-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape'); // <-- Landscape

                return $pdf->download('DesignReport.pdf');
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new DesignReportExport($data['data']), 'DesignReport.xlsx');
            }

            // Normal AJAX response
            return response()->json([
                'status' => true,
                'data' => $data['data'],
                'pagination' => $data['pagination']
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

public function listDesignReport( Request $request ) {
        try {
            $data = $this->service->listDesignReport($request);
       
          $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');
            return view( 'organizations.report.list-accept-design-by-production', compact( 'data','getProjectName' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
public function listDesignReportAjax(Request $request){
        try {
            $data = $this->service->listDesignReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.design-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape'); // <-- Landscape

                return $pdf->download('DesignReport.pdf');
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new DesignReportExport($data['data']), 'DesignReport.xlsx');
            }

            // Normal AJAX response
            return response()->json([
                'status' => true,
                'data' => $data['data'],
                'pagination' => $data['pagination']
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
public function getSecurityReport( Request $request ) {
        try {
            $data = $this->service->getSecurityReport($request);
       
          $getProjectName = Vendors::whereNotNull('vendor_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('vendor_name', 'id');
            $getPurchaseOrder = PurchaseOrdersModel::whereNotNull('purchase_orders_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('purchase_orders_id', 'purchase_orders_id'); // use po number as key & value

           
            return view( 'organizations.report.security-report', compact( 'data','getProjectName','getPurchaseOrder' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
public function getSecurityReportAjax(Request $request)
    {
        try {
            $data = $this->service->getSecurityReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.security-report-pdf', ['data' => $data['data']])
                    ->setPaper('a4'); // <-- Landscape

                return $pdf->download('DesignReport.pdf');
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new SecurityReportExport($data['data']), 'DesignReport.xlsx');
            }

            // Normal AJAX response
            return response()->json([
                'status' => true,
                'data' => $data['data'],
                'pagination' => $data['pagination']
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
public function getGRNReport( Request $request ) {
        try {
            $data = $this->service->getGRNReport($request);
       
           $getProjectName = Vendors::whereNotNull('vendor_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('vendor_name', 'id');
             $getPurchaseOrder = PurchaseOrdersModel::whereNotNull('purchase_orders_id')
    ->where('is_deleted', 0)
    ->where('is_active', 1)
    ->pluck('purchase_orders_id', 'purchase_orders_id');
            return view( 'organizations.report.grn-report', compact( 'data','getProjectName','getPurchaseOrder' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
public function getGRNReportAjax(Request $request){
    try {
        $data = $this->service->getGRNReport($request);

        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.grn-report-pdf', ['data' => $data['data']])
                ->setPaper('a3', 'landscape');
            return $pdf->download('DesignReport.pdf');
        }

        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new GRNReportExport($data['data']), 'DesignReport.xlsx');
        }

        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
}
// public function listConsumptionReport( Request $request ) {
//         try {
//             $data = $this->service->getConsumptionReport($request);
       
//        $getProjectName = Business::whereNotNull('project_name')
//             ->where('is_deleted', 0)
//             ->where('is_active', 1)
//             ->pluck('project_name', 'id');
//     $getProductName = BusinessDetails::whereNotNull('product_name')
//             ->where('is_deleted', 0)
//             ->where('is_active', 1)
//             ->pluck('product_name', 'id');
           
//             return view( 'organizations.report.consumption-report', compact( 'data','getProjectName', 'getProductName') );
//         } catch ( \Exception $e ) {
//             return $e;
//         }
//     }
public function listConsumptionReport(Request $request)
{
    $getProjectName = Business::whereNotNull('project_name')
        ->where('is_deleted', 0)
        ->where('is_active', 1)
        ->pluck('project_name', 'id');

    if ($request->filled('export_type')) {
        $data = $this->service->getConsumptionReport($request)['data'];

        if ($request->export_type == 1) {
            $pdf = Pdf::loadView('exports.consumption-report-pdf', ['data' => $data]);
            return $pdf->download('consumption_report.pdf');
        }

        if ($request->export_type == 2) {
            return Excel::download(new ConsumptionReportExport($data), 'consumption_report.xlsx');
        }
    }

    return view('organizations.report.consumption-report', compact('getProjectName'));
}

public function listConsumptionReportAjax(Request $request){
    try {
        $response = $this->service->getConsumptionReport($request);

        // Validate structure before using as array
        if (!is_array($response) || !isset($response['data'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid data format returned from service.',
            ]);
        }

        // Handle PDF export
        if ($request->filled('export_type') && $request->export_type == 1) {
           
            $pdf = Pdf::loadView('exports.consumption-report-pdf', [
                'data' => $response['data']
            ])->setPaper('a4');
            return $pdf->download('ConsumptionReport.pdf');
        }

        // Handle Excel export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new ConsumptionReport($response['data']), 'ConsumptionReport.xlsx');
        }

        // Normal JSON response for AJAX
        return response()->json([
            'status' => true,
            'data' => $response['data'],
            'pagination' => $response['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ]);
    }
}
public function getConsumptionMaterialList($id){
    try {
        $id = $id;

        $editData = $this->service->getConsumptionMaterialList($id);

      

        $dataOutputPartItem = PartItem::where('is_active', true)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();

        return view('organizations.report.consumption-material-list', [
            'productDetails' => $editData['productDetails'],
            'dataGroupedById' => $editData['dataGroupedById'],
            'dataOutputPartItem' => $dataOutputPartItem,
            'dataOutputUnitMaster' => $dataOutputUnitMaster,
            'id' => $id
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
    }
}
public function getProductsByProject($id){
    try {
        $products = BusinessDetails::where('business_id', $id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->pluck('product_name', 'id');

        $response = [];
        foreach ($products as $id => $name) {
            $response[] = ['id' => $id, 'name' => $name];
        }

        return response()->json([
            'status' => true,
            'products' => $response
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    }
}
public function listItemStockReport( Request $request ) {
        try {
     

        $editData = $this->service->listItemStockReport();

      

        $dataOutputPartItem = PartItem::where('is_active', true)->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();

        return view('organizations.report.consumption-material-list', [
            'productDetails' => $editData['productDetails'],
            'dataGroupedById' => $editData['dataGroupedById'],
            'dataOutputPartItem' => $dataOutputPartItem,
            'dataOutputUnitMaster' => $dataOutputUnitMaster,
            'id' => $id
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
    }
    }
public function listItemStockReportAjax(Request $request)
    {
        try {
            $data = $this->service->listItemStockReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.security-report-pdf', ['data' => $data['data']])
                    ->setPaper('a4'); // <-- Landscape

                return $pdf->download('DesignReport.pdf');
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new SecurityReportExport($data['data']), 'DesignReport.xlsx');
            }

            // Normal AJAX response
            return response()->json([
                'status' => true,
                'data' => $data['data'],
                'pagination' => $data['pagination']
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
public function listLogisticsReport(Request $request)
{
    try {
        $data = $this->service->listLogisticsReport($request);

        $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        $getProductName = BusinessDetails::whereNotNull('product_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('product_name', 'id');

        return view('organizations.report.logistics-report', compact('data', 'getProjectName', 'getProductName'));
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
public function listLogisticsReportAjax(Request $request)
{
    try {
        $data = $this->service->listLogisticsReport($request);

        // Defensive check
        if (!is_array($data) || !isset($data['data'])) {
            throw new \Exception("Invalid response format from service.");
        }

        // PDF Export
        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.logistics-report-pdf', ['data' => $data['data']])
                ->setPaper('a4');

            return $pdf->download('LogisticsReport.pdf');
        }

        // Excel Export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new LogisticsReportExport($data['data']), 'LogisticsReport.xlsx');
        }

        // Normal AJAX response
        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function listFiananceReport(Request $request)
{
    try {
        $data = $this->service->listFiananceReport($request);

        $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        $getProductName = BusinessDetails::whereNotNull('product_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('product_name', 'id');

        return view('organizations.report.fianance-report', compact('data', 'getProjectName', 'getProductName'));
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
public function listFiananceReportAjax(Request $request)
{
    try {
        $data = $this->service->listFiananceReport($request);

        // Defensive check
        if (!is_array($data) || !isset($data['data'])) {
            throw new \Exception("Invalid response format from service.");
        }

        // PDF Export
        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.fianance-report-pdf', ['data' => $data['data']])
                ->setPaper('a4');

            return $pdf->download('LogisticsReport.pdf');
        }

        // Excel Export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new SecurityReportExport($data['data']), 'LogisticsReport.xlsx');
        }

        // Normal AJAX response
        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function listVendorPaymentReport(Request $request)
{
    try {
        $data = $this->service->listVendorPaymentReport($request);
         $getVendorName = Vendors::whereNotNull('vendor_name')
        ->where('is_deleted', 0)
        ->where('is_active', 1)
        ->pluck('vendor_name', 'id');

         $getPurchaseOrder = PurchaseOrdersModel::whereNotNull('purchase_orders_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('purchase_orders_id', 'purchase_orders_id'); // use po number as key & value

        return view('organizations.report.vendor-payment-report', compact('data', 'getVendorName', 'getPurchaseOrder'));
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
public function listVendorPaymentReportAjax(Request $request)
{
    try {
        $data = $this->service->listVendorPaymentReport($request);

        // Defensive check
        if (!is_array($data) || !isset($data['data'])) {
            throw new \Exception("Invalid response format from service.");
        }

        // PDF Export
        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.logistics-report-pdf', ['data' => $data['data']])
                ->setPaper('a4');

            return $pdf->download('LogisticsReport.pdf');
        }

        // Excel Export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new SecurityReportExport($data['data']), 'LogisticsReport.xlsx');
        }

        // Normal AJAX response
        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function listDispatchReport(Request $request)
{
    try {
        $data = $this->service->listDispatchReport($request);

        $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        $getProductName = BusinessDetails::whereNotNull('product_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('product_name', 'id');

        return view('organizations.report.dispatch-report', compact('data', 'getProjectName', 'getProductName'));
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
public function listDispatchReportAjax(Request $request)
{
    try {
        $data = $this->service->listDispatchReport($request);

        // Defensive check
        if (!is_array($data) || !isset($data['data'])) {
            throw new \Exception("Invalid response format from service.");
        }

        // PDF Export
        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.dispatch-report-pdf', ['data' => $data['data']])
                ->setPaper('a4');

            return $pdf->download('LogisticsReport.pdf');
        }

        // Excel Export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new SecurityReportExport($data['data']), 'LogisticsReport.xlsx');
        }

        // Normal AJAX response
        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function listPendingDispatchReport(Request $request)
{
    try {
        $data = $this->service->listPendingDispatchReport($request);

        $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        $getProductName = BusinessDetails::whereNotNull('product_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('product_name', 'id');

        return view('organizations.report.dispatch-pending-report', compact('data', 'getProjectName', 'getProductName'));
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
public function listPendingDispatchReportAjax(Request $request)
{
    try {
        $data = $this->service->listPendingDispatchReport($request);

        // Defensive check
        if (!is_array($data) || !isset($data['data'])) {
            throw new \Exception("Invalid response format from service.");
        }

        // PDF Export
        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.dispatch-report-pdf', ['data' => $data['data']])
                ->setPaper('a4');

            return $pdf->download('LogisticsReport.pdf');
        }

        // Excel Export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new SecurityReportExport($data['data']), 'LogisticsReport.xlsx');
        }

        // Normal AJAX response
        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function listDispatchBarChart( Request $request ) {
        try {
            $data = $this->service->listDispatchBarChart($request); 
            $DataProductWise = $this->service->listDispatchBarChartProductWise($request); 
             $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('project_name', 'id');

        $getProductName = BusinessDetails::whereNotNull('product_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('product_name', 'id');
   $vendorWise = $this->service->listVendorWise($request);
return view('organizations.report.dispatch-bar-chart', ['data' => $data, 'DataProductWise' => $DataProductWise, 'getProjectName'=> $getProjectName, 'getProductName'=>$getProductName,  'vendorWise' => $vendorWise['data'],]);
        } catch ( \Exception $e ) {
            return $e;
        }
    }

public function listVendorThroughTakenMaterial(Request $request)
{
    $getVendorName = Vendors::whereNotNull('vendor_name')
        ->where('is_deleted', 0)
        ->where('is_active', 1)
        ->pluck('vendor_name', 'id');

    if ($request->filled('export_type')) {
        $data = $this->service->listVendorThroughTakenMaterial($request)['data'];

        if ($request->export_type == 1) {
            $pdf = Pdf::loadView('exports.vendor-through-taken-material', ['data' => $data])
                ->setPaper('a3', 'landscape');
            return $pdf->download('vendor-through-taken-material.pdf');
        }

        if ($request->export_type == 2) {
            return Excel::download(new VendorThroughTakenMaterialReport($data), 'vendor-through-taken-material.xlsx');
        }
    }

    return view('organizations.report.vendor-through-taken-material', compact('getVendorName'));
}

public function listVendorThroughTakenMaterialAjax(Request $request)
{
    try {
        $response = $this->service->listVendorThroughTakenMaterial($request);

        if (isset($response['status']) && $response['status'] === false) {
            return response()->json([
                'status' => false,
                'message' => $response['message']
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $response['data'],
            'pagination' => $response['pagination']
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function listVendorThroughTakenMaterialVendorId(Request $request, $id)
{
    try {
        $data = $this->service->listVendorThroughTakenMaterialVendorId($request, $id)['data'];
        return view('organizations.report.vendor-material-list', compact('data'));
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}
public function listVendorThroughTakenMaterialVendorIdAjax(Request $request, $id)
{
    try {
        $data = $this->service->listVendorThroughTakenMaterialVendorId($request, $id);

        // Defensive check
        if (!is_array($data) || !isset($data['data'])) {
            throw new \Exception("Invalid response format from service.");
        }

        // PDF Export
        if ($request->filled('export_type') && $request->export_type == 1) {
            $pdf = Pdf::loadView('exports.logistics-report-pdf', ['data' => $data['data']])
                ->setPaper('a4');

            return $pdf->download('LogisticsReport.pdf');
        }

        // Excel Export
        if ($request->filled('export_type') && $request->export_type == 2) {
            return Excel::download(new SecurityReportExport($data['data']), 'LogisticsReport.xlsx');
        }

        // Normal AJAX response
        return response()->json([
            'status' => true,
            'data' => $data['data'],
            'pagination' => $data['pagination']
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function getStockItem(Request $request)
{
    if ($request->filled('export_type')) {
        $data = $this->service->getStockItem($request)['data'];

        if ($request->export_type == 1) {
            $pdf = Pdf::loadView('exports.stock-item-pdf', ['data' => $data])
                ->setPaper('a3', 'landscape');
            return $pdf->download('stock-item.pdf');
        }

        if ($request->export_type == 2) {
            return Excel::download(new ItemStockReport($data), 'stock-item.xlsx');
        }
    }

    // If no export type, show the view with data (optional: fetch data for initial load)
    $data = $this->service->getStockItem($request)['data'] ?? [];
    return view('organizations.report.list-stock-item', compact('data'));
}
public function getStockItemAjax(Request $request)
{
    try {
        $response = $this->service->getStockItem($request);

        if (isset($response['status']) && $response['status'] === false) {
            return response()->json([
                'status' => false,
                'message' => $response['message']
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $response['data'],
            'pagination' => $response['pagination']
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
public function getStoreItemStockList(Request $request)
{
    if ($request->filled('export_type')) {
        $data = $this->service->getStoreItemStockList($request)['data'];

        if ($request->export_type == 1) {
            $pdf = Pdf::loadView('exports.stock-item-pdf', ['data' => $data])
                ->setPaper('a3', 'landscape');
            return $pdf->download('stock-item.pdf');
        }

        if ($request->export_type == 2) {
            return Excel::download(new ItemStockReport($data), 'stock-item.xlsx');
        }
    }

    // If no export type, show the view with data (optional: fetch data for initial load)
    $data = $this->service->getStoreItemStockList($request)['data'] ?? [];
    return view('organizations.report.store-item-stock-list', compact('data'));
}
public function getStoreItemStockListAjax(Request $request)
{
    try {
        $response = $this->service->getStoreItemStockList($request);

        if (isset($response['status']) && $response['status'] === false) {
            return response()->json([
                'status' => false,
                'message' => $response['message']
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $response['data'],
            'pagination' => $response['pagination']
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}

public function getProductionReport(Request $request)
{
    if ($request->filled('export_type')) {
        $data = $this->service->getProductionReport($request)['data'];
        if ($request->export_type == 1) {
            $pdf = Pdf::loadView('exports.stock-item-pdf', ['data' => $data])
                ->setPaper('a3', 'landscape');
            return $pdf->download('stock-item.pdf');
        }

        if ($request->export_type == 2) {
            return Excel::download(new ItemStockReport($data), 'stock-item.xlsx');
        }
    }

    $getProjectName = Business::whereNotNull('project_name')
    ->where('is_deleted', 0)
    ->where('is_active', 1)
    ->pluck('project_name', 'id');
    // If no export type, show the view with data (optional: fetch data for initial load)
    $data = $this->service->getStoreItemStockList($request)['data'] ?? [];
    return view('organizations.report.production-report', compact('data', 'getProjectName'));
}
public function getProductionReportAjax(Request $request)
{
    try {
        $response = $this->service->getProductionReport($request);

        if (isset($response['status']) && $response['status'] === false) {
            return response()->json([
                'status' => false,
                'message' => $response['message']
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $response['data'],
            'pagination' => $response['pagination']
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}
}