@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Kriteria Penilaian</h1>
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
                        <!--begin::Card title-->
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                                    </svg>
                                </span>
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="searchInput" placeholder="Cari Kriteria" />
                            </div>
                        </div>
                        <!--end::Card title-->
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                Tambah Kriteria
                            </button>
                            <!--end::Button-->
                        </div>
                    </div>
                    <!--end::Card header-->
                    
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>jenis</th>
                                        <th>proses</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold" id="myTable">
                                    @foreach ($kriteriaPenilaians as $key => $kriteria)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kriteria->nama }}</td>
                                            <td>{{ $kriteria->jenis }}</td>
                                            <td>{{ ucfirst($kriteria->proses) }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $kriteria->id }}">Edit</button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteKriteria({{ $kriteria->id }})">Hapus</button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $kriteria->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Kriteria Penilaian</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('kriteria-penilaian.update', $kriteria->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-3">
                                                                <label for="nama" class="form-label">Nama Kriteria</label>
                                                                <input type="text" class="form-control" name="nama" value="{{ $kriteria->nama }}" required>
                                                            </div>
                                                       
                                                            <div class="mb-3">
                                                                <label for="jenis" class="form-label">jenis</label>
                                                                <select name="jenis" class="form-select" required>
                                                                    <option value="penelitian" {{ $kriteria->jenis == 'penelitian' ? 'selected' : '' }}>penelitian</option>
                                                                    <option value="pengabdian" {{ $kriteria->jenis == 'pengabdian' ? 'selected' : '' }}>pengabdian</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="proses" class="form-label">proses</label>
                                                                <select name="proses" class="form-select" required>
                                                                    <option value="usulan" {{ $kriteria->proses == 'usulan' ? 'selected' : '' }}>Usulan</option>
                                                                    <option value="lapkemajuan" {{ $kriteria->proses == 'lapkemajuan' ? 'selected' : '' }}>Laporan Kemajuan</option>
                                                                    <option value="lapakhir" {{ $kriteria->proses == 'lapakhir' ? 'selected' : '' }}>Laporan Akhir</option>
                                                                </select>
                                                            </div>

                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <div class="py-5">
                        <!--begin::Pages-->
                        <ul class="pagination">
                            {{ $kriteriaPenilaians->links() }}
                        </ul>
                        <!--end::Pages-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Post-->
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kriteria Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kriteria-penilaian.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kriteria</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="proses" class="form-label">proses</label>
                            <select name="proses" class="form-select" required>
                                <option value="usulan">Usulan</option>
                                <option value="lapkemajuan">Laporan Kemajuan</option>
                                <option value="lapakhir">Laporan Akhir</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert for Delete Confirmation -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteKriteria(id) {
            Swal.fire({
                title: 'Anda yakin ingin menghapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('kriteria-penilaian') }}/' + id, // Pastikan route benar
                        type: 'POST', // Gunakan POST karena browser biasanya tidak mendukung DELETE secara langsung
                        data: {
                            _method: 'DELETE', // Laravel menggunakan _method untuk mendukung DELETE via form
                            _token: '{{ csrf_token() }}' // Token CSRF untuk keamanan
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'Kriteria Penilaian berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload(); // Reload halaman setelah sukses
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    
        // Pencarian tabel
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll('#myTable tr');
            rows.forEach(row => {
                let match = row.innerText.toLowerCase().includes(value);
                row.style.display = match ? '' : 'none';
            });
        });
    </script>
    
    
@endsection
