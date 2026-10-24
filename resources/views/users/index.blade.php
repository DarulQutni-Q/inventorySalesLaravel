@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="page-head">
    <div>
        <h1>Kelola User</h1>
        <p class="page-desc">Admin mengelola semua hal; staff membuat transaksi dan melihat data.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn">Tambah User</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role === 'admin' ? 'Admin' : 'Staff' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('users.edit', $user) }}">Ubah</a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-btn">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty-cell">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $users->links() }}
@endsection
