@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="emp-form-card">
                    <div class="emp-form-header">
                        <h4><i class="fa fa-edit mr-2"></i> Edit Stock</h4>
                        <a href="{{ route('list-part-item') }}" class="emp-form-close">&times;</a>
                    </div>
                    <div class="emp-form-body">

                        @if(session('msg'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('msg') }}
                            </div>
                        @endif

                        <form action="{{ route('update-product-stock') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
                            @csrf
                            <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id">

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <select class="form-control mb-2 {{ old('part_item_id', $editData->part_item_id) ? 'has-value' : '' }}"
                                            name="part_item_id" id="part_item_id" disabled
                                            onchange="this.classList.toggle('has-value', this.value !== '')">
                                            <option value="" default>Select Item Name</option>
                                            @foreach ($dataOutputPartItem as $service)
                                                <option value="{{ $service['id'] }}"
                                                    {{ old('part_item_id', $editData->part_item_id) == $service->id ? 'selected' : '' }}>
                                                    {{ $service->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="part_item_id">Item Name <span class="text-danger">*</span></label>
                                        @if ($errors->has('part_item_id'))
                                            <span class="fl-error">{{ $errors->first('part_item_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                    <div class="fl-group">
                                        <input type="text" class="form-control" value="@if (old('quantity')) {{ old('quantity') }}@else{{ $editData->quantity }} @endif" id="quantity" name="quantity" placeholder=" ">
                                        <label for="quantity">Quantity</label>
                                    </div>
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
