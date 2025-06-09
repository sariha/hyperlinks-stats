# Hyperlinks Stats

Collect statistics about the visible hyperlinks above the fold in the frontpage of a WordPress website, for the past 7 days.


## How it works

### Frontend Script

The javascript code is compiled from `assets/front.js` using `@wordpress/scripts` and Webpack to `build/front.js`. It also generates a `build/front.asset.php` file that contains the dependencies for WordPress to load the script correctly. The asset file is enqueued with `Assets` class, wich takes care of the external dependencies and versioning. The script is loaded on frontpage only, in the footer, to avoid blocking the rendering of the page.

The script run on frontpage of a website and collect the hyperlinks above the fold.
It uses the `IntersectionObserver` API to detect which links are above the fold taking into account the visible links only, so links that are not visible when the page is loaded are not counted.

Then the data is sent to the server using the `apiFetch` API to the WordPress REST API endpoint.

### REST endpoint

Rest endpoint is registered in the `Rest` class. It handles the incoming data from the frontend script and stores it in the database using the `Links` class. The endpoint is registered with the `register_rest_route` function, which allows us to define a custom route for our API.

### Storage
I decided to use a custom database table to store the links data. This allows us to have more control over the data and avoid using options or CPT to store data. The table is created when the plugin is activated using the `register_activation_hook` function in the `Plugin` class. The plugin should remove the table when deactivated, but it is not implemented in this version.
 
### Links Class
I choosed to use a class to handle the links data with custom query methods to write and read the data. This class use mostly static methods to avoid instantiating the class every time we need to access the data. 
In this class I choosed to ignore two PHPCS rules ( `WordPress.DB.DirectDatabaseQuery.DirectQuery` and `WordPress.DB.DirectDatabaseQuery.NoCaching` ) since they are custom queries. \
That being said, I used prepared statements to avoid SQL injection and ensure the security of the queries. I also set a large limit of 120 results, which is more than enough for the use case of this plugin, since we only collect data for the past 7 days.


### Admin Page
The admin page is registered in the `Plugin` class. It uses the `add_menu_page` function to create a new menu item in the WordPress admin sidebar. The page displays the links data stored in the database and allows the user to filter the data by date range.

WordPress default styles are used to display the data in a table format, I would do it in react if I had more time, but I wanted to keep it simple and use the default WordPress styles.

While window sizes are stored in the database, I didn't implement a way to display them in the admin page. I planned to add a chart to display the data, but I ran out of time. It would be a nice feature to add in the future.

## What could be improved

- **Error Handling**: The plugin currently lacks error handling for the REST API requests and database operations. Implementing proper error handling would improve the user experience and make debugging easier.
- **Logging**: There is no logging mechanism in place to track errors or important events. Adding a logging system like Monolog would help in monitoring the plugin's performance and diagnosing issues.
- **Testing**: The plugin lakes of automated tests. Adding more unit tests and integration tests would help ensure the plugin's reliability and prevent regressions in future updates.


## About development environment
 I used the `@wordpress/env` package to set up a local WordPress environment for development and testing. This allows me to quickly spin up a WordPress instance with the plugin activated and test the functionality in a controlled environment.

 I also imported data from [ThemeUnitTestData](https://github.com/WordPress/theme-test-data/blob/master/themeunittestdata.wordpress.xml) and used default Twenty Twenty-Five theme to test the plugin.

Tests are passed with `npm run run-tests` command, while wp-env is running.
