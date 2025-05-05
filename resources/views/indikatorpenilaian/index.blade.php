@extends('layouts.main_layout')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Toolbar -->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Indikator Penilaian</h1>
            </div>
        </div>
    </div>
    <!-- End Toolbar -->

    <!-- Content -->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <!-- Card -->
            <div class="card">
                <!-- Card Header -->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="black" />
                                </svg>
                            </span>
                            <input type="text" class="form-control form-control-solid w-250px ps-14" id="searchInput"
                                placeholder="Cari Indikator" />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            Tambah Indikator
                        </button>
                    </div>
                </div>
                <!-- End Card Header -->

                <!-- Card Body -->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-indikator">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Kriteria Penilaian</th>
                                    <th>Nama Indikator</th>
                                    <th>Jumlah Bobot</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                                @foreach ($indikatorPenilaians as $key => $indikator)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ optional($indikator->kriteriaPenilaian)->nama }} - {{ optional($indikator->kriteriaPenilaian)->jenis }} -{{ optional($indikator->kriteriaPenilaian)->proses }}
                                        </td>
                                        <td>{{ $indikator->nama_indikator }}</td>
                                        {{-- <td>
                                            @foreach ($kriteriaPenilaians as $kriteria)
                                                @if ($kriteria->id == $indikator->kriteria_id)
                                                    {{ $kriteria->nama }}-{{ $kriteria->jenis }}
                                                @endif
                                            @endforeach
                                        </td> --}}
                                       
                                        
                                        <td>{{ $indikator->jumlah_bobot }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-light-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $indikator->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deleteIndikator({{ $indikator->id }})">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $indikator->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Indikator Penilaian</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('indikator-penilaian.update', $indikator->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="kriteria_id" class="form-label">Pilih Kriteria Penilaian</label>
                                                            <select name="kriteria_id" class="form-select" required>
                                                                @foreach ($kriteriaPenilaians as $kriteria)
                                                                    <option value="{{ $kriteria->id }}"
                                                                        {{ $kriteria->id == $indikator->kriteria_id ? 'selected' : '' }}>
                                                                        {{ $kriteria->nama }}-{{ $kriteria->jenis }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nama_indikator" class="form-label">Nama Indikator</label>
                                                            <input type="text" class="form-control" name="nama_indikator"
                                                                value="{{ $indikator->nama_indikator }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="jumlah_bobot" class="form-label">Jumlah Bobot</label>
                                                            <input type="number" class="form-control" name="jumlah_bobot"
                                                                value="{{ $indikator->jumlah_bobot }}" min="10" max="100" required>
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
                </div>
                <!-- End Card Body -->

                
            </div>
            <!-- End Card -->
        </div>
    </div>
    <!-- End Content -->
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Indikator Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('indikator-penilaian.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kriteria_id" class="form-label">Pilih Kriteria Penilaian</label>
                        <select name="kriteria_id" class="form-select" required>
                            <option value="">Pilih Kriteria</option>
                            @foreach ($kriteriaPenilaians as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->nama }}-{{ $kriteria->jenis }}-{{ $kriteria->proses }}</option>

                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama_indikator" class="form-label">Nama Indikator</label>
                        <input type="text" class="form-control" name="nama_indikator" required>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_bobot" class="form-label">Jumlah Bobot</label>
                        <input type="number" class="form-control" name="jumlah_bobot" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteIndikator(id) {
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
                    url: '{{ url('indikator-penilaian') }}/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Terhapus!', 'Indikator Penilaian berhasil dihapus.', 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
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
        var xin_table = $('#table-indikator').DataTable({
            searchable: true,
        });
    </script>
@endsection
