@extends('layouts.main_layout')

<style>
    #usulanByProdiChartWrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        padding-bottom: 10px; /* Spasi bawah agar nyaman dilihat */
    }

    #usulanByProdiChart {
        width: 100% !important;
        height: auto !important;
        margin: 0 auto;
    }

    .card-body {
        padding: 1rem;
    }
</style>

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Dashboard
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">
                        {{ Auth::user()->name }}
                    </small>
                </h1>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title">Grafik Penerima Hibah Berdasarkan Prodi</h3>

                                <!-- Form Filter Tahun -->
                                <form method="POST" action="{{ route('grafik-penerima-hibah.index') }}" class="d-flex align-items-center">
                                    @csrf
                                    <label for="tahun" class="me-2">Filter Tahun:</label>
                                    <select name="tahun" id="tahun" class="form-control w-25">
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 2;  // Start year: 2 years before the current year
                                            $endYear = $currentYear + 2;    // End year: 2 years after the current year
                                        @endphp
                                        
                                        @for ($year = $startYear; $year <= $endYear; $year++)
                                            <option value="{{ $year }}" {{ isset($tahun) && $tahun == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-primary ms-2">Terapkan</button>
                                </form>
                                
                            </div>

                            <!-- Grafik Batang -->
                            <div class="card-body">
                                <div id="usulanByProdiChartWrapper">
                                    <canvas id="usulanByProdiChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari controller
    const labels = {!! json_encode($labels) !!};
    const totals = {!! json_encode($totals) !!};

    // Inisialisasi grafik
    const ctx = document.getElementById('usulanByProdiChart').getContext('2d');
    const usulanByProdiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Usulan',
                data: totals,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // Hanya menampilkan bilangan bulat
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false, // Memastikan label tidak terlewat
                        maxRotation: 45, // Menyediakan rotasi untuk label jika terlalu panjang
                        minRotation: 45
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endsection
