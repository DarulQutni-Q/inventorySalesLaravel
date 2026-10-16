@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
<div class="page-head">
    <h1>Tambah Supplier</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <label for="name">Nama Supplier</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="150" placeholder="mis. UD Berkah Jaya">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-row">
            <div>
                <label for="phone">Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="30" placeholder="mis. 0812-3456-7890">
                @error('phone')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="150">
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>

        <label for="address">Alamat</label>
        <textarea id="address" name="address" rows="3">{{ old('address') }}</textarea>
        @error('address')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
