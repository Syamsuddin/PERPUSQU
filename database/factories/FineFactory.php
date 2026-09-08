<?php

namespace Database\Factories;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fine>
 */
class FineFactory extends Factory
{
    protected $model = Fine::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'member_id' => Member::factory(),
            'fine_type' => 'overdue',
            'amount' => 5000,
            'late_days' => 5,
            'status' => 'outstanding',
            'notes' => 'Keterlambatan 5 hari',
        ];
    }

    /**
     * Denda milik anggota tertentu. Pinjaman induknya dibuat berstatus
     * `returned`, karena denda keterlambatan lahir saat pengembalian — kalau
     * dibiarkan aktif, denda ini diam-diam ikut menambah kuota pinjam anggota
     * dan mengaburkan test kelayakan.
     */
    public function forMember(Member $member): static
    {
        return $this->state(fn () => [
            'member_id' => $member->id,
            'loan_id' => Loan::factory()->returned()->state(['member_id' => $member->id]),
        ]);
    }

    public function outstanding(): static
    {
        return $this->state(fn () => ['status' => 'outstanding']);
    }

    public function settled(): static
    {
        return $this->state(fn () => ['status' => 'settled']);
    }

    public function waived(): static
    {
        return $this->state(fn () => ['status' => 'waived']);
    }
}
