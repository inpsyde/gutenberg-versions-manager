<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Tests;

use bovigo\vfs;
use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Inpsyde\GutenbergVersions;

class UnitTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected const ROOT_THEMES_PATH = 'root/themes';

    private const DIR_STRUCTURE = [
        'themes' => [
            'theme-name' => [
                'config' => [
                    'gutenberg.php' => <<<PHP
<?php
return [
    'versions' => [
        '13.9.0',
        '12.8.0',
        '11.14.1',
    ]
];
PHP,
                ],
            ],
            'empty-versions-theme-name' => [
                'config' => [
                    'gutenberg.php' => <<<PHP
<?php
return [
    'versions' => []
];
PHP,
                ],
            ],
            'not-exists-versions-theme-name' => [
                'config' => [
                    'gutenberg.php' => '',
                ],
            ],
        ],
    ];

    protected vfs\vfsStreamDirectory $filesystem;

    /** @var \WP_Theme|\Mockery\MockInterface */
    protected $theme;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();

        $this->filesystem = vfs\vfsStream::setup('root', null, self::DIR_STRUCTURE);
        $this->theme = \Mockery::mock('\WP_Theme');

        Monkey\Functions\when('__')->returnArg();
    }

    public function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    protected function themesPath(?string $path = null): string
    {
        $path and $path = self::ROOT_THEMES_PATH . '/' . \trim($path, '\\/');
        $path or $path = self::ROOT_THEMES_PATH;

        return vfs\vfsStream::url($path);
    }

    protected function withActiveGutenberg(): void
    {
        \Mockery::mock('alias:' . GutenbergVersions\Loader::class, [
            'isLoaded' => false,
            'loadMatching' => true,
        ]);

        Monkey\Functions\expect('is_plugin_active')->andReturn(false);
        Monkey\Functions\expect('wp_get_theme')->andReturn($this->theme);

        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('theme-name'));
    }

    protected function withoutThemeSupport(): void
    {
        Monkey\Functions\expect('is_plugin_active')->andReturn(false);
        Monkey\Functions\expect('wp_get_theme')->andReturn($this->theme);

        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('empty-versions-theme-name'));
    }

    protected function withGutenbergPluginActive(): void
    {
        Monkey\Functions\expect('is_plugin_active')->andReturn(true);
    }
}
