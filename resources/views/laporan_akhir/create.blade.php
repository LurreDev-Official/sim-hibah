@extends('layouts.main_layout')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Laporan Akhir
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Tambah Data</small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Begin::Post -->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary" href="{{ url('/') }}">Kembali</a>

                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <!-- Bootstrap 5 Form -->
                    <div class="card-body pt-0">
                        @if ($getTemplate == null)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Template Laporan Akhir penelitian belum tersedia.<br><br>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h3 class="text-center">Template Laporan Akhir Penelitian</h3>
                            <strong>Info!</strong> Silahkan download template Laporan Akhir penelitian <a
                                href="{{ asset('storage/' . $getTemplate->file) }}" class="btn btn-success btn-sm"
                                download>disini</a>.
                        </div>
                    @endif
                    <hr>

                    <form action="{{ route('laporan-akhir.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Usulan Dropdown -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="usulan_id"><strong>Laporan Kemajuan Sebelumnya:</strong></label>
                                    <select name="usulan_id" id="usulan_id" class="form-select">
                                        <option value="">Pilih Usulan</option>
                                        @foreach ($laporakemajuans as $laporankemajuan)
                                        <option value="{{ $laporankemajuan->usulan_id }}">{{ $laporankemajuan->usulan->judul_usulan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Dokumen Laporan Akhir -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="dokumen_laporan_akhir"><strong>Dokumen Laporan Akhir:</strong></label>
                                    <input type="file" name="dokumen_laporan_akhir" id="dokumen_laporan_akhir" class="form-control" />
                                </div>
                            </div>

                            <!-- jenis -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="jenis"><strong>Jenis :</strong></label>
                                    <input type="text" name="jenis" value="{{ $jenis }}" class="form-control" readonly />
                                </div>
                            </div>
 
                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end pt-7">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
