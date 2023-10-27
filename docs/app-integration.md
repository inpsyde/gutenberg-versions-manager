# Application Integration

The Mu Plugin is a Wp App Container Package and as such shall be consumed at application level.

Whatever way you boot your application what you need to do is simply add the Package to the application.

```php
\Inpsyde\App\Bootstrap\app()->addPackage(
    \Inpsyde\GutenbergVersionsManager\Package::new()
);
```

The Package will read the current theme `config/gutenberg.php` file to retrieve the list of the compatible Gutenberg versions.

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
