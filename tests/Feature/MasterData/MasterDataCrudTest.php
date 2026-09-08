<?php

namespace Tests\Feature\MasterData;

use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Models\StudyProgram;
use App\Modules\MasterData\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Sepuluh entitas master data memakai pola yang sama persis: index, create,
 * store, edit, update, destroy — masing-masing dijaga izin `<entitas>.<aksi>`.
 * Menuliskannya sepuluh kali akan menghasilkan seribu baris yang nyaris kembar,
 * jadi polanya dijalankan lewat data provider. Yang khas per entitas (aturan
 * unik, relasi wajib) diuji tersendiri di bawah.
 */
class MasterDataCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * [nama route, prefiks izin, kelas model, payload sah, kolom penanda]
     */
    public static function entities(): array
    {
        return [
            'pengarang' => ['authors', 'authors', Author::class, ['name' => 'Ibnu Katsir'], 'name'],
            'penerbit' => ['publishers', 'publishers', Publisher::class, ['name' => 'Pustaka Al-Kautsar', 'city' => 'Jakarta'], 'name'],
            'bahasa' => ['languages', 'languages', Language::class, ['code' => 'ara', 'name' => 'Bahasa Arab'], 'code'],
            'klasifikasi' => ['classifications', 'classifications', Classification::class, ['code' => '297', 'name' => 'Islam'], 'code'],
            'subjek' => ['subjects', 'subjects', Subject::class, ['name' => 'Ilmu Hadis'], 'name'],
            'jenis koleksi' => ['collection-types', 'collection_types', CollectionType::class, ['code' => 'BUKU', 'name' => 'Buku Teks'], 'code'],
            'lokasi rak' => ['rack-locations', 'rack_locations', RackLocation::class, ['code' => 'RAK-A1', 'name' => 'Rak Agama'], 'code'],
            'fakultas' => ['faculties', 'faculties', Faculty::class, ['code' => 'FTI', 'name' => 'Fakultas Teknologi Informasi'], 'code'],
            'kondisi item' => ['item-conditions', 'item_conditions', ItemCondition::class, ['code' => 'BAIK', 'name' => 'Baik', 'severity_level' => 1], 'code'],
        ];
    }

    private function route(string $slug, string $action, $model = null): string
    {
        return route("admin.master-data.{$slug}.{$action}", $model ? [$model] : []);
    }

    #[Test]
    #[DataProvider('entities')]
    public function an_authorised_user_can_create_an_entry(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(["{$perm}.create", "{$perm}.view"]);

        $this->post($this->route($slug, 'store'), $payload)
            ->assertRedirect($this->route($slug, 'index'))
            ->assertSessionHas('success');

        $this->assertNotNull($modelClass::firstWhere($key, $payload[$key]));
    }

    #[Test]
    #[DataProvider('entities')]
    public function the_create_form_rejects_an_empty_submission(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(["{$perm}.create"]);

        $this->post($this->route($slug, 'store'), [])->assertSessionHasErrors();
    }

    #[Test]
    #[DataProvider('entities')]
    public function an_authorised_user_can_update_an_entry(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(["{$perm}.update", "{$perm}.view"]);
        $model = $modelClass::factory()->create();

        $this->put($this->route($slug, 'update', $model), $payload)
            ->assertRedirect($this->route($slug, 'index'))
            ->assertSessionHas('success');

        $this->assertSame($payload[$key], $model->fresh()->{$key});
    }

    #[Test]
    #[DataProvider('entities')]
    public function an_authorised_user_can_delete_an_entry(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(["{$perm}.delete", "{$perm}.view"]);
        $model = $modelClass::factory()->create();

        $this->delete($this->route($slug, 'destroy', $model))->assertSessionHas('success');

        $this->assertDatabaseMissing($model->getTable(), ['id' => $model->id]);
    }

    #[Test]
    #[DataProvider('entities')]
    public function the_screens_render_for_an_authorised_user(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(["{$perm}.view", "{$perm}.create", "{$perm}.update"]);
        $model = $modelClass::factory()->create();

        $this->get($this->route($slug, 'index'))->assertOk();
        $this->get($this->route($slug, 'create'))->assertOk();
        $this->get($this->route($slug, 'edit', $model))->assertOk();
    }

    /**
     * Izin baca tidak boleh membawa serta izin tulis. Diuji untuk setiap
     * entitas karena satu route yang lupa dipasangi middleware sudah cukup
     * untuk membuka seluruh master data.
     */
    #[Test]
    #[DataProvider('entities')]
    public function a_read_only_user_cannot_write(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(["{$perm}.view"]);
        $model = $modelClass::factory()->create();

        $this->get($this->route($slug, 'index'))->assertOk();
        $this->get($this->route($slug, 'create'))->assertForbidden();
        $this->post($this->route($slug, 'store'), $payload)->assertForbidden();
        $this->get($this->route($slug, 'edit', $model))->assertForbidden();
        $this->put($this->route($slug, 'update', $model), $payload)->assertForbidden();
        $this->delete($this->route($slug, 'destroy', $model))->assertForbidden();
    }

    #[Test]
    #[DataProvider('entities')]
    public function a_user_without_any_permission_for_the_entity_is_refused(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get($this->route($slug, 'index'))->assertForbidden();
    }

    #[Test]
    #[DataProvider('entities')]
    public function a_guest_is_sent_to_the_login_page(string $slug, string $perm, string $modelClass, array $payload, string $key): void
    {
        $this->get($this->route($slug, 'index'))->assertRedirect(route('auth.login'));
    }

    // ── Aturan khas per entitas ──────────────────────────────────────────

    #[Test]
    public function a_study_program_must_belong_to_a_faculty(): void
    {
        $this->actingAsUserWith(['study_programs.create', 'study_programs.view']);

        $this->post($this->route('study-programs', 'store'), ['code' => 'TI', 'name' => 'Teknik Informatika'])
            ->assertSessionHasErrors('faculty_id');

        $this->post($this->route('study-programs', 'store'), [
            'code' => 'TI',
            'name' => 'Teknik Informatika',
            'faculty_id' => Faculty::factory()->create()->id,
        ])->assertSessionHasNoErrors();

        $this->assertNotNull(StudyProgram::firstWhere('code', 'TI'));
    }

    #[Test]
    public function a_language_code_and_name_must_both_be_unique(): void
    {
        $this->actingAsUserWith(['languages.create']);
        Language::factory()->create(['code' => 'ind', 'name' => 'Bahasa Indonesia']);

        $this->post($this->route('languages', 'store'), ['code' => 'ind', 'name' => 'Nama Lain'])
            ->assertSessionHasErrors('code');
        $this->post($this->route('languages', 'store'), ['code' => 'lain', 'name' => 'Bahasa Indonesia'])
            ->assertSessionHasErrors('name');
    }

    /**
     * Kode entitas dipakai di URL dan laporan, jadi dibatasi `alpha_dash`.
     */
    #[Test]
    public function a_faculty_code_may_not_contain_spaces(): void
    {
        $this->actingAsUserWith(['faculties.create']);

        $this->post($this->route('faculties', 'store'), ['code' => 'F TI', 'name' => 'Fakultas Uji'])
            ->assertSessionHasErrors('code');
    }

    #[Test]
    public function a_classification_may_point_to_a_parent(): void
    {
        $this->actingAsUserWith(['classifications.create', 'classifications.view']);
        $parent = Classification::factory()->create(['code' => '200', 'name' => 'Agama']);

        $this->post($this->route('classifications', 'store'), [
            'code' => '297.1',
            'name' => 'Al-Quran',
            'parent_id' => $parent->id,
        ])->assertSessionHasNoErrors();

        $this->assertSame($parent->id, Classification::firstWhere('code', '297.1')->parent_id);
    }

    #[Test]
    public function a_classification_rejects_a_parent_that_does_not_exist(): void
    {
        $this->actingAsUserWith(['classifications.create']);

        $this->post($this->route('classifications', 'store'), [
            'code' => '297.1',
            'name' => 'Al-Quran',
            'parent_id' => 999999,
        ])->assertSessionHasErrors('parent_id');
    }

    #[Test]
    public function an_item_condition_severity_must_sit_between_one_and_ten(): void
    {
        $this->actingAsUserWith(['item_conditions.create']);

        foreach ([0, 11, 'parah'] as $severity) {
            $this->post($this->route('item-conditions', 'store'), [
                'code' => 'RUSAK',
                'name' => 'Rusak Berat',
                'severity_level' => $severity,
            ])->assertSessionHasErrors('severity_level');
        }
    }

    /**
     * Nama pengarang dinormalkan ke huruf kecil supaya pencarian dan pencegahan
     * duplikat tidak bergantung pada cara pengetikan.
     */
    #[Test]
    public function an_author_name_is_normalised_on_save(): void
    {
        $this->actingAsUserWith(['authors.create', 'authors.view']);

        $this->post($this->route('authors', 'store'), ['name' => 'M. Quraish SHIHAB']);

        $this->assertSame('m. quraish shihab', Author::firstWhere('name', 'M. Quraish SHIHAB')->normalized_name);
    }
}
