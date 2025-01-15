@extends('layouts.main_layout')

<style>
    #usulanChart {
        width: 100% !important;
        max-width: 300px !important; /* Maksimal ukuran lebar */
        height: auto !important;
        margin: 0 auto;
    }
</style>


@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
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
        <!--end::Toolbar-->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-xl-stretch mb-5 mb-xl-8">
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title">Grafik Hibah Usulan Penelitian per Program Studi</h3>
                                    <!-- Filter Fakultas -->
                                    <div class="d-flex justify-content-between">
                                        <select id="fakultasFilter" class="form-select" style="width: 200px;">
                                            <option value="">Pilih Fakultas</option>
                                            <option value="Fakultas Agama Islam">Fakultas Agama Islam</option>
                                            <option value="Fakultas Teknik">Fakultas Teknik</option>
                                            <option value="Fakultas Teknologi Informasi">Fakultas Teknologi Informasi</option>
                                            <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                                            <option value="Fakultas Ilmu Pendidikan">Fakultas Ilmu Pendidikan</option>
                                        </select>
                                    </div>
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
                        // Data dummy per fakultas dan prodi
                        const dataByProdi = {
                            "Fakultas Agama Islam": {
                                labels: ['Prodi Pendidikan Agama Islam', 'Prodi Hukum Islam', 'Prodi Tarbiyah'],
                                data: [10, 15, 5],
                            },
                            "Fakultas Teknik": {
                                labels: ['Prodi Teknik Sipil', 'Prodi Teknik Mesin', 'Prodi Teknik Elektro'],
                                data: [20, 25, 10],
                            },
                            "Fakultas Teknologi Informasi": {
                                labels: ['Prodi Teknik Informatika', 'Prodi Sistem Informasi'],
                                data: [35, 15],
                            },
                            "Fakultas Ekonomi": {
                                labels: ['Prodi Ekonomi Pembangunan', 'Prodi Manajemen', 'Prodi Akuntansi'],
                                data: [10, 15, 10],
                            },
                            "Fakultas Ilmu Pendidikan": {
                                labels: ['Prodi Pendidikan Guru Sekolah Dasar', 'Prodi Pendidikan Luar Biasa'],
                                data: [5, 5],
                            }
                        };
                    
                        // Inisialisasi chart
                        const ctx = document.getElementById('usulanByProdiChart').getContext('2d');
                        let usulanByProdiChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: [],
                                datasets: [{
                                    label: 'Usulan Penelitian per Program Studi',
                                    data: [],
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
                    
                        // Fungsi untuk mengupdate chart berdasarkan fakultas yang dipilih
                        document.getElementById('fakultasFilter').addEventListener('change', function () {
                            const fakultas = this.value;
                            if (fakultas && dataByProdi[fakultas]) {
                                const data = dataByProdi[fakultas];
                                usulanByProdiChart.data.labels = data.labels;
                                usulanByProdiChart.data.datasets[0].data = data.data;
                                usulanByProdiChart.update();
                            }
                        });
                    
                        // Panggil perubahan fakultas pertama kali untuk menampilkan data default (misalnya Fakultas Agama Islam)
                        document.getElementById('fakultasFilter').value = "Fakultas Agama Islam"; // Set default value
                        const fakultasDefault = document.getElementById('fakultasFilter').value;
                        if (fakultasDefault && dataByProdi[fakultasDefault]) {
                            const data = dataByProdi[fakultasDefault];
                            usulanByProdiChart.data.labels = data.labels;
                            usulanByProdiChart.data.datasets[0].data = data.data;
                            usulanByProdiChart.update();
                        }
                    </script>
                    
                </div>
            </div>
        </div>
    </div>

     
@endsection
