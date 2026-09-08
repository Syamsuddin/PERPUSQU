<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Services\FineService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FineServiceTest extends TestCase
{
    use RefreshDatabase;

    private FineService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FineService::class);
    }

    #[Test]
    public function it_settles_an_outstanding_fine(): void
    {
        $this->actingAsUserWith(['circulation.view_fines']);
        $fine = Fine::factory()->outstanding()->create(['amount' => 4000]);

        $this->service->settle($fine);

        $this->assertSame('settled', $fine->fresh()->status);
    }

    #[Test]
    public function it_waives_an_outstanding_fine(): void
    {
        $this->actingAsUserWith(['circulation.view_fines']);
        $fine = Fine::factory()->outstanding()->create(['amount' => 4000]);

        $this->service->waive($fine);

        $this->assertSame('waived', $fine->fresh()->status);
    }

    /**
     * Denda yang sudah selesai tidak boleh diproses ulang — kalau bisa,
     * satu denda dapat "dilunasi" berkali-kali dan laporan kas jadi salah.
     */
    #[Test]
    #[DataProvider('closedStatuses')]
    public function it_refuses_to_settle_a_fine_that_is_no_longer_outstanding(string $status): void
    {
        $this->actingAsUserWith(['circulation.view_fines']);
        $fine = Fine::factory()->create(['status' => $status]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Hanya denda outstanding yang dapat dilunasi.');

        $this->service->settle($fine);
    }

    #[Test]
    #[DataProvider('closedStatuses')]
    public function it_refuses_to_waive_a_fine_that_is_no_longer_outstanding(string $status): void
    {
        $this->actingAsUserWith(['circulation.view_fines']);
        $fine = Fine::factory()->create(['status' => $status]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Hanya denda outstanding yang dapat dihapuskan.');

        $this->service->waive($fine);
    }

    public static function closedStatuses(): array
    {
        return [['settled'], ['waived']];
    }

    #[Test]
    public function the_summary_separates_outstanding_settled_and_waived(): void
    {
        Fine::factory()->outstanding()->create(['amount' => 1000]);
        Fine::factory()->outstanding()->create(['amount' => 2500]);
        Fine::factory()->settled()->create(['amount' => 4000]);
        Fine::factory()->waived()->create(['amount' => 500]);

        $summary = $this->service->getSummary();

        $this->assertEquals(3500, $summary['total_outstanding']);
        $this->assertEquals(4000, $summary['total_settled']);
        $this->assertEquals(500, $summary['total_waived']);
        $this->assertSame(2, $summary['count_outstanding']);
        $this->assertSame(1, $summary['count_settled']);
        $this->assertSame(1, $summary['count_waived']);
    }

    #[Test]
    public function the_summary_is_all_zeroes_when_there_are_no_fines(): void
    {
        $summary = $this->service->getSummary();

        $this->assertEquals(0, $summary['total_outstanding']);
        $this->assertSame(0, $summary['count_outstanding']);
    }

    #[Test]
    public function the_list_can_be_filtered_by_status_and_member(): void
    {
        $member = $this->eligibleMember();
        Fine::factory()->forMember($member)->outstanding()->create();
        Fine::factory()->forMember($member)->settled()->create();
        Fine::factory()->outstanding()->create();

        $this->assertCount(2, $this->service->getPaginated(['member_id' => $member->id]));
        $this->assertCount(2, $this->service->getPaginated(['status' => 'outstanding']));
        $this->assertCount(1, $this->service->getPaginated(['member_id' => $member->id, 'status' => 'outstanding']));
    }
}
