<div class="aside-menu flex-column-fluid">
    <!--begin::Aside Menu-->
    <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
        <!--begin::Menu-->
        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
            <!-- Main Menu Header -->
            <div class="menu-item">
                <div class="menu-content pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">LAUNCHER</span>
                </div>
            </div>

            <!-- Dashboard Menu -->
            <div class="menu-item">
                <a class="menu-link active" href="{{ url('') }}">
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>

            <!-- Menu Profil -->
            <div class="menu-item">
                <div class="menu-content pt-8 pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">KELOLA DATA</span>
                </div>
            </div>

            <!-- Menu Kelola Users untuk Role "Kepala LPPM" -->
            @role('Kepala LPPM')
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="black" />
                                <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="black" />
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">Kelola Users</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('users.index') }}">
                            <span class="menu-bullet">
                                <i class="align-middle" data-feather="list"></i>
                            </span>
                            <span class="menu-title">Daftar Users</span>
                        </a>
                    </div>
                </div>
                <div class="menu-sub menu-sub-accordion">
                    <!-- Menu item for Kriteria Penilaian -->
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('kriteria-penilaian.index') }}">
                            <span class="menu-bullet">
                                <i class="align-middle" data-feather="check-square"></i>
                            </span>
                            <span class="menu-title">Kriteria Penilaian</span>
                        </a>
                    </div>
                
                    <!-- Menu item for Indikator Penilaian -->
                    <div class="menu-item">
                        <a class="menu-link" href="{{ route('indikator-penilaian.index') }}">
                            <span class="menu-bullet">
                                <i class="align-middle" data-feather="bar-chart"></i>
                            </span>
                            <span class="menu-title">Indikator Penilaian</span>
                        </a>
                    </div>
                </div>

                
            </div>

                <!-- Menu Penelitian -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-card-checklist"></i>
                            </span>
                        </span>
                        <span class="menu-title">Penelitian</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('usulan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="file-text"></i>
                                </span>
                                <span class="menu-title">Usulan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('perbaikan-usulan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="edit"></i>
                                </span>
                                <span class="menu-title">Perbaikan Usulan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('laporan-kemajuan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="bar-chart"></i>
                                </span>
                                <span class="menu-title">Laporan Kemajuan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('laporan-akhir/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="check-square"></i>
                                </span>
                                <span class="menu-title">Laporan Akhir</span>
                            </a>
                        </div>
                    </div>
                </div>


            @endrole

            @role('Dosen')
                <!-- Menu Penelitian -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-card-checklist"></i>
                            </span>
                        </span>
                        <span class="menu-title">Penelitian</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('usulan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="file-text"></i>
                                </span>
                                <span class="menu-title">Usulan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('perbaikan-usulan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="edit"></i>
                                </span>
                                <span class="menu-title">Perbaikan Usulan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('laporan-kemajuan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="bar-chart"></i>
                                </span>
                                <span class="menu-title">Laporan Kemajuan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('laporan-akhir/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="check-square"></i>
                                </span>
                                <span class="menu-title">Laporan Akhir</span>
                            </a>
                        </div>
                    </div>
                </div>
                

            @endrole

            @role('Reviewer')

<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-card-checklist"></i>
                            </span>
                        </span>
                        <span class="menu-title">Review</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('penilaian-usulan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="file-text"></i>
                                </span>
                                <span class="menu-title">Usulan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('review-laporan-kemajuan/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="bar-chart"></i>
                                </span>
                                <span class="menu-title">Laporan Kemajuan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ url('review-laporan-akhir/penelitian') }}">
                                <span class="menu-bullet">
                                    <i class="align-middle" data-feather="check-square"></i>
                                </span>
                                <span class="menu-title">Laporan Akhir</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole

            
            
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Aside Menu-->
</div>
