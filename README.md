# Hyperlink

Above the fold links statistics for your WordPress.

# Get started with development
Use [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) start to start a local WordPress environment
- Install dependencies with `npm install`
- Compile assets with `npm run build` or `npm start`

- Run `composer install` to install PHP dependencies
- Start with `wp-env start` -> this will create a local WordPress environment with the plugin activated, an error will occur because composer is not installed yet.
- Run `npm run run-tests` will run the composer tests
- Run `npm run composer phpcs`

Finally, navigate to http://localhost:8888 in your web browser to see WordPress running with the local WordPress plugin or theme running and activated. Default login credentials are username: admin password: password.

# Content
* `bin/install-wp-tests.sh`: installer for WordPress tests suite
* `.editorconfig`: config file for your IDE to follow our coding standards
* `.gitattributes`: list of directories & files excluded from export
* `.gitignore`: list of directories & files excluded from versioning
* `composer.json`: Base composer file to customize for the project
* `LICENSE`: License file using GPLv3
* `phpcs.xml`: Base PHP Code Sniffer configuration file to customize for the project
* `README.md`: The readme displayed on Github, to customize for the project
