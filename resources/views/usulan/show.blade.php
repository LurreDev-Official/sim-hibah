@extends('layouts.main_layout')
<!-- Sertakan Bootstrap CSS dari CDN -->
<!-- Sertakan Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


<!-- Sertakan Select2 Bootstrap Theme CSS -->
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Anggota</h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>

        <!-- Display error messages -->
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary" href="{{ url('usulan/penelitian') }}">Kembali</a>
                            </div>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Data Anggota Dosen -->
                        <h3>Data Anggota Dosen</h3>
                        <!-- Tombol untuk memunculkan modal tambah anggota dosen -->
                        @php
                            // Ambil data dosen terkait user yang sedang login
                            $dosen = \App\Models\Dosen::where('user_id', auth()->user()->id)->first();

                            // Ambil data anggota dosen berdasarkan dosen yang login
                            $anggotaDosencek = null;
                            if ($dosen) {
                                $anggotaDosencek = \App\Models\AnggotaDosen::where('dosen_id', $dosen->id)->where('usulan_id',$usulan->id)->first();
                            }
                        @endphp

                        @if ($anggotaDosencek->status_anggota == 'ketua' && $usulan->status == 'draft')
                            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahDosen"
                                @if ($usulan->status === 'submitted') disabled @endif>Tambah Anggota Dosen</button>
                        @endif


                        <!-- Tabel Data Anggota Dosen -->
                        <table class="table table-bordered mb-5">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                    <th>Status Anggota</th>
                                    <th>Status</th>
                                    @if ($anggotaDosencek->status_anggota == 'ketua' && $usulan->status == 'draft')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($anggotaDosen as $index => $dosen)
                                    <tr>
                                        <td>{{ $dosen->dosen->user->name }}</td>
                                        <td>{{ $dosen->dosen->user->name }}</td>
                                        <td>{{ $dosen->status_anggota }}</td>
                                        <td>{{ $dosen->status }}</td>
                                        <td>
                                            <!-- Jika dosen ini adalah ketua, munculkan tombol hapus -->

                                            @if ($anggotaDosencek->status_anggota == 'ketua' && $usulan->status == 'draft')
                                                <form
                                                    action="{{ route('anggota-dosen.destroy', ['anggota_dosen' => $dosen->id]) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        @if ($usulan->status == 'draft')  @endif
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')">Hapus</button>
                                                </form>
                                            @endif


                                            <!-- Jika status dosen adalah 'pending', munculkan tombol Setuju dan Tolak -->
                                            @if ($dosen->status == 'belum disetujui' && $anggotaDosencek->status_anggota == 'anggota')
                                                <form
                                                    action="{{ route('anggota-dosen.approve', ['usulan_id' => $usulan->id, 'anggota_dosen' => $dosen->id]) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm">Setuju</button>
                                                </form>
                                                <form
                                                    action="{{ route('anggota-dosen.reject', ['usulan_id' => $usulan->id, 'anggota_dosen' => $dosen->id]) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-warning btn-sm">Tolak</button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Modal Tambah Anggota Dosen -->
                        <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-labelledby="modalTambahDosenLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTambahDosenLabel">Tambah Anggota Dosen</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('anggota-dosen.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="usulan_id" value="{{ $usulan->id }}">

                                        <div class="modal-body">
                                            <input type="text" name="jenis" value="{{ $jenis }}" hidden>
                                            <!-- Dropdown Select2 (di dalam modal tambah dosen) -->
                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-bold">Pilih Dosen:</label>
                                                <div class="col-lg-8">
                                                    <!-- Dropdown Select2 -->
                                                    <select class="form-control select2" name="dosen_id">
                                                        <option value="" disabled selected>Pilih Dosen</option>
                                                        @foreach ($dosens as $dosen)
                                                            <option value="{{ $dosen->id }}">
                                                                {{ $dosen->nidn }}-{{ $dosen->user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tombol di bagian bawah modal -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Tambah Anggota Dosen</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-body">
                        <h3>Data Anggota Mahasiswa</h3>
                        @if ($anggotaDosencek->status_anggota == 'ketua' && $usulan->status == 'draft')
                            <button class="btn btn-success mb-3" data-bs-toggle="modal"
                                data-bs-target="#modalTambahMahasiswa"@if ($usulan->status === 'submitted') disabled @endif>Tambah
                                Anggota Mahasiswa</button>
                        @endif
                        <!-- Modal Tambah Anggota Mahasiswa -->
                        <div class="modal fade" id="modalTambahMahasiswa" tabindex="-1"
                            aria-labelledby="modalTambahMahasiswaLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTambahMahasiswaLabel">Tambah Anggota Mahasiswa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form id="formTambahMahasiswa" method="POST"
                                        action="{{ route('anggota-mahasiswa.store') }}">
                                        @csrf
                                        <input type="hidden" name="usulan_id" value="{{ $usulan->id }}">

                                        <div class="modal-body">
                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-bold">Nama Mahasiswa:</label>
                                                <div class="col-lg-8">
                                                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                                                        class="form-control" placeholder="Masukkan Nama Mahasiswa"
                                                        value="{{ old('nama_lengkap') }}">
                                                    <span class="text-danger error-text nama_lengkap_error"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-bold">NIM:</label>
                                                <div class="col-lg-8">
                                                    <input type="number" name="nim" id="nim"
                                                        class="form-control" placeholder="Masukkan NIM"
                                                        value="{{ old('nim') }}">
                                                    <span class="text-danger error-text nim_error"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-bold">Fakultas:</label>
                                                <div class="col-lg-8">
                                                    <select name="fakultas" id="fakultas" class="form-control">
                                                        <option value="" disabled selected>Pilih Fakultas</option>
                                                        <option value="Fakultas Agama Islam">Fakultas Agama Islam</option>
                                                        <option value="Fakultas Teknik">Fakultas Teknik</option>
                                                        <option value="Fakultas Teknologi Informasi">Fakultas Teknologi
                                                            Informasi</option>
                                                        <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                                                        <option value="Fakultas Ilmu Pendidikan">Fakultas Ilmu Pendidikan
                                                        </option>
                                                    </select>
                                                    <span class="text-danger error-text fakultas_error"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-bold">Prodi:</label>
                                                <div class="col-lg-8">
                                                    <select name="prodi" id="prodi" class="form-control">
                                                        <option value="" disabled selected>Pilih Prodi</option>
                                                    </select>
                                                    <span class="text-danger error-text prodi_error"></span>
                                                </div>
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Data array Fakultas dan Prodi terkait
                                                    const fakultasProdi = {
                                                        "Fakultas Agama Islam": [
                                                            "S1 Hukum Keluarga",
                                                            "S1 Hukum Ekonomi Syari'ah",
                                                            "S1 Manajemen Pendidikan Islam",
                                                            "S1 Komunikasi dan Penyiaran Islam",
                                                            "S1 Pendidikan Agama Islam",
                                                            "S1 Pendidikan Bahasa Arab",
                                                            "S1 Pendidikan Guru MI"
                                                        ],
                                                        "Fakultas Teknik": [
                                                            "S1 Teknik Mesin",
                                                            "S1 Teknik Elektro",
                                                            "S1 Teknik Sipil",
                                                            "S1 Teknik Industri"
                                                        ],
                                                        "Fakultas Teknologi Informasi": [
                                                            "S1 Teknik Informatika",
                                                            "S1 Sistem Informasi",
                                                            "S1 Teknologi Informasi"
                                                        ],
                                                        "Fakultas Ekonomi": [
                                                            "S1 Manajemen",
                                                            "S1 Akuntansi",
                                                            "S1 Akuntansi"
                                                        ],
                                                        "Fakultas Ilmu Pendidikan": [
                                                            "S1 Pendidikan Guru Sekolah Dasar",
                                                            "S1 Pendidikan Bahasa dan Sastra Indonesia",
                                                            "S1 Pendidikan Bahasa Inggris",
                                                            "S1 Pendidikan IPA",
                                                            "S1 Pendidikan Matematika"
                                                        ]
                                                    };

                                                    // Mengambil fakultas dan prodi yang sudah dipilih jika ada
                                                    const selectedFakultas = "{{ old('fakultas', $dosen->fakultas ?? '') }}";
                                                    const selectedProdi = "{{ old('prodi', $dosen->prodi ?? '') }}";

                                                    // Elemen dropdown Fakultas dan Prodi
                                                    const fakultasSelect = document.getElementById('fakultas');
                                                    const prodiSelect = document.getElementById('prodi');

                                                    // Fungsi untuk mengisi dropdown Prodi berdasarkan Fakultas yang dipilih
                                                    fakultasSelect.addEventListener('change', function() {
                                                        const fakultas = this.value;
                                                        const prodiList = fakultasProdi[fakultas];

                                                        // Kosongkan opsi prodi sebelumnya
                                                        prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Prodi</option>';

                                                        // Tambahkan opsi prodi baru berdasarkan fakultas yang dipilih
                                                        if (prodiList) {
                                                            prodiList.forEach(function(prodi) {
                                                                const option = document.createElement('option');
                                                                option.value = prodi;
                                                                option.textContent = prodi;
                                                                prodiSelect.appendChild(option);
                                                            });
                                                        }

                                                        // Jika ada prodi yang sudah dipilih sebelumnya, otomatis dipilih
                                                        if (fakultas === selectedFakultas) {
                                                            prodiSelect.value = selectedProdi;
                                                        }
                                                    });

                                                    // Saat halaman dimuat, isi fakultas dan prodi jika sudah ada data sebelumnya
                                                    if (selectedFakultas) {
                                                        fakultasSelect.value = selectedFakultas;
                                                        fakultasSelect.dispatchEvent(new Event('change')); // Trigger event change untuk memuat Prodi
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Fakultas</th>
                                    <th>Prodi</th>
                                    @if ($anggotaDosencek->status_anggota == 'ketua' && $usulan->status == 'draft')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($anggotaMahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $mahasiswa->nama_lengkap }}</td>
                                        <td>{{ $mahasiswa->nim }}</td>
                                        <td>{{ $mahasiswa->fakultas }}</td>
                                        <td>{{ $mahasiswa->prodi }}</td>
                                        <td>
                                            @if ($anggotaDosencek->status_anggota == 'ketua' && $usulan->status == 'draft')
                                                <form action="{{ route('anggota-mahasiswa.destroy', $usulan->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        @if ($usulan->status == 'approved') disabled @endif
                                                        @if ($anggotaDosencek->status_anggota !== 'ketua' || $usulan->status === 'submitted') disabled @endif>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                         

                        @if (
                            $anggotaDosencek &&
                                $anggotaDosencek->status_anggota == 'ketua' &&
                                !in_array($usulan->status, ['submitted', 'review','waiting approved', 'revision', 'approved', 'rejected']))
                          <div class="col-12 py-4 text-center">
                                <!-- Form untuk mengajukan usulan -->
                                <form id="submitUsulanForm_{{ $usulan->id }}"
                                    action="{{ route('usulan.submit', ['jenis' => $jenis, 'usulan' => $usulan->id]) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('PATCH')
                                </form>
    
                                <!-- Tombol untuk Ajukan -->
                                <button id="submitUsulanButton_{{ $usulan->id }}"
                                    type="button" class="btn btn-info btn-lg"
                                    onclick="
                                    Swal.fire({
                                        title: 'Apakah Anda yakin?',
                                        text: 'Anda akan mengajukan usulan ini pastikan anggota dosen sudah disetujui dan data valid!',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, ajukan!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('submitUsulanForm_{{ $usulan->id }}').submit();
                                        }
                                    });
                                ">
                                    <i class="fas fa-paper-plane"></i> Ajukan
                                </button>
                            </div>
                            <!-- SweetAlert Konfirmasi Pengajuan -->
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        @endif

                    </div>
                   
                </div>
            </div>

        </div>
        <!--end::Post-->
    </div>

@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Inisialisasi Select2 -->
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4', // Menggunakan tema Bootstrap 4
            placeholder: "Pilih Dosen",
            allowClear: true
        });
    });
</script>
