<?php

return [
    'enabled' => env('AUDIT_ENABLED', true),
    'log_ip_address' => env('AUDIT_LOG_IP_ADDRESS', true),
    'log_user_agent' => env('AUDIT_LOG_USER_AGENT', true),
    'value_sanitization' => env('AUDIT_LOG_VALUE_SANITIZATION', true),
    'sensitive_fields' => ['password', 'password_confirmation', 'remember_token', 'api_token'],
];
