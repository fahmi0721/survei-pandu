@extends('layouts.admin')

@section('title', 'Kelola Responden')
@section('page-title', 'Kelola Responden')

@section('content')

<div id="respondentModule"
     data-url="{{ route('admin.respondents.index') }}"
     data-data-url="{{ route('admin.respondents.data') }}">

    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Monitoring Survei
                </div>
                <h2 class="page-title">
                    Data Responden
                </h2>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row row-cards mb-4">

        <div class="col-md-4">
            <div class="card card-body">
                <div class="subheader">Total Respons</div>
                <div class="h1 mb-0" id="statTotal">0</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-body">
                <div class="subheader">Status Aktif</div>
                <div class="h1 mb-0" id="statActive">0</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-body">
                <div class="subheader">Tidak Aktif</div>
                <div class="h1 mb-0" id="statInactive">0</div>
            </div>
        </div>

    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Survei</label>

                    <select id="filterSurvey"
                            class="form-select">
                        <option value="">Semua Survei</option>

                        @foreach($surveys as $survey)
                            <option value="{{ $survey->id }}">
                                {{ $survey->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status Kerja</label>

                    <select id="filterStatus"
                            class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Wilayah Kerja</label>

                    <select id="filterWorkArea"
                            class="form-select select2-work-area-filter">
                        <option value="">Semua Wilayah</option>

                        @foreach($workAreas as $area)
                            <option value="{{ $area->id }}">
                                {{ $area->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Respons Survei</h3>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="tableRespondents"
                       class="table table-vcenter w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Survei</th>
                            <th>Status</th>
                            <th>Wilayah</th>
                            <th>Kelas Pandu</th>
                            <th>Jawaban</th>
                            <th>Tanggal Pengisian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.respondents._detail')

@endsection