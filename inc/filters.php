<?php
/**
 * Filters
 *
 * @package touchstones-museum
 * @since 1.0.0
 */

/**
 * Show '(No title)' if a post has no title.
 *
 * @since 1.0.0
 */
add_filter(
	'the_title',
	function( $title ) {
		if ( ! is_admin() && empty( $title ) ) {
			$title = _x( '(No title)', 'Used if post or pages has no title', 'touchstones-museum' );
		}

		return $title;
	}
);

/**
 * Replace the default [...] excerpt more with an elipsis.
 *
 * @since 1.0.0
*/
// add_filter(
// 	'excerpt_more',
// 	function( $more ) {
// 		return '&hellip;';
// 	}
// );

add_filter( 'excerpt_more', '__return_empty_string' ); 
remove_filter('get_the_excerpt', 'wp_trim_excerpt');


/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	return site_url('store');
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );