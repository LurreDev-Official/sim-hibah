@extends('layouts.main_layout')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
                <div class="card">
                    <!-- Card Header -->
                    <div class="card-header border-0 pt-6">
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <a href="{{ url('penilaian-usulan') }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body py-5">
                        <h3>Daftar Perbaikan</h3>

                        <!-- Cek jika tidak ada data -->
                        @if ($usulanPerbaikans->isEmpty())
                            <div class="alert alert-warning">
                                <p><strong>Belum ada perbaikan yang diajukan.</strong></p>
                            </div>
                        @else
                            <!-- Tampilkan Daftar Perbaikan -->
                            @foreach ($usulanPerbaikans as $usulanPerbaikan)
                                <div class="mb-4">
                                    <h5>Dokumen Usulan: {{ $usulanPerbaikan->dokumen_usulan }}</h5>

                                    <!-- Tombol untuk melihat dokumen PDF -->
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#pdfModal"
                                        data-pdf="{{ asset('storage/' . $usulanPerbaikan->dokumen_usulan) }}">
                                        Lihat PDF
                                    </button>

                                    <!-- Form untuk update status perbaikan -->
                                    <form action="{{ route('perbaikan-penilaian.update', $penilaianReviewers->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT') <!-- Menandakan bahwa ini adalah update -->

                                        <div class="mt-3">
                                            <label for="status" class="form-label">Status Perbaikan:</label>
                                            <select name="status" id="status" class="form-select" required>
                                                <option value="Diterima"
                                                    {{ $usulanPerbaikan->status == 'Diterima' ? 'selected' : '' }}>Diterima
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Tombol Simpan -->
                                        <button type="submit" class="btn btn-success mt-3">Simpan</button>
                                    </form>
                                </div>
                                <hr>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Menampilkan PDF -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Dokumen Usulan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="600px">
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Menyisipkan PDF ke dalam Modal -->
    <script>
        // Ketika tombol untuk melihat PDF diklik
        var pdfModal = document.getElementById('pdfModal');
        pdfModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Tombol yang diklik
            var pdfUrl = button.getAttribute('data-pdf'); // Ambil URL PDF

            // Menyisipkan URL PDF ke dalam embed
            var pdfViewer = document.getElementById('pdfViewer');
            pdfViewer.setAttribute('src', pdfUrl);
        });
    </script>
@endsection
