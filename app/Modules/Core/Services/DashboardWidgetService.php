<?php

namespace App\Modules\Core\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;
use App\Modules\Reporting\Services\DailyStatisticsService;

class DashboardWidgetService
{
    public function __construct(
        protected DailyStatisticsService $statistics,
    ) {}

    public function buildDashboardForUser(User $user): array
    {
        $widgets = [];

        // Core stats visible to all authenticated users
        $widgets['total_catalog'] = BibliographicRecord::count();
        $widgets['total_items'] = PhysicalItem::count();
        $widgets['total_members'] = Member::count();
        $widgets['active_loans'] = Loan::active()->count();
        $widgets['overdue_loans'] = Loan::overdue()->count();
        $widgets['total_digital_assets'] = DigitalAsset::count();
        $widgets['total_users'] = User::count();
        $widgets['available_items'] = PhysicalItem::available()->count();

        // Recent data
        $widgets['recent_catalogs'] = BibliographicRecord::with('authors')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $widgets['recent_loans'] = Loan::with(['member', 'physicalItem.bibliographicRecord'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Selisih terhadap potret harian terakhir. Angka utamanya tetap dibaca
        // langsung dari basis data — mengganti angka hidup dengan potret kemarin
        // akan membuat dashboard berbohong. Potret hanya dipakai sebagai
        // PEMBANDING, dan bernilai null selama belum ada potret sama sekali:
        // "0" akan terbaca sebagai "tidak ada perubahan" padahal yang benar
        // adalah "belum diketahui".
        $widgets['changes'] = [
            'total_catalog' => $this->statistics->changeSinceLastSnapshot('titles_total', $widgets['total_catalog']),
            'total_members' => $this->statistics->changeSinceLastSnapshot('members_total', $widgets['total_members']),
            'active_loans' => $this->statistics->changeSinceLastSnapshot('loans_active', $widgets['active_loans']),
            'overdue_loans' => $this->statistics->changeSinceLastSnapshot('loans_overdue', $widgets['overdue_loans']),
        ];

        return $widgets;
    }
}
