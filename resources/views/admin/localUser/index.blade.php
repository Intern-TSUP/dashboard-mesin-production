@extends('layout.master')
@section('title')
    Local User
@endsection
@section('page_title')
    Local User
@endsection
@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-muted">
            <a href="#" class="text-muted text-hover-primary">Home</a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">admin</li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">local user</li>
    </ul>
@endsection
@section('action_button')
    <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        Tambah Local User
    </button>
@endsection
@section('main-content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-content">
                                <div class="d-flex align-items-center position-relative my-5">
                                    <span class="svg-icon position-absolute ms-4">
                                        <i class="ki-duotone ki-magnifier fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <input type="text" id="search_dt" class="form-control border border-2 w-250px ps-14" placeholder="Search User" />
                                </div>
                                <table id="datatables" class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th style="width: 50px;">No</th>
                                            <th class="d-none">ID</th>
                                            <th>Nama Lengkap</th>
                                            <th>NIK</th>
                                            <th>Email</th>
                                            <th class="d-none">Job Level</th>
                                            <th>Job Title</th>
                                            <th class="d-none">Group Code</th>
                                            <th class="d-none">Group Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th style="width: 50px;">No</th>
                                            <th class="d-none">ID</th>
                                            <th>Nama Lengkap</th>
                                            <th>NIK</th>
                                            <th>Email</th>
                                            <th class="d-none">Job Level</th>
                                            <th>Job Title</th>
                                            <th class="d-none">Group Code</th>
                                            <th class="d-none">Group Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <form id="addForm" class="modal-content" action="{{ route('admin.localUser.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Local User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col mb-3" id="employee_search_container">
                            <label class="form-label">Cari Karyawan</label>
                            <select id="employee" name="employee" class="form-select mb-3 mb-lg-0"></select>
                        </div>
                        <div id="extra_fields" style="display: none;">
                            <div class="mb-3 d-none">
                                <label for="compCode" class="form-label">Company Code</label>
                                <input type="text" class="form-control" name="compCode" id="compCode" required>
                            </div>
                            <div class="mb-3">
                                <label for="employeId" class="form-label">NIK</label>
                                <input type="text" class="form-control" name="employeId" id="employeId" required>
                            </div>
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Fullname</label>
                                <input type="text" class="form-control" name="fullname" id="fullname" required>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="empTypeGroup" class="form-label">Employee Type Group</label>
                                <input type="text" class="form-control" name="empTypeGroup" id="empTypeGroup" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="jobLvl" class="form-label">Job Level</label>
                                <input type="text" class="form-control" name="jobLvl" id="jobLvl" required>
                            </div>
                            <div class="mb-3">
                                <label for="jobTitle" class="form-label">Job Title</label>
                                <input type="text" class="form-control" name="jobTitle" id="jobTitle" required>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="deptKode" class="form-label">Departemen Code</label>
                                <input type="text" class="form-control" name="deptKode" id="deptKode" required>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="groupKode" class="form-label">Sub Departemen Code</label>
                                <input type="text" class="form-control" name="groupKode" id="groupKode" required>
                            </div>
                            <div class="mb-3">
                                <label for="groupName" class="form-label">Sub Departemen</label>
                                <input type="text" class="form-control" name="groupName" id="groupName" required>
                            </div>
                            <h6 class="text-muted mb-3">Kata sandi default adalah 'kalbefarma', user bisa edit password nya sendiri setelah berhasil masuk</h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <form id="editForm" class="modal-content" action="{{ route('admin.localUser.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Local User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 d-none">
                            <label for="edit_id" class="form-label">Edit ID</label>
                            <input type="text" class="form-control" name="edit_id" id="edit_id" required>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="edit_compCode" class="form-label">Company Code</label>
                            <input type="text" class="form-control" name="edit_compCode" id="edit_compCode" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_employeId" class="form-label">NIK</label>
                            <input type="text" class="form-control" name="edit_employeId" id="edit_employeId" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_fullname" class="form-label">Fullname</label>
                            <input type="text" class="form-control" name="edit_fullname" id="edit_fullname" required>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="edit_empTypeGroup" class="form-label">Employee Type Group</label>
                            <input type="text" class="form-control" name="edit_empTypeGroup" id="edit_empTypeGroup" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="edit_email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jobLvl" class="form-label">Job Level</label>
                            <input type="text" class="form-control" name="edit_jobLvl" id="edit_jobLvl" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jobTitle" class="form-label">Job Title</label>
                            <input type="text" class="form-control" name="edit_jobTitle" id="edit_jobTitle" required>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="edit_deptKode" class="form-label">Departemen Code</label>
                            <input type="text" class="form-control" name="edit_deptKode" id="edit_deptKode" required>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="edit_groupKode" class="form-label">Sub Departemen Code</label>
                            <input type="text" class="form-control" name="edit_groupKode" id="edit_groupKode" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_groupName" class="form-label">Sub Departemen</label>
                            <input type="text" class="form-control" name="edit_groupName" id="edit_groupName" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        const _URL = "{{ route('admin.localUser.getData') }}";
        let datatables;

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000);

            datatables = $("#datatables").DataTable({
                processing: true,
                serverSide: true,
                order: [[1, 'desc']],
                ajax: {
                    url: _URL,
                },
                columns: [
                    { data: "DT_RowIndex" },
                    { data: "id", visible: false },
                    { data: "fullname" },
                    { data: "employeId" },
                    { data: "email" },
                    { data: "jobLvl", visible: false },
                    { data: "jobTitle" },
                    { data: "groupKode", visible: false },
                    { data: "groupName", visible: false },
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
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        targets: [1, 5, 7, 8],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: 9,
                        className: 'text-center',
                        createdCell: function (td, cellData, rowData, row, col) {
                            $(td).addClass('text-nowrap');
                        }
                    }
                ],
            });

            $('#search_dt').on('keyup', function () {
                datatables.search(this.value).draw();
            });

            $('#addModal').on('shown.bs.modal', function () {
                $('#employee').select2({
                    minimumInputLength: 2,
                    placeholder: 'Cari Karyawan...',
                    ajax: {
                        url: "{{ route('admin.localUser.searchEmployee') }}",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            console.log(data);
                            return {
                                results: data.map(function (res) {
                                    return {
                                        id: res.employeId,
                                        text: res.fullname + ' - ' + res.groupName,
                                        compCode: res.compCode,
                                        employeId: res.employeId,
                                        fullname: res.fullname,
                                        empTypeGroup: res.empTypeGroup,
                                        jobLvl: res.jobLvl,
                                        jobTitle: res.jobTitle,
                                        deptKode: res.deptKode,
                                        groupKode: res.groupKode,
                                        groupName: res.groupName
                                    };
                                })
                            };
                        }
                    },
                    width: '100%',
                    dropdownParent: $('#addModal')
                });
            });

            $('#employee').on('select2:select', function (e) {
                const data = e.params.data;

                $('#employee_search_container').hide();
                $('#extra_fields').fadeIn();

                $('#compCode').val(data.compCode);
                $('#employeId').val(data.employeId);
                $('#fullname').val(data.fullname);
                $('#empTypeGroup').val(data.empTypeGroup);
                $('#jobLvl').val(data.jobLvl);
                $('#jobTitle').val(data.jobTitle);
                $('#deptKode').val(data.deptKode);
                $('#groupKode').val(data.groupKode);
                $('#groupName').val(data.groupName);
            });

            $('#addModal').on('hidden.bs.modal', function () {
                $('#addForm')[0].reset();

                $('#employee').empty().trigger('change'); 

                $('#extra_fields').hide();
                $('#employee_search_container').show();
            });
        });

        
        document.addEventListener('DOMContentLoaded', function () {
            function setSubmitLoading(form, isLoading) {
                const btn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (!btn) return;

                if (isLoading) {
                    if (!btn.dataset.originalText) {
                        btn.dataset.originalText = (btn.tagName === 'INPUT') ? btn.value : btn.innerHTML;
                    }
                    
                    btn.disabled = true;

                    if (btn.tagName === 'INPUT') {
                        btn.value = 'Memuat...';
                    } else {
                        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memuat...`;
                    }
                } else {
                    btn.disabled = false;
                    const orig = btn.dataset.originalText || 'Submit';
                    if (btn.tagName === 'INPUT') btn.value = orig;
                    else btn.innerHTML = orig;
                }
            }

            const addForm = document.getElementById('addForm');
            if (addForm) {
                addForm.addEventListener('submit', function () {
                    setSubmitLoading(addForm, true);
                });
            }

            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    setSubmitLoading(editForm, true);
                });
            }
        });

        $(function () {
            const key = 'flash_swal';
            const raw = sessionStorage.getItem(key);
            if (!raw) return;

            sessionStorage.removeItem(key);

            try {
                const payload = JSON.parse(raw);
                Swal.fire(payload);
            } catch (e) {}
        });

        function editData(e) {
            const rowData = datatables.row($(e).parents('tr')).data();

            $('#edit_id').val(rowData.id);
            $('#edit_compCode').val(rowData.compCode);
            $('#edit_employeId').val(rowData.employeId);
            $('#edit_fullname').val(rowData.fullname);
            $('#edit_empTypeGroup').val(rowData.empTypeGroup);
            $('#edit_email').val(rowData.email);
            $('#edit_jobLvl').val(rowData.jobLvl);
            $('#edit_jobTitle').val(rowData.jobTitle);
            $('#edit_deptKode').val(rowData.deptKode);
            $('#edit_groupKode').val(rowData.groupKode);
            $('#edit_groupName').val(rowData.groupName);

            $('#editModal').modal('show');
        }

        function resetPassword(e) {
            const rowData = datatables.row($(e).parents('tr')).data();

            Swal.fire({
                title: 'Reset Password?',
                text: `Password untuk user ${rowData.fullname} akan dikembalikan ke default.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.localUser.resetPassword') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: rowData.id
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', 'Password telah direset.', 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan pada server.', 'error');
                        }
                    });
                }
            });
        }

        function deleteData(id) {
            Swal.fire({
                title: "Hapus data?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batal!",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('admin.localUser.destroy') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            $("#datatables").DataTable().ajax.reload(null, false);
                            sessionStorage.setItem('flash_swal', JSON.stringify({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }));

                            window.location.href = window.location.href;
                        },
                        error: function (xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                    Swal.fire("Dibatalkan", "Data anda aman :)", "error");
                }
            });
        }
    </script>
@endsection
