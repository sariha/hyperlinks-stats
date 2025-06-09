<?php
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching

/**
 * Plugin Links class
 *
 * @package     hyperlinks-stats
 * @author      Sariha Chabert
 * @license     GPL-2.0-or-later
 */

namespace ROCKET_HYPERLINKS_STATS;

/**
 * Class to manage plugin links
 *
 * - Save links in the database.
 * - Display links in the admin panel.
 * - Remove outdated links.
 */
class Links {

	/**
	 * Creates the database table for hyperlinks stats if it doesn't exist.
	 *
	 * @return void
	 */
	public static function create_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'hyperlinks_stats';
		$charset_collate = $wpdb->get_charset_collate();

		// Create the table.
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			url varchar(255) NOT NULL,
			screen_size varchar(255) NOT NULL,
			date_created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id),
			KEY url (url)
		) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
	}

	/**
	 * Saves a link visit to the database.
	 *
	 * @param string $url         The URL being tracked.
	 * @param string $screen_size The screen size of the device.
	 *
	 * @return int|false The ID of the inserted record, or false on failure.
	 */
	public static function save_link( string $url, string $screen_size ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'hyperlinks_stats';

		$result = $wpdb->insert(
			$table_name,
			array(
				'url'         => sanitize_text_field( $url ),
				'screen_size' => sanitize_text_field( $screen_size ),
			),
			array( '%s', '%s' )
		);

		return $result ? $wpdb->insert_id : false;
	}

	/**
	 * Gets all links from the database with optional filters.
	 *
	 * @param array $args Optional. Query arguments.
	 *
	 * @return array Array of link records.
	 */
	public static function get_links( array $args = array() ): array {
		global $wpdb;

		$table_name = $wpdb->prefix . 'hyperlinks_stats';

		$defaults = array(
			'limit'   => 100,
			'offset'  => 0,
			'orderby' => 'date_created',
			'order'   => 'DESC',
		);

		$args = wp_parse_args( $args, $defaults );

		return $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM %s ORDER BY %s %s LIMIT %d OFFSET %d',
				$table_name,
				$args['orderby'],
				$args['order'],
				$args['limit'],
				$args['offset']
			)
		);
	}

	/**
	 * Removes links older than the specified number of days.
	 *
	 * @param int $days Number of days after which to remove links.
	 * @return int|false Number of rows affected, or false on error.
	 */
	public static function remove_outdated_links( $days = 7 ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'hyperlinks_stats';

		return $wpdb->query(
			$wpdb->prepare(
				'DELETE FROM %s WHERE date_created < DATE_SUB(NOW(), INTERVAL %d DAY)',
				$table_name,
				$days
			)
		);
	}
}
