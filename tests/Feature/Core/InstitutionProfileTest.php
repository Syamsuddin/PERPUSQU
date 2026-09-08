<?php

namespace Tests\Feature\Core;

use App\Modules\Core\Models\InstitutionProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstitutionProfileTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'institution_name' => 'Institut Agama Islam Gibtha',
            'library_name' => 'Perpustakaan Gibtha',
            'email' => 'perpustakaan@gibtha.test',
            'website' => 'https://perpustakaan.gibtha.test',
        ], $overrides);
    }

    #[Test]
    public function the_page_renders_before_any_profile_exists(): void
    {
        $this->actingAsUserWith(['core.view_institution_profile']);

        $this->get(route('admin.settings.institution_profile.edit'))
            ->assertOk()
            ->assertViewHas('profile', null);
    }

    /**
     * Instalasi baru belum punya baris profil sama sekali; penyimpanan pertama
     * harus membuatnya, bukan gagal karena tidak ada yang bisa diperbarui.
     */
    #[Test]
    public function the_first_save_creates_the_profile(): void
    {
        $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);
        $this->assertDatabaseCount('institution_profiles', 0);

        $this->from(route('admin.settings.institution_profile.edit'))
            ->put(route('admin.settings.institution_profile.update'), $this->validPayload())
            ->assertSessionHas('success');

        $this->assertDatabaseHas('institution_profiles', [
            'institution_name' => 'Institut Agama Islam Gibtha',
            'library_name' => 'Perpustakaan Gibtha',
        ]);
        $this->assertDatabaseCount('institution_profiles', 1);
    }

    #[Test]
    public function saving_again_updates_the_same_row_instead_of_adding_another(): void
    {
        $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);
        InstitutionProfile::factory()->create(['library_name' => 'Nama Lama']);

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload());

        $this->assertDatabaseCount('institution_profiles', 1);
        $this->assertSame('Perpustakaan Gibtha', InstitutionProfile::first()->library_name);
    }

    #[Test]
    #[DataProvider('invalidPayloads')]
    public function it_rejects_invalid_input(array $overrides, string $expectedField): void
    {
        $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload($overrides))
            ->assertSessionHasErrors($expectedField);

        $this->assertDatabaseCount('institution_profiles', 0);
    }

    public static function invalidPayloads(): array
    {
        return [
            'tanpa nama institusi' => [['institution_name' => ''], 'institution_name'],
            'tanpa nama perpustakaan' => [['library_name' => ''], 'library_name'],
            'nama institusi terlalu pendek' => [['institution_name' => 'AB'], 'institution_name'],
            'email tidak valid' => [['email' => 'bukan-email'], 'email'],
            'website bukan url' => [['website' => 'perpustakaan-tanpa-skema'], 'website'],
        ];
    }

    #[Test]
    public function a_logo_upload_is_stored_and_referenced_by_the_profile(): void
    {
        Storage::fake('public');
        $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload([
            'logo' => UploadedFile::fake()->image('logo.png', 200, 200),
        ]))->assertSessionHas('success');

        $profile = InstitutionProfile::first();
        $this->assertNotNull($profile->logo_path);
        Storage::disk('public')->assertExists($profile->logo_path);
    }

    #[Test]
    public function a_non_image_logo_is_refused(): void
    {
        Storage::fake('public');
        $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload([
            'logo' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]))->assertSessionHasErrors('logo');
    }

    /**
     * Logo lama harus dibuang setelah diganti, agar disk tidak menumpuk berkas
     * yang tidak lagi dirujuk siapa pun.
     */
    #[Test]
    public function replacing_the_logo_removes_the_previous_file(): void
    {
        Storage::fake('public');
        $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload([
            'logo' => UploadedFile::fake()->image('logo-lama.png'),
        ]));
        $oldPath = InstitutionProfile::first()->logo_path;

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload([
            'logo' => UploadedFile::fake()->image('logo-baru.png'),
        ]));

        $newPath = InstitutionProfile::first()->logo_path;
        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    #[Test]
    public function updating_the_profile_is_written_to_the_audit_log(): void
    {
        $admin = $this->actingAsUserWith(['core.view_institution_profile', 'core.update_institution_profile']);

        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload());

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'core',
            'description' => 'Profil institusi diperbarui',
            'causer_id' => $admin->id,
        ]);
    }

    #[Test]
    public function viewing_and_updating_need_separate_permissions(): void
    {
        $this->actingAsUserWith(['core.view_institution_profile']);

        $this->get(route('admin.settings.institution_profile.edit'))->assertOk();
        $this->put(route('admin.settings.institution_profile.update'), $this->validPayload())->assertForbidden();

        $this->assertDatabaseCount('institution_profiles', 0);
    }

    /**
     * Nama perpustakaan tampil di halaman publik, jadi perubahannya harus
     * langsung terlihat oleh pengunjung OPAC.
     */
    #[Test]
    public function the_library_name_reaches_the_public_landing_page(): void
    {
        InstitutionProfile::factory()->create(['library_name' => 'Perpustakaan Gibtha Jaya']);

        $this->get('/')->assertOk()->assertSee('Perpustakaan Gibtha Jaya');
    }
}
