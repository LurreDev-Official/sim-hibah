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
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Kelola Usulan
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Description-->
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Tambah Usulan</small>
                <!--end::Description--></h1>
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
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary" href="{{ url('usulan/penelitian') }}">Kembali</a>
                        </div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Form-->
                    <form action="{{ route('usulan.store', ['jenis' => $jenis]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                
                            <!-- Ketua Dosen (Readonly) -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Ketua Dosen:</strong>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>
                
                            <!-- Judul Usulan -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Judul Usulan:</strong>
                                    <input type="text" name="judul_usulan" class="form-control" placeholder="Judul Usulan" value="{{ old('judul_usulan') }}">
                                    @error('judul_usulan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <!-- Tahun Pelaksanaan -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Tahun Pelaksanaan:</strong>
                                    <select name="tahun_pelaksanaan" class="form-control">
                                        <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                                    </select>
                                    @error('tahun_pelaksanaan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            
                
                            <!-- Rumpun Ilmu -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Rumpun Ilmu:</strong>
                                    <select name="rumpun_ilmu" class="form-control select2">
                                        <option value="" disabled selected>Pilih Rumpun Ilmu</option>
                                        <option value="Ilmu Sosial" {{ old('rumpun_ilmu') == 'Ilmu Sosial' ? 'selected' : '' }}>Ilmu Sosial</option>
                                        <option value="Ilmu Alam" {{ old('rumpun_ilmu') == 'Ilmu Alam' ? 'selected' : '' }}>Ilmu Alam</option>
                                        <option value="Ilmu Komputer" {{ old('rumpun_ilmu') == 'Ilmu Komputer' ? 'selected' : '' }}>Ilmu Komputer</option>
                                    </select>
                                    @error('rumpun_ilmu')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <!-- Bidang Fokus -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Bidang Fokus:</strong>
                                    <select name="bidang_fokus" class="form-control select2">
                                        <option value="" disabled selected>Pilih Bidang Fokus</option>
                                        <option value="Ilmu Sosial" {{ old('bidang_fokus') == 'Ilmu Sosial' ? 'selected' : '' }}>Ilmu Sosial</option>
                                        <option value="Ilmu Alam" {{ old('bidang_fokus') == 'Ilmu Alam' ? 'selected' : '' }}>Ilmu Alam</option>
                                        <option value="Ilmu Komputer" {{ old('bidang_fokus') == 'Ilmu Komputer' ? 'selected' : '' }}>Ilmu Komputer</option>
                                    </select>
                                    @error('bidang_fokus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <!-- Tema Penelitian -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Tema Penelitian:</strong>
                                    <input type="text" name="tema_penelitian" class="form-control" placeholder="Tema Penelitian" value="{{ old('tema_penelitian') }}">
                                    @error('tema_penelitian')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <!-- Topik Penelitian -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Topik Penelitian:</strong>
                                    <input type="text" name="topik_penelitian" class="form-control" placeholder="Topik Penelitian" value="{{ old('topik_penelitian') }}">
                                    @error('topik_penelitian')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <!-- Lama Kegiatan -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Lama Kegiatan (dalam tahun):</strong>
                                    <input type="number" name="lama_kegiatan" class="form-control" readonly value="1" value="{{ old('lama_kegiatan') }}">
                                    @error('lama_kegiatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <!-- Dokumen Usulan -->
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Dokumen Usulan (PDF):</strong>
                                    <input type="file" name="dokumen_usulan" class="form-control form-control-lg form-control-solid" accept=".pdf"/>
                                    @error('dokumen_usulan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="form-group">
                                    <strong>Status:</strong>
                                    <select name="status" class="form-control select2">
                                        <option value="draft" readonly selected>draft</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                
                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end pt-7">
                                <button type="submit" class="btn btn-sm fw-bolder btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
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
