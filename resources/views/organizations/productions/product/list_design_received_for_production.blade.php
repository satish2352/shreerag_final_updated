@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>New Design Received For Production <span class="table-project-n"></span></h1>
                        </div>
                    </div>                     
                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">                          
                            <div class="table-responsive"> 
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="id">ID</th>   
                                            <th data-field="date" data-editable="false">Sent Date</th>
                                            <th data-field="project_name" data-editable="false">Project Name</th>
                                            <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                            <th data-field="purchase_id" data-editable="false">Remark</th>                                         
                                            <th data-field="status">Status</th>                                                                                                                          
                                        </tr>
                                    </thead>                                   
                                    <tbody>
                                        @foreach($data_output as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                            <td>{{ ucwords($data->project_name) }}</td>
                                            <td>{{ ucwords($data->customer_po_number) }}</td>
                                            <td>{{ucwords($data->remarks)}}</td>
                                            <td> <a href="{{ route('list-new-requirements-received-for-production-business-wise', base64_encode($data->business_id)) }}" ><button class="btn btn-sm btn-bg-colour" type="submit" >View Details</button></a></td>
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
