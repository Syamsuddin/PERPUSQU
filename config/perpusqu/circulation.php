<?php

return [
    'loan_default_days' => env('LOAN_DEFAULT_DAYS', 14),
    'max_active_loans' => env('LOAN_MAX_ACTIVE_LOANS', 5),
    'max_renewal_count' => env('LOAN_MAX_RENEWAL_COUNT', 2),
    'allow_renewal' => env('LOAN_ALLOW_RENEWAL', true),
    'require_active_member' => env('LOAN_REQUIRE_ACTIVE_MEMBER', true),
    'require_unblocked_member' => env('LOAN_REQUIRE_UNBLOCKED_MEMBER', true),
    'fine_daily_amount' => env('FINE_DAILY_AMOUNT', 1000),
];
