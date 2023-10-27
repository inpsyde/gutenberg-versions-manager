<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Service;

use Inpsyde\GutenbergVersions\Loader;

class BodyClassesFilter
{
    private SupportedVersions $supportedVersions;

    public static function new(SupportedVersions $supportedVersions): BodyClassesFilter
    {
        return new self($supportedVersions);
    }

    final private function __construct(SupportedVersions $supportedVersions)
    {
        $this->supportedVersions = $supportedVersions;
    }

    public function addGutenbergVersionClass(\WP_Theme $theme, array|string $bodyClasses): array|string
    {
        $version = (string)$this->currentGutenbergVersionByTheme($theme);
        $cleanedVersion = \str_replace('.', '', $version);

        if (!$version) {
            return $bodyClasses;
        }

        $className = "inpsyde-gutenberg-{$cleanedVersion}";
        $classNames = \array_unique(
            \array_merge((array)$bodyClasses, [$className]),
        );

        return \is_array($bodyClasses)
            ? $classNames
            : \implode(' ', $classNames);
    }

    private function currentGutenbergVersionByTheme(\WP_Theme $theme): ?string
    {
        return Loader::matchingVersion(
            ...$this->supportedVersions->forTheme($theme)
        );
    }
}
