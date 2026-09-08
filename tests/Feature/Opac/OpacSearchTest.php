<?php

namespace Tests\Feature\Opac;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\Opac\Services\OpacSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Perilaku pencarian yang harus sama di kedua jalur — FULLTEXT maupun
 * cadangan substring. Berkas ini tidak peduli jalur mana yang terpakai; yang
 * dijaga adalah apa yang ditemukan pengguna, bukan bagaimana menemukannya.
 */
class OpacSearchTest extends TestCase
{
    use RefreshDatabase;

    private function service(): OpacSearchService
    {
        return app(OpacSearchService::class);
    }

    private function publish(string $title, array $attributes = []): BibliographicRecord
    {
        return BibliographicRecord::factory()->published()->withAuthor()->create(
            array_merge(['title' => $title], $attributes)
        );
    }

    #[Test]
    public function an_empty_keyword_returns_the_whole_public_catalogue(): void
    {
        $this->publish('Tafsir Al Misbah');
        $this->publish('Atlas Sejarah Islam');

        $this->assertSame(2, $this->service()->search([])->total());
        $this->assertSame(2, $this->service()->search(['keyword' => '   '])->total());
    }

    #[Test]
    public function it_finds_a_record_by_a_word_in_the_title(): void
    {
        $this->publish('Tafsir Al Misbah');
        $this->publish('Atlas Sejarah Islam');

        $results = $this->service()->search(['keyword' => 'Misbah']);

        $this->assertSame(1, $results->total());
        $this->assertSame('Tafsir Al Misbah', $results->first()->title);
    }

    /**
     * ISBN dan nama pengarang berada di luar indeks FULLTEXT. Keduanya harus
     * tetap dapat ditemukan — itulah salah satu alasan jalur cadangan ada.
     */
    #[Test]
    public function it_finds_a_record_by_its_isbn(): void
    {
        $this->publish('Ensiklopedia Hadis', ['isbn' => '9789794334455']);
        $this->publish('Buku Lain');

        $results = $this->service()->search(['keyword' => '9789794334455']);

        $this->assertSame(1, $results->total());
        $this->assertSame('Ensiklopedia Hadis', $results->first()->title);
    }

    #[Test]
    public function it_finds_a_record_by_its_author(): void
    {
        $author = Author::factory()->create(['name' => 'Quraish Shihab']);
        BibliographicRecord::factory()->published()->withAuthor($author)->create(['title' => 'Wawasan Al-Quran']);
        $this->publish('Buku Tanpa Kaitan');

        $results = $this->service()->search(['keyword' => 'Quraish']);

        $this->assertSame(1, $results->total());
        $this->assertSame('Wawasan Al-Quran', $results->first()->title);
    }

    #[Test]
    public function it_finds_a_record_by_its_keywords(): void
    {
        $this->publish('Judul Tanpa Petunjuk', ['keywords' => 'tafsir, tematik']);
        $this->publish('Buku Lain');

        $this->assertSame(1, $this->service()->search(['keyword' => 'tematik'])->total());
    }

    /**
     * Kata pendek berada di bawah `innodb_ft_min_token_size`, jadi tidak pernah
     * masuk indeks. Jalur cadangan yang harus menanganinya — kalau tidak,
     * pencarian yang dulu berhasil mendadak tidak menemukan apa-apa.
     */
    #[Test]
    public function a_word_too_short_to_be_indexed_is_still_found(): void
    {
        $this->publish('Kitab AL Fiqh');

        $this->assertSame(1, $this->service()->search(['keyword' => 'AL'])->total());
    }

    /**
     * Operator boolean mode yang datang dari pengguna tidak boleh mengubah arti
     * kueri atau menjatuhkan halaman — pencarian "C++" adalah kasus nyata.
     */
    #[Test]
    #[DataProvider('keywordsWithOperators')]
    public function boolean_operators_in_the_keyword_never_break_the_search(string $keyword): void
    {
        $this->publish('Pemrograman C++ Dasar');

        $results = $this->service()->search(['keyword' => $keyword]);

        $this->assertGreaterThanOrEqual(0, $results->total());
    }

    public static function keywordsWithOperators(): array
    {
        return [
            'plus' => ['C++'],
            'minus' => ['-Dasar'],
            'tanda kutip' => ['"Pemrograman'],
            'tanda bintang' => ['***'],
            'tanda kurung' => ['(Dasar)'],
            'campuran' => ['+Dasar -Lanjut ~C'],
        ];
    }

    #[Test]
    public function search_never_reaches_across_the_visibility_boundary(): void
    {
        BibliographicRecord::factory()->draft()->create(['title' => 'Naskah Rahasia']);
        BibliographicRecord::factory()->internal()->create(['title' => 'Dokumen Internal']);
        BibliographicRecord::factory()->archived()->create(['title' => 'Arsip Lama']);

        foreach (['Rahasia', 'Internal', 'Arsip'] as $keyword) {
            $this->assertSame(0, $this->service()->search(['keyword' => $keyword])->total(),
                "Kata kunci {$keyword} menembus batas visibilitas.");
        }
    }

    #[Test]
    public function a_soft_deleted_record_disappears_from_search(): void
    {
        $record = $this->publish('Judul Akan Ditarik');
        $this->assertSame(1, $this->service()->search(['keyword' => 'Ditarik'])->total());

        $record->delete();

        $this->assertSame(0, $this->service()->search(['keyword' => 'Ditarik'])->total());
    }

    #[Test]
    public function filters_still_narrow_the_results_on_both_paths(): void
    {
        $buku = CollectionType::factory()->create();
        $jurnal = CollectionType::factory()->create();
        $this->publish('Kajian Tafsir Modern', ['collection_type_id' => $buku->id, 'publication_year' => 2020]);
        $this->publish('Kajian Tafsir Klasik', ['collection_type_id' => $jurnal->id, 'publication_year' => 2021]);

        $this->assertSame(2, $this->service()->search(['keyword' => 'Kajian'])->total());
        $this->assertSame(1, $this->service()->search(['keyword' => 'Kajian', 'collection_type_id' => $buku->id])->total());
        $this->assertSame(1, $this->service()->search(['keyword' => 'Kajian', 'publication_year' => 2021])->total());
    }

    #[Test]
    public function a_keyword_that_matches_nothing_returns_an_empty_page(): void
    {
        $this->publish('Tafsir Al Misbah');

        $results = $this->service()->search(['keyword' => 'astrofisika']);

        $this->assertSame(0, $results->total());
        $this->assertTrue($results->isEmpty());
    }

    /**
     * Jalur FULLTEXT hanya boleh dipakai di MySQL; pada SQLite kuerinya akan
     * gagal karena tidak ada MATCH ... AGAINST.
     */
    #[Test]
    public function the_search_works_on_whichever_driver_is_configured(): void
    {
        $this->publish('Tafsir Al Misbah');

        $this->assertSame(1, $this->service()->search(['keyword' => 'Misbah'])->total());
        $this->assertContains(DB::connection()->getDriverName(), ['mysql', 'sqlite']);
    }

    #[Test]
    public function the_search_page_renders_the_results(): void
    {
        $this->publish('Tafsir Al Misbah');

        $this->get(route('opac.search', ['keyword' => 'Misbah']))
            ->assertOk()
            ->assertSee('Tafsir Al Misbah');
    }
}
