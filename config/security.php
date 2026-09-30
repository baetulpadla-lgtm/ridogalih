<?php

return [
    'csp' => [
        'enabled' => (bool) env('SECURITY_CSP_ENABLED', true),
        'report_only' => (bool) env('SECURITY_CSP_REPORT_ONLY', true),
    ],

    'ip_restriction' => [
        'enabled' => (bool) env('SECURITY_IP_RESTRICTION_ENABLED', false),
        'allowed' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('SECURITY_ADMIN_ALLOWED_IPS', ''))
        ))),
    ],

    'hsts_include_subdomains' => (bool) env('SECURITY_HSTS_INCLUDE_SUBDOMAINS', false),
];
