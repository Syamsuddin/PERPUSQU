<?php

return [
    'public_disk' => env('STORAGE_PUBLIC_DISK', 'public'),
    'private_disk' => env('STORAGE_PRIVATE_DISK', 'local'),
    'temp_disk' => env('STORAGE_TEMP_DISK', 'local'),
    'logo_max_mb' => env('LIBRARY_LOGO_MAX_MB', 2),
    'cover_max_mb' => env('LIBRARY_COVER_MAX_MB', 4),
    'asset_max_mb' => env('LIBRARY_ASSET_MAX_MB', 50),
    'allowed_image_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    'allowed_asset_extensions' => ['pdf'],
    'allowed_asset_mime_types' => ['application/pdf'],
    'cover_path' => 'covers',
    'logo_path' => 'logos',
    'asset_path' => 'digital-assets',
    'temp_path' => 'temp',
];
