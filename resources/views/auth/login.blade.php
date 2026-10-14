@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card" style="max-width: 420px; margin: 40px auto;">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')<div class="error-text">{{ $message }}</div>@enderror

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        @error('password')<div class="error-text">{{ $message }}</div>@enderror

        <label style="margin-top: 12px;">
            <input type="checkbox" name="remember"> Ingat saya
        </label>

        <button type="submit" class="btn btn-primary" style="margin-top: 16px; width: 100%;">Masuk</button>
    </form>
    <p style="font-size: 13px; color: #666; margin-top: 16px;">
        Akun demo: admin@inventory.test / password (admin),
        staff@inventory.test / password (staff)
    </p>
</div>
@endsection
