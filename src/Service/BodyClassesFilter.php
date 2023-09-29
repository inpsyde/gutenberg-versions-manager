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

    /**
     * @param array|string $bodyClasses
     * @return array|string
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     */
    public function addGutenbergVersionClass(\WP_Theme $theme, $bodyClasses)
    {
        // phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
        // phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType

        $version = (string)$this->currentGutenbergVersionByTheme($theme);
        $cleanedVersion = \str_replace('.', '', $version);

        if (!$version) {
            return $bodyClasses;
        }

        $className = "mh-gutenberg-{$cleanedVersion}";
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
