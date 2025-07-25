<?php
/**
 *  Implements the Integration test set for the plugin management.
 *
 * @package     TO FILL
 * @since       TO FILL
 * @author      Mathieu Lamiot
 * @license     GPL-2.0-or-later
 */

namespace ROCKET_HYPERLINKS_STATS;

require_once dirname(dirname(__DIR__)) . "/plugin.php";

use WPMedia\PHPUnit\Integration\TestCase;
use Brain\Monkey\Functions;

/**
 * Integration test set for the Webplan Updater Cron Class.
 */
class Rocket_Hyperlinks_Stats_Plugin_Integration_Test extends TestCase {

	/**
     * Checks the call to plugin init function on plugin_loaded.
     */
    public function testShouldLoadPlugin() {
		  Functions\expect(__NAMESPACE__ . '\rocket_hyperlinks_stats_plugin_init')->once();
		  do_action('plugins_loaded');
    }
}
