@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="page-head">
    <h1>Penjualan</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">+ Tambah Penjualan</a>
</div>

<div class="card">
    <form method="GET" action="{{ route('sales.index') }}" class="filter-bar">
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
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    @error('sampai')<div class="error-text" style="margin-bottom: 12px;">{{ $message }}</div>@enderror

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NO FAKTUR</th>
                    <th>TANGGAL</th>
                    <th>OLEH</th>
                    <th>TOTAL ITEM</th>
                    <th>TOTAL HARGA</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $i => $sale)
                    <tr>
                        <td>{{ $sales->firstItem() + $i }}</td>
                        <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->sale_number }}</a></td>
                        <td>{{ $sale->sold_at->format('d/m/Y') }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td>{{ $sale->items_count }}</td>
                        <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $sale->status === 'completed' ? 'badge-done' : 'badge-cancel' }}">
                                {{ $sale->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-sm">Detail</a>
                            @if (auth()->user()->isAdmin() && $sale->status === 'completed')
                                <form action="{{ route('sales.cancel', $sale) }}" method="POST" style="display:inline"
                                      onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Batal</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">Tidak ada transaksi penjualan yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $sales->links() }}</div>
</div>
@endsection
