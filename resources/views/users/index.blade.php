@extends('layouts.main_layout')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Penguna
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    </h1>
                    <!--end::Separator-->
                    <!--begin::Description-->
                    {{-- <small class="text-muted fs-7 fw-bold my-1 ms-1">Users </small> --}}
                    <!--end::Description-->
                    <!--end::Title-->
                </div>
                <!--end::Page title-->

            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="myInput"
                                    placeholder="Search user" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                    </div>
                    <!--end::Card header-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-users">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">No</th>
                                        <th class="min-w-125px">Nama</th>
                                        <th class="min-w-125px">Email</th>
                                        <th class="min-w-150px">Akses</th> <!-- Kolom untuk Role -->
                                        <th class="text-end min-w-100px">Aksi</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold" id="myTable">
                                    @foreach ($data as $key => $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>
                                                @if ($data->roles->isNotEmpty())
                                                    @foreach ($data->roles as $role)
                                                        <span class="badge badge-light-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-light-secondary">No Role</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-light btn-active-light-primary btn-sm me-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal{{ $data->id }}">Edit</button>
                                                    <button class="btn btn-danger btn-sm bypass-login-btn"
                                                    data-user-id="{{ $data->id }}">
                                                    <i class="fas fa-key"></i> Bypass Login
                                                </button>
                                                <script>
                                                    $(document).ready(function() {
                                                        $('.bypass-login-btn').on('click', function() {
                                                            const userId = $(this).data('user-id');
                                                            
                                                            Swal.fire({
                                                                title: 'Alihkan Login?',
                                                                text: 'Anda yakin ingin login sebagai pengguna ini?',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#d33',
                                                                cancelButtonColor: '#3085d6',
                                                                confirmButtonText: 'Ya, Alihkan Login'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    $.ajax({
                                                                        url: `{{ route('users.bypass-login', ['user' => '__USERID__']) }}`.replace('__USERID__', userId),
                                                                        method: 'POST',
                                                                        headers: {
                                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                        },
                                                                        success: function(response) {
                                                                            if (response.success) {
                                                                                Swal.fire(
                                                                                    'Berhasil!',
                                                                                    response.message,
                                                                                    'success'
                                                                                ).then(() => {
                                                                                    window.location.href = response.redirect;
                                                                                });
                                                                            } else {
                                                                                Swal.fire(
                                                                                    'Error!',
                                                                                    response.message,
                                                                                    'error'
                                                                                );
                                                                            }
                                                                        },
                                                                        error: function(xhr) {
                                                                            Swal.fire(
                                                                                'Error!',
                                                                                'Tidak dapat mengalihkan login',
                                                                                'error'
                                                                            );
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                        });
                                                    });
                                                    </script>

                                            </td>
                                            
                                            <script>
                                            function bypassLogin(userId) {
                                                // Implement the bypass login logic here
                                                // This could be an AJAX call to a specific route that logs in the user directly
                                                $.ajax({
                                                    url: '/bypass-login/' + userId,
                                                    method: 'POST',
                                                    success: function(response) {
                                                        // Redirect or show success message
                                                        if (response.redirect) {
                                                            window.location.href = response.redirect;
                                                        } else {
                                                            toastr.success('Login bypassed successfully');
                                                        }
                                                    },
                                                    error: function(xhr) {
                                                        toastr.error('Failed to bypass login');
                                                    }
                                                });
                                            }
                                            </script>
                                        </tr>

                                        <!--begin::Modal - Edit Email and Password-->
                                        <div class="modal fade" id="editUserModal{{ $data->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Email dan Password</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('users.update', $data->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="user_id" value="{{ $data->id }}">

                                                            <!-- Name Field -->
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama</label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ $data->name }}" required>
                                                            </div>
                                                            
                                                            <!-- Email Field -->
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="{{ $data->email }}" required>
                                                            </div>

                                                            <!-- Password Field -->
                                                            <div class="mb-3">
                                                                <label for="password" class="form-label">Password</label>
                                                                <input type="password" class="form-control" name="password"
                                                                    placeholder="Enter new password (optional)">
                                                            </div>

                                                            <!-- Submit Button -->
                                                            <div class="text-end">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Modal-->
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>

                        <!--end::Table-->
                    </div>


                    
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-users').DataTable({
            searchable: true,
        });
    </script>
@endsection