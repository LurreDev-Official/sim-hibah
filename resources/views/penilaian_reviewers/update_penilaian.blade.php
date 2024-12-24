@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Update Penilaian
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    </h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary" href="{{ url()->previous() }}">Kembali</a>
                            </div>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <div class="card-body">
                        <!-- Display Usulan Information -->
                        <div class="mb-5">
                            <h4 class="fw-bold">Data Usulan</h4>
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Judul Usulan</th>
                                        <td>{{ $usulan->judul_usulan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Skema</th>
                                        <td>{{ $usulan->jenis_skema }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Pelaksanaan</th>
                                        <td>{{ $usulan->tahun_pelaksanaan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dokumen Usulan</th>
                                        <td>
                                            <a href="{{ asset('storage/' . $usulan->dokumen_usulan) }}" target="_blank">
                                                Lihat Dokumen
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Rumpun Ilmu</th>
                                        <td>{{ $usulan->rumpun_ilmu }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bidang Fokus</th>
                                        <td>{{ $usulan->bidang_fokus }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tema Penelitian</th>
                                        <td>{{ $usulan->tema_penelitian }}</td>
                                    </tr>
                                    <tr>
                                        <th>Topik Penelitian</th>
                                        <td>{{ $usulan->topik_penelitian }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lama Kegiatan</th>
                                        <td>{{ $usulan->lama_kegiatan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <br>

                {{-- <div class="card">
                    <div class="card-body">
                        <div class="mb-5">
                            <h4 class="fw-bold">Form Update Penilaian</h4>
                            <!-- Form Container -->
                            <div class="card">
                                <div class="card-body">
                                    <form id="penilaianForm" action="{{ url('update-penilaian/' . $penilaianReviewer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Menggunakan PUT untuk update -->
                                    
                                        <input type="hidden" name="penilaian_reviewers_id" value="{{ $penilaianReviewer->id }}">
                                    
                                        <!-- Table for Input Fields -->
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th>Nama Indikator</th>
                                                    <th>Jumlah Bobot</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($indikatorPenilaians as $indikator)
                                                    <tr>
                                                        <td>{{ $indikator->kriteriaPenilaian->nama }}</td>
                                                        <td>{{ $indikator->nama_indikator }}</td>
                                    
                                                        <td>
                                                            <select name="indikator[{{ $indikator->id }}][jumlah_bobot]" class="form-control bobot-selector" required>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <option value="{{ $i }}" {{ old('indikator.' . $indikator->id . '.jumlah_bobot', $indikator->jumlah_bobot ?? $penilaianReviewer->indikatorPenilaians()->where('id', $indikator->id)->first()->pivot->jumlah_bobot ?? '') == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    
                                        <!-- Total Bobot Display -->
                                        <div class="mt-4">
                                            <h5>Total Bobot: <span id="totalBobot">0</span></h5>
                                            <h5>Rata-rata Bobot: <span id="averageBobot">0</span></h5>
                                            <h5>Bobot Persentase: <span id="percentageBobot">0%</span></h5>
                                        </div>
                                    
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const bobotSelectors = document.querySelectorAll('.bobot-selector');
                                                const totalBobotDisplay = document.getElementById('totalBobot');
                                                const averageBobotDisplay = document.getElementById('averageBobot');
                                                const percentageBobotDisplay = document.getElementById('percentageBobot');
                                    
                                                function calculateBobot() {
                                                    let total = 0;
                                                    let count = 0;
                                    
                                                    bobotSelectors.forEach(selector => {
                                                        const value = parseInt(selector.value, 10);
                                                        if (!isNaN(value)) {
                                                            total += value;
                                                            count++;
                                                        }
                                                    });
                                    
                                                    const average = count > 0 ? (total / count).toFixed(2) : 0;
                                                    const maxTotal = count * 5;
                                                    const percentage = maxTotal > 0 ? ((total / maxTotal) * 100).toFixed(2) : 0;
                                    
                                                    totalBobotDisplay.textContent = total;
                                                    averageBobotDisplay.textContent = average;
                                                    percentageBobotDisplay.textContent = `${percentage}%`;
                                                }
                                    
                                                bobotSelectors.forEach(selector => {
                                                    selector.addEventListener('change', calculateBobot);
                                                });
                                    
                                                calculateBobot();
                                            });
                                        </script>
                                    
                                        <!-- Submit Button -->
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary">Update Penilaian</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
@endsection
