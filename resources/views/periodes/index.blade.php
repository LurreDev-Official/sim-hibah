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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Periode</h1>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Cari Periode" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                Tambah Periode
                            </button>
                        </div>
                        
                        <!-- Create Modal -->
                        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Periode</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('periodes.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="tahun" class="form-label">Tahun</label>
                                                <select class="form-control" name="tahun" id="tahun" required>
                                                    @php
                                                        $currentYear = date('Y'); // Get the current year
                                                        $startYear = 2025; // Start year
                                                    @endphp
                                                    @for ($year = $startYear; $year <= $currentYear; $year++)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            
                        
                                            <div class="mb-3">
                                                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                                <input type="date" class="form-control" name="tanggal_awal" id="tanggal_awal" required>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                                <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir" required>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label for="nominal" class="form-label">Nominal</label>
                                                <input type="number" class="form-control" name="nominal" id="nominal" required>
                                            </div>
                        
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label">Aktif</label>
                                                <select class="form-control" name="is_active" id="is_active" required>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
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
                        
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-periode">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Tanggal Awal</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($periodes as $periode)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $periode->tahun }}</td>
                                        <td>{{ $periode->tanggal_awal->format('d-m-Y') }}</td>
                                        <td>{{ $periode->tanggal_akhir->format('d-m-Y') }}</td>
                                        <td>{{ number_format($periode->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $periode->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $periode->is_active ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-light-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $periode->id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deletePeriode({{ $periode->id }})">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $periode->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Periode</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('periodes.update', $periode->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="tahun" class="form-label">Tahun</label>
                                                            <input type="text" class="form-control" name="tahun"
                                                                value="{{ $periode->tahun }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal_awal" class="form-label">Tanggal
                                                                Awal</label>
                                                            <input type="date" class="form-control" name="tanggal_awal"
                                                                value="{{ $periode->tanggal_awal->format('Y-m-d') }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal_akhir" class="form-label">Tanggal
                                                                Akhir</label>
                                                            <input type="date" class="form-control" name="tanggal_akhir"
                                                                value="{{ $periode->tanggal_akhir->format('Y-m-d') }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nominal" class="form-label">Nominal</label>
                                                            <input type="number" class="form-control" name="nominal"
                                                                value="{{ $periode->nominal }}" required>
                                                        </div>

                                                        
                                                        
                                                        <!-- Displaying badge for current status -->
                                                        <div class="mb-3">
                                                            <label for="is_active" class="form-label">Status</label>
                                                            <select name="is_active" class="form-select" required>
                                                                <option value="1" {{ $periode->is_active ? 'selected' : '' }}>
                                                                    Aktif
                                                                </option>
                                                                <option value="0" {{ !$periode->is_active ? 'selected' : '' }}>
                                                                    Non-Aktif
                                                                </option>
                                                            </select>
                                                        </div>
                                                        

                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
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
    </div>

    <script>
        $(document).ready(function() {
            $('#table-periode').DataTable({
                responsive: true,
                processing: true,
                paging: true,
                searching: true,
                columnDefs: [
                    { targets: 6, orderable: false } // Disable ordering for Actions column
                ]
            });
        });

        function deletePeriode(id) {
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
                        url: '{{ url('periode') }}/' + id,
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
        var xin_table = $('#table-periode').DataTable({
            searchable: true,
        });
    </script>
@endsection
