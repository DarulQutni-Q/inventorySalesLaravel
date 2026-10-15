@extends('layouts.app')

@section('title', 'Ubah Kategori')

@section('content')
<h1>Ubah Kategori</h1>
<div class="card" style="max-width: 520px;">
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')
        <label for="name">Nama Kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
