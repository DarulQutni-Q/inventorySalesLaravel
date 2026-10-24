@extends('layouts.app')

@section('title', 'Ubah User')

@section('content')
<div class="page-head">
    <div>
        <h1>Ubah User</h1>
        <p class="page-desc">Biarkan password kosong kalau tidak ingin mengganti.</p>
    </div>
</div>

<form method="POST" action="{{ route('users.update', $user) }}" class="form-narrow">
    @csrf
    @method('PUT')

    <div class="field">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
        @error('email')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-grid">
        <div class="field">
            <label for="password">Password baru</label>
            <input type="password" id="password" name="password">
            @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi password baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
    </div>

    <div class="field">
        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
        </select>
        @error('role')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('users.index') }}">Batal</a>
    </div>
</form>
@endsection
