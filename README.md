# Mh Gutenberg Version Manager

[![PHP Quality Assurance](https://github.com/inpsyde/mh-gutenberg-version-manager/actions/workflows/php-qa.yml/badge.svg)](https://github.com/inpsyde/mh-gutenberg-version-manager/actions/workflows/php-qa.yml)

The Gutenberg Version Manager is a Wp App Container Package which allow Themes to specify a list of
compatible Gutenberg Versions and load the first compatible one.

The Package will look into the current theme `config/gutenberg.php` file to retrieve the list of the compatible Gutenberg
versions.

This package depends on [Gutenberg Versions Mirror](https://github.com/inpsyde/gutenberg-versions-mirror) to load the compatible version of Gutenberg.

Here an example of how the file looks like

```php
<?php

declare(strict_types=1);

return [
    'versions' => [
        '13.9.0',
        '12.8.0',
        '11.14.1',
    ],
];
```

## Application Integration

Just add the Package to the application.

```php
\Inpsyde\App\Bootstrap\app()->addPackage(
    \Inpsyde\GutenbergVersionManager\Package::new()
);
```

## Requirements

* PHP >= 8.0
* WordPress >= 6.2

---
Copyright (c) 2023 Inpsyde GmbH

This software is released under the ["GPL 2.0 or later"](LICENSE) license.
