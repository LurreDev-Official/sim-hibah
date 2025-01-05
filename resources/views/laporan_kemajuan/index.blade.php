@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Laporan Kemajuan
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">List</small>
                    </h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 11 17ZM17 7.53333"
                                            fill="black" />
                                    </svg>
                                </span>
                                <input type="text" id="myInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Search Laporan" name="search" />
                            </div>
                        </div>


                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-laporan">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Created</th>
                                    <th>Judul Laporan</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="myTable">
                                @foreach ($laporanKemajuan as $laporan)
                                    <tr>
                                        <!-- ID -->
                                        <td>{{ $loop->iteration }}</td>
                    
                                        <!-- Created At -->
                                        <td>{{ $laporan->created_at->format('d M Y') }}</td>
                    
                                        <!-- Judul Laporan -->
                                        <td>{{ $laporan->usulan->judul_usulan ?? '-' }}</td>
                    
                                        <!-- Jenis -->
                                        <td>{{ ucfirst($laporan->jenis) }}</td>
                    
                                        <!-- Status -->
                                        <td>
                                            @switch($laporan->status)
                                                @case('submitted')
                                                    <span class="badge bg-info">Submitted</span>
                                                    @break
                                                @case('review')
                                                    <span class="badge bg-primary">In Review</span>
                                                    <ul>
                                                        @foreach ($laporan->reviewers as $reviewer)
                                                            <li>{{ $reviewer->user->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                    @break
                                                @case('revision')
                                                    <span class="badge bg-warning">Needs Revision</span>
                                                    @break
                                                @case('waiting approved')
                                                    <span class="badge bg-secondary text-black">Waiting Approved</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">Approved</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-dark">Unknown</span>
                                            @endswitch
                                        </td>
                    
                                        <!-- Dokumen -->
                                        <td>
                                            @if ($laporan->dokumen_laporan_kemajuan)
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#lihatDokumenModal-{{ $laporan->id }}">
                                                    <i class="fas fa-file-pdf"></i> Lihat
                                                </button>
                    
                                                <!-- Modal untuk dokumen -->
                                                <div class="modal fade" id="lihatDokumenModal-{{ $laporan->id }}" tabindex="-1"
                                                    aria-labelledby="lihatDokumenModalLabel-{{ $laporan->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-fullscreen">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="lihatDokumenModalLabel-{{ $laporan->id }}">
                                                                    Dokumen Laporan
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <iframe src="{{ asset('storage/' . $laporan->dokumen_laporan_kemajuan) }}"
                                                                    width="100%" height="500px" frameborder="0"></iframe>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <a href="{{ asset('storage/' . $laporan->dokumen_laporan_kemajuan) }}"
                                                                    class="btn btn-success" target="_blank">
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-danger">Tidak ada dokumen</span>
                                            @endif
                                        </td>
                    
                                        <!-- Actions -->
                                        <td class="text-end">
                                            @role('Dosen')
                                                @if ($laporan->status == 'submitted')
                                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $laporan->id }}">Edit</button>
                                                @endif
                    
                                                @if ($laporan->status == 'approved')
                                                    <a href="{{ route('laporan-kemajuan.cetakBuktiACC', $laporan->id) }}"
                                                        class="btn btn-success btn-sm" target="_blank">
                                                        <i class="fas fa-download"></i> Bukti ACC
                                                    </a>
                                                @endif
                    
                                                @if ($laporan->status == 'revision')
                                                    <a href="{{ route('laporan-kemajuan.perbaikiRevisi', ['jenis' => $jenis, 'id' => $laporan->id]) }}"
                                                        class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-edit"></i> Perbaiki Revisi
                                                    </a>
                                                @endif
                    
                                                @if ($anggotaDosencek->status_anggota == 'ketua' && $laporan->status == 'draft')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="deleteUsulan('{{ $jenis }}', {{ $laporan->id }})">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                @endif
                                            @endrole
                                            @role('Kepala LPPM')
                                                @if (in_array($laporan->status, ['draft', 'submitted']))
                                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#pilihKirimReviewerModal-{{ $laporan->id }}">
                                                        Kirim ke Reviewer
                                                    </button>
                                                @endif
                                            @endrole
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@foreach ($laporanKemajuan as $laporan)
    <div class="modal fade" id="editModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('laporan-kemajuan.update', $laporan->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Dokumen Laporan Kemajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- File upload input -->
                        <div class="mb-3">
                            <label for="dokumen_laporan_kemajuan{{ $laporan->id }}" class="form-label">Dokumen
                                Laporan</label>
                            <input type="file" class="form-control"
                                id="dokumen_laporan_kemajuan{{ $laporan->id }}" name="dokumen_laporan_kemajuan"
                                accept=".pdf,.doc,.docx">
                            <!-- Show current document if exists -->
                            @if ($laporan->dokumen_laporan_kemajuan)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $laporan->dokumen_laporan_kemajuan) }}"
                                        target="_blank">Lihat Dokumen Lama</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach



 @section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-laporan').DataTable({
            searchable: true,
        });
    </script>
@endsection
