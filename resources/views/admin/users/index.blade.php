@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')

<div id="userModule"
     data-url="{{ url('/admin/users') }}"
     data-data-url="{{ route('admin.users.data') }}"
     data-store-url="{{ route('admin.users.store') }}">

    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Administrator</div>
                <h2 class="page-title">Manajemen User HRD</h2>
            </div>

            <div class="col-auto">
                <button type="button"
                        class="btn btn-primary"
                        id="btnAddUser"
                        data-bs-toggle="modal"
                        data-bs-target="#modalUser">
                    <i class="ti ti-user-plus me-1"></i>
                    Tambah User
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableUsers"
                       class="table table-vcenter w-100">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.users._modal')
@include('admin.users._reset_password')
@endsection