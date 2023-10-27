<?php

/*
 * Plugin Name: Gutenberg Versions Manager Mu Plugin
 * Description: A Mu Plugin to test the functionality of the Gutenberg Versions Manager.
 * Author: Inpsyde
 * Version: 1.0.0.0
 */

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager;

$autoloader = WP_CONTENT_DIR . '/mu-plugins/gutenberg-versions-manager/vendor/autoload.php';

if (\file_exists($autoloader)) {
    require_once $autoloader;
}
if (!\class_exists('Inpsyde\\App\\App')) {
    return;
}

$app = \Inpsyde\App\App::new()->addPackage(
    \Inpsyde\GutenbergVersionManager\Package::new()
);

\add_action('muplugins_loaded', static fn () => $app->boot());
