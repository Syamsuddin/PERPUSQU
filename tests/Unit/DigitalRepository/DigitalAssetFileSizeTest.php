<?php

namespace Tests\Unit\DigitalRepository;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DigitalAssetFileSizeTest extends TestCase
{
    public static function fileSizes(): array
    {
        return [
            'nol byte' => [0, '0 B'],
            'byte' => [512, '512 B'],
            'tepat 1 KB' => [1024, '1 KB'],
            'kilobyte' => [2048, '2 KB'],
            'tepat 1 MB' => [1048576, '1 MB'],
            'megabyte' => [5_242_880, '5 MB'],
            'gigabyte' => [2_147_483_648, '2 GB'],
            'batas bawah KB' => [1023, '1023 B'],
        ];
    }

    #[Test]
    #[DataProvider('fileSizes')]
    public function it_formats_the_file_size_in_the_largest_fitting_unit(int $bytes, string $expected): void
    {
        $asset = new DigitalAsset(['file_size' => $bytes]);

        $this->assertSame($expected, $asset->file_size_formatted);
    }
}
