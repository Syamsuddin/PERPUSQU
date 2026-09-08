<?php

namespace App\Modules\Core\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Core\Contracts\Restorable;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

/**
 * Kotak sampah lintas modul.
 *
 * Lima tabel memakai penghapusan lunak, tetapi sampai sekarang tidak ada satu
 * pun layar yang menampilkan isinya. Akibatnya penghapusan terasa permanen bagi
 * petugas, dan pemulihan hanya mungkin lewat tinker — yang berarti praktis
 * tidak mungkin.
 *
 * Wewenangnya sengaja tidak memakai izin baru: siapa yang boleh menghapus,
 * boleh pula membatalkannya. Izin `*.delete` yang sudah ada dipakai apa adanya.
 */
class TrashService
{
    /**
     * Jenis data yang dapat dipulihkan.
     *
     * `label`      — nama yang tampil di tab
     * `model`      — kelas modelnya
     * `permission` — izin yang mengatur penghapusan, dan karenanya pemulihan
     * `title`      — kolom yang mewakili baris di layar
     * `subtitle`   — kolom pendamping, boleh null
     * `parent`     — relasi induk yang harus ada sebelum baris ini dipulihkan
     */
    public const TYPES = [
        'katalog' => [
            'label' => 'Katalog',
            'model' => BibliographicRecord::class,
            'permission' => 'catalog.delete',
            'title' => 'title',
            'subtitle' => 'isbn',
            'parent' => null,
        ],
        'item' => [
            'label' => 'Item Fisik',
            'model' => PhysicalItem::class,
            'permission' => 'collections.delete',
            'title' => 'barcode',
            'subtitle' => 'inventory_code',
            'parent' => 'bibliographicRecord',
        ],
        'anggota' => [
            'label' => 'Anggota',
            'model' => Member::class,
            'permission' => 'members.delete',
            'title' => 'name',
            'subtitle' => 'member_number',
            'parent' => null,
        ],
        'aset-digital' => [
            'label' => 'Aset Digital',
            'model' => DigitalAsset::class,
            'permission' => 'digital_assets.delete',
            'title' => 'original_file_name',
            'subtitle' => 'asset_type',
            'parent' => 'bibliographicRecord',
        ],
        'pengguna' => [
            'label' => 'Pengguna',
            'model' => User::class,
            'permission' => 'users.delete',
            'title' => 'name',
            'subtitle' => 'username',
            'parent' => null,
        ],
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(string $type): array
    {
        if (! array_key_exists($type, self::TYPES)) {
            throw new InvalidArgumentException("Jenis data tidak dikenal: {$type}.");
        }

        return self::TYPES[$type];
    }

    /**
     * Isi kotak sampah untuk satu jenis data, terbaru lebih dulu.
     */
    public function paginate(string $type, int $perPage = 20): LengthAwarePaginator
    {
        $definition = $this->definition($type);
        $model = $definition['model'];

        return $model::onlyTrashed()
            ->when($definition['parent'], fn ($q) => $q->with($definition['parent']))
            ->orderByDesc('deleted_at')
            ->paginate($perPage);
    }

    /**
     * Jumlah baris terhapus per jenis, untuk penanda di tab.
     *
     * @return array<string, int>
     */
    public function counts(): array
    {
        $counts = [];

        foreach (self::TYPES as $type => $definition) {
            $model = $definition['model'];
            $counts[$type] = $model::onlyTrashed()->count();
        }

        return $counts;
    }

    public function findTrashed(string $type, int $id): Model&Restorable
    {
        $model = $this->definition($type)['model'];

        return $model::onlyTrashed()->findOrFail($id);
    }

    /**
     * Kembalikan satu baris.
     *
     * Baris yang induknya masih terhapus sengaja ditolak: memulihkannya akan
     * menghasilkan eksemplar atau berkas yang menggantung pada judul yang tidak
     * terlihat di mana pun — keadaan yang lebih membingungkan daripada tetap
     * berada di kotak sampah.
     *
     * @throws InvalidArgumentException bila induknya masih terhapus
     */
    public function restore(string $type, Model&Restorable $record): void
    {
        $definition = $this->definition($type);

        if ($parent = $definition['parent']) {
            $this->assertParentIsPresent($record, $parent);
        }

        $record->restore();

        activity('core')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->withProperties(['type' => $type])
            ->log('Data dipulihkan dari kotak sampah: '.$this->describe($type, $record));
    }

    protected function assertParentIsPresent(Model $record, string $relation): void
    {
        $parent = $record->{$relation};

        if ($parent === null || $parent->trashed()) {
            throw new InvalidArgumentException(
                'Katalog induknya masih berada di kotak sampah. Pulihkan katalognya terlebih dahulu.'
            );
        }
    }

    public function describe(string $type, Model $record): string
    {
        $definition = $this->definition($type);

        return (string) ($record->{$definition['title']} ?? '#'.$record->getKey());
    }
}
