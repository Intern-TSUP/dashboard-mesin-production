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
                        Proses
                    </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Data Master</a>
                        </li>
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
                    <div class="card-title"></div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary" onclick="addRuang()">
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
                            <input type="text" id="search_dt" class="form-control border border-2 w-250px ps-14"
                                placeholder="Search Proses" />
                        </div>
                        <table id="dt_proses" class="table table-bordered table-striped align-middle table-row-dashed fs-6 gy-5 border rounded">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">NO</th>
                                    <th>Nama Proses</th>
                                    <th>Updated_at</th>
                                    <th>Inupby</th>
                                    <th>Action</th>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!--begin::modal proses-->
                        <div class="modal fade" tabindex="-1" id="modalProses">
                            <form id="formProses">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="titleModalProses"></h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-times text-dark"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        
                                        <div class="modal-body" id="bodyModalProses"></div>

                                        <div class="modal-footer">
                                            <div class="me-auto">
                                                <small class="fst-italic"><span class="text-danger">* Tidak boleh kosong</span></small>
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
                        <!--end::modal proses -->

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

        const _URL = "{{ route('v1.proses.getDataTableProses') }}";

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_proses").DataTable({
                order: [[1, 'asc']],
                processing: false,
                serverSide: true,
                ajax: {
                    url: _URL,
                },
                columns: [
                    { data: "DT_RowIndex" },
                    { data: "name" },
                    { data: "updated_at", name: "updated_at", orderable: true, searchable: true },
                    { data: "inupby", name: "inupby", orderable: true, searchable: true },
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

        function addRuang() {
            // isEdit_temp = 0;
            $('#formProses')[0].reset();  // clear the form
            $('#titleModalProses').html('Add New Proses');
            $('#formProses').find('input[name="_method"]').remove();

            $('#formProses').attr('action', "{{ route('v1.proses.store') }}");
            $('#formProses').attr('method', 'POST');

            $('#bodyModalProses').html(`
                <div class="row align-items-center mb-3">
                    <label for="name" class="col-sm-4 col-form-label">Nama Proses <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
            `);

            $('#modalProses').modal('show');
        }

        function editRuang(id) {
            // isEdit_temp = 1;
            id_temp = id;
            $('#formProses')[0].reset();  // clear the form
            $('#titleModalProses').html('Edit Proses');
            $('#formProses').find('input[name="_method"]').remove();

            $('#formProses').attr('action', `{{ url('v1/proses/update') }}/${id}`);
            $('#formProses').attr('method', 'POST');

            $('#formProses').append('<input type="hidden" name="_method" value="PUT">');

            $('#bodyModalProses').html(`
                <input type="hidden" name="id" id="id">
                <div class="row align-items-center mb-3">
                    <label for="name" class="col-sm-4 col-form-label">Nama Proses <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div> 
                </div>
            `);

            // Ambil data dari server untuk diisikan ke form
            let url = `{{ url('v1/proses/edit') }}/${id}`;
            $.get(url, function (response) {
                $('#name').val(response.name);
                $('#modalProses').modal('show');
            });
        }

        function deleteRuang(id) {
            Swal.fire({
                text: "Are you sure you want to delete this Proses?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('v1.proses.destroy') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            $("#dt_proses").DataTable().ajax.reload(null, false);
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
