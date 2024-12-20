@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Daftar Usulan {{ $jenis }}</small>
                    </h1>
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
                                    placeholder="Search usulan" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Add usulan-->
                                @role('Dosen')
                                    <a href="{{ route('usulan.create', ['jenis' => $jenis]) }}" class="btn btn-primary">Tambah
                                        Usulan</a>
                                @endrole
                            </div>
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
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">No</th>
                                        <th class="min-w-150px">Judul Usulan</th>
                                        <th class="min-w-150px">Tahun Pelaksanaan</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-150px">Rumpun Ilmu</th>
                                        <th class="min-w-150px">Bidang Fokus</th>
                                        <th class="min-w-150px">Tema Penelitian</th>
                                        <th class="min-w-150px">Topik Penelitian</th>
                                        <th class="min-w-150px">Lama Kegiatan</th>
                                        <th class="min-w-150px">Ketua Dosen</th>
                                        <th class="min-w-150px">Dokumen Usulan</th>
                                        <th class="min-w-150px">Aksi</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->

                                <!--begin::Table body-->
                                <tbody id="myTable">
                                    @forelse($data as $usulan)
                                        <tr>
                                            <td>{{ $usulan->id }}</td>
                                            <td>{{ $usulan->judul_usulan }}</td>
                                            <td>{{ $usulan->tahun_pelaksanaan }}</td>
                                            <td>
                                                @if ($usulan->status == 'draft')
                                                    <span class="badge bg-warning">Draft</span>
                                                @elseif ($usulan->status == 'submitted')
                                                    <span class="badge bg-info">Submitted</span>
                                                @elseif ($usulan->status == 'review')
                                                    <span class="badge bg-primary">In Review</span>
                                                @elseif ($usulan->status == 'revision')
                                                    <span class="badge bg-secondary">Needs Revision</span>
                                                @elseif ($usulan->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($usulan->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif

                                            </td>
                                            <td>{{ $usulan->rumpun_ilmu }}</td>
                                            <td>{{ $usulan->bidang_fokus }}</td>
                                            <td>{{ $usulan->tema_penelitian }}</td>
                                            <td>{{ $usulan->topik_penelitian }}</td>
                                            <td>{{ $usulan->lama_kegiatan }} tahun</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#pdfModal{{ $usulan->id }}">
                                                    Lihat Dokumen
                                                </button>
                                            </td>
                                            <!-- Modal -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="pdfModal{{ $usulan->id }}" tabindex="-1"
                                                aria-labelledby="pdfModalLabel{{ $usulan->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="pdfModalLabel{{ $usulan->id }}">
                                                                Preview Dokumen</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <iframe src="{{ Storage::url($usulan->dokumen_usulan) }}"
                                                                style="width: 100%; height: 100vh;"
                                                                frameborder="0"></iframe>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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
                <div class="py-5">
                    <ul class="pagination">
                        {{ $data->links() }}
                    </ul>
                </div>
                <!--end::Pagination-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    </div>
@endsection

<script>
    document.getElementById('myInput').addEventListener('keyup', function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('myInput');
        filter = input.value.toUpperCase();
        table = document.getElementById('myTable');
        tr = table.getElementsByTagName('tr');
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName('td')[0]; // Search by Judul Usulan
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    });
</script>

<script>
    function fillEditForm(usulan) {
        document.getElementById('judul_usulan').value = usulan.judul_usulan;
        document.getElementById('tahun_pelaksanaan').value = usulan.tahun_pelaksanaan;
        document.getElementById('status').value = usulan.status;
        document.getElementById('rumpun_ilmu').value = usulan.rumpun_ilmu;
        document.getElementById('bidang_fokus').value = usulan.bidang_fokus;
        document.getElementById('tema_penelitian').value = usulan.tema_penelitian;
        document.getElementById('topik_penelitian').value = usulan.topik_penelitian;
        document.getElementById('lama_kegiatan').value = usulan.lama_kegiatan;
    }
</script>
