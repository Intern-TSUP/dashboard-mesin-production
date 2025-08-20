@extends('layout.master')
@section('title')
    Contact Us
@endsection
@section('main-content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Contact Us
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="#" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Employee</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Contact Us</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
            <div class="row">
                <div class="col">
                    <div class="card card-flush border-0 mb-5">
                        <div class="card-body py-15 py-lg-20">
                            <div class="d-flex justify-content-center">
                                <div class="row">
                                    <!--begin::Illustration-->
                                    <div class="col mb-11">
                                        <img src="{{asset('assets/img/contact_us.png')}}" class="mw-100 mh-400px theme-light-show" alt="" />
                                    </div>
                                    <!--end::Illustration-->
                                </div>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-bolder fs-2qx text-gray-900 mb-4">Contact Center</h1>
                                <!--begin::Text-->
                                <div class="fw-semibold fs-7 text-gray-700">Call For Support</div>
                                <p class="mt-2 mb-4 text-sm fw-bold">
                                    EXT: 626
                                </p>
                                <!--end::Text-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection