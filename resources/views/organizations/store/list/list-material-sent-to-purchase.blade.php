@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material List Sent To <span class="table-project-n">Purchase</span> Department</h1>
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
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control"
                                                    placeholder="Search Project Name / Product Name  / PO No.">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project Name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                {{-- <th data-field="title" data-editable="false">Name</th> --}}
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                <th data-field="bom_image" data-editable="false">Requisition BOM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td>{{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ ucwords($data->customer_project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    {{-- <td>{{ucwords($data->title)}}</td> --}}
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td>
                                                        @if(!empty($data->requistition_id))
                                                            <button type="button" class="btn btn-sm btn-info"
                                                                data-toggle="modal"
                                                                data-target="#storeBomModal{{ $data->requistition_id }}">
                                                                <i class="fa fa-list"></i> View BOM
                                                            </button>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ $data_output->onEachSide(1)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- BOM Requisition Modals — same pattern reused from Purchase dept's
     list-bom-material-recived-for-purchase.blade.php (without the PO Created /
     Not Ordered status badges since the store dept doesn't create POs). --}}
@foreach ($data_output as $data)
    @php
        $reqItems = $requisitionItemsMap[$data->requistition_id] ?? collect();
    @endphp
    @if(!empty($data->requistition_id))
    <div class="modal fade" id="storeBomModal{{ $data->requistition_id }}" tabindex="-1" role="dialog"
         aria-labelledby="storeBomModalLabel{{ $data->requistition_id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width:95%; margin:30px auto;">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a3a6b; color:#fff;">
                    <h5 class="modal-title" id="storeBomModalLabel{{ $data->requistition_id }}">
                        <i class="fa fa-list"></i>
                        BOM Requisition — {{ ucwords($data->product_name) }}
                        <small style="font-size:13px; opacity:0.85;">({{ ucwords($data->customer_project_name) }})</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height:70vh; overflow-y:auto; padding:12px;">
                    @if($reqItems->isEmpty())
                        <div class="alert alert-warning">No BOM items found for this requisition.</div>
                    @else
                        <div style="overflow-x:auto; width:100%;">
                        <table class="table table-bordered table-hover table-sm" style="min-width:800px; width:100%; font-size:13px;">
                            <thead style="background:#1a3a6b; color:#fff;">
                                <tr>
                                    <th style="width:40px; white-space:nowrap;">Sr.</th>
                                    <th style="min-width:180px;">Product Description</th>
                                    <th style="white-space:nowrap;">Required Qty</th>
                                    <th style="white-space:nowrap;">Available Stock</th>
                                    <th style="white-space:nowrap;">Shortage Qty</th>
                                    <th style="white-space:nowrap;">Unit</th>
                                    <th style="white-space:nowrap;">Rate</th>
                                    <th style="white-space:nowrap;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $modalTotal = 0; @endphp
                                @foreach($reqItems as $ri => $ritem)
                                    @php
                                        $rTotal      = (float)($ritem->shortage_quantity ?? 0) * (float)($ritem->rate ?? 0);
                                        $modalTotal += $rTotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $ri + 1 }}</td>
                                        <td>{{ $ritem->product_description ?? (optional($ritem->partItem)->description ?? '—') }}</td>
                                        <td>{{ number_format($ritem->required_quantity, 3) }}</td>
                                        <td>{{ number_format($ritem->available_quantity, 3) }}</td>
                                        <td><strong style="color:#dc3545;">{{ number_format($ritem->shortage_quantity, 3) }}</strong></td>
                                        <td>{{ optional($ritem->unitMaster)->name ?? '—' }}</td>
                                        <td>{{ $ritem->rate !== null ? number_format((float)$ritem->rate, 3) : '—' }}</td>
                                        <td><strong>{{ number_format($rTotal, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#f0f0f0; font-weight:700;">
                                    <td colspan="7" style="text-align:right; padding-right:12px;">Grand Total</td>
                                    <td><strong>{{ number_format($modalTotal, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if(!empty($data->bom_file))
                    <a href="{{ Config::get('FileConstant.REQUISITION_VIEW') }}{{ $data->bom_file }}"
                       class="btn btn-secondary btn-sm" target="_blank">
                        <i class="fa fa-download"></i> Download File
                    </a>
                    @endif
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection
