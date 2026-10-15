@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
<h1>Tambah Supplier</h1>
<div class="card" style="max-width: 560px;">
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <label for="name">Nama Supplier</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="150">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <label for="phone">Telepon</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="30">
        @error('phone')<div class="error-text">{{ $message }}</div>@enderror

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="150">
        @error('email')<div class="error-text">{{ $message }}</div>@enderror

        <label for="address">Alamat</label>
        <textarea id="address" name="address" rows="3">{{ old('address') }}</textarea>
        @error('address')<div class="error-text">{{ $message }}</div>@enderror

        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
