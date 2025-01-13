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
                    <center><h1>Add Stock Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form action="{{ route('store-product-stock') }}" method="POST" enctype="multipart/form-data" id="regForm">
                                    @csrf
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="part_item_id">Item Name <span class="text-danger">*</span></label>
                                                <select class="form-control" name="part_item_id" id="part_item_id">
                                                    <option value="">Select Item Name</option>
                                                    @foreach ($dataOutputPartItem as $data)
                                                        <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('part_item_id'))
                                                <span class="red-text">{{ $errors->first('part_item_id') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="quantity">Quantity:</label>
                                                <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Enter stock quantity">
                                                @if ($errors->has('quantity'))
                                                <span class="red-text">{{ $errors->first('quantity') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-inventory-material') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
                                                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        // Custom method to check for spaces only
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Field cannot contain only spaces.");

        // Validate the form
        $("#regForm").validate({
            rules: {
                part_item_id: {
                    required: true,
                    spcenotallow: true
                },
                quantity: {
                    required: true,
                    spcenotallow: true,
                    number: true // Ensures only valid numeric values
                }
            },
            messages: {
                part_item_id: {
                    required: "Please select the part number.",
                    spcenotallow: "Part number cannot contain only spaces."
                },
                quantity: {
                    required: "Please enter a quantity.",
                    spcenotallow: "Quantity cannot contain only spaces.",
                    number: "Please enter a valid number for quantity."
                }
            }
        });
    });
</script>

@endsection
