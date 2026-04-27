@extends('layouts.admin')
@section('title', 'Daftar Anggota')
@section('page-title', 'Anggota Perpustakaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Anggota</li>
@endsection
@section('content')
@php $resolver = \App\Modules\Member\Support\MemberEligibilityResolver::class; @endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Daftar Anggota</h5><small class="text-muted">{{ $items->total() }} data terdaftar</small></div>
    @can('members.create')
    <a href="{{ route('admin.members.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Anggota</a>
    @endcan
</div>
{{-- Filter --}}
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, nomor anggota, identitas..."></div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="member_type">
                <option value="">Semua Tipe</option>
                @foreach(['student'=>'Mahasiswa','lecturer'=>'Dosen','staff'=>'Staf','alumni'=>'Alumni','guest'=>'Tamu'] as $v => $l)
                <option value="{{ $v }}" {{ request('member_type')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="faculty_id">
                <option value="">Semua Fakultas</option>
                @foreach($faculties as $f)<option value="{{ $f->id }}" {{ request('faculty_id')==$f->id?'selected':'' }}>{{ $f->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="is_active">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active')==='1'?'selected':'' }}>Aktif</option>
                <option value="0" {{ request('is_active')==='0'?'selected':'' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a>
        </div>
    </form>
</div></div>
{{-- Table --}}
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th style="width:50px">#</th><th>Nama</th><th>No. Anggota</th><th>Tipe</th><th>Fakultas</th><th>Status</th><th class="text-center">Pinjaman</th><th class="text-center" style="width:140px">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($items as $item)
            @php $state = $resolver::derivedState($item); @endphp
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td>
                    <a href="{{ route('admin.members.show', $item) }}" class="text-decoration-none fw-medium">{{ $item->name }}</a>
                    @if($item->email)<div class="small text-muted">{{ $item->email }}</div>@endif
                </td>
                <td><code>{{ $item->member_number }}</code></td>
                <td><small>{{ $resolver::typeLabel($item->member_type) }}</small></td>
                <td><small>{{ $item->faculty->name ?? '-' }}</small></td>
                <td><span class="badge bg-{{ $resolver::stateBadgeClass($state) }}">{{ $resolver::stateLabel($state) }}</span></td>
                <td class="text-center"><span class="badge bg-info">{{ $item->loans_count }}</span></td>
                <td class="text-center"><div class="btn-group btn-group-sm">
                    @can('members.view')<a href="{{ route('admin.members.show', $item) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>@endcan
                    @can('members.update')<a href="{{ route('admin.members.edit', $item) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
                    @can('members.delete')
                    <form method="POST" action="{{ route('admin.members.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus anggota ini?')">@csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                    </form>@endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection
