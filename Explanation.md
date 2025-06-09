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
 
### Links Class
I choosed to use a class to handle the links data. A database table is created to store the links data when plugin is activated. 
In this class I choosed to ignore WordPress.DB.DirectDatabaseQuery.DirectQuery PHPCS rule since the data is stored in a custom table.

### Admin Page
The admin page is registered in the `Plugin` class. It uses the `add_menu_page` function to create a new menu item in the WordPress admin sidebar. The page displays the links data stored in the database and allows the user to filter the data by date range.

WordPress default styles are used to display the data in a table format, I would do it in react if I had more time, but I wanted to keep it simple and use the default WordPress styles.

While window sizes are stored in the database, I didn't implement a way to display them in the admin page. I planned to add a chart to display the data, but I ran out of time. It would be a nice feature to add in the future.
