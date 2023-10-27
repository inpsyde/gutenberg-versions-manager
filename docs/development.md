# Development

The project rely on [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/), and it contains all the necessary configuration to have the development environment up and running.

Simply run `yarn wp-env:start` or `yarn wp-env:start --xdebug` and you're good to go.

To stop the environment run `yarn wp-env:stop`

The development configuration includes the followings within the `.development` directory at the root of the project.

- A small theme which is configured to require a compatible Gutenberg version
- A Mu Plugin which is configured to load the Gutenberg Version Manager Package
