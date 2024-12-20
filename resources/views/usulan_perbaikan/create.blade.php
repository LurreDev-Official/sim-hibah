@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    </h1>
                    <!--end::Separator-->
                    <!--begin::Description-->
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Detail Usulan & Revisi Reviewer</small>
                    <!--end::Description-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card 1 - Detail Usulan-->
                <div class="card mb-5">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3>Detail Usulan</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Judul Usulan:</strong>
                                <p>{{ $usulan->judul_usulan }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Jenis Skema:</strong>
                                <p>{{ $usulan->jenis_skema }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Tahun Pelaksanaan:</strong>
                                <p>{{ $usulan->tahun_pelaksanaan }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p>{{ $usulan->status }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Rumpun Ilmu:</strong>
                                <p>{{ $usulan->rumpun_ilmu }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Bidang Fokus:</strong>
                                <p>{{ $usulan->bidang_fokus }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Tema Penelitian:</strong>
                                <p>{{ $usulan->tema_penelitian }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Topik Penelitian:</strong>
                                <p>{{ $usulan->topik_penelitian }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Lama Kegiatan:</strong>
                                <p>{{ $usulan->lama_kegiatan }} bulan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card 1-->

                <!--begin::Card 2 - Detail Revisi Reviewer 1-->
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3>Detail Revisi Berdasarkan Reviewer</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($penilaian as $penilaianReviewer)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>Reviewer: ID {{ $penilaianReviewer->reviewer->user->id }}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Status Penilaian:</strong>
                                            <p>{{ $penilaianReviewer->status_penilaian }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Total Nilai:</strong>
                                            <p>{{ $penilaianReviewer->total_nilai }}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Catatan:</strong>
                                            <p>{{ $penilaianReviewer->catatan }}</p>
                                        </div>
                                    </div>

                                    <!-- Cek apakah sudah ada dokumen revisi -->
                                    @if ($penilaianReviewer->status_penilaian == 'sudah diperbaiki' || $penilaianReviewer->status_penilaian == 'Diterima')
                                        <!-- Jika sudah ada dokumen revisi, tampilkan tabel -->
                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Dokumen Revisi</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <!-- Link untuk melihat dokumen, memicu modal -->
                                                            <button class="btn btn-link" data-bs-toggle="modal"
                                                                data-bs-target="#pdfModal{{ $penilaianReviewer->id }}">
                                                                Lihat Dokumen
                                                            </button>
                                                            <!-- Modal untuk melihat dokumen revisi -->
                                                          

                                                                        <div class="modal fade" id="pdfModal{{ $penilaianReviewer->id }}" tabindex="-1"
                                                                            aria-labelledby="pdfModalLabel{{ $penilaianReviewer->id }}" aria-hidden="true">
                                                                            <div class="modal-dialog modal-fullscreen">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="pdfModalLabel{{ $penilaianReviewer->id }}">
                                                                                            Preview Dokumen</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        @php
                                                                                            $usulanPerbaikan = App\Models\UsulanPerbaikan::where('penilaian_id', $penilaianReviewer->id)->first();
                                                                                        @endphp
                                                                                        @if ($usulanPerbaikan && $usulanPerbaikan->dokumen_usulan)
                                                                                        <iframe src="{{ Storage::url($usulan->dokumen_usulan) }}"
                                                                                            style="width: 100%; height: 100vh;"
                                                                                            frameborder="0"></iframe>
                                                                                        @else
                                                                                            <p>No document available for preview.</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                          
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <!-- Jika belum ada dokumen revisi, tampilkan tombol untuk upload -->
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#uploadRevisiModal{{ $penilaianReviewer->id }}">
                                            Upload PDF Revisi
                                        </button>
                                    @endif

                                    <!-- Modal -->
                                    <div class="modal fade" id="uploadRevisiModal{{ $penilaianReviewer->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Upload PDF Perbaikan Revisi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('perbaikan-usulan.upload_revisi', $penilaianReviewer->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="pdf_file" class="form-label">Upload PDF
                                                                    Perbaikan Revisi:</label>
                                                                <input type="file" class="form-control" name="pdf_file"
                                                                    id="pdf_file" accept="application/pdf" required>
                                                            </div>
                                                        </div>

                                                        <div class="text-end mt-3">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <!--end::Card 2-->
                <!--end::Card 2-->
            </div>
        </div>
    </div>
@endsection
