<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Service;

use Inpsyde\GutenbergVersions;

/**
 * @internal
 */
class Loader
{
    public static function new(): Loader
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function load(string ...$supportedVersions): bool
    {
        if (GutenbergVersions\Loader::isLoaded()) {
            return true;
        }

        foreach ($supportedVersions as $supportedVersion) {
            if (GutenbergVersions\Loader::loadMatching($supportedVersion)) {
                return true;
            }
        }

        return false;
    }
}
