@extends('layouts.app')

@section('title', 'Ubah Supplier')

@section('content')
<div class="page-head">
    <div>
        <h1>Ubah Supplier</h1>
    </div>
</div>

<form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="form-narrow">
    @csrf
    @method('PUT')

    <div class="field">
        <label for="name">Nama supplier</label>
        <input type="text" id="name" name="name" value="{{ old('name', $supplier->name) }}" maxlength="150">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="phone">Telepon</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" maxlength="30">
        @error('phone')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $supplier->email) }}" maxlength="150">
        @error('email')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="address">Alamat</label>
        <textarea id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
        @error('address')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('suppliers.index') }}">Batal</a>
    </div>
</form>
@endsection
