<?php

return [
    // Statuses counted towards the listing quota
    'counted_statuses' => [
        'pending',
        'approved',
        'published',
        'active',
    ],

    // Roles that are exempt from quota enforcement
    'bypass_roles' => ['admin'],

    // Fallback limit when no role quota is defined (null = unlimited)
    'default_limit' => null,
];
