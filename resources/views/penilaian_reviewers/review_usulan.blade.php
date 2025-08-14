@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Daftar Usulan</small>
                    </h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                                    </svg>
                                </span>
                                <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Cari usulan..." />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                                        </svg>
                                    </span>
                                    Filter
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->
                                    <!--begin::Content-->
                                    <div class="px-7 py-5" data-kt-table-filter="form">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-bold">Status:</label>
                                            <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-table-filter="status">
                                                <option></option>
                                                <option value="Pending">Pending</option>
                                                <option value="sudah dinilai">Sudah Dinilai</option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-table-filter="reset">Reset</button>
                                            <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-table-filter="filter">Apply</button>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Filter-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-usulans">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">No</th>
                                        <th class="min-w-150px">Nama Ketua Dosen</th>
                                        <th class="min-w-250px">Judul Usulan</th>
                                        <th class="min-w-150px">Proposal Title</th>
                                        <th class="min-w-100px">Skeme Penelitian</th>
                                        <th class="min-w-100px">Status Review</th>
                                        <th class="min-w-100px">Total Nilai</th>
                                        <th class="min-w-150px">Detail Perbaikan</th>
                                        <th class="min-w-150px">Actions</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold">
                                    @foreach ($getpenilaianreview as $key => $usulan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $usulan->ketuaDosen->user->name }}</td>
                                            <td>{{ $usulan->usulan->judul_usulan }}</td>
                                            <td>{{ $usulan->usulan->jenis_skema }}</td>
                                            
                                            <td>
                                                <span class="badge badge-light-primary">{{ $usulan->status_penilaian }}</span>
                                            </td>
                                            <td>
                                                {{ $usulan->total_nilai }}
                                            </td>
                                            <td>
                                                @if ($usulan->status_penilaian == 'sudah dinilai')
                                                    @php
                                                        $usulanPerbaikan = $usulanPerbaikans
                                                            ->where('usulan_id', $usulan->usulan_id)
                                                            ->first();
                                                    @endphp

                                                    @if ($usulanPerbaikan && $usulanPerbaikan->dokumen_usulan !== null)
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#perbaikanModal{{ $usulan->usulan_id }}">
                                                            Lihat Perbaikan
                                                        </button>
                                                    @else
                                                        <span class="text-muted">Belum Ada Perbaikan</span>
                                                    @endif
                                                @endif
                                            </td>

                                            <!-- Modal for Perbaikan -->
                                            @php
                                                $usulanPerbaikan = $usulanPerbaikans->firstWhere(
                                                    'usulan_id',
                                                    $usulan->usulan_id,
                                                );
                                            @endphp

                                            @if ($usulanPerbaikan)
                                                <div class="modal fade" id="perbaikanModal{{ $usulan->usulan_id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="perbaikanModalLabel{{ $usulan->usulan_id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="perbaikanModalLabel{{ $usulan->usulan_id }}">
                                                                    Detail Perbaikan: {{ $usulan->usulan->judul_usulan }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Displaying the uploaded file (Perbaikan) -->
                                                                <iframe
                                                                    src="{{ asset('storage/' . $usulanPerbaikan->dokumen_usulan) }}"
                                                                    width="100%" height="500px" frameborder="0"></iframe>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <!-- Form to update status of perbaikan -->
                                                                <form
                                                                    action="{{ route('review-usulan.updateStatus', $usulan->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <!-- Status Dropdown -->
                                                                    <div class="d-flex align-items-center">
                                                                        <label for="status{{ $usulan->usulan_id }}"
                                                                            class="form-label me-2">Status:</label>
                                                                        <select name="status"
                                                                            id="status{{ $usulan->usulan_id }}"
                                                                            class="form-select me-3" required>
                                                                            <option value="Di Revisi Kembali"
                                                                                {{ $usulanPerbaikan->status == 'Di Revisi Kembali' ? 'selected' : '' }}>
                                                                                Di Revisi Kembali
                                                                            </option>
                                                                            <option value="Diterima"
                                                                                {{ $usulanPerbaikan->status == 'Diterima' ? 'selected' : '' }}>
                                                                                Diterima
                                                                            </option>

                                                                        </select>
                                                                        <button type="submit"
                                                                            class="btn btn-success">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <td>
                                                <a href="{{ route('review-usulan.lihat', ['id' => $usulan->usulan_id]) }}"
                                                    class="btn btn-info" style="margin-right: 10px;">
                                                    Lihat Detail
                                                </a>

                                                @if ($usulan->total_nilai == 0)
                                                    <a href="{{ route('form-penilaian.input', ['id' => $usulan->usulan_id]) }}"
                                                        class="btn btn-primary" style="margin-right: 10px;">
                                                        Review
                                                    </a>
                                                @else
                                                    <!-- Tombol Batal Penilaian -->
                                                    <form
                                                        action="{{ route('form-penilaian.batal', ['id' => $usulan->id]) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('POST')
                                                        <input type="hidden" name="penilaian_reviewers_id"
                                                            value="{{ $usulan->id }}">
                                                        <input type="hidden" name="usulan_id"
                                                            value="{{ $usulan->usulan_id }}">

                                                        <button type="button" class="btn btn-danger batal-button"
                                                            style="margin-right: 10px;">
                                                            Batal Penilaian
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        "use strict";

        var KTDatatablesServerSide = function () {
            var table;
            var dt;
            var filterStatus;

            var initDatatable = function () {
                dt = $("#table-usulans").DataTable({
                    searchDelay: 500,
                    processing: true,
                    stateSave: true,
                    select: {
                        style: 'multi',
                        selector: 'td:first-child input[type="checkbox"]',
                        className: 'row-selected'
                    },
                    order: [[0, 'desc']],
                    lengthMenu: [10, 25, 50, 75, 100],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            render: function (data) {
                                return `
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="${data}" />
                                    </div>`;
                            }
                        },
                    ],
                    language: {
                        "sProcessing": "Sedang memproses...",
                        "sLengthMenu": "Tampilkan _MENU_ entri",
                        "sZeroRecords": "Tidak ada data yang sesuai",
                        "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                        "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                        "sInfoPostFix": "",
                        "sSearch": "Cari:",
                        "sUrl": "",
                        "oPaginate": {
                            "sFirst": "Pertama",
                            "sPrevious": "Sebelumnya",
                            "sNext": "Selanjutnya",
                            "sLast": "Terakhir"
                        }
                    }
                });

                table = dt.$;

                // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
                dt.on('draw', function () {
                    handleDeleteRows();
                    KTMenu.createInstances();
                });
            }

            // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
            var handleSearchDatatable = function () {
                const filterSearch = document.querySelector('[data-kt-table-filter="search"]');
                filterSearch.addEventListener('keyup', function (e) {
                    dt.search(e.target.value).draw();
                });
            }

            // Filter Datatable
            var handleFilterDatatable = () => {
                // Select filter options
                filterStatus = document.querySelector('[data-kt-table-filter="status"]');

                // Filter datatable on submit
                document.querySelector('[data-kt-table-filter="filter"]').addEventListener('click', function () {
                    // Get filter values
                    const statusValue = filterStatus.value;

                    // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
                    if (statusValue) {
                        dt.column(5).search(statusValue).draw(); // Kolom status ada di index 5
                    } else {
                        dt.column(5).search('').draw();
                    }
                });

                // Reset Filter
                document.querySelector('[data-kt-table-filter="reset"]').addEventListener('click', function () {
                    // Reset filter form
                    filterStatus.value = '';
                    
                    // Reset datatable
                    dt.search('').columns().search('').draw();
                });
            }

            // Delete customer
            var handleDeleteRows = () => {
                // Select all delete buttons
                const deleteButtons = document.querySelectorAll('.batal-button');

                deleteButtons.forEach(d => {
                    // Delete button on click
                    d.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Penilaian ini akan dibatalkan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, batalkan!',
                            cancelButtonText: 'Tidak, batalkan!',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Jika pengguna mengonfirmasi, kirim form
                                this.closest('form').submit();
                            }
                        });
                    })
                });
            }

            // Public methods
            return {
                init: function () {
                    initDatatable();
                    handleSearchDatatable();
                    handleFilterDatatable();
                    handleDeleteRows();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTDatatablesServerSide.init();
        });
    </script>
@endsection
