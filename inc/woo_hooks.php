<?php
/*
	Woocommerce hooks for single pages and other items
*/
remove_filter( 'woocommerce_loop_add_to_cart_link', 'xoo_wsc_suggested_product_addtocart_link', 999, 3 );


/*############# Single Product Page ##############*/
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 6 );
function woocommerce_template_price_and_cart_wrap_open(){
	echo '<div class="price-cart-wrap-open flex is-content-justification-space-around">';
}
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_price_and_cart_wrap_open', 5 );
function woocommerce_template_price_and_cart_wrap_close(){
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_price_and_cart_wrap_close', 15 );
add_filter('woocommerce_paypal_payments_single_product_renderer_hook', function() {
    return 'woocommerce_after_add_to_cart_form';
});
add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_address_my_account', 999 );
 
function bbloomer_remove_address_my_account( $items ) {
   unset( $items['edit-address'] );
   return $items;
}
 
// -------------------------------
// 2. Second, print the ex tab content (woocommerce_account_edit_address) into an existing tab (woocommerce_account_edit-account_endpoint). See notes below!
 
add_action( 'woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_address' );

add_action( 'woocommerce_account_downloads_columns', 'custom_downloads_columns', 10, 1 ); // Orders and account
add_action( 'woocommerce_email_downloads_columns', 'custom_downloads_columns', 10, 1 ); // Email notifications
function custom_downloads_columns( $columns ){
    // Removing "Download expires" column
    if(isset($columns['download-expires']))
        unset($columns['download-expires']);

    // Removing "Download remaining" column
    if(isset($columns['download-remaining']))
        unset($columns['download-remaining']);

    return $columns;
}

/**
 *
 *  @author     Christopher Davies, WP Davies
 *  @link       https://wpdavies.dev/
 *  @link       https://wpdavies.dev/automatically-complete-virtual-orders-woocommerce/
 *  @snippet    Automatically complete orders in WooCommerce if virtual
 *
 */
add_action('woocommerce_thankyou', 'wpd_autocomplete_virtual_orders', 10, 1 );
function wpd_autocomplete_virtual_orders( $order_id ) {
  
    if( ! $order_id ) return;
  
    // Get order
    $order = wc_get_order( $order_id );
  
    // get order items = each product in the order
    $items = $order->get_items();
  
    // Set variable
    $only_virtual = true;
  
    foreach ( $items as $item ) {
          
        // Get product object
        if ( isset($item['variation_id']) && ! empty($item['variation_id']) ) {
 
            $product = wc_get_product( $item['variation_id'] );
 
        } else {
 
            $product = wc_get_product( $item['product_id'] );
 
        }
 
        // Safety check
        if ( ! is_object($product) ) {
 
            return false;
 
        }
                 
        // Is virtual
        $is_virtual = $product->is_virtual();
  
        // Is_downloadable
        $is_downloadable = $product->is_downloadable();
  
        if ( ! $is_virtual && ! $is_downloadable  ) {
  
            $only_virtual = false;
  
        }
  
    }
  
    // true
    if ( $only_virtual ) {
  
        $order->update_status( 'completed' );
  
    }
 
}

