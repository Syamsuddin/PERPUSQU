<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\LoanReminder;
use App\Modules\Circulation\Notifications\LoanDueReminder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Menyusun dan mengirim pengingat jatuh tempo.
 *
 * Aturan yang dijaga di sini:
 *
 *  - Setiap jenis pengingat dikirim paling banyak SEKALI per pinjaman. Tanpa
 *    itu, perintah harian akan mengirim surat yang sama setiap hari — cara
 *    tercepat membuat orang berhenti membacanya. Penegakannya ada di indeks
 *    unik `uq_loan_reminders_loan_kind`, bukan hanya di kode ini.
 *  - Pengingat hanya untuk pinjaman yang masih aktif. Yang sudah dikembalikan
 *    tidak perlu diingatkan.
 *  - Anggota tanpa alamat email dilewati dengan catatan, bukan dengan error:
 *    banyak anggota memang dilayani sepenuhnya di meja sirkulasi.
 */
class LoanReminderService
{
    /** Berapa hari sebelum jatuh tempo pengingat awal dikirim. */
    public const DAYS_BEFORE_DUE = 3;

    /**
     * @return array<string, int> jumlah terkirim per jenis, plus yang dilewati
     */
    public function sendDueReminders(): array
    {
        $sent = [
            LoanReminder::KIND_DUE_SOON => 0,
            LoanReminder::KIND_DUE_TODAY => 0,
            LoanReminder::KIND_OVERDUE => 0,
            'skipped_no_email' => 0,
        ];

        foreach (LoanReminder::KINDS as $kind) {
            foreach ($this->loansNeeding($kind)->cursor() as $loan) {
                if ($this->send($loan, $kind)) {
                    $sent[$kind]++;
                } else {
                    $sent['skipped_no_email']++;
                }
            }
        }

        return $sent;
    }

    /**
     * Pinjaman aktif yang memenuhi syarat satu jenis pengingat dan belum
     * pernah menerimanya.
     */
    protected function loansNeeding(string $kind): Builder
    {
        $today = now()->startOfDay();

        $query = Loan::query()
            ->with(['member', 'physicalItem.bibliographicRecord'])
            ->where('loan_status', 'active')
            ->whereDoesntHave('reminders', fn ($q) => $q->where('kind', $kind));

        return match ($kind) {
            LoanReminder::KIND_DUE_SOON => $query
                ->whereBetween('due_date', [
                    $today->copy()->addDay()->startOfDay(),
                    $today->copy()->addDays(self::DAYS_BEFORE_DUE)->endOfDay(),
                ]),
            LoanReminder::KIND_DUE_TODAY => $query->whereBetween('due_date', [
                $today->copy()->startOfDay(),
                $today->copy()->endOfDay(),
            ]),
            default => $query->where('due_date', '<', $today),
        };
    }

    /**
     * @return bool true bila pengingat benar-benar dikirim
     */
    protected function send(Loan $loan, string $kind): bool
    {
        $member = $loan->member;

        if (! $member?->email) {
            Log::info('Pengingat jatuh tempo dilewati: anggota tidak punya email', [
                'loan_id' => $loan->id,
                'member_id' => $member?->id,
                'kind' => $kind,
            ]);

            return false;
        }

        // Catatan ditulis lebih dulu: bila pengiriman gagal, pengingat tidak
        // akan terkirim dua kali pada jadwal berikutnya. Surat yang hilang
        // lebih ringan akibatnya daripada surat yang datang berulang-ulang.
        LoanReminder::create([
            'loan_id' => $loan->id,
            'kind' => $kind,
            'channel' => 'mail',
            'sent_at' => now(),
        ]);

        $member->notify(new LoanDueReminder($loan, $kind));

        return true;
    }
}
