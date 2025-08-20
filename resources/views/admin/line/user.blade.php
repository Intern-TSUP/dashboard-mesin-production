@extends('layout.master')
@section('title')
    Dashboard
@endsection
@section('main-content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Line Manage
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
                        <li class="breadcrumb-item text-muted">Admin</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Line Manage</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ $line->name }}</div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary" onclick="addRuang('{{ $line->id }}')">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Add New
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex align-items-center position-relative my-5">
                            <span class="svg-icon position-absolute ms-4">
                                <i class="ki-duotone ki-magnifier fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <input type="text" id="search_dt" class="form-control border-2 w-250px ps-14"
                                placeholder="Search User" />
                        </div>
                        <table id="dt_user" class="table table-bordered table-striped align-middle table-row-dashed fs-6 gy-5 border rounded">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">NO</th>
                                    <th>Line</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Job Level</th>
                                    <th>Action</th>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!--begin::modal user-->
                        <div class="modal fade" tabindex="-1" id="modalUser">
                            <form id="formUser">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="titleModalUser"></h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-times text-dark"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        
                                        <div class="modal-body" id="bodyModalUser"></div>

                                        <div class="modal-footer">
                                            <div class="me-auto">
                                                <small class="fst-italic"><span class="text-danger">*Tidak boleh kosong</span></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light btn-action" type="button" data-bs-dismiss="modal" id="btnBatal" onclick="" style="margin-right: 10px;">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-sm btn-primary btn-action" id="btnSimpan" onclick="">
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--end::modal user -->

                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->

    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script src="{{asset("/assets/plugins/custom/datatables/datatables.bundle.js")}}"></script>
    <script>

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        const _URL = "{{ route('admin.permissionLine.getDataTableUser') }}";


        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_user").DataTable({
                order: [[2, 'asc']],
                processing: false,
                serverSide: true,
                ajax: {
                    url: _URL,
                    data: function (d) {
                        d.line_id = "{{ $line->id }}";
                    }
                },
                columns: [
                    { data: "DT_RowIndex" },
                    { data: "line_name" },
                    { data: "fullname" },
                    { data: "email" },
                    { data: "jobLvl" },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1; // Calculate the row index
                        },
                    },
                ],
            });

            $('#search_dt').on('keyup', function () {
                DT.search(this.value).draw();
            });
        });

        // let isEdit_temp = 0;
        // let id_temp = "";

        function addRuang(id) {
            $('#formUser')[0].reset();
            $('#titleModalUser').html('Add New User');
            $('#formUser').find('input[name="_method"]').remove();
            $('#formUser').attr('action', `{{ url('admin/permissionLine/storeUser') }}/${id}`);
            $('#formUser').attr('method', 'POST');

            $('#bodyModalUser').html(`
                <div class="form-group mb-3">
                    <label for="user_id" class="form-label">Name<span class="text-danger">*</span></label>
                    <select class="form-select" id="user_id_select" name="user_id" required></select>
                </div>
            `);
            
            $('#user_id_select').select2({
                placeholder: "Search and Select User...",
                minimumInputLength: 2, 
                dropdownParent: $('#modalUser'),
                ajax: {
                    url: "{{ route('admin.permissionLine.getAvailableUsers') }}",
                    dataType: 'json',
                    delay: 250, 
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('#modalUser').modal('show');
        }

        function deleteRuang(id) {
            Swal.fire({
                text: "Are you sure you want to delete this User?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('admin.permissionLine.destroyUser') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            $("#dt_user").DataTable().ajax.reload(null, false);
                            Swal.fire("Deleted!", response.message, "success");
                        },
                        error: function (xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                    Swal.fire("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>

@endsection
