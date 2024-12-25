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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Perbaikan Revisi</h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>

    <!-- Display error messages -->
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary" href="{{ url('usulan/penelitian') }}">Kembali</a>
                        </div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body">
                    <!-- Informasi Usulan -->
                    <!-- Catatan Revisi -->
                    <div class="mb-4">
                        <h5 class="text-warning">Catatan Revisi</h5>
                        @if ($indikatorPenilaians->isNotEmpty())
                            @foreach ($indikatorPenilaians->groupBy('kriteria_id') as $kriteriaId => $indikators)
                                <div class="mb-3">
                                    <h6 class="text-primary"><strong>Kriteria: {{ $indikators->first()->kriteriaPenilaian->nama }}</strong></h6>
                                    <ul class="list-group">
                                        @foreach ($indikators as $indikator)
                                            <li class="list-group-item">
                                                <strong>Indikator:</strong> {{ $indikator->nama_indikator }} <br>
                                                <strong>Catatan:</strong> {{ $penilaianReviewer->catatan[$indikator->id] ?? 'Tidak ada catatan.' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Tidak ada catatan revisi.</p>
                        @endif
                    </div>

                    <!-- Form Perbaikan -->
                    <div class="mb-4">
                        <h5 class="text-success">Form Perbaikan</h5>
                        
                        @if(isset($usulanPerbaikan) && $usulanPerbaikan->dokumen_usulan)
                            <!-- File View Button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#fileModal">
                                    <i class="fas fa-eye"></i> Lihat File Perbaikan
                                </button>
                            </div>
                        @endif
                    
                        <!-- Upload File Form -->
                        <form action="{{ route('usulan.simpanPerbaikan', ['id' => $usulan->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                    
                            <div class="mb-3">
                                <label for="file_perbaikan" class="form-label">Upload File Perbaikan</label>
                                <input type="file" name="file_perbaikan" id="file_perbaikan" class="form-control" required>
                                <small class="text-muted">Unggah file perbaikan dalam format PDF atau DOCX (maks. 5MB).</small>
                            </div>
                    
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Kirim Perbaikan
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal for Viewing File -->
                    @if(isset($usulanPerbaikan) && $usulanPerbaikan->dokumen_usulan)
                    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen"> <!-- Fullscreen Modal -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="fileModalLabel">File Perbaikan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Display the file (PDF or DOCX) -->
                                    @if(pathinfo($usulanPerbaikan->dokumen_usulan, PATHINFO_EXTENSION) === 'pdf')
                                        <embed src="{{ asset('storage/' . $usulanPerbaikan->dokumen_usulan) }}" type="application/pdf" width="100%" height="500px" />
                                    @else
                                        <p>File tersedia untuk diunduh:</p>
                                        <a href="{{ asset('storage/' . $usulanPerbaikan->dokumen_usulan) }}" target="_blank" class="btn btn-success">
                                            <i class="fas fa-download"></i> Unduh File
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection
