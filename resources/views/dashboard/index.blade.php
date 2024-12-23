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

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <h1>Dashboard</h1>

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                {{-- Role-based Greetings --}}
                                @role('Kepala LPPM')
                                    <p>Selamat datang, Kepala LPPM {{ Auth::user()->name }}!</p>
                                @endrole

                                @role('Dosen')
                                    <p>Selamat datang, Dosen {{ Auth::user()->name }}!</p>
                                @endrole

                                @role('Reviewer')
                                    <p>Selamat datang, Reviewer {{ Auth::user()->name }}!</p>
                                @endrole
                            </div>


                            {{-- Card Notifikasi --}}
                            <div class="row mt-4">
                                @php
                                    $notifikasi = [];

                                    // Logika berdasarkan role
                                    if (Auth::user()->hasRole('Kepala LPPM')) {
                                        // Kepala LPPM melihat semua usulan dengan status tertentu
                                        $notifikasi = \App\Models\Usulan::where('status', 'Submitted')->get();
                                    } elseif (Auth::user()->hasRole('Dosen')) {
                                        // Ambil dosen terkait user login
                                        $dosen = \App\Models\Dosen::where('user_id', Auth::id())->first();

                                        if ($dosen) {
                                            // Dosen melihat usulan di mana dia sebagai ketua atau anggota dengan status belum disetujui
                                            $notifikasi = \App\Models\Usulan::where('ketua_dosen_id', $dosen->id)
                                                ->orWhereHas('anggotaDosen', function ($query) use ($dosen) {
                                                    $query
                                                        ->where('dosen_id', $dosen->id)
                                                        ->where('status', 'belum disetujui');
                                                })
                                                ->with([
                                                    'anggotaDosen' => function ($query) use ($dosen) {
                                                        $query
                                                            ->where('dosen_id', $dosen->id)
                                                            ->where('status', 'belum disetujui');
                                                    },
                                                ])
                                                ->get();
                                        }
                                    } elseif (Auth::user()->hasRole('Reviewer')) {
                                        // Ambil reviewer terkait user yang sedang login
                                        $reviewer = \App\Models\Reviewer::where('user_id', Auth::id())->first();

                                        if ($reviewer) {
                                            // Reviewer melihat usulan yang berstatus Pending dan terkait dengan reviewer tersebut
                                            $notifikasi = \App\Models\PenilaianReviewer::where(
                                                'reviewer_id',
                                                $reviewer->id,
                                            )
                                                ->where('status_penilaian', 'Pending')
                                                ->with('usulan') // Pastikan relasi 'usulan' didefinisikan di PenilaianReviewer
                                                ->get();
                                        } else {
                                            $notifikasi = collect(); // Kosongkan notifikasi jika reviewer tidak ditemukan
                                        }
                                    }
                                @endphp

                                @if (count($notifikasi) > 0)
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Notifikasi</h4>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group">
                                                    @foreach ($notifikasi as $notif)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                @if (Auth::user()->hasRole('Kepala LPPM'))
                                                                    Usulan <strong>{{ $notif->judul_usulan }}</strong>
                                                                    dengan status <em>{{ $notif->status }}</em> menunggu
                                                                    diteruskan ke reviewer.
                                                                @elseif (Auth::user()->hasRole('Dosen'))
                                                                    Usulan <strong>{{ $notif->judul_usulan }}</strong>
                                                                    dengan status <em>{{ $notif->status }}</em> belum
                                                                    disetujui.
                                                                    <button class="btn btn-info btn-sm"
                                                                        onclick="showDetailUsulan('{{ $notif->jenis_skema }}', {{ $notif->id }})">
                                                                        <i class="fas fa-eye"></i> Detail
                                                                    </button>

                                                                    </button>
                                                                @elseif (Auth::user()->hasRole('Reviewer'))
                                                                    Usulan <strong>{{ $notif->judul_usulan }}</strong>
                                                                    menunggu ulasan Anda.
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <p>Tidak ada notifikasi saat ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <script>
                                function showDetailUsulan(jenis, id) {
                                    // Navigasi ke URL detail usulan dengan parameter jenis dan id
                                    const url = `/detail-usulan/${jenis}/${id}`;
                                    window.location.href = url;
                                }
                            </script>


                        </div>


                    </div>
                </div>





                <div class="row">
                    <div class="col-12">
                        <h3>Proses Penelitian</h3>
                    </div>

                    {{-- Card Usulan --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Usulan</h5>
                                    <span class="badge bg-success">{{ $countUsulan }}</span>
                                </div>
                                <p>Jumlah usulan penelitian yang sudah selesai.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Perbaikan Usulan --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Perbaikan Usulan</h5>
                                    <span class="badge bg-warning">{{ $countPerbaikanUsulan }}</span>
                                </div>
                                <p>Jumlah usulan yang perlu diperbaiki.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Laporan Kemajuan --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Laporan Kemajuan</h5>
                                    <span class="badge bg-info">{{ $countLaporanKemajuan }}</span>
                                </div>
                                <p>Jumlah laporan kemajuan penelitian yang sudah selesai.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Laporan Akhir --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Laporan Akhir</h5>
                                    <span class="badge bg-warning">{{ $countLaporanAkhir }}</span>
                                </div>
                                <p>Jumlah laporan akhir penelitian yang sudah selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Counter for Community Service Process --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <h3>Proses Pengabdian</h3>
                    </div>

                    {{-- Card Usulan Pengabdian --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Usulan Pengabdian</h5>
                                    <span class="badge bg-success">{{ $countUsulanPengabdian }}</span>
                                </div>
                                <p>Jumlah usulan pengabdian yang sudah selesai.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Perbaikan Usulan Pengabdian --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Perbaikan Usulan Pengabdian</h5>
                                    <span class="badge bg-warning">{{ $countPerbaikanUsulanPengabdian }}</span>
                                </div>
                                <p>Jumlah usulan pengabdian yang perlu diperbaiki.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Laporan Kemajuan Pengabdian --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Laporan Kemajuan Pengabdian</h5>
                                    <span class="badge bg-info">{{ $countLaporanKemajuanPengabdian }}</span>
                                </div>
                                <p>Jumlah laporan kemajuan pengabdian yang sudah selesai.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Laporan Akhir Pengabdian --}}
                    <div class="col-sm-3">
                        <div class="card card-dashed">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Laporan Akhir Pengabdian</h5>
                                    <span class="badge bg-warning">{{ $countLaporanAkhirPengabdian }}</span>
                                </div>
                                <p>Jumlah laporan akhir pengabdian yang sudah selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
    <!--end::Post-->
    </div>
@endsection
