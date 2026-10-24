@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
<div class="page-head">
    <div>
        <h1>Pesanan Saya</h1>
        <p class="page-desc">Pesanan menunggu sampai staff mengkonfirmasi. Stok berkurang saat konfirmasi, bukan saat pesanan dibuat.</p>
    </div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nomor pesanan</th>
                <th>Tanggal</th>
                <th class="num">Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('customer.orders.show', $order) }}" class="mono">{{ $order->order_number }}</a></td>
                    <td>{{ $order->ordered_at->format('d/m/Y H:i') }}</td>
                    <td class="num">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>{{ $order->statusLabel() }}</td>
                    <td><a href="{{ route('customer.orders.show', $order) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-cell">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $orders->links() }}
@endsection
