<?php

namespace App\Modules\Core\Contracts;

/**
 * Menandai model yang penghapusannya bersifat lunak dan karenanya dapat
 * dipulihkan lewat Kotak Sampah.
 *
 * Trait SoftDeletes sudah menyediakan kedua metode ini, jadi menerapkan
 * antarmuka ini tidak menuntut kode tambahan. Gunanya adalah membuat kontrak
 * itu terlihat: TrashService hanya menerima model yang menyatakan dirinya dapat
 * dipulihkan, alih-alih menerima Model apa pun lalu berharap metodenya ada.
 */
interface Restorable
{
    /**
     * @return bool|null
     */
    public function restore();

    /**
     * @return bool
     */
    public function trashed();
}
