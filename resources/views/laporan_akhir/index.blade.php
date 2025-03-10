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
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Laporan Akhir
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 11 17ZM17 7.53333"
                                            fill="black" />
                                    </svg>
                                </span>
                                <input type="text" id="myInput" class="form-control form-control-solid w-250px ps-15"
                                    placeholder="Search Laporan" name="search" />
                            </div>

                        
                            
                            <div class="d-flex justify-content-end mb-4 p-2">
                                <!-- Tombol Tambah Laporan Akhir -->
                                @if (isset($dosen) && $usulans->isNotEmpty())
                                    @foreach ($usulans as $usulan)
                                        @if ($usulan->ketua_dosen_id == $dosen->id)
                                            <a href="{{ route('laporan-akhir.create', ['jenis' => 'penelitian']) }}" class="btn btn-primary me-2">
                                                <i class="fas fa-plus"></i> Tambah Laporan Akhir
                                            </a>
                                            @break <!-- Hentikan loop jika kondisi terpenuhi -->
                                        @endif
                                    @endforeach
                                @endif
                            
                                <!-- Tombol Export Data -->
                                <a href="{{ url('laporan-akhir/export/' . $jenis) }}" class="btn btn-success">
                                    <i class="fa fa-download"></i> Export Data
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-laporan">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>No Usulan</th>
                                    <th>Tanggal</th>
                                    <th>Judul Laporan</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="myTable">
                                @foreach ($laporanAkhir as $laporan)

                                @php
                                    // Ambil data dosen terkait user yang sedang login
                                    $dosen = \App\Models\Dosen::where('user_id', auth()->user()->id)->first();

                                    // Ambil data anggota dosen berdasarkan dosen yang login
                                    $anggotaDosencek = null;
                                    if ($dosen) {
                                        $anggotaDosencek = \App\Models\AnggotaDosen::where(
                                            'dosen_id',
                                            $dosen->id,
                                        )->first();
                                    }
                                @endphp


                                    <tr>
                                        <td>{{ $laporan->usulan->id }}</td>
                                        <td>{{ $laporan->created_at }}</td>
                                        <td>{{ $laporan->usulan->judul_usulan }}</td>
                                        <td>{{ $laporan->jenis }}</td>
                                        <td>
                                            @if ($laporan->status == 'submitted')
                                                <span class="badge bg-info">Terkirim</span>
                                            @elseif ($laporan->status == 'review')
                                                <span class="badge bg-primary">Sedang Ditinjau</span>
                                                @php
                                                    // Ambil reviewer dari PenilaianReviewer berdasarkan laporanakhir_id
                                                    $getreviewer = \App\Models\PenilaianReviewer::where(
                                                        'laporanakhir_id',
                                                        $laporan->id,
                                                    )
                                                        ->with('reviewer') // Load relasi reviewer dan user untuk nama
                                                        ->get();
                                                @endphp
                                                <ul>
                                                    @forelse ($getreviewer as $item)
                                                        @role('Kepala LPPM')
                                                            <li>{{ $item->reviewer->user->name }}</li>
                                                        @endrole
                                                    @empty
                                                        <li>Belum ada reviewer yang ditugaskan</li>
                                                    @endforelse
                                                </ul>
                                            @elseif ($laporan->status == 'revision')
                                                <span class="badge bg-warning">Perlu Revisi</span>
                                            @elseif ($laporan->status == 'waiting approved')
                                                <span class="badge bg-secondary text-black">Menunggu Persetujuan</span>
                                            @elseif ($laporan->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Tombol Lihat Dokumen Laporan Akhir -->
                                            <div class="col p-2">
                                                @if ($laporan->dokumen_laporan_akhir)
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#lihatDokumenModal-{{ $laporan->id }}">
                                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Laporan Akhir
                                                    </button>
                                                @else
                                                    <span class="text-danger">Tidak ada dokumen</span>
                                                @endif
                                            </div>

                                            <!-- Modal untuk Melihat Dokumen PDF -->
                                            @if ($laporan->dokumen_laporan_akhir)
                                                <div class="modal fade" id="lihatDokumenModal-{{ $laporan->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="lihatDokumenModalLabel-{{ $laporan->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-fullscreen">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="lihatDokumenModalLabel-{{ $laporan->id }}">Lihat
                                                                    Dokumen Laporan Akhir</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Iframe untuk Menampilkan PDF -->
                                                                <iframe
                                                                    src="{{ asset('storage/' . $laporan->dokumen_laporan_akhir) }}"
                                                                    width="100%" height="500px" frameborder="0"></iframe>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <a href="{{ asset('storage/' . $laporan->dokumen_laporan_akhir) }}"
                                                                    class="btn btn-success" target="_blank">
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @role('Dosen')
                                            <td>
                                                @if ($laporan->status == 'submitted')
                                                <!-- Tombol Edit -->
                                                <button type="button" class="btn btn-light btn-active-light-primary btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $laporan->id }}">
                                                    Edit
                                                </button>
                                                @endif

                                                <!-- Tombol Download Bukti ACC -->
                                                @if ($laporan->status == 'approved')
                                                    <div class="col p-2">
                                                        <a href="{{ route('laporan-akhir.cetakBuktiACC', $laporan->id) }}"
                                                            class="btn btn-success btn-sm" target="_blank">
                                                            <i class="fas fa-download"></i> Download Bukti ACC
                                                        </a>
                                                    </div>
                                                @endif

                                              

                                                <!-- Tombol Perbaiki Revisi -->
                                                @if ($laporan->status == 'revision')
                                                    <div class="d-flex justify-content-end mt-4">
                                                        <a href="{{ route('laporan-akhir.perbaikiRevisi', ['jenis' => $jenis, 'id' => $laporan->id]) }}"
                                                            class="btn btn-secondary">
                                                            <i class="fas fa-edit"></i> Perbaiki Revisi
                                                        </a>
                                                    </div>
                                                @endif

                                                <!-- Tombol Hapus (Untuk Ketua) -->
                                                @if ($anggotaDosencek->status_anggota == 'ketua' && $laporan->status == 'draft')
                                                    <div class="col p-2">
                                                        <button class="btn btn-danger btn-sm"
                                                            onclick="deleteUsulan('{{ $jenis }}', {{ $laporan->id }})">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                        @endrole


                                        @role('Kepala LPPM')
                                            <td>
                                                <!-- Tombol Pilih/Kirim Ke Reviewer -->
                                                <div class="col p-2">
                                                    @if ($laporan->status == 'draft' || $laporan->status == 'submitted')
                                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#pilihKirimReviewerModal-{{ $laporan->id }}">
                                                            <i class="fas fa-paper-plane"></i> Kirim Ke Reviewer
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Modal Pilih/Kirim Ke Reviewer -->
                                                <div class="modal fade" id="pilihKirimReviewerModal-{{ $laporan->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="pilihKirimReviewerModalLabel-{{ $laporan->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="pilihKirimReviewerModalLabel-{{ $laporan->id }}">
                                                                    Kirim ke Reviewer
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Silakan pilih reviewer untuk mengirim atau mengirim ulang
                                                                    Laporan Akhir ini.</p>
                                                                <form id="pilihKirimReviewerForm-{{ $laporan->id }}"
                                                                    action="{{ route('laporan-akhir.kirim', ['jenis' => $jenis]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="laporanakhir_id"
                                                                        value="{{ $laporan->id }}">
                                                                    <input type="hidden" name="jenis"
                                                                        value="{{ $jenis }}">
                                                                    <input type="hidden" name="action"
                                                                        id="action-{{ $laporan->id }}" value="">
                                                                    <!-- Dropdown Reviewer -->
                                                                    <div class="mb-3">
                                                                        <label for="reviewer_id-{{ $laporan->id }}"
                                                                            class="form-label">Pilih Reviewer</label>
                                                                        <select name="reviewer_id[]"
                                                                            id="reviewer_id-{{ $laporan->id }}"
                                                                            class="form-select" multiple required>
                                                                            <option value="" disabled selected>Pilih
                                                                                Reviewer</option>
                                                                            @foreach ($reviewers as $reviewer)
                                                                                <option value="{{ $reviewer->id }}">
                                                                                    {{ $reviewer->user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <button type="button" class="btn btn-primary"
                                                                    onclick="submitForm('{{ $laporan->id }}', 'kirim')">Kirim</button>
                                                                <button type="button" class="btn btn-warning"
                                                                    onclick="submitForm('{{ $laporan->id }}', 'kirim_ulang')">Kirim
                                                                    Ulang</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                    function submitForm(usulanId, action) {
                                                        document.getElementById('action-' + usulanId).value = action;
                                                        document.getElementById('pilihKirimReviewerForm-' + usulanId).submit();
                                                    }
                                                </script>

                                                <!-- Tombol Approve/Reject Usulan -->
                                                @if ($laporan->allReviewersAccepted && $laporan->status !== 'approved')
                                                    <div class="col p-2">
                                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#approveRejectModal{{ $laporan->id }}">
                                                            Diterima
                                                        </button>
                                                    </div>

                                                    <!-- Modal untuk Approve/Reject Usulan -->
                                                    <div class="modal fade" id="approveRejectModal{{ $laporan->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="approveRejectModalLabel{{ $laporan->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="approveRejectModalLabel{{ $laporan->id }}">
                                                                        Diterima </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form
                                                                        action="{{ route('laporan-akhir.updateStatus', $laporan->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group">
                                                                            <label for="status">Select Status:</label>
                                                                            <select name="status" id="status"
                                                                                class="form-select" required>
                                                                                <option value="approved">Diterima</option>
                                                                                <option value="rejected">Ditolak</option>
                                                                            </select>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                tidak ada aksi
                                                @endif

                                            </td>

                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                            <script>
                                                function deleteUsulan(jenis, id) {
                                                    Swal.fire({
                                                        title: 'Apakah Anda yakin?',
                                                        text: "Usulan ini akan dihapus secara permanen!",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Ya, hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $.ajax({
                                                                url: '{{ url('usulan') }}/' + jenis + '/' + id + '/hapus',
                                                                type: 'DELETE',
                                                                data: {
                                                                    "_token": "{{ csrf_token() }}",
                                                                },
                                                                success: function(response) {
                                                                    Swal.fire('Dihapus!', response.success, 'success').then(() => {
                                                                        location.reload();
                                                                    });
                                                                },
                                                                error: function(xhr) {
                                                                    let errorMessage = (xhr.status === 404) ?
                                                                        xhr.responseJSON.error :
                                                                        'Terjadi kesalahan: ' + xhr.responseJSON.error;
                                                                    Swal.fire('Error!', errorMessage, 'error');
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            </script>
                                        @endrole




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

@foreach ($laporanAkhir as $laporan)
    <div class="modal fade" id="editModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('laporan-akhir.update', $laporan->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Dokumen Laporan Akhir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- File upload input -->
                        <div class="mb-3">
                            <label for="dokumen_laporan_akhir{{ $laporan->id }}" class="form-label">Dokumen
                                Laporan</label>
                            <input type="file" class="form-control"
                                id="dokumen_laporan_akhir{{ $laporan->id }}" name="dokumen_laporan_akhir"
                                accept=".pdf,.doc,.docx">
                            <!-- Show current document if exists -->
                            @if ($laporan->dokumen_laporan_akhir)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $laporan->dokumen_laporan_akhir) }}"
                                        target="_blank">Lihat Dokumen Lama</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach



@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-laporan').DataTable({
            searchable: true,
        });
    </script>
@endsection
