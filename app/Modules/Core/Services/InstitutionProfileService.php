<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\InstitutionProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstitutionProfileService
{
    /** Direktori penyimpanan logo institusi pada disk publik. */
    protected const LOGO_DIRECTORY = 'institution';

    public function getInstitutionProfile(): ?InstitutionProfile
    {
        return InstitutionProfile::current();
    }

    public function updateInstitutionProfile(array $data): InstitutionProfile
    {
        $profile = InstitutionProfile::current() ?? new InstitutionProfile;

        $oldLogoPath = null;
        $newLogoPath = null;

        if (isset($data['logo']) && $data['logo']) {
            // Simpan logo baru lebih dulu; logo lama baru dihapus setelah profil
            // tersimpan, agar kegagalan tidak menyisakan baris yang menunjuk ke
            // file yang sudah terhapus.
            $oldLogoPath = $profile->logo_path;
            $newLogoPath = $this->storeLogo($data['logo'], ['profile_id' => $profile->id]);
            $data['logo_path'] = $newLogoPath;
            unset($data['logo']);
        }

        try {
            $profile->fill($data);
            $profile->save();
        } catch (\Throwable $e) {
            $this->deleteLogo($newLogoPath, ['profile_id' => $profile->id]);

            throw $e;
        }

        if ($oldLogoPath && $oldLogoPath !== $profile->logo_path) {
            $this->deleteLogo($oldLogoPath, ['profile_id' => $profile->id]);
        }

        activity('core')
            ->causedBy(auth()->user())
            ->performedOn($profile)
            ->log('Profil institusi diperbarui');

        return $profile;
    }

    /**
     * Simpan logo institusi ke disk publik dan kembalikan path relatifnya.
     *
     * @throws \InvalidArgumentException bila folder tujuan tidak dapat ditulis
     *                                   atau file gagal disimpan
     */
    protected function storeLogo(UploadedFile $logo, array $context = []): string
    {
        $this->ensureStorageWritable();

        try {
            $logoPath = $logo->store(self::LOGO_DIRECTORY, 'public');
        } catch (\Throwable $e) {
            Log::error('Institution logo storage exception', $context + [
                'error' => $e->getMessage(),
                'user_id' => auth()?->id(),
            ]);

            // Pesan internal (berisi path server) hanya masuk log, tidak ke pengguna.
            throw new \InvalidArgumentException('Gagal menyimpan logo institusi. Hubungi administrator.');
        }

        if (! $logoPath) {
            Log::error('Logo upload failed for institution profile', $context + [
                'user_id' => auth()?->id(),
            ]);

            throw new \InvalidArgumentException('Gagal mengunggah logo institusi.');
        }

        return $logoPath;
    }

    /**
     * Pastikan direktori logo pada disk publik ada dan dapat ditulis.
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureStorageWritable(): void
    {
        try {
            $disk = Storage::disk('public');

            if (! $disk->directoryExists(self::LOGO_DIRECTORY)) {
                $disk->makeDirectory(self::LOGO_DIRECTORY);
            }

            $path = $disk->path(self::LOGO_DIRECTORY);
            $writable = is_dir($path) && is_writable($path);
        } catch (\Throwable $e) {
            Log::error('Institution logo storage writability check failed', [
                'directory' => self::LOGO_DIRECTORY,
                'error' => $e->getMessage(),
            ]);
            $writable = false;
        }

        if (! $writable) {
            throw new \InvalidArgumentException('Folder penyimpanan logo tidak dapat ditulis. Hubungi administrator.');
        }
    }

    /**
     * Hapus file logo secara best-effort. Kegagalan hanya dicatat di log agar
     * tidak membatalkan operasi yang sudah berhasil di database.
     */
    protected function deleteLogo(?string $path, array $context = []): void
    {
        if (! $path) {
            return;
        }

        try {
            $disk = Storage::disk('public');

            if ($disk->exists($path) && ! $disk->delete($path)) {
                Log::warning('Failed to delete institution logo', $context + ['logo_path' => $path]);
            }
        } catch (\Throwable $e) {
            Log::warning('Institution logo deletion exception', $context + [
                'logo_path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
