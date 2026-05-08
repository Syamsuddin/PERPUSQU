<?php

namespace App\Modules\Core\Services;

use App\Modules\Identity\Models\User;
use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Member\Models\Member;
use App\Modules\Circulation\Models\Loan;
use App\Modules\DigitalRepository\Models\DigitalAsset;

class DashboardWidgetService
{
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

        return $widgets;
    }
}
