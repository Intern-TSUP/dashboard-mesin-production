@extends('layout.master')
@section('title')
    Page Settings
@endsection
@section('page_title')
    Page Setting
@endsection
@section('breadcrumb')
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
        <li class="breadcrumb-item text-muted">admin</li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">settings</li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('main-content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-12 mb-0 py-0">
                                    <h5 class="my-0">Manage Your Setting Apps</h5>
                                </div>
                            </div>
                            </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <div class="col">
                                <label for="maintenance_mode" class="form-label fw-bold">Maintenance Mode</label>
                                <div class="input-group mb-5">
                                    <select name="maintenance_mode" id="maintenance_mode" class="form-select">
                                        <option value="false" {{ isset($maintenanceMode->maintenance) && $maintenanceMode->maintenance == false ? 'selected' : '' }}>Disabled</option>
                                        <option value="true" {{ isset($maintenanceMode->maintenance) && $maintenanceMode->maintenance == true ? 'selected' : '' }}>Enabled</option>
                                    </select>
                                </div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="mb-3 row" id="maintenance_reason_row" style="{{ isset($maintenanceMode->maintenance) && $maintenanceMode->maintenance == true ? '' : 'display:none;' }}">
                            <div class="col">
                                <label for="maintenance_reason" class="form-label fw-bold">Reason for Maintenance</label>
                                <div class="input-group mb-5">
                                    <textarea name="reason" id="maintenance_reason" class="form-control">{{ $maintenanceMode->reason ?? '' }}</textarea>
                                    {{-- <input type="text" class="form-control" value="" id="maintenance_reason" name="maintenance_reason" placeholder="Enter reason for maintenance"/> --}}
                                </div>
                            </div>
                        </div>

                        <div class="separator my-5"></div>

                        <div class="mb-3 row">
                            <div class="col-4">
                                <label for="idle_time" class="form-label fw-bold">Idle Time</label>
                                <div class="input-group mb-5">
                                    <input type="number" min="0" max="60" class="form-control" placeholder="0" aria-label="idle time"
                                    aria-describedby="basic-addon2" value="{{ $maintenanceMode->idle_time ?? '0' }}" id="idle_time" name="idle_time" oninput="if(this.value > 60) this.value = 60;"/>
                                    <span class="input-group-text" id="basic-addon2">menit</span>
                                </div>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div class="mb-3 row">
                            <div class="col">
                                <label for="url_hris" class="form-label fw-bold">URL Hris</label>
                                <div class="input-group mb-5">
                                    <input type="text" class="form-control" value="{{ $maintenanceMode->url_hris ?? '' }}" id="url_hris" name="url_hris"/>
                                </div>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
                <div class="card card-flush">
                    <div class="card-footer ">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const maintenanceSelect = document.getElementById('maintenance_mode');
            const reasonRow = document.getElementById('maintenance_reason_row');
            maintenanceSelect.addEventListener('change', function () {
                if (this.value === 'true') {
                    reasonRow.style.display = '';
                } else {
                    reasonRow.style.display = 'none';
                }
            });
        });
    </script>
@endsection