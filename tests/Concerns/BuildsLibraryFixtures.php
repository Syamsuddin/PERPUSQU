<?php

namespace Tests\Concerns;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Member\Models\Member;

/**
 * Fixture tingkat domain. Alur sirkulasi PERPUSQU selalu butuh trio
 * record → item → member yang konsisten; helper ini menjaga agar test
 * bicara soal aturan bisnis, bukan soal merangkai enam factory.
 */
trait BuildsLibraryFixtures
{
    /**
     * Item fisik siap pinjam beserta record induknya yang sudah terbit.
     */
    protected function availableItem(array $attributes = []): PhysicalItem
    {
        return PhysicalItem::factory()
            ->available()
            ->for(BibliographicRecord::factory()->published(), 'bibliographicRecord')
            ->create($attributes);
    }

    /**
     * Anggota yang lolos seluruh syarat peminjaman: aktif, tidak diblokir,
     * tanpa pinjaman aktif, tanpa denda outstanding.
     */
    protected function eligibleMember(array $attributes = []): Member
    {
        return Member::factory()->create($attributes);
    }

    /**
     * Pinjaman aktif yang konsisten: item berstatus `loaned` dan terikat ke
     * anggota yang sama. Dipakai sebagai titik awal uji kembali/perpanjang.
     */
    protected function activeLoan(?Member $member = null, ?PhysicalItem $item = null, array $attributes = []): Loan
    {
        $member ??= $this->eligibleMember();
        $item ??= $this->availableItem();
        $item->update(['item_status' => 'loaned']);

        return Loan::factory()->create(array_merge([
            'member_id' => $member->id,
            'physical_item_id' => $item->id,
        ], $attributes));
    }

    /**
     * Pinjaman aktif yang sudah lewat jatuh tempo sekian hari.
     */
    protected function overdueLoan(int $lateDays = 5, ?Member $member = null): Loan
    {
        return $this->activeLoan($member, null, [
            'loan_date' => now()->subDays(14 + $lateDays),
            'due_date' => now()->subDays($lateDays),
        ]);
    }

    /**
     * Payload lengkap halaman Aturan Operasional. Formulir mengirim seluruh
     * field sekaligus, jadi payload uji pun harus lengkap; hanya yang sedang
     * diuji yang ditimpa.
     */
    protected function operationalRulePayload(array $overrides = []): array
    {
        return array_merge([
            'loan_default_days' => 14,
            'loan_days_student' => 14,
            'loan_days_lecturer' => 30,
            'loan_days_staff' => 14,
            'loan_days_alumni' => 7,
            'loan_days_guest' => 7,
            'loan_renewal_days' => 7,
            'loan_max_active_loans' => 5,
            'loan_max_renewal_count' => 2,
            'fine_daily_amount' => 1000,
            'allow_renewal' => 1,
            'require_active_member' => 1,
            'require_unblocked_member' => 1,
            'asset_max_upload_size_mb' => 50,
            'ocr_enabled' => 0,
            'public_preview_enabled' => 1,
            'app_name' => 'GIBTHA LIBRARY',
            'maintenance_mode' => 0,
        ], $overrides);
    }
}
