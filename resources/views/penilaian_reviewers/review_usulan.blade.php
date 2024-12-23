@extends('layouts.main_layout')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-150px">Proposal Title</th>
                                    <th class="min-w-100px">Review Status</th>
                                    <th class="min-w-150px">Form Penilaian</th>
                                    <th class="min-w-150px">Actions</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold">
                                @foreach ($usulans as $key => $usulan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $usulan->usulan->judul_usulan }}</td>
                                        <td>
                                            <span class="badge badge-light-primary">{{ $usulan->status_penilaian }}</span>
                                        </td>
                                        <td>
                                            @if ($usulan->formPenilaian)
                                                <span class="badge badge-light-success">{{ $usulan->formPenilaian->status }}</span>
                                            @else
                                                <a href="{{ route('form-penilaian.input', ['usulan_id' => $usulan->usulan->id]) }}" 
                                                   class="btn btn-sm btn-primary ms-3">Input Penilaian</a>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('perbaikan-penilaian.lihat', $usulan->id) }}" class="btn btn-primary btn-sm">Lihat Perbaikan</a>
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
                    {{ $usulans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
