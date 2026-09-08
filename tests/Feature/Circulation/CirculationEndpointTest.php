<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Lapisan HTTP sirkulasi: validasi form, pesan flash, dan penjagaan izin —
 * termasuk perilaku saat aturan bisnis menolak (harus kembali dengan pesan,
 * bukan melempar error 500 ke petugas).
 */
class CirculationEndpointTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_clerk_can_record_a_loan_through_the_form(): void
    {
        $this->actingAsUserWith(['circulation.process_loan', 'circulation.view_active_loans']);
        $member = $this->eligibleMember();
        $item = $this->availableItem();

        $response = $this->post(route('admin.circulation.loans.store'), [
            'member_id' => $member->id,
            'barcode' => $item->barcode,
            'notes' => 'Loket 1',
        ]);

        $response->assertRedirect(route('admin.circulation.loans.active'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'physical_item_id' => $item->id,
            'loan_status' => 'active',
        ]);
    }

    /**
     * Penolakan aturan bisnis harus mendarat sebagai pesan flash yang bisa
     * dibaca petugas, bukan sebagai exception yang tidak tertangani.
     */
    #[Test]
    public function a_rejected_loan_returns_the_reason_as_a_flash_message(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember(['is_blocked' => true, 'blocked_reason' => 'Denda menumpuk']);
        $item = $this->availableItem();

        $response = $this->from(route('admin.circulation.loans.create'))->post(route('admin.circulation.loans.store'), [
            'member_id' => $member->id,
            'barcode' => $item->barcode,
        ]);

        $response->assertRedirect(route('admin.circulation.loans.create'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('loans', 0);
    }

    #[Test]
    public function the_loan_form_rejects_a_missing_member_and_barcode(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);

        $response = $this->post(route('admin.circulation.loans.store'), []);

        $response->assertSessionHasErrors(['member_id', 'barcode']);
        $this->assertDatabaseCount('loans', 0);
    }

    #[Test]
    public function the_loan_form_rejects_a_member_that_does_not_exist(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);

        $response = $this->post(route('admin.circulation.loans.store'), [
            'member_id' => 999999,
            'barcode' => 'BC0000000001',
        ]);

        $response->assertSessionHasErrors('member_id');
    }

    #[Test]
    public function a_clerk_can_record_a_return_through_the_form(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->activeLoan();

        $response = $this->post(route('admin.circulation.returns.store'), [
            'barcode' => $loan->physicalItem->barcode,
        ]);

        $response->assertSessionHas('success');
        $this->assertSame('returned', $loan->fresh()->loan_status);
    }

    #[Test]
    public function returning_an_item_that_is_not_on_loan_flashes_an_error(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $item = $this->availableItem();

        $response = $this->from(route('admin.circulation.returns.create'))
            ->post(route('admin.circulation.returns.store'), ['barcode' => $item->barcode]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('return_transactions', 0);
    }

    #[Test]
    public function a_clerk_can_renew_a_loan_through_the_endpoint(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal', 'circulation.view_active_loans']);
        $loan = $this->activeLoan(null, null, ['due_date' => now()->addDays(4)]);

        $response = $this->post(route('admin.circulation.loans.renew', $loan));

        $response->assertRedirect(route('admin.circulation.loans.show', $loan));
        $response->assertSessionHas('success');
        $this->assertSame(1, $loan->renewals()->count());
    }

    #[Test]
    public function renewing_an_overdue_loan_flashes_an_error_and_changes_nothing(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal', 'circulation.view_active_loans']);
        $loan = $this->overdueLoan(3);
        $originalDueDate = $loan->due_date->toDateTimeString();

        $response = $this->from(route('admin.circulation.loans.show', $loan))
            ->post(route('admin.circulation.loans.renew', $loan));

        $response->assertSessionHas('error');
        $this->assertSame($originalDueDate, $loan->fresh()->due_date->toDateTimeString());
        $this->assertDatabaseCount('loan_renewals', 0);
    }

    #[Test]
    public function a_cashier_can_settle_and_waive_fines(): void
    {
        $this->actingAsUserWith(['circulation.view_fines']);
        $toSettle = Fine::factory()->outstanding()->create();
        $toWaive = Fine::factory()->outstanding()->create();

        $this->post(route('admin.circulation.fines.settle', $toSettle))->assertSessionHas('success');
        $this->post(route('admin.circulation.fines.waive', $toWaive))->assertSessionHas('success');

        $this->assertSame('settled', $toSettle->fresh()->status);
        $this->assertSame('waived', $toWaive->fresh()->status);
    }

    #[Test]
    public function settling_an_already_settled_fine_flashes_an_error(): void
    {
        $this->actingAsUserWith(['circulation.view_fines']);
        $fine = Fine::factory()->settled()->create();

        $this->from(route('admin.circulation.fines.index'))
            ->post(route('admin.circulation.fines.settle', $fine))
            ->assertSessionHas('error');

        $this->assertSame('settled', $fine->fresh()->status);
    }

    #[Test]
    public function the_circulation_screens_render_for_an_authorised_clerk(): void
    {
        $this->actingAsUserWith([
            'circulation.process_loan', 'circulation.process_return',
            'circulation.view_active_loans', 'circulation.view_history', 'circulation.view_fines',
        ]);
        $loan = $this->activeLoan();
        Fine::factory()->outstanding()->create();

        $this->get(route('admin.circulation.loans.create'))->assertOk();
        $this->get(route('admin.circulation.loans.active'))->assertOk();
        $this->get(route('admin.circulation.loans.history'))->assertOk();
        $this->get(route('admin.circulation.loans.show', $loan))->assertOk();
        $this->get(route('admin.circulation.returns.create'))->assertOk();
        $this->get(route('admin.circulation.fines.index'))->assertOk();
    }

    #[Test]
    public function the_active_loan_list_shows_the_borrower_and_hides_returned_loans(): void
    {
        $this->actingAsUserWith(['circulation.view_active_loans']);
        $active = $this->activeLoan($this->eligibleMember(['name' => 'Nadia Kurnia']));
        $returned = $this->activeLoan($this->eligibleMember(['name' => 'Bayu Saputra']));
        $returned->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $response = $this->get(route('admin.circulation.loans.active'));

        $response->assertOk();
        $response->assertSee('Nadia Kurnia');
        $response->assertDontSee('Bayu Saputra');
        $this->assertSame(1, Loan::active()->count());
        $this->assertNotNull($active->id);
    }

    #[Test]
    public function a_clerk_without_the_loan_permission_cannot_reach_the_loan_form(): void
    {
        $this->actingAsUserWith(['circulation.view_active_loans']);

        $this->get(route('admin.circulation.loans.create'))->assertForbidden();
        $this->post(route('admin.circulation.loans.store'), [
            'member_id' => $this->eligibleMember()->id,
            'barcode' => $this->availableItem()->barcode,
        ])->assertForbidden();

        $this->assertDatabaseCount('loans', 0);
    }

    #[Test]
    public function a_guest_is_redirected_to_the_login_page(): void
    {
        $this->get(route('admin.circulation.loans.active'))->assertRedirect(route('auth.login'));
    }
}
