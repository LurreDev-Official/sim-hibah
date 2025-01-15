@extends('layouts.main_layout')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Data Dosen</h1>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Cari Dosen" />
                            </div>
                        </div>

                        <!-- Filter Dropdown -->
                        <div class="d-flex mt-4">
                            <select class="form-select" id="fakultas" aria-label="Fakultas">
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $fakultasItem)
                                    <option value="{{ $fakultasItem->id }}">{{ $fakultasItem->name }}</option>
                                @endforeach
                            </select>
                            
                            <select class="form-select ms-3" id="prodi" aria-label="Program Studi">
                                <option value="">Pilih Program Studi</option>
                            </select>
                            
                            <!-- Tombol Filter -->
                            <button id="filterButton" class="btn btn-primary ms-3">Filter</button>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-dosen">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                    <th>Program Studi</th>
                                    <th>Jumlah Proposal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dosen as $index => $data)
                                    <tr data-fakultas="{{ $data->fakultas_id }}" data-prodi="{{ $data->prodi_id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->nama_dosen }}</td>
                                        <td>{{ $data->prodi }}</td>
                                        <td>{{ $data->jumlah_proposal }}</td>
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

<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#table-dosen').DataTable({
            responsive: true,
            processing: true,
            paging: true,
            searching: true,
            columnDefs: [
                { targets: 6, orderable: false } // Disable ordering for Actions column
            ]
        });

        // Event listener for Fakultas change
        $('#fakultas').on('change', function() {
            var fakultasId = $(this).val();
            if (fakultasId) {
                $.ajax({
                    url: '/get-prodi/' + fakultasId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#prodi').empty().append('<option value="">Pilih Program Studi</option>');
                        $.each(data, function(key, value) {
                            $('#prodi').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#prodi').prop('disabled', false);  // Enable Prodi dropdown
                    }
                });
            } else {
                $('#prodi').empty().append('<option value="">Pilih Program Studi</option>');
                $('#prodi').prop('disabled', true);  // Disable Prodi dropdown
            }
        });

        // Filter Button click event
        $('#filterButton').on('click', function() {
            var fakultasId = $('#fakultas').val();
            var prodiId = $('#prodi').val();

            // Filter rows based on selected Fakultas and Program Studi
            table.rows().every(function() {
                var row = this.node();
                var rowFakultasId = $(row).data('fakultas');
                var rowProdiId = $(row).data('prodi');

                if ((fakultasId === "" || rowFakultasId == fakultasId) &&
                    (prodiId === "" || rowProdiId == prodiId)) {
                    $(row).show();
                } else {
                    $(row).hide();
                }
            });
        });
    });
</script>
