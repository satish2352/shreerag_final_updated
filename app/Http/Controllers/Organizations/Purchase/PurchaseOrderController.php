<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Models\PurchaseOrdersModel;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\PurchaseOrderServices;
use App\Http\Controllers\Controller;
use Validator;

class PurchaseOrderController extends Controller
{
    public function __construct(){
        $this->service = new PurchaseOrderServices();
    }

    public function index()
    {
        $title = 'Purchase Orders';
        $getOutput = PurchaseOrdersModel::get();
        return view('organizations.purchase.addpurchasedetails.list-purchase-orders',compact(
            'title','getOutput'
        ));
    }

    public function create()
    {
        $title = 'create invoice';
        return view('organizations.purchase.addpurchasedetails.add-purchase-orders',compact(
            'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        $rules = [
            'client_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'tax' => 'required',
            'invoice_date' => 'required',
            'gst_number' => 'required',
            'payment_terms' => 'required',
            'client_address' => 'required',
            'discount' => 'required',
            'status' => 'required',
            'note' => 'nullable',
            ];

            $messages = [
                        'client_name.required' => 'The Client Name is required.',
                        'phone_number.required' => 'The Phone Number is required.',
                        'email.required' => 'The Email is required.',
                        'tax.required' => 'The Tax is required.',
                        'invoice_date.required' => 'The Invoice Date is required.',
                        'gst_number.required' => 'The GST Number is required.',
                        'payment_terms.required' => 'The Payment Terms is required.',
                        'client_address.required' => 'The Client Address is required.',
                        'discount.required' => 'The Discount is required.',
                        'status.required' => 'The Status is required.',
                        'note.required' => 'The Note is required.',
                                            ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('add-purchase-order')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->submitBOMToOwner($request);
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-purchase-order')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-purchase-order')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-business')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }

    public function store_old(Request $request)
    {
        $rules = [
            'client_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'tax' => 'required',
            'invoice_date' => 'required',
            'gst_number' => 'required',
            'payment_terms' => 'required',
            'client_address' => 'required',
            'discount' => 'required',
            'status' => 'required',
            'note' => 'nullable',
            ];

       
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
        return view('organizations.purchase.addpurchasedetails.show-purchase-orders',compact('invoice','title'));
    }

    public function show21(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        // dd($invoice);
        $title = 'view invoice';
        return view('organizations.purchase.addpurchasedetails.show-purchase-orders21',compact('invoice','title'));
    }

    public function showpurchase(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        dd($invoice);
        $title = 'view invoice';
        return view('organizations.purchase.addpurchasedetails.show-purchase-orders1',compact('invoice','title'));
    }

     
    public function edit(Request $request)
    {
        $show_data_id = base64_decode($request->id);
        $invoice = PurchaseOrdersModel::find($show_data_id);
        // dd($invoice);
        $title = 'edit invoice';
        return view('organizations.purchase.addpurchasedetails.edit-purchase-orders',compact(
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