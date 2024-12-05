<?php

/**
 * Custom scripts and styles.
 *
 * @package touchstonesFSE
 */

/**
 * Enqueue scripts and styles.
 *
 * @author WebDevStudios
 */
function wds_acme_scripts()
{
	$asset_file_path = dirname(__DIR__) . '/build/index.asset.php';

	if (is_readable($asset_file_path)) {
		$asset_file = include $asset_file_path;
	} else {
		$asset_file = [
			'version'      => '1.0.0',
			'dependencies' => ['wp-polyfill'],
		];
	}

	// Register styles & scripts.
	wp_enqueue_style('wd_s', get_stylesheet_directory_uri() . '/build/index.css', [], $asset_file['version']);
	wp_enqueue_script('wds-scripts', get_stylesheet_directory_uri() . '/build/index.js', $asset_file['dependencies'], $asset_file['version'], true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'wds_acme_scripts');

/**
 * Inline Critical CSS.
 *
 * @author Corey Collins
 */
function wds_acme_critical_css()
{
?>
	<style>
		<?php include get_stylesheet_directory() . '/build/critical.css'; ?>
	</style>
<?php
}
add_action('wp_head', 'wds_acme_critical_css', 1);

/**
 * Preload styles and scripts.
 *
 * @author WebDevStudios
 */
function wds_acme_preload_scripts()
{
	$asset_file_path = dirname(__DIR__) . '/build/index.asset.php';

	if (is_readable($asset_file_path)) {
		$asset_file = include $asset_file_path;
	} else {
		$asset_file = [
			'version'      => '1.0.0',
			'dependencies' => ['wp-polyfill'],
		];
	}

?>
	<link rel="preload" href="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/build/index.css?ver=<?php echo esc_html($asset_file['version']); ?>" as="style">
	<link rel="preload" href="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/build/index.js?ver=<?php echo esc_html($asset_file['version']); ?>" as="script">
<?php
}
add_action('wp_head', 'wds_acme_preload_scripts', 1);

/**
 * Preload assets.
 *
 * @author Corey Collins
 */
function wds_acme_preload_assets()
{
?>
	<?php if (wds_acme_get_custom_logo_url()) : ?>
		<link rel="preload" href="<?php echo esc_url(wds_acme_get_custom_logo_url()); ?>" as="image">
	<?php endif; ?>
<?php
}
add_action('wp_head', 'wds_acme_preload_assets', 1);

// remove woocommerce styling

// add_filter('woocommerce_enqueue_styles', '__return_empty_array');
/**
 * Set WooCommerce image dimensions upon theme activation
 */
// Remove each style one by one
add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
function jk_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	return $enqueue_styles;
}

// Or just remove them all in one line
// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
