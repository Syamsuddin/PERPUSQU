<?php

namespace App\Modules\Reporting\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Member\Models\Member;
use App\Modules\Reporting\Models\DailyStatistic;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Mengambil dan membaca potret harian.
 *
 * Catatan penting soal kejujuran angka: potret dijalankan sesaat setelah tengah
 * malam, dan angka STOK yang dicatat adalah keadaan PADA SAAT ITU. Untuk
 * tanggal kemarin, keadaan awal hari ini adalah pendekatan terbaik yang dapat
 * diperoleh — keadaan kemarin pukul 23:59 tidak dapat dihitung ulang setelah
 * lewat. Karena itu potret untuk tanggal lampau yang jauh sengaja TIDAK
 * disediakan: mengarang angka stok jauh lebih berbahaya daripada tidak punya.
 *
 * Angka ARUS berbeda: sepenuhnya dihitung dari rentang tanggalnya sendiri,
 * sehingga tetap benar kapan pun dihitung ulang.
 */
class DailyStatisticsService
{
    /**
     * Ambil potret untuk satu tanggal. Menimpa potret yang sudah ada agar
     * penjadwal yang berjalan dua kali tidak menggandakan baris.
     */
    public function capture(?Carbon $date = null): DailyStatistic
    {
        $date = ($date ?? now()->subDay())->copy()->startOfDay();

        // Pencocokan lewat whereDate, bukan updateOrCreate dengan string
        // tanggal: cast `date` menuliskan kolomnya sebagai `Y-m-d H:i:s` di
        // SQLite, sehingga perbandingan dengan `Y-m-d` tidak pernah cocok dan
        // potret kedua akan menabrak indeks uniknya. whereDate membandingkan
        // bagian tanggalnya saja, dan berlaku sama di kedua driver.
        $snapshot = DailyStatistic::query()
            ->whereDate('snapshot_date', $date)
            ->first() ?? new DailyStatistic;

        $snapshot->fill(['snapshot_date' => $date] + $this->stockMetrics() + $this->flowMetrics($date));
        $snapshot->save();

        return $snapshot;
    }

    /**
     * @return array<string, int>
     */
    protected function stockMetrics(): array
    {
        return [
            'titles_total' => BibliographicRecord::count(),
            'titles_public' => BibliographicRecord::published()->public()->count(),
            'items_total' => PhysicalItem::count(),
            'items_available' => PhysicalItem::where('item_status', 'available')->count(),
            'items_loaned' => PhysicalItem::where('item_status', 'loaned')->count(),
            'members_total' => Member::count(),
            'members_active' => Member::where('is_active', true)->count(),
            'members_blocked' => Member::where('is_blocked', true)->count(),
            'loans_active' => Loan::where('loan_status', 'active')->count(),
            'loans_overdue' => Loan::where('loan_status', 'active')->where('due_date', '<', now())->count(),
            'digital_assets_total' => DigitalAsset::count(),
            'digital_assets_public' => DigitalAsset::where('publication_status', 'published')->where('is_public', true)->count(),
            'fines_outstanding_amount' => (int) round((float) Fine::where('status', 'outstanding')->sum('amount')),
        ];
    }

    /**
     * @return array<string, int>
     */
    protected function flowMetrics(Carbon $date): array
    {
        [$from, $to] = [$date->copy()->startOfDay(), $date->copy()->endOfDay()];

        return [
            'loans_created' => Loan::whereBetween('loan_date', [$from, $to])->count(),
            'loans_returned' => Loan::whereBetween('returned_at', [$from, $to])->count(),
            'fines_raised_amount' => (int) round((float) Fine::whereBetween('created_at', [$from, $to])->sum('amount')),
        ];
    }

    /**
     * Deret satu metrik untuk beberapa hari terakhir, siap dipakai grafik.
     *
     * @return Collection<int, array{date: string, value: int}>
     */
    public function series(string $metric, int $days = 30): Collection
    {
        $this->assertKnownMetric($metric);

        return DailyStatistic::query()
            ->since(now()->subDays($days)->startOfDay())
            ->orderBy('snapshot_date')
            ->get(['snapshot_date', $metric])
            ->map(fn (DailyStatistic $row) => [
                'date' => $row->snapshot_date->toDateString(),
                'value' => (int) $row->{$metric},
            ]);
    }

    /**
     * Selisih satu metrik terhadap potret terakhir.
     *
     * Mengembalikan null bila belum ada potret sama sekali — instalasi baru
     * belum punya pembanding, dan menampilkan "0" akan terbaca sebagai "tidak
     * ada perubahan" padahal yang benar adalah "belum diketahui".
     */
    public function changeSinceLastSnapshot(string $metric, int $currentValue): ?int
    {
        $this->assertKnownMetric($metric);

        $latest = DailyStatistic::query()->orderByDesc('snapshot_date')->first();

        return $latest ? $currentValue - (int) $latest->{$metric} : null;
    }

    public function latest(): ?DailyStatistic
    {
        return DailyStatistic::query()->orderByDesc('snapshot_date')->first();
    }

    /**
     * Salah ketik nama metrik akan menghasilkan kolom yang tidak ada. Dijaga
     * di sini supaya gagalnya jelas, bukan berubah menjadi kueri yang aneh.
     */
    protected function assertKnownMetric(string $metric): void
    {
        $known = array_merge(DailyStatistic::STOCK_METRICS, DailyStatistic::FLOW_METRICS);

        if (! in_array($metric, $known, true)) {
            throw new \InvalidArgumentException("Metrik statistik tidak dikenal: {$metric}.");
        }
    }
}
