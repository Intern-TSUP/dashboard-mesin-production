@extends('layout.master')
@section('title')
    Audit Trail
@endsection
@section('page_title')
    Audit Trail
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
        <li class="breadcrumb-item text-muted">Employee</li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">Audit Trail</li>
        <!--end::Item-->
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('action_button')
    <button class="btn btn-primary btn-lg" id="pdfExport-btn">
        <i class="ki-duotone ki-file-sheet fs-2">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        <span id="pdf-label" class="fw-semibold">
            PDF Export
        </span>
    </button>
@endsection
@section('main-content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h6>Audit Trail</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_audit" class="display table table-bordered table-striped table-hover fs-6 gy-4"
                            style="width:100%">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>#</th>
                                    <th>Perusahaan</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!--begin::Modal - Import Ruangan-->
    <div class="modal fade" id="export_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header my-0" id="export_modal_header">
                    <label class="fw-semibold fs-4">Download Log</label>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body px-5">
                    <div class="form-group my-2">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_awal" id="tanggal_awal">
                    </div>
                    <div class="form-group my-2">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir">
                    </div>
                    <div class="form-group my-4">
                        <button class="btn btn-primary w-100" type="button" id="submitFormDownload">
                            Download Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("/assets/plugins/custom/datatables/datatables.bundle.js")}}"></script>
    <script>
        const auditUrl = "{{ route('v1.auditTrail.index') }}";

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            $("#table_audit").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: auditUrl,
                },
                columns: [
                    { data: "DT_RowIndex" },
                    { data: "perusahaan" },
                    { data: "user" },
                    { data: "tindakan" },
                    { data: "catatan" },
                    { data: "tanggal" },
                ],
                lengthChange: false
            });
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        function backTo(route) {
            // Redirect to the dashboard route
            window.location.href = route;
        }

        $('#pdfExport-btn').on('click', function () {
            $('#export_modal').modal('show');
        });

        $('#submitFormDownload').on('click', function() {
            // Disable tombol submit setelah form disubmit
            var $form = $(this);
            $form.find('button[type="submit"]').attr('disabled', true);
            $form.find('button[type="submit"]').text('Loading...');

            $.ajax({
                url: "{{ route('v1.auditTrail.generatePdf') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    tanggal_awal: $('#tanggal_awal').val(),
                    tanggal_akhir: $('#tanggal_akhir').val()
                },
                xhrFields: {
                    responseType: 'blob' // Pastikan response diterima sebagai blob
                },
                beforeSend: function() {
                    $('.page-loading').fadeIn();
                    $form.find('button[type="submit"]').attr('disabled', true);
                    $form.find('button[type="submit"]').text('Loading...');
                },
                success: function(response, status, xhr) {
                    let contentType = xhr.getResponseHeader("Content-Type");

                    // Jika response berupa JSON (error), tampilkan pesan
                    if (contentType.includes("application/json")) {
                        response.text().then(text => {
                            let jsonResponse = JSON.parse(text);
                            Swal.fire({
                                title: "Mohon Maaf :(",
                                text: jsonResponse.message,
                                icon: "error",
                                allowOutsideClick: false, // Mencegah klik di luar menutup alert
                                allowEscapeKey: false, // Mencegah tombol Escape menutup alert
                                showCloseButton: true, // Menampilkan tombol close (X)
                            });
                        });
                        return;
                    }

                    // Jika response adalah Blob (PDF), lanjutkan proses download
                    let filename = "AuditTrail.pdf";
                    let disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.includes('filename=')) {
                        filename = disposition.split('filename=')[1].replace(/"/g, '');
                    }

                    let blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function(xhr) {
                    try {
                        let jsonResponse = JSON.parse(xhr.responseText);
                        Swal.fire({
                            title: "Mohon Maaf :(",
                            text: jsonResponse.message,
                            icon: "error",
                            allowOutsideClick: false, // Mencegah klik di luar menutup alert
                            allowEscapeKey: false, // Mencegah tombol Escape menutup alert
                            showCloseButton: true, // Menampilkan tombol close (X)
                        });
                    } catch (e) {
                        Swal.fire({
                            title: "Mohon Maaf :(",
                            text: "Terjadi kesalahan saat mengunduh PDF.",
                            icon: "error",
                            allowOutsideClick: false, // Mencegah klik di luar menutup alert
                            allowEscapeKey: false, // Mencegah tombol Escape menutup alert
                            showCloseButton: true, // Menampilkan tombol close (X)
                        });
                    }
                },
                complete: function() {
                    $('.page-loading').fadeOut();
                    $form.find('button[type="submit"]').attr('disabled', false);
                }
            });
        });
    </script>
@endsection