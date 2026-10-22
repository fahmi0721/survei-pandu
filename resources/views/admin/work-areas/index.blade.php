@extends('layouts.admin')

@section('title', 'Master Wilayah Kerja')
@section('page-title', 'Master Wilayah Kerja')

@section('content')

<div id="workAreaModule"
     data-base-url="{{ url('/admin/work-areas') }}"
     data-data-url="{{ route('admin.work-areas.data') }}"
     data-store-url="{{ route('admin.work-areas.store') }}">

    <div class="page-header mb-4">
        <div class="row align-items-center">

            <div class="col">
                <div class="page-pretitle">Master Data</div>
                <h2 class="page-title">Wilayah Kerja</h2>
            </div>

            <div class="col-auto">
                <button type="button"
                        class="btn btn-primary"
                        id="btnAddWorkArea"
                        data-bs-toggle="modal"
                        data-bs-target="#modalWorkArea">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Wilayah
                </button>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableWorkAreas"
                       class="table table-vcenter w-100">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Wilayah</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.work-areas._modal')

@endsection