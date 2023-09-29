<?php // phpcs:disable
declare(strict_types=1);

(static function (string $libPath): void {
    $vendorDir = "{$libPath}/vendor";
    $phpTestDir = __DIR__;
    $testsDir = \dirname($phpTestDir);

    if (!\realpath($vendorDir)) {
        die('Please install via Composer before running tests.');
    }

    /** @noinspection PhpIncludeInspection */
    require_once "{$vendorDir}/autoload.php";
})(
    \dirname(__DIR__)
);
