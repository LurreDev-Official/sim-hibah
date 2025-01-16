@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Dosen</h1>
                </div>
            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <!-- Icon Search -->
                                </span>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Cari Dosen" />
                            </div>
                        </div>

                        <!-- Filter Dropdown -->
                        <div class="d-flex mt-4">
                            <!-- Form untuk Filter -->
                            <form action="{{ route('laporan-hitungan-usulan.filter') }}" method="POST" class="d-flex align-items-center">
                                @csrf <!-- Tambahkan CSRF Token untuk keamanan -->
                        
                                <!-- Dropdown Fakultas -->
                                <select name="fakultas" class="form-select" id="fakultas" aria-label="Fakultas" required>
                                    <option value="">Pilih Fakultas</option>
                                    @foreach($fakultas as $fakultasItem)
                                        <option value="{{ $fakultasItem->id }}">{{ $fakultasItem->name }}</option>
                                    @endforeach
                                </select>
                        
                                <!-- Dropdown Program Studi -->
                                <select name="prodi_id" class="form-select ms-3" id="prodi" aria-label="Program Studi">
                                    <option value="">Pilih Program Studi</option>
                                </select>
                        
                                <!-- Tombol Filter -->
                                <button type="submit" class="btn btn-primary ms-3">Filter</button>
                            </form>
                        </div>
                        
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-dosen">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                    <th>Program Studi</th>
                                    <th>Jumlah Proposal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dosen as $index => $data)
                                    <tr data-fakultas="{{ $data->fakultas_id }}" data-prodi="{{ $data->prodi_id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->user->name }}</td>
                                        <td>{{ $data->prodi->name }}</td>
                                        <td>{{ $data->jumlah_proposal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-dosen').DataTable({
            searchable: true,
        });
    </script>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        console.log("jQuery is working");
    });
</script>

<script>
    $(document).ready(function () {
        // Menangani perubahan pada dropdown fakultas
        $('#fakultas').on('change', function () {
            let fakultas_id = $(this).val(); // Ambil ID Fakultas yang dipilih
            
            // Kosongkan dropdown Program Studi dan tambahkan pesan "Memuat..."
            $('#prodi').empty().append('<option value="">Memuat data...</option>');

            if (fakultas_id) {
                $.ajax({
                    url: '/get-prodi/' + fakultas_id, // URL dinamis
                    method: 'GET',
                    success: function (data) {
                        console.log(data); // Log data untuk debugging
                        
                        // Hapus pesan "Memuat..." dan tambahkan opsi default
                        $('#prodi').empty().append('<option value="">Pilih Program Studi</option>');

                        if (Array.isArray(data) && data.length > 0) {
                            // Iterasi data JSON dan tambahkan opsi ke dropdown
                            data.forEach(function (prodi) {
                                $('#prodi').append('<option value="' + prodi.id + '">' + prodi.name + '</option>');
                            });
                        } else {
                            // Jika data kosong
                            $('#prodi').append('<option value="">Tidak ada program studi tersedia</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Terjadi kesalahan saat memuat data:", error);
                        // Tampilkan pesan error di dropdown
                        $('#prodi').empty().append('<option value="">Gagal memuat data</option>');
                        alert("Gagal memuat data program studi. Silakan coba lagi.");
                    }
                });
            } else {
                // Jika tidak ada fakultas yang dipilih, reset dropdown Program Studi
                $('#prodi').empty().append('<option value="">Pilih Program Studi</option>');
            }
        });
    });
</script>

