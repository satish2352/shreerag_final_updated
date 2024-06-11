@extends('admin.layouts.master')
@section('content')
    <style>
        .form-control {
            border: 2px solid #ced4da;
            border-radius: 4px;
        }

        .error {
            color: red;
        }

        .no-print {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .selfProfile {
            float: left;
            width: 50%;
        }

        .imgLogo {
            float: left;
            width: 30%;
        }

        .profile {
            /* float: right; */
            /* width: 70%; */
        }

        .data {
            float: right;
            width: 50%;
        }

        .bordersBottom {
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
        }

        .borders {
            border: 1px solid black;
        }

        .no-border {
            border: none !important;
        }

        .invoice-payments {
            float: left;
            width: 60%;
        }

        .tops {
            margin-top: -63px;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 8px;
            /* Optional: add padding for better readability */
        }

        table th {
            background-color: #f2f2f2;
            /* Optional: add background color for table header */
        }

        /* table tr td {
                                border: 1px solid red;
                            } */
    </style>
  

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
              
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 col-xl-12 m-b-20">

                                <table class="table table-striped table-hover" style="margin:0px;">
                                    <thead>
                                        <tr style="border-width: 1px 1px 0px 1px; border-style: solid; border-color: black;">
                                            <td>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2"
                                                        style="display: flex; justify-content: center; align-items: center;">
                                                        <img class="main-logo"
                                                            src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $getOrganizationData->image }}"
                                                            alt="{{ strip_tags($getOrganizationData['company_name']) }} Image"
                                                            style="background-color: #175CA2" alt="">
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                                        <div
                                                            style="display: flex;justify-content: center;align-items: center;">
                                                            <h4>{{ $getOrganizationData->company_name }}</h4>
                                                        </div>
                                                        <div
                                                            style="display: flex;justify-content: center;align-items: center;">
                                                            <p>{{ $getOrganizationData->address }},
                                                                {{ $getOrganizationData->mobile_number }},
                                                                {{ $getOrganizationData->email }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <table class="table table-striped table-hover" style="margin:0px;">
                                    <thead>
                                        <tr style="border-width: 0px; border-style: solid; border-color: black;">
                                            <td
                                                style="padding: 5px; align-items: center; display: flex; justify-content: center;">
                                                <div style="display: flex; justify-content: center;">
                                                    <h4 style="margin: 0px;">Purchase Order Details</h4>
                                                </div><br>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <table class="table table-striped table-hover" style="margin:0px;">
                                    <thead>
                                        <tr style="border-width: 0px; border-style: solid; border-color: black;">
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6 selfProfile asdf">
                                                        <div class="row">
                                                            <div class="col-md-12 profile">
                                                                <ul class="list-unstyled">
                                                                    <h4>SHREERAG ENGINEERING & AUTO PVT. LTD.</h4>
                                                                    <li>{{ $purchaseOrder->client_address }}</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <ul class="list-unstyled">
                                                                    <li>GST Number: {{ $purchaseOrder->gst_number }}
                                                                    </li>
                                                                    <li>Client Name:
                                                                        {{ $purchaseOrder->client_name }}</li>
                                                                    <li>Client Name:
                                                                        {{ $purchaseOrder->phone_number }}</li>
                                                                    <li>Email: <a
                                                                            href="javascript:void(0)">{{ $purchaseOrder->email }}</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 data">
                                                        <div class="row">
                                                            <div class="col-md-6 selfProfile">
                                                                <ul class="list-unstyled">
                                                                    <li><strong>P.O. No. :
                                                                            {{ $purchaseOrder->purchase_orders_id }}</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6 data">
                                                                <ul class="list-unstyled">
                                                                    <li><strong>Date:
                                                                            {{ $purchaseOrder->created_at }}</strong>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-6 selfProfile">
                                                                <ul class="list-unstyled">
                                                                    <li>Quote Ref No.: --</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-6 selfProfile">
                                                                <ul class="list-unstyled">
                                                                    <li>Payment Terms:
                                                                        {{ $purchaseOrder->payment_terms }} DAYS
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>


                                                        <br>
                                                    </div>
                                                </div>
                                                <p style="margin: 0px; padding:10px 0px;"><b>Dear Sir, Please arrange to
                                                        supply following Material
                                                        as per quantity, specification and schedule
                                                        mentioned below</b></p>
                                            </td>

                                        </tr>
                                    </thead>
                                </table>

                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr style="border:1px solid black;">
                                            <th>Sr. No.</th>
                                            <th class="col-sm-2">Part No.</th>
                                            <th class="col-md-2">Description</th>
                                            <th class="col-md-2">Due Date</th>
                                            <th class="col-md-2">HSN</th>
                                            <th class="col-md-2">Quantity</th>
                                            <th class="col-md-2">Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseOrderDetails as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->part_no }}</td>
                                                <td class="d-none d-sm-table-cell"
                                                    style="max-width: 150px !important; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
                                                    {{ $item->description }}</td>
                                                <td>{{ $item->due_date }}</td>
                                                <td>{{ $item->hsn_no }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ $item->rate }}</td>
                                                <td class="text-right">
                                                    {{ $item->amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="border:1px solid black;">
                                        <tr>
                                            <td class="no-border" colspan="3">
                                                <strong>Terms & Condition :-{{ $getOrganizationData->terms_condition}}</strong>
                                            </td>
                                            <td class="no-border" colspan="3"></td>
                                            <td>Sub Total</td>
                                            <td class="text-right">
                                                {{ $purchaseOrderDetails->sum('amount') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="no-border" colspan="3">
                                                <strong>Remark :-</strong>
                                            </td>
                                            <td class="no-border" colspan="3"></td>
                                            <td>Discount {{ $purchaseOrder->discount }}
                                                %</td>
                                            <td class="text-right">
                                                {{ $purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="no-border" colspan="6"></td>
                                            <td>Freight</td>
                                            <td class="text-right">
                                                0.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="no-border" colspan="6"></td>
                                            <td>CGST 9%</td>
                                            <td class="text-right">
                                                {{ ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * 0.09 }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="no-border" colspan="6"></td>
                                            <td>SGST 9%</td>
                                            <td class="text-right">
                                                {{ ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * 0.09 }}
                                            </td>
                                        </tr>


                                        <tr>
                                            <td class="no-border" colspan="6"></td>
                                            <td>NIL GST</td>
                                            <td class="text-right">
                                                0.00
                                            </td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black">
                                            <td class="no-border" colspan="3">
                                                <strong>transport/Dispatch :-</strong>
                                            </td>
                                            <td class="no-border" colspan="3"></td>
                                            <td><strong> Net Total</strong></td>
                                            <td class="text-right">
                                                <strong>
                                                    {{ $purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) + ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * 0.09 * 2 }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" class="no-border">
                                                Delivery</td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" class="no-border">
                                                <div style="float: right;">For. SHREERAG
                                                    ENGINEERING & AUTO PVT. LTD.</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="no-border" colspan="6">
                                                <strong>Prepared By</strong></td>
                                                <td class="no-border" colspan="">
                                                    ( Authorized Signatory )</td>
                                        </tr>
                                    </tfoot>


                                </table>
                                {{-- </div> --}}
                              <div class="" style="margin-bottom: 70px;">
                                <a href="{{ route('accept-purchase-order', $purchase_order_id) }}"><button data-toggle="tooltip"
                                    title="Accept Purchase Order" class="pd-setting-ed">Accept</button></a> &nbsp;
                            &nbsp; &nbsp;
                              </div>
                              
                            </div>
                        </div>
                      

                        {{-- </div> --}}
                    </div>



                    {{-- <script>
                      function printInvoice() {
                        window.print();
                      }
                      </script> --}}
                @endsection
