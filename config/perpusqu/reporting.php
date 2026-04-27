<?php

return [
    'export_default_format' => env('REPORT_EXPORT_DEFAULT_FORMAT', 'xlsx'),
    'export_async' => env('REPORT_EXPORT_ASYNC', false),
    'export_temp_path' => env('REPORT_EXPORT_TEMP_PATH', storage_path('app/temp/exports')),
    'export_retention_hours' => env('REPORT_EXPORT_RETENTION_HOURS', 24),
    'member_import_max_mb' => env('MEMBER_IMPORT_MAX_FILE_MB', 10),
];
