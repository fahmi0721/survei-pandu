@extends('layouts.admin')

@section('title', 'Master Pertanyaan')
@section('page-title', 'Master Pertanyaan')

@section('content')

<div id="questionModule"
     data-base-url="{{ url('/admin/surveys/'.$survey->id.'/sections/'.$section->id.'/questions') }}"
     data-data-url="{{ route('admin.surveys.sections.questions.data', [$survey, $section]) }}"
     data-store-url="{{ route('admin.surveys.sections.questions.store', [$survey, $section]) }}"
     data-reorder-url="{{ route('admin.surveys.sections.questions.reorder', [$survey, $section]) }}">

    <div class="page-header d-print-none mb-4">

        <div class="row align-items-center g-3">

            <div class="col">

                <div class="page-pretitle">
                    {{ $survey->title }}
                </div>

                <h2 class="page-title">
                    {{ $section->title }}
                </h2>

                <div class="text-secondary mt-2">
                    {{ $section->description }}
                </div>

            </div>

            <div class="col-auto ms-auto d-flex gap-2">

                <a href="{{ route('admin.surveys.sections.index', $survey) }}"
                   class="btn btn-outline-secondary">

                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali

                </a>

                <button type="button"
                        class="btn btn-primary"
                        id="btnAddQuestion">

                    <i class="ti ti-plus me-1"></i>
                    Tambah Pertanyaan

                </button>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center w-100">

                <div>
                    <h3 class="card-title">
                        Daftar Pertanyaan
                    </h3>

                    <div class="text-secondary small mt-1">
                        Geser ikon di samping pertanyaan untuk mengubah urutan.
                    </div>
                </div>

                <span id="questionCount"
                      class="badge bg-primary-lt">
                    0 Pertanyaan
                </span>

            </div>

        </div>

        <div class="card-body">

            <div id="questionLoading"
                 class="text-secondary text-center py-4">
                Memuat pertanyaan...
            </div>

            <div id="questionList"
                 class="list-group">
            </div>

            <div id="questionEmpty"
                 class="text-secondary text-center py-5 d-none">

                <i class="ti ti-help-circle fs-1 d-block mb-2"></i>

                Belum ada pertanyaan pada bagian ini.

            </div>

        </div>

    </div>

</div>

@include('admin.questions._modal')

@endsection