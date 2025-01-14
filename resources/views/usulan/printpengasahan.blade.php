<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <title>Halaman Pengesahan</title>
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

    @php
    
    $listdekan =
    [
        [
            'nama' => 'Dr. Jasminto, M.Pd.I., M.Ag',
            'nidn' => '2112038101',
            'fakultas' => 'Fakultas Agama Islam',
        ],
        [
            'nama' => 'Dr. Resdianto Permata Raharjo, M.Pd',
            'nidn' => '0701109201',
            'fakultas' => 'Fakultas Ilmu Pendidikan',
        ],
        [
            'nama' => 'Dr. Ir. Nur Kholis, S.T., M.T.',
            'nidn' => '0021057204',
            'fakultas' => 'Fakultas Teknik',
        ],
        [
            'nama' => 'Aries Dwi Indriyanti, S.Kom., M.Kom',
            'nidn' => '0012048006',
            'fakultas' => 'Fakultas Teknologi Informasi',
        ],
        [
            'nama' => 'Dr. Tony Seno Aji, S.E., M.E',
            'nidn' => '0024097803',
            'fakultas' => 'Fakultas Ekonomi'
        ],
    ];

    $kepalaLPPM = [
        'nama' => 'Prof. Dr. Udjang Pairin M. Basir, M.Pd',
        'nidn' => '0010065707',
    ];

    @endphp
</head>
<body>
    <div class="container">
        <p class="title">
            HALAMAN PENGESAHAN
        </p>
        <p class="text-center" style="font-size: 12pt;">&nbsp;</p>
        <div class="table-container">
            <table>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>Judul PKM</td>
                        <td class="text-center">:</td>
                        <td colspan="1">
                            <p>Dampak Digitalisasi Perilaku Perjalanan</p>
                            <p>Terhadap Kesehatan Fisik Dan Sosial</p>
                        </td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Ketua</td>
                        <td class="text-center">&nbsp;</td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol style="list-style-type: lower-latin;">
                                <li>Nama Lengkap</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">Nama, ST., MT</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="2" style="list-style-type: lower-latin;">
                                <li>NIDN</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">07050</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="3" style="list-style-type: lower-latin;">
                                <li>Jabatan/Golongan</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="4" style="list-style-type: lower-latin;">
                                <li>Program Studi</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">Teknik Sipil</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="5" style="list-style-type: lower-latin;">
                                <li>Perguruan Tinggi</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">Universitas Hasyim Asy’ari</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="6" style="list-style-type: lower-latin;">
                                <li>Bidang Keahlian</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">Teknik Sipil</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="7" style="list-style-type: lower-latin;">
                                <li>Alamat Kantor/Telp/Faks/Surel</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">Jl. Irian Jaya No. 55. Tebuireng</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Anggota Peneliti</td>
                        <td class="text-center">&nbsp;</td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol style="list-style-type: lower-latin;">
                                <li>Jumlah Anggota</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">7 orang</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <ol start="2" style="list-style-type: lower-latin;">
                                <li>Nama Anggota</li>
                            </ol>
                        </td>
                        <td class="text-center">:</td>
                        <td colspan="1">
                            <p>Nama, ST., MT NIDN. 07050</p>
                            <p>Nama, ST., MT NIDN. 07050</p>
                            <p>Nama, ST., MT NIDN. 07050</p>
                            <p>Dst</p>
                        </td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Lokasi Kegiatan</td>
                        <td class="text-center">:</td>
                        <td colspan="1">Kota Malang</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Jangka waktu Pelaksanaan</td>
                        <td class="text-center">:</td>
                        <td colspan="1">Maret - Agustus 2022</td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Pendanaan</td>
                        <td class="text-center">:</td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Sumber Biaya Unhasy</td>
                        <td class="text-center">:</td>
                        <td colspan="1">Rp. 45.000.000</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <p>&nbsp;</p>
                            <p>Dekan</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>Nama, ST M.T</p>
                            <p>NIDN. 07050</p>
                        </td>
                        <td colspan="2" class="text-center">
                            <p>Jombang, 08 Oktober 2024</p>
                            <p>Ketua Peneliti,</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>Nama, ST., MT</p>
                            <p>NIDN. 07050</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-center">
                            <p>Menyetujui,</p>
                            <p>Kepala LPPM Unhasy</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>Prof. Dr. Udjang Pairin M. Basir, M.Pd</p>
                            <p>NIDN. 07050</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<script>
    window.print();
    setTimeout(() => {
        window.close();
    }, 100);



</script>

