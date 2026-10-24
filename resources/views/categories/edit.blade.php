@extends('layouts.app')

@section('title', 'Ubah Kategori')

@section('content')
<div class="page-head">
    <div>
        <h1>Ubah Kategori</h1>
    </div>
</div>

<form method="POST" action="{{ route('categories.update', $category) }}" class="form-narrow">
    @csrf
    @method('PUT')

    <div class="field">
        <label for="name">Nama kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" maxlength="255">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('categories.index') }}">Batal</a>
    </div>
</form>
@endsection
