@extends('layouts.admin')

@section('title', 'Master Bagian Survei')
@section('page-title', 'Master Bagian Survei')

@section('content')

<div id="sectionModule"
     data-base-url="{{ url('/admin/surveys/'.$survey->id.'/sections') }}"
     data-data-url="{{ route('admin.surveys.sections.data', $survey) }}"
     data-store-url="{{ route('admin.surveys.sections.store', $survey) }}"
     data-reorder-url="{{ route('admin.surveys.sections.reorder', $survey) }}">

    <div class="page-header d-print-none mb-4">

        <div class="row align-items-center">

            <div class="col">

                <div class="page-pretitle">
                    Master Survei / Bagian
                </div>

                <h2 class="page-title">
                    {{ $survey->title }}
                </h2>

                <p class="text-secondary mt-2">
                    Kelola kategori dan urutan bagian survei.
                </p>

            </div>

            <div class="col-auto ms-auto d-flex gap-2">

                <a href="{{ route('admin.surveys.index') }}"
                   class="btn btn-outline-secondary">

                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali

                </a>

                <button type="button"
                        class="btn btn-primary"
                        id="btnAddSection">

                    <i class="ti ti-plus me-1"></i>
                    Tambah Bagian

                </button>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-header">
            <div>
                <h3 class="card-title">Daftar Bagian</h3>
                <div class="text-secondary small mt-1">
                    Geser bagian untuk mengubah urutan prioritas tampilannya.
                </div>
            </div>
        </div>

        <div class="card-body">

            <div id="sectionLoading"
                 class="text-secondary text-center py-4">
                Memuat bagian survei...
            </div>

            <div id="sectionList"
                 class="list-group"
                 aria-label="Daftar bagian survei">
            </div>

            <div id="sectionEmpty"
                 class="text-center text-secondary py-5 d-none">

                <i class="ti ti-list-details fs-1 d-block mb-2"></i>

                Belum ada bagian survei.

            </div>

        </div>

    </div>

</div>

@include('admin.sections._modal')

@endsection