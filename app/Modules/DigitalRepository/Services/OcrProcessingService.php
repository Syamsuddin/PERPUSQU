<?php

namespace App\Modules\DigitalRepository\Services;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Models\OcrText;

class OcrProcessingService
{
    /**
     * State flow: NOT_REQUESTED → QUEUED → PROCESSING → SUCCESS / FAILED
     */
    public function requestOcr(DigitalAsset $asset): DigitalAsset
    {
        if (! in_array($asset->ocr_status, ['not_requested', 'failed'])) {
            throw new \InvalidArgumentException('OCR hanya dapat diminta untuk status not_requested atau failed.');
        }

        $asset->update([
            'ocr_status' => 'queued',
            'ocr_attempted_at' => now(),
        ]);

        // In production, dispatch OCR job here
        // ProcessDigitalAssetOcrJob::dispatch($asset);

        activity('digital_repository')
            ->causedBy(auth()->user())
            ->performedOn($asset)
            ->log('OCR diminta untuk: '.($asset->title ?: $asset->original_file_name));

        return $asset;
    }

    public function markProcessing(DigitalAsset $asset): void
    {
        $asset->update(['ocr_status' => 'processing']);
    }

    public function markSuccess(DigitalAsset $asset, string $extractedText): OcrText
    {
        $asset->update(['ocr_status' => 'success']);

        return OcrText::updateOrCreate(
            ['digital_asset_id' => $asset->id],
            [
                'source_type' => 'tesseract',
                'extracted_text' => $extractedText,
                'extraction_status' => 'success',
                'extracted_at' => now(),
                'error_message' => null,
            ]
        );
    }

    public function markFailed(DigitalAsset $asset, string $errorMessage): void
    {
        $asset->update(['ocr_status' => 'failed']);

        OcrText::updateOrCreate(
            ['digital_asset_id' => $asset->id],
            [
                'source_type' => 'tesseract',
                'extraction_status' => 'failed',
                'error_message' => $errorMessage,
            ]
        );
    }
}
