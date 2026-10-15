@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="toolbar">
    <h1>Kategori</h1>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah Kategori</a>
    @endif
</div>

<div class="card">
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
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary">Ubah</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="3">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $categories->links() }}</div>
</div>
@endsection
