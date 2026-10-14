@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            font-size: 12px;
        }

        .disabled-row {
            background-color: #f0f0f0;
            opacity: 0.6;
        }

        .red-text {
            color: red;
        }

        .disabled-row input,
        .disabled-row select,
        .disabled-row button {
            pointer-events: none;
        }

        .marg-top {
            margin-top: 30px !important;
        }
    </style>
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
      <div class="emp-form-header">
        <h4><i class="fa fa-chart-bar mr-2"></i> Edit Quantity Tracking</h4>
        <a href="{{ route('list-material-received') }}" class="emp-form-close">&times;</a>
      </div>
      <div class="emp-form-body">

                                @if (Session::get('status') == 'success')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-success" id="success-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-danger" id="error-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="all-form-element-inner">
                                    <form action="{{ route('update-final-production-completed-status', $id) }}"
                                        method="POST" id="addProductForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="business_details_id" id="business_details_id"
                                            value="{{ $id }}">
                                        <input type="hidden" name="part_item_id" id="part_item_id"
                                            value="{{ $id }}">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                <div class="fl-group">
                                                    <input type="text" class="form-control" id="name"
                                                        name="product_name" placeholder=" "
                                                        value="{{ $productDetails->product_name }}" readonly>
                                                    <label for="name">Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                <div class="fl-group">
                                                    <input type="text" class="form-control" id="description"
                                                        name="description" placeholder=" "
                                                        value="{{ $productDetails->description }}" readonly>
                                                    <label for="description">Description</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                <div class="fl-group">
                                                    <input type="text" class="form-control" id="quantity"
                                                        name="quantity" placeholder=" "
                                                        value="{{ $productDetails->quantity }}" readonly>
                                                    <label for="quantity">Actual Quantity</label>
                                                </div>
                                            </div>

                                            @if ($productDetails->completed_quantity == null)
                                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                    <div class="fl-group">
                                                        <input type="text" class="form-control" placeholder=" "
                                                            value="0" readonly>
                                                        <label>Completed Production</label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                    <div class="fl-group">
                                                        <input type="text" class="form-control" placeholder=" "
                                                            value="{{ $productDetails->completed_quantity }}" readonly>
                                                        <label>Completed Production</label>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($productDetails->completed_quantity == null)
                                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                    <div class="fl-group">
                                                        <input type="text" class="form-control" id="remaining_quantity"
                                                            name="remaining_quantity" placeholder=" "
                                                            value="{{ $productDetails->quantity }}" readonly>
                                                        <label for="remaining_quantity">Balance Quantity</label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                    <div class="fl-group">
                                                        <input type="text" class="form-control" id="remaining_quantity"
                                                            name="remaining_quantity" placeholder=" "
                                                            value="{{ $productDetails->remaining_quantity }}" readonly>
                                                        <label for="remaining_quantity">Balance Quantity</label>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                                <div class="fl-group">
                                                    <input type="text" class="form-control" id="completed_quantity"
                                                        name="completed_quantity" placeholder=" " value="">
                                                    <label for="completed_quantity">Complete Quantity <span
                                                            class="red-text">*</span></label>
                                                    @if($errors->has('completed_quantity'))
                                                        <span class="fl-error">{{ $errors->first('completed_quantity') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row d-flex justify-content-center">
                                            <div class="login-btn-inner marg-top">
                                                <a href="{{ route('list-material-received') }}" class="btn btn-white me-2">Cancel</a>
                                                <button class="btn btn-sm btn-bg-colour" type="submit">Submit Completed Quantity</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

      </div>
    </div>
    </div></div></div></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            // Validate the form
            $("#addProductForm").validate({
                rules: {
                    completed_quantity: {
                        required: true,
                        number: true,
                        min: 1,
                        max: function() {
                           return parseInt($("#remaining_quantity").val());
                        }
                    }
                },
                messages: {
                    completed_quantity: {
                        required: "Please enter the completed quantity.",
                        number: "Please enter a valid number.",
                        min: "Completed quantity must be at least 1.",
                        max: "Completed quantity cannot exceed the actual quantity."
                    }
                },
                submitHandler: function(form) {
                    // Confirmation dialog before submitting the form
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you want to submit the completed quantity?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, submit it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
