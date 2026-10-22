@extends('layouts.auth')

@section('title', 'Login Administrator - Survei Pandu')

@section('content')

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-heading">
            <h2>PT INTAN SEJAHTERA UTAMA</h2>

            <p>Administrator Survei Perwira Pandu</p>
        </div>

        <form id="formLogin"
              action="{{ route('login.submit') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="email">Email</label>

                <input type="email"
                       id="email"
                       name="email"
                       autocomplete="username"
                       required
                       placeholder="Masukkan email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input type="password"
                       id="password"
                       name="password"
                       autocomplete="current-password"
                       required
                       placeholder="Masukkan password">
            </div>

            <button type="submit" id="btnLogin">
                Login
            </button>

        </form>

    </div>

</div>

@endsection