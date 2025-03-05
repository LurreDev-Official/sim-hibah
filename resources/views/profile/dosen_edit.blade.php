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
                                        <input id="nidn" type="number"
                                            class="form-control form-control-lg form-control-solid @error('nidn') is-invalid @enderror"
                                            name="nidn" value="{{ old('nidn', $dosen->nidn) }}" required
                                            placeholder="0715118702">
                                        @error('nidn')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Nama Lengkap Dan Gelar -->
                                <!-- Nama Lengkap Dan Gelar -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Lengkap dan
                                        Gelar</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="name" type="text"
                                            class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $dosen->user->name) }}" required
                                            placeholder="Dr. Nama Dosen, M.Sc">
                                        @error('name')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Fakultas -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Fakultas</label>
                                    <div class="col-lg-8 fv-row">
                                        <select id="fakultas" name="fakultas"
                                            class="form-control form-control-lg form-control-solid">
                                            <option value="" disabled>Pilih Fakultas</option>
                                            @foreach ($fakultas as $fakultasItem)
                                                <option value="{{ $fakultasItem->id }}"
                                                    {{ old('fakultas', $dosen->fakultas_id) == $fakultasItem->id ? 'selected' : '' }}>
                                                    {{ $fakultasItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Program Studi (Prodi) -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Program Studi
                                        (Prodi)</label>
                                    <div class="col-lg-8 fv-row">
                                        <select id="prodi" name="prodi"
                                            class="form-control form-control-lg form-control-solid">
                                            <option value="" disabled>Pilih Program Studi</option>
                                            <!-- Prodi options will be populated by JS -->
                                        </select>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const fakultasSelect = document.getElementById('fakultas');
                                        const prodiSelect = document.getElementById('prodi');
                                        const selectedProdi = "{{ old('prodi', $dosen->prodi_id ?? '') }}";

                                        // Function to fetch Prodi based on Fakultas selection
                                        function loadProdi(fakultasId) {
                                            fetch(`/get-prodi/${fakultasId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    // Clear previous options
                                                    prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Prodi</option>';

                                                    // Populate Prodi options based on the response
                                                    if (data.length > 0) {
                                                        data.forEach(prodi => {
                                                            const option = document.createElement('option');
                                                            option.value = prodi.id;
                                                            option.textContent = prodi.name;

                                                            // Pre-select the option if it matches the selected Prodi
                                                            if (selectedProdi && selectedProdi == prodi.id) {
                                                                option.selected = true;
                                                            }

                                                            prodiSelect.appendChild(option);
                                                        });
                                                    } else {
                                                        const option = document.createElement('option');
                                                        option.textContent = 'No Program Studi available';
                                                        prodiSelect.appendChild(option);
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error fetching Prodi:', error);
                                                });
                                        }

                                        // Event listener for Fakultas selection
                                        fakultasSelect.addEventListener('change', function() {
                                            const fakultasId = this.value;
                                            if (fakultasId) {
                                                loadProdi(fakultasId);
                                            } else {
                                                prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Prodi</option>';
                                            }
                                        });

                                        // Trigger Prodi loading if Fakultas is already selected
                                        const selectedFakultas = "{{ old('fakultas', $dosen->fakultas_id ?? '') }}";
                                        if (selectedFakultas) {
                                            fakultasSelect.value = selectedFakultas;
                                            loadProdi(selectedFakultas); // Load Prodi for selected Fakultas
                                        }
                                    });
                                </script>

                                <!-- Score Sinta -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Score Sinta</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="score_sinta" type="number" step="0.01"
                                            class="form-control form-control-lg form-control-solid @error('score_sinta') is-invalid @enderror"
                                            name="score_sinta" value="{{ old('score_sinta', $dosen->score_sinta) }}"
                                            required readonly>
                                        @error('score_sinta')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
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

<script>
    if (data.score_sinta !== null) {
        scoreSintaInput.value = data.score_sinta;
        toastr.success('Data ditemukan: Score Sinta adalah ' + data.score_sinta);
    } else {
        scoreSintaInput.value = ''; // Clear if no score found
        toastr.error('Data tidak ditemukan untuk NIDN: ' + nidn);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nidnInput = document.getElementById('nidn');
        const scoreSintaInput = document.getElementById('score_sinta');

        nidnInput.addEventListener('input', function() {
            const nidn = this.value;

            if (nidn) {
                fetch(`/sinta-score/${nidn}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.score_sinta !== null) {
                            scoreSintaInput.value = data.score_sinta;
                            toastr.success('Data ditemukan: Score Sinta adalah ' + data
                                .score_sinta);
                        } else {
                            scoreSintaInput.value = ''; // Clear if no score found
                            toastr.error('Data tidak ditemukan untuk NIDN: ' + nidn);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching score Sinta:', error);
                        toastr.error('Terjadi kesalahan saat mengambil data.');
                    });
            } else {
                scoreSintaInput.value = ''; // Clear input if NIDN is empty
            }
        });
    });
</script>
