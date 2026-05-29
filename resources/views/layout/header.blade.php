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

<!--
|
|
|
|
|
|
|
|
|
|
|=================================================================
|  Kode tracking (Google Analytics, Facebook Pixel, dll) bisa ditempatkan di sini
|=================================================================
-->

<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
    _atrk_opts = {
        atrk_acct: "O+bhq1ah9W20em",
        domain: "telusur.co.id",
        dynamic: true
    };
    (function() {
        var as = document.createElement('script');
        as.type = 'text/javascript';
        as.async = true;
        as.src = "https://certify-js.alexametrics.com/atrk.js";
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(as, s);
    })();
</script>
<noscript>
    <img src="https://certify.alexametrics.com/atrk.gif?account=O+bhq1ah9W20em" style="display:none" height="1"
        width="1" alt="" />
</noscript>

<!--google search console-->
<meta name="google-site-verification" content="rqww4C4btdgzdCwBDl2xO2xjCgkyWQ8-j-MlRzE5cQo" />

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111633799-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-111633799-1');
</script>

<!-- Google Tag Manager -->
<script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PTK55GX');
</script>
