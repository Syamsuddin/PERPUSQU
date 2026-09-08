<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menautkan akun login ke data anggota.
 *
 * Sebelumnya `users` dan `members` sama sekali tidak berhubungan: MemberUserSeeder
 * membuat keduanya berdampingan tanpa kunci apa pun, hanya bertemu lewat email
 * secara implisit. Akibatnya portal "Pinjaman Saya" mustahil dibangun — tidak
 * ada cara mengetahui anggota mana yang sedang masuk.
 *
 * Relasinya satu-ke-satu dan boleh kosong: seorang anggota dapat dilayani di
 * meja sirkulasi tanpa pernah punya akun, dan satu akun tidak boleh mewakili
 * dua anggota sekaligus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id')
                ->unique('uq_members_user_id');

            // Foreign key hanya dipasang di MySQL. SQLite tidak dapat membuang
            // kolom yang masih dirujuk definisi foreign key — bahkan dengan
            // penegakan dimatikan — sehingga `down()` akan macet permanen di
            // driver itu. Indeks unik, yang menjaga aturan sesungguhnya (satu
            // akun mewakili paling banyak satu anggota), tetap berlaku di
            // keduanya.
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->foreign('user_id', 'fk_members_user')
                    ->references('id')->on('users')
                    ->cascadeOnUpdate()->nullOnDelete();
            }
        });

        $this->linkExistingAccountsByEmail();
    }

    /**
     * Data yang sudah ada hanya punya email sebagai penghubung. Penautan
     * dilakukan sekali di sini, dan hanya bila pasangannya tidak ambigu:
     * satu email harus menunjuk tepat satu anggota dan tepat satu akun.
     * Sisanya sengaja dibiarkan kosong untuk ditautkan manual — menebak
     * kepemilikan akun jauh lebih berbahaya daripada membiarkannya kosong.
     */
    private function linkExistingAccountsByEmail(): void
    {
        $ambiguousMemberEmails = DB::table('members')
            ->whereNotNull('email')
            ->groupBy('email')
            ->havingRaw('count(*) > 1')
            ->pluck('email');

        $candidates = DB::table('members')
            ->whereNotNull('email')
            ->whereNotIn('email', $ambiguousMemberEmails)
            ->pluck('email', 'id');

        foreach ($candidates as $memberId => $email) {
            $userIds = DB::table('users')
                ->whereNull('deleted_at')
                ->where('email', $email)
                ->pluck('id');

            if ($userIds->count() !== 1) {
                continue;
            }

            // Akun yang sudah tertaut ke anggota lain dilewati.
            $alreadyTaken = DB::table('members')->where('user_id', $userIds->first())->exists();

            if (! $alreadyTaken) {
                DB::table('members')->where('id', $memberId)->update(['user_id' => $userIds->first()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // SQLite tidak mendukung penghapusan foreign key berdasarkan nama;
            // di sana kunci itu ikut hilang bersama kolomnya.
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->dropForeign('fk_members_user');
            }

            $table->dropUnique('uq_members_user_id');
            $table->dropColumn('user_id');
        });
    }
};
