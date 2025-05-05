<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti ACC - Usulan</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            margin: 0;
            padding: 30px;
            position: relative;
        }
        h1, h2 {
            text-align: center;
            margin: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1, .header h2 {
            margin: 0;
        }
        .header p {
            font-size: 10pt;
            margin: 5px 0;
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
        .footer {
            position: absolute;
            bottom: 30px;
            right: 30px;
            text-align: right;
            font-size: 10pt;
        }
        .footer p {
            margin: 0;
            padding: 0;
        }
        .barcode {
            text-align: center;
            margin-top: 10px;
        }
        .barcode img {
            width: 120px;
            height: auto;
        }
        .signature {
            margin-top: 10px;
            font-size: 10pt;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lembaga Penelitian dan Pengabdian Masyarakat (LPPM)</h1>
        <h2>Universitas Hasyim Asy'ari</h2>
        <p>Tebuireng, Jl. Irian Jaya No.55, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471</p>
        <p>Telp: (0274) 1234567 | Email: lppm@unhasy.ac.id</p>
    </div>

    <h1>Bukti ACC Usulan Proposal yang Didanai</h1>

    <table style="width: 100%; text-align: center; border-collapse: collapse;">
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
                        @else
                            <li>Tidak ada anggota dosen.</li>
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
                    @if($usulan->anggotaMahasiswa->isEmpty())
                        <li>Tidak ada anggota mahasiswa.</li>
                    @endif
                </ul>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Jombang, {{ date('d F Y') }}</p>
        <div class="barcode">
            <img src="{{ $barcodeBase64 }}" alt="QR Code">
        </div>
        <p class="signature">
            Telah disetujui oleh Ketua LPPM sebagai tanda tangan elektronik.
        </p>
    </div>
</body>
</html>
