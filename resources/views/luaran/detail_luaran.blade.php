@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Luaran</h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <!-- SVG Icon -->
                                </span>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Cari Luaran" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                Tambah Luaran
                            </button>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-luaran">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Type</th>
                                    <th>URL</th>
                                    <th>File LOA</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($luarans as $luaran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $luaran->judul }}</td>
                                        <td>{{ $luaran->type }}</td>
                                        <td><a href="{{ $luaran->url }}" target="_blank">Link</a></td>
                                        <td>{{ $luaran->file_loa }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-light-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $luaran->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deleteLuaran({{ $luaran->id }})">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $luaran->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Luaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('luaran.update', $luaran->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="judul" class="form-label">Judul</label>
                                                            <input type=" text" class="form-control" name="judul" value="{{ $luaran->judul }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="type" class="form-label">Type</label>
                                                            <input type="text" class="form-control" name="type" value="{{ $luaran->type }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="url" class="form-label">URL</label>
                                                            <input type="url" class="form-control" name="url" value="{{ $luaran->url }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="file_loa" class="form-label">File LOA</label>
                                                            <input type="file" class="form-control" name="file_loa">
                                                        </div>

                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Edit Modal -->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Post-->

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Luaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('luaran.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" name="judul" required>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" class="form-control" name="type" required>
                            </div>

                            <div class="mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="url" class="form-control" name="url" required>
                            </div>

                            <div class="mb-3">
                                <label for="file_loa" class="form-label">File LOA</label>
                                <input type="file" class="form-control" name="file_loa" required>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Tambah Luaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Create Modal -->
    </div>

    <script>
        $(document).ready(function() {
            $('#table-luaran').DataTable({
                responsive: true,
                processing: true,
                paging: true,
                searching: true,
                columnDefs: [
                    { targets: 5, orderable: false } // Disable ordering for Actions column
                ]
            });
        });

        function deleteLuaran(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('luaran') }}/' + id,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Data telah dihapus.',
                                'success'
                            ).then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    var xin_table = $('#table-luaran').DataTable({
        searchable: true,
    });
</script>
@endsection