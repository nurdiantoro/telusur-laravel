<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/icon-telusur.webp') }}">

<title>{{ $title ?? 'Telusur' }}</title>
<meta name="description"
    content="{{ $description ??
        'Telusur adalah platform pencarian yang membantu Anda menemukan informasi, tempat, dan layanan dengan mudah. Jelajahi dunia dengan Telusur!' }}">

<meta name="keywords" content="berita, news, telusur, indonesia">

{{-- Canonical --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph (Facebook, WhatsApp, LinkedIn, dll) --}}
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $title ?? 'Telusur' }}">
<meta property="og:description" content="{{ $description ?? 'Portal berita Telusur' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $thumbnail ?? asset('img/logo-telusur.webp') }}">
<meta property="og:site_name" content="Telusur">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? 'Telusur' }}">
<meta name="twitter:description" content="{{ $description ?? 'Portal berita Telusur' }}">
<meta name="twitter:image" content="{{ $thumbnail ?? asset('img/logo-telusur.webp') }}">

{{-- Optional (kalau ada) --}}
<meta name="author" content="Telusur">
<meta name="robots" content="index, follow">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

{{-- prioritas image --}}
<link rel="preload" as="image" href="{{ asset('img/logo-telusur.webp') }}">
<link rel="preload" as="image" href="{{ asset('img/city.webp') }}">
