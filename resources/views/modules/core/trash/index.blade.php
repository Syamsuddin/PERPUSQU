@extends('layouts.admin')
@section('title', 'Kotak Sampah')
@section('page-title', 'Kotak Sampah')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kotak Sampah</li>
@endsection
@section('content')

<div class="alert alert-light border d-flex align-items-start mb-4">
    <i class="bi bi-info-circle me-2 mt-1"></i>
    <div class="small">
        Data yang dihapus tidak langsung musnah — ia berpindah ke sini dan dapat dipulihkan.
        Nomor identitasnya (barcode, nomor anggota, ISBN) tetap dipegang selama berada di kotak sampah,
        sehingga tidak dapat dipakai ulang oleh data baru.
    </div>
</div>

<ul class="nav nav-pills mb-4 flex-wrap">
    @foreach($types as $key => $meta)
        @can($meta['permission'])
            <li class="nav-item">
                <a class="nav-link {{ $type === $key ? 'active' : '' }}"
                   href="{{ route('admin.trash.index', ['type' => $key]) }}">
                    {{ $meta['label'] }}
                    <span class="badge {{ $type === $key ? 'bg-light text-dark' : 'bg-secondary' }} ms-1">{{ $counts[$key] }}</span>
                </a>
            </li>
        @endcan
    @endforeach
</ul>

<div class="pq-card p-4">
    @if($records->isEmpty())
        <p class="text-muted mb-0 text-center py-4">
            <i class="bi bi-check-circle me-1"></i>Tidak ada {{ strtolower($definition['label']) }} di kotak sampah.
        </p>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ $definition['label'] }}</th>
                        <th>Dihapus</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $record->{$definition['title']} ?? '#'.$record->getKey() }}</div>
                            @if($definition['subtitle'] && $record->{$definition['subtitle']})
                                <small class="text-muted">{{ $record->{$definition['subtitle']} }}</small>
                            @endif
                            @if($definition['parent'])
                                @php($parent = $record->{$definition['parent']})
                                @if($parent === null || $parent->trashed())
                                    <div><span class="badge bg-warning text-dark mt-1">Katalog induk masih terhapus</span></div>
                                @endif
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $record->deleted_at?->diffForHumans() }}</small></td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.trash.restore', ['type' => $type, 'id' => $record->getKey()]) }}"
                                  onsubmit="return confirm('Pulihkan data ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Pulihkan
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $records->appends(['type' => $type])->links() }}</div>
    @endif
</div>
@endsection
