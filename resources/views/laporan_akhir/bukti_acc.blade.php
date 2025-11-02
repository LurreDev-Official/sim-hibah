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
            padding: 40px;
            position: relative;
            background-color: #f9f9f9;
            line-height: 1.5;
        }
        
        .document-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
        }
        
        .header h1 {
            font-size: 16pt;
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 14pt;
            margin: 0 0 15px 0;
            color: #34495e;
        }
        
        .header p {
            font-size: 10pt;
            margin: 3px 0;
            color: #555;
        }
        
        .document-title {
            text-align: center;
            margin: 30px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        
        .document-title h1 {
            font-size: 16pt;
            margin: 0;
            color: #2c3e50;
            text-transform: uppercase;
        }
        
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        
        .content-table, .content-table th, .content-table td {
            border: 1px solid #ddd;
        }
        
        .content-table th {
            background-color: #f2f2f2;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            width: 25%;
            color: #2c3e50;
        }
        
        .content-table td {
            padding: 12px;
            text-align: left;
        }
        
        .verification-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
            border: 1px dashed #ccc;
        }
        
        .date-info {
            font-size: 10pt;
            margin-bottom: 20px;
            /* font-weight: bold; */
        }
        
        .barcode {
            text-align: center;
            margin: 20px 0;
        }
        
        .barcode img {
            width: 140px;
            height: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: white;
        }
        
        .signature {
            margin-top: 15px;
            font-size: 10pt;
            font-style: italic;
            color: #555;
        }
        
        .footer {
            text-align: right;
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 9pt;
            color: #777;
        }
        
        .footer p {
            margin: 3px 0;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.03);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .document-container {
                box-shadow: none;
                border: none;
                padding: 20px;
            }
            
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">APPROVED</div>
    
    <div class="document-container">
        <div class="header">
            <h1>Lembaga Penelitian dan Pengabdian Kepada Masyarakat</h1>
            <h1>Universitas Hasyim Asy'ari</h1>
            <p>Tebuireng, Jl. Irian Jaya No.55, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471</p>
            <p>Telp: (0321) 861719| Email: lppm@unhasy.ac.id</p>
        </div>

        <div class="document-title">
            <h1>Bukti Laporan Akhir</h1>
        </div>

        <table class="content-table">
            <tr>
                <th>Judul</th>
                <td>{{ $laporanAkhir->usulan->judul_usulan }} - {{ $laporanAkhir->usulan->ketuaDosen->user->name }}</td>
            </tr>
        </table>

        <div class="verification-section">
            <div class="date-info">
                Jombang, {{ date('d F Y') }}
            </div>
            
            <div class="barcode">
                <img src="{{ $barcodeBase64 }}" alt="QR Code">
            </div>
            
            <div class="signature">
                Telah disetujui oleh Ketua LPPM dengan tanda tangan elektronik.
            </div>
        </div>

        <div class="footer">
            <p>Dokumen ini sah dan telah diverifikasi secara elektronik</p>
            <p>Generated on: {{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>