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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Laporan Kemajuan
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
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 11 17ZM17 7.53333" fill="black" />
                                    </svg>
                                </span>
                                <input type="text" id="myInput" class="form-control form-control-solid w-250px ps-15" placeholder="Search Laporan" name="search" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('laporan-kemajuan.create') }}" class="btn btn-primary">Tambah Laporan</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-laporan">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Judul Laporan</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="myTable">
                                @foreach ($laporanKemajuan as $laporan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $laporan->judul }}</td>
                                        <td>{{ $laporan->jenis }}</td>
                                        <td>{{ $laporan->status }}</td>
                                        <td>
                                            @if ($laporan->dokumen_laporan_kemajuan)
                                                <a href="{{ asset('storage/' . $laporan->dokumen_laporan_kemajuan) }}" target="_blank">Download</a>
                                            @else
                                                Tidak ada dokumen
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('laporan-kemajuan.edit', $laporan->id) }}" class="btn btn-light btn-active-light-primary btn-sm">Edit</a>
                                            <form action="{{ route('laporan-kemajuan.destroy', $laporan->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-danger btn-sm" onclick="return confirm('Hapus laporan ini?')">Hapus</button>
                                            </form>
                                        </td>
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
        var xin_table = $('#table-laporan').DataTable({
            searchable: true,
        });
    </script>
@endsection
