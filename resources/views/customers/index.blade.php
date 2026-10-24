@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="page-head">
    <div>
        <h1>Pelanggan</h1>
        <p class="page-desc">Pelanggan pesanan katalog dan penjualan. Telepon adalah identitas saat pelanggan masuk.</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('customers.create') }}" class="btn">Tambah Pelanggan</a>
    @endif
</div>

<form method="GET" action="{{ route('customers.index') }}" class="filter-bar">
    <div class="field">
        <label for="search">Cari nama atau telepon</label>
        <input type="text" id="search" name="search" value="{{ request('search') }}">
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn">Cari</button>
        <a href="{{ route('customers.index') }}">Reset</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
                @if (auth()->user()->isAdmin())
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->address ?? '-' }}</td>
                    @if (auth()->user()->isAdmin())
                        <td>
                            <div class="actions">
                                <a href="{{ route('customers.edit', $customer) }}">Ubah</a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-btn">Hapus</button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="5" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $customers->links() }}
@endsection
