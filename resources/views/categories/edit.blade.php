@extends('layouts.app')

@section('title', 'Ubah Kategori')

@section('content')
<div class="page-head">
    <h1>Ubah Kategori</h1>
</div>

<div class="card form-narrow">
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')
        <label for="name">Nama Kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="255">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
