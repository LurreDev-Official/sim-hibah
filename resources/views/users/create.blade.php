@extends('layouts.main_layout')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Tambah
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Users</small>
                        <!--end::Description-->
                    </h1>
                    <!--end::Title-->
                </div>

            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <a href="{{ route('kelola-user.index') }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                        <!--end::Card toolbar-->
                    </div>

                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-5">
                        @if (count($errors) > 0)
                            <!--begin::Alert-->
                            <div class="alert alert-dismissible bg-primary d-flex flex-column flex-sm-row p-5 mb-10">
                                <!--begin::Icon-->
                                <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">...</span>
                                <!--end::Icon-->

                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                                    <!--begin::Title-->
                                    <h4 class="mb-2 light">There were some problems with your input</h4>
                                    <!--end::Title-->
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Close-->
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <span class="svg-icon svg-icon-2x svg-icon-light">...</span>
                                </button>
                                <!--end::Close-->
                            </div>
                            <!--end::Alert-->
                        @endif

                        <form id="kt_account_profile_details_form" class="form" action="{{ route('kelola-user.store') }}" enctype="multipart/form-data" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body border-top p-9">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama </label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="name" class="form-control form-control-lg form-control-solid" placeholder="Isi nama" required />
                                        {{-- <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $user->name}}</label> --}}
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">email </label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="email" class="form-control form-control-lg form-control-solid" placeholder="Isi email" required />
                                        {{-- <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ $user->name}}</label> --}}
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">alamat </label>
                                    <div class="col-lg-8 fv-row">
                                        <textarea name="alamat" id="" class="form-control" cols="30" rows="10">isi alamat</textarea>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">no_wa </label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="number" name="no_wa" class="form-control form-control-lg form-control-solid" placeholder="Isi No wa" required />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Role </label>
                                    <div class="col-lg-8 fv-row">
                                        <select class="form-select" name="role" aria-label="Default select example">
                                            <option selected>Role</option>
                                            <option value="admin">admin</option>
                                            <option value="penyelenggara">penyelenggara</option>
                                            <option value="outlet">outlet</option>
                                        </select>
                                    </div>
                                </div>

                                {{--  end: Input Group  --}}
                                {{--  begin: Input Group  --}}
                                <div class="row mb-6">
                                    <label id="password" class="col-lg-4 col-form-label required fw-bold fs-6">Password</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="password" name="password" class="form-control form-control-lg form-control-solid" placeholder="Password" required />
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end pt-7">
                                    <button type="submit" class="btn btn-sm fw-bolder btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
