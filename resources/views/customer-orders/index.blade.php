@extends('layouts.app')

@section('title', 'Pesanan Pelanggan')

@section('content')
<div class="page-head">
    <div>
        <h1>Pesanan Pelanggan</h1>
        <p class="page-desc">Konfirmasi mengubah pesanan menjadi penjualan dan mengurangi stok saat itu juga.</p>
    </div>
</div>

<form method="GET" action="{{ route('customer-orders.index') }}" class="filter-bar">
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            <option value="pending" @selected(($filter['status'] ?? '') === 'pending')>Menunggu konfirmasi</option>
            <option value="confirmed" @selected(($filter['status'] ?? '') === 'confirmed')>Dikonfirmasi</option>
            <option value="rejected" @selected(($filter['status'] ?? '') === 'rejected')>Ditolak</option>
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn">Cari</button>
        <a href="{{ route('customer-orders.index') }}">Reset</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nomor pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th class="num">Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('customer-orders.show', $order) }}" class="mono">{{ $order->order_number }}</a></td>
                    <td>{{ $order->customer?->name }}</td>
                    <td>{{ $order->ordered_at->format('d/m/Y H:i') }}</td>
                    <td class="num">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>{{ $order->statusLabel() }}</td>
                    <td><a href="{{ route('customer-orders.show', $order) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $orders->links() }}
@endsection
