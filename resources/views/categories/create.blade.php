@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<h1>Tambah Kategori</h1>
<div class="card" style="max-width: 520px;">
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <label for="name">Nama Kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')<div class="error-text">{{ $message }}</div>@enderror

        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
