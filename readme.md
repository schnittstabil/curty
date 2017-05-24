# Schnittstabil\Curty [![Build Status](https://travis-ci.org/schnittstabil/curty.svg?branch=master)](https://travis-ci.org/schnittstabil/curty) [![Coverage Status](https://coveralls.io/repos/schnittstabil/curty/badge.svg?branch=master&service=github)](https://coveralls.io/github/schnittstabil/curty?branch=master) [![Code Climate](https://codeclimate.com/github/schnittstabil/curty/badges/gpa.svg)](https://codeclimate.com/github/schnittstabil/curty) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/schnittstabil/curty/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/schnittstabil/curty/?branch=master)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a4f3538f-5d0f-40b0-bef1-d1c09f896f40/big.png)](https://insight.sensiolabs.com/projects/a4f3538f-5d0f-40b0-bef1-d1c09f896f40)

> Simple curly braces micro templating.


## Install

```
composer require schnittstabil/curty
```


## Usage


```php
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
```


## License

MIT © [schnittstabil](http://schnittstabil.de)
