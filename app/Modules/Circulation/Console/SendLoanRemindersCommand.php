<?php

namespace App\Modules\Circulation\Console;

use App\Modules\Circulation\Models\LoanReminder;
use App\Modules\Circulation\Services\LoanReminderService;
use Illuminate\Console\Command;

class SendLoanRemindersCommand extends Command
{
    protected $signature = 'library:send-loan-reminders';

    protected $description = 'Kirim pengingat jatuh tempo peminjaman kepada anggota';

    public function handle(LoanReminderService $reminders): int
    {
        $sent = $reminders->sendDueReminders();

        $this->table(
            ['Jenis pengingat', 'Terkirim'],
            [
                ['Akan jatuh tempo', $sent[LoanReminder::KIND_DUE_SOON]],
                ['Jatuh tempo hari ini', $sent[LoanReminder::KIND_DUE_TODAY]],
                ['Sudah terlambat', $sent[LoanReminder::KIND_OVERDUE]],
                ['Dilewati (tanpa email)', $sent['skipped_no_email']],
            ]
        );

        return self::SUCCESS;
    }
}
