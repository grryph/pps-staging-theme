<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package touchstonesFSE
 */

//* TN - Remove Query String from Static Resources
function tn_remove_css_js_ver( $src ) {
	if( strpos( $src, '?ver=' ) )
	$src = remove_query_arg( 'ver', $src );
	return $src;
  }
  
  function tn_remove_css_js() {
	if (!is_admin()) {
	  add_filter( 'style_loader_src', 'tn_remove_css_js_ver', 10, 2 );
	  add_filter( 'script_loader_src', 'tn_remove_css_js_ver', 10, 2 );
	}
  }
  add_action('init', 'tn_remove_css_js');


/**
 * Returns true if a blog has more than 1 category, else false.
 *
 * @author WebDevStudios
 *
 * @return bool Whether the blog has more than one category.
 */
function wds_acme_categorized_blog() {
	$category_count = get_transient( 'wds_acme_categories' );

	if ( false === $category_count ) {
		$category_count_query = get_categories( [ 'fields' => 'count' ] );

		$category_count = isset( $category_count_query[0] ) ? (int) $category_count_query[0] : 0;

		set_transient( 'wds_acme_categories', $category_count );
	}

	return $category_count > 1;
}

/**
 * Get an attachment ID from it's URL.
 *
 * @author WebDevStudios
 *
 * @param string $attachment_url The URL of the attachment.
 *
 * @return int    The attachment ID.
 */
function wds_acme_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;

	$attachment_id = false;

	// If there is no url, return.
	if ( '' === $attachment_url ) {
		return false;
	}

	// Get the upload directory paths.
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image.
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

		// If this is the URL of an auto-generated thumbnail, get the URL of the original image.
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

		// Remove the upload path base directory from the attachment URL.
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

		// Do something with $result.
		// phpcs:ignore phpcs:ignore WordPress.DB -- db call ok, cache ok, placeholder ok.
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM {$wpdb->posts} wposts, {$wpdb->postmeta} wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = %s AND wposts.post_type = 'attachment'", $attachment_url ) );
	}

	return $attachment_id;
}

/**
 * Shortcode to display copyright year.
 *
 * @author Haris Zulfiqar
 *
 * @param array $atts Optional attributes.
 *     $starting_year Optional. Define starting year to show starting year and current year e.g. 2015 - 2018.
 *     $separator Optional. Separator between starting year and current year.
 *
 * @return string Copyright year text.
 */
function wds_acme_copyright_year( $atts ) {
	// Setup defaults.
	$args = shortcode_atts(
		[
			'starting_year' => '',
			'separator'     => ' - ',
		],
		$atts
	);

	$current_year = gmdate( 'Y' );

	// Return current year if starting year is empty.
	if ( ! $args['starting_year'] ) {
		return $current_year;
	}

	return esc_html( $args['starting_year'] . $args['separator'] . $current_year );
}

add_shortcode( 'wds_acme_copyright_year', 'wds_acme_copyright_year', 15 );

/**
 * Retrieve the URL of the custom logo uploaded, if one exists.
 *
 * @author Corey Collins
 */
function wds_acme_get_custom_logo_url() {

	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( ! $custom_logo_id ) {
		return;
	}

	$custom_logo_image = wp_get_attachment_image_src( $custom_logo_id, 'full' );

	if ( ! isset( $custom_logo_image[0] ) ) {
		return;
	}

	return $custom_logo_image[0];
}

add_filter( 'excerpt_more', '__return_empty_string' ); 

function wpb_demo_shortcode2() { 
	$args = get_posts( array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
					'taxonomy' => 'product_cat',
					'terms' => array(22237, 22269),
					// 'terms' => array(17865, 163, 457),
					'operator' => 'IN'
			)
		),
		'fields' => 'ids',
	)
	);

	$args_2 = get_posts( array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'fields' => 'ids',
	) );
	
	$merged = array_merge( $args, $args_2);

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$query = new WP_Query(array(
		'post_type' => ['product', 'event'],
		'post_status' => 'publish',
        'posts_per_page' => 10,
        'paged' => $paged,
		'post__in' => $merged,
	));

	usort($query->posts, function($a, $b) {
		return strcasecmp( 
				$b->post_date,
				$a->post_date
			);
	});

	ob_start();
	while ($query->have_posts()) {
		//Setup post data
		$query->the_post(  );
		?>
		<div class="row alignwide flex calender-events">
			<div class="column col-1">
				<?php echo get_the_post_thumbnail() ?>
			</div>

			<div class="column col-2">
			<h2> <a style="text-decoration: none;" target="_self" href="<?php the_permalink() ?>"><?php the_title(); ?></a> </h2>
			
			<?php 
				the_excerpt();
				echo get_field('event_date');
			?>
				<div class="item-actions flex">
					<?php
					if(get_post_type() == "product"){
						global $product;?>
						<span class="woocommerce-Price-amount amount">
						<?php
						echo "$" . $product->get_price();
						?>
						</span>
						<?php
						echo apply_filters( 'woocommerce_loop_add_to_cart_link',
							sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button wp-element-button %s product_type_%s">%s</a>',
								esc_url( $product->add_to_cart_url() ),
								esc_attr( $product->get_id() ),
								esc_attr( $product->get_sku() ),
								$product->is_purchasable() ? 'add_to_cart_button' : '',
								esc_attr( $product->get_type() ),
								esc_html( $product->add_to_cart_text() )
							),
						$product );
					}
					?> 
				</div>
				<a class="wp-element-button button" href="<?php the_permalink() ?>">Read More</a>
			</div>
	
		</div>
		<?php
	}?>
	<div class="pagination">
    <?php 
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
    ?>
	</div>
	<?php
	wp_reset_postdata();
	wp_reset_query();
	return ob_get_clean();
}

// register shortcode
add_shortcode('calender-events', 'wpb_demo_shortcode2');


// Add checkbox
function action_woocommerce_product_options_inventory_product_data() {
    // Checkbox
    woocommerce_wp_checkbox( array( 
        'id'             => '_prevent_add_to_cart_button', // Required, it's the meta_key for storing the value (is checked or not)
        'label'          => __( 'Disable Add To Cart', 'woocommerce' ), // Text in the editor label
        'desc_tip'       => false, // true or false, show description directly or as tooltip
        'description'    => __( 'Prevent add to cart', 'woocommerce' ) // Provide something useful here
    ) );
}
add_action( 'woocommerce_product_options_inventory_product_data', 'action_woocommerce_product_options_inventory_product_data', 10, 0 );
        
// Save Field
function action_woocommerce_admin_process_product_object( $product ) {
    // Isset, yes or no
    $checkbox = isset( $_POST['_prevent_add_to_cart_button'] ) ? 'yes' : 'no';

    // Update meta
    $product->update_meta_data( '_prevent_add_to_cart_button', $checkbox );
}
add_action( 'woocommerce_admin_process_product_object', 'action_woocommerce_admin_process_product_object', 10, 1 );

// Is_purchasable (simple)
function filter_woocommerce_is_purchasable( $purchasable, $product ) {
    // Get meta
    $hide_add_to_cart_button = $product->get_meta( '_prevent_add_to_cart_button' );
    
    // Compare
    if ( $hide_add_to_cart_button == 'yes' ) {
        $purchasable = false;
    }

    return $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'filter_woocommerce_is_purchasable', 10, 2 );
