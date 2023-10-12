<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Service;

/**
 * A repository to retrieve all the supported versions of Gutenberg.
 *
 * @internal
 */
class SupportedVersions
{
    private const VERSIONS_KEY = 'versions';
    private const FILE_PATH_TEMPLATE = '%s/config/gutenberg.php';

    public static function new(): SupportedVersions
    {
        return new self();
    }

    final private function __construct()
    {
    }

    /**
     * @return string[]
     */
    public function forTheme(\WP_Theme $theme): array
    {
        $themeRoot = \rtrim($theme->get_template_directory(), '/\\');
        $lookupFile = \sprintf(self::FILE_PATH_TEMPLATE, $themeRoot);

        if (!\is_readable($lookupFile)) {
            return [];
        }

        $configuration = (array)(include $lookupFile);

        return \array_filter(
            (array)($configuration[self::VERSIONS_KEY] ?? null),
            static fn (mixed $v) => \is_string($v),
        );
    }
}
