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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Template Dokumen</h1>
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
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15" placeholder="Cari Template" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                Tambah Template
                            </button>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-template">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Proses</th>
                                    <th>Skema</th>
                                    <th>File</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $template)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $template->nama }}</td>
                                        <td>{{ $template->proses }}</td>
                                        <td>{{ $template->skema }}</td>
                                        <td><a href="{{ asset('storage/' . $template->file) }}" target="_blank">Lihat File</a></td>
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $template->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteTemplate({{ $template->id }})">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $template->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Template Dokumen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('template-dokumen.update', $template->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="nama" class="form-label">Nama Template</label>
                                                            <input type="text" class="form-control" name="nama" value="{{ $template->nama }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="proses" class="form-label">Proses</label>
                                                            <select name="proses" class="form-select" required>
                                                                <option value="" disabled>Pilih Proses</option>
                                                                <option value="Usulan" {{ $template->proses == 'Usulan' ? 'selected' : '' }}>Usulan</option>
                                                                <option value="Laporan Akhir" {{ $template->proses == 'Laporan Akhir' ? 'selected' : '' }}>Laporan Akhir</option>
                                                                <option value="Laporan Kemajuan" {{ $template->proses == 'Laporan Kemajuan' ? 'selected' : '' }}>Laporan Kemajuan</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="skema" class="form-label">Skema</label>
                                                            <select name="skema" class="form-select" required>
                                                                <option value="Penelitian" {{ $template->skema == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                                                                <option value="Pengabdian" {{ $template->skema == 'Pengabdian' ? 'selected' : '' }}>Pengabdian</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="file" class="form-label">File</label>
                                                            <input type="file" class="form-control" name="file">
                                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah file.</small>
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
                        <h5 class="modal-title">Tambah Template Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('template-dokumen.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Template</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>

                            <div class="mb-3">
                                <label for="proses" class="form-label">Proses</label>
                                <select name="proses" class="form-select" required>
                                    <option value="" disabled selected>Pilih Proses</option>
                                    <option value="Usulan">Usulan</option>
                                    <option value="Laporan Akhir">Laporan Akhir</option>
                                    <option value="Laporan Kemajuan">Laporan Kemajuan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="skema" class="form-label">Skema</label>
                                <select name="skema" class="form-select" required>
                                    <option value="Penelitian" selected>Penelitian</option>
                                    <option value="Pengabdian">Pengabdian</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">File</label>
                                <input type="file" class="form-control" name="file" required>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Tambah Template</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Create Modal -->
    </div>

    <script>
        

        function deleteTemplate(id) {
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
                        url: '{{ url('template-dokumen') }}/' + id,
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
        var xin_table = $('#table-template').DataTable({
            searchable: true,
        });
    </script>
@endsection