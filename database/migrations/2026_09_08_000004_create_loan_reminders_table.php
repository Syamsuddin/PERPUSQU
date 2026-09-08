<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catatan pengingat jatuh tempo yang sudah dikirim.
 *
 * Tanpa tabel ini, perintah harian akan mengirim ulang pengingat yang sama
 * setiap hari kepada anggota yang sama — cara tercepat membuat orang berhenti
 * membaca surat dari perpustakaan. Indeks uniknya yang menegakkan aturan
 * "satu pengingat per jenis per pinjaman", bukan sekadar pengecekan di kode.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->index('idx_loan_reminders_loan_id');
            $table->string('kind', 30);
            $table->string('channel', 30)->default('mail');
            $table->dateTime('sent_at')->index('idx_loan_reminders_sent_at');
            $table->timestamps();

            $table->unique(['loan_id', 'kind'], 'uq_loan_reminders_loan_kind');

            $table->foreign('loan_id', 'fk_loan_reminders_loan')
                ->references('id')->on('loans')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_reminders');
    }
};
