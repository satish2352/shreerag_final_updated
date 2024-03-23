@extends('admin.layouts.master')
@section('content')
<style type="text/css" media="print">
.no-print {
  display: none !important;
}

.left {
  float: left;
  width: 70%;
}

.right {
  float: right;
  width: 30%;
}

.lefts {
  float: left;
  width: 50%;
}

.rights {
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
        <h4>Requsition Slip</h4>
        <center>
          <hr>
    </div>
  </div>
  <div class="row ">
    <div class="col-md-8 col-lg-8 col-sm-8 left">
      <p>Requision By:Savita</p>
    </div>
    <div class="col-md-4 col-lg-4 col-sm-4 right">
      <h5>Sr.No.:332</h5>
      <h5>Date:29/01/2024</h5>
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
            <th class="col-sm-2">Description.</th>
            <th class="col-md-2">Quantity</th>
            <th class="col-md-2">UOM</th>
            <th class="col-md-2">Day</th>
            <th class="col-md-2">Remark</th>
            <th class="col-md-2">Stock</th>
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
    <div class="col-md-6 col-lg-6 col-sm-6 lefts"><br><br><br><br>
      <p>(Receiver's Signatory)</p>
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 rights">
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