<?php

namespace App\Modules\Catalog\Http\Requests;

use App\Support\Validation\Rules\SecureMimeType;
use Illuminate\Foundation\Http\FormRequest;

class QuickEntryEbookRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // ── Metadata Katalog ──
            'title'             => 'required|string|min:2|max:255',
            'collection_type_id'=> 'required|integer|exists:collection_types,id',
            'publisher_id'      => 'nullable|integer|exists:publishers,id',
            'language_id'       => 'nullable|integer|exists:languages,id',
            'classification_id' => 'nullable|integer|exists:classifications,id',
            'publication_year'  => 'nullable|integer|min:1000|max:9999',
            'isbn'              => 'nullable|string|max:50',
            'edition'           => 'nullable|string|max:100',
            'keywords'          => 'nullable|string|max:2000',
            'abstract'          => 'nullable|string|max:20000',
            'cover'             => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'is_public'         => 'nullable|boolean',
            'author_ids'        => 'required|array|min:1',
            'author_ids.*'      => 'integer|exists:authors,id',
            'subject_ids'       => 'nullable|array',
            'subject_ids.*'     => 'integer|exists:subjects,id',

            // ── Data Aset Digital ──
            'file' => [
                'required', 'file', 'mimes:pdf', 'max:51200',
                new SecureMimeType(['application/pdf']),
            ],
            'ebook_description' => 'nullable|string|max:5000',
            'publication_status'=> 'nullable|string|in:draft,published',
        ];
    }

    public function messages(): array
    {
        return [
            'author_ids.required'  => 'Minimal satu pengarang wajib dipilih.',
            'file.required'        => 'File PDF e-book wajib diunggah.',
            'file.mimes'           => 'Hanya file PDF yang didukung.',
            'file.max'             => 'Ukuran file maksimum 50 MB.',
        ];
    }
}
