<?php

namespace App\Modules\Circulation\Notifications;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\LoanReminder;
use App\Modules\Core\Services\SystemSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Pengingat jatuh tempo peminjaman.
 *
 * Diantrekan (ShouldQueue) supaya perintah harian selesai cepat walau ada
 * ratusan pengingat: satu perpustakaan dengan seribu pinjaman aktif tidak boleh
 * membuat penjadwal menggantung selama pengiriman surat berlangsung.
 */
class LoanDueReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Loan $loan,
        public readonly string $kind,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->loan->physicalItem?->bibliographicRecord?->title ?? 'Koleksi perpustakaan';
        $dueDate = $this->loan->due_date->translatedFormat('d F Y');

        $message = (new MailMessage)
            ->subject($this->subject())
            ->greeting('Assalamualaikum, '.$notifiable->name.'.');

        foreach ($this->bodyLines($title, $dueDate) as $line) {
            $message->line($line);
        }

        return $message->salutation('Terima kasih, '.app(SystemSettings::class)->appName().'.');
    }

    /**
     * @return list<string>
     */
    protected function bodyLines(string $title, string $dueDate): array
    {
        return match ($this->kind) {
            LoanReminder::KIND_DUE_SOON => [
                "Pinjaman Anda atas \"{$title}\" akan jatuh tempo pada {$dueDate}.",
                'Silakan kembalikan atau perpanjang sebelum tanggal tersebut.',
            ],
            LoanReminder::KIND_DUE_TODAY => [
                "Pinjaman Anda atas \"{$title}\" jatuh tempo hari ini, {$dueDate}.",
                'Pengembalian setelah hari ini akan dikenakan denda keterlambatan.',
            ],
            default => [
                "Pinjaman Anda atas \"{$title}\" telah melewati jatuh tempo pada {$dueDate}.",
                'Denda keterlambatan berjalan setiap hari sampai koleksi dikembalikan.',
            ],
        };
    }

    protected function subject(): string
    {
        return match ($this->kind) {
            LoanReminder::KIND_DUE_SOON => 'Pengingat: pinjaman Anda akan jatuh tempo',
            LoanReminder::KIND_DUE_TODAY => 'Pinjaman Anda jatuh tempo hari ini',
            default => 'Pinjaman Anda telah melewati jatuh tempo',
        };
    }
}
