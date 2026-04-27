<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\Language;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Language::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Language
    {
        $lang = Language::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($lang)->log('Bahasa dibuat: ' . $lang->name);
        return $lang;
    }

    public function update(Language $language, array $data): Language
    {
        $language->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($language)->log('Bahasa diperbarui: ' . $language->name);
        return $language;
    }

    public function delete(Language $language): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($language)->log('Bahasa dihapus: ' . $language->name);
        $language->delete();
    }
}
