@extends('layouts.app')

@section('title', 'Area Admin')

@section('content')
<h1>Area Admin</h1>
<div class="card">
    <p>Halaman khusus admin. Menu admin akan bertambah seiring modul selesai.</p>
    @if (Route::has('users.index'))
        <p><a href="{{ route('users.index') }}" class="btn btn-secondary">Kelola Pengguna</a></p>
    @endif
</div>
@endsection
