<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Purchase Orders';
        $getOutput = PurchaseOrdersModel::get();
        return view('organizations.purchase.invoices.list-purchase-orders',compact(
            'title','getOutput'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'create invoice';
        return view('organizations.purchase.invoices.add-purchase-orders',compact(
            'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'client_name' => 'required',
            'po_number' => 'required',
            'email' => 'required',
            'tax' => 'required',
            'client_address' => 'required',
            'gst_number' => 'required',
            'invoice_date' => 'required',
            'items' => 'required',
            'note' => 'nullable',
        ]);

       
        $amount = 0;
        foreach ($request->items as $item) {
            $amount += $item['amount'];
        }

        $itemsJson = json_encode($request->items);

        
        $invoice = new PurchaseOrdersModel([
            'client_name' => $request->client_name,
            'phone_number' => $request->phone_number,
            'tax' => $request->tax,
            'email' => $request->email,
            'client_address' => $request->client_address,
            'gst_number' => $request->gst_number,
            'invoice_date' => $request->invoice_date,
            'payment_terms' => $request->payment_terms,
            'items' => $itemsJson,
            'discount' => $request->discount,
            'total' => $amount,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        if ($invoice->save()) {
            $msg = 'Invoice has been created';
            $status = 'success';

            return redirect('list-purchase-order')->with(compact('msg', 'status'));
        } else {
            $msg = 'Failed to create invoice';
            $status = 'error';

            return redirect('add-purchase-order')->withInput()->with(compact('msg', 'status'));
        }
    }


    
    public function show(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        // dd($invoice);
        $title = 'view invoice';
        return view('organizations.purchase.invoices.show-purchase-orders',compact('invoice','title'));
    }

    public function show21(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        // dd($invoice);
        $title = 'view invoice';
        return view('organizations.purchase.invoices.show-purchase-orders21',compact('invoice','title'));
    }

    public function showpurchase(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        dd($invoice);
        $title = 'view invoice';
        return view('organizations.purchase.invoices.show-purchase-orders1',compact('invoice','title'));
    }

     
    public function edit(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        // dd($invoice);
        $title = 'edit invoice';
        return view('organizations.purchase.invoices.edit-purchase-orders',compact(
            'title','invoice'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
public function update(Request $request)
{
    // dd($request);
    $this->validate($request, [
        'client_name' => 'required',
        'phone_number' => 'required',
        'email' => 'required',
        'tax' => 'required',
        'client_address' => 'required',
        'gst_number' => 'required',
        'invoice_date' => 'required',
        'items' => 'required',
        'note' => 'nullable',
    ]);


    $itemsJson = json_encode($request->items);


    $amount = 0;
    foreach ($request->items as $item) {
        $amount += $item['amount'];
    }

    $invoice=PurchaseOrdersModel::find($request->id);
    $invoice->update([
        'client_name' => $request->client_name,
        'phone_number' => $request->phone_number,
        'tax' => $request->tax,
        'email' => $request->email,
        'client_address' => $request->client_address,
        'gst_number' => $request->gst_number,
        'invoice_date' => $request->invoice_date,
        'payment_terms' => $request->payment_terms,
        'items' => $itemsJson,
        'discount' => $request->discount,
        'total' => $amount,
        'note' => $request->note,
        'status' => $request->status,
    ]);
    // dd($invoice->wasChanged());
     if ($invoice->wasChanged()) {
        $msg = 'Invoice has been updated';
        $status = 'success';
        return redirect()->route('list-purchase-order')->with(compact('msg', 'status'));
    } else {
        $msg = 'No changes were made to the invoice';
        $status = 'error';
        return redirect()->route('list-purchase-order')->with(compact('msg', 'status'));
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Invoice::findOrFail($request->id)->delete();
        $notification = notify('Invoice has been deleted successfully');
        return back()->with($notification);
    }
}