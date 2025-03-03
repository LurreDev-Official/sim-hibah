@extends('layouts.main_layout')

<style>
    .chart-container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        margin-bottom: 50px;
    }
</style>

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
     <!--begin::Toolbar-->
     <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Grafik Hibah Usulan</h1>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            @foreach ($formattedData as $fakultasName => $prodiData)
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title">Grafik Hibah Usulan Penelitian - Fakultas {{ $fakultasName }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chart-{{ Str::slug($fakultasName) }}" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Load Chart.js Library -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    const ctx = document.getElementById('chart-{{ Str::slug($fakultasName) }}').getContext('2d');
                    const labels = {!! json_encode(array_column($prodiData, 'prodi')) !!};
                    const totals = {!! json_encode(array_column($prodiData, 'total')) !!};

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Usulan Penelitian',
                                data: totals,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 206, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)'
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
                                            const value = tooltipItem.raw;
                                            return `${tooltipItem.label}: ${value} Usulan`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Usulan'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Program Studi'
                                    }
                                }
                            }
                        }
                    });
                </script>
            @endforeach
        </div>
    </div>
</div>
@endsection