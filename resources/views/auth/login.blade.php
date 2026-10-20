@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h1 class="login-title">Selamat Datang</h1>
<p class="login-subtitle">Masuk ke akun CRMEdge Anda</p>

@if ($errors->has('email'))
    <div class="alert-error">{{ $errors->first('email') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}"
           required autofocus autocomplete="email" placeholder="nama@perusahaan.com">

    <label for="password">Password</label>
    <input type="password" id="password" name="password"
           required autocomplete="current-password" placeholder="••••••••">

    <label class="remember">
        <input type="checkbox" name="remember"> Ingat saya di perangkat ini
    </label>

    <button type="submit" class="btn-submit">Masuk ke Dashboard</button>
</form>

<div class="demo-box">
    <strong>Akun Demo</strong>
    Admin &nbsp;— <code>admin@inventory.test</code> / <code>password</code><br>
    Staff &nbsp;&nbsp;— <code>staff@inventory.test</code> / <code>password</code>
</div>
@endsection
