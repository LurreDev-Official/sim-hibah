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
                                Terima kasih telah berkontribusi sebagai reviewer. Anda dapat memulai tugas peninjauan Anda dari
                                dashboard ini.
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
                                        </h3>
                                        <div class="card-toolbar">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item">
                                                    <a class="nav-link btn btn-sm btn-light fw-bolder px-4 me-1 active"
                                                        data-bs-toggle="tab" href="#kt_table_widget_5_tab_1">Usulan Baru</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link btn btn-sm btn-light fw-bolder px-4 me-1"
                                                        data-bs-toggle="tab" href="#kt_table_widget_5_tab_2">Laporan
                                                        Kemajuan</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link btn btn-sm btn-light fw-bolder px-4"
                                                        data-bs-toggle="tab" href="#kt_table_widget_5_tab_3">Laporan
                                                        Akhir</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div class="card-body py-3">
                                        <div class="tab-content">
                                            <!--begin::Tab Pane Usulan-->
                                            <div class="tab-pane fade show active" id="kt_table_widget_5_tab_1">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover align-middle">
                                                        <thead>
                                                            <tr class="text-muted fw-bolder">
                                                                <th>No</th>
                                                                <th>Judul Usulan</th>
                                                                <th>Jenis Skema</th>
                                                                <th>Status Penilaian</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($notifreview as $key => $penilaian)
                                                                @if ($penilaian->usulan)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $penilaian->usulan->judul_usulan }}</td>
                                                                        <td>{{ $penilaian->usulan->jenis_skema }}</td>
                                                                        <td>
                                                                            <span
                                                                                class="badge badge-light-primary">{{ $penilaian->status_penilaian }}</span>
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('form-penilaian.input', ['id' => $penilaian->usulan->id]) }}"
                                                                                class="btn btn-sm btn-primary">Review</a>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">Tidak ada usulan
                                                                        untuk dinilai</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--end::Tab Pane Usulan-->

                                            <!--begin::Tab Pane Laporan Kemajuan-->
                                            <div class="tab-pane fade" id="kt_table_widget_5_tab_2">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover align-middle">
                                                        <thead>
                                                            <tr class="text-muted fw-bolder">
                                                                <th>No</th>
                                                                <th>Judul Usulan</th>
                                                                <th>Jenis Skema</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($notifreview as $key => $penilaian)
                                                                @if ($penilaian->laporankemajuan)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $penilaian->laporankemajuan->usulan->judul_usulan }}</td>
                                                                        <td>{{ $penilaian->laporankemajuan->usulan->jenis_skema }}</td>
                                                                        <td>
                                                                            <span
                                                                                class="badge badge-light-primary">{{ $penilaian->status_penilaian }}</span>
                                                                        </td>

                                                                        <td>
                                                                         
                                                                            <a href="{{ route('form-penilaian.laporan-kemajuan', $penilaian->laporankemajuan_id) }}"
                                                                                class="btn btn-sm btn-primary">Review</a>
                                                                        </td>

                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <td colspan="5" class="text-center text-muted">
                                                                            Tidak ada laporan kemajuan untuk direview.</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--end::Tab Pane Laporan Kemajuan-->

                                            <!--begin::Tab Pane Laporan Akhir-->
                                            <div class="tab-pane fade" id="kt_table_widget_5_tab_3">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover align-middle">
                                                        <thead>
                                                            <tr class="text-muted fw-bolder">
                                                                <th>No</th>
                                                                <th>Judul Usulan</th>
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($notifreview as $key => $penilaian)
                                                                @if ($penilaian->laporanakhir)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $penilaian->laporanakhir->usulan->judul_usulan }}
                                                                        </td>
                                                                        <td>
                                                                            <span
                                                                                class="badge badge-light-primary">{{ $penilaian->laporanakhir->status }}</span>
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('form-penilaian.laporan-akhir', $penilaian->laporanakhir_id) }}"
                                                                                class="btn btn-sm btn-primary">Review</a>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @if ($notifreview->where('laporanakhir', null)->isEmpty())
                                                                <tr>
                                                                    <td colspan="5" class="text-center text-muted">Tidak
                                                                        ada laporan akhir untuk direview.</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--end::Tab Pane Laporan Akhir-->
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
