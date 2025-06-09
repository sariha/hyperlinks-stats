<?php
/**
 * Plugin main class
 *
 * @package     hyperlinks-stats
 * @author      Sariha Chabert
 * @license     GPL-2.0-or-later
 */

namespace ROCKET_HYPERLINKS_STATS;

/**
 * Main plugin class. It manages initialization, install, and activations.
 */
class Plugin {
	/**
	 * Manages plugin initialization
	 *
	 * @return void
	 */
	public function __construct() {
		// Register plugin lifecycle hooks.
		register_deactivation_hook( ROCKET_HYPERLINKS_STATS_PLUGIN, array( $this, 'rhs_deactivate' ) );

		// Register menu.
		$this->rhs_menu_init();
	}

	/**
	 * Handles plugin activation:
	 *
	 * @return void
	 */
	public static function rhs_activate() {
		// Security checks.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
		check_admin_referer( "activate-plugin_{$plugin}" );

		// Create the database table for hyperlinks stats.
		Links::create_table();

		// Schedule the daily cleanup task.
		if ( ! wp_next_scheduled( 'hyperlinks_stats_daily_cleanup' ) ) {
			$time = strtotime( 'tomorrow midnight' );
			wp_schedule_event(
				$time,
				'daily',
				'hyperlinks_stats_daily_cleanup'
			);
		}
	}

	/**
	 * Handles plugin deactivation
	 *
	 * @return void
	 */
	public function rhs_deactivate() {
		// Security checks.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
		check_admin_referer( "deactivate-plugin_{$plugin}" );
	}

	/**
	 * Handles plugin uninstall
	 *
	 * @return void
	 */
	public static function rhs_uninstall() {

		// Security checks.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
	}

	/**
	 * Initializes the admin menu for the plugin.
	 *
	 * This method adds a menu page to the WordPress admin dashboard for the plugin.
	 * The page will be accessible under the "Findstr" menu item.
	 *
	 * @return void
	 */
	private function rhs_menu_init() {
		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					'Hyperlinks Stats',
					'Hyperlinks Stats',
					'manage_options',
					'hyperlinks-stats',
					function () {
						include_once ROCKET_HYPERLINKS_STATS_PLUGIN_DIR . 'views/admin/index.php';
					},
					'dashicons-admin-links',
				);
			}
		);
	}
}
