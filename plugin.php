<?php
/**
 * Plugin Name: Hyperlinks Stats
 *
 * @package     hyperlinks-stats
 * @author      Sariha Chabert
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Hyperlinks Stats
 * Version: 0.1.0
 * Description: A plugin to track hyperlinks in WordPress.
 * Author: Sariha Chabert <sariha.chabert@gmail.com>
 */

namespace ROCKET_HYPERLINKS_STATS;

define( 'ROCKET_HYPERLINKS_STATS_PLUGIN', __FILE__ ); // Filename of the plugin, including the file.

if ( ! defined( 'ABSPATH' ) ) { // If WordPress is not loaded.
	exit( 'WordPress not loaded. Can not load the plugin' );
}

// Load the dependencies installed through composer.
require_once __DIR__ . '/src/plugin.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/support/exceptions.php';

// Plugin initialization.
/**
 * Creates the plugin object on plugins_loaded hook
 *
 * @return void
 */
function rocket_hyperlinks_stats_plugin_init() {
	new Rocket_Hyperlinks_Stats_Plugin_Class();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\rocket_hyperlinks_stats_plugin_init' );

register_activation_hook( __FILE__, array( __NAMESPACE__ . '\Rocket_Hyperlinks_Stats_Plugin_Class', 'rhs_activate' ) );

register_uninstall_hook( __FILE__, array( __NAMESPACE__ . '\Rocket_Hyperlinks_Stats_Plugin_Class', 'rhs_uninstall' ) );
