<?php

return [
    'force_https' => env('SECURITY_FORCE_HTTPS', false),
    'enable_hsts' => env('SECURITY_ENABLE_HSTS', false),
    'login_rate_limit' => env('SECURITY_LOGIN_RATE_LIMIT_PER_MINUTE', 5),
    'public_api_rate_limit' => env('SECURITY_PUBLIC_API_RATE_LIMIT_PER_MINUTE', 60),
    'hide_debug_details' => env('SECURITY_HIDE_DEBUG_DETAILS', true),
    'sensitive_fields_masking' => env('APP_LOG_SENSITIVE_FIELDS_MASKING', true),
];
