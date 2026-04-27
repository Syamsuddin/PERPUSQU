<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\Author;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Author::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Author
    {
        $data['normalized_name'] = $data['normalized_name'] ?? strtolower($data['name']);
        $author = Author::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($author)->log('Pengarang dibuat: ' . $author->name);
        return $author;
    }

    public function update(Author $author, array $data): Author
    {
        $data['normalized_name'] = $data['normalized_name'] ?? strtolower($data['name']);
        $author->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($author)->log('Pengarang diperbarui: ' . $author->name);
        return $author;
    }

    public function delete(Author $author): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($author)->log('Pengarang dihapus: ' . $author->name);
        $author->delete();
    }
}
