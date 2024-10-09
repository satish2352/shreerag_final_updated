<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
<style>
.fixed-table-loading {
  display: none;
}

#table thead th {
  white-space: nowrap;
}

#table thead th {
  width: 300px !important;
  padding-right: 49px !important;
  padding-left: 20px !important;
}

.custom-datatable-overright table tbody tr td {
  padding-left: 19px !important;
  padding-right: 5px !important;
  font-size: 14px;
  text-align: left;
}

#clickable-link {
  color: blue;
  text-decoration: underline;
  cursor: pointer;
}

#clickable-link:hover {
  color: red;
  /* Change text color on hover for better visibility */
}
</style>

<div class="data-table-area mg-tb-15">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline13-list">
          <div class="sparkline13-hd">
            <div class="main-sparkline13-hd">
              <h1>Delivery Chalan <span class="table-project-n">Data</span> Table</h1>
              <div class="form-group-inner login-btn-inner row">
                <div class="col-lg-2">
                  <div class="login-horizental cancel-wp pull-left">
                    <form action="{{ route('add-delivery-chalan') }}" method="POST">
                    @csrf
             
                    {{-- <input type="hidden" name="requistition_id" id="requistition_id" value="{{$requistition_id}}"> --}}
                    <button class="btn btn-sm btn-primary login-submit-cs"
                    type="submit">Add Delivery Chalan</button>

                    </form>
                  </div>
                </div>
                <div class="col-lg-10"></div>
              </div>
            </div>
          </div>

          @if (Session::get('status') == 'success')
          <div class="alert alert-success alert-success-style1">
            <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
              <span class="icon-sc-cl" aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-check adminpro-checked-pro admin-check-pro" aria-hidden="true"></i>
            <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
          </div>
          @endif
          @if (Session::get('status') == 'error')
          <div class="alert alert-danger alert-mg-b alert-success-style4">
            <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
              <span class="icon-sc-cl" aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-times adminpro-danger-error admin-check-pro" aria-hidden="true"></i>
            <p><strong>Danger!</strong> {{ Session::get('msg') }}</p>
          </div>
          @endif

          <div class="sparkline13-graph">
            <div class="datatable-dashv1-list custom-datatable-overright">
              <div id="toolbar">
                <select class="form-control">
                  <option value="">Export Basic</option>
                  <option value="all">Export All</option>
                  <option value="selected">Export Selected</option>
                </select>
              </div>


              <div class="table-responsive">
                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true"
                  data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true"
                  data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                  data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                  <thead>
                    <tr>
                      <th data-field="#">#</th>
                      <th data-field="customer_po_number" data-editable="false"> PO Number</th>
                      <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                      <th data-field="transport_name" data-editable="false">Transport Name</th>
                      <th data-field="vehicle_name" data-editable="false">Vehicle Name</th>
                      
                      <th data-field="action">Action</th>
                    </tr>

                  </thead>
                  <tbody>
                   
                    @foreach($getOutput as $data)
                    <tr>
                      
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ucwords($data->customer_po_number)}}</td>
                      <td>{{ucwords($data->vendor_name)}}</td>
                      <td>{{ucwords($data->transport_name)}}</td>
                      <td>{{ucwords($data->vehicle_name)}}</td>
                      
                      {{-- <td>
                        <div style="display: flex; align-items: center;">
                          <a href="{{route('show-delivery-chalan', $data->id)}}"><button
                              data-toggle="tooltip" title="Edit" class="pd-setting-ed">View Details</button></a>
                        </div>
                      </td> --}}
                      <td>
                        <div style="display: flex; align-items: center;">
                            <a href="{{ route('show-delivery-chalan', base64_encode($data->id)) }}">
                                <button data-toggle="tooltip" title="View Details" class="pd-setting-ed">View Details</button>
                            </a>
                        </div>
                    </td>
                    
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              {{-- @if(!$getOutput->isEmpty())
              <form action="{{ route('submit-delivery-chalan-to-owner-for-review') }}" method="POST" style="padding-top: 14px;">
                @csrf
                <input type="hidden" name="requistition_id" id="requistition_id" value="{{$requistition_id}}">
                <button class="btn btn-sm btn-primary login-submit-cs mt-0"
                type="submit">Send to the owner for approval</button>
                </form>
                @endif --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection