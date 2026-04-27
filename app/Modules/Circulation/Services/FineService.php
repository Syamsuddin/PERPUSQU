<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Circulation\Models\Fine;
use Illuminate\Pagination\LengthAwarePaginator;

class FineService
{
    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        return Fine::with(['loan.physicalItem', 'member'])
            ->when(isset($filters['member_id']), fn ($q) => $q->where('member_id', $filters['member_id']))
            ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['fine_type']), fn ($q) => $q->where('fine_type', $filters['fine_type']))
            ->when(isset($filters['from_date']), fn ($q) => $q->whereDate('created_at', '>=', $filters['from_date']))
            ->when(isset($filters['to_date']), fn ($q) => $q->whereDate('created_at', '<=', $filters['to_date']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function settle(Fine $fine): Fine
    {
        if ($fine->status !== 'outstanding') {
            throw new \InvalidArgumentException('Hanya denda outstanding yang dapat dilunasi.');
        }
        $fine->update(['status' => 'settled']);

        activity('circulation')
            ->causedBy(auth()->user())
            ->performedOn($fine)
            ->log("Denda #$fine->id dilunasi (Rp ".number_format($fine->amount, 0, ',', '.').')');

        return $fine;
    }

    public function waive(Fine $fine): Fine
    {
        if ($fine->status !== 'outstanding') {
            throw new \InvalidArgumentException('Hanya denda outstanding yang dapat dihapuskan.');
        }
        $fine->update(['status' => 'waived']);

        activity('circulation')
            ->causedBy(auth()->user())
            ->performedOn($fine)
            ->log("Denda #$fine->id dihapuskan (Rp ".number_format($fine->amount, 0, ',', '.').')');

        return $fine;
    }

    public function getSummary(): array
    {
        return [
            'total_outstanding' => Fine::where('status', 'outstanding')->sum('amount'),
            'total_settled' => Fine::where('status', 'settled')->sum('amount'),
            'total_waived' => Fine::where('status', 'waived')->sum('amount'),
            'count_outstanding' => Fine::where('status', 'outstanding')->count(),
            'count_settled' => Fine::where('status', 'settled')->count(),
            'count_waived' => Fine::where('status', 'waived')->count(),
        ];
    }
}
