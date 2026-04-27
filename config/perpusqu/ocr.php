<?php

return [
    'enabled' => env('OCR_ENABLED', false),
    'binary_path' => env('OCR_BINARY_PATH', '/usr/local/bin/tesseract'),
    'temp_path' => env('OCR_TEMP_PATH', storage_path('app/temp/ocr')),
    'default_mode' => env('OCR_DEFAULT_MODE', 'queue'),
    'max_pages_per_batch' => env('OCR_MAX_PAGES_PER_BATCH', 50),
    'retry_attempts' => env('OCR_RETRY_ATTEMPTS', 3),
    'minimum_text_threshold' => env('OCR_MINIMUM_TEXT_THRESHOLD', 10),
    'image_dpi' => env('OCR_IMAGE_DPI', 300),
];
