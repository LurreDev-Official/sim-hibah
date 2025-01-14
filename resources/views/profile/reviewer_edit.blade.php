@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Update Profil Reviewer</h1>
                </div>
                <!--end::Page title-->
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body py-5">
                        <form method="POST" action="{{ route('reviewer.update', $reviewer->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body border-top p-9">
                                <!-- NIDN -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">NIDN</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="nidn" type="number"
                                            class="form-control form-control-lg form-control-solid @error('nidn') is-invalid @enderror"
                                            name="nidn" value="{{ old('nidn', $reviewer->nidn) }}"
                                            placeholder="Masukkan NIDN" required>
                                        @error('nidn')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Fakultas -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Fakultas</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="fakultas" type="text"
                                            class="form-control form-control-lg form-control-solid" name="fakultas"
                                            value="{{ old('fakultas', $reviewer->fakultas) }}" placeholder="Masukkan Fakultas" required>
                                    </div>
                                </div>

                                <!-- Program Studi (Prodi) -->
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Program Studi</label>
                                    <div class="col-lg-8 fv-row">
                                        <input id="prodi" type="text"
                                            class="form-control form-control-lg form-control-solid @error('prodi') is-invalid @enderror"
                                            name="prodi" value="{{ old('prodi', $reviewer->prodi) }}"
                                            placeholder="Masukkan Program Studi" required>
                                        @error('prodi')
                                            <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>



                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end pt-7">
                                    <button type="submit" class="btn btn-sm fw-bolder btn-primary">Update Reviewer</button>
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

@stack('scripts')
