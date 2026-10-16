@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="page-head">
    <h1>Tambah Kategori</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <label for="name">Nama Kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="mis. Sembako">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
