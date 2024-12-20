@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Update Profil Dosen</h1>
                </div>
                <!--end::Page title-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-5">
                        <form method="POST" action="{{ route('profile.update', $dosen->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body border-top p-9">
                                <!-- NIDN -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">NIDN</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="nidn" type="number" class="form-control form-control-lg form-control-solid @error('nidn') is-invalid @enderror" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" required>
                                        @error('nidn')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kuota Proposal -->
                                {{-- <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label readonly fw-bold fs-6">Kuota Proposal</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="kuota_proposal" type="number" class="form-control form-control-lg form-control-solid @error('kuota_proposal') is-invalid @enderror" name="kuota_proposal" value="{{ old('kuota_proposal', $dosen->kuota_proposal) }}" readonly>
                                        @error('kuota_proposal')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <!-- Jumlah Proposal -->
                                {{-- <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Jumlah Proposal</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="jumlah_proposal" type="number" class="form-control form-control-lg form-control-solid @error('jumlah_proposal') is-invalid @enderror" name="jumlah_proposal" value="{{ old('jumlah_proposal', $dosen->jumlah_proposal) }}" readonly>
                                        @error('jumlah_proposal')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <!-- Fakultas -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Fakultas</label>
                                    <div class="col-lg-8 fv-row">
                                        <select id="fakultas" name="fakultas" class="form-control form-control-lg form-control-solid">
                                            <option value="" disabled>Pilih Fakultas</option>
                                            <option value="Fakultas Agama Islam" {{ old('fakultas', $dosen->fakultas) == 'Fakultas Agama Islam' ? 'selected' : '' }}>Fakultas Agama Islam</option>
                                            <option value="Fakultas Teknik" {{ old('fakultas', $dosen->fakultas) == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                            <option value="Fakultas Teknologi Informasi" {{ old('fakultas', $dosen->fakultas) == 'Fakultas Teknologi Informasi' ? 'selected' : '' }}>Fakultas Teknologi Informasi</option>
                                            <option value="Fakultas Ekonomi" {{ old('fakultas', $dosen->fakultas) == 'Fakultas Ekonomi' ? 'selected' : '' }}>Fakultas Ekonomi</option>
                                            <option value="Fakultas Ilmu Pendidikan" {{ old('fakultas', $dosen->fakultas) == 'Fakultas Ilmu Pendidikan' ? 'selected' : '' }}>Fakultas Ilmu Pendidikan</option>
                                        </select>
                                    </div>
                                </div>
    
                                <!-- Program Studi (Prodi) -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Program Studi (Prodi)</label>
                                    <div class="col-lg-8 fv-row">
                                        <select id="prodi" name="prodi" class="form-control form-control-lg form-control-solid">
                                            <option value="" disabled>Pilih Prodi</option>
                                            <!-- Prodi akan terisi otomatis berdasarkan Fakultas -->
                                        </select>
                                    </div>
                                </div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
            "S1 Ekonomi Islam"
        ],
        "Fakultas Ilmu Pendidikan": [
            "S1 Pendidikan Guru Sekolah Dasar",
            "S1 Pendidikan Bahasa dan Sastra Indonesia",
            "S1 Pendidikan Bahasa Inggris",
            "S1 Pendidikan IPA",
            "S1 Pendidikan Matematika"
        ]
    };

    // Dosen's current fakultas and prodi from database
    const selectedFakultas = "{{ old('fakultas', $dosen->fakultas) }}";
    const selectedProdi = "{{ old('prodi', $dosen->prodi) }}";

    // Fungsi untuk mengisi dropdown Prodi berdasarkan Fakultas yang dipilih
    const fakultasSelect = document.getElementById('fakultas');
    const prodiSelect = document.getElementById('prodi');

    fakultasSelect.addEventListener('change', function () {
        const fakultas = this.value;
        const prodiList = fakultasProdi[fakultas];

        // Hapus semua opsi prodi sebelumnya
        prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Prodi</option>';

        // Tambahkan opsi prodi baru berdasarkan fakultas yang dipilih
        if (prodiList) {
            prodiList.forEach(function (prodi) {
                const option = document.createElement('option');
                option.value = prodi;
                option.textContent = prodi;
                prodiSelect.appendChild(option);
            });
        }

        // Jika ada prodi yang sudah terpilih dari database, auto select
        if (fakultas === selectedFakultas) {
            prodiSelect.value = selectedProdi;
        }
    });

    // Isi prodi saat halaman dimuat jika fakultas sudah ada dari database
    if (selectedFakultas) {
        fakultasSelect.value = selectedFakultas;
        fakultasSelect.dispatchEvent(new Event('change')); // Trigger the change event to load prodies
    }
});
</script>
@endpush



                                <!-- Score Sinta -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Score Sinta</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="score_sinta" type="number" step="0.01" class="form-control form-control-lg form-control-solid @error('score_sinta') is-invalid @enderror" name="score_sinta" value="{{ old('score_sinta', $dosen->score_sinta) }}" required>
                                        @error('score_sinta')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end pt-7">
                                    <button type="submit" class="btn btn-sm fw-bolder btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

@stack('scripts')
