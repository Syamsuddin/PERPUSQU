<?php

namespace App\Modules\DigitalRepository\Http\Requests;

use App\Support\Validation\Rules\SecureMimeType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDigitalAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_type' => [
                'required',
                'string',
                'in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other',
            ],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'publication_status' => 'required|string|in:draft,published,unpublished,archived',
            'is_public' => 'nullable|boolean',
            'is_embargoed' => 'nullable|boolean',
            'embargo_until' => 'nullable|date',
            'replacement_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:51200', // 50 MB
                new SecureMimeType(['application/pdf']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'replacement_file.max' => 'Ukuran file pengganti maksimum 50 MB.',
            'replacement_file.mimes' => 'File pengganti hanya mendukung format PDF.',
            'asset_type.required' => 'Tipe aset wajib dipilih.',
            'publication_status.required' => 'Status publikasi wajib dipilih.',
        ];
    }
}
