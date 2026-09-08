<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\LoanReminder;
use App\Modules\Circulation\Notifications\LoanDueReminder;
use App\Modules\Circulation\Services\LoanReminderService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pengingat jatuh tempo. Sampai sekarang perpustakaan ini tidak punya satu pun
 * pekerjaan terjadwal, sehingga anggota baru mengetahui keterlambatannya saat
 * ditagih di meja sirkulasi.
 */
class LoanReminderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-05-10 07:00:00');
        Notification::fake();
    }

    private function service(): LoanReminderService
    {
        return app(LoanReminderService::class);
    }

    public static function reminderWindows(): array
    {
        return [
            'tiga hari lagi' => ['2026-05-13 10:00:00', LoanReminder::KIND_DUE_SOON],
            'besok' => ['2026-05-11 10:00:00', LoanReminder::KIND_DUE_SOON],
            'hari ini' => ['2026-05-10 16:00:00', LoanReminder::KIND_DUE_TODAY],
            'kemarin' => ['2026-05-09 10:00:00', LoanReminder::KIND_OVERDUE],
            'seminggu lalu' => ['2026-05-03 10:00:00', LoanReminder::KIND_OVERDUE],
        ];
    }

    #[Test]
    #[DataProvider('reminderWindows')]
    public function it_picks_the_reminder_that_matches_the_due_date(string $dueDate, string $expectedKind): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test']);
        $loan = $this->activeLoan($member, null, ['due_date' => $dueDate]);

        $this->service()->sendDueReminders();

        $this->assertDatabaseHas('loan_reminders', ['loan_id' => $loan->id, 'kind' => $expectedKind]);
        Notification::assertSentTo($member, LoanDueReminder::class,
            fn (LoanDueReminder $n) => $n->kind === $expectedKind);
    }

    /**
     * Jatuh tempo yang masih jauh belum perlu diingatkan — surat terlalu dini
     * sama tidak bergunanya dengan surat yang terlambat.
     */
    #[Test]
    public function a_due_date_beyond_the_window_is_left_alone(): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test']);
        $this->activeLoan($member, null, ['due_date' => '2026-05-20 10:00:00']);

        $this->service()->sendDueReminders();

        $this->assertDatabaseCount('loan_reminders', 0);
        Notification::assertNothingSent();
    }

    #[Test]
    public function a_returned_loan_is_never_reminded(): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test']);
        $loan = $this->activeLoan($member, null, ['due_date' => '2026-05-01 10:00:00']);
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $this->service()->sendDueReminders();

        $this->assertDatabaseCount('loan_reminders', 0);
        Notification::assertNothingSent();
    }

    /**
     * Inti dari tabel loan_reminders: menjalankan perintah setiap hari tidak
     * boleh mengirim surat yang sama berulang-ulang.
     */
    #[Test]
    public function running_the_reminder_twice_does_not_send_twice(): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test']);
        $this->activeLoan($member, null, ['due_date' => '2026-05-12 10:00:00']);

        $this->service()->sendDueReminders();
        $this->service()->sendDueReminders();

        $this->assertDatabaseCount('loan_reminders', 1);
        Notification::assertSentToTimes($member, LoanDueReminder::class, 1);
    }

    /**
     * Sebuah pinjaman melewati beberapa tahap, dan tiap tahap layak diingatkan
     * satu kali. Yang tidak boleh adalah mengulang tahap yang sama.
     */
    #[Test]
    public function each_stage_of_the_same_loan_earns_its_own_reminder(): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test']);
        $loan = $this->activeLoan($member, null, ['due_date' => '2026-05-12 10:00:00']);

        $this->service()->sendDueReminders();

        Carbon::setTestNow('2026-05-12 07:00:00');
        $this->service()->sendDueReminders();

        Carbon::setTestNow('2026-05-15 07:00:00');
        $this->service()->sendDueReminders();

        $this->assertSame(
            [LoanReminder::KIND_DUE_SOON, LoanReminder::KIND_DUE_TODAY, LoanReminder::KIND_OVERDUE],
            $loan->reminders()->orderBy('id')->pluck('kind')->all()
        );
        Notification::assertSentToTimes($member, LoanDueReminder::class, 3);
    }

    /**
     * Banyak anggota dilayani sepenuhnya di meja sirkulasi dan tidak punya
     * email. Itu keadaan biasa, bukan kesalahan.
     */
    #[Test]
    public function a_member_without_an_email_is_skipped_not_failed(): void
    {
        $member = $this->eligibleMember(['email' => null]);
        $this->activeLoan($member, null, ['due_date' => '2026-05-12 10:00:00']);

        $sent = $this->service()->sendDueReminders();

        $this->assertSame(1, $sent['skipped_no_email']);
        $this->assertDatabaseCount('loan_reminders', 0);
        Notification::assertNothingSent();
    }

    #[Test]
    public function the_reminder_names_the_borrowed_title_and_the_due_date(): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test', 'name' => 'Nadia']);
        $loan = $this->activeLoan($member, null, ['due_date' => '2026-05-12 10:00:00']);
        $loan->physicalItem->bibliographicRecord->update(['title' => 'Fikih Muamalah']);

        $this->service()->sendDueReminders();

        // Tanggal dibandingkan dengan format yang dipakai aplikasi itu sendiri;
        // menuliskannya sebagai teks tetap akan pecah begitu locale berubah.
        $expectedDate = $loan->due_date->translatedFormat('d F Y');

        Notification::assertSentTo($member, LoanDueReminder::class, function (LoanDueReminder $n) use ($member, $expectedDate) {
            $body = implode(' ', $n->toMail($member)->introLines);

            return str_contains($body, 'Fikih Muamalah') && str_contains($body, $expectedDate);
        });
    }

    #[Test]
    public function the_command_reports_what_it_sent(): void
    {
        $member = $this->eligibleMember(['email' => 'anggota@perpusqu.test']);
        $this->activeLoan($member, null, ['due_date' => '2026-05-12 10:00:00']);

        $this->artisan('library:send-loan-reminders')
            ->expectsOutputToContain('Akan jatuh tempo')
            ->assertSuccessful();

        $this->assertDatabaseCount('loan_reminders', 1);
    }

    /**
     * Indeks unik menegakkan aturannya di basis data, bukan hanya di kode —
     * dua proses terjadwal yang berjalan bersamaan tidak dapat menembusnya.
     */
    #[Test]
    public function the_database_forbids_two_reminders_of_the_same_kind(): void
    {
        $loan = $this->activeLoan();
        LoanReminder::factory()->create(['loan_id' => $loan->id, 'kind' => LoanReminder::KIND_OVERDUE]);

        $this->expectException(QueryException::class);

        LoanReminder::factory()->create(['loan_id' => $loan->id, 'kind' => LoanReminder::KIND_OVERDUE]);
    }
}
