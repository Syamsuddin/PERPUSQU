<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyimpan nilai uang sebagai rupiah bulat, bukan desimal.
 *
 * Rupiah tidak punya satuan pecahan yang dipakai — sen sudah lama tidak
 * beredar, dan tarif denda perpustakaan selalu bilangan bulat. Menyimpannya
 * sebagai DECIMAL(12,2) berarti setiap nilai melewati konversi float di PHP,
 * dan `sum()` atas kolom desimal mengembalikan string yang mudah tercampur
 * dengan float saat dijumlahkan lagi di laporan.
 *
 * Konversi ini aman karena seluruh nilai yang ada memang bulat: tarifnya
 * dikalikan jumlah hari, keduanya bilangan bulat. Pemeriksaan di bawah menolak
 * migrasi bila ternyata ada pecahan, alih-alih membulatkannya diam-diam.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->assertNoFractionalAmounts();

        Schema::table('fines', function (Blueprint $table) {
            $table->unsignedBigInteger('amount')->default(0)->change();
        });

        Schema::table('return_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('fine_amount')->default(0)->change();
        });
    }

    /**
     * Membulatkan uang tanpa sepengetahuan siapa pun adalah cara kehilangan
     * kepercayaan pada angka. Bila ada pecahan, migrasi berhenti dan meminta
     * keputusan manusia.
     */
    private function assertNoFractionalAmounts(): void
    {
        $checks = [
            ['fines', 'amount'],
            ['return_transactions', 'fine_amount'],
        ];

        foreach ($checks as [$table, $column]) {
            $fractional = DB::table($table)
                ->whereRaw("{$column} <> ROUND({$column})")
                ->count();

            if ($fractional > 0) {
                throw new RuntimeException(
                    "Migrasi dihentikan: {$fractional} baris pada {$table}.{$column} memiliki nilai pecahan. "
                    .'Tentukan pembulatannya secara sadar sebelum kolom ini diubah menjadi bilangan bulat.'
                );
            }
        }
    }

    public function down(): void
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->default(0.00)->change();
        });

        Schema::table('return_transactions', function (Blueprint $table) {
            $table->decimal('fine_amount', 12, 2)->default(0.00)->change();
        });
    }
};
