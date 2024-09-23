@extends('admin.layouts.master')
@section('content')
<style>
    .form-control {
      border: 2px solid #ced4da;
      border-radius: 4px;
    }
    .error {
      color: red !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Update Item Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        @if(session('msg'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('msg') }}
                            </div>
                        @endif

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form action="{{ route('update-product-stock') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id">
                                        <div class="row">
                                           
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="part_item_id">Item Name:</label> &nbsp;<span
                                                        class="red-text">*</span>
                                                    <select class="form-control mb-2"
                                                        name="part_item_id" id="part_item_id" disabled>
                                                        <option value="" default>Select Item Name</option>
                                                        @foreach ($dataOutputPartItem as $service)
                                                            <option value="{{ $service['id'] }}"
                                                                {{ old('part_item_id', $editData->part_item_id) == $service->id ? 'selected' : '' }}>
                                                                {{ $service->description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('part_item_id'))
                                                        <span
                                                            class="red-text">{{ $errors->first('part_item_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                          
                                          
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="quantity">Quantity:</label>
                                                <input type="text" class="form-control" value="@if (old('quantity')) {{ old('quantity') }}@else{{ $editData->quantity }} @endif" id="quantity" name="quantity" placeholder="Enter open stock">
                                            </div>
                                        </div>

                                    <div class="login-btn-inner">
                                        <div class="row">
                                            <div class="col-lg-5"></div>
                                            <div class="col-lg-7">
                                                <div class="login-horizental cancel-wp pull-left">
                                                    <a href="{{ route('list-part-item') }}">
                                                        <button class="btn btn-white" style="margin-bottom:50px">Cancel</button>
                                                    </a>
                                                    <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Update Data</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

<script>
    $(document).ready(function() {
        // Custom validation rule to check if the input does not contain only spaces
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Enter some valid text");

        // Initialize the form validation
        $("#regForm").validate({
            rules: {
                part_number: {
                    required: true,
                    spcenotallow: true,  // Apply custom space check
                },
                description: {
                    required: true,
                    spcenotallow: true,  // Apply custom space check
                },
                // extra_description: {
                //     spcenotallow: true,  // Extra description is optional but cannot be only spaces
                // },
                unit_id: {
                    required: true,
                },
                hsn_id: {
                    required: true,
                },
                group_type_id: {
                    required: true,
                },
                basic_rate: {
                    required: true,
                    number: true, // Ensure it's a valid number
                },
                opening_stock: {
                    required: true,
                    number: true,  // Ensure it's a valid number
                }
            },
            messages: {
                part_number: {
                    required: "Please enter the part number.",
                    spcenotallow: "Part number cannot contain only spaces.",
                },
                description: {
                    required: "Please enter the description.",
                    spcenotallow: "Description cannot contain only spaces.",
                },
                // extra_description: {
                //     spcenotallow: "Extra description cannot contain only spaces.",
                // },
                unit_id: {
                    required: "Please select a unit name.",
                },
                hsn_id: {
                    required: "Please select an HSN name.",
                },
                group_type_id: {
                    required: "Please select a group name.",
                },
                basic_rate: {
                    required: "Please enter the basic rate.",
                    number: "Please enter a valid number for the basic rate.",
                },
                opening_stock: {
                    required: "Please enter the opening stock.",
                    number: "Please enter a valid number for the opening stock.",
                }
            }
        });
    });
</script>


@endsection
