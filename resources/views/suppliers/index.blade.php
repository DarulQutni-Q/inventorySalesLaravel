@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<div class="page-head">
    <h1>Supplier</h1>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Tambah Supplier</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Jml Pembelian</th>
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
                        <td>{{ $supplier->purchases_count }}</td>
                        @if (auth()->user()->isAdmin())
                            <td>
                                <div class="actions">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada supplier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $suppliers->links() }}</div>
</div>
@endsection
