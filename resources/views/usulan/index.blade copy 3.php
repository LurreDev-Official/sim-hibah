@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

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
                                    <a href="{{ route('usulan.create', ['jenis' => $jenis]) }}" class="btn btn-primary">Tambah
                                        Usulan</a>
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
                                                            <li> {{ $item->reviewer->user->name}}</li>
                                                        @empty
                                                            <li>Belum ada reviewer yang ditugaskan</li>
                                                        @endforelse
                                                    </ul>
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
                                            <td>{{ $usulan->ketuaDosen->user->name }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#pdfModal{{ $usulan->id }}">
                                                    Lihat Dokumen
                                                </button>
                                            </td>
                                            <!-- Modal -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="pdfModal{{ $usulan->id }}" tabindex="-1"
                                                aria-labelledby="pdfModalLabel{{ $usulan->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="pdfModalLabel{{ $usulan->id }}">
                                                                Preview Dokumen</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
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
                                                <td class="row">
                                                    @php
                                                        // Ambil data dosen terkait user yang sedang login
                                                        $dosen = \App\Models\Dosen::where(
                                                            'user_id',
                                                            auth()->user()->id,
                                                        )->first();

                                                        // Ambil data anggota dosen berdasarkan dosen yang login
                                                        $anggotaDosencek = $dosen
                                                            ? \App\Models\AnggotaDosen::where(
                                                                'dosen_id',
                                                                $dosen->id,
                                                            )->first()
                                                            : null;
                                                    @endphp

                                                    @if (
                                                        $anggotaDosencek &&
                                                            $anggotaDosencek->status_anggota == 'ketua' &&
                                                            !in_array($usulan->status, ['submitted', 'review', 'revision', 'approved', 'rejected']))
                                                        <div class="col p-2">
                                                            <!-- Tombol Edit -->
                                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#modalEditUsulan"
                                                                onclick="fillEditForm({{ $usulan }})">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <div class="col">
                                                            <!-- Form untuk mengajukan usulan -->
                                                            <form id="submitUsulanForm_{{ $usulan->id }}"
                                                                action="{{ route('usulan.submit', ['jenis' => $jenis, 'usulan' => $usulan->id]) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('PATCH')
                                                            </form>

                                                            <!-- Tombol untuk Ajukan -->
                                                            <button id="submitUsulanButton_{{ $usulan->id }}"
                                                                type="button" class="btn btn-success btn-sm"
                                                                onclick="
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: 'Anda akan mengajukan usulan ini!',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Ya, ajukan!',
                                                                    cancelButtonText: 'Batal'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        document.getElementById('submitUsulanForm_{{ $usulan->id }}').submit();
                                                                    }
                                                                });
                                                            ">
                                                                <i class="fas fa-paper-plane"></i> Ajukan
                                                            </button>
                                                        </div>


                                                        <!-- SweetAlert Konfirmasi Pengajuan -->
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    @endif

                                                    <!-- Modal Edit Usulan -->
                                                    <div class="modal fade" id="modalEditUsulan" tabindex="-1"
                                                        aria-labelledby="modalEditUsulanLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalEditUsulanLabel">Edit
                                                                        Usulan</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form id="formEditUsulan" method="POST"
                                                                    action="{{ route('usulan.update', ['jenis' => $jenis, 'id' => $usulan->id]) }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="row mb-4">
                                                                            <label class="col-lg-4 fw-bold">Judul
                                                                                Usulan:</label>
                                                                            <div class="col-lg-8">
                                                                                <input type="text" name="judul_usulan"
                                                                                    id="judul_usulan" class="form-control"
                                                                                    value="{{ $usulan->judul_usulan }}">
                                                                                <span
                                                                                    class="text-danger error-text judul_usulan_error"></span>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Tambahkan semua input lainnya seperti pada kode awal -->
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan
                                                                            Perubahan</button>
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
                                                        <!-- Button Pilih/Kirim Ke Reviewer -->
                                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#pilihKirimReviewerModal-{{ $usulan->id }}">
                                                            <i class="fas fa-paper-plane"></i> Pilih/Kirim Ke Reviewer
                                                        </button>
                                                    
                                                        <!-- Modal Pilih/Kirim Ke Reviewer -->
                                                        <div class="modal fade" id="pilihKirimReviewerModal-{{ $usulan->id }}" tabindex="-1"
                                                            aria-labelledby="pilihKirimReviewerModalLabel-{{ $usulan->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="pilihKirimReviewerModalLabel-{{ $usulan->id }}">Pilih/Kirim Usulan ke Reviewer</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Silakan pilih reviewer untuk mengirim atau mengirim ulang usulan ini.</p>
                                                    
                                                                        <!-- Form untuk Pilih/Kirim Reviewer -->
                                                                        <form id="pilihKirimReviewerForm-{{ $usulan->id }}"
                                                                            action="{{ route('usulan.kirim', ['jenis' => $jenis]) }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="usulan_id" value="{{ $usulan->id }}">
                                                                            <input type="hidden" name="jenis" value="{{ $jenis }}">
                                                                            <input type="hidden" name="action" id="action-{{ $usulan->id }}" value="">
                                                                            <!-- Action diset lewat JavaScript -->
                                                    
                                                                            <!-- Dropdown Reviewer -->
                                                                            <div class="mb-3">
                                                                                <label for="reviewer_id-{{ $usulan->id }}" class="form-label">Pilih Reviewer</label>
                                                                                <select name="reviewer_id[]" id="reviewer_id-{{ $usulan->id }}" class="form-select" multiple required>
                                                                                    <option value="" disabled selected>Pilih Reviewer</option>
                                                                                    @foreach ($reviewers as $reviewer)
                                                                                        <option value="{{ $reviewer->id }}">{{ $reviewer->user->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                        <!-- Tombol Kirim -->
                                                                        <button type="button" class="btn btn-primary"
                                                                            onclick="submitForm('{{ $usulan->id }}', 'kirim')">Kirim</button>
                                                                        <!-- Tombol Kirim Ulang -->
                                                                        <button type="button" class="btn btn-warning"
                                                                            onclick="submitForm('{{ $usulan->id }}', 'kirim_ulang')">Kirim Ulang</button>
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
                            <div class="col p-2">
                                <button class="btn btn-danger btn-sm"
                                    onclick="deleteUsulan('{{ $jenis }}', {{ $usulan->id }})"
                                   
                                    <i class="fas fa-trash-alt"></i> Hapus 
                                </button>
                            </div>
                            

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
                                                url: '{{ url('usulan') }}/' + jenis + '/' + id + '/hapus',
                                                    id, // URL endpoint dengan jenis dan ID usulan
                                                type: 'DELETE', // HTTP method DELETE
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
