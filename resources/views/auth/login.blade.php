@extends('layout.auth')
@section('content')
    <!--begin::Wrapper-->
    <div class="d-flex flex-center flex-column flex-column-fluid pb-10 pb-lg-15">
        <!--begin::Form-->
        <form class="form w-100" action="{{ route('loginHris') }}" method="POST" enctype="multipart/form-data" id="loginForm">
            @csrf
            <!--begin::Heading-->
            <span class="d-flex flex-center">
                <img alt="Logo" src="{{asset('assets/logo/Logo-Kalbe-&-BSB_Original.png')}}"
                    class="h-100px app-sidebar-logo-default theme-light-show" />
                <img alt="Logo" src="{{asset('assets/logo/Logo-Kalbe-&-BSB_Original.png')}}"
                    class="h-100px app-sidebar-logo-default theme-dark-show"
                    style="filter: contrast(0);" />
            </span>
            <!--begin::Heading-->
            <!--begin::Heading-->
            <div class="text-center mb-8">
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder">Sign In</h1>
                <!--end::Title-->
                <!--begin::Subtitle-->
                <div class="text-gray-500 fw-semibold fs-6">Portal {{ env('APP_NAME') }}</div>
                <!--end::Subtitle=-->
            </div>
            <!--begin::Heading-->
            @if ($errors->any())
            <!--begin::Alert-->
            <div
                class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                <!--begin::Icon-->
                {{-- <i class="ki-solid ki-security-check fs-2hx text-danger me-4 mb-5 mb-sm-0"></i> --}}
                <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-column text-danger pe-0 pe-sm-10">

                    <!--begin::Content-->
                    <span>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Close-->
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-1 text-danger">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
                <!--end::Close-->
            </div>
            <!--end::Alert-->
            @endif
            <!--begin::Input group=-->
            <div class="fv-row my-2">
                <!--begin::Username-->
                <label for="email" class="required form-label text-gray-600">Email</label>
                <input type="text" id="email" placeholder="jhon.doe@kalbe.co.id" name="email"
                class="form-control" />
                <!--end::Username-->
            </div>
            <!--end::Input group=-->
            <div class="fv-row mt-3 mb-2">
                <!--begin::Password-->
                <label for="password" class="required form-label  text-gray-600">Password</label>
                <input type="password" id="password" placeholder="********" name="password"
                    class="form-control" />
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            <!--begin::Submit button-->
            <div class="d-grid mt-8">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <span class="indicator-label">Log in Accout</span>
                </button>
            </div>
            <!--end::Submit button-->
            <div class="d-flex justify-content-between align-items-end mt-5">
                <h6 class="f-w-500 mb-0">Lupa Password ?</h6>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="link-primary">
                    Cek Panduan
                </a>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
@endsection