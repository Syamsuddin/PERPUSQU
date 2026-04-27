<?php

return [
    'ocr_enabled' => env('OCR_ENABLED', false),
    'horizon_enabled' => env('HORIZON_ENABLED', false),
    'telescope_enabled' => env('TELESCOPE_ENABLED', false),
    'pulse_enabled' => env('PULSE_ENABLED', false),
    'opac_public_suggestion' => env('OPAC_ENABLE_PUBLIC_SUGGESTION', false),
    'report_export_async' => env('REPORT_EXPORT_ASYNC', false),
    'mail_enabled' => env('MAIL_ENABLED', false),
];
