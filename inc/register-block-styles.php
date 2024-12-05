<?php
/**
 * Block styles.
 *
 * @package touchstones-museum
 * @since 1.0.0
 */

/**
 * Register block styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function touchstones_museum_register_block_styles() {

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/template-part',
		array(
			'name'  => 'touchstones-museum-sticky',
			'label' => __( 'Sticky header', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/button',
		array(
			'name'  => 'touchstones-museum-flat-button',
			'label' => __( 'Flat button', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/button',
		array(
			'name'  => 'touchstones-museum-button-shadow',
			'label' => __( 'Button with shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/navigation',
		array(
			'name'  => 'touchstones-museum-navigation-button',
			'label' => __( 'Button style', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/navigation',
		array(
			'name'  => 'touchstones-museum-navigation-button-shadow',
			'label' => __( 'Button with shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/list',
		array(
			'name'  => 'touchstones-museum-list-underline',
			'label' => __( 'Underlined list items', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/group',
		array(
			'name'  => 'touchstones-museum-box-shadow',
			'label' => __( 'Box shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/column',
		array(
			'name'  => 'touchstones-museum-box-shadow',
			'label' => __( 'Box shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/columns',
		array(
			'name'  => 'touchstones-museum-box-shadow',
			'label' => __( 'Box shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/site-title',
		array(
			'name'  => 'touchstones-museum-text-shadow',
			'label' => __( 'Text shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/post-title',
		array(
			'name'  => 'touchstones-museum-text-shadow',
			'label' => __( 'Text shadow', 'touchstones-museum' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/heading',
		array(
			'name'  => 'touchstones-museum-text-shadow',
			'label' => __( 'Text shadow', 'touchstones-museum' ),
		)
	);
}
add_action( 'init', 'touchstones_museum_register_block_styles' );

/**
 * This is an example of how to unregister a core block style.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 * @see https://github.com/WordPress/gutenberg/pull/37580
 *
 * @since 1.0.0
 *
 * @return void
 */
function touchstones_museum_unregister_block_style() {
	wp_enqueue_script(
		'touchstones-museum-unregister',
		get_stylesheet_directory_uri() . '/assets/js/unregister.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		TOUCHSTONES_MUSEUM_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'touchstones_museum_unregister_block_style' );
