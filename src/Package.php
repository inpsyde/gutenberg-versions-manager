<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager;

use Inpsyde\App;

class Package implements App\Provider\Package
{
    public static function new(): Package
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function providers(): App\Provider\ServiceProviders
    {
        return App\Provider\ServiceProviders::new()
            ->add(Provider\ServiceIntegration::new());
    }
}
