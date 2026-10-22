@extends('layouts.admin')

@section('title', 'Master Survei')
@section('page-title', 'Master Survei')

@section('content')

<div id="surveyModule"
     data-url="{{ route('admin.surveys.index') }}"
     data-data-url="{{ route('admin.surveys.data') }}"
     data-store-url="{{ route('admin.surveys.store') }}">

    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">

            <div class="col">
                <div class="page-pretitle">
                    Master Data
                </div>

                <h2 class="page-title">
                    Master Survei
                </h2>
            </div>

            <div class="col-auto ms-auto">
                <button type="button"
                        class="btn btn-primary"
                        id="btnAddSurvey">

                    <i class="ti ti-plus me-1"></i>
                    Tambah Survei

                </button>
            </div>

        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Daftar Survei</h3>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="tableSurveys"
                       class="table table-vcenter card-table survey-table w-100">

                    <thead>
                        <tr>
                            <th>Judul Survei</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Bagian</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@include('admin.surveys._modal')

@endsection