@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" 
                     data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" 
                     class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                        Dashboard
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">
                            {{ Auth::user()->name }}
                        </small>
                        <!--end::Description-->
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Main content-->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Tampilkan pesan selamat datang jika user memiliki role 'Reviewer' -->
                    @role('Reviewer')
                        <div class="alert alert-success">
                            <h4 class="alert-heading">Selamat Datang, Reviewer!</h4>
                            <p>
                                Terima kasih telah berkontribusi sebagai reviewer. Anda dapat memulai tugas peninjauan Anda dari dashboard ini.
                            </p>
                        </div>
                    @endrole
                    
                    <!-- Tampilkan konten dashboard lainnya -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Notifikasi </h3>
                        </div>
                        <div class="card-body">
                            <div class="col-xxl-8">
                                <!--begin::Tables Widget 5-->
                                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                                    <!--begin::Header-->
                                    <div class="card-header border-0 pt-5">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder fs-3 mb-1">Tugas Review Baru</span>
                                            {{-- <span class="text-muted mt-1 fw-bold fs-7">More than 400 new products</span> --}}
                                        </h3>
                                        <div class="card-toolbar">
                                            <ul class="nav">
                                                <li class="nav-item">
                                                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-dark active fw-bolder px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_5_tab_1">Usulan Baru</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-dark fw-bolder px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_5_tab_2">Laporan Kemajuan</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-dark fw-bolder px-4" data-bs-toggle="tab" href="#kt_table_widget_5_tab_3">Laporan Akhir</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body py-3">
                                        <div class="tab-content">
                                            <!--begin::Tap pane-->
                                            <div class="tab-pane fade show active" id="kt_table_widget_5_tab_1">
                                                <!--begin::Table container-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <tr class="text-muted fw-bolder">
                                                                <th>No</th>
                                                                <th>Judul Usulan</th>
                                                                <th>Jenis Skema</th>
                                                                <th>Status Penilaian</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody>
                                                            @foreach ($notifusulan as $key => $usulan)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $usulan->usulan->judul_usulan }}</td>
                                                                <td>{{ $usulan->usulan->jenis_skema }}</td>
                                                                <td>
                                                                    <span class="badge badge-light-primary">{{ $usulan->status_penilaian }}</span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('form-penilaian.input', ['usulan_id' => $usulan->usulan->id]) }}" 
                                                                       class="btn btn-sm btn-primary">
                                                                        Review
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            
                                            <!--end::Tap pane-->
                                            <!--begin::Tap pane-->
                                            <div class="tab-pane fade" id="kt_table_widget_5_tab_2">
                                                <!--begin::Table container-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <tr class="text-muted fw-bolder">
                                                                <th>No</th>
                                                                <th>Judul Usulan</th>
                                                                <th>Ketua Dosen</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody>
                                                            @foreach ($notifLaporanKemajuan as $key => $laporan)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $laporan->usulan->judul_usulan }}</td>
                                                                <td>{{ $laporan->dosen->nama ?? 'Tidak Diketahui' }}</td>
                                                                <td>
                                                                    <span class="badge badge-light-primary">{{ $laporan->status }}</span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('laporan-kemajuan.review', ['laporan_id' => $laporan->id]) }}" 
                                                                       class="btn btn-sm btn-primary">
                                                                        Review
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @if ($notifLaporanKemajuan->isEmpty())
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted">Tidak ada laporan kemajuan untuk direview.</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            
                                            <!--end::Tap pane-->
                                            <!--begin::Tap pane-->
                                            <div class="tab-pane fade" id="kt_table_widget_5_tab_3">
                                                <!--begin::Table container-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                                        <!--begin::Table head-->
                                                        <thead>
                                                            <tr class="text-muted fw-bolder">
                                                                <th>No</th>
                                                                <th>Judul Usulan</th>
                                                                <th>Ketua Dosen</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <!--end::Table head-->
                                                        <!--begin::Table body-->
                                                        <tbody>
                                                            @foreach ($notifLaporanAkhir as $key => $laporan)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $laporan->usulan->judul_usulan }}</td>
                                                                <td>{{ $laporan->dosen->nama ?? 'Tidak Diketahui' }}</td>
                                                                <td>
                                                                    <span class="badge badge-light-primary">{{ $laporan->status }}</span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('laporan-akhir.review', ['laporan_id' => $laporan->id]) }}" 
                                                                       class="btn btn-sm btn-primary">
                                                                        Review
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @if ($notifLaporanAkhir->isEmpty())
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted">Tidak ada laporan akhir untuk direview.</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table container-->
                                            </div>
                                            
                                            <!--end::Tap pane-->
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Tables Widget 5-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Main content-->
    </div>
@endsection
