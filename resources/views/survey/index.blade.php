@extends('layouts.survey')

@section('title', 'Survei Perwira Pandu')

@section('content')

<div class="public-home">

    <section class="home-intro">

        <span class="eyebrow">
            SURVEI INTERNAL • PERWIRA PANDU
        </span>

        <h1>Survei Perwira Pandu</h1>

        <p>
            Suara dan masukan Anda menjadi bagian penting
            dalam pemetaan kebutuhan, kesejahteraan,
            keselamatan, dan pengembangan profesi
            Perwira Pandu.
        </p>

    </section>

    <section class="home-surveys">

        <div class="home-section-heading">

            <div>
                <h2>Survei yang Tersedia</h2>

                <p>
                    Pilih survei untuk memulai pengisian.
                </p>
            </div>

            <span class="home-count">
                {{ $surveys->count() }} Survei
            </span>

        </div>

        <div class="home-survey-grid">

            @forelse ($surveys as $survey)

                <article class="home-survey-card">

                    <div class="home-card-top">

                        <span class="home-status">
                            <span class="home-status-dot"></span>
                            Sedang Aktif
                        </span>

                        <span class="home-card-icon">
                            <i class="ti ti-clipboard-list"></i>
                        </span>

                    </div>

                    <h3>{{ $survey->title }}</h3>

                    <p class="home-description">
                        {{ $survey->description ?: 'Survei untuk memetakan kebutuhan dan prioritas Perwira Pandu.' }}
                    </p>

                    <div class="home-card-meta">

                        <div>
                            <i class="ti ti-list-details"></i>
                            {{ $survey->active_sections_count }} Bagian
                        </div>

                        <div>
                            <i class="ti ti-calendar"></i>

                            @if ($survey->end_date)
                                Hingga {{ $survey->end_date->format('d/m/Y') }}
                            @else
                                Tanpa batas tanggal
                            @endif
                        </div>

                    </div>

                    <a href="{{ route('survey.public.show', $survey) }}"
                       class="home-survey-button">

                        Mulai Survei
                        <i class="ti ti-arrow-right"></i>

                    </a>

                </article>

            @empty

                <div class="home-empty">

                    <i class="ti ti-clipboard-off"></i>

                    <h3>Belum Ada Survei Aktif</h3>

                    <p>
                        Saat ini belum terdapat survei
                        yang tersedia untuk diisi.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection