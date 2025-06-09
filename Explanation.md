# Hyperlinks Stats

Get the last 7 days statistics about the hyperlinks above the fold in the frontpagwe of a website.

## How it works


### Frontend Script

The javascript code is compiled from `assets/front.js` using `@wordpress/scripts` and Webpack to `build/front.js`. It also generates a `build/front.asset.php` file that contains the dependencies for WordPress to load the script correctly. The asset file is enqueued with `Assets` class, wich takes care of the external dependencies and versioning. The script is loaded on frontpage only, in the footer, to avoid blocking the rendering of the page.


The script run on frontpage of a website and collect the hyperlinks above the fold.
It uses the `IntersectionObserver` API to detect which links are above the fold taking into account the visible links only, so links that are not visible when the page is loaded are not counted.

Then the data is sent to the server using the `apiFetch` API to the WordPress REST API endpoint.

### REST endpoint

Rest endpoint is registered in the `Rest` class.
 
### Links Class
I choosed to use a class to handle the links data. A database table is created to store the links data when plugin is activated. 
In this class I choosed to ignore WordPress.DB.DirectDatabaseQuery.DirectQuery PHPCS rule since the data is stored in a custom table.
