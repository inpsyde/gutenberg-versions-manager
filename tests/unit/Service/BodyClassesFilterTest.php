<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Tests\Unit\Service;

use Inpsyde\GutenbergVersions;
use Inpsyde\GutenbergVersionManager\Service;
use Inpsyde\GutenbergVersionManager\Tests\UnitTestCase;

class BodyClassesFilterTest extends UnitTestCase
{
    /**
     * @test
     */
    public function can_filter_strings(): void
    {
        \Mockery::mock('alias:' . GutenbergVersions\Loader::class, [
            'matchingVersion' => '12.4.0',
        ]);
        $supportedVersions = \Mockery::mock(Service\SupportedVersions::class, [
            'forTheme' => ['12.4.0', '13.9.0'],
        ]);
        $bodyClassesFilter = Service\BodyClassesFilter::new($supportedVersions);

        $classes = 'custom-classname';
        $result = $bodyClassesFilter->addGutenbergVersionClass($this->theme, $classes);
        $this->assertEquals("$classes inpsyde-gutenberg-1240", $result);
    }

    /**
     * @test
     */
    public function can_filter_array(): void
    {
        \Mockery::mock('alias:' . GutenbergVersions\Loader::class, [
            'matchingVersion' => '12.4.0',
        ]);
        /* Not necessary to mock a valid `forTheme` return value
         * because of the alias set to the Loader.
         */
        $supportedVersions = \Mockery::mock(Service\SupportedVersions::class, [
            'forTheme' => [],
        ]);
        $bodyClassesFilter = Service\BodyClassesFilter::new($supportedVersions);

        $classes = ['custom-classname'];
        $result = $bodyClassesFilter->addGutenbergVersionClass($this->theme, $classes);
        $this->assertEquals([...$classes, 'inpsyde-gutenberg-1240'], $result);
    }

    /**
     * @test
     */
    public function filter_not_applied_because_no_versions_supported(): void
    {
        \Mockery::mock('alias:' . GutenbergVersions\Loader::class, [
            'matchingVersion' => null,
        ]);
        /* Not necessary to mock a valid `forTheme` return value
         * because of the alias set to the Loader.
         */
        $supportedVersions = \Mockery::mock(Service\SupportedVersions::class, [
            'forTheme' => [],
        ]);
        $bodyClassesFilter = Service\BodyClassesFilter::new($supportedVersions);

        $classes = ['custom-classname'];
        $result = $bodyClassesFilter->addGutenbergVersionClass($this->theme, $classes);
        $this->assertEquals($classes, $result);
    }
}
