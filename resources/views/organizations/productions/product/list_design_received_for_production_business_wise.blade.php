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
#table thead th{
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
.mb-4{
    margin-bottom:4%;
}
button.pd-setting-ed {
    border: 1px solid rgb(0 0 0 / 39%) !important;
}
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>New Design Recived For Production <span class="table-project-n"></span></h1>
                            <div class="form-group-inner login-btn-inner row">
                                <div class="col-lg-2" >
                                    {{-- <div class="login-horizental cancel-wp pull-left">
                                            <a href="{{ route('add-design-upload') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" >Add Design</button></a>
                                    </div> --}}
                                </div>
                                <div class="col-lg-10">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                      @if (Session::get('status') == 'success')
                           <div class="alert alert-success alert-success-style1">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
										<span class="icon-sc-cl" aria-hidden="true">&times;</span>
									</button>
                                {{-- <i class="fa fa-check adminpro-checked-pro admin-check-pro" aria-hidden="true"></i> --}}
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
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            
                                            <th data-field="id">ID</th>   
                                            {{-- <th data-field="customer_po_number" data-editable="true">PO Number</th> --}}
                                            <th data-field="product_name" data-editable="true">Product Name</th>
                                            {{-- <th data-field="title" data-editable="true">Name</th> --}}
                                            <th data-field="quantity" data-editable="true">Quantity</th>
                                            <th data-field="grn_date" data-editable="true">Description</th>
                                            {{-- <th data-field="purchase_id" data-editable="true">Remark</th>                                          --}}
                                            <th data-field="design_image" data-editable="false">Design Layout</th>
                                            <th data-field="bom_image" data-editable="false">BOM</th>                                                                                                                           
                                            <th data-field="action">Action</th>
                                        </tr>

                                    </thead>

                                   

                                    <tbody>
                                        @foreach($data_output as $data)
                                        <tr>
                                            
                                            <td>{{ $loop->iteration }}</td>
                                            {{-- <td>{{ ucwords($data->customer_po_number) }}</td> --}}
                                            <td>{{ucwords($data->product_name)}}</td>
                                            {{-- <td>{{ucwords($data->title)}}</td> --}}
                                            <td>{{ucwords($data->quantity)}}</td>
                                            <td>{{ ucwords($data->description) }}</td>
                                            {{-- <td>{{ucwords($data->remarks)}}</td>                                             --}}
                                            <td><a class="img-size" target="_blank"
                                                href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                alt="Design"> Click to view</a>
                                            </td>
                                            <td> <a class="img-size"
                                                    href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                    alt="bill of material" >Click to download</a>
                                            </td>
                                            
                                            <td>
                                                <div style="display: inline-block; align-items: center;">
                                                    <a href="{{route('reject-design-edit', base64_encode($data->productionId))}}"><button data-toggle="tooltip" title="Edit" class="pd-setting-ed mb-4"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Reject</button></a> &nbsp;
                                                    {{-- <a href="{{ route('accept-design', base64_encode($data->productionId)) }}"
                                                        onclick="return confirmAccept('{{ route('accept-design', base64_encode($data->id)) }}')"
                                                        class="pd-setting-ed"
                                                        data-toggle="tooltip"
                                                        title="Accept">
                                                        <button><i class="fa fa-check" aria-hidden="true"></i> Accept </button>
                                                    </a> --}}

                                                    <a href="{{ route('accept-design', base64_encode($data->productionId)) }}"
                                                        onclick="return confirmAccept('{{ route('accept-design', base64_encode($data->productionId)) }}')"
                                                        class="pd-setting-ed"
                                                        data-toggle="tooltip"
                                                        title="Accept">
                                                        <button><i class="fa fa-check" aria-hidden="true"></i> Accept </button>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function confirmAccept(acceptUrl) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to accept this design and send for production ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, accept it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, redirect to the accept URL
                window.location.href = acceptUrl;
            }
        });
        // Prevent the default link action until the user confirms
        return false;
    }
</script>

@endsection