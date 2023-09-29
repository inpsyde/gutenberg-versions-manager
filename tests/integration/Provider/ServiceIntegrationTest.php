<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Tests\Integration\Provider;

use Inpsyde\App;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Inpsyde\GutenbergVersionManager\Provider;
use Inpsyde\GutenbergVersionManager\Tests\UnitTestCase;

class ServiceIntegrationTest extends UnitTestCase
{
    private App\Container $container;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = new App\Container();
    }

    /**
     * @test
     */
    public function when_gutenberg_plugin_is_active_show_admin_notices(): void
    {
        $serviceIntegration = Provider\ServiceIntegration::new();
        $this->withGutenbergPluginActive();
        Actions\expectAdded('admin_notices');
        $serviceIntegration->boot($this->container);
    }

    /**
     * @test
     */
    public function when_gutenberg_plugin_is_active_service_do_not_bootstrap(): void
    {
        $serviceIntegration = Provider\ServiceIntegration::new();
        $this->withGutenbergPluginActive();
        $this->assertEquals(false, $serviceIntegration->boot($this->container));
    }

    /**
     * @test
     */
    public function cannot_activate_for_current_theme_with_no_support(): void
    {
        $serviceIntegration = Provider\ServiceIntegration::new();
        $this->withoutThemeSupport();
        Actions\expectAdded('admin_notices');
        $this->assertEquals(false, $serviceIntegration->boot($this->container));
    }

    /**
     * @test
     */
    public function custom_gutenberg_version_is_activated(): void
    {
        $serviceIntegration = Provider\ServiceIntegration::new();
        $this->withActiveGutenberg();
        Actions\expectAdded('admin_notices')->never();
        $serviceIntegration->boot($this->container);
    }

    /**
     * @test
     */
    public function add_backoffice_and_frontoffice_body_classes(): void
    {
        $serviceIntegration = Provider\ServiceIntegration::new();
        $this->withActiveGutenberg();
        Filters\expectAdded('body_class')->once();
        Filters\expectAdded('admin_body_class')->once();
        $serviceIntegration->boot($this->container);
    }
}
