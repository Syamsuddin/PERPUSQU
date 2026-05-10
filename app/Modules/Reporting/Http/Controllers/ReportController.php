<?php

namespace App\Modules\Reporting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\Member\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'collections');
        $year = (int) $request->get('year', now()->year);
        $month = $request->get('month');

        $data = match ($tab) {
            'members'     => $this->membersData(),
            'circulation' => $this->circulationData($year, $month),
            'fines'       => $this->finesData($year),
            default       => $this->collectionsData(),
        };

        $years = range(now()->year, max(now()->year - 4, 2020));

        return view('modules.reporting.index', array_merge($data, compact('tab', 'year', 'month', 'years')));
    }

    private function collectionsData(): array
    {
        $byStatus = BibliographicRecord::select('publication_status', DB::raw('count(*) as total'))
            ->groupBy('publication_status')
            ->pluck('total', 'publication_status');

        $byType = BibliographicRecord::select('collection_type_id', DB::raw('count(*) as total'))
            ->with('collectionType')
            ->groupBy('collection_type_id')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->collectionType?->name ?? 'Tidak Dikategorikan',
                'total' => $r->total,
            ]);

        $itemsByStatus = PhysicalItem::select('item_status', DB::raw('count(*) as total'))
            ->groupBy('item_status')
            ->pluck('total', 'item_status');

        $popular = Loan::select('physical_item_id', DB::raw('count(*) as loan_count'))
            ->with(['physicalItem.bibliographicRecord'])
            ->groupBy('physical_item_id')
            ->orderByDesc('loan_count')
            ->limit(10)
            ->get()
            ->filter(fn ($l) => $l->physicalItem?->bibliographicRecord);

        return compact('byStatus', 'byType', 'itemsByStatus', 'popular');
    }

    private function membersData(): array
    {
        $byType = Member::select('member_type', DB::raw('count(*) as total'))
            ->groupBy('member_type')
            ->pluck('total', 'member_type');

        $byFaculty = Member::select('faculty_id', DB::raw('count(*) as total'))
            ->with('faculty')
            ->groupBy('faculty_id')
            ->get()
            ->map(fn ($r) => [
                'label' => $r->faculty?->name ?? 'Tanpa Fakultas',
                'total' => $r->total,
            ])
            ->sortByDesc('total')
            ->values();

        $statusSummary = [
            'total'    => Member::count(),
            'active'   => Member::where('is_active', true)->count(),
            'inactive' => Member::where('is_active', false)->count(),
            'blocked'  => Member::where('is_blocked', true)->count(),
        ];

        $topBorrowers = Member::withCount(['loans' => fn ($q) => $q->whereYear('loan_date', now()->year)])
            ->orderByDesc('loans_count')
            ->limit(10)
            ->get();

        return compact('byType', 'byFaculty', 'statusSummary', 'topBorrowers');
    }

    private function circulationData(int $year, ?string $month): array
    {
        $monthlyLoans = Loan::select(
            DB::raw('MONTH(loan_date) as month'),
            DB::raw('count(*) as total')
        )
            ->whereYear('loan_date', $year)
            ->groupBy(DB::raw('MONTH(loan_date)'))
            ->orderBy('month')
            ->pluck('total', 'month');

        $months = collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => $monthlyLoans->get($m, 0)]);

        $summary = [
            'total_loans'   => Loan::whereYear('loan_date', $year)->count(),
            'active'        => Loan::where('loan_status', 'active')->count(),
            'overdue'       => Loan::overdue()->count(),
            'returned'      => Loan::where('loan_status', 'returned')->whereYear('returned_at', $year)->count(),
        ];

        $overdueList = Loan::with(['member', 'physicalItem.bibliographicRecord'])
            ->overdue()
            ->orderBy('due_date')
            ->limit(15)
            ->get();

        $recentLoans = Loan::with(['member', 'physicalItem.bibliographicRecord'])
            ->whereYear('loan_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('loan_date', $month))
            ->latest('loan_date')
            ->limit(20)
            ->get();

        return compact('months', 'summary', 'overdueList', 'recentLoans', 'year');
    }

    private function finesData(int $year): array
    {
        $summary = [
            'outstanding' => Fine::where('status', 'outstanding')->sum('amount'),
            'settled'     => Fine::where('status', 'settled')->whereYear('updated_at', $year)->sum('amount'),
            'waived'      => Fine::where('status', 'waived')->whereYear('updated_at', $year)->sum('amount'),
            'count_outstanding' => Fine::where('status', 'outstanding')->count(),
            'count_settled'     => Fine::where('status', 'settled')->count(),
            'count_waived'      => Fine::where('status', 'waived')->count(),
        ];

        $monthlyFines = Fine::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('sum(amount) as total'),
            DB::raw('count(*) as count')
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = collect(range(1, 12))->map(fn ($m) => [
            'month' => $m,
            'total' => $monthlyFines->get($m)?->total ?? 0,
            'count' => $monthlyFines->get($m)?->count ?? 0,
        ]);

        $topDebtors = Member::withSum(['fines as outstanding_amount' => fn ($q) => $q->where('status', 'outstanding')], 'amount')
            ->having('outstanding_amount', '>', 0)
            ->orderByDesc('outstanding_amount')
            ->limit(10)
            ->get();

        $recentFines = Fine::with(['member', 'loan.physicalItem.bibliographicRecord'])
            ->whereYear('created_at', $year)
            ->latest()
            ->limit(20)
            ->get();

        return compact('summary', 'months', 'topDebtors', 'recentFines', 'year');
    }
}
