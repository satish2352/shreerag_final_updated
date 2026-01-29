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
use App\Http\Controllers\Exports\DispatchExportReport;
use App\Http\Controllers\Exports\DispatchPendingExportReport;
use App\Http\Controllers\Exports\FiananceExportReport;
use App\Http\Controllers\Exports\ItemStockReportExport;
use App\Http\Controllers\Exports\VendorThroughTakenMaterialListDetailsReport;
use App\Http\Controllers\Exports\ItemStockReport;
use App\Http\Controllers\Exports\ConsumptionReportExport;
use App\Http\Controllers\Exports\VendorPaymentReportExport;
use App\Http\Controllers\Exports\CompleteProductReportExport;
use App\Http\Controllers\Exports\ConsumptionReport;
use App\Http\Controllers\Exports\VendorThroughTakenMaterialReport;
use App\Http\Controllers\Exports\ItemWiseVendorRateReportExport;
use App\Http\Controllers\Exports\EstimationReportExport;
use App\Http\Controllers\Exports\EmployeeLeaveExportReport;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\{
    Business,
    Vendors,
    PurchaseOrdersModel,
    BusinessDetails,
    PartItem,
    UnitMaster,
    Dispatch,
    BusinessApplicationProcesses
};

class ReportController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new ReportServices();
    }

    private function timeStamp()
    {
        return now()->format('Y-m-d_H-i-s');
    }

    public function getCompletedProductList(Request $request)
    {
        try {
            $data_output = $this->service->getCompletedProductList($request);
            // $getProjectName = Business::whereNotNull('project_name')
            //     ->where('is_deleted', 0)
            //     ->where('is_active', 1)
            //     ->pluck('project_name', 'id');


            // $getProjectName = Business::where('is_deleted', 0)
            // ->where('is_active', 1)
            // ->whereNotNull('project_name')
            // ->where('project_name', '!=', '')
            // ->pluck('project_name', 'id');


            // $getProductName = BusinessDetails::whereNotNull('product_name')
            //     ->where('is_deleted', 0)
            //     ->where('is_active', 1)
            //     ->pluck('product_name', 'id');


            $getProjectName = Business::leftJoin('business_application_processes', function ($join) {
                $join->on('businesses.id', '=', 'business_application_processes.business_id');
            })
                ->whereNotNull('businesses.project_name')
                ->where('businesses.project_name', '!=', '')
                ->where('businesses.is_deleted', 0)
                ->where('businesses.is_active', 1)
                ->where('business_application_processes.off_canvas_status', 22)
                ->pluck('businesses.project_name', 'businesses.id');

            $getProductName = BusinessDetails::leftJoin('business_application_processes as bap', function ($join) {
                $join->on('businesses_details.id', '=', 'bap.business_details_id');
            })
                ->where('businesses_details.is_deleted', 0)
                ->where('businesses_details.is_active', 1)
                ->where('bap.off_canvas_status', 22)
                ->pluck('businesses_details.product_name', 'businesses_details.id');


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
    public function getCompletedProductListAjax(Request $request)
    {
        try {
            $data = $this->service->getCompletedProductList($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.complete-product-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape'); // <-- Landscape

                return $pdf->download("CompleteProductReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new CompleteProductReportExport($data['data']), "CompleteProductReport_{$this->timeStamp()}.xlsx");
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
    public function listDesignReport(Request $request)
    {
        try {
            $data = $this->service->listDesignReport($request);

            $getProjectName =  BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->where('businesses.is_deleted', 0)
                ->where('businesses.is_active', 1)
                ->pluck('businesses.project_name', 'businesses.id');

            return view('organizations.report.list-accept-design-by-production', compact('data', 'getProjectName'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listDesignReportAjax(Request $request)
    {
        try {
            $data = $this->service->listDesignReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.design-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape'); // <-- Landscape

                return $pdf->download("DesignReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new DesignReportExport($data['data']), "DesignReport_{$this->timeStamp()}.xlsx");
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
    public function getEstimationReport(Request $request)
    {
        try {
            $data = $this->service->getEstimationReport($request);

            $getProjectName = Business::whereNotNull('project_name')
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->whereNotNull('project_name')
                ->pluck('project_name', 'id');

            $getProductName = BusinessDetails::whereNotNull('product_name')
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->pluck('product_name', 'id');

            return view('organizations.report.estimation-report', compact('data', 'getProjectName', 'getProductName'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function getEstimationReportAjax(Request $request)
    {
        try {
            $data = $this->service->getEstimationReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.estimation-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape'); // <-- Landscape

                return $pdf->download("EstimationReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new EstimationReportExport($data['data']), "EstimationReport_{$this->timeStamp()}.xlsx");
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

    public function getSecurityReport(Request $request)
    {
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


            return view('organizations.report.security-report', compact('data', 'getProjectName', 'getPurchaseOrder'));
        } catch (\Exception $e) {
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

                return $pdf->download("SecurityReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new SecurityReportExport($data['data']), "SecurityReport_{$this->timeStamp()}.xlsx");
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
    public function getGRNReport(Request $request)
    {
        try {
            // Fetch report data from service
            $data = $this->service->getGRNReport($request);

            // -------------------------------------------
            // 🔹 VENDOR LIST (ONLY WHERE GRN IS GENERATED)
            // -------------------------------------------
            $getProjectName = PurchaseOrdersModel::leftJoin('vendors', function ($join) {
                $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
            })
                ->leftJoin('grn_tbl', function ($join) {
                    $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
                })
                ->whereNotNull('grn_tbl.grn_no_generate') // <-- IMPORTANT
                ->where('vendors.is_deleted', 0)
                ->where('vendors.is_active', 1)
                ->distinct() // prevents duplicate vendor names
                ->pluck('vendors.vendor_name', 'vendors.id');

            // -------------------------------------------
            // 🔹 PURCHASE ORDER LIST (ONLY WHERE GRN EXISTS)
            // -------------------------------------------
            $getPurchaseOrder = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->whereNotNull('grn_tbl.grn_no_generate') // <-- IMPORTANT
                ->where('purchase_orders.is_deleted', 0)
                ->where('purchase_orders.is_active', 1)
                ->distinct()
                ->pluck('purchase_orders.purchase_orders_id', 'purchase_orders.purchase_orders_id');

            return view('organizations.report.grn-report', compact(
                'data',
                'getProjectName',
                'getPurchaseOrder'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function getGRNReportAjax(Request $request)
    {
        try {
            $data = $this->service->getGRNReport($request);


            // if ($request->filled('export_type') && $request->export_type == 1) {
            if ($request->export_type == "1") {
                $pdf = Pdf::loadView('exports.grn-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape');
                return $pdf->download("GRNReport_{$this->timeStamp()}.pdf");
            }

            // if ($request->filled('export_type') && $request->export_type == 2) {
            if ($request->export_type == "2") {
                return Excel::download(new GRNReportExport($data['data']), "GRNReport_{$this->timeStamp()}.xlsx");
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

    public function getRejectedGRNReport(Request $request)
    {
        try {
            // Fetch report data from service
            $data = $this->service->getRejectedGRNReport($request);

            // -------------------------------------------
            // 🔹 VENDOR LIST (ONLY WHERE GRN IS GENERATED)
            // -------------------------------------------
            $getProjectName = PurchaseOrdersModel::leftJoin('vendors', function ($join) {
                $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
            })
                ->leftJoin('grn_tbl', function ($join) {
                    $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
                })
                ->whereNotNull('grn_tbl.grn_no_generate') // <-- IMPORTANT
                ->where('vendors.is_deleted', 0)
                ->where('vendors.is_active', 1)
                ->distinct() // prevents duplicate vendor names
                ->pluck('vendors.vendor_name', 'vendors.id');

            // -------------------------------------------
            // 🔹 PURCHASE ORDER LIST (ONLY WHERE GRN EXISTS)
            // -------------------------------------------
            $getPurchaseOrder = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->whereNotNull('grn_tbl.grn_no_generate') // <-- IMPORTANT
                ->where('purchase_orders.is_deleted', 0)
                ->where('purchase_orders.is_active', 1)
                ->distinct()
                ->pluck('purchase_orders.purchase_orders_id', 'purchase_orders.purchase_orders_id');

            return view('organizations.report.rejected-grn-report', compact(
                'data',
                'getProjectName',
                'getPurchaseOrder'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function getRejectedGRNReportAjax(Request $request)
    {
        try {
            $data = $this->service->getRejectedGRNReport($request);


            // if ($request->filled('export_type') && $request->export_type == 1) {
            if ($request->export_type == "1") {
                $pdf = Pdf::loadView('exports.rejected-grn-report-pdf', ['data' => $data['data']])
                    ->setPaper('a3', 'landscape');
                return $pdf->download("RejectedGRNReport_{$this->timeStamp()}.pdf");
            }

            // if ($request->filled('export_type') && $request->export_type == 2) {
            if ($request->export_type == "2") {
                return Excel::download(new GRNReportExport($data['data']), "RejectedGRNReport_{$this->timeStamp()}.xlsx");
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

    public function getVendorbyPurchaseOrder($id)
    {
        try {
            $purchaseOrders = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->select('purchase_orders.purchase_orders_id')
                ->where('purchase_orders.vendor_id', $id)
                ->whereNotNull('grn_tbl.grn_no_generate')   // ✅ Only GRN generated POs
                ->where('purchase_orders.is_active', 1)
                ->where('purchase_orders.is_deleted', 0)
                ->distinct()
                ->get();

            $response = $purchaseOrders->map(function ($po) {
                return [
                    'name' => $po->purchase_orders_id
                ];
            });

            return response()->json([
                'status' => true,
                'purchaseOrders' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }





    public function listConsumptionReport(Request $request)
    {
        $getProjectName = Business::whereNotNull('project_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)

            ->whereNotNull('project_name')
            ->where('project_name', '!=', '')

            ->pluck('project_name', 'id');

        if ($request->filled('export_type')) {
            $data = $this->service->getConsumptionReport($request)['data'];

            if ($request->export_type == 1) {
                $pdf = Pdf::loadView('exports.consumption-report-pdf', ['data' => $data]);
                return $pdf->download("consumptionReport_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new ConsumptionReportExport($data), "consumptionReport_{$this->timeStamp()}.xlsx");
            }
        }

        return view('organizations.report.consumption-report', compact('getProjectName'));
    }

    public function listConsumptionReportAjax(Request $request)
    {
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
                return $pdf->download("ConsumptionReport_{$this->timeStamp()}.pdf");
            }

            // Handle Excel export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new ConsumptionReport($response['data']), "ConsumptionReport_{$this->timeStamp()}.xlsx");
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
    public function getConsumptionMaterialList($id)
    {
        try {
            $id = $id;

            $editData = $this->service->getConsumptionMaterialList($id);



            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();

            return view('organizations.report.consumption-material-list', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'total_items_used_amount' => $editData['total_items_used_amount'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function getProductsByProject($id)
    {
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
    public function listItemStockReport(Request $request)
    {
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

                return $pdf->download("DesignReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new SecurityReportExport($data['data']), "DesignReport_{$this->timeStamp()}.xlsx");
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
                ->whereNotNull('project_name')
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

                return $pdf->download("LogisticsReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new LogisticsReportExport($data['data']), "LogisticsReport_{$this->timeStamp()}.xlsx");
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
    public function listFinanceReport(Request $request)
    {
        try {
            $data = $this->service->listFinanceReport($request);

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
    public function listFinanceReportAjax(Request $request)
    {
        try {
            $data = $this->service->listFinanceReport($request);

            // Defensive check
            if (!is_array($data) || !isset($data['data'])) {
                throw new \Exception("Invalid response format from service.");
            }

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.fianance-report-pdf', ['data' => $data['data']])
                    ->setPaper('a4');

                return $pdf->download("FiananceReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new FiananceExportReport($data['data']), "FiananceReport_{$this->timeStamp()}.xlsx");
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
            // $getVendorName = Vendors::whereNotNull('vendor_name')
            //     ->where('is_deleted', 0)
            //     ->where('is_active', 1)
            //     ->pluck('vendor_name', 'id');

            // $getPurchaseOrder = PurchaseOrdersModel::whereNotNull('purchase_orders_id')
            //     ->where('is_deleted', 0)
            //     ->where('is_active', 1)
            //     ->pluck('purchase_orders_id', 'purchase_orders_id'); // use po number as key & value




            $getVendorName = PurchaseOrdersModel::leftJoin('vendors', function ($join) {
                $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
            })
                ->leftJoin('grn_tbl', function ($join) {
                    $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
                })
                ->whereNotNull('grn_tbl.grn_no_generate') // <-- IMPORTANT
                ->where('vendors.is_deleted', 0)
                ->where('vendors.is_active', 1)
                ->distinct() // prevents duplicate vendor names
                ->pluck('vendors.vendor_name', 'vendors.id');

            // -------------------------------------------
            // 🔹 PURCHASE ORDER LIST (ONLY WHERE GRN EXISTS)
            // -------------------------------------------
            $getPurchaseOrder = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->whereNotNull('grn_tbl.grn_no_generate') // <-- IMPORTANT
                ->where('purchase_orders.is_deleted', 0)
                ->where('purchase_orders.is_active', 1)
                ->distinct()
                ->pluck('purchase_orders.purchase_orders_id', 'purchase_orders.purchase_orders_id');



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
                $pdf = Pdf::loadView('exports.vendor-payment-report-pdf', ['data' => $data['data']])
                    ->setPaper('a4');

                return $pdf->download("VendorPaymentReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new VendorPaymentReportExport($data['data']), "VendorPaymentReport_{$this->timeStamp()}.xlsx");
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

            $getProjectName = Business::leftJoin('business_application_processes', function ($join) {
                $join->on('businesses.id', '=', 'business_application_processes.business_id');
            })
                ->whereNotNull('businesses.project_name')
                ->where('businesses.project_name', '!=', '')
                ->where('businesses.is_deleted', 0)
                ->where('businesses.is_active', 1)
                ->where('business_application_processes.off_canvas_status', 22)
                ->pluck('businesses.project_name', 'businesses.id');

            $getProductName = BusinessDetails::leftJoin('business_application_processes as bap', function ($join) {
                $join->on(' businesses_details.id', '=', 'bap.business_details_id');
            })
                // ->whereNotNull('businesses_details.product_name')
                ->where('businesses_details.is_deleted', 0)
                ->where('businesses_details.is_active', 1)
                ->where('bap.off_canvas_status', 22)
                ->select('businesses_details.product_name', 'businesses_details.id');

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

                return $pdf->download("DispatchReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new DispatchExportReport($data['data']), "DispatchReport_{$this->timeStamp()}.xlsx");
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

            $getProjectName = Business::leftJoin('business_application_processes', function ($join) {
                $join->on('businesses.id', '=', 'business_application_processes.business_id');
            })
                ->whereNotNull('businesses.project_name')
                ->where('businesses.is_deleted', 0)
                ->where('businesses.is_active', 1)
                ->where('business_application_processes.off_canvas_status', 21)
                ->pluck('businesses.project_name', 'businesses.id');

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
                $pdf = Pdf::loadView('exports.dispatch-pending-report-pdf', ['data' => $data['data']])
                    ->setPaper('a4');

                return $pdf->download("DispatchPendingReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new DispatchPendingExportReport($data['data']), "DispatchPendingReport_{$this->timeStamp()}.xlsx");
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
    public function listDispatchBarChart(Request $request)
    {
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
            return view('organizations.report.dispatch-bar-chart', ['data' => $data, 'DataProductWise' => $DataProductWise, 'getProjectName' => $getProjectName, 'getProductName' => $getProductName,  'vendorWise' => $vendorWise['data'],]);
        } catch (\Exception $e) {
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
                $pdf = Pdf::loadView('exports.vendorThroughTakenMaterial_{$this->timeStamp()}', ['data' => $data])
                    ->setPaper('a3', 'landscape');
                return $pdf->download("vendor-through-taken-material.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new VendorThroughTakenMaterialReport($data), "vendorThroughTakenMaterial_{$this->timeStamp()}.xlsx");
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
                $pdf = Pdf::loadView('exports.vendor-through-taken-material-report-pdf', ['data' => $data['data']])
                    ->setPaper('a4');

                return $pdf->download("VendorThroughTakenMaterialReport_{$this->timeStamp()}.pdf");
            }

            // Excel Export
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(new VendorThroughTakenMaterialListDetailsReport($data['data']), "VendorThroughTakenMaterialReport_{$this->timeStamp()}.xlsx");
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
                $pdf = Pdf::loadView('exports.list-item-stock-pdf', ['data' => $data])
                    ->setPaper('a4', 'landscape');
                return $pdf->download("ItemStock_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new ItemStockReport($data), "ItemStock_{$this->timeStamp()}.xlsx");
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
            // $data = $this->service->getStoreItemStockList($request)['data'];
            $dataResponse = $this->service->getStoreItemStockList($request);

            $data = $dataResponse['export_data'] ?? $dataResponse['data'] ?? [];

            if ($request->export_type == 1) {
                $pdf = Pdf::loadView('exports.stock-item-pdf', ['data' => $data])
                    ->setPaper('a3', 'landscape');
                return $pdf->download("stockItem_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                return Excel::download(new ItemStockReport($data), "stockItem_{$this->timeStamp()}.xlsx");
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
        try {
            // Fetch report data from service
            $data = $this->service->getProductionReport($request);

            $getProjectName = Business::whereNotNull('project_name')
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->pluck('project_name', 'id');

            return view('organizations.report.production-report', compact(
                'data',
                'getProjectName'

            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function getProductionReportAjax(Request $request)
    {
        try {
            $data = $this->service->getProductionReport($request);

            // --- PDF EXPORT ---
            if ($request->export_type == "1") {

                $records = $data['data']->get();  // important

                $pdf = Pdf::loadView('exports.production-report-pdf', [
                    'data' => $records
                ])->setPaper('a3', 'landscape');

                return $pdf->download("ProductionReport_{$this->timeStamp()}.pdf");
            }

            // --- EXCEL EXPORT ---
            if ($request->export_type == "2") {

                return Excel::download(
                    new ProductionReportExport($data['data']), // only query
                    "ProductionReport_{$this->timeStamp()}.xlsx"
                );
            }

            // Normal AJAX response
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
    public function listStockDailyReport(Request $request)
    {
        // First get full response from service
        $response = $this->service->listStockDailyReport($request);

        // --------------------------------------------
        // EXPORT REQUEST
        // --------------------------------------------
        if ($request->filled('export_type')) {

            $data   = $response['data'] ?? [];
            $totals = $response['totals'] ?? ['received' => 0, 'issue' => 0, 'balance' => 0];

            // ---- PDF EXPORT ----
            if ($request->export_type == 1) {

                $pdf = Pdf::loadView('exports.item-stock-report-pdf', [
                    'data'   => $data,
                    'totals' => $totals
                ])->setPaper('a3', 'landscape');

                return $pdf->download("ItemStockReport_{$this->timeStamp()}.pdf");
            }

            // ---- EXCEL EXPORT ----
            if ($request->export_type == 2) {
                return Excel::download(
                    new ItemStockReportExport($response['data'], $response['totals']),
                    "ItemStockReport_{$this->timeStamp()}.xlsx"
                );
            }
        }

        // --------------------------------------------
        // NORMAL PAGE LOAD
        // --------------------------------------------
        $getPartItemName = PartItem::whereNotNull('description')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('description', 'id');

        // load default data (optional)
        $data = $response['data'] ?? [];

        return view('organizations.report.item-stock-report', compact('data', 'getPartItemName'));
    }
    public function listStockDailyReportAjax(Request $request)
    {
        try {
            $response = $this->service->listStockDailyReport($request);

            if (isset($response['status']) && $response['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message']
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $response['data'],
                'pagination' => $response['pagination'],
                'totals' => $response['totals'] ?? ['received' => 0, 'issue' => 0, 'balance' => 0],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function listItemWiseVendorRateReport(Request $request)
    {
        // First get full response from service
        $response = $this->service->listItemWiseVendorRateReport($request);

        // --------------------------------------------
        // EXPORT REQUEST
        // --------------------------------------------
        if ($request->filled('export_type')) {

            $data   = $response['data'] ?? [];
            // $totals = $response['totals'] ?? ['received' => 0, 'issue' => 0, 'balance' => 0];

            // ---- PDF EXPORT ----
            if ($request->export_type == 1) {

                $pdf = Pdf::loadView('exports.item-wise-vendor-rate-report-pdf', [
                    'data'   => $data,
                    // 'totals' => $totals
                ])->setPaper('a3', 'landscape');

                return $pdf->download("ItemWiseVendorRateReport_{$this->timeStamp()}.pdf");
            }

            if ($request->export_type == 2) {
                $data = $response['data'] ?? collect();
                return Excel::download(new ItemWiseVendorRateReportExport($data), "ItemWiseVendorRateReport_{$this->timeStamp()}.xlsx");
            }
        }

        // --------------------------------------------
        // NORMAL PAGE LOAD
        // --------------------------------------------
        $getPartItemName = PartItem::whereNotNull('description')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->pluck('description', 'id');

        // load default data (optional)
        $data = $response['data'] ?? [];

        return view('organizations.report.meterial-vendor-through-taken-rate-list', compact('data', 'getPartItemName'));
    }
    public function listItemWiseVendorRateReportAjax(Request $request)
    {
        try {
            $response = $this->service->listItemWiseVendorRateReport($request);

            if (isset($response['status']) && $response['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message']
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $response['data'],
                'pagination' => $response['pagination'],
                'totals' => $response['totals'] ?? ['received' => 0, 'issue' => 0, 'balance' => 0],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function listEmployeeLeaveReport(Request $request)
    {
        try {
            $data = $this->service->listEmployeeLeaveReport($request);

            $getProjectName = Business::whereNotNull('project_name')
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->pluck('project_name', 'id');

            $getProductName = BusinessDetails::whereNotNull('product_name')
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->pluck('product_name', 'id');

            return view('organizations.report.employee-leave-report', compact('data', 'getProjectName', 'getProductName'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function listEmployeeLeaveAjax(Request $request)
    {
        try {
            $data = $this->service->listEmployeeLeaveReport($request);

            if (!is_array($data) || !isset($data['data'])) {
                throw new \Exception("Invalid response format from service.");
            }

            // ✅ PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {

                $pdf = Pdf::loadView('exports.employee-leave-report-pdf', [
                    'data' => $data['data']
                ])->setPaper('a3', 'landscape');

                return $pdf->download("EmployeeLeaveReport_{$this->timeStamp()}.pdf");
            }

            // ✅ Excel Export (optional – if you want)
            if ($request->filled('export_type') && $request->export_type == 2) {
                return Excel::download(
                    new EmployeeLeaveExportReport($data['data']),
                    "EmployeeLeaveReport_{$this->timeStamp()}.xlsx"
                );
            }

            return response()->json([
                'status' => true,
                'data' => $data['data'],
                'pagination' => $data['pagination']
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function employeeLeaveDetails(Request $request)
    {
        $employeeId = $request->employee_id;
        $year = $request->year;

        $data = DB::table('tbl_leaves as l')
            ->join('tbl_leave_management as lm', 'lm.id', '=', 'l.leave_type_id')
            ->where('l.employee_id', $employeeId)
            ->whereYear('l.leave_start_date', $year)
            ->where('l.is_approved', 2)
            ->select(
                'l.leave_start_date',
                'l.leave_end_date',
                'l.leave_count',
                'l.reason',
                'lm.name as leave_name'
            )
            ->orderBy('l.leave_start_date')
            ->get();

        return response()->json(['data' => $data]);
    }
}
