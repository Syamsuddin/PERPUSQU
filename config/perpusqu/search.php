<?php

return [
    'driver' => env('SEARCH_DRIVER', 'meilisearch'),
    'public_index' => env('SEARCH_INDEX_PUBLIC_RECORDS', 'opac_records'),
    'reindex_chunk_size' => env('SEARCH_REINDEX_CHUNK_SIZE', 100),
    'hydrate_chunk_size' => env('SEARCH_HYDRATE_CHUNK_SIZE', 50),
    'public_enabled' => env('SEARCH_PUBLIC_ENABLED', true),
    'sync_settings_on_deploy' => env('SEARCH_SYNC_SETTINGS_ON_DEPLOY', false),
];
