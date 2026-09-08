<?php

namespace Tests\Feature\Member;

use App\Modules\Circulation\Models\Fine;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'member_number' => 'AGT-2026-0001',
            'member_type' => 'student',
            'name' => 'Zulfa Amaliyah',
            'identity_number' => '2026010101',
            'email' => 'zulfa@example.test',
        ], $overrides);
    }

    #[Test]
    public function a_new_member_starts_active_and_unblocked(): void
    {
        $this->actingAsUserWith(['members.create', 'members.view']);

        $this->post(route('admin.members.store'), $this->validPayload())
            ->assertRedirect(route('admin.members.index'))
            ->assertSessionHas('success');

        $member = Member::firstWhere('member_number', 'AGT-2026-0001');
        $this->assertTrue($member->is_active);
        $this->assertFalse($member->is_blocked);
    }

    /**
     * `is_blocked` selalu dipaksa false saat pendaftaran, walaupun formulir
     * mengirimnya — anggota baru tidak boleh lahir dalam keadaan terblokir.
     */
    #[Test]
    public function a_new_member_cannot_be_created_already_blocked(): void
    {
        $this->actingAsUserWith(['members.create', 'members.view']);

        $this->post(route('admin.members.store'), $this->validPayload(['is_blocked' => true]));

        $this->assertFalse(Member::firstWhere('member_number', 'AGT-2026-0001')->is_blocked);
    }

    #[Test]
    public function the_member_number_must_be_unique(): void
    {
        $this->actingAsUserWith(['members.create']);
        Member::factory()->create(['member_number' => 'AGT-KEMBAR']);

        $this->post(route('admin.members.store'), $this->validPayload(['member_number' => 'AGT-KEMBAR']))
            ->assertSessionHasErrors('member_number');
    }

    #[Test]
    public function the_identity_number_must_be_unique(): void
    {
        $this->actingAsUserWith(['members.create']);
        Member::factory()->create(['identity_number' => '999999']);

        $this->post(route('admin.members.store'), $this->validPayload(['identity_number' => '999999']))
            ->assertSessionHasErrors('identity_number');
    }

    #[Test]
    #[DataProvider('invalidPayloads')]
    public function the_form_rejects_invalid_input(array $payload, string $expectedField): void
    {
        $this->actingAsUserWith(['members.create']);

        $this->post(route('admin.members.store'), $payload)->assertSessionHasErrors($expectedField);
    }

    public static function invalidPayloads(): array
    {
        return [
            'tanpa nomor anggota' => [['member_type' => 'student', 'name' => 'Ahmad Fauzi'], 'member_number'],
            'jenis anggota tidak dikenal' => [['member_number' => 'A1', 'member_type' => 'robot', 'name' => 'Ahmad Fauzi'], 'member_type'],
            'nama terlalu pendek' => [['member_number' => 'AGT-1', 'member_type' => 'student', 'name' => 'Ab'], 'name'],
            'email tidak valid' => [['member_number' => 'AGT-1', 'member_type' => 'student', 'name' => 'Ahmad Fauzi', 'email' => 'bukan-email'], 'email'],
            'fakultas tidak ada' => [['member_number' => 'AGT-1', 'member_type' => 'student', 'name' => 'Ahmad Fauzi', 'faculty_id' => 999999], 'faculty_id'],
        ];
    }

    #[Test]
    public function a_member_can_be_linked_to_a_faculty_and_study_program(): void
    {
        $this->actingAsUserWith(['members.create', 'members.view']);
        $faculty = Faculty::factory()->create();

        $this->post(route('admin.members.store'), $this->validPayload(['faculty_id' => $faculty->id]));

        $this->assertDatabaseHas('members', [
            'member_number' => 'AGT-2026-0001',
            'faculty_id' => $faculty->id,
        ]);
    }

    #[Test]
    public function a_member_can_be_deactivated_and_reactivated(): void
    {
        $this->actingAsUserWith(['members.update', 'members.view']);
        $member = Member::factory()->create();

        $this->post(route('admin.members.deactivate', $member))->assertSessionHas('success');
        $this->assertFalse($member->fresh()->is_active);

        $this->post(route('admin.members.activate', $member))->assertSessionHas('success');
        $this->assertTrue($member->fresh()->is_active);
    }

    /**
     * Menonaktifkan anggota yang masih memegang buku akan memutus jejak
     * pertanggungjawaban, jadi ditolak selama pinjaman aktif masih ada.
     */
    #[Test]
    public function a_member_with_an_active_loan_cannot_be_deactivated(): void
    {
        $this->actingAsUserWith(['members.update', 'members.view']);
        $member = $this->eligibleMember();
        $this->activeLoan($member);

        $this->from(route('admin.members.show', $member))
            ->post(route('admin.members.deactivate', $member))
            ->assertSessionHas('error');

        $this->assertTrue($member->fresh()->is_active);
    }

    #[Test]
    public function a_member_with_an_active_loan_cannot_be_deleted(): void
    {
        $this->actingAsUserWith(['members.delete', 'members.view']);
        $member = $this->eligibleMember();
        $this->activeLoan($member);

        $this->from(route('admin.members.show', $member))
            ->delete(route('admin.members.destroy', $member))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('members', ['id' => $member->id]);
    }

    #[Test]
    public function a_member_without_active_loans_is_soft_deleted(): void
    {
        $this->actingAsUserWith(['members.delete', 'members.view']);
        $member = Member::factory()->create();

        $this->delete(route('admin.members.destroy', $member))->assertSessionHas('success');

        $this->assertSoftDeleted('members', ['id' => $member->id]);
        $this->assertNull(Member::find($member->id));
    }

    /**
     * Anggota yang pernah meminjam ditahan foreign key dari tabel `loans` dan
     * `fines`. Penghapusan lunak membuatnya tetap bisa dihapus dari daftar
     * aktif tanpa memutus riwayat — denda yang belum lunas tetap menyebut
     * pemiliknya.
     */
    #[Test]
    public function deleting_a_member_keeps_their_loan_and_fine_history_readable(): void
    {
        $this->actingAsUserWith(['members.delete', 'members.view']);
        $member = $this->eligibleMember(['name' => 'Hasan Basri']);
        $loan = $this->activeLoan($member);
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);
        $fine = Fine::factory()->create([
            'member_id' => $member->id,
            'loan_id' => $loan->id,
        ]);

        $this->delete(route('admin.members.destroy', $member))->assertSessionHas('success');

        $this->assertSoftDeleted('members', ['id' => $member->id]);
        $this->assertSame('Hasan Basri', $loan->fresh()->member?->name);
        $this->assertSame('Hasan Basri', $fine->fresh()->member?->name);
    }

    #[Test]
    public function a_soft_deleted_member_can_be_restored(): void
    {
        $this->actingAsUserWith(['members.delete', 'members.view']);
        $member = Member::factory()->create(['member_number' => 'AGT-PULIH-1']);
        $this->delete(route('admin.members.destroy', $member));

        Member::withTrashed()->find($member->id)->restore();

        $this->assertSame('AGT-PULIH-1', Member::find($member->id)?->member_number);
    }

    #[Test]
    public function the_member_screens_render_for_an_authorised_user(): void
    {
        $this->actingAsUserWith(['members.view', 'members.create', 'members.update']);
        $member = Member::factory()->create(['name' => 'Rania Salsabila']);

        $this->get(route('admin.members.index'))->assertOk();
        $this->get(route('admin.members.create'))->assertOk();
        $this->get(route('admin.members.show', $member))->assertOk()->assertSee('Rania Salsabila');
        $this->get(route('admin.members.edit', $member))->assertOk();
        $this->get(route('admin.members.history', $member))->assertOk();
    }

    #[Test]
    public function the_index_can_be_searched_by_name_and_member_number(): void
    {
        $this->actingAsUserWith(['members.view']);
        Member::factory()->create(['name' => 'Ilham Ramadhan', 'member_number' => 'AGT-A-1']);
        Member::factory()->create(['name' => 'Salma Nabila', 'member_number' => 'AGT-B-2']);

        $this->get(route('admin.members.index', ['keyword' => 'Ilham']))
            ->assertOk()->assertSee('Ilham Ramadhan')->assertDontSee('Salma Nabila');

        $this->get(route('admin.members.index', ['keyword' => 'AGT-B-2']))
            ->assertOk()->assertSee('Salma Nabila')->assertDontSee('Ilham Ramadhan');
    }

    #[Test]
    public function a_viewer_cannot_create_update_or_delete_members(): void
    {
        $this->actingAsUserWith(['members.view']);
        $member = Member::factory()->create();

        $this->get(route('admin.members.create'))->assertForbidden();
        $this->post(route('admin.members.store'), $this->validPayload())->assertForbidden();
        $this->put(route('admin.members.update', $member), $this->validPayload())->assertForbidden();
        $this->delete(route('admin.members.destroy', $member))->assertForbidden();
        $this->post(route('admin.members.activate', $member))->assertForbidden();
    }
}
