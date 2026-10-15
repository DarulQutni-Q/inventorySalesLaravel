@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<div class="toolbar">
    <h1>Pembelian</h1>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Buat Pembelian</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No. Transaksi</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Oleh</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->purchase_number }}</td>
                    <td>{{ $purchase->purchased_at->format('d M Y H:i') }}</td>
                    <td>{{ $purchase->supplier->name }}</td>
                    <td>{{ $purchase->user->name }}</td>
                    <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                    <td>{{ $purchase->status }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada transaksi pembelian.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $purchases->links() }}</div>
</div>
@endsection
