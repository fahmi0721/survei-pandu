<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Survei Perwira Pandu')</title>
    {{-- CSS halaman publik dipisahkan dari Tabler agar tampilan asli tidak berubah. --}}
    @vite(['resources/css/survey-public.css', 'resources/js/survey-public.js'])
</head>
<body>
    @yield('content')
</body>
</html>
