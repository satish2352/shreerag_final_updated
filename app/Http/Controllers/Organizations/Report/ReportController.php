<?php

namespace App\Http\Controllers\Organizations\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Report\ReportServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    Business
}
;

class ReportController extends Controller
{ 
    public function __construct(){
        $this->service = new ReportServices();
    }
public function getCompletedProductList(Request $request)
{
    try {
        $data_output = $this->service->getCompletedProductList($request);
        return view('organizations.report.list-report-product-completed', [
            'data_output' => $data_output['data'],
            'total_count' => $data_output['total_count'],
            // other data
        ]);
        // return view('organizations.report.list-report-product-completed', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
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
   public function listDesignReportAjax(Request $request)
    {
        try {
            $data = $this->service->listDesignReport($request);

            // PDF Export
            if ($request->filled('export_type') && $request->export_type == 1) {
                $pdf = Pdf::loadView('exports.design_report', ['data' => $data['data']])
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

}