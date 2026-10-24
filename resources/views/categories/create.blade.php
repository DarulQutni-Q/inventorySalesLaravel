@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="page-head">
    <div>
        <h1>Tambah Kategori</h1>
        <p class="page-desc">Nama kategori tidak boleh sama dengan kategori lain.</p>
    </div>
</div>

<form method="POST" action="{{ route('categories.store') }}" class="form-narrow">
    @csrf

    <div class="field">
        <label for="name">Nama kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="255">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('categories.index') }}">Batal</a>
    </div>
</form>
@endsection
