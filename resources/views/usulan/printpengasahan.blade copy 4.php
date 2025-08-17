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
            margin: 3mm; /* Standard Word margins (1 inch) */
        }

        body {
            line-height: 1;
            font-family: "Times New Roman", serif;
            font-size: 12pt; /* Set default font size */
            margin: 0;
            padding: 0;
        }

        h3, p {
            margin: 0 0 4pt;
        }

        .container {
            padding: 2mm; /* Adjust container padding */
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; /* Automatically adjust column width */
        }

        td {
            padding: 6px; /* Reduced padding for better spacing */
            color: black;
            border: none;
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
            padding-left: 0; /* Remove extra left padding */
            padding-right: 0; /* Remove extra right padding */
        }

        .footer, .header {
            font-size: 9pt;
            line-height: 1.2;
        }

        p {
            margin: 6px 0; /* Adjust paragraph margins */
            line-height: 1; /* Single line spacing */
        }

        .title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

    <style>
        @page {
            size: A4;
            margin: 3mm; /* Standard Word margins (1 inch) */
        }

        body {
            line-height: 1;
            font-family: "Times New Roman", serif;
            font-size: 12pt; /* Set default font size */
            margin: 0;
            padding: 0;
        }

        h3, p {
            margin: 0 0 4pt;
        }

        .container {
            padding: 2mm; /* Adjust container padding */
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; /* Automatically adjust column width */
        }

        td {
            padding: 6px; /* Reduced padding for better spacing */
            color: black;
            border: none;
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
            padding-left: 0; /* Remove extra left padding */
            padding-right: 0; /* Remove extra right padding */
        }

        .footer, .header {
            font-size: 9pt;
            line-height: 1.2;
        }

        p {
            margin: 6px 0; /* Adjust paragraph margins */
            line-height: 1; /* Single line spacing */
        }

        .title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
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
                    <tr>
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
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol style="list-style-type: lower-latin;">
                                <li>Nama Lengkap</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['ketuaDosen']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="2" style="list-style-type: lower-latin;">
                                <li>NIDN</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['ketuaDosen']['nidn'] }}</td>
                    </tr>
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
                    <tr>
                        <td>3.</td>
                        <td>Anggota Peneliti</td>
                        <td class="text-center">&nbsp; :</td>
                        <td>
                            @foreach ($formattedUsulan['anggotaDosen'] as $anggota)
                                <p>{{ $anggota['name'] }} (NIDN: {{ $anggota['nidn'] }})</p>
                            @endforeach
                            {{-- @foreach ($formattedUsulan['anggotaMahasiswa'] as $anggota)
                                <p>{{ $anggota['name'] }} (NIM: {{ $anggota['nim'] }})</p>
                            @endforeach --}}
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol style="list-style-type: lower-latin;">
                                <li>Jumlah Anggota</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">{{ $formattedUsulan['jumlahAnggota'] }} orang</td>
                    </tr>

                    
                    <tr>
                        <td>4.</td>
                        <td>Jangka Waktu Pelaksanaan</td>
                        <td class="text-center">:</td>
                        <td>
                            {{-- {{ $periode ? $periode->tanggal_awal->format('d M Y') : '-' }}
                            -
                            {{ $periode ? $periode->tanggal_akhir->format('d M Y') : '-' }} --}}
                             22 Mei - 22 September 2025
                        </td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Pendanaan</td>
                        <td class="text-center">:</td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Sumber Biaya Unhasy</td>
                        <td class="text-center">:</td>
                        <td>
                            {{-- Rp. {{ $periode ? number_format($periode->nominal, 0, ',', '.') : '0' }} --}}

                            @if ($usulan->jenis_skema == 'penelitian')
                                Rp. 45.000.000
                            @elseif ($usulan->jenis_skema == 'pengabdian')
                                Rp. 5.000.000
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <td>6.</td>
                        <td>Lokasi Penelitian</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['lokasi_penelitian'] }}</td>
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>Tingkat Kecukupan Teknologi (TKT)</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['tingkat_kecukupan_teknologi'] }}</td>
                    </tr>
                    <tr>
                        <td>8.</td>
                        <td>Nama Mitra (Bidang)</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['nama_mitra'] }}-{{ $formattedUsulan['bidang_mitra'] }}</td>
                    </tr>
                    <tr>
                        <td>9.</td>
                        <td>Lokasi Mitra/Jarak</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['lokasi_mitra'] }}- {{ $formattedUsulan['jarak_pt_ke_lokasi_mitra'] }} km</td>
                    </tr>
                    
                    <tr>
                        <td>10.</td>
                        <td>Luaran</td>
                        <td class="text-center">:</td>
                        <td>{{ $formattedUsulan['luaran'] }}</td>
                    </tr>

                    
                    <tr>
                        <td colspan="3" class="text-center">
                            <br>
                            <p>Dekan</p>
                            <br>
                            <br>
                            <p>{{ $dekan['nama'] }}</p>
                            <p>NIDN: {{ $dekan['nidn'] }}</p>
                        </td>
                        <td colspan="3" class="text-center">
                            <p>Jombang, {{ now()->format('d F Y') }}</p>
                            <p>Ketua Peneliti,</p>
                            <br>
                            <br>
                            <p>{{ $formattedUsulan['ketuaDosen']['name'] }}</p>
                            <p>NIDN: {{ $formattedUsulan['ketuaDosen']['nidn'] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-center">
                            <p>Menyetujui,</p>
                            <p>Kepala LPPM Unhasy</p>
                            <br>
                            <br>
                            <p>{{ $kepalaLPPM['nama'] }}</p>
                            <p>NIDN: {{ $kepalaLPPM['nidn'] }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="5" class="text-center">
                            <p>Scan QR Code untuk Mengakses Dokumen Usulan:</p>
                            <div>{!! $qrCodeSVG !!}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="qrcode">
         
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