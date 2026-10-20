@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="page-head">
    <h1>Laporan Transaksi</h1>
    <form method="GET" action="{{ route('reports.transactions') }}" class="filter-bar" style="margin-bottom:0;">
        <div>
            <label for="dari">Dari</label>
            <input type="date" id="dari" name="dari" value="{{ $dari }}">
        </div>
        <div>
            <label for="sampai">Sampai</label>
            <input type="date" id="sampai" name="sampai" value="{{ $sampai }}">
        </div>
        <div>
            <button type="submit" class="btn btn-ghost">Filter</button>
        </div>
    </form>
</div>

<div class="stats">
    <div class="stat">
        <div class="label">Total Pembelian <span style="font-weight:400;color:#94a3b8;">({{ $dari }} s/d {{ $sampai }})</span></div>
        <div class="value small">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</div>
    </div>
    <div class="stat">
        <div class="label">Total Penjualan <span style="font-weight:400;color:#94a3b8;">({{ $dari }} s/d {{ $sampai }})</span></div>
        <div class="value small">Rp {{ number_format($totalSale, 0, ',', '.') }}</div>
    </div>
</div>

<div class="card" style="margin-bottom:16px;">
    <h2 style="margin:0 0 16px;font-size:16px;font-weight:600;color:#0f172a;">Pembelian (Barang Masuk)</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td>
                            <a href="{{ route('purchases.show', $purchase) }}"
                               style="color:#0f172a;font-weight:500;text-decoration:none;font-family:monospace;">
                                {{ $purchase->purchase_number }}
                            </a>
                        </td>
                        <td style="color:#64748b;">{{ $purchase->purchased_at->format('d M Y H:i') }}</td>
                        <td style="color:#475569;">{{ $purchase->supplier->name }}</td>
                        <td style="font-weight:600;color:#0f172a;">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $purchase->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
                                {{ $purchase->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Tidak ada pembelian pada rentang tanggal ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $purchases->links() }}</div>
</div>

<div class="card">
    <h2 style="margin:0 0 16px;font-size:16px;font-weight:600;color:#0f172a;">Penjualan (Barang Keluar)</h2>
    <div class="table-wrap">
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
                        <td>
                            <a href="{{ route('sales.show', $sale) }}"
                               style="color:#0f172a;font-weight:500;text-decoration:none;font-family:monospace;">
                                {{ $sale->sale_number }}
                            </a>
                        </td>
                        <td style="color:#64748b;">{{ $sale->sold_at->format('d M Y H:i') }}</td>
                        <td style="color:#475569;">{{ $sale->user->name }}</td>
                        <td style="font-weight:600;color:#0f172a;">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $sale->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
                                {{ $sale->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Tidak ada penjualan pada rentang tanggal ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $sales->links() }}</div>
</div>
@endsection
