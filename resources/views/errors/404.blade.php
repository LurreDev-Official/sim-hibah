@extends('layouts.main_layout')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    Halaman Tidak Ditemukan
                </h1>
            </div>
        </div>
        <!--end::Toolbar-->
    </div>

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-body text-center p-10">
                    <h1 class="fw-bolder text-danger">404</h1>
                    <h2 class="mb-4">Oops! Halaman tidak ditemukan.</h2>
                    <p class="fs-4 text-gray-600 mb-7">
                        Maaf, halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--end::Post-->
</div>
@endsection