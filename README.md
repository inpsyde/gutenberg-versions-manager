# Gutenberg Versions Manager

![banner.png](./docs/img/banner.png)

[![PHP Quality Assurance](https://github.com/inpsyde/gutenberg-versions-manager/actions/workflows/php-qa.yml/badge.svg)](https://github.com/inpsyde/gutenberg-versions-manager/actions/workflows/php-qa.yml)

The Gutenberg Version Manager is a [Wp App Container](https://github.com/inpsyde/wp-app-container) Package allowing themes to specify a list of compatible Gutenberg Versions and load the first compatible one.

The package is served as a WordPress Mu Plugin and is installed under the appropriate WP directory.

## Documentation

1. [Development](./docs/development.md)
2. [App Integration](./docs/app-integration.md)

## Requirements

This package depends on [Gutenberg Versions Mirror](https://github.com/inpsyde/gutenberg-versions-mirror) to load the compatible version of Gutenberg.

* PHP >= 8.0
* WordPress >= 6.2

---
This repository is a free software, and is released under the terms of the GNU General Public License version 2 or (at your option) any later version. See [LICENSE](./LICENSE) for complete license.
