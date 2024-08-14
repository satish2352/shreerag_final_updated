@extends('admin.layouts.master')
@section('content')
    <div class="show-page-position">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center">
                    <h3 class="page-title">
                        Notice Detail
                    </h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 show-btn-position">
                    <a href="{{ route('list-notice') }}" class="btn btn-sm btn-primary ml-3">Back</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="sparkline12-list">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="row">
                                @if($showData->isNotEmpty())
                                    @php
                                        $notice = $showData->first();
                                    @endphp
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                    <label>Department Name :</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <label>{{ strip_tags($notice->department_name) }}</label>
                                </div>
                            </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                            <label>Title :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($notice->title) }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                            <label>Description :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($notice->description) }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                            <label> Upload :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                          

                                            <a href="{{ Config::get('DocumentConstant.NOTICE_VIEW') }}{{ $notice['image'] }}" target="blank"> Click to view</a>
                                        </div>
                                    </div>
                                @else
                                    <p>No notice found.</p>
                                @endif
                            </div>
                            


                            {{-- <div class="row">
                                    <div class="row ">
                                        <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                            <label>Title :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($showData->title) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                            <label>Description :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($showData->description) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-3 col-md-3 col-sm-3 padding-col-left">
                                            <label> Upload :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <img src="{{ Config::get('DocumentConstant.NOTICE_VIEW') }}{{ $showData->image }}"
                                                style="width:150px; height:150px; background-color: aliceblue;"
                                                alt=" {{ strip_tags($showData['title']) }} Image" />
                                        </div>
                                    </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- content-wrapper ends -->
    @endsection
