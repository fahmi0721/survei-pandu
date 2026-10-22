<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard') - Survei Pandu ISMA
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body>

<div class="page">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main content --}}
    <div class="page-wrapper">

        {{-- Navbar --}}
        @include('admin.partials.navbar')

        {{-- Page content --}}
        <div class="page-body">

            <div class="container-xl">

                @yield('content')

            </div>

        </div>

        {{-- Footer --}}
        <footer class="footer footer-transparent py-3 mt-auto">
            <div class="container-xl">

                <div class="text-center text-secondary">

                    &copy; {{ date('Y') }}
                    PT Intan Sejahtera Utama

                </div>

            </div>
        </footer>

    </div>

</div>

@stack('scripts')

</body>
</html>