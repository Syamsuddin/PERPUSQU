@extends('layouts.admin')
@section('title', 'Daftar Role')
@section('page-title', 'Manajemen Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Role</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold mb-0">Daftar Role</h5>
    @can('roles.create')
    <a href="{{ route('admin.access.roles.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Role</a>
    @endcan
</div>
<div class="pq-card">
    <div class="table-responsive">
        <table class="table pq-table mb-0">
            <thead><tr><th>#</th><th>Nama Role</th><th>Jumlah User</th><th>Jumlah Permission</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $roles->firstItem() + $loop->index }}</td>
                    <td class="fw-medium">{{ $role->name }}</td>
                    <td><span class="badge bg-secondary">{{ $role->users_count }}</span></td>
                    <td><span class="badge bg-info text-dark">{{ $role->permissions_count }}</span></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            @can('roles.update')
                            <a href="{{ route('admin.access.roles.edit', $role) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('roles.delete')
                            <form method="POST" action="{{ route('admin.access.roles.destroy', $role) }}" class="d-inline" onsubmit="return confirm('Hapus role ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada role.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($roles->hasPages())<div class="p-3">{{ $roles->links() }}</div>@endif
</div>
@endsection
