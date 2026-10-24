@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="page-head">
    <div>
        <h1>Kategori</h1>
        <p class="page-desc">Kategori dipakai untuk mengelompokkan produk.</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="btn">Tambah Kategori</a>
    @endif
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th class="num">Jumlah produk</th>
                @if (auth()->user()->isAdmin())
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td class="num">{{ $category->products_count }}</td>
                    @if (auth()->user()->isAdmin())
                        <td>
                            <div class="actions">
                                <a href="{{ route('categories.edit', $category) }}">Ubah</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-btn">Hapus</button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="3" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $categories->links() }}
@endsection
