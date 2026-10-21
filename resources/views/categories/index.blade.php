@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="page-head">
    <h1>Kategori</h1>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah Kategori</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah Produk</th>
                    @if (auth()->user()->isAdmin())
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->products_count }}</td>
                        @if (auth()->user()->isAdmin())
                            <td>
                                <div class="actions">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn-action btn-action-edit">Ubah</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $categories->links() }}</div>
</div>
@endsection
