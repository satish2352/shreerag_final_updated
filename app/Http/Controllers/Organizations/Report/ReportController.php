<?php

namespace App\Http\Controllers\Organizations\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Report\ReportServices;
use Session;
use Validator;
use Config;
use Carbon;

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

}