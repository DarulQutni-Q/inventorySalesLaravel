@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<div class="page-head">
    <h1>Pembelian</h1>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Buat Pembelian</a>
</div>

<div class="card">
    <form method="GET" action="{{ route('purchases.index') }}" class="filter-bar">
        <div>
            <label for="dari">Dari Tanggal</label>
            <input type="date" id="dari" name="dari" value="{{ $filter['dari'] ?? '' }}">
        </div>
        <div>
            <label for="sampai">Sampai Tanggal</label>
            <input type="date" id="sampai" name="sampai" value="{{ $filter['sampai'] ?? '' }}">
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua</option>
                <option value="completed" @selected(($filter['status'] ?? '') === 'completed')>Selesai</option>
                <option value="cancelled" @selected(($filter['status'] ?? '') === 'cancelled')>Dibatalkan</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    @error('sampai')<div class="error-text" style="margin-bottom: 12px;">{{ $message }}</div>@enderror

    <div class="table-wrap">
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
                        <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->purchase_number }}</a></td>
                        <td>{{ $purchase->purchased_at->format('d M Y H:i') }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td>{{ $purchase->user->name }}</td>
                        <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $purchase->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
                                {{ $purchase->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Tidak ada transaksi pembelian yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $purchases->links() }}</div>
</div>
@endsection
