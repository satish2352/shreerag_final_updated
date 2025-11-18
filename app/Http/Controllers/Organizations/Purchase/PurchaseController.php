<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\PurchaseServices;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Exception;

class PurchaseController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new PurchaseServices();
    }

    public function submitBOMToOwner($id)
    {
        try {
            $data_output = $this->service->getDetailsForPurchase($id);
            return view('organizations.purchase.addpurchasedetails.add-purchase-orders', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}
