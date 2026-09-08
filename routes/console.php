<?php

use App\Modules\Circulation\Console\SendLoanRemindersCommand;
use App\Modules\DigitalRepository\Console\ReleaseExpiredEmbargoesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Pekerjaan Terjadwal
|--------------------------------------------------------------------------
|
| Perpustakaan berjalan di atas waktu — jatuh tempo, denda, embargo — tetapi
| sampai sekarang tidak ada satu pun pekerjaan terjadwal. Akibatnya anggota
| baru mengetahui keterlambatannya saat ditagih, jejak audit tumbuh tanpa
| batas, dan embargo yang sudah berakhir tetap tampak menutup akses.
|
| Agar benar-benar berjalan, server produksi memerlukan satu baris cron:
|
|     * * * * * cd /path/ke/perpusqu && php artisan schedule:run >> /dev/null 2>&1
|
*/

// Pagi hari, sebelum jam layanan: anggota menerima pengingat saat masih
// sempat bertindak, bukan tengah malam.
Schedule::command(SendLoanRemindersCommand::class)
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command(ReleaseExpiredEmbargoesCommand::class)
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->onOneServer();

// Jejak audit adalah satu-satunya catatan pertanggungjawaban, jadi retensinya
// panjang — dua tahun — tetapi tetap berbatas. `--days` disebut eksplisit
// karena konfigurasi paket tidak dipublikasikan di proyek ini.
Schedule::command('activitylog:clean --days=730 --force')
    ->weeklyOn(1, '02:00')
    ->withoutOverlapping()
    ->onOneServer();
