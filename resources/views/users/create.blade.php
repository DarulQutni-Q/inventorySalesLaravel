@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="page-head">
    <h1>Tambah User</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nama</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-error @enderror"
                   value="{{ old('name') }}" placeholder="Nama lengkap" required>
            @error('name')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-error @enderror"
                   value="{{ old('email') }}" placeholder="email@example.com" required>
            @error('email')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color:#94a3b8;font-weight:400;">(min. 8 karakter)</span></label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-error @enderror"
                       placeholder="Masukkan password" required>
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Ulangi password" required>
            </div>
        </div>

        <div class="form-group">
            <label for="role" class="form-label">Role</label>
            <select id="role" name="role" class="form-control @error('role') is-error @enderror" required>
                <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff</option>
                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
            </select>
            @error('role')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
