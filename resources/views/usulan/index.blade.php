@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Usulan {{ $jenis }}
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">List</small>
                    </h1>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <br>
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <!-- Search Icon -->
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
                                <!-- Search Input -->
                                <input type="text" class="form-control form-control-solid w-250px ps-14" id="myInput"
                                    placeholder="Search usulan" />
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
                                    @php
                                        $dosen = \App\Models\Dosen::where('user_id', auth()->user()->id)->first();
                                        $scoreSinta = $dosen->score_sinta;
                                    @endphp
                                    @if ($isButtonActive)
                                            <a href="{{ route('usulan.create', ['jenis' => $jenis]) }}" class="btn btn-primary mb-3">Tambah Usulan </a>
                                            @else
                                            @endif
                                        <!-- @if($usulans->count() < 2 )
                                            <a href="{{ route('usulan.create', ['jenis' => $jenis]) }}" class="btn btn-primary mb-3">Tambah Usulan </a>
                                            
                                        @endif -->
                                @endrole
                                <!-- Export Button -->
                                <a href="{{ route('usulan.export', ['jenis' => $jenis]) }}" class="btn btn-success">
                                    <i class="fa fa-download"></i> Export Data
                                </a>


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
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-usulan">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">No Usulan</th>
                                        <th class="min-w-150px">Judul Usulan</th>
                                        <th class="min-w-150px">Tahun Pelaksanaan</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-150px">Rumpun Ilmu</th>
                                        <th class="min-w-150px">Bidang Fokus</th>
                                        {{-- <th class="min-w-150px">Tema Penelitian</th> --}}
                                        {{-- <th class="min-w-150px">Topik Penelitian</th> --}}
                                        <th class="min-w-150px">Lama Kegiatan</th>
                                        <th class="min-w-150px">Ketua Dosen</th>
                                        <th class="min-w-150px">Dokumen Usulan</th>
                                        <th class="min-w-150px">Aksi</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->

                                <!--begin::Table body-->
                                <tbody>
                                    @forelse($usulans as $usulan)
                                        <tr>
                                            <td>{{ $usulan->id }}</td>
                                            <td>{{ $usulan->judul_usulan }}</td>
                                            <td>{{ $usulan->tahun_pelaksanaan }}</td>
                                            <td>
                                                   
                                                @if ($usulan->status == 'draft')
                                                    <span class="badge bg-warning">Draf</span>
                                                @elseif ($usulan->status == 'submitted')
                                                    <span class="badge bg-info">Dikirim</span>
                                                @elseif ($usulan->status == 'review')
                                                    <span class="badge bg-primary">Sedang Ditinjau</span>
                                                    @php
                                                        // Ambil reviewer dari PenilaianReviewer berdasarkan usulan_id
                                                        $getreviewer = \App\Models\PenilaianReviewer::where(
                                                            'usulan_id',
                                                            $usulan->id,
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
                                                            <li>Belum ada peninjau yang ditugaskan</li>
                                                        @endforelse
                                                    </ul>
                                                @elseif ($usulan->status == 'revision'&& $usulan->status != 'approved')

                                                    <span class="badge bg-warning">Perlu Revisi</span>

                                                @elseif ($usulan->status == 'waiting approved')


                                                    <span class="badge bg-secondary text-black">Menunggu Persetujuan</span>
@php
        // Ambil satu record PenilaianReviewer berdasarkan usulan_id
        $penilaianReviewer = \App\Models\PenilaianReviewer::where('usulan_id', $usulan->id)
            ->where('status_penilaian', 'sudah dinilai') // Status penilaian yang diterima
            ->first(); // Ambil satu record pertama yang ditemukan
    @endphp

    <p>Total Nilai:
        @if ($penilaianReviewer)
<span class="badge bg-danger fs-4 p-3">{{ $penilaianReviewer->total_nilai }}</span>
        @else
            Belum Ada Penilaian
        @endif
    </p>
                                                @elseif ($usulan->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif ($usulan->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>

                                            <td>{{ $usulan->rumpun_ilmu }}</td>
                                            <td>{{ $usulan->bidang_fokus }}</td>
                                            {{-- <td>{{ $usulan->tema_penelitian }}</td> --}}
                                            {{-- <td>{{ $usulan->topik_penelitian }}</td> --}}
                                            <td>{{ $usulan->lama_kegiatan }} tahun</td>
                                            <td>{{ $usulan->ketuaDosen->user->name }}</td>
                                            <td>
                                                <div class="d-flex justify-content-start gap-3">
                                                    <!-- Button for "Lihat Dokumen Asli" -->
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#pdfModal{{ $usulan->id }}">
                                                        Lihat Dokumen Asli
                                                    </button>

                                                    <!-- Button for "Lihat Dokumen Perbaikan" -->
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#perbaikanModal{{ $usulan->id }}">
                                                        Lihat Dokumen Perbaikan
                                                    </button>
                                                </div>

                                                <!-- Modal for Edit File -->
                                                <div class="modal fade" id="editFileModal{{ $usulan->id }}"
                                                    tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editFileModalLabel">Upload
                                                                    Dokumen Usulan Baru</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('usulan.update', ['jenis' => $usulan->jenis_skema, 'id' => $usulan->id]) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <div class="mb-3">
                                                                        <label for="dokumen_usulan" class="form-label">Pilih
                                                                            File PDF
                                                                            Usulan</label>
                                                                        <input type="file" class="form-control"
                                                                            name="dokumen_usulan" accept=".pdf" required>
                                                                        @error('dokumen_usulan')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>

                                                                    <button type="submit"
                                                                        class="btn btn-primary">Upload</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal for viewing the corrected document -->
                                                <div class="modal fade" id="perbaikanModal{{ $usulan->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="perbaikanModalLabel{{ $usulan->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="perbaikanModalLabel{{ $usulan->id }}">Dokumen
                                                                    Perbaikan Usulan</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Fetch the UsulanPerbaikan related to this Usulan -->
                                                                @php
                                                                    $usulanPerbaikan = \App\Models\UsulanPerbaikan::where(
                                                                        'usulan_id',
                                                                        $usulan->id,
                                                                    )->first();
                                                                @endphp

                                                                @if ($usulanPerbaikan && $usulanPerbaikan->dokumen_usulan)
                                                                    <!-- Embed the corrected document or provide a link to download -->
                                                                    <embed
                                                                        src="{{ asset('storage/' . $usulanPerbaikan->dokumen_usulan) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px" />
                                                                @else
                                                                    <p>Dokumen perbaikan tidak tersedia.</p>
                                                                @endif
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <!-- Modal -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="pdfModal{{ $usulan->id }}" tabindex="-1"
                                                aria-labelledby="pdfModalLabel{{ $usulan->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="pdfModalLabel{{ $usulan->id }}">
                                                                Preview Dokumen</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <iframe src="{{ Storage::url($usulan->dokumen_usulan) }}"
                                                                style="width: 100%; height: 100vh;"
                                                                frameborder="0"></iframe>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @role('Dosen')
                                                <td>
                                                    @if ($usulan->status == 'approved')
                                                        <div class="col p-2">
                                                            <a href="{{ route('usulan.cetakBuktiACC', $usulan->id) }}"
                                                                class="btn btn-success btn-sm" target="_blank">
                                                                <i class="fas fa-download"></i> Download Bukti ACC
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="col p-2">
                                                        <!-- Tombol Detail -->
                                                        <button class="btn btn-info btn-sm"
                                                            onclick="showDetailUsulan('{{ $jenis }}', {{ $usulan->id }})">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                    </div>


                                                


                                                    <script>
                                                        function showDetailUsulan(jenis, id) {
                                                            // Navigasi ke URL detail usulan dengan parameter jenis dan id
                                                            const url = `/detail-usulan/${jenis}/${id}`;
                                                            window.location.href = url;
                                                        }
                                                    </script>

                                                    @php
                                                        // Ambil data dosen terkait user yang sedang login
                                                        $dosen = \App\Models\Dosen::where(
                                                            'user_id',
                                                            auth()->user()->id,
                                                        )->first();

                                                        // Jika dosen ditemukan, cek apakah dosen tersebut adalah ketua untuk usulan yang sesuai
                                                        $anggotaDosencek = null;
                                                        if ($dosen) {
                                                            // Cek apakah dosen yang login adalah ketua dari usulan ini dengan mencocokkan ketua_dosen_id
                                                            if ($usulan->ketua_dosen_id == $dosen->id) {
                                                                $anggotaDosencek = true;
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($anggotaDosencek && in_array($usulan->status, ['draft', 'submitted']))
                                                        <div class="col p-2">
                                                            <!-- Button to Delete Usulan -->
                                                            <button class="btn btn-danger btn-sm"
                                                                onclick="deleteUsulan('{{ $jenis }}', {{ $usulan->id }})">
                                                                <i class="fas fa-trash-alt"></i> Hapus
                                                            </button>

                                                            <!-- Button to Edit Usulan with margin-right for spacing -->
                                                            <a href="{{ route('usulan.edit', ['jenis' => $usulan->jenis_skema, 'id' => $usulan->id]) }}"
                                                                class="btn btn-warning btn-sm ms-2">
                                                                <i class="fas fa-pencil-alt"></i> Edit
                                                            </a>
                                                        </div>
                                                    @else
                                                        <!-- Display notification when user is not the ketua or usulan status is not valid -->
                                                        <div class="col p-2">
                                                            @if (!$anggotaDosencek)
                                                                <p class="text-warning">Anda bukan ketua untuk usulan ini.</p>
                                                            @elseif ($usulan->status == 'revision')
                                                                <div class="d-flex justify-content-end mt-4">
                                                                    <a href="{{ route('usulan.perbaikiRevisi', ['jenis' => $jenis, 'id' => $usulan->id]) }}"
                                                                        class="btn btn-secondary">
                                                                        <i class="fas fa-edit"></i> Perbaiki Revisi
                                                                    </a>
                                                                </div>
                                                            @elseif (!in_array($usulan->status, ['draft', 'submitted']))
                                                                <p class="text-warning">Usulan ini tidak dalam status yang
                                                                    dapat diedit atau dihapus.</p>
                                                            @endif
                                                        </div>
                                                    @endif


                                        </td>

                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <script>
                                            function deleteUsulan(jenis, id) {
                                                // Menggunakan SweetAlert2 untuk dialog konfirmasi
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
                                                        // Jika konfirmasi di-klik
                                                        $.ajax({
                                                            url: '{{ url('usulan') }}/' + jenis + '/' + id +
                                                                '/hapus', // URL endpoint dengan jenis dan ID usulan
                                                            type: 'POST', // HTTP method POST
                                                            data: {
                                                                "_token": "{{ csrf_token() }}", // Mengirimkan token CSRF untuk keamanan
                                                            },
                                                            success: function(response) {
                                                                // Tampilkan pesan sukses dengan SweetAlert2
                                                                Swal.fire(
                                                                    'Dihapus!',
                                                                    response.success,
                                                                    'success'
                                                                ).then(() => {
                                                                    location
                                                                        .reload(); // Reload halaman setelah penghapusan berhasil
                                                                });
                                                            },
                                                            error: function(xhr) {
                                                                if (xhr.status === 404) {
                                                                    // Jika usulan tidak ditemukan
                                                                    Swal.fire(
                                                                        'Error!',
                                                                        xhr.responseJSON.error,
                                                                        'error'
                                                                    );
                                                                } else {
                                                                    // Jika terjadi error lainnya
                                                                    Swal.fire(
                                                                        'Error!',
                                                                        'Terjadi kesalahan: ' + xhr.responseJSON.error,
                                                                        'error'
                                                                    );
                                                                }
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        </script>
                                    @endrole
                                    @role('Kepala LPPM')
                                        <td>
                                            <div class="col p-2">

                                                     

                                                <!-- Button Kirim Ke Reviewer -->
                                                @if ($usulan->status == 'draft' || $usulan->status == 'submitted' || $usulan->status == 'review')
                                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#pilihKirimReviewerModal-{{ $usulan->id }}">
                                                        <i class="fas fa-paper-plane"></i> Kirim Ke Reviewer
                                                    </button>
                                                @endif


                                                <!-- Modal Kirim Ke Reviewer -->
                                                <div class="modal fade" id="pilihKirimReviewerModal-{{ $usulan->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="pilihKirimReviewerModalLabel-{{ $usulan->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="pilihKirimReviewerModalLabel-{{ $usulan->id }}">
                                                                    Pilih/Kirim Usulan ke Reviewer</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Silakan pilih reviewer untuk mengirim atau mengirim
                                                                    ulang usulan ini.</p>

                                                                <!-- Form untuk Pilih/Kirim Reviewer -->
                                                                <form id="pilihKirimReviewerForm-{{ $usulan->id }}"
                                                                    action="{{ route('usulan.kirim', ['jenis' => $jenis]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="usulan_id"
                                                                        value="{{ $usulan->id }}">
                                                                    <input type="hidden" name="jenis"
                                                                        value="{{ $jenis }}">
                                                                    <input type="hidden" name="action"
                                                                        id="action-{{ $usulan->id }}" value="">
                                                                    <!-- Action diset lewat JavaScript -->

                                                                    <!-- Dropdown Reviewer -->
                                                                    <div class="mb-3">
                                                                        <label for="reviewer_id-{{ $usulan->id }}"
                                                                            class="form-label">Pilih Reviewer</label>
                                                                        <select name="reviewer_id[]"
                                                                            id="reviewer_id-{{ $usulan->id }}"
                                                                            class="form-select" multiple required>
                                                                            <option value="" disabled selected>
                                                                                Pilih Reviewer</option>
                                                                            @foreach ($reviewers as $reviewer)
                                                                                <option value="{{ $reviewer->id }}">
                                                                                    {{ $reviewer->user->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <!-- Tombol Kirim -->
                                                                <button type="button" class="btn btn-primary"
                                                                    onclick="submitForm('{{ $usulan->id }}', 'kirim')">Kirim</button>
                                                                <!-- Tombol Kirim Ulang -->
                                                                <button type="button" class="btn btn-warning"
                                                                    onclick="submitForm('{{ $usulan->id }}', 'kirim_ulang')">Kirim
                                                                    Ulang</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                function submitForm(usulanId, action) {
                                                    // Set value 'action' berdasarkan pilihan tombol
                                                    document.getElementById('action-' + usulanId).value = action;

                                                    // Submit form berdasarkan ID dinamis
                                                    document.getElementById('pilihKirimReviewerForm-' + usulanId).submit();
                                                }
                                            </script>

                            </div>
                            @if ($usulan->allReviewersAccepted && $usulan->status == 'waiting approved')
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#approveRejectModal{{ $usulan->id }}">
                                    Diterima Atau Ditolak
                                </button>


                                <!-- Modal for Approving or Rejecting Usulan -->
                                @if ($usulan->allReviewersAccepted && $usulan->status == 'waiting approved')
                                    <div class="modal fade" id="approveRejectModal{{ $usulan->id }}" tabindex="-1"
                                        aria-labelledby="approveRejectModalLabel{{ $usulan->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveRejectModalLabel{{ $usulan->id }}">
                                                        Diterima Atau Ditolak Usulan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('usulan.updateStatus', $usulan->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Dropdown to choose status -->
                                                        <div class="form-group">
                                                            <label for="status">Pilih Status:</label>
                                                            <select name="status" id="status" class="form-select"
                                                                required>
                                                                <option value="approved">Diterima</option>
                                                                <option value="rejected">Ditolak</option>
                                                            </select>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <span class="text-danger">Waiting for approval from all reviewers.</span>
                            @endif
                            {{-- <div class="col p-2">
                                <!-- Tombol Batal Usulan -->
                                <a href="{{ route('usulan.batal', ['id' => $usulan->id, 'jenis' => $usulan->jenis_skema]) }}" class="btn btn-danger">Batal</a>
                            </div> --}}

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
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var xin_table = $('#table-usulan').DataTable({
            searchable: true,
        });
    </script>
@endsection
