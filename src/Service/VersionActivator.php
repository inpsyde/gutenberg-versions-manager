<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Service;

/**
 * Activate the compatible version of Gutenberg.
 *
 * @internal
 */
class VersionActivator
{
    private SupportedVersions $supportedVersions;

    private Loader $loader;

    public static function new(
        SupportedVersions $supportedVersions,
        Loader $loader
    ): VersionActivator {

        return new self($supportedVersions, $loader);
    }

    final private function __construct(SupportedVersions $supportedVersions, Loader $loader)
    {
        $this->supportedVersions = $supportedVersions;
        $this->loader = $loader;
    }

    public function forTheme(\WP_Theme $theme): bool
    {
        $supportedVersions = $this->supportedVersions->forTheme($theme);
        if (!$supportedVersions) {
            return false;
        }

        return $this->loader->load(...$supportedVersions);
    }
}
