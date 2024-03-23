@extends('admin.layouts.master')
@section('content')
<style type="text/css" media="print">
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
  float: right;
  width: 70%;
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

.invoice-payments {
  float: left;
  width: 60%;
}

.tops {
  margin-top: -63px;
}
</style>
<br>
<div class="form-group-inner login-btn-inner row">
  <div class="row no-print ">
    <div class="col-md-11 text-right login-horizental cancel-wp pull-left">
      <button class="btn btn-sm btn-primary login-submit-cs" onclick="printInvoice()">Print</button>
    </div>
  </div>
</div>
<br>
<br>

<div class="container-fluid tops">
  <div class="row borders">
    <div class="col-md-6 selfProfile asdf">
      <div class="row borders">
        <div class="col-md-3 imgLogo">
          <img src="" class="img-responsive">
        </div>
        <div class="col-md-9 profile">
          <ul class="list-unstyled">
            <h5> SHREERAG ENGINEERING & AUTO PVT. LTD.</h5>
            <li>W-127 A, MIDC, Ambad , Nashik-422010 , Maharashtra Code-27 , CIN U99999MH1997PTC108601</li>
            <li>shreerageng@rediffmail.com</li>
            <li>Contact Number:- +91 9822502226</li>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-12">
          <ul class="list-unstyled">
            <li>Consignee (Ship to)</li>
            <li>
              Client Name :- {{$invoice->client_name}}
            </li>
            <li>Client Address :- {{$invoice->client_address}}</li>
            <li>Email :- <a href="javascript:void(0)">{{$invoice->email}}</a></li>
            <li>GST Number :- {{$invoice->gst_number}}</li>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-12">
          <ul class="list-unstyled">
            <li>Consignee (Ship to)</li>
            <li>
              Client Name :- {{$invoice->client_name}}
            </li>
            <li>Client Address :- {{$invoice->client_address}}</li>
            <li>Email :- <a href="javascript:void(0)">{{$invoice->email}}</a></li>
            <li>GST Number :- {{$invoice->gst_number}}</li>
          </ul>
        </div>
      </div>
    </div>


    <div class="col-md-6 data ">
      <div class="row borders">
        <div class="col-md-6 selfProfile">
          <ul class="list-unstyled">
            <li>Invoice No :- e-Way Bill No.</li>
            <li><strong>SR2966 </strong>211410898575</li>
          </ul>
        </div>
        <div class="col-md-6 data">
          <ul class="list-unstyled">
            <li>Dated</li>
            <li><strong>28-Jan-24</strong></li><br>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-6 selfProfile">
          <ul class="list-unstyled">
            <li>Delivery Note</li><br><br>
          </ul>
        </div>
        <div class="col-md-6 data">
          <ul class="list-unstyled">
            <li>Mode/Terms of Payment</li>
            <li><strong>45 DAYS</strong></li>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-6 selfProfile">
          <ul class="list-unstyled">
            <li>Buyer's Order No.</li>
            <li>33000000178</li>
          </ul>
        </div>
        <div class="col-md-6 data">
          <ul class="list-unstyled">
            <li>Dated</li>
            <li><strong>29-Dec-23</strong></li>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-6 selfProfile">
          <ul class="list-unstyled">
            <li>Dispatch Doc No.</li><br>
          </ul>
        </div>
        <div class="col-md-6 data">
          <ul class="list-unstyled">
            <li>Delivery Note Date</li><br>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-6 selfProfile">
          <ul class="list-unstyled">
            <li>Dispatch Through</li>
            <li><strong>NASHIK GLOBAL TRANSPORT</strong></li>
          </ul>
        </div>
        <div class="col-md-6 data">
          <ul class="list-unstyled">
            <li>Destination</li>
            <li>
              <strong>VENDOR CODE S22920</strong>
            </li><br>
          </ul>
        </div>
      </div>
      <div class="row borders">
        <div class="col-md-6 selfProfile">
          <ul class="list-unstyled">
            <li>Bill of Lading/LR-PR No.</li>
            <li>
              <h5>dt. 28-Jan-24</h5>
            </li>
          </ul>
        </div>
        <div class="col-md-6 data">
          <ul class="list-unstyled">
            <li>Motor Vehicle No.</li>
            <li>
              <h5>MH15JC2677</h5>
            </li>
          </ul>
        </div>
      </div>

      <div class="row bordersBottom">
        <div class="col-md-6 ">
          <ul class="list-unstyled">
            <li>Terms Of Delivery</li><br><br>

          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
          </ul>
        </div>
      </div>
    </div>
  </div><br>
  <div class="row ">
    <div class="col-sm-12 col-lg-12 col-xl-12 m-b-20">
      <p>Dear Sir , Please arrange to supply following Material as per quantity , specification and schedule mentiond
        below </p>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
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
            @php
            $items = json_decode($invoice->items, true);
            @endphp

            @if(is_array($items))
            @foreach ($items as $item)
            <tr>
              <td>{{ $item['id'] }}</td>
              <td>{{ $item['part_no'] }}</td>
              <td class="d-none d-sm-table-cell"
                style="max-width: 150px !important; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
                {{ $item['description'] }}</td>
              <td>{{ $item['due_date'] }}</td>
              <td>{{ $item['hsn'] }}</td>
              <td>{{ $item['quantity'] }}</td>
              <td>{{ $item['rate'] }}</td>
              <td class="text-right">{{$item['amount']}}</td>

            </tr>
            @endforeach
            @endif
          </tbody>
          <tfoot>

            <tr>
              <td></td>
              <td>Total</td>
              <td class="d-none d-sm-table-cell"
                style="max-width: 150px !important; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
              </td>
              <td>No. S</td>
              <td></td>
              <td></td>
              <td></td>
              <td class="text-right">
                {{(($invoice->total -(($invoice->discount/100) * $invoice->total))  + (($invoice->tax/100) * ($invoice->total-(($invoice->discount/100) * $invoice->total))))+((($invoice->tax/100) * ($invoice->total-(($invoice->discount/100) * $invoice->total))))}}
              </td>

            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="row ">
    <div class="col-sm-12 col-lg-12 col-xl-12 m-b-20">
      <ul class="list-unstyled">
        <li>Amount Chargable (in words)</li>
        <li>
          <h5>One hundred seventy-one thousand three hundred thirty-six</h5>
        </li>
      </ul>
    </div>
  </div><br>
  <div class="row ">
    <div class="col-sm-12 col-lg-12 col-xl-12 m-b-20">
      <table style="width:100%" border="1">
        <tr>
          <th rowspan="2" class="col-md-8">Taxable Value</th>
          <td colspan="2">
            <center>IGST</center>
          </td>
          <td rowspan="2">Total Tax Amount</td>
        </tr>
        <tr>
          <td class="col-md-1">Rate</td>
          <td>Amount</td>
        </tr>
        <tr>
          <td>145,200</td>
          <td>18%</td>
          <td>26136</td>
          <td>26136</td>
        </tr>
        <tr>
          <td>145,200</td>
          <td></td>
          <td>26136</td>
          <td>26136</td>
        </tr>
      </table>


    </div>
  </div><br>
  <div class="row ">
    <div class="col-sm-12 col-lg-12 col-xl-12 m-b-20">
      <ul class="list-unstyled">
        <li>Amount Chargable (in words) :- <strong>One hundred seventy-one thousand three hundred thirty-six</strong>
        <li>Company's PAN :- <strong>AAHCS6330</strong>
        </li>
    </div>
  </div>
  <div class="row ">
    <div class="col-sm-6 col-lg-6 col-xl-6 m-b-6 selfProfile">
      <ul class="list-unstyled">
        <h6>Declaration</h6>
        <li>By my signature, I acknowledge that I have read, understand, and agree to
          the policies and procedures of outpatient treatment as defined in the
          outpatient welcome packet that I received.</li>
    </div>
    <div class="col-sm-6 col-lg-6 col-xl-6 m-b-6 data">
      <ul class="list-unstyled">
        <h6></h6>
        <li>ASN:</li>
        <li>Electronic Reference No:</li>
        <li>Time Of Study:</li>
        <li>Wheteher Tac Can Reverse Charge: No</li>
    </div>
  </div><br><br><br><br>
  <div class="row ">
    <div class="col-sm-6 col-lg-6 col-xl-6 m-b-6 selfProfile">
      <ul class="list-unstyled">
        <h6>Customer's Seal and Signature : -</h6>
    </div>
    <div class="col-sm-6 col-lg-6 col-xl-6 m-b-6 data">
      <ul class="list-unstyled">
        <h6>for Shreerag Engineering & Auto Pvt. Ltd.</h6>
    </div>
  </div>


  </ul>
</div>
</div>
</div>

@endsection


@section('scripts')
<!-- Select2 JS -->
<script>
function printInvoice() {
  window.print();
}
</script>
@endsection