@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Owner Updated Amounts — Pending Your Review</h1>
                            </div>
                        </div>
                        @if (isset($data_output) && count($data_output) > 0)
                            {{-- Use a custom class instead of Bootstrap's .alert so the global
                                 footer timer ($(".alert").alert('close') after 1s) does NOT
                                 dismiss this informational note before the user can read it. --}}
                            <div class="bom-owner-update-note" style="margin: 10px 0; padding: 12px 16px; background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; border-radius: 4px;">
                                <strong>Note:</strong> The owner has responded to the exceed-amount item(s) below —
                                either by updating the business amount or rejecting the request. Please review the
                                response and remark, update your estimation accordingly (reduce the amount if
                                rejected), then send it to the owner again.
                            </div>
                        @endif
                        <div class="sparkline13-graph">
                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                <th data-field="date">Owner Response Date</th>
                                                <th data-field="project_name">Project Name</th>
                                                <th data-field="po_number">PO Number</th>
                                                <th data-field="your_amount">Your Amount (₹)</th>
                                                <th data-field="your_remark">Your Remark</th>
                                                <th data-field="owner_amount">Owner Suggested (₹)</th>
                                                <th data-field="owner_remark">Owner Remark</th>
                                                <th data-field="status">Status</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                @php
                                                    // T-2026-057: bom_estimation_send_to_owner 1302 = owner rejected the
                                                    // exceed-amount request (sibling to 1301 = owner suggested amount).
                                                    $isRejected = ($data->bom_estimation_send_to_owner ?? null) == 1302
                                                        || !empty($data->is_exceed_rejected);
                                                    $responseDate = $isRejected ? $data->exceed_rejected_at : $data->owner_suggested_at;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $responseDate ? \Carbon\Carbon::parse($responseDate)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ ucwords($data->project_name ?? '') }}</td>
                                                    <td>{{ ucwords($data->customer_po_number ?? '') }}</td>
                                                    <td>₹{{ number_format($data->total_estimation_amount, 2) }}</td>
                                                    <td>{{ $data->exceed_remark ?? '-' }}</td>
                                                    <td>
                                                        @if ($isRejected)
                                                            <span class="text-muted">—</span>
                                                        @else
                                                            <strong
                                                                class="text-success">₹{{ number_format($data->owner_suggested_amount, 2) }}</strong>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $isRejected ? ($data->exceed_rejected_remark ?? '-') : ($data->owner_suggestion_remark ?? '-') }}
                                                    </td>
                                                    <td>
                                                        @if ($isRejected)
                                                            <span class="badge badge-danger" style="font-size:12px; padding:5px 8px;">
                                                                Rejected by Owner
                                                            </span>
                                                        @else
                                                            <span class="badge badge-success" style="font-size:12px; padding:5px 8px;">
                                                                Owner Suggested Amount
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{ route('accept-owner-suggested-amount', base64_encode($data->business_details_id)) }}"
                                                            class="accept-btn"
                                                            data-amount="{{ number_format($data->owner_suggested_amount, 2) }}">
                                                            <button class="btn btn-sm btn-success" type="button">
                                                                Accept Owner Amount
                                                            </button>
                                                        </a> --}}
                                                        &nbsp;
                                                        <a
                                                            href="{{ route('edit-estimation', base64_encode($data->business_details_id)) }}">
                                                            <button class="btn btn-sm btn-primary" type="button"
                                                                data-toggle="tooltip"
                                                                title="{{ $isRejected ? 'Reduce/revise the estimation amount and resubmit' : 'Modify estimation and resubmit (if still above new limit, exceed flow restarts)' }}">
                                                                Edit Estimation
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No owner-suggested amounts
                                                        pending review.</td>
                                                </tr>
                                            @endforelse
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
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
            jQuery.noConflict();
            jQuery(document).ready(function($) {
                $(document).on('click', '.accept-btn', function(e) {
                    e.preventDefault();
                    var href = $(this).attr('href');
                    var amount = $(this).data('amount');
                    Swal.fire({
                        icon: 'question',
                        title: 'Accept Owner Amount?',
                        text: 'This will set your estimation amount to ₹' + amount +
                            ' and send it to the owner for normal approval.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Accept',
                        cancelButtonText: 'Cancel',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
