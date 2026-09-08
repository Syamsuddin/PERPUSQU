<ul class="nav nav-pills mb-4">
    @can('own_loans.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.portal.loans') ? 'active' : '' }}"
               href="{{ route('member.portal.loans') }}"><i class="bi bi-journal-bookmark me-1"></i>Pinjaman Saya</a>
        </li>
    @endcan
    @can('own_loans.view_history')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.portal.history') ? 'active' : '' }}"
               href="{{ route('member.portal.history') }}"><i class="bi bi-clock-history me-1"></i>Riwayat</a>
        </li>
    @endcan
    @can('own_fines.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('member.portal.fines') ? 'active' : '' }}"
               href="{{ route('member.portal.fines') }}"><i class="bi bi-cash-coin me-1"></i>Denda Saya</a>
        </li>
    @endcan
</ul>
