<?php
/**
 * Integration test for the Assets class.
 *
 * @package     hyperlinks-stats
 * @author      Sariha Chabert
 * @license     GPL-2.0-or-later
 */

namespace ROCKET_HYPERLINKS_STATS;

require_once dirname(dirname(__DIR__)) . "/plugin.php";

use WPMedia\PHPUnit\Integration\TestCase;
use Brain\Monkey\Functions;
/**
 * Integration test for the Assets class.
 */
class Assets_Integration_Test extends TestCase {

	/**
	 * Tests that the plugin assets are enqueued correctly.
	 *
	 * @return void
	 */
	public function testShouldEnqueuePluginAssets() {
		Functions\expect( __NAMESPACE__ . '\rocket_hyperlinks_stats_assets' )->once();
		do_action( 'init' );
	}
}
