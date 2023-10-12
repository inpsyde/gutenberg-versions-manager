<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Provider;

use Inpsyde\App;
use Inpsyde\GutenbergVersions\Loader;
use Inpsyde\GutenbergVersionManager\Service;

class ServiceIntegration extends App\Provider\EarlyBootedOnly
{
    private const RELATIVE_VERSIONS_PATH = '/inpsyde/gutenberg-mirror';

    // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
    private const URL_FIXER_PREG_PATTERN = '~^(https?://.+?/wp-content).+?(client-mu-plugins/vendor' . self::RELATIVE_VERSIONS_PATH . '/[0-9\.-]+.+?)~';

    private Service\VersionActivator $versionActivator;

    private Service\Notice $notice;

    private Service\SupportedVersions $supportedVersions;

    private Service\BodyClassesFilter $bodyClassesFilter;

    public static function new(): ServiceIntegration
    {
        return new self();
    }

    final private function __construct()
    {
        $this->supportedVersions = Service\SupportedVersions::new();
        $this->notice = Service\Notice::new();
        $this->versionActivator = Service\VersionActivator::new(
            $this->supportedVersions,
            Service\Loader::new()
        );
        $this->bodyClassesFilter = Service\BodyClassesFilter::new($this->supportedVersions);
    }

    public function boot(App\Container $c): bool
    {
        // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
        if ($this->isGutenbergPluginActive()) {
            $this->notice->addNotice(
                \__(
                    'Gutenberg Version Manager cannot activate the Gutenberg plugin because a version of it is currently active. Please disable the Gutenberg plugin first.',
                    'gutenberg-versions-manager'
                ),
                Service\Notice::ERROR
            );
            return false;
        }

        if (!$this->activateGutenbergVersion()) {
            return false;
        }

        $this->fixPluginsUrl();
        $this->addBodyClasses();
        $this->activeGutenbergVersionThemeRowFilter();
        $this->activeGutenbergVersionAtAGlanceInfo();

        return true;
    }

    private function isGutenbergPluginActive(): bool
    {
        if (!\function_exists('is_plugin_active')) {
            require_once \untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
        }

        return \is_plugin_active('gutenberg/gutenberg.php');
    }

    private function activateGutenbergVersion(): bool
    {
        if (!$this->versionActivator->forTheme(\wp_get_theme())) {
            return false;
        }

        return true;
    }

    private function fixPluginsUrl(): void
    {
        \add_filter(
            'plugins_url',
            static fn (string $url) => \preg_replace(self::URL_FIXER_PREG_PATTERN, '$1/$2', $url),
        );
    }

    private function addBodyClasses(): void
    {
        $callback = fn (array|string $classes) => $this->bodyClassesFilter->addGutenbergVersionClass(
            \wp_get_theme(),
            $classes
        );

        \add_filter('admin_body_class', $callback);
        \add_filter('body_class', $callback);
    }

    private function activeGutenbergVersionThemeRowFilter(): void
    {
        \add_filter(
            'theme_row_meta',
            function (
                array $themeMeta,
                string $stylesheet,
                \WP_Theme $currentTheme
            ): array {
                if ($this->isGutenbergPluginActive()) {
                    return $themeMeta;
                }

                $version = $this->currentGutenbergVersionByTheme($currentTheme);

                if ($version) {
                    $themeMeta[] = \sprintf(
                    /* translators: %s is the Gutenberg version */
                        \_x(
                            'Gutenberg Version %s',
                            'Theme Information',
                            'gutenberg-versions-manager'
                        ),
                        $version
                    );
                }

                return $themeMeta;
            },
            10,
            3
        );
    }

    private function activeGutenbergVersionAtAGlanceInfo(): void
    {
        \add_filter(
            'update_right_now_text',
            function (string $content): string {
                $version = $this->currentGutenbergVersionByTheme();

                if ($version) {
                    $content .= ' ' . \sprintf(
                        /* translators: %s is the Gutenberg version */
                        \_x(
                            'Gutenberg Version %s',
                            'Theme Information',
                            'gutenberg-versions-manager'
                        ),
                        $version
                    );
                }

                return $content;
            }
        );
    }

    private function currentGutenbergVersionByTheme(\WP_Theme $theme = null): ?string
    {
        $currentTheme = $theme ?? \wp_get_theme();
        return Loader::matchingVersion(
            ...$this->supportedVersions->forTheme($currentTheme)
        );
    }
}
