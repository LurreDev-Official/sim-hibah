@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Daftar Usulan {{ $jenis }}</small>
                    </h1>
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
                                <!-- Search Icon -->
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                                    </svg>
                                </span>
                                <!-- Search Input -->
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="myInput" placeholder="Search usulan" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                          <!--begin::Card toolbar-->
                          <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Add usulan-->
                                @role('Dosen')
                                <a href="{{ route('usulan.create', ['jenis' => $jenis]) }}" class="btn btn-primary">Tambah Usulan</a>
                                @endrole
                            </div>
                        </div>
                        <!--end::Card toolbar-->

                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">No</th>
                                        <th class="min-w-150px">Judul Usulan</th>
                                        <th class="min-w-150px">Tahun Pelaksanaan</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-150px">Rumpun Ilmu</th>
                                        <th class="min-w-150px">Bidang Fokus</th>
                                        <th class="min-w-150px">Tema Penelitian</th>
                                        <th class="min-w-150px">Topik Penelitian</th>
                                        <th class="min-w-150px">Lama Kegiatan</th>
                                        <th class="min-w-150px">Ketua Dosen</th>
                                        <th class="min-w-150px">Dokumen Usulan</th>
                                        <th class="min-w-150px">Aksi</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                
                                <!--begin::Table body-->
                                <tbody id="myTable">
                                    @forelse($usulans as $usulan)
                                        <tr>
                                            <td>{{ $usulan->id }}</td>
                                            <td>{{ $usulan->judul_usulan }}</td>
                                            <td>{{ $usulan->tahun_pelaksanaan }}</td>
                                            <td>
                                                @if ($usulan->status == 'draft')
                                                <span class="badge bg-warning">Draft</span>
                                            @elseif ($usulan->status == 'submitted')
                                                <span class="badge bg-info">Submitted</span>
                                            @elseif ($usulan->status == 'review')
                                                <span class="badge bg-primary">In Review</span>
                                            @elseif ($usulan->status == 'revision')
                                                <span class="badge bg-secondary">Needs Revision</span>
                                            @elseif ($usulan->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($usulan->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                      
                                            @endif

                                            </td>
                                            <td>{{ $usulan->rumpun_ilmu }}</td>
                                            <td>{{ $usulan->bidang_fokus }}</td>
                                            <td>{{ $usulan->tema_penelitian }}</td>
                                            <td>{{ $usulan->topik_penelitian }}</td>
                                            <td>{{ $usulan->lama_kegiatan }} tahun</td>
                                            <td>{{ $usulan->ketua_dosen_id }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pdfModal{{ $usulan->id }}">
                                                    Lihat Dokumen
                                                </button>
                                            </td>
                                            <!-- Modal -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="pdfModal{{ $usulan->id }}" tabindex="-1" aria-labelledby="pdfModalLabel{{ $usulan->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="pdfModalLabel{{ $usulan->id }}">Preview Dokumen</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <iframe src="{{ Storage::url($usulan->dokumen_usulan) }}" style="width: 100%; height: 100vh;" frameborder="0"></iframe>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @role('Dosen')
                                            <td class="row">
                                                <div class="col p-2">
                                                    <button class="btn btn-info btn-sm me-2" 
                                                            onclick="window.location.href='{{ route('usulan.show', ['jenis' => $jenis, 'id' => $usulan->id]) }}'"
                                                            >
                                                        <i class="fas fa-info-circle"></i> Anggota
                                                    </button>
                                                </div>
                                                @php
                                                // Ambil data dosen terkait user yang sedang login
                                                $dosen = \App\Models\Dosen::where('user_id', auth()->user()->id)->first();
                                            
                                                // Ambil data anggota dosen berdasarkan dosen yang login
                                                $anggotaDosencek = null;
                                                if ($dosen) {
                                                    $anggotaDosencek = \App\Models\AnggotaDosen::where('dosen_id', $dosen->id)->first();
                                                } 
                                            @endphp
                                            
                                        
                                            @if ($anggotaDosencek->status_anggota == 'ketua')
                                                <div class="col p-2">
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditUsulan" onclick="fillEditForm({{ $usulan }})"  @if ($usulan->status === 'submitted') disabled @endif>
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <!-- Form untuk mengajukan usulan -->
                                                    <form action="{{ route('usulan.submit', ['jenis' => $jenis, 'usulan' => $usulan->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengajukan usulan ini?')"  @if ($usulan->status === 'submitted') disabled @endif>
                                                            <i class="fas fa-paper-plane"></i> Ajukan
                                                        </button>
                                                    </form>
                                                </div>
                                             @endif
                                                <!-- Modal Edit Usulan -->
                                                <div class="modal fade" id="modalEditUsulan" tabindex="-1" aria-labelledby="modalEditUsulanLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalEditUsulanLabel">Edit Usulan</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form id="formEditUsulan" method="POST" action="{{ route('usulan.update', ['jenis' => $jenis, 'id' => $usulan->id]) }}">

                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Judul Usulan:</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" name="judul_usulan" id="judul_usulan" class="form-control" value="{{ $usulan->judul_usulan }}">
                                                                            <span class="text-danger error-text judul_usulan_error"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Tahun Pelaksanaan:</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="number" name="tahun_pelaksanaan" id="tahun_pelaksanaan" class="form-control" value="{{ $usulan->tahun_pelaksanaan }}">
                                                                            <span class="text-danger error-text tahun_pelaksanaan_error"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Status:</label>
                                                                        <div class="col-lg-8">
                                                                        <select name="status" class="form-control select2">
                                                                            <option value="" disabled selected>Pilih Status</option>
                                                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : (isset($usulan) && $usulan->status == 'draft' ? 'selected' : '') }}>Draft</option>
                                                                            <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : (isset($usulan) && $usulan->status == 'submitted' ? 'selected' : '') }}>Submitted</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Rumpun Ilmu:</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" name="rumpun_ilmu" id="rumpun_ilmu" class="form-control" value="{{ $usulan->rumpun_ilmu }}" readonly>
                                                                            <span class="text-danger error-text rumpun_ilmu_error"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Bidang Fokus:</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" name="bidang_fokus" id="bidang_fokus" class="form-control" value="{{ $usulan->bidang_fokus }}">
                                                                            <span class="text-danger error-text bidang_fokus_error"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Tema Penelitian:</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" name="tema_penelitian" id="tema_penelitian" class="form-control" value="{{ $usulan->tema_penelitian }}">
                                                                            <span class="text-danger error-text tema_penelitian_error"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Topik Penelitian:</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" name="topik_penelitian" id="topik_penelitian" class="form-control" value="{{ $usulan->topik_penelitian }}">
                                                                            <span class="text-danger error-text topik_penelitian_error"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Lama Kegiatan (Tahun):</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="number" name="lama_kegiatan" id="lama_kegiatan" class="form-control" value="{{ $usulan->lama_kegiatan }}">
                                                                            <span class="text-danger error-text lama_kegiatan_error"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label class="col-lg-4 fw-bold">Dokumen Usulan (PDF):</label>
                                                                        <div class="col-lg-8">
                                                                            <input type="file" name="dokumen_usulan" class="form-control form-control-lg form-control-solid" accept=".pdf" @if(!$usulan->dokumen_usulan) required @endif>
                                                                            @if ($usulan->dokumen_usulan)
                                                                                <small>File saat ini: <a href="{{ Storage::url($usulan->dokumen_usulan) }}" target="_blank">Lihat Dokumen</a></small>
                                                                            @endif
                                                                            @error('dokumen_usulan')
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            @endrole
                                            @role('Kepala LPPM')
                                            <td>
                                                <div class="col p-2">
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditUsulan" onclick="fillEditForm({{ $usulan }})"  @if ($usulan->status === 'submitted') disabled @endif>
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <!-- Form untuk mengajukan usulan -->
                                                    <form action="{{ route('usulan.submit', ['jenis' => $jenis, 'usulan' => $usulan->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengajukan usulan ini?')"  @if ($usulan->status === 'submitted') disabled @endif>
                                                            <i class="fas fa-paper-plane"></i> Ajukan
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            
                                            @endrole
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Tidak ada usulan yang tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                    
                    <!--begin::Pagination-->
                    <div class="py-5">
                        <ul class="pagination">
                            {{ $usulans->links() }}
                        </ul>
                    </div>
                    <!--end::Pagination-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

<script>
    document.getElementById('myInput').addEventListener('keyup', function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('myInput');
        filter = input.value.toUpperCase();
        table = document.getElementById('myTable');
        tr = table.getElementsByTagName('tr');
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName('td')[0]; // Search by Judul Usulan
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


<script>
    function fillEditForm(usulan) {
    document.getElementById('judul_usulan').value = usulan.judul_usulan;
    document.getElementById('tahun_pelaksanaan').value = usulan.tahun_pelaksanaan;
    document.getElementById('status').value = usulan.status;
    document.getElementById('rumpun_ilmu').value = usulan.rumpun_ilmu;
    document.getElementById('bidang_fokus').value = usulan.bidang_fokus;
    document.getElementById('tema_penelitian').value = usulan.tema_penelitian;
    document.getElementById('topik_penelitian').value = usulan.topik_penelitian;
    document.getElementById('lama_kegiatan').value = usulan.lama_kegiatan;
}

</script>
