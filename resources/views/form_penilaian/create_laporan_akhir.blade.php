@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Input Penilaian
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
                        <h4>Tampilkan PDF Laporan Akhir</h4>
                        <iframe src="{{ asset('storage/' . $laporanAkhir->dokumen_laporan_akhir) }}" width="100%" height="600px" frameborder="0"></iframe>
                    </div>
                    
                </div>

                <br>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-5">
                            <h4 class="fw-bold">Form Penilaian</h4>
                            <!-- Form Container -->
                            <div class="card">
                                <div class="card-body">
                                    <form id="penilaianForm" action="{{ route('form-penilaian.laporan-akhir.store', ['id' => $laporanAkhir->id]) }}" method="POST">
                                        @csrf
                                    
                                        <input type="hidden" name="penilaian_reviewers_id" value="{{ $penilaianReviewer->id }}">
                                    
                                        <!-- Table for Input Fields -->
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th colspan="1"> </th>
                                                    <th>Nama Indikator</th>
                                                    <th>Nilai</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $currentKriteria = null;
                                            @endphp
                                
                                            @foreach ($indikatorPenilaians as $indikator)
                                                @if ($currentKriteria !== $indikator->kriteriaPenilaian->nama)
                                                    @php
                                                        $currentKriteria = $indikator->kriteriaPenilaian->nama;
                                                    @endphp
                                                    <!-- Group Header Row for Kriteria -->
                                                    <tr>
                                                        <td colspan="4" class="bg-light font-weight-bold text-uppercase text-center">{{ $currentKriteria }}</td>
                                                    </tr>
                                                @endif
                                
                                                <!-- Display Nama Indikator, Jumlah Bobot, and Catatan -->
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $indikator->nama_indikator }}</td>
                                                    <td>
                                                        <select name="indikator[{{ $indikator->id }}][nilai]" class="form-control bobot-selector" required>
                                                            <option value="1" {{ old('indikator.' . $indikator->id . '.nilai', $indikator->nilai ?? '') == 1 ? 'selected' : '' }}>1</option>
                                                            <option value="2" {{ old('indikator.' . $indikator->id . '.nilai', $indikator->nilai ?? '') == 2 ? 'selected' : '' }}>2</option>
                                                            <option value="3" {{ old('indikator.' . $indikator->id . '.nilai', $indikator->nilai ?? '') == 3 ? 'selected' : '' }}>3</option>
                                                            <option value="4" {{ old('indikator.' . $indikator->id . '.nilai', $indikator->nilai ?? '') == 4 ? 'selected' : '' }}>4</option>
                                                            <option value="5" {{ old('indikator.' . $indikator->id . '.nilai', $indikator->nilai ?? '') == 5 ? 'selected' : '' }}>5</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea name="indikator[{{ $indikator->id }}][catatan]" class="form-control" placeholder="Catatan" style="height: 200px;" required>{{ old('indikator.' . $indikator->id . '.catatan', '') }}</textarea>   
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    
                                        <!-- Total Bobot Display -->
                                        <div class="mt-4">
                                            <h5>Nilai Review : <span id="totalBobot">0</span></h5>
                                            {{-- <h5>Rata-rata Bobot: <span id="averageBobot">0</span></h5> --}}
                                            {{-- <h5>Nilai Review: <span id="percentageBobot">0%</span></h5> --}}
                                        </div>
                                    
                                        <script>
                                            // Function to calculate total, average, and percentage bobot
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
                                                        if (!isNaN(value)) { // Ensure valid number
                                                            total += value;
                                                            count++;
                                                        }
                                                    });
                                    
                                                    const average = count > 0 ? (total / count).toFixed(2) : 0;
                                                    const maxTotal = count * 5; // Maximum possible total based on 5 per indicator
                                                    const percentage = maxTotal > 0 ? ((total / maxTotal) * 100).toFixed(2) : 0;
                                    
                                                    totalBobotDisplay.textContent = total; // Update total bobot
                                                    averageBobotDisplay.textContent = average; // Update average bobot
                                                    percentageBobotDisplay.textContent = `${percentage}%`; // Update percentage bobot
                                                }
                                    
                                                // Recalculate when a bobot value is changed
                                                bobotSelectors.forEach(selector => {
                                                    selector.addEventListener('change', calculateBobot);
                                                });
                                    
                                                // Initial calculation
                                                calculateBobot();
                                            });
                                        </script>
                                    
                                        <!-- Submit Button -->
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary">Submit Penilaian</button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
