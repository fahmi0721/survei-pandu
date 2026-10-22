@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrator')

@section('content')

<div class="page-header d-print-none mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Overview
            </div>

            <h2 class="page-title">
                Dashboard Survei Perwira Pandu
            </h2>

        </div>

        <div class="col-auto ms-auto">

            <span class="badge bg-success-lt">
                Sistem Aktif
            </span>

        </div>

    </div>

</div>

<div class="row row-deck row-cards">

    @php
        $cards = [
            [
                'title' => 'Total Survei',
                'icon' => 'ti-clipboard-list',
                'value' => '—',
                'color' => 'primary',
            ],
            [
                'title' => 'Survei Aktif',
                'icon' => 'ti-circle-check',
                'value' => '—',
                'color' => 'success',
            ],
            [
                'title' => 'Total Responden',
                'icon' => 'ti-users',
                'value' => '—',
                'color' => 'azure',
            ],
            [
                'title' => 'Jawaban Masuk',
                'icon' => 'ti-chart-bar',
                'value' => '—',
                'color' => 'warning',
            ],
        ];
    @endphp

    @foreach ($cards as $card)

        <div class="col-sm-6 col-lg-3">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="subheader">
                            {{ $card['title'] }}
                        </div>

                        <div class="ms-auto">
                            <i class="ti {{ $card['icon'] }}
                                text-{{ $card['color'] }} fs-2"></i>
                        </div>

                    </div>

                    <div class="h1 mb-0">
                        {{ $card['value'] }}
                    </div>

                    <div class="text-secondary small mt-2">
                        Belum tersedia
                    </div>

                </div>

            </div>

        </div>

    @endforeach

    <div class="col-12">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Selamat Datang
                </h3>
            </div>

            <div class="card-body">

                <h3>
                    Halo, {{ auth()->user()->name }}!
                </h3>

                <p class="text-secondary">
                    Selamat datang di Sistem Manajemen Survei
                    Perwira Pandu PT Intan Sejahtera Utama.
                </p>

                <p class="mb-0">
                    Modul Master Survei dan laporan akan
                    tersedia setelah tahap pengembangan
                    database selesai.
                </p>

                <div class="mt-4 d-flex gap-2 flex-wrap">

                    <button type="button"
                            id="btnTestAlert"
                            class="btn btn-primary">

                        <i class="ti ti-bell me-1"></i>
                        Test SweetAlert2

                    </button>

                    <button type="button"
                            id="btnTestNotify"
                            class="btn btn-success">

                        <i class="ti ti-check me-1"></i>
                        Test Notify.js

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection