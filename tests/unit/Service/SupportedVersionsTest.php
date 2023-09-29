<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Tests\Unit\Service;

use Inpsyde\GutenbergVersionManager\Service;
use Inpsyde\GutenbergVersionManager\Tests;

class SupportedVersionsTest extends Tests\UnitTestCase
{
    /**
     * @test Retrieve the supported Gutenberg versions from the current theme
     */
    public function retrieve_gutenberg_versions(): void
    {
        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('theme-name'));

        $supportedThemeVersions = Service\SupportedVersions::new();
        $versions = $supportedThemeVersions->forTheme($this->theme);

        $this->assertEquals(['13.9.0', '12.8.0', '11.14.1'], $versions);
    }

    /**
     * @test Try to retrieve versions with a wrong path, results in an empty `versions` entry.
     */
    public function cannot_retrieve_gutenberg_versions(): void
    {
        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('theme-does-not-exists'));

        $supportedThemeVersions = Service\SupportedVersions::new();
        $versions = $supportedThemeVersions->forTheme($this->theme);

        $this->assertEquals([], $versions);
    }

    /**
     * @test The Versions entry is empty
     */
    public function retrieve_empty_versions(): void
    {
        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('empty-versions-theme-name'));

        $supportedThemeVersions = Service\SupportedVersions::new();
        $versions = $supportedThemeVersions->forTheme($this->theme);

        $this->assertEquals([], $versions);
    }

    /**
     * @test The `versions` entry does not exist and empty `versions` is returned
     */
    public function return_empty_versions_entry_if_entry_does_not_exists(): void
    {
        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('not-exists-versions-theme-name'));

        $supportedThemeVersions = Service\SupportedVersions::new();
        $versions = $supportedThemeVersions->forTheme($this->theme);

        $this->assertEquals([], $versions);
    }

    /**
     * @test Empty file content will still return an empty `versions` entry
     */
    public function return_empty_versions_entry_if_file_content_is_empty(): void
    {
        $this->theme
            ->shouldReceive('get_template_directory')
            ->andReturn($this->themesPath('not-exists-versions-theme-name'));

        $supportedThemeVersions = Service\SupportedVersions::new();
        $versions = $supportedThemeVersions->forTheme($this->theme);

        $this->assertEquals([], $versions);
    }
}
