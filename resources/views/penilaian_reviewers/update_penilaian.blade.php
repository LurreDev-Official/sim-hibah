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
                                <a class="btn btn-primary" href="{{ url('review-usulan') }}">Kembali</a>
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

                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Lihat Penilaian</h4>
                
                        <!-- Penilaian Details Grouped by Kriteria -->
                        <div class="mb-4">
                            @foreach ($indikatorPenilaians->groupBy('kriteria_id') as $kriteriaId => $indikators)
                                <div class="mb-4">
                                    <h5 class="text-primary"><strong>Kriteria: {{ $indikators->first()->kriteriaPenilaian->nama }}</strong></h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">#</th>
                                                    <th>Indikator</th>
                                                    <th class="text-center" style="width: 120px;">Nilai</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($indikators as $indikator)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>{{ $indikator->nama_indikator }}</td>
                                                        <td class="text-center">{{ $penilaianReviewer->total_nilai ?? '0' }}</td>
                                                        <td>{{ $penilaianReviewer->catatan[$indikator->id] ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                
                        <!-- Total Nilai -->
                        <div class="bg-light p-3 rounded">
                            <h5 class="mb-2">Total Nilai</h5>
                            <p class="fw-bold text-primary fs-4 mb-0">{{ $penilaianReviewer->total_nilai }}</p>
                        </div>
                    </div>
                </div>
                
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
