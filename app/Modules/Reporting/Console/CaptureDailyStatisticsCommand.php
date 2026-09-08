<?php

namespace App\Modules\Reporting\Console;

use App\Modules\Reporting\Services\DailyStatisticsService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CaptureDailyStatisticsCommand extends Command
{
    protected $signature = 'library:capture-daily-statistics {--date= : Tanggal potret (Y-m-d); bawaan kemarin}';

    protected $description = 'Ambil potret statistik harian perpustakaan';

    public function handle(DailyStatisticsService $statistics): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : now()->subDay();

        $snapshot = $statistics->capture($date);

        $this->info("Potret {$snapshot->snapshot_date->toDateString()} tersimpan.");
        $this->table(
            ['Metrik', 'Nilai'],
            [
                ['Judul (publik)', $snapshot->titles_total.' ('.$snapshot->titles_public.')'],
                ['Item (tersedia)', $snapshot->items_total.' ('.$snapshot->items_available.')'],
                ['Anggota (aktif)', $snapshot->members_total.' ('.$snapshot->members_active.')'],
                ['Pinjaman aktif (terlambat)', $snapshot->loans_active.' ('.$snapshot->loans_overdue.')'],
                ['Pinjaman hari itu', $snapshot->loans_created],
                ['Pengembalian hari itu', $snapshot->loans_returned],
                ['Denda belum lunas', 'Rp '.number_format($snapshot->fines_outstanding_amount, 0, ',', '.')],
            ]
        );

        return self::SUCCESS;
    }
}
