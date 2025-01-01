<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti ACC - Usulan</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px;
            position: relative; /* Untuk posisi absolut di dalam body */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .barcode {
            text-align: center;
            margin-top: 20px;
        }
        .barcode img {
            width: 150px; /* Atur ukuran QR Code sesuai kebutuhan */
            height: auto;
        }
        .footer {
            position: absolute; /* Posisi absolut untuk menempatkan di pojok kanan bawah */
            bottom: 20px; /* Jarak dari bawah */
            right: 20px; /* Jarak dari kanan */
            text-align: right; /* Rata kanan */
            font-size: 10pt; /* Ukuran font */
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lembaga Penelitian dan Pengabdian Masyarakat (LPPM)</h1>
        <h2>Universitas Hasyim Asy'ari</h2> <!-- Nama universitas diperbarui -->
        <p>Alamat: Jl. Contoh No. 123, Jombang</p> <!-- Alamat disesuaikan -->
        <p>Telp: (0274) 1234567 | Email: lppm@unhasy.ac.id</p>
    </div>

    <h1>Bukti ACC Usulan Proposal di danai</h1>
    
    <table>
        <tr>
            <th>Judul Usulan</th>
            <td>{{ $usulan->judul_usulan }}</td>
        </tr>
        <tr>
            <th>Jenis Skema</th>
            <td>{{ $usulan->jenis_skema }}</td>
        </tr>
        <tr>
            <th>Tahun Pelaksanaan</th>
            <td>{{ $usulan->tahun_pelaksanaan }}</td>
        </tr>
        <tr>
            <th>Ketua Dosen</th>
            <td>{{ $usulan->ketuaDosen->user->name }}</td>
        </tr>
        <tr>
            <th>Anggota Dosen</th>
            <td>
                <ul>
                    @foreach($usulan->anggotaDosen as $anggota)
                        @if($anggota->status_anggota == 'anggota') <!-- Ganti 'anggota' dengan status yang sesuai -->
                            <li>{{ $anggota->dosen->user->name }} (NIDN: {{ $anggota->dosen->nidn }})</li>
                        @endif
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <th>Anggota Mahasiswa</th>
            <td>
                <ul>
                    @foreach($usulan->anggotaMahasiswa as $mahasiswa)
                        <li>{{ $mahasiswa->nama_lengkap }} (NIM: {{ $mahasiswa->nim }}), Fakultas: {{ $mahasiswa->fakultas }}, Prodi: {{ $mahasiswa->prodi }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Jombang, {{ date('d F Y') }}</p> <!-- Menampilkan tanggal saat ini -->
        <img src="{{ $barcodeBase64 }}" alt="QR Code">
        <p style="margin-top: 10px; font-style: italic; font-size: 10pt;">
            Telah disetujui oleh Ketua LPPM sebagai tanda tangan elektronik.
        </p>
    </div>
</body>
</html>