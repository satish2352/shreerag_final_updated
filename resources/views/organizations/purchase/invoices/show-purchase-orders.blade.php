@extends('admin.layouts.master')
@section('content')
<style type="text/css" media="print">
.no-print {
  display: none !important;
}

.left {
  float: left;
  width: 50%;
}

.right {
  float: right;
  width: 50%;
}

.left {
  float: left;
  width: 50%;
}

.right {
  float: right;
  width: 50%;
}

.tops {
  margin-top: -83px;
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
  <div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12 selfProfile asdf">
      <hr>
      <center>
        <h4>SHREERAG ENGINEERING & AUTO PVT.LTD.</h4>
        <center>
          <hr>
    </div>
  </div>
  <div class="row ">
    <div class="col-md-6 col-lg-6 col-sm-6 left">
      <ul class="list-unstyled">
        <li>
          <h5>SUPPLIER NAME:- SHREERAJ ENGINEERING</h5>
        </li>
        <li>
          <h5>GAT NO. 679/2/1, KURULI- ALANDI ROAD , CHAKKAN , TAL. KHED , DIST. PUNE-410501</h5>
        </li>
      </ul>
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 right">
      <div class="row ">
        <div class="col-md-8 col-lg-8 col-sm-8 left">
          <ul class="list-unstyled">
            <li>
              <h5>GRN. NO:- 1686</h5>
            </li>
            <li>
              <h5>PO No. :- 1617</h5>
            </li>
            <li>
              <h5>DC/Inv No.:- 398<h5>
            </li>
          </ul>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4 asdf">
          <ul class="list-unstyled">
            <li>
              <h5>GRN. DATE:- 27/01/2024</h5>
            </li>
            <li>
              <h5>PO Date. :- 25/01/2024</h5>
            </li>
            <li>
              <h5>DC/Inv Date:- 25/01/2024<h5>
            </li>
          </ul>
        </div>
      </div>



    </div>
  </div>
</div>
<div class="row ">
  <div class="col-sm-12 col-lg-12 col-xl-12 m-b-20">
    <div class="table-responsive">
      <table class="table table-striped table-hover" border='1'>
        <thead>
          <tr>
            <th>#</th>
            <th class="col-sm-2">Part No. Description.</th>
            <th class="col-md-2">QC Check Remark:- </th>
            <th class="col-md-2">Challan Qty.</th>
            <th class="col-md-2">Actual Qty.</th>
            <th class="col-md-2">Accepted Qty.</th>
            <th class="col-md-2">Rej Qty.</th>
          </tr>
        </thead>
        <tbody>
          <td>1</td>
          <td>6"*2" SPRING LOADED BRACKET WITH RED CIPU WHEEL [ FIX = @ NOS, S/W=@ NOS]</td>
          <td>10</td>
          <td>SETS</td>
          <td>0</td>
          <td>0</td>
          <td>0.00</td>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="row ">
  <center>
    <div class="col-md-6 col-lg-6 col-sm-6 left">

    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 right">
      <div class="row ">
        <div class="col-md-3 col-lg-3 col-sm-3 left">

          <p>Accepted Qty.:- 4.00</p>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-3 right">
          <p>Rejected Qty.:- 0.00</p>
        </div>

      </div>
  </center>
</div>
<div class="row ">
  <center>
    <div class="col-md-6 col-lg-6 col-sm-6 left"><br><br><br><br>
      <p>(Receiver's Signatory)</p>
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 right">
      <p>For SHREERAG ENGINEERING & AUTO PVT. LTD.</p><br><br><br>
      <h6>(Authorized Signatory)</h6>
    </div>
  </center>
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