@extends('layout.master')
@section('title')
    {{ ucwords($role->name) }} Management
@endsection
@section('page_title')
    {{ ucwords($role->name) }} Management
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
        <li class="breadcrumb-item text-muted">user</li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">{{ ucwords($role->name) }}</li>
    </ul>
@endsection
@section('main-content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-12 mb-0 py-0">
                                        <h5 class="my-0">Managament Account {{ ucwords($role->name) }}</h5>
                                    </div>
                                    <div class="col-12 my-0 py-0">
                                        <span class="fw-light fs-8">Create Or Update Or Delete User {{ ucwords($role->name) }}</span>
                                    </div>
                                </div>
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
                                    <input type="text" id="search_dt" class="form-control border border-2 w-250px ps-14" placeholder="Search User" />
                                </div>
                                <table id="dt_userTable"
                                    class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th style="width: 50px;">#</th>
                                            <th>Fullname</th>
                                            <th>NIK</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th style="width: 50px;">#</th>
                                            <th>Fullname</th>
                                            <th>NIK</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add New Delegasi</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-5">
                                <div class="btn-group w-100 user-toggle" role="group">
                                    <input type="radio" class="btn-check" name="dataSource" id="sourceWorkday" value="workday" checked autocomplete="off">
                                    <label class="btn btn-outline-primary" for="sourceWorkday">
                                        Workday
                                    </label>

                                    <input type="radio" class="btn-check" name="dataSource" id="sourceLokal" value="lokal" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="sourceLokal">
                                        Lokal
                                    </label>
                                </div>
                            </div>

                            <form id="employeeForm" name="employeeForm">
                                <input type="hidden" name="employee_id" id="employee_id">
                                <input type="text" class="d-none" name="fullname">
                                <input type="text" class="d-none" name="nik">
                                <input type="text" class="d-none" name="dept">
                                <input type="text" class="d-none" name="phone">
                                <input type="text" class="d-none" name="email">

                                <div class="mb-3">
                                    <label for="employee" class="form-label">Cari Employee</label>
                                    <select class="form-select" name="employee" id="employee">
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary w-100" id="savedata" value="create">Submit
                                    Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User Custom Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editEmployeeForm" name="editEmployeeForm">
                            <input type="hidden" name="edit_employee_id" id="edit_employee_id">
                            <div class="mb-3">
                                <label for="edit_fullname" class="form-label">Fullname</label>
                                <input type="text" class="form-control" name="edit_fullname" id="edit_fullname"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" name="edit_nik" id="edit_nik" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="edit_email" id="edit_email" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateData">Update Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        const _URL = "{{ route('admin.user-has-custom-role.getDT', [strtolower(str_replace(' ', '-', $role->name)), $role->id]) }}";

        $(document).ready(function() {
            $('.page-loading').fadeIn();
            setTimeout(function() {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let userTable = $("#dt_userTable").DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [1, 'desc']
                ],
                ajax: {
                    url: _URL,
                },
                columns: [{
                        data: 0,
                        orderable: true
                    },
                    {
                        data: "fullname"
                    },
                    {
                        data: "nik"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart +
                            1; // Calculate the row index
                    },
                }, ],
            });

            $('#search_dt').on('keyup', function() {
                userTable.search(this.value).draw();
            });

            $('#employee').select2({
                minimumInputLength: 2,
                placeholder: 'Pilih Employee',
                ajax: {
                    url: "{{ route('admin.user-has-custom-role.getHrisEmployee', [strtolower(str_replace(' ', '-', $role->name)), $role->id]) }}",
                    dataType: 'json',
                    delay: 150,
                    data: function (params) {
                        return {
                            q: params.term,
                            source: $("input[name='dataSource']:checked").val() 
                        };
                    },
                    processResults: data => {
                        return {
                            results: data.map(res => {
                                var text = res.fullname + ' - ' + res.dept
                                return {
                                    text: text,
                                    id: res.id,
                                    fullname: res.fullname,
                                    email: res.email,
                                    phone: res.phone,
                                    dept: res.dept,
                                    subDept: res.subDept,
                                    groupName: res.groupName
                                }
                            })
                        }
                    },
                    cache: true
                }
            }).on('select2:select', function(e) {
                var data = e.params.data;
                // Display the selected employee details in the HTML
                $("input[name='nik']").val(data.id);
                $("input[name='fullname']").val(data.fullname);
                $("input[name='dept']").val(data.dept);
                $("input[name='phone']").val(data.phone);
                $("input[name='email']").val(data.email);
            });

            $('#savedata').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');

                $.ajax({
                    data: $('#employeeForm').serialize(),
                    url: "{{ route('admin.user-has-custom-role.store', [strtolower(str_replace(' ', '-', $role->name)), $role->id]) }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil !",
                                text: data.message,
                                icon: "success"
                            });
                            $('#employeeForm').trigger("reset");
                            userTable.draw();
                        } else {
                            Swal.fire({
                                title: "Gagal !",
                                text: data.message,
                                icon: "info"
                            });
                            $('#employeeForm').trigger("reset");
                            userTable.draw();
                        }

                    },
                    error: function(data) {
                        Swal.fire({
                            title: "Error !",
                            text: data.message,
                            icon: "error"
                        });

                        console.log('Error:', data);
                    },
                    complete: function() {
                        $('#savedata').html('Submit Data');
                    }
                });
            });

            $('body').on('click', '.deletePost', function() {
                var url = $(this).attr("data-url");
                Swal.fire({
                    title: "Apakah anda yakin ?",
                    text: "Menghapus data users dapat mengakibatkan data yang berelasi akan terhapus",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire({
                                        title: "Terhapus !",
                                        text: data.message,
                                        icon: "success"
                                    });
                                    userTable.draw();
                                } else {
                                    Swal.fire({
                                        title: "Error System !",
                                        text: data.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(data) {
                                Swal.fire({
                                    title: "Galat System !",
                                    text: data,
                                    icon: "error"
                                });
                                console.log('Error:', data);
                            }
                        });

                    }
                });
            });

            $('body').on('click', '.editPost', function() {
                var userId = $(this).attr("data-id");
                var fullname = $(this).attr("data-fullname");
                var nik = $(this).attr("data-nik");
                var email = $(this).attr("data-email");
                var subDept = $(this).attr("data-subdept");
                var phone = $(this).attr("data-phone");
                var dept = $(this).attr("data-dept");
                // Populate modal dengan data user
                $('#edit_employee_id').val(userId);
                $('#edit_fullname').val(fullname);
                $('#edit_nik').val(nik);
                $('#edit_email').val(email);
                // Buka modal
                $('#editUserModal').modal('show');
            });

            // Submit Update
            $('#updateData').click(function(e) {
                e.preventDefault();
                $(this).html('Updating..');
                $.ajax({
                    data: $('#editEmployeeForm').serialize(),
                    url: "{{ route('admin.user-has-custom-role.update', ':id') }}".replace(':id', $(
                        '#edit_employee_id').val()),
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil !",
                                text: data.message,
                                icon: "success"
                            });
                            $('#editEmployeeForm').trigger("reset");
                            $('#editUserModal').modal('hide');
                            userTable.draw(); // Reload tabel DataTable
                        } else {
                            Swal.fire({
                                title: "Gagal !",
                                text: data.message,
                                icon: "info"
                            });
                        }
                    },
                    error: function(data) {
                        Swal.fire({
                            title: "Error !",
                            text: data.message,
                            icon: "error"
                        });
                        console.log('Error:', data);
                    },
                    complete: function() {
                        $('#updateData').html('Update Data');
                    }
                });
            });
        });
    </script>
@endsection
