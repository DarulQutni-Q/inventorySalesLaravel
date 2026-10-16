@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="page-head">
    <h1>Kelola User</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge {{ $user->role === 'admin' ? 'badge-done' : 'badge-cancel' }}">{{ $user->role }}</span></td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->links() }}</div>
</div>
@endsection
