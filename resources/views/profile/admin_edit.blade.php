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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Update
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <!--end::Separator-->
                        <!--begin::Description-->
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Account (Kepala LPPM)</small>
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
                                <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body py-5">
                        <form method="POST" action="{{ route('profile.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="card-body border-top p-9">
                                <!-- Name Field -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="name" type="text" class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="email" type="email" class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password Field -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">Password</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="password" type="password" class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" name="password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end pt-7">
                                    <button type="submit" class="btn btn-sm fw-bolder btn-primary">Update Account</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection
