<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Tests;

function bootstrap(string $libPath): void
{
    $vendorDir = "{$libPath}/vendor";
    $phpTestDir = __DIR__;
    $testsDir = \dirname($phpTestDir);

    if (!\realpath($vendorDir)) {
        die('Please install via Composer before running tests.');
    }

    require_once "{$vendorDir}/autoload.php";
}
