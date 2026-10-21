@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="page-head">
    <h1>Kelola User</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
</div>

<div class="card">
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
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:36px;height:36px;border-radius:50%;background:#ecfccb;color:#3f6212;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;text-transform:uppercase;">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <span style="font-weight:500;color:#0f172a;">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748b;">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-admin">Admin</span>
                            @else
                                <span class="badge badge-staff">Staff</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-action-edit">Ubah</a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete">Hapus</button>
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
