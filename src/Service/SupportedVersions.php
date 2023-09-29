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
     * @return string[]|array
     */
    public function forTheme(\WP_Theme $theme): array
    {
        $themeRoot = \rtrim($theme->get_template_directory(), '/\\');
        $lookupFile = \sprintf(self::FILE_PATH_TEMPLATE, $themeRoot);

        if (!\is_readable($lookupFile)) {
            return [];
        }

        $configuration = (array)(include $lookupFile);

        if (!\array_key_exists(self::VERSIONS_KEY, $configuration)) {
            return [];
        }

        $configuration = \array_merge(
            $this->cannedConfiguration(),
            $configuration
        );

        return $configuration[self::VERSIONS_KEY] ?? [];
    }

    private function cannedConfiguration(): array
    {
        return [
            self::VERSIONS_KEY => [],
        ];
    }
}
