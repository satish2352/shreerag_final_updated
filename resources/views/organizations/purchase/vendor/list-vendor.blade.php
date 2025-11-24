@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Vendor <span class="table-project-n">List</span> Table</h1>
                                <div class="form-group-inner login-btn-inner row">
                                    <div class="col-lg-2" >
                                        <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('add-vendor') }}" ><button class="btn btn-sm btn-primary login-submit-cs" type="submit" >Add Vendor</button></a>
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
                          
                           
                          
                            <div class="table-responsive"> 
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="id">Sr.No.</th>
                                            <th data-field="vendor_name" data-editable="false">Vendor Name</th>
                                            <th data-field="vendor_company_name" data-editable="false">Company Name</th>
                                            <th data-field="email" data-editable="false">Email</th>
                                            <th data-field="contact_no" data-editable="false">Conatct No.</th>
                                            <th data-field="gst_no" data-editable="false">GST No.</th>
                                            <th data-field="quote_no" data-editable="false">Quote No.</th>
                                            <th data-field="payment_terms" data-editable="false">Payment terms</th> 
                                            <th data-field="address" data-editable="false">Address</th>
                                            <th data-field="action">Action</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                       @foreach($data_output as $vendor_data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor_data->vendor_name }}</td>
                                            <td>{{ $vendor_data->vendor_company_name }}</td>
                                            <td>{{ $vendor_data->vendor_email }}</td>
                                            <td>{{ $vendor_data->contact_no }}</td>
                                            <td>{{ $vendor_data->gst_no }}</td>
                                            <td>{{ $vendor_data->quote_no }}</td>
                                            <td>{{ $vendor_data->payment_terms }}</td>
                                            <td>{{ $vendor_data->vendor_address }}</td>                                         
                                            <td>
                                                <div style="display: flex; align-items: center;">
                                                    <a href="{{route('edit-vendor', base64_encode($vendor_data->id))}}"><button data-toggle="tooltip" title="Edit" class="pd-setting-ed"><i class="fas fa-pen-square" aria-hidden="true"></i></button></a>
                                                    <a href="{{route('delete-vendor', base64_encode($vendor_data->id))}}" id="saveButton"><button data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
            <script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
            <script src="{{ asset('js/password-meter/password-meter-active.js') }}"></script>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

            <script>
    $.noConflict();
    jQuery(document).ready(function($) {
        $("#saveButton").click(function(event) {
            event.preventDefault(); // Prevent the default behavior of anchor element
            var deleteUrl = $(this).attr('href'); // Get the delete URL
            
            // Use SweetAlert to show a confirmation dialog
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to delete this Vendor?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(function(result) {
                if (result.isConfirmed) {
                    // If user clicks "Yes", navigate to the delete URL
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>

@endsection
