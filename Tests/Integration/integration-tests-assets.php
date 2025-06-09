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

	public function testShouldReturnCorrectFileNames() {
		// Create an instance of the Assets class
		$assets = new Assets();

		// Call the get_file_names method with a test JS file name
		$result = $assets->get_file_names('test.js');

		// Assert that the returned array has the expected structure and values
		$this->assertIsArray($result);
		$this->assertArrayHasKey('filename', $result);
		$this->assertArrayHasKey('js_file', $result);
		$this->assertArrayHasKey('php_file', $result);

		// Assert that the values are correct
		$this->assertEquals('test', $result['filename']);
		$this->assertEquals('test.js', $result['js_file']);
		$this->assertEquals('test.php', $result['php_file']);
	}

}
