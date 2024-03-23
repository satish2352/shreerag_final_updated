@extends('admin.layouts.master')
@section('content')
<style>
.form-control {
  border: 2px solid #ced4da;
  border-radius: 4px;
}
</style>
<div class="data-table-area mg-tb-15">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline13-list">
          <div class="sparkline13-hd">
            <div class="main-sparkline13-hd">
              <h1>Purchase Order <span class="table-project-n">Data</span> Table</h1>
            </div><br>
            <form action="{{route('update-purchase-order')}}" method="post" enctype="multipart/form-data">
              @csrf

              <div class="row">
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Client Name<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="client_name" value="{{$invoice->client_name}}">
                    <input class="form-control" type="hidden" name="id" value="{{$invoice->id}}">
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Phone Number <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="phone_number" value="{{$invoice->phone_number}}">
                  </div>
                </div>

                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{$invoice->email}}">
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Tax</label>
                    <select name="tax" class="form-control" title="select tax" id="inv_tax">
                      <option value="null">Select Tax</option>
                      <option value="9" {{ $invoice->tax == 9 ? 'selected' : '' }}>C-GST</option>
                      <option value="9" {{ $invoice->tax == 9 ? 'selected' : '' }}>S-GST</option>
                      <option value="18" {{ $invoice->tax == 18 ? 'selected' : '' }}>C-GST + S-GST</option>
                    </select>
                  </div>
                </div>

              </div>
              <div class="row">

                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Invoice date <span class="text-danger">*</span></label>
                    <div class="cal-icon">
                      <input class="form-control datetimepicker" type="text" name="invoice_date"
                        value="{{$invoice->invoice_date}}">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>GST Number<span class="text-danger">*</span></label>
                    <div class="cal-icon">
                      <input class="form-control " type="text" name="gst_number" value="{{$invoice->gst_number}}">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Payment Terms</label>
                    <select name="payment_terms" class="form-control" title="select payment terms" id="">
                      <option value="null">Select Payment Terms</option>
                      <option value="30" {{ $invoice->payment_terms == 30 ? 'selected' : '' }}>30 Days</option>
                      <option value="60" {{ $invoice->payment_terms == 60 ? 'selected' : '' }}>60 Days</option>
                      <option value="90" {{ $invoice->payment_terms == 90 ? 'selected' : '' }}>90 Days</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Client Address</label>
                    <textarea class="form-control" rows="3"
                      name="client_address">{{ $invoice->client_address }}</textarea>
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-hover table-white repeater">
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
                          <th><button type="button" class="btn btn-sm btn-success font-18 mr-1" title="Add"
                              data-repeater-create>
                              <i class="fa fa-plus"></i>
                            </button> </th>
                        </tr>
                      </thead>
                      <tbody data-repeater-list="items">
                        @php
                        try {
                        $items = json_decode($invoice->items, true, 512, JSON_THROW_ON_ERROR);
                        } catch (JsonException $e) {
                        $items = [];
                        }
                        $count=0;
                        @endphp

                        @foreach ($items as $item)
                        $count++;
                        <tr>
                          <td>
                            <input type="text" name="items[id][]" class="form-control" style="min-width:50px" readonly
                              value="{{ $item['id'] }}">
                          </td>
                          <td>
                            <input class="form-control" name="items[part_no][]" value="{{ $item['part_no'] }}"
                              type="text" style="min-width:150px">
                          </td>
                          <td>
                            <input class="form-control" name="items[description][]" value="{{ $item['description'] }}"
                              type="text" style="min-width:150px">
                          </td>
                          <td>
                            <input class="form-control datetimepicker" name="items[due_date][]"
                              value="{{ $item['due_date'] }}" style="width:100px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="items[hsn][]" value="{{ $item['hsn'] }}"
                              style="width:80px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="items[quantity][]" value="{{ $item['quantity'] }}"
                              style="width:120px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="items[rate][]" value="{{ $item['rate'] }}"
                              style="width:120px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="items[amount][]" value="{{ $item['amount'] }}" readonly
                              style="width:120px" type="text">
                          </td>
                          <td>
                            <button type="button" class="btn btn-sm btn-danger font-18 ml-2" title="Delete"
                              data-repeater-delete>
                              <i class="fa fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                        @endforeach
                        <tr data-repeater-item>
                          <td>
                            <input type="text" name="id" class="form-control" style="min-width:50px" readonly value="1">
                          </td>
                          <td>
                            <input class="form-control" name="part_no" type="text" style="min-width:150px">
                          </td>
                          <td>
                            <input class="form-control " name="description" type="text" style="min-width:150px">
                          </td>
                          <td>
                            <input class="form-control datetimepicker" name="due_date" type="text"
                              style="min-width:150px">
                          </td>
                          <td>
                            <input class="form-control" name="hsn" style="width:100px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="quantity" style="width:100px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="rate" style="width:80px" type="text">
                          </td>
                          <td>
                            <input class="form-control" name="amount" readonly style="width:120px" type="text">
                          </td>
                          <td>
                            <button type="button" class="btn btn-sm btn-danger font-18 ml-2" title="Delete"
                              data-repeater-delete>
                              <i class="fa fa-trash"></i>
                            </button>

                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover table-white">
                      <tbody>
                        <tr>
                          <td colspan="6" style="text-align: right">Sub Total</td>
                          <td style="text-align: right;width: 230px">
                            <input class="form-control text-right" value="{{$invoice->total}} Rs." readonly type="text">
                          </td>
                        </tr>
                        <tr>
                          <td colspan="6" style="text-align: right">Discount</td>
                          <td style="text-align: right;width: 230px">
                            <input class="form-control text-right"
                              value="{{'- '.(($invoice->discount/100) * $invoice->total).' Rs. '}}" readonly
                              type="text">
                          </td>
                        </tr>
                        <tr>
                          <td colspan="6" style="text-align: right">Tax</td>
                          <td style="text-align: right;width: 230px">
                            <input class="form-control text-right"
                              value="{{'+ '.(($invoice->tax/100) * ($invoice->total-(($invoice->discount/100) * $invoice->total))).' Rs. '}}"
                              readonly type="text">
                          </td>
                        </tr>

                        <tr>
                          <td colspan="6" style="text-align: right; font-weight: bold">
                            Grand Total
                          </td>
                          <td style="text-align: right; font-weight: bold; font-size: 16px;width: 230px;color:black">
                            {{(($invoice->total -(($invoice->discount/100) * $invoice->total))  + (($invoice->percentage/100) * ($invoice->total-(($invoice->discount/100) * $invoice->total))))+((($invoice->tax/100) * ($invoice->total-(($invoice->discount/100) * $invoice->total))))}}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Discount </label>
                    <input class="form-control text-right" type="text" name="discount" value="{{$invoice->discount}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control">
                      <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                      <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Other Information</label>
                    <textarea class="form-control" name="note">{{$invoice->note}}</textarea>
                  </div>
                </div>
              </div>
              <div class="login-btn-inner">
                <div class="row">
                  <div class="col-lg-5"></div>
                  <div class="col-lg-7">
                    <div class="login-horizental cancel-wp pull-left">
                      <a href="{{ route('list-purchase') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                      <button class="btn btn-sm btn-primary login-submit-cs" type="submit"
                        style="margin-bottom:50px">Save
                        Data</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        @endsection

        @section('scripts')
        <!-- Select2 JS -->
        <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script>
        <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="{{asset('assets/plugins/jquery-repeater/jquery.repeater.min.js')}}"></script>
        <script>
        $(document).ready(function() {
          'use strict';
          var index = `{{$count}}`;

          var tax = $('#inv_tax').val();

          function dateTime() {
            $('.datetimepicker').datetimepicker({
              format: 'YYYY-MM-DD ', // Adjust the format as needed
              showClear: true,
              showClose: true,
              icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-crosshairs',
                clear: 'fa fa-trash',
                close: 'fa fa-times',
              },
              useCurrent: false, // Do not default to the current date
              widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom',
              },
            });
          }

          $('table.repeater').repeater({

            show: function() {
              var id = $(`input[name="items[${index}][id]"]`);
              var part_no = $(`input[name="items[${index}][part_no]"]`);
              var description = $(`input[name="items[${index}][description]"]`);
              var due_date = $(`input[name="items[${index}][due_date]"]`);
              var hsn = $(`input[name="items[${index}][hsn]"]`);
              var quantity = $(`input[name="items[${index}][quantity]"]`);
              var rate = $(`input[name="items[${index}][rate]"]`);
              var amount = $(`input[name="items[${index}][amount]"]`);
              var item_amount = rate.val() * quantity.val();
              console.log(amount.val(item_amount))
              amount.val(item_amount);

              due_date.datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Adjust the format as needed
                showClear: true,
                showClose: true,
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-crosshairs',
                  clear: 'fa fa-trash',
                  close: 'fa fa-times'
                },
                useCurrent: false, // Do not default to the current date
                widgetPositioning: {
                  horizontal: 'auto',
                  vertical: 'bottom'
                }
              });
              index++;
              id.val(index).trigger('change');
              $(this).slideDown();
              dateTime();
            },

            hide: function(deleteElement) {
              index--;
              $(this).slideUp(deleteElement);
            },
          });

          dateTime();


        });
        </script>
        @endsection