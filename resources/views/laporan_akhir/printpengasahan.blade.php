<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <title>Halaman Pengesahan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        @page {
            size: A4;
            margin: 3mm;
        }

        body {
            line-height: 1;
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        h3, p {
            margin: 0 0 4pt;
        }

        .container {
            padding: 2mm;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Diubah ke fixed untuk kontrol yang lebih baik */
        }

        td {
            padding: 6px;
            color: black;
            border: none;
            vertical-align: top;
        }

        /* Kolom pertama untuk nomor */
        td:first-child {
            width: 25px;
            text-align: left;
        }

        /* Kolom kedua untuk nama/konten */
        td:nth-child(2) {
            width: 35%;
            text-align: justify; /* Rata kiri-kanan */
            word-wrap: break-word;
        }

        /* Kolom ketiga untuk titik dua */
        td:nth-child(3) {
            width: 15px;
            text-align: center;
        }

        /* Kolom keempat untuk isi */
        td:nth-child(4) {
            width: 60%;
            text-align: left;
            word-wrap: break-word;
        }

        td.text-center {
            text-align: center;
        }

        ol {
            padding-left: 20px;
            margin-top: 0;
            margin-bottom: 0;
        }

        .text-center {
            padding-left: 0;
            padding-right: 0;
        }

        .footer, .header {
            font-size: 9pt;
            line-height: 1.2;
        }

        p {
            margin: 6px 0;
            line-height: 1;
            text-align: justify; /* Untuk paragraf dalam sel */
        }

        .title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        /* Style khusus untuk baris konten */
        .konten-row td:nth-child(2) {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="title">HALAMAN PENGESAHAN</p>
        </br>
        <div class="table-container">
            <table>
                <tbody>
                    <!-- Baris dengan konten rata kiri-kanan -->
                    <tr class="konten-row">
                        <td>1.</td>
                        <td>Judul {{$usulan->jenis_skema}}</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['judul_usulan'] }}</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Ketua</td>
                        <td class="text-center">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr >
                        <td>&nbsp;</td>
                        <td>
                            <ol style="list-style-type: lower-latin;">
                                <li>Nama</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['ketuaDosen']['name'] }} (NIDN: {{ $formattedUsulan['ketuaDosen']['nidn'] }})</td>
                    </tr>
                    {{-- <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="2" style="list-style-type: lower-latin;">
                                <li>NIDN</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['ketuaDosen']['nidn'] }}</td>
                    </tr> --}}
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="3" style="list-style-type: lower-latin;">
                                <li>Program Studi</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['ketuaDosen']['prodi'] }}</td>
                    </tr>
                    <tr class="konten-row">
                        <td>7.</td>
                        <td>TKT</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['tingkat_kecukupan_teknologi'] }}</td>
                    </tr>
                    <tr class="konten-row">
                        <td>3.</td>
                        <td>Anggota Peneliti</td>
                        <td class="text-center">&nbsp; :</td>
                        <td>
                            @foreach ($formattedUsulan['anggotaDosen'] as $anggota)
                                <p>{{ $anggota['name'] }} (NIDN: {{ $anggota['nidn'] }})</p>
                            @endforeach
                        </td>
                    </tr>

                    {{-- <tr class="konten-row">
                        <td>&nbsp;</td>
                        <td>
                            <ol style="list-style-type: lower-latin;">
                                <li>Jumlah Anggota</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">{{ $formattedUsulan['jumlahAnggota'] }} orang</td>
                    </tr> --}}

                    <tr class="konten-row">
                        <td>4.</td>
                        <td>Waktu Pelaksanaan</td>
                        <td class="text-center">:</td>
                        <td>22 Mei - 22 September 2025</td>
                    </tr>
                    <tr class="konten-row">
                        <td>5.</td>
                        <td>Pendanaan</td>
                        <td class="text-center">:</td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr class="konten-row">
                        <td>&nbsp;</td>
                        <td style="padding-left: 20px;"><li>Unhasy</li></td>
                        <td class="text-center">:</td>
                        <td>
                            @if ($usulan->jenis_skema == 'penelitian')
                                Rp. 45.000.000
                            @elseif ($usulan->jenis_skema == 'pengabdian')
                                Rp. 5.000.000
                            @endif
                        </td>
                    </tr>
                    
                    <tr class="konten-row">
                        <td>6.</td>
                        <td>Lokasi Penelitian</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['lokasi_penelitian'] }}</td>
                    </tr>
                    <tr class="konten-row">
                        <td>9.</td>
                        <td>Jarak</td>
                        <td class="text-center">:</td>
                        <td>{{  $formattedUsulan['jarak_pt_ke_lokasi_mitra'] }} km</td>
                    </tr>
                    
                    <tr class="konten-row">
                        <td>8.</td>
                        <td>Nama Mitra</td>
                        <td class="text-center">:</td>
                        {{-- <td>{{ $formattedUsulan['nama_mitra'] }}-{{ $formattedUsulan['bidang_mitra'] }}</td> --}}
                        <td>{{ $formattedUsulan['nama_mitra'] }}</td>
                    </tr>
                    {{-- <tr class="konten-row">
                        <td>9.</td>
                        <td>Lokasi Mitra/Jarak</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['lokasi_mitra'] }}- {{ $formattedUsulan['jarak_pt_ke_lokasi_mitra'] }} km</td>
                    </tr> --}}
                    
                    <tr class="konten-row">
                        <td>10.</td>
                        <td>Luaran</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['luaran'] }}</td>
                    </tr>

                    <!-- Baris tanda tangan -->
                    <tr>
                        <td colspan="4" class="text-center">
                            <table style="width: 100%; margin-top: 20px;">
                                <tr>
                                    <td style="width: 50%; text-align: center;">
                                        <br>
                                        <p>Dekan</p>
                                        <span style="color: green; font-size: 24px;">✓ Verified</span>
                                        <p>{{ $dekan['nama'] }}</p>
                                        <p>NIDN: {{ $dekan['nidn'] }}</p>
                                    </td>
                                    <td style="width: 50%; text-align: center;">
                                        <p>Jombang, {{ now()->format('d F Y') }}</p>
                                        <p>Ketua Peneliti,</p>
                                        <span style="color: green; font-size: 24px;">✓ Verified</span>
                                        <p>{{ $formattedUsulan['ketuaDosen']['name'] }}</p>
                                        <p>NIDN: {{ $formattedUsulan['ketuaDosen']['nidn'] }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center">
                            <p>Menyetujui,</p>
                            <p>Kepala LPPM Unhasy</p>
                            <span style="color: green; font-size: 24px;">✓ Verified</span>
                            <p>{{ $kepalaLPPM['nama'] }}</p>
                            <p>NIDN: {{ $kepalaLPPM['nidn'] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center">
                            <p>Scan QR Code untuk Mengakses Dokumen Usulan:</p>
                            <div>{!! $qrCodeSVG !!}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
<script>
    window.print();
    setTimeout(() => {
        window.close();
    }, 100);
</script>
</html>