<?php 
// TO DO write $_GET for product ids get data convert to base 64 and pass into audio block object
if(!isset($_GET['prod']) || !isset($_GET['order'])){
	// redirect to home or something similar
	header("Location: ". home_url());
	exit();
}

$prod_id = $_GET['prod'];
$order_id = $_GET['order'];
$order = new WC_Order($order_id);
$product = wc_get_product( $prod_id );

$output = []; // Initializing

if ( $product->is_downloadable() ) {
    // Loop through WC_Product_Download objects
    foreach( $product->get_downloads() as $key_download_id => $download ) {
        ## Using WC_Product_Download methods (since WooCommerce 3)
        $download_name = $download->get_name(); // File label name
        $download_link = $download->get_file(); // File Url
        $download_id   = $download->get_id(); // File Id (same as $key_download_id)
        $download_type = $download->get_file_type(); // File type
        $download_ext  = $download->get_file_extension(); // File extension
		$download_url = $order->get_download_url($prod_id, $download_id);
		if($download_ext != "mp3"){
			$output[$download_id] = "
		<!-- wp:heading -->
		<h2>" . $download_name . "</h2>
		<!-- /wp:heading -->
				<!-- wp:button -->
				<div class='wp-block-button'><a class='wp-block-button__link wp-element-button' href='$download_url'>Download</a></div>
				<!-- /wp:button -->

			";
			continue;
		}

        $output[$download_id] = "
		<!-- wp:heading -->
		<h2>" . $download_name . "</h2>
		<!-- /wp:heading -->
		<!-- wp:core/audio {'align':'center'} -->
		<figure class='wp-block-audio aligncenter'>
			
		<audio controls='' preload='auto' src=".$download_url ."></audio>
		
		</figure>
		<!-- /wp:core/audio -->
		<!-- wp:buttons {'style':{'spacing':{'margin':{'top':'var:preset|spacing|40','bottom':'var:preset|spacing|40'}}}} -->
		<div class='wp-block-buttons' style='margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)'>
		<!-- wp:button {'className':'is-style-fill'} /-->
			<div class='wp-block-button'><a class='wp-block-button__link wp-element-button' href='$download_url'>Download</a></div>
		<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
		"
		
		;
    }
    // Output example
}

?>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
	$block_content = do_blocks( '
		<!-- wp:group {"tagName":"main","align":"full","style":{"spacing":{"padding":{"top":"calc(var(--wp--custom--v-spacing, 1.25rem) *3)","bottom":"calc(var(--wp--custom--v-spacing, 1.25rem) *2)"}}},"layout":{"inherit":true}} -->
<main class="wp-block-group alignfull" style="padding-top:calc(var(--wp--custom--v-spacing, 1.25rem) *3);padding-bottom:calc(var(--wp--custom--v-spacing, 1.25rem) *2)">

	<!-- wp:post-content {"align":"full","layout":{"inherit":true}} /-->' 
	. implode('<br>', $output) .
	'<!-- wp:spacer {"height":40} -->
	<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:separator {"color":"secondary","className":"is-style-wide"} -->
	<hr class="wp-block-separator has-text-color has-background has-secondary-background-color has-secondary-color is-style-wide" />
	<!-- /wp:separator -->

	<!-- wp:post-comments /-->

</main>
<!-- /wp:group -->
		'
 	);
 	?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">

<header class="wp-block-template-part site-header">
<?php block_header_area(); ?>
</header>

<?php echo $block_content; ?>

<footer class="wp-block-template-part site-footer">
<?php block_footer_area(); ?>
</footer>
</div>
<?php wp_footer(); ?>
</body>