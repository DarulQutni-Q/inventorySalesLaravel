@extends('layouts.app')

@section('title', 'Ubah User')

@section('content')
<div class="page-head">
    <h1>Ubah User</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-row">
            <div>
                <label for="password">Password Baru (kosongkan jika tidak diganti)</label>
                <input type="password" id="password" name="password">
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div>
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>
        </div>

        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
        </select>
        @error('role')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
