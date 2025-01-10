@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Report Hasil Penelitian
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">List</small>
                    </h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <form method="POST" action="{{ route('report.filter') }}" class="d-flex align-items-center">
                                @csrf
                                <select name="startYear" class="form-control form-control-solid me-3">
                                    <option value="">Tahun Awal</option>
                                    @for ($year = 2025; $year <= 2030; $year++)
                                        <option value="{{ $year }}" {{ old('startYear') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="endYear" class="form-control form-control-solid me-3">
                                    <option value="">Tahun Akhir</option>
                                    @for ($year = 2025; $year <= 2030; $year++)
                                        <option value="{{ $year }}" {{ old('endYear') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                <button type="submit" name="action" value="filter" class="btn btn-primary me-2">Filter</button>
                                <button type="submit" name="action" value="export" class="btn btn-success">Export Excel</button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-laporan">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Created</th>
                                    <th>Judul Laporan</th>
                                    <th>Jenis</th>
                                    <th>Dokumen</th>
                                    <th>Luara</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="myTable">
                                @foreach ($laporanAkhir as $laporan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $laporan->created_at }}</td>
                                        <td>{{ $laporan->usulan->judul_usulan }}</td>
                                        <td>{{ $laporan->jenis }}</td>
                                        <td>{{ $laporan->status }}</td>
                                        <td><a href="{{ $laporan->dokumen_url }}">Download</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-laporan').DataTable();
        });
    </script>
@endsection
