<?php

namespace App\Modules\DigitalRepository\Http\Requests;

use App\Modules\Core\Services\SystemSettings;
use App\Support\Validation\Rules\SecureMimeType;
use Illuminate\Foundation\Http\FormRequest;

class StoreDigitalAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Batas ukuran unggahan diambil dari Aturan Operasional, bukan angka tetap,
     * sehingga pengelola dapat menyesuaikannya dengan kapasitas server.
     */
    protected function maxUploadKilobytes(): int
    {
        return app(SystemSettings::class)->maxUploadSizeKilobytes();
    }

    public function rules(): array
    {
        return [
            'bibliographic_record_id' => 'required|integer|exists:bibliographic_records,id',
            'asset_type' => [
                'required',
                'string',
                'in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other',
            ],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:'.$this->maxUploadKilobytes(),
                new SecureMimeType(['application/pdf']),
            ],
            'publication_status' => 'nullable|string|in:draft,published,unpublished,archived',
            'is_public' => 'nullable|boolean',
            'is_embargoed' => 'nullable|boolean',
            'embargo_until' => 'nullable|date|after:today',
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'Ukuran file maksimum '.app(SystemSettings::class)->maxUploadSizeMb().' MB.',
            'file.mimes' => 'Hanya file PDF yang didukung.',
            'bibliographic_record_id.required' => 'Katalog bibliografi wajib dipilih.',
            'bibliographic_record_id.exists' => 'Katalog bibliografi tidak ditemukan.',
            'asset_type.required' => 'Tipe aset wajib dipilih.',
        ];
    }
}
