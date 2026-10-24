@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="page-head">
    <div>
        <h1>Tambah User</h1>
        <p class="page-desc">Password minimal 8 karakter dan di-hash otomatis sebelum disimpan.</p>
    </div>
</div>

<form method="POST" action="{{ route('users.store') }}" class="form-narrow">
    @csrf

    <div class="field">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}">
        @error('email')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-grid">
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
    </div>

    <div class="field">
        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff</option>
            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
        </select>
        @error('role')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('users.index') }}">Batal</a>
    </div>
</form>
@endsection
