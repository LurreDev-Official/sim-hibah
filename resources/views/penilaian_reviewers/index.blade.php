@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Penilaian Reviewer
                        <!--begin::Separator-->
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    </h1>
                    <!--end::Separator-->
                    <!--begin::Description-->
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">List of Penilaian Reviewer</small>
                    <!--end::Description-->
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="myInput"
                                    placeholder="Search reviewer" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">No</th>
                                        @role('Kepala LPPM')
                                            <th class="min-w-150px">Reviewer Name</th>
                                        @endrole
                                        <th class="min-w-150px">Proposal Title</th>
                                        <th class="min-w-100px">Review Status</th>
                                        <th class="min-w-150px">Form Penilaian</th>
                                        <th class="min-w-150px">Perbaikan</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold" id="myTable">
                                    @foreach ($penilaianReviewers as $key => $penilaian)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @role('Kepala LPPM')
                                                <td>{{ $penilaian->reviewer->user->name }}</td>
                                            @endrole
                                            <td>{{ $penilaian->usulan->judul_usulan }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-light-primary">{{ $penilaian->status_penilaian }}</span>
                                            </td>
                                          
                                            <td>
                                                @if ($penilaian->formPenilaian)
                                                    <span class="badge badge-light-success">{{ $penilaian->formPenilaian->status }}</span>
                                                @else
                                                    <!-- Input Penilaian Button -->
                                                    <a href="{{ route('form-penilaian.input', ['usulan_id' => $penilaian->usulan->id]) }}" 
                                                       class="btn btn-sm btn-primary ms-3">Input Penilaian</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('perbaikan-penilaian.lihat', $penilaian->id) }}" class="btn btn-primary">Lihat Perbaikan</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>

                    <div class="py-5">
                        <!--begin::Pages-->
                        <ul class="pagination">
                            {{ $penilaianReviewers->links() }}
                        </ul>
                        <!--end::Pages-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection
