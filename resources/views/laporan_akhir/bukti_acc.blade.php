<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti ACC - Laporan Akhir</title>
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
            width: 120px; /* Ukuran QR Code */
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
        <p>Jl. Contoh No. 123, Jombang</p>
        <p>Telp: (0274) 1234567 | Email: lppm@unhasy.ac.id</p>
    </div>

    <h1>Bukti ACC Laporan Akhir</h1>

    <table style="width: 100%; text-align: center; border-collapse: collapse;">
        <tr>
            <th>Judul Laporan Akhir</th>
            <td>{{ $laporanAkhir->usulan->judul_usulan }}- {{ $laporanAkhir->usulan->ketuaDosen->user->name }} </td>
            
        </tr>
        <tr>
            <td colspan="2">
                <p>Jombang, {{ date('d F Y') }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="barcode">
                    <img src="{{ $barcodeBase64 }}" alt="QR Code" style="width: 120px; height: auto;">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="signature" style="font-style: italic; font-size: 10pt;">
                    Telah disetujui oleh Ketua LPPM dengan tanda tangan elektronik.
                </p>
            </td>
        </tr>
    </table>
    
</body>
</html>
