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
}
