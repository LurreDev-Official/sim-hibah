@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

         <!--begin::Toolbar-->
         <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Luaran {{ $jenis }}
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">List</small>
                    </h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <!-- Search Icon -->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!-- Search Input -->
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="myInput"
                                    placeholder="Search Luaran" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            @role('Kepala LPPM')
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <a href="{{ route('luaran.export', ['jenis' => $jenis]) }}" class="btn btn-success">
                                    <i class="fa fa-download"></i> Export Data
                                </a>
                            </div>
                            @endrole

                        </div>
                        <!--end::Card toolbar-->

                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-usulan">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">No Usulan</th>
                                    <th class="min-w-125px">Tanggal</th>
                                        <th class="min-w-150px">Judul Usulan</th>
                                        <th class="min-w-150px">Tahun</th>
                                        <th class="min-w-150px">Status</th>
                                        {{-- <th class="min-w-125px">Status</th>
                                        <th class="min-w-150px">Rumpun Ilmu</th>
                                        <th class="min-w-150px">Bidang Fokus</th>
                                        <th class="min-w-150px">Tema Penelitian</th>
                                        <th class="min-w-150px">Topik Penelitian</th>
                                        <th class="min-w-150px">Lama Kegiatan</th>
                                        <th class="min-w-150px">Ketua Dosen</th>
                                        <th class="min-w-150px">Dokumen Usulan</th> --}}
                                        <th class="min-w-150px">Aksi</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->

                                <!--begin::Table body-->
                                <tbody>
                                    @forelse($usulans as $usulan)
                                        <tr>
                                            <td>{{ $usulan->id }}</td>
                                            <td>{{ $usulan->created_at }}</td>
                                            <td>{{ $usulan->judul_usulan }}</td>
                                            <td>{{ $usulan->tahun_pelaksanaan }}</td>
                                            <td>
                                                @php
                                                    $terpenuhiCount = App\Models\Luaran::where('usulan_id', $usulan->id )
                                                        ->where('status', 'Terpenuhi')
                                                        ->count();
                                                @endphp

                                                @if ($terpenuhiCount <3)
                                                    <span class="badge badge-light-primary">Belum Terpenuhi= {{ $terpenuhiCount }}</span>
                                                @else
                                                    <span class="badge badge-light-success">Terpenuhi</span>
                                                @endif


                                            </td>
                                            {{-- 
                                            <td>{{ $usulan->rumpun_ilmu }}</td>
                                            <td>{{ $usulan->bidang_fokus }}</td>
                                            <td>{{ $usulan->tema_penelitian }}</td>
                                            <td>{{ $usulan->topik_penelitian }}</td>
                                            <td>{{ $usulan->lama_kegiatan }} tahun</td>
                                            <td>{{ $usulan->ketuaDosen->user->name }}</td>
                                            <td>
                                                @if ($usulan->dokumen_usulan)
                                                    <a href="{{ asset('storage/' . $usulan->dokumen_usulan) }}" target="_blank">Lihat Dokumen</a>
                                                @else
                                                    Tidak ada dokumen
                                                @endif
                                            </td> --}}
                                            <td>
                                                <!-- Tombol create Luaran -->
                                                <div class="col p-2">
                                                    <a href="{{ route('luaran.create', $usulan->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-info-circle"></i> Detail Luaran
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Tidak ada usulan yang tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->

                    <!--begin::Pagination-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-usulan').DataTable({
            searchable: true,
        });
    </script>
@endsection
