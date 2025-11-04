<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Srikandi Sistem Riset & Pengabdian Masyarakat UNHASY</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }
        
        .maintenance-container {
            max-width: 800px;
            width: 100%;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .logo-container {
            margin-bottom: 40px;
        }
        
        .logo {
            width: 180px;
            height: 180px;
            margin: 0 auto 30px;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .logo-icon {
            font-size: 80px;
            color: #2a5298;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .subtitle {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .maintenance-icon {
            font-size: 70px;
            margin: 30px 0;
            color: #FFD700;
            animation: spin 3s linear infinite;
        }
        
        .message {
            font-size: 22px;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .details {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: left;
        }
        
        .details h3 {
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .details p {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .contact {
            margin-top: 30px;
            font-size: 18px;
        }
        
        .progress-container {
            width: 100%;
            height: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            margin: 30px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            width: 65%;
            background: #4CAF50;
            border-radius: 5px;
            animation: progress 2s ease-in-out infinite alternate;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes progress {
            0% { width: 65%; }
            100% { width: 70%; }
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 30px 20px;
            }
            
            .logo {
                width: 140px;
                height: 140px;
            }
            
            .logo-icon {
                font-size: 60px;
            }
            
            .logo-text {
                font-size: 24px;
            }
            
            .message {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="logo-container">
            <div class="logo">
                {{-- <div class="logo-icon">🔬</div> --}}
                <a href="#" class="mb-12">
						<img alt="Logo" src="{{ asset('image/logobr.png')}}" class="h-100px rounded" />
					</a>
            </div>
            <h1 class="logo-text">Srikandi Sistem Riset & Pengabdian Masyarakat</h1>
            <p class="subtitle">Universitas Hasyim Asy'ari (UNHASY) - Dana Internal</p>
        </div>
        
        <div class="maintenance-icon">⚙️</div>
        
        <h2 class="message">Sistem Sedang Dalam Pemeliharaan</h2>
        
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
        
        <div class="details">
            <h3>Informasi Pemeliharaan</h3>
            <p>Kami sedang melakukan pemeliharaan sistem untuk meningkatkan kualitas layanan dan keamanan data.</p>
            <p>Selama proses ini, sistem tidak dapat diakses sementara waktu.</p>
        </div>
        
        <div class="contact">
            <p>Untuk pertanyaan mendesak, hubungi: <strong>lppm@unhasy.ac.id</strong></p>
        </div>
    </div>
</body>
</html>