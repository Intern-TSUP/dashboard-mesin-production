@extends('layout.master')
@section('title')
    Dashboard
@endsection
@section('styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding-top: 20px;
        }
        .machine-card {
            width: 300px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #ffffff; /* Ubah ke putih agar sesuai template */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); /* Bayangan lebih halus */
            transition: transform 0.2s;
        }
        .machine-card:hover {
            transform: translateY(-5px); /* Efek hover */
        }
        .machine-card .icon {
            margin-bottom: 15px;
        }
        .machine-card .icon i {
            font-size: 50px !important; /* Gunakan !important untuk menimpa style lain */
            color: #7239ea; /* Contoh warna primer template */
        }
        .machine-card .card-title {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .machine-card .card-text {
            font-size: 0.9rem;
            color: #5e6278; /* Warna teks muted dari template */
            margin-bottom: 5px;
        }
    </style>
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
                        Dashboard
                    </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
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
                    <div class="card-title">Data Mesin</div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center py-5">

                            <div class="d-flex align-items-center">
                                <label for="filterLines" class="form-label me-2 mb-0">Line:</label>
                                <select id="filterLines" class="form-select form-select-sm w-150px">
                                    <option value="">Semua Line</option>
                                    @foreach ($filter_lines as $line)
                                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex align-items-center">
                                <label for="filterProses" class="form-label mx-2 mb-0">Proses:</label>
                                <select id="filterProses" class="form-select form-select-sm w-150px">
                                    <option value="">Semua Proses</option>
                                    @foreach ($filter_proses as $proses)
                                        <option value="{{ $proses->id }}">{{ $proses->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex align-items-center position-relative ms-auto">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                                <input type="text" id="search_dt" class="form-control form-control-sm w-250px ps-12" placeholder="Search Mesin" />
                            </div>

                        </div>
                        
                        <table id="dt_mesin" class="table table-bordered table-striped align-middle table-row-dashed fs-6 gy-5 border rounded">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">NO</th>
                                    <th>Line</th>
                                    <th>Proses</th>
                                    <th>Kode Mesin</th>
                                    <th>Nama Mesin</th>
                                    <th>Jumlah Operator</th>
                                    <th>Kapasitas</th>
                                    <th>Speed</th>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!--begin::modal mesin-->
                        <div class="modal fade" tabindex="-1" id="modalMesin">
                            <form id="formMesin">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="titleModalMesin"></h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-times text-dark"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        
                                        <div class="modal-body" id="bodyModalMesin"></div>

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
                        <!--end::modal mesin -->

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

        const _URL = "{{ route('v1.dashboard.getDataTableMesin') }}";

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_mesin").DataTable({
                order: [[1, 'asc']],
                processing: false,
                serverSide: true,
                ajax: {
                    url: _URL,
                    data: function (d) {
                        d.filter_lines = $('#filterLines').val();
                        d.filter_proses = $('#filterProses').val();
                    },
                },
                columns: [
                    { data: "DT_RowIndex", orderable: false, searchable: false, width: "5%" },
                    { data: "line_name", name: "line.name", orderable: false, searchable: true, width: "5%" },
                    { data: "proses_name", name: "proses.name", orderable: false, searchable: true, width: "5%" },
                    { data: "kodeMesin", name: "kodeMesin", orderable: true, searchable: true, width: "10%" },
                    { data: "name", name: "name", orderable: true, searchable: true },
                    { data: "jumlahOperator", name: "jumlahOperator", width: "10%" },
                    { data: "kapasitas", name: "kapasitas", orderable: true, searchable: true, width: "20%" },
                    { data: "speed" },
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

            $('#filterLines, #filterProses').on('change', function() {
                DT.ajax.reload(); // Muat ulang data tabel
            });

            $('#search_dt').on('keyup', function () {
                DT.search(this.value).draw();
            });
        });
    </script>
@endsection