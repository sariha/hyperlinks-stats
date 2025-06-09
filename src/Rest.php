<?php
/**
 * Plugin Rest class
 *
 * @package     hyperlinks-stats
 * @author      Sariha Chabert
 * @license     GPL-2.0-or-later
 */

namespace ROCKET_HYPERLINKS_STATS;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Class to manage plugin REST API endpoints
 */
class Rest {

	/**
	 * The namespace for the plugin REST API.
	 *
	 * This is used to define the base URL for the plugin's REST API endpoints.
	 *
	 * @var string
	 */
	public $namespace = 'hyperlinks-stats/v1';

	/**
	 * Constructor to initialize the plugin REST API endpoints.
	 *
	 * This method sets up the necessary hooks to register the REST API routes.
	 */
	public function __construct() {
		$this->rhs_register_rest_routes();
	}

	/**
	 * Registers the REST API routes for the plugin.
	 *
	 * @return void
	 */
	public function rhs_register_rest_routes() {
		register_rest_route(
			$this->namespace,
			'/stats',
			array(
				'methods'             => 'POST',
				'callback'            => function ( $request ) {
					return $this->rhs_post_stats( $request );
				},
				'permission_callback' => function ( $request ) {
					return wp_verify_nonce( $request->get_header( 'X-WP-Nonce' ), 'wp_rest' );
				},
			)
		);
	}


	/**
	 * Handles the POST request to the /stats endpoint.
	 *
	 * @param WP_REST_Request $request The REST request object containing parameters.
	 *
	 * @return WP_REST_Response The response object to return to the REST client.
	 */
	public function rhs_post_stats( $request ): WP_REST_Response {

		$links       = $request->get_param( 'links' );
		$window_size = wp_json_encode( $request->get_param( 'windowSize' ) );

		if ( ! is_array( $links ) ) {
			return rest_ensure_response(
				array(
					'status'  => 'error',
					'message' => 'Invalid links parameter. It should be an array.',
				)
			);
		}

		if ( empty( $links ) ) {
			return rest_ensure_response(
				array(
					'status'  => 'error',
					'message' => 'Links parameter cannot be empty.',
				)
			);
		}

		foreach ( $links as $link ) {
			Links::save_link(
				$link,
				$window_size
			);
		}

		return rest_ensure_response(
			array(
				'status'  => 'success',
				'message' => 'Links stats received successfully.',
			)
		);
	}
}
