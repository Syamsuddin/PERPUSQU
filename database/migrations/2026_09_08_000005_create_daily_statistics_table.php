<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Potret harian keadaan perpustakaan.
 *
 * Modul laporan sampai sekarang hanya dapat menjawab "berapa sekarang", tidak
 * pernah "bagaimana perkembangannya", karena tidak ada satu pun yang menyimpan
 * keadaan kemarin. Tabel ini yang menyimpannya.
 *
 * Dua jenis angka sengaja dibedakan:
 *
 *  - STOK (`*_total`, `*_active`, dan sejenisnya) — keadaan pada saat potret
 *    diambil. Potret dijalankan sesaat setelah tengah malam, sehingga angkanya
 *    dibaca sebagai keadaan pada akhir hari yang dicatat.
 *  - ARUS (`loans_created`, `loans_returned`, `fines_raised_amount`) — apa yang
 *    terjadi SELAMA hari itu, dihitung dari rentang tanggalnya sendiri.
 *
 * Perbedaan itu penting: angka stok untuk tanggal lampau tidak dapat dihitung
 * ulang, sedangkan angka arus dapat. Lihat DailyStatisticsService.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_statistics', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date')->unique('uq_daily_statistics_snapshot_date');

            // Stok
            $table->unsignedInteger('titles_total')->default(0);
            $table->unsignedInteger('titles_public')->default(0);
            $table->unsignedInteger('items_total')->default(0);
            $table->unsignedInteger('items_available')->default(0);
            $table->unsignedInteger('items_loaned')->default(0);
            $table->unsignedInteger('members_total')->default(0);
            $table->unsignedInteger('members_active')->default(0);
            $table->unsignedInteger('members_blocked')->default(0);
            $table->unsignedInteger('loans_active')->default(0);
            $table->unsignedInteger('loans_overdue')->default(0);
            $table->unsignedInteger('digital_assets_total')->default(0);
            $table->unsignedInteger('digital_assets_public')->default(0);

            // Arus
            $table->unsignedInteger('loans_created')->default(0);
            $table->unsignedInteger('loans_returned')->default(0);

            // Rupiah bulat: mata uang ini tidak punya satuan pecahan yang dipakai.
            $table->unsignedBigInteger('fines_raised_amount')->default(0);
            $table->unsignedBigInteger('fines_outstanding_amount')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_statistics');
    }
};
