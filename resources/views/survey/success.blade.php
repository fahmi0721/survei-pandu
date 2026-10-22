@extends('layouts.survey')

@section('title', 'Survei Berhasil Dikirim')

@section('content')

<section class="survey-success">

    <div class="success-icon">
        <i class="ti ti-check"></i>
    </div>

    <span class="success-eyebrow">
        PENGIRIMAN BERHASIL
    </span>

    <h1>Terima Kasih atas Partisipasi Anda!</h1>

    <p class="success-description">
        Jawaban survei Anda telah berhasil diterima dan
        disimpan oleh sistem.

        Masukan yang Anda berikan akan menjadi bagian
        dari evaluasi dan pengembangan kesejahteraan,
        keselamatan, serta lingkungan kerja Perwira Pandu.
    </p>

    <div class="success-information">

        <div class="success-information-row">
            <i class="ti ti-clipboard-check"></i>

            <div>
                <strong>Survei</strong>

                <span>{{ $surveyTitle }}</span>
            </div>
        </div>

        <div class="success-information-row">
            <i class="ti ti-calendar-check"></i>

            <div>
                <strong>Status Pengiriman</strong>

                <span>Berhasil disimpan</span>
            </div>
        </div>

    </div>

    <div class="success-privacy">
        <i class="ti ti-shield-check"></i>

        <span>
            Informasi survei akan digunakan sesuai
            tujuan pengumpulan data dan ketentuan
            perlindungan data yang berlaku.
        </span>
    </div>

    <a href="{{ route('survey.public.index') }}"
       class="home-survey-button success-home-button">

        <i class="ti ti-home"></i>
        Kembali ke Halaman Utama

    </a>

</section>

@endsection