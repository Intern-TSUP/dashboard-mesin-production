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
                        Mesin
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
                    <div class="card-title">Data Mesin</div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary" onclick="addRuang()">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Add New
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center py-5">

                            <div class="d-flex align-items-center">
                                <label for="filterProses" class="form-label mx-2 mb-0">Proses:</label>
                                <select id="filterProses" class="form-select form-select-sm w-150px">
                                    <option value="">Semua Proses</option>
                                    @foreach ($all_proses as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                    <th>Action</th>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!--begin::modal mesin-->
                        <div class="modal fade" tabindex="-1" id="modalMesin">
                            <form id="formMesin">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
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

                        <!--begin::modal detail mesin-->
                        <div class="modal fade" tabindex="-1" id="modalDetailMesin">
                            <form id="formMesin">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="titleModalDetailMesin"></h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-times text-dark"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        
                                        <div class="modal-body" id="bodyModalDetailMesin"></div>
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

        const _URL = "{{ route('v1.mesin.getDataTableMesin') }}";

        // let filter_line = $('#filterLine').val();
        // let filter_proses = $('#filterProses').val();

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
                        d.filter_line = $('#filterLine').val();
                        d.filter_proses = $('#filterProses').val();
                    },
                },
                columns: [
                    { data: "DT_RowIndex", orderable: false, searchable: false},
                    { data: "line_name", name: "line.name", orderable: false, searchable: true},
                    { data: "proses_name", name: "proses.name", orderable: false, searchable: true},
                    { data: "kodeMesin", name: "kodeMesin", orderable: true, searchable: true, width: "10%" },
                    { data: "name", name: "name", orderable: true, searchable: true },
                    { data: "jumlahOperator", name: "jumlahOperator"},
                    { data: "kapasitas", name: "kapasitas", orderable: true, searchable: true},
                    { data: "speed", name: "speed", orderable: true, searchable: true},
                    { data: "action", orderable: false, searchable: false, width: "15%"},
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

            $('#filterLine, #filterProses').on('change', function() {
                DT.ajax.reload(); // Muat ulang data tabel
            });

            $('#search_dt').on('keyup', function () {
                DT.search(this.value).draw();
            });

        });

        // let isEdit_temp = 0;
        // let id_temp = "";

        function addRuang() {
    // isEdit_temp = 0;
    $('#formMesin')[0].reset();  // clear the form
    $('#titleModalMesin').html('Add New Mesin');
    $('#formMesin').find('input[name="_method"]').remove();

    $('#formMesin').attr('action', "{{ route('v1.mesin.store') }}");
    $('#formMesin').attr('method', 'POST');

    $.get("{{ route('v1.mesin.create') }}", function(response) {
        // --- Proses options ---
        let prosesOptions = '';
        response.all_proses.forEach(function (proses) {
            prosesOptions += `<option value="${proses.id}">${proses.name}</option>`;
        });

        // --- Lines options & select ---
        let linesOptions = '';
        response.all_line.forEach(function (line) {
            linesOptions += `<option value="${line.id}">${line.name}</option>`;
        });

        let linesSelect = `
            <select name="line_id"
                aria-label="Select a line"
                data-control="select2"
                data-allow-clear="true"
                data-placeholder="Select an item..."
                class="form-select fw-bold"
                required>
                <option value="">Select Line</option>
                ${linesOptions}
            </select>
        `;

        // --- Tentukan row Line berdasarkan userLine ---
        let lineRowHtml = '';

        if (response.userLine) {
            // User sudah punya line → pakai readonly + hidden (seperti sebelumnya)
            lineRowHtml = `
                <div class="row align-items-center mb-3">
                    <label for="line_id" class="col-sm-4 col-form-label">
                        Line<span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text"
                            class="form-control"
                            value="${response.userLine.name}"
                            readonly
                            style="cursor: not-allowed">
                        <input type="hidden"
                            id="line_id"
                            name="line_id"
                            value="${response.userLine.id}">
                    </div>
                </div>
            `;
        } else {
            // User tidak punya line → pakai select pilihan line
            lineRowHtml = `
                <div class="row align-items-center mb-3">
                    <label for="line_id" class="col-sm-4 col-form-label">
                        Line<span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        ${linesSelect}
                    </div>
                </div>
            `;
        }

        // --- Render body modal ---
        $('#bodyModalMesin').html(`
            ${lineRowHtml}
            <div class="row align-items-center mb-3">
                <label for="kodeMesin" class="col-sm-4 col-form-label">Kode Mesin<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="kodeMesin" name="kodeMesin" required placeholder="Masukkan kode mesin">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="name" class="col-sm-4 col-form-label">Nama Mesin<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama mesin">
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="kapasitas" class="col-sm-4 col-form-label">Kapasitas<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" class="form-control" id="kapasitas" name="kapasitas" placeholder="Kapasitas" required>
                        <select class="form-control form-select w-25" id="satuanKapasitas" name="satuanKapasitas" required>
                            <option value="" disabled selected>Satuan</option>
                            <option value="Kilogram">Kilogram</option>
                            <option value="Liter">Liter</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="speed" class="col-sm-4 col-form-label">Speed<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" class="form-control" id="speed" name="speed" placeholder="Speed" required>
                        <select class="form-control form-select w-25" id="satuanSpeed" name="satuanSpeed" required>
                            <option value="" disabled selected>Satuan</option>
                            <option value="Batch/menit">Batch/menit</option>
                            <option value="Blister/menit">Blister/menit</option>
                            <option value="Botol/menit">Botol/menit</option>
                            <option value="Box/menit">Box/menit</option>
                            <option value="Cap/menit">Cap/menit</option>
                            <option value="Lot/menit">Lot/menit</option>
                            <option value="Strip/menit">Strip/menit</option>
                            <option value="Tab/menit">Tab/menit</option>
                            <option value="Tub/menit">Tub/menit</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="jumlahOperator" class="col-sm-4 col-form-label">Jumlah Operator<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="jumlahOperator" name="jumlahOperator" min="1" value="1" required>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="proses_ids" class="col-sm-4 col-form-label">Proses<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control form-select" id="proses_ids" name="proses_ids[]" multiple="multiple" data-placeholder="-- Select Proses --" required>
                        ${prosesOptions}
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                <div class="col-sm-8">
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan mesin"></textarea>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label class="col-sm-4 col-form-label">Link Dokumen</label>
                <div class="col-sm-8">

                    <div id="linkDokumenWrapper">
                        <div class="row align-items-center mb-2 link-row">
                            <label class="col-sm-4 col-form-label d-none d-sm-block"></label>
                            <div class="col-sm-12 col-sm-8">
                                <div class="input-group">
                                    <input type="url"
                                        class="form-control"
                                        name="link_kualifikasi[]"
                                        placeholder="Masukkan link dokumen">
                                    <button type="button"
                                            class="btn btn-sm btn-danger ms-2 btn-delete-link d-none">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary mt-2" id="btnAddLink">
                        Tambah Link
                    </button>
                    <div class="form-text">Maksimal 5 link dokumen, minimal 1.</div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <label for="image" class="col-sm-4 col-form-label">Image</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>
        `);

        // Select2 untuk proses
        $('#proses_ids').select2({
            dropdownParent: $('#modalMesin')
        });

        // Kalau pakai select line, aktifkan juga select2 untuk line
        $('[name="line_id"][data-control="select2"]').select2({
            dropdownParent: $('#modalMesin')
        });

        // Inisialisasi tombol delete link
        if (typeof updateDeleteButtons === 'function') {
            updateDeleteButtons();
        }

        $('#modalMesin').modal('show');
    });
}

        const maxLinks = 5;

        function updateDeleteButtons() {
            const rows = $('#linkDokumenWrapper .link-row');

            if (rows.length === 1) {
                rows.find('.btn-delete-link').addClass('d-none');
            } else {
                rows.find('.btn-delete-link').removeClass('d-none');
            }
        }

        // Tambah link (DELEGATED) -> bisa dipasang sekali saja
        $(document).on('click', '#btnAddLink', function () {
            const wrapper = $('#linkDokumenWrapper');
            const count = wrapper.find('.link-row').length;

            if (count >= maxLinks) {
                return;
            }

            const newRow = `
                <div class="row align-items-center mb-2 link-row">
                    <label class="col-sm-4 col-form-label d-none d-sm-block"></label>
                    <div class="col-sm-12 col-sm-8">
                        <div class="input-group">
                            <input type="url"
                                class="form-control"
                                name="link_kualifikasi[]"
                                placeholder="Masukkan link dokumen">
                            <button type="button"
                                    class="btn btn-sm btn-danger ms-2 btn-delete-link">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;

            wrapper.append(newRow);
            updateDeleteButtons();
        });

        // Hapus link (DELEGATED – ini sudah benar)
        $(document).on('click', '.btn-delete-link', function () {
            const wrapper = $('#linkDokumenWrapper');
            const rows = wrapper.find('.link-row');

            if (rows.length <= 1) {
                // minimal harus tetap ada 1 input
                return;
            }

            $(this).closest('.link-row').remove();
            updateDeleteButtons();
        });

        function editRuang(id) {
            $('#formMesin')[0].reset();
            $('#titleModalMesin').html('Edit Mesin');
            $('#formMesin').find('input[name="_method"]').remove();
            $('#formMesin').attr('action', `{{ url('v1/mesin/update') }}/${id}`); 
            $('#formMesin').attr('method', 'POST');
            $('#formMesin').append('<input type="hidden" name="_method" value="PUT">');

            $('#bodyModalMesin').html('<p class="text-center">Loading data...</p>');
            $('#modalMesin').modal('show');

            let url = `{{ url('v1/mesin/edit') }}/${id}`; 
            $.get(url, function (response) {
                let mesin = response.mesin;
                let allProses = response.all_proses;
                
                let currentImageHtml = '';
                if (mesin.image) {
                    currentImageHtml = `
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label">Current Image</label>
                            <div class="col-sm-8">
                                <img src="{{ asset('assets/mesin_images') }}/${mesin.image}" alt="${mesin.name}" class="img-fluid mb-2" style="max-height: 200px; max-width: 100%;">
                            </div>
                        </div>
                    `;
                } else {
                    currentImageHtml = `
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label">Current Image</label>
                            <div class="col-sm-8">
                                <p class="text-muted">No image available</p>
                            </div>
                        </div>
                    `;
                }

                $('#bodyModalMesin').html(`
                    <div class="row align-items-center mb-3">
                        <label for="line_id" class="col-sm-4 col-form-label">Line<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="line_name" name="line_name" readonly style="cursor: not-allowed">
                            <input type="hidden" id="line_id" name="line_id">
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="kodeMesin" class="col-sm-4 col-form-label">Kode Mesin<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="kodeMesin" name="kodeMesin" required placeholder="Masukkan kode mesin">
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="name" class="col-sm-4 col-form-label">Nama Mesin<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama mesin">
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="kapasitas" class="col-sm-4 col-form-label">Kapasitas<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kapasitas" name="kapasitas" placeholder="Kapasitas" required>
                                <select class="form-control form-select w-25" id="satuanKapasitas" name="satuanKapasitas" required>
                                    <option value="" disabled selected>Satuan</option>
                                    <option value="Kilogram">Kilogram</option>
                                    <option value="Liter">Liter</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="speed" class="col-sm-4 col-form-label">Speed<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="speed" name="speed" placeholder="Speed" required>
                                <select class="form-control form-select w-25" id="satuanSpeed" name="satuanSpeed" required>
                                    <option value="" disabled selected>Satuan</option>
                                    <option value="Batch/menit">Batch/menit</option>
                                    <option value="Blister/menit">Blister/menit</option>
                                    <option value="Botol/menit">Botol/menit</option>
                                    <option value="Box/menit">Box/menit</option>
                                    <option value="Cap/menit">Cap/menit</option>
                                    <option value="Lot/menit">Lot/menit</option>
                                    <option value="Strip/menit">Strip/menit</option>
                                    <option value="Tab/menit">Tab/menit</option>
                                    <option value="Tub/menit">Tub/menit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="jumlahOperator" class="col-sm-4 col-form-label">Jumlah Operator<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="jumlahOperator" name="jumlahOperator" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="proses_ids" class="col-sm-4 col-form-label">Proses<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control form-select" id="proses_ids" name="proses_ids[]" multiple="multiple" required>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan mesin">${mesin.keterangan || ''}</textarea>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <label class="col-sm-4 col-form-label">Link Dokumen</label>
                        <div class="col-sm-8">

                            <div id="linkDokumenWrapper">
                                
                            </div>

                            <button type="button" class="btn btn-sm btn-primary mt-2" id="btnAddLink">
                                Tambah Link
                            </button>
                            <div class="form-text">Maksimal 5 link dokumen, minimal 1.</div>
                        </div>
                    </div>
                    ${currentImageHtml}
                    <div class="row align-items-center mb-3">
                        <label for="image" class="col-sm-4 col-form-label">Image</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                `);

                $('#line_name').val(mesin.line.name);
                $('#line_id').val(mesin.line.id);
                $('#kodeMesin').val(mesin.kodeMesin);
                $('#name').val(mesin.name);
                $('#kapasitas').val(mesin.kapasitas);
                $('#satuanKapasitas').val(mesin.satuanKapasitas);
                $('#speed').val(mesin.speed);
                $('#satuanSpeed').val(mesin.satuanSpeed);
                $('#jumlahOperator').val(mesin.jumlahOperator);
                $('#keterangan').val(mesin.keterangan);
                
                const links = [
                    mesin.link_kualifikasi_1,
                    mesin.link_kualifikasi_2,
                    mesin.link_kualifikasi_3,
                    mesin.link_kualifikasi_4,
                    mesin.link_kualifikasi_5,
                ].filter(function (val) {
                    return val !== null && val !== undefined && val !== '';
                });

                const wrapper = $('#linkDokumenWrapper');
                wrapper.empty();

                // minimal selalu ada 1 row
                if (links.length === 0) {
                    links.push('');
                }

                links.forEach(function (value, index) {
                    const rowHtml = `
                        <div class="row align-items-center mb-2 link-row">
                            <label class="col-sm-4 col-form-label d-none d-sm-block"></label>
                            <div class="col-sm-12 col-sm-8">
                                <div class="input-group">
                                    <input type="url"
                                        class="form-control"
                                        name="link_kualifikasi[]"
                                        placeholder="Masukkan link dokumen mesin"
                                        value="${value ?? ''}">
                                    <button type="button"
                                            class="btn btn-sm btn-danger ms-2 btn-delete-link">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    wrapper.append(rowHtml);
                });

                // atur tombol hapus (fungsi global yang sudah kamu buat)
                if (typeof updateDeleteButtons === 'function') {
                    updateDeleteButtons();
                }

                let prosesSelect = $('#proses_ids');
                prosesSelect.empty();
                let selectedProsesIds = mesin.proses.map(p => p.id);
                allProses.forEach(function(proses) {
                    let isSelected = selectedProsesIds.includes(proses.id);
                    prosesSelect.append(`<option value="${proses.id}" ${isSelected ? 'selected' : ''}>${proses.name}</option>`);
                });

                $('#proses_ids').select2({
                    dropdownParent: $('#modalMesin')
                });

            }).fail(function() {
                $('#bodyModalMesin').html('<p class="text-center text-danger">Gagal memuat data.</p>');
            });
        }

        function deleteRuang(id) {
            Swal.fire({
                text: "Are you sure you want to delete this Machine?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('v1.mesin.destroy') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            $("#dt_mesin").DataTable().ajax.reload(null, false);
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

        function showDetail(id) {
            $('#titleModalDetailMesin').html('Machine Details');
            $('#bodyModalDetailMesin').html('<p class="text-center">Loading data...</p>');
            $('#modalDetailMesin').modal('show');

            let url = `{{ url('v1/mesin/edit') }}/${id}`;

            $.get(url, function (response) {
                let mesin = response.mesin;

                let prosesList = '<p>Tidak ada proses terkait.</p>';
                if (mesin.proses && mesin.proses.length > 0) {
                    prosesList = '<ul class="list-group list-group-flush">';
                    mesin.proses.forEach(function (p) {
                        prosesList += `<li class="list-group-item">${p.name}</li>`;
                    });
                    prosesList += '</ul>';
                }

                let imageHtml = '<p class="text-muted">No image available</p>';
                if (mesin.image) {
                    let imageUrl = `{{ asset('assets/mesin_images') }}/${mesin.image}`;
                    imageHtml = `
                        <img src="${imageUrl}" 
                            alt="${mesin.name}" 
                            class="img-fluid rounded" 
                            style="max-height: 200px; display:block; margin:auto;">
                    `;
                }

                let updatedAt = mesin.updated_at
                    ? new Date(mesin.updated_at).toLocaleDateString('id-ID')
                    : '-';

                let linkDokumenHtml = '';
                let linkFields = [
                    mesin.link_kualifikasi_1,
                    mesin.link_kualifikasi_2,
                    mesin.link_kualifikasi_3,
                    mesin.link_kualifikasi_4,
                    mesin.link_kualifikasi_5
                ];

                let anyLinkExists = linkFields.some(v => v && v.trim() !== '');

                if (!anyLinkExists) {
                    linkDokumenHtml = `<tr><th>Link Dokumen</th><td>-</td></tr>`;
                } else {
                    linkFields.forEach((value, index) => {
                        if (value && value.trim() !== '') {
                            linkDokumenHtml += `
                                <tr>
                                    <th>Link Dokumen ${index + 1}</th>
                                    <td>
                                        <a href="${value}" target="_blank" class="text-primary fw-bold">
                                            Lihat Form ${index + 1}
                                        </a>
                                    </td>
                                </tr>
                            `;
                        }
                    });
                }

                $('#bodyModalDetailMesin').html(`
                    <div class="mb-5 text-center">
                        ${imageHtml}
                    </div>

                    <table class="table table-bordered table-striped border border-4">
                        <tbody>
                            <tr><th class="w-250px">Line</th><td>${mesin.line ? mesin.line.name : '-'}</td></tr>

                            <tr><th>Proses</th><td>${prosesList}</td></tr>

                            <tr><th>Kode Mesin</th><td>${mesin.kodeMesin}</td></tr>
                            <tr><th>Nama Mesin</th><td>${mesin.name}</td></tr>
                            <tr><th>Jumlah Operator</th><td>${mesin.jumlahOperator}</td></tr>
                            <tr><th>Kapasitas</th><td>${mesin.kapasitas} ${mesin.satuanKapasitas}</td></tr>
                            <tr><th>Speed</th><td>${mesin.speed} ${mesin.satuanSpeed}</td></tr>
                            <tr><th>Keterangan</th><td>${mesin.keterangan || '-'}</td></tr>

                            ${linkDokumenHtml}

                            <tr><th>Updated At</th><td>${updatedAt}</td></tr>
                            <tr><th>Input By</th><td>${mesin.inupby || '-'}</td></tr>
                        </tbody>
                    </table>
                `);
            })
            .fail(function () {
                $('#bodyModalDetailMesin').html('<p class="text-center text-danger">Gagal memuat data.</p>');
            });
        }
    </script>
@endsection
