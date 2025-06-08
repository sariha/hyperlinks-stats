<?php
/**
 * Plugin Assets class
 *
 * @package     hyperlinks-stats
 * @author      Sariha Chabert
 * @license     GPL-2.0-or-later
 */

namespace ROCKET_HYPERLINKS_STATS;

/**
 * Class to manage plugin assets
 */
class Assets {


	/**
	 * Constructor to initialize the plugin assets.
	 *
	 * This method sets up the necessary hooks to enqueue scripts and styles.
	 */
	public function __construct() {
		// Enqueue the plugin scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'rhs_enqueue_plugin_scripts' ) );
	}

	/**
	 * Enqueues the plugin scripts and styles.
	 * This method retrieves the manifest file content to determine the correct paths for the JavaScript and PHP files.
	 * It then enqueues the JavaScript file with the necessary dependencies
	 *
	 * @return void
	 */
	public function rhs_enqueue_plugin_scripts() {

		// Enqueue in frontpage only.
		if ( is_front_page() ) {
			$this->enqueue_script( 'front.js' );
		}
	}


	/**
	 * Enqueues a JavaScript file for the plugin.
	 *
	 * This method checks if the JavaScript file exists in the manifest and enqueues it with the necessary dependencies.
	 * It also localizes the script with the REST API URL for the plugin.
	 *
	 * @param string $js_file The name of the JavaScript file to enqueue.
	 * @param array  $data    Additional data to pass to the script (optional).
	 *
	 * @return void
	 */
	public function enqueue_script( string $js_file, array $data = array() ) {

		$manifest = $this->get_manifest_file_content();

		['filename' => $filename, 'js_file' => $js_file, 'php_file' => $php_file] = $this->get_file_names( $js_file );

		$filename = str_replace( '.js', '', $js_file );
		$php_file = $filename . '.php';

		if ( isset( $manifest['files'][ $js_file ] ) && ! empty( $manifest ) ) {
			$js_file = 'build/' . basename( $manifest['files'][ $js_file ] );
		}

		$assets_file = "build/${filename}.asset.php";
		if ( isset( $manifest['files'][ $php_file ] ) && ! empty( $manifest ) ) {
			$assets_file = 'build/' . basename( $manifest['files'][ $php_file ] );
		}

		if ( is_file( ROCKET_HYPERLINKS_STATS_PLUGIN_DIR . $assets_file ) ) {
			$assets = include_once ROCKET_HYPERLINKS_STATS_PLUGIN_DIR . $assets_file;

			$dependencies = $assets['dependencies'];
			$version      = $assets['version'];

			wp_enqueue_script(
				'hyperlinks-stats-' . $filename,
				plugins_url( $js_file, ROCKET_HYPERLINKS_STATS_PLUGIN ),
				$dependencies,
				$version,
				array(
					'in_footer' => true,
				)
			);

			if ( ! empty( $data ) ) {
				wp_localize_script(
					'hyperlinks-stats-' . $filename,
					'hyperlinksStats',
					$data
				);
			}
		}
	}

	/**
	 * Retrieves the content of the manifest file.
	 *
	 * @return array The content of the manifest file as an associative array.
	 */
	private function get_manifest_file_content(): array {
		$manifest_file = ROCKET_HYPERLINKS_STATS_PLUGIN_DIR . 'build/assets.json';

		if ( file_exists( $manifest_file ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			return json_decode( $wp_filesystem->get_contents( $manifest_file ), true );
		}

		return array();
	}

	/**
	 * Returns the file names for the given JavaScript file.
	 *
	 * This method generates the used variables 'filename', 'js_file', and 'php_file'
	 * based on the provided JavaScript file name.
	 *
	 * @param string $js_file The name of the JavaScript file.
	 *
	 * @return array An associative array containing 'filename', 'js_file', and 'php_file'.
	 */
	private function get_file_names( string $js_file ): array {

		$filename = str_replace( '.js', '', $js_file );
		$php_file = $filename . '.php';

		return array(
			'filename' => $filename,
			'js_file'  => $js_file,
			'php_file' => $php_file,
		);
	}
}
