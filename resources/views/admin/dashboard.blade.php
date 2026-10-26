@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrator')

@section('content')
@php
    $totalSurveys = (int) ($statistics['total_surveys'] ?? 0);
    $activeSurveys = (int) ($statistics['active_surveys'] ?? 0);
    $totalResponses = (int) ($statistics['total_respondents'] ?? 0);
    $totalAnswers = (int) ($statistics['total_answers'] ?? 0);
    $activeRespondents = (int) ($statistics['status_counts']['aktif'] ?? 0);
    $inactiveRespondents = (int) ($statistics['status_counts']['tidak_aktif'] ?? 0);
    $latestResponses = $statistics['latest_responses'] ?? collect();
@endphp

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center g-2">
        <div class="col">
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Dashboard Survei Perwira Pandu</h2>
            <div class="text-secondary mt-1">Ringkasan data dan aktivitas pengisian survei</div>
        </div>
        <div class="col-auto ms-auto">
            <span class="badge bg-success-lt">Sistem Aktif</span>
        </div>
    </div>
</div>

{{-- Statistik utama --}}
<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Survei</div>
                    <i class="ti ti-clipboard-list text-primary fs-2 ms-auto" aria-hidden="true"></i>
                </div>
                <div class="h1 mb-0">{{ number_format($totalSurveys, 0, ',', '.') }}</div>
                <div class="text-secondary small mt-2">Seluruh survei terdaftar</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Survei Aktif</div>
                    <i class="ti ti-circle-check text-success fs-2 ms-auto" aria-hidden="true"></i>
                </div>
                <div class="h1 mb-0">{{ number_format($activeSurveys, 0, ',', '.') }}</div>
                <div class="text-secondary small mt-2">Sedang menerima pengisian</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Respons</div>
                    <i class="ti ti-users text-azure fs-2 ms-auto" aria-hidden="true"></i>
                </div>
                <div class="h1 mb-0">{{ number_format($totalResponses, 0, ',', '.') }}</div>
                <div class="text-secondary small mt-2">Formulir yang telah dikirim</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Jawaban Masuk</div>
                    <i class="ti ti-chart-bar text-warning fs-2 ms-auto" aria-hidden="true"></i>
                </div>
                <div class="h1 mb-0">{{ number_format($totalAnswers, 0, ',', '.') }}</div>
                <div class="text-secondary small mt-2">Total aspek ranking yang dijawab</div>
            </div>
        </div>
    </div>
</div>

{{-- Distribusi status: menggunakan kolom survey_responses.status --}}
<div class="row row-deck row-cards mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Respons Status Aktif</div>
                    <i class="ti ti-user-check text-success fs-2 ms-auto" aria-hidden="true"></i>
                </div>
                <div class="h1 mb-0 text-success">{{ number_format($activeRespondents, 0, ',', '.') }}</div>
                <div class="text-secondary small mt-2">Respons dengan status aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Respons Status Tidak Aktif</div>
                    <i class="ti ti-user-off text-secondary fs-2 ms-auto" aria-hidden="true"></i>
                </div>
                <div class="h1 mb-0">{{ number_format($inactiveRespondents, 0, ',', '.') }}</div>
                <div class="text-secondary small mt-2">Respons dengan status tidak aktif</div>
            </div>
        </div>
    </div>
</div>

{{-- Akses cepat mengikuti Gate untuk Admin dan HRD --}}
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Selamat Datang</h3>
    </div>
    <div class="card-body">
        <h3 class="mb-2">Halo, {{ auth()->user()?->name ?? 'Pengguna' }}!</h3>
        <p class="text-secondary mb-2">
            Selamat datang di Sistem Manajemen Survei Perwira Pandu PT Intan Sejahtera Utama.
        </p>
        <p>Pantau pengisian survei, kelola data respons, dan analisis prioritas Perwira Pandu.</p>
        <div class="btn-list mt-3">
            @can('view-respondents')
                <a href="{{ route('admin.respondents.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-users me-1" aria-hidden="true"></i> Kelola Responden
                </a>
            @endcan
            @can('view-survey-results')
                <a href="{{ route('admin.survey-results.index') }}" class="btn btn-primary">
                    <i class="ti ti-chart-bar me-1" aria-hidden="true"></i> Lihat Hasil Survei
                </a>
            @endcan
            @can('manage-surveys')
                <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-clipboard-list me-1" aria-hidden="true"></i> Master Survei
                </a>
            @endcan
        </div>
    </div>
</div>

{{-- Daftar respons terbaru --}}
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Respons Survei Terbaru</h3>
        @can('view-respondents')
            <div class="card-actions">
                <a href="{{ route('admin.respondents.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
        @endcan
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>ID Respons</th>
                    <th>Survei</th>
                    <th>Status</th>
                    <th>Wilayah</th>
                    <th class="text-center">Jawaban</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestResponses as $response)
                    <tr>
                        <td>#{{ $response->id }}</td>
                        <td>{{ $response->survey?->title ?? '—' }}</td>
                        <td>
                            @if ($response->status === 'aktif')
                                <span class="badge bg-success-lt">Aktif</span>
                            @elseif ($response->status === 'tidak_aktif')
                                <span class="badge bg-secondary-lt">Tidak Aktif</span>
                            @else
                                <span class="badge bg-warning-lt">{{ $response->status ?? 'Tidak diketahui' }}</span>
                            @endif
                        </td>
                        <td>{{ $response->workArea?->nama ?? '—' }}</td>
                        <td class="text-center">{{ number_format((int) ($response->answers_count ?? 0), 0, ',', '.') }}</td>
                        <td>{{ $response->submitted_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            Belum ada responden yang mengirim survei.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
