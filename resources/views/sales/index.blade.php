@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="toolbar">
    <h1>Penjualan</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Buat Penjualan</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No. Transaksi</th>
                <th>Tanggal</th>
                <th>Oleh</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->sale_number }}</a></td>
                    <td>{{ $sale->sold_at->format('d M Y H:i') }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>{{ $sale->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada transaksi penjualan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $sales->links() }}</div>
</div>
@endsection
