<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .container {
            text-align: center;
            max-width: 480px;
            width: 100%;
        }

        .error-code {
            font-size: clamp(100px, 20vw, 160px);
            font-weight: 800;
            letter-spacing: -10px;
            line-height: 1;
            color: #e2e8f0;
        }

        h1 {
            font-size: 26px;
            font-weight: 700;
            margin: 24px 0 12px;
        }

        p {
            font-size: 15px;
            line-height: 1.7;
            color: #64748b;
            margin-bottom: 28px;
        }

        .home-button {
            display: inline-block;
            padding: 13px 24px;
            background: #0f172a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .home-button:hover {
            background: #334155;
        }

        .footer {
            margin-top: 48px;
            font-size: 12px;
            color: #94a3b8;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 22px;
            }

            .error-code {
                letter-spacing: -6px;
            }
        }
    </style>
</head>

<body>

    <main class="container">
        <div class="error-code">404</div>

        <h1>Halaman Tidak Ditemukan</h1>

        <p>
            Maaf, halaman yang kamu cari tidak tersedia
            atau mungkin sudah dipindahkan.
        </p>

        <a href="/" class="home-button">
            ← Kembali ke Beranda
        </a>

        <div class="footer">
            © {{ date('Y') }} Telusur
        </div>
    </main>

</body>

</html>
