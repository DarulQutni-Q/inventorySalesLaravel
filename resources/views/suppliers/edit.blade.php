@extends('layouts.app')

@section('title', 'Ubah Supplier')

@section('content')
<div class="page-head">
    <h1>Ubah Supplier</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
        @csrf
        @method('PUT')
        <label for="name">Nama Supplier</label>
        <input type="text" id="name" name="name" value="{{ old('name', $supplier->name) }}" required maxlength="150">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-row">
            <div>
                <label for="phone">Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" maxlength="30">
                @error('phone')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $supplier->email) }}" maxlength="150">
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>

        <label for="address">Alamat</label>
        <textarea id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
        @error('address')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
