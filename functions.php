<?php
/**
 * touchstonesFSE functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package touchstonesFSE
 */

/**
 * Get all the include files for the theme.
 *
 * @author WebDevStudios
 */
function wds_acme_get_theme_include_files() {
	return [
		'inc/setup.php', // Theme set up. Should be included first.
		'inc/compat.php', // Backwards Compatibility.
		'inc/customizer/customizer.php', // Customizer additions.
		'inc/filters.php',
		'inc/extras.php', // Custom functions that act independently of the theme templates.
		'inc/hooks.php', // Load custom filters and hooks.
		'inc/woo_hooks.php', // Load custom filters and hooks.
		'inc/security.php', // WordPress hardening.
		'inc/scaffolding.php', // Scaffolding.
		'inc/scripts.php', // Load styles and scripts.
		'inc/template-tags.php', // Custom template tags for this theme.
	];
}

foreach ( wds_acme_get_theme_include_files() as $include ) {
	require trailingslashit( get_template_directory() ) . $include;
}

define( 'TOUCHSTONES_MUSEUM_VERSION', wp_get_theme()->get( 'Version' ) );
/* Block theme fix */

add_action( 'wp_enqueue_scripts', function(){

	wp_enqueue_script( 'wc-cart-fragments' );

}, PHP_INT_MAX );



add_action( 'wp_footer', function(){

	?>

	<span class="xoo-wsc-cart-trigger" style="display: none;"></span>

	<script type="text/javascript">

					jQuery(document).ready(function($){

									$(document.body).on( 'wc-blocks_added_to_cart', function(){

													$( document.body ).trigger( 'wc_fragment_refresh' );

													$('.xoo-wsc-cart-trigger').trigger('click');

									} )

					})

	</script>

	<?php

} );

/**
 * Add theme support for block styles and editor style.
 *
 * @since 1.0.0
 *
 * @return void
 */
function touchstones_museum_setup() {
	add_theme_support( 'wp-block-styles' );
	add_editor_style( './assets/css/style-shared.min.css' );

	/*
	 * Load additional block styles.
	 * See details on how to add more styles in the readme.txt.
	 */
	$styled_blocks = [ 'button', 'file', 'latest-comments', 'latest-posts', 'quote', 'search' ];
	foreach ( $styled_blocks as $block_name ) {
		$args = array(
			'handle' => "touchstones-museum-$block_name",
			'src'    => get_theme_file_uri( "src/scss/blocks/$block_name.min.css" ),
			'path'   => get_theme_file_path( "src/scss/blocks/$block_name.min.css" ),
		);
		// Replace the "core" prefix if you are styling blocks from plugins.
		wp_enqueue_block_style( "core/$block_name", $args );
	}

}
// add_action( 'after_setup_theme', 'touchstones_museum_setup' );

/**
 * Enqueue the CSS files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function touchstones_museum_styles() {
	wp_enqueue_style(
		'touchstones-museum-style',
		get_stylesheet_uri(),
		[],
		TOUCHSTONES_MUSEUM_VERSION
	);
	wp_enqueue_style(
		'touchstones-museum-shared-styles',
		get_theme_file_uri( 'assets/css/style-shared.min.css' ),
		[],
		TOUCHSTONES_MUSEUM_VERSION
	);

	wp_enqueue_script(
		'pps-script',
		get_theme_file_uri( 'js/functions.js' ),
		[],
		TOUCHSTONES_MUSEUM_VERSION
	);
}



add_action( 'wp_enqueue_scripts', 'touchstones_museum_styles' );

/* ADD FONTS */

function add_font() {
    $font_script = <<<'EOD'
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
EOD;
    echo $font_script;
}
add_action('wp_head', 'add_font');


/* ADDED ACTIONS */

add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

function add_analytics_head_js() {
	?>

	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-5989226-26"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-5989226-26');
</script>
	
	<?php
}
add_action( 'wp_head', 'add_analytics_head_js', 11 );


// Filters.
// require_once get_theme_file_path( 'inc/filters.php' );

// Block variation example.
require_once get_theme_file_path( 'inc/register-block-variations.php' );

// Block style examples.
require_once get_theme_file_path( 'inc/register-block-styles.php' );

// Block pattern and block category examples.
require_once get_theme_file_path( 'inc/register-block-patterns.php' );


add_filter( 'automatewoo_email_templates', 'my_automatewoo_email_templates' );

function my_automatewoo_email_templates( $templates ) {
    
	// SIMPLE
	// register a template by adding a slug and name to the $templates array
	$templates['workflow-1'] = 'PPS Template';

	return $templates;
}

function reverse_my_downloads_order( $downloads ) {
    return array_reverse( $downloads );
}
add_filter( 'woocommerce_customer_get_downloadable_products', 'reverse_my_downloads_order' );