<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Survei Perwira Pandu')</title>
    <meta name="description"
      content="@yield('meta_description', 'Survei Perwira Pandu PT Intan Sejahtera Utama')">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">

    <meta property="og:site_name"
        content="PT Intan Sejahtera Utama">

    <meta property="og:locale" content="id_ID">

    <meta property="og:title"
        content="@yield('og_title', 'Survei Perwira Pandu ISMA')">

    <meta property="og:description"
        content="@yield('og_description', 'Sampaikan aspirasi dan prioritas Anda melalui Survei Perwira Pandu PT Intan Sejahtera Utama.')">

    <meta property="og:url"
        content="@yield('og_url', url()->current())">

    <meta property="og:image"
        content="{{ asset('images/survey.png') }}">

    <meta property="og:image:secure_url"
        content="{{ secure_asset('images/survey.png') }}">

    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta property="og:image:alt"
        content="Survei Perwira Pandu PT Intan Sejahtera Utama">

    {{-- Twitter / X Card --}}
    <meta name="twitter:card" content="summary_large_image">
    {{-- CSS halaman publik dipisahkan dari Tabler agar tampilan asli tidak berubah. --}}
    @vite(['resources/css/survey-public.css', 'resources/js/survey-public.js'])
</head>
<body>
    @yield('content')
</body>
</html>
