@extends('layouts.admin')

@section('title', 'Hasil Survei')
@section('page-title', 'Hasil Survei')

@section('content')

<div id="surveyResultModule"
     data-url="{{ route('admin.survey-results.data') }}">

    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Analisis</div>
                <h2 class="page-title">Hasil Survei</h2>
                <div class="text-secondary mt-1">
                    Analisis berdasarkan jawaban ranking 1
                    pada setiap bagian survei.
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Survei</label>
                    <select id="resultSurvey" class="form-select">
                        @foreach ($surveys as $survey)
                            <option value="{{ $survey->id }}">
                                {{ $survey->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select id="resultStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Wilayah Kerja</label>
                    <select id="resultWorkArea" class="form-select select2-work-area-filter">
                        <option value="">Semua Wilayah</option>
                        @foreach ($workAreas as $area)
                            <option value="{{ $area->id }}">
                                {{ $area->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    <!-- <div class="row row-cards mb-4">
        <div class="col-md-6">
            <div class="card card-body">
                <div class="subheader">Total Responden</div>
                <div class="h1 mb-0" id="resultTotalRespondents">0</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-body">
                <div class="subheader">Bagian Survei</div>
                <div class="h1 mb-0" id="resultTotalSections">0</div>
            </div>
        </div>
    </div> -->

    {{-- INFOGRAFIK HASIL SURVEI --}}
    <div class="card mb-4" id="surveyInfographic">

        <div class="card-header">
            <div>
                <h3 class="card-title">
                    Infografik Hasil Survei
                </h3>
                <div class="text-secondary small mt-1">
                    Visualisasi hasil berdasarkan filter yang dipilih
                </div>
            </div>
        </div>

        <div class="card-body">

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card card-body bg-primary-lt">
                        <div class="subheader">Total Responden</div>
                        <div class="h1 mb-0" id="infoRespondents">0</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-body bg-success-lt">
                        <div class="subheader">Bagian Survei</div>
                        <div class="h1 mb-0" id="infoSections">0</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-body bg-azure-lt">
                        <div class="subheader">Aspek Survei</div>
                        <div class="h1 mb-0" id="infoQuestions">0</div>
                    </div>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-lg-4">
                    <h4>Distribusi Status Responden</h4>
                    <div class="survey-chart-container">
                        <canvas id="chartRespondentStatus"></canvas>
                    </div>
                </div>

                <div class="col-lg-8">
                    <h4>Jumlah Pilihan Ranking 1 Teratas per Bagian</h4>
                    <div class="survey-chart-container">
                        <canvas id="chartTopPriorities"></canvas>
                    </div>
                </div>

            </div>

            <div class="text-secondary small mt-3">
                Grafik batang menampilkan jumlah responden yang
                memilih aspek dengan frekuensi ranking 1 tertinggi
                pada masing-masing bagian.
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                Prioritas Utama Setiap Responden
            </h3>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="resultRespondentsTable"
                       class="table table-vcenter w-100">
                    <thead>
                        <tr>
                            <th>Respons</th>
                            <th>Status</th>
                            <th>Wilayah</th>
                            <th>Prioritas Ranking 1</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Kesimpulan Prioritas per Bagian
            </h3>
        </div>

        <div class="card-body">
            <div id="resultSectionSummary">
                <p class="text-secondary mb-0">
                    Memuat hasil survei...
                </p>
            </div>
        </div>
    </div>

</div>

@endsection