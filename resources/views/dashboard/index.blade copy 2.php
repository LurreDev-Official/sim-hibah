@extends('layouts.main_layout')

<style>
    #flash-notifications .toast {
        animation: flyIn 0.5s ease-out, fadeOut 0.5s 4.5s ease-out forwards;
    }

    @keyframes flyIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }
</style>
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
                                    if (Auth::user()->hasRole('Kepala LPPM')|| Auth::user()->hasRole('Admin')) {
                                        // Kepala LPPM melihat semua usulan dengan status tertentu
                                        $notifikasi = \App\Models\Usulan::all();
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
                                                            ->where('status', '=', 'belum disetujui');
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

                                <div class="row mt-4">
                                    @if ($notifikasi->isNotEmpty())
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Notifikasi Usulan Baru</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="list-group">
                                                        @foreach ($notifikasi as $notif)
                                                            <div class="list-group-item list-group-item-action mb-2 border border-2 shadow-sm">
                                                                <div class="d-flex w-100 justify-content-between">
                                                                    <h5 class="mb-1">
                                                                        @if (Auth::user()->hasRole('Kepala LPPM'))
                                                                            Notifikasi Kepala LPPM
                                                                        @elseif (Auth::user()->hasRole('Admin'))
                                                                            Notifikasi Admin
                                                                        @elseif (Auth::user()->hasRole('Dosen'))
                                                                            Notifikasi Dosen
                                                                        @elseif (Auth::user()->hasRole('Reviewer'))
                                                                            Notifikasi Reviewer
                                                                        @else
                                                                            Notifikasi
                                                                        @endif
                                                                    </h5>
                                                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                                                </div>
                                                                <p class="mb-1">
                                                                    @if (Auth::user()->hasRole('Kepala LPPM'))
                                                                        @if ($notif->status == 'submitted')
                                                                            <span class="text-info">
                                                                                Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                                                                <em>{{ $notif->status }}</em> menunggu diteruskan ke reviewer.
                                                                            </span>
                                                                        </button>
                                                                        @elseif ($notif->status == 'waiting approved')
                                                                            <span class="text-warning">
                                                                                Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                                                                <em>{{ $notif->status }}</em> menunggu persetujuan.
                                                                            </span>
                                                                        </button>
                                                                        @endif
                                                                    @elseif (Auth::user()->hasRole('Dosen'))
                                                                        @if ($notif->status == 'draft')
                                                                            <span class="text-danger">
                                                                                Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                                                                <em>{{ $notif->status }}</em> belum disetujui.
                                                                            </span>
                                                                            <button class="btn btn-info btn-sm mt-2"
                                                                                onclick="showDetailUsulan('{{ $notif->jenis_skema }}', {{ $notif->id }})">
                                                                                <i class="fas fa-eye"></i> Detail
                                                                            </button>
                                                                        @elseif ($notif->status == 'revision')
                                                                            <span class="text-warning">
                                                                                Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                                                                <em>{{ $notif->status }}</em> menunggu revisi.
                                                                            </span>
                                                                            <a href="{{ route('usulan.perbaikiRevisi', ['jenis' => $notif->jenis_skema, 'id' => $notif->id]) }}"
                                                                                class="btn btn-secondary btn-sm mt-2">
                                                                                <i class="fas fa-edit"></i> Perbaiki Revisi
                                                                            </a>
                                                                        @endif
                                                                    @elseif (Auth::user()->hasRole('Reviewer'))
                                                                        <span class="text-primary">
                                                                            Usulan <strong>{{ $notif->judul_usulan }}</strong> menunggu ulasan Anda.
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    

                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <p class="text-muted">Tidak ada notifikasi saat ini.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <script>
                                    function showDetailUsulan(jenis, id) {
                                        const url = `/detail-usulan/${jenis}/${id}`;
                                        window.location.href = url;
                                    }
                                </script>

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
                @role('Dosen')
                    <div class="row mb-4 pt-5">
                        <div class="col-xl-4">
                            <!--begin::List Widget 4-->
                            <div class="card card-xl-stretch mb-xl-8">
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark">Kuota Pengajuan</span>
                                        <span class="text-muted mt-1 fw-bold fs-7">Kuota dan Jumlah Proposal</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <!-- Tombol Modal Aturan dengan btn-info -->
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalAturan">
                                            <!-- Icon untuk tombol modal -->
                                            <span class="svg-icon svg-icon-2 me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="5" y="5" width="5" height="5" rx="1"
                                                            fill="#000000"></rect>
                                                        <rect x="14" y="5" width="5" height="5" rx="1"
                                                            fill="#000000" opacity="0.3"></rect>
                                                        <rect x="5" y="14" width="5" height="5" rx="1"
                                                            fill="#000000" opacity="0.3"></rect>
                                                        <rect x="14" y="14" width="5" height="5" rx="1"
                                                            fill="#000000" opacity="0.3"></rect>
                                                    </g>
                                                </svg>
                                            </span>
                                            Aturan Penelitian
                                        </button>
                                    </div>
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card-body pt-5">
                                    <!-- Kuota Pengajuan -->
                                    <div class="d-flex align-items-sm-center mb-7">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-50px me-5">
                                            <span class="symbol-label">
                                                <img src="assets/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center"
                                                    alt="Icon Kuota">
                                            </span>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Section-->
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bolder">Kuota
                                                    Proposal</a>
                                                <span class="text-muted fw-bold d-block fs-7">Kuota Proposal:
                                                    {{ $dosenData->kuota_proposal }}</span>
                                            </div>
                                            <span class="badge badge-light fw-bolder my-2">Jumlah Proposal:
                                                {{ $dosenData->jumlah_proposal }}</span>
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::List Widget 4-->
                        </div>

                        <!-- Modal Aturan -->
                        <div class="modal fade" id="modalAturan" tabindex="-1" aria-labelledby="modalAturanLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalAturanLabel">Ketentuan Umum Penelitian Mono Tahun
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>1. Biodata Dosen (NIDN):</h6>
                                        <ul>
                                            dalam 1 jenis_skema
                                            <li>boleh menjadi ketua 1x judul usulan proposal</li>
                                            <li>boleh anggota 1x judul usulan </li>
                                            dan jika dibawah score 200
                                            <li>boleh menjadi ketua 2x judul usulan proposal</li>
                                        </ul>

                                        <h6>2. Jumlah Anggota Pengusul (7 orang):</h6>
                                        <ul>
                                            <li>5 Orang dosen se prodi unhasy</li>
                                            <li>1 Orang dosen luar prodi unhasy</li>
                                            <li>1 Orang dosen mitra unhasy</li>
                                        </ul>

                                        <h6>3. Biodata Mahasiswa (NIM):</h6>
                                        <ul>
                                            <li>1 Orang Mahasiswa Semester 5 &gt;</li>
                                            <li>2 Orang Mahasiswa Semester &lt;3</li>
                                        </ul>

                                        <h6>4. Jumlah Anggota Mahasiswa (3 orang):</h6>
                                        <ul>
                                            <li>5 Orang dosen se prodi unhasy</li>
                                        </ul>

                                        <h6>5. Skoring SINTA 200&gt; Menjadi ketua pengusul (pengembangan kedepan tahun 2026):
                                        </h6>

                                        <h6>6. Luaran Wajib, sesuai Peraturan Rektor:</h6>
                                        <h7>Penelitian</h7>
                                        <ul>
                                            <li>• Laporan akhir penelitian</li>
                                            <li>• Artikel ilmiah di jurnal terakreditasi minimal SINTA 3 atau SINTA 4</li>
                                            <li>• Artikel ilmiah di prosiding SAINSTEKNOPAK</li>
                                        </ul>

                                        <h7>Pengabdian</h7>
                                        <ul>
                                            <li>• Laporan akhir pengabdian kepada masyarakat</li>
                                            <li>• Artikel ilmiah di jurnal terakreditasi minimal SINTA 3 atau SINTA 4</li>
                                            <li>• Artikel ilmiah di prosiding SAINSTEKNOPAK</li>
                                        </ul>

                                        <h6>Luaran tambahan:</h6>
                                        <ul>
                                            <li>• Buku</li>
                                            <li>• HKI</li>
                                            <li>• Prototipe, Model, Produk, dll.</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole

                @role('Kepala LPPM')
                    <div class="row ">
                        <!-- Proses Penelitian -->
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
                    <!-- Proses Pengabdian -->
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
                @endrole


                <div class="row">
                    <div class="col-12">
                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title">Grafik chart Hibah Usulan Penelitian per Fakultas</h3>
                            </div>
                            <div class="card-body">
                                 <!-- Canvas untuk Pie Chart -->
                        <canvas id="usulanChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>


                    <!-- noftif flas ajax -->
        {{-- <div id="flash-notifications" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            @foreach ($notifikasi as $notif)
                <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="toast-header">
                        @if (Auth::user()->hasRole('Kepala LPPM'))
                            <strong class="me-auto text-primary">Notifikasi Kepala LPPM</strong>
                        @elseif (Auth::user()->hasRole('Dosen'))
                            <strong class="me-auto text-info">Notifikasi Dosen</strong>
                        @elseif (Auth::user()->hasRole('Reviewer'))
                            <strong class="me-auto text-success">Notifikasi Reviewer</strong>
                        @else
                            <strong class="me-auto">Notifikasi</strong>
                        @endif
                        <small>Baru</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        @if (Auth::user()->hasRole('Kepala LPPM'))
                            @if ($notif->status == 'submitted')
                                <span class="text-info">
                                    Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                    <em>{{ $notif->status }}</em> menunggu diteruskan ke reviewer.
                                </span>
                            @elseif ($notif->status == 'waiting approved')
                                <span class="text-warning">
                                    Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                    <em>{{ $notif->status }}</em> menunggu persetujuan.
                                </span>
                            @endif
                        @elseif (Auth::user()->hasRole('Dosen'))
                            @if ($notif->status == 'draft')
                                <span class="text-danger">
                                    Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                    <em>{{ $notif->status }}</em> belum disetujui.
                                </span>
                                <button class="btn btn-info btn-sm mt-2"
                                    onclick="showDetailUsulan('{{ $notif->jenis_skema }}', {{ $notif->id }})">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            @elseif ($notif->status == 'revision')
                                <span class="text-warning">
                                    Usulan <strong>{{ $notif->judul_usulan }}</strong> dengan status 
                                    <em>{{ $notif->status }}</em> menunggu revisi.
                                </span>
                                <a href="{{ route('usulan.perbaikiRevisi', ['jenis' => $notif->jenis_skema, 'id' => $notif->id]) }}"
                                    class="btn btn-secondary btn-sm mt-2">
                                    <i class="fas fa-edit"></i> Perbaiki Revisi
                                </a>
                            @endif
                        @elseif (Auth::user()->hasRole('Reviewer'))
                            <span class="text-primary">
                                Usulan <strong>{{ $notif->judul_usulan }}</strong> menunggu ulasan Anda.
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const toastElements = document.querySelectorAll('.toast');
                    toastElements.forEach(function (toastEl) {
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
                    });
                });
            </script>
            
            
        </div> --}}


    @endsection
<!-- Include JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
@stack('scripts')

@stack('scripts')
    <script>
        // Chart.js Pie Chart Configuration
        const ctx = document.getElementById('usulanChart').getContext('2d');
        const usulanChart = new Chart(ctx, {
            const usulanChart = new Chart(ctx, {
            type: 'pie', // Jenis grafik pie
            data: {
                labels: ['Fakultas Agama Islam', 'Fakultas Teknik', 'Fakultas Teknologi Informasi', 'Fakultas Ekonomi', 'Fakultas Ilmu Pendidikan'],
                datasets: [{
                    label: 'Usulan Penelitian',
                    data: [15, 25, 30, 10, 20], // Data dummy untuk setiap fakultas
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
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' Usulan';
                            }
                        }
                    }
                }
            }
        });
    </script>