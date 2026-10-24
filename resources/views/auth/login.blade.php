@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h1 class="guest-title">Masuk</h1>
<p class="guest-sub">Masuk untuk mengelola inventori dan penjualan.</p>

@if ($errors->has('email'))
    <div class="alert alert-error">{{ $errors->first('email') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus autocomplete="email">
        @error('email')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password">
        @error('password')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label>
            <input type="checkbox" name="remember"> Ingat saya di perangkat ini
        </label>
    </div>

    <button type="submit" class="btn btn-block">Masuk</button>
</form>

<div class="guest-box">
    <strong>Akun demo</strong><br>
    Admin: admin@inventory.test / password<br>
    Staff: staff@inventory.test / password
</div>
@endsection
