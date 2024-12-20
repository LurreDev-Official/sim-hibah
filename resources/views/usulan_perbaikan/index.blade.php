@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Daftar Usulan Perbaikan</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <!-- SVG Search Icon -->
                                </span>
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="myInput" placeholder="Search Usulan Perbaikan" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">No</th>
                                        <th class="min-w-150px">Usulan ID</th>
                                        <th class="min-w-150px">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    @foreach($usulanPerbaikans as $usulan)
                                        <tr>
                                            <td>{{ $usulan->id }}</td>
                                            <td>{{ $usulan->usulan_id }}</td>
                                            <td>
                                                @if ($usulan->status == 'revisi')
                                                    <span class="badge bg-warning">Revisi</span>
                                                    <!-- Tombol Detail Revisi hanya muncul ketika status "revisi" -->
                                                    <a href="{{ route('perbaikan-usulan.detail_revisi', $usulan->usulan_id) }}" class="btn btn-info btn-sm mt-2">Detail Revisi</a>
                                                @elseif ($usulan->status == 'didanai')
                                                    <span class="badge bg-success">Didanai</span>
                                                @elseif ($usulan->status == 'tidak didanai')
                                                    <span class="badge bg-danger">Tidak Didanai</span>
                                                @endif
                                            </td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('myInput').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('myTable');
            tr = table.getElementsByTagName('tr');
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td')[1]; // Search by Usulan ID
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        });
    </script>
@endsection
