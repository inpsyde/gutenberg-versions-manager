<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Tests\Integration\Service;

use Inpsyde\GutenbergVersions;
use Inpsyde\GutenbergVersionManager\Service;
use Inpsyde\GutenbergVersionManager\Tests;

class VersionActivatorTest extends Tests\UnitTestCase
{
    /**
     * @test Can activate Gutenberg version as specified by the Theme
     */
    public function activate_gutenberg_as_specified_by_the_theme(): void
    {
        $versionsLoader = \Mockery::mock('alias:' . GutenbergVersions\Loader::class);

        $supportedVersions = Service\SupportedVersions::new();
        $loader = Service\Loader::new();

        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('theme-name'));

        $versionsLoader->shouldReceive('isLoaded')->andReturn(false);
        $versionsLoader->shouldReceive('loadMatching')->with('13.9.0')->andReturn(true);

        $versionActivator = Service\VersionActivator::new($supportedVersions, $loader);

        $this->assertEquals(true, $versionActivator->forTheme($this->theme));
    }

    /**
     * @test
     */
    public function does_not_activate_gutenberg_because_no_compatible_versions_found(): void
    {
        $versionsLoader = \Mockery::mock('alias:' . GutenbergVersions\Loader::class);

        $supportedVersions = Service\SupportedVersions::new();
        $loader = Service\Loader::new();

        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('theme-name'));

        $versionsLoader->shouldReceive('isLoaded')->andReturn(false);
        $versionsLoader->shouldReceive('loadMatching')->andReturn(false);

        $versionActivator = Service\VersionActivator::new($supportedVersions, $loader);

        $this->assertEquals(false, $versionActivator->forTheme($this->theme));
    }

    /**
     * @test
     */
    public function does_not_activate_gutenberg_because_already_loaded(): void
    {
        $versionsLoader = \Mockery::mock('alias:' . GutenbergVersions\Loader::class);

        $supportedVersions = Service\SupportedVersions::new();
        $loader = Service\Loader::new();

        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('theme-name'));

        $versionsLoader->shouldReceive('isLoaded')->andReturn(true);
        $versionsLoader->shouldReceive('loadMatching')->never();

        $versionActivator = Service\VersionActivator::new($supportedVersions, $loader);

        $this->assertEquals(true, $versionActivator->forTheme($this->theme));
    }
}
