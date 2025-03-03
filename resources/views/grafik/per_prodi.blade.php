@extends('layouts.main_layout')

<style>
    #usulanByProdiChart {
        width: 100% !important;
        max-width: 500px !important;
        height: auto !important;
        margin: 0 auto;
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
                                    <h3 class="card-title">Grafik Hibah Usulan Penelitian per Program Studi</h3>
                                     
                                </div>
                                <div class="card-body col-6">
                                    <canvas id="usulanByProdiChart" width="200" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Load Chart.js Library -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    
                    <script>
                        const ctx = document.getElementById('usulanByProdiChart').getContext('2d');
                        const usulanByProdiChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: @json($labels), // Label Prodi
                                datasets: [{
                                    label: 'Usulan Penelitian per Program Studi',
                                    data: @json($totals), // Data jumlah usulan per prodi
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                const total = tooltipItem.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                                const value = tooltipItem.raw;
                                                const percentage = ((value / total) * 100).toFixed(2);
                                                return `${tooltipItem.label}: ${value} Usulan (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
