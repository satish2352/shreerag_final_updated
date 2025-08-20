
@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material Received <span class="table-project-n">And Need To Forward To Store</span> Department</h1>
                               
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
                               


                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="grn_number" data-editable="false">Product Name</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                {{-- <th data-field="store_material_sent_date" data-editable="false">Matrial Recieved Date</th> --}}
                                                {{-- <th data-field="design_image" data-editable="false">Purchase order</th> --}}
                                                <th data-field="" data-editable="false">Purchase Order</th>
                                                    <th data-field="gatepass_count" data-editable="false">Count</th>
                                                <th data-field="bom_image" data-editable="false">Genrate Gate Pass</th>

                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                           
                                                            <a href="{{ route('list-po-details', [base64_encode($data->gatepass_id), base64_encode($data->purchase_orders_id)]) }}">
                                                                <button data-toggle="tooltip"
                                                                title="View PO" class="btn btn-sm btn-bg-colour">Check PO Details</button></a>
                                                        
                                                    </div>
                                                    </td>
                                                  <td>{{ $data->gatepass_count }}</td>
                                                    {{-- <td>
                                                        <div style="display: flex; align-items: center;">
                                                               
                                                                <a href="{{ route('check-details-of-po-before-send-vendor', $data->purchase_orders_id) }}"
                                                                 >
                                                                    <button data-toggle="tooltip"
                                                                    title="View PO" class="pd-setting-ed">Check PO Details</button></a>
                                                            
                                                        </div>
                                                        </td> --}}
                                                <td> 
                                                    <a  href="{{route('add-gatepass-with-po', base64_encode($data->purchase_orders_id))}}"
                                                    alt="Design">
                                                
                                                 <button data-toggle="tooltip"
                                                                title="View PO" class="btn btn-sm btn-bg-colour">Generate Gate Pass</button>
                                                </a>
{{-- 
                                                    <a class="btn btn-sm btn-primary login-submit-cs" type="button"
                                                    href="{{ route('add-gatepass-with-po', ['id' => base64_encode($data->purchase_orders_id), 'productionId' => base64_encode($data->productionId)]) }}"
                                                    alt="Design">Generate Gate Pass</a> --}}
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
@endsection
