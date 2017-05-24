#!/usr/bin/env php
<?php

namespace Usage;

require __DIR__.'/../vendor/autoload.php';

ob_start();

// *************************** readme-usage start ***************************
// Context (variable lookup table)
$ctx = [
    'user' => [
        'login' => 'curt',
        'phone' => [
            'internal' => 1337,
        ],
    ],
    'unicorn' => '{{user.login}}: {{user.phone.internal}}',
    'lazy' => function ($ctx) : string {
        return date('z').' {{unicorn}}';
    },
];

/*
 * Simple rendering
 */
use Schnittstabil\Curty;

echo Curty\render('{{user.login}}', $ctx); // => 'curt'
echo Curty\render('{{unicorn}}', $ctx);    // => '{{user.login}}: {{user.phone.internal}}'
echo Curty\render('{{lazy}}', $ctx);       // => '42 {{unicorn}}'

/*
 * Fixed-point rendering
 */
use function Schnittstabil\curty;

echo curty('{{user.login}}', $ctx); // => 'curt'
echo curty('{{unicorn}}', $ctx);    // => 'curt: 1337'
echo curty('{{lazy}}', $ctx);       // => '42 curt: 1337'
// **************************** readme-usage end ****************************

$actual = ob_get_clean();

$expected = 'curt';
$expected .= '{{user.login}}: {{user.phone.internal}}';
$expected .= date('z').' {{unicorn}}';
$expected .= 'curt';
$expected .= 'curt: 1337';
$expected .= date('z').' curt: 1337';

var_dump([
    'actual' => $actual,
    'expected' => $expected,
]);

exit($actual === $expected ? 0 : 1);
