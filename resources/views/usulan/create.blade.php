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
                        <!--end::Description-->
                    </h1>
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
                        @if ($getTemplate == null)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> Template usulan penelitian belum tersedia.<br><br>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h3 class="text-center">Template Usulan Penelitian</h3>
                                <strong>Info!</strong> Silahkan download template usulan penelitian <a
                                    href="{{ asset('storage/' . $getTemplate->file) }}" class="btn btn-success btn-sm"
                                    download>disini</a>.
                            </div>
                        @endif
                        <hr>
                        <form action="{{ route('usulan.store', ['jenis' => $jenis]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <!-- Ketua Dosen (Readonly) -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Ketua Dosen:</strong>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}"
                                            readonly>
                                    </div>
                                </div>

                                <!-- Judul Usulan -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Judul Usulan:</strong>
                                        <input type="text" name="judul_usulan" class="form-control"
                                            placeholder="Judul Usulan" value="{{ old('judul_usulan') }}">
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
                                            <option value="Ilmu Sosial"
                                                {{ old('rumpun_ilmu') == 'Ilmu Sosial' ? 'selected' : '' }}>Ilmu Sosial
                                            </option>
                                            <option value="Ilmu Alam"
                                                {{ old('rumpun_ilmu') == 'Ilmu Alam' ? 'selected' : '' }}>Ilmu Alam
                                            </option>
                                            <option value="Ilmu Terapan"
                                                {{ old('rumpun_ilmu') == 'Ilmu Terapan' ? 'selected' : '' }}>Ilmu Terapan
                                            </option>
                                            <option value="Ilmu Formal"
                                                {{ old('rumpun_ilmu') == 'Ilmu Formal' ? 'selected' : '' }}>Ilmu Formal
                                            </option>
                                            <option value="Ilmu Humaniora"
                                                {{ old('rumpun_ilmu') == 'Ilmu Humaniora' ? 'selected' : '' }}>Ilmu Humaniora
                                            </option>
                                            <option value="Ilmu Keagamaan"
                                                {{ old('rumpun_ilmu') == 'Ilmu Keagamaan' ? 'selected' : '' }}>Ilmu Keagamaan
                                            </option>
                                        </select>
                                        @error('rumpun_ilmu')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bidang Fokus -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Pohon Ilmu:</strong>
                                        <select name="bidang_fokus" class="form-control select2">
                                            <option value="" disabled selected>Pilih Pohon Ilmu</option>
                                            <option value="Astronomi"
                                                {{ old('bidang_fokus') == 'Astronomi' ? 'selected' : '' }}>Astronomi
                                            </option>
                                            <option value="Biofisika"
                                                {{ old('bidang_fokus') == 'Biofisika' ? 'selected' : '' }}>Biofisika
                                            </option>
                                            <option value="Biologi"
                                                {{ old('bidang_fokus') == 'Biologi' ? 'selected' : '' }}>
                                                Biologi</option>
                                        </select>
                                        @error('bidang_fokus')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tema Penelitian -->

                                <input type="text" value="-" name="tema_penelitian" hidden>
                                <input type="text" value="-" name="topik_penelitian" hidden>
                                {{-- <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Tema Penelitian:</strong>
                                        <input type="text" name="tema_penelitian" class="form-control"
                                            placeholder="Tema Penelitian" value="{{ old('tema_penelitian') }}">
                                        @error('tema_penelitian')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <!-- Topik Penelitian -->
                                {{-- <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Topik Penelitian:</strong>
                                        <input type="text" name="topik_penelitian" class="form-control"
                                            placeholder="Topik Penelitian" value="{{ old('topik_penelitian') }}">
                                        @error('topik_penelitian')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <!-- Lama Kegiatan -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Lama Kegiatan (dalam tahun):</strong>
                                        <input type="number" name="lama_kegiatan" class="form-control" readonly
                                            value="1" value="{{ old('lama_kegiatan') }}">
                                        @error('lama_kegiatan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Lokasi Penelitian -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Lokasi Penelitian:</strong>
                                        <input type="text" name="lokasi_penelitian" class="form-control"
                                            placeholder="Lokasi Penelitian" value="{{ old('lokasi_penelitian') }}">
                                        @error('lokasi_penelitian')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Tingkat Kecukupan Teknologi (TKT):</strong>
                                        <input type="number" name="tingkat_kecukupan_teknologi" class="form-control" placeholder="TKT" value="{{ old('tingkat_kecukupan_teknologi') }}">
                                        @error('tingkat_kecukupan_teknologi')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Nama Mitra:</strong>
                                        <input type="text" name="nama_mitra" class="form-control" placeholder="Nama Mitra" value="{{ old('nama_mitra') }}">
                                        @error('nama_mitra')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Lokasi Mitra:</strong>
                                        <input type="text" name="lokasi_mitra" class="form-control" placeholder="Lokasi Mitra" value="{{ old('lokasi_mitra') }}">
                                        @error('lokasi_mitra')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Bidang Mitra:</strong>
                                        <input type="text" name="bidang_mitra" class="form-control" placeholder="Bidang Mitra" value="{{ old('bidang_mitra') }}">
                                        @error('bidang_mitra')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Jarak PT ke Lokasi Mitra (km):</strong>
                                        <input type="number" step="0.01" name="jarak_pt_ke_lokasi_mitra" class="form-control" placeholder="Jarak dalam km" value="{{ old('jarak_pt_ke_lokasi_mitra') }}">
                                        @error('jarak_pt_ke_lokasi_mitra')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Luaran:</strong>
                                        <textarea name="luaran" class="form-control" rows="3" placeholder="Deskripsi Luaran">{{ old('luaran', 'Laporan akhir penelitian,\nArtikel ilmiah di jurnal terakreditasi minimal SINTA 3 atau SINTA 4,\nArtikel ilmiah di prosiding SAINSTEKNOPAK') }}</textarea>
                                        @error('luaran')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Dokumen Usulan -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <strong>Dokumen Usulan (PDF): MAX (5mb)</strong>
                                        <input type="file" name="dokumen_usulan"
                                            class="form-control form-control-lg form-control-solid" accept=".pdf" />
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
