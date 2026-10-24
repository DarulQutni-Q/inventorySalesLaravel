@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="page-head">
    <div>
        <h1>Supplier</h1>
        <p class="page-desc">Supplier dicatat di setiap transaksi barang masuk.</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('suppliers.create') }}" class="btn">Tambah Supplier</a>
    @endif
</div>

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
            @forelse ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone ?? '-' }}</td>
                    <td>{{ $supplier->email ?? '-' }}</td>
                    <td>{{ $supplier->address ?? '-' }}</td>
                    @if (auth()->user()->isAdmin())
                        <td>
                            <div class="actions">
                                <a href="{{ route('suppliers.edit', $supplier) }}">Ubah</a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
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
{{ $suppliers->links() }}
@endsection
