@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<h1>Tambah User</h1>
<div class="card">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')<div class="error-text">{{ $message }}</div>@enderror

        <label for="password">Password (min. 8 karakter)</label>
        <input type="password" id="password" name="password" required>
        @error('password')<div class="error-text">{{ $message }}</div>@enderror

        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="staff" @selected(old('role') === 'staff')>Staff</option>
            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
        </select>
        @error('role')<div class="error-text">{{ $message }}</div>@enderror

        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
