@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Daftar Usulan</small>
                    </h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-usulans">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">No</th>
                                        <th class="min-w-150px">Proposal Title</th>
                                        <th class="min-w-100px">Status Review</th>
                                        <th class="min-w-100px">Total Nilai</th>
                                        <th class="min-w-150px">Detail Perbaikan</th>
                                        <th class="min-w-150px">Actions</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold">
                                    @foreach ($getpenilaianreview as $key => $usulan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $usulan->usulan->judul_usulan }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-light-primary">{{ $usulan->status_penilaian }}</span>
                                            </td>
                                            <td>
                                                {{ $usulan->total_nilai }}
                                            </td>
                                            <td>
                                                @if ($usulan->status_penilaian = 'sudah dinilai')
                                                    @php
                                                        $usulanPerbaikan = $usulanPerbaikans
                                                            ->where('usulan_id', $usulan->usulan_id)
                                                            ->first();
                                                    @endphp

                                                    @if ($usulanPerbaikan && $usulanPerbaikan->dokumen_usulan !== null)
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#perbaikanModal{{ $usulan->usulan_id }}">
                                                            Lihat Perbaikan
                                                        </button>
                                                    @else
                                                        <span class="text-muted">Belum Ada Perbaikan</span>
                                                    @endif
                                                @endif
                                            </td>

                                            <!-- Modal for Perbaikan -->
                                            @php
                                                $usulanPerbaikan = $usulanPerbaikans->firstWhere(
                                                    'usulan_id',
                                                    $usulan->usulan_id,
                                                );
                                            @endphp

                                            @if ($usulanPerbaikan)
                                                <div class="modal fade" id="perbaikanModal{{ $usulan->usulan_id }}"
                                                    tabindex="-1" aria-labelledby="perbaikanModalLabel{{ $usulan->usulan_id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="perbaikanModalLabel{{ $usulan->usulan_id }}">
                                                                    Detail Perbaikan: {{ $usulan->judul_usulan }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Displaying the uploaded file (Perbaikan) -->
                                                                <iframe
                                                                    src="{{ asset('storage/' . $usulanPerbaikan->dokumen_usulan) }}"
                                                                    width="100%" height="500px" frameborder="0"></iframe>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <!-- Form to update status of perbaikan -->
                                                                <form
                                                                    action="{{ route('perbaikan-penilaian.update', $usulanPerbaikan->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <!-- Status Dropdown -->
                                                                    <div class="d-flex align-items-center">
                                                                        <label for="status{{ $usulan->usulan_id }}"
                                                                            class="form-label me-2">Status:</label>
                                                                        <select name="status"
                                                                            id="status{{ $usulan->usulan_id }}"
                                                                            class="form-select me-3" required>
                                                                            <option value="Diterima"
                                                                                {{ $usulanPerbaikan->status == 'Diterima' ? 'selected' : '' }}>
                                                                                Diterima
                                                                            </option>

                                                                        </select>
                                                                        <button type="submit"
                                                                            class="btn btn-success">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            <td>
                                                <a href="{{ route('review-usulan.lihat', ['id' => $usulan->usulan_id]) }}"
                                                    class="btn btn-info">
                                                    Lihat Detail
                                                </a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-usulans').DataTable({
            searchable: true,
        });
    </script>
@endsection
