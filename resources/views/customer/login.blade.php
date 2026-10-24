@extends('layouts.guest')

@section('title', 'Masuk sebagai Pelanggan')

@section('content')
<h1 class="guest-title">Masuk sebagai Pelanggan</h1>
<p class="guest-sub">Isi nama dan nomor telepon. Telepon yang sudah terdaftar dipakai lagi, tidak membuat akun baru.</p>

<form method="POST" action="{{ route('customer.login.post') }}">
    @csrf

    <div class="field">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="150">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="phone">Nomor telepon</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" maxlength="30">
        @error('phone')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="address">Alamat (boleh kosong)</label>
        <input type="text" id="address" name="address" value="{{ old('address') }}" maxlength="255">
        @error('address')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-block">Masuk dan Lihat Katalog</button>
</form>

<hr class="guest-sep">

<p class="guest-sub">Pemilik toko atau staff? <a href="{{ route('login') }}">Masuk dengan email</a>.</p>
@endsection
