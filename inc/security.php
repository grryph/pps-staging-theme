<?php
/**
 * Security functions.
 *
 * Enable or disable certain functionality to harden WordPress.
 *
 * @package touchstonesFSE
 */

/**
 * Remove generator meta tags.
 *
 * @author WebDevStudios
 * @see https://developer.wordpress.org/reference/functions/the_generator/
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Disable XML RPC.
 *
 * @author WebDevStudios
 * @see https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Change REST-API header from "null" to "*".
 *
 * @author WebDevStudios
 * @see https://w3c.github.io/webappsec-cors-for-developers/#avoid-returning-access-control-allow-origin-null
 */
function wds_acme_cors_control() {
	header( 'Access-Control-Allow-Origin: *' );
}
add_action( 'rest_api_init', 'wds_acme_cors_control' );
