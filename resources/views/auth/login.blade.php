@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h1>Masuk</h1>
<p class="sub">Kelola stok dan transaksi tokomu.</p>

@if ($errors->has('email'))
    <div class="alert-error">{{ $errors->first('email') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required autocomplete="current-password">

    <label class="remember">
        <input type="checkbox" name="remember"> Ingat saya
    </label>

    <button type="submit" class="btn">Masuk</button>
</form>

<p class="demo">
    Akun demo: <code>admin@inventory.test</code> / <code>password</code> (admin)<br>
    <code>staff@inventory.test</code> / <code>password</code> (staff)
</p>
@endsection
