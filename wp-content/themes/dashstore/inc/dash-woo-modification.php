<?php

/*-------Woocommerce modifications----------*/

/* Contents:
	1. Style & Scripts
	3. Product columns filter
	4. Custom catalog order
	5. Woocommerce Main content wrapper
	6. Changing 'add to cart' buttons text
	7. Modifying Product Loop layout
	8. Modifying Single Product layout
	9. Adding single product pagination
	10. Custom chekout fields order output
	11. Add meta box for activating extra gallery on product hover
	12. Add meta box for adding custom Product Badge
	13. Shop on front page functions
	14. Catalog Mode Function
	15. Variables Products fix
	16. Filter for proper work of $_chosen_attributes
 */

if ( class_exists('Woocommerce') ) {

	// ----- 1. Style & Scripts
	// Deactivating Woocommerce styles
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	} else {
		define( 'WOOCOMMERCE_USE_CSS', false );
	}

	// Adding new styles
	if ( ! function_exists( 'dash_woo_custom_style' ) ) {
		function dash_woo_custom_style() {
			wp_register_style( 'dash-woo-styles', get_template_directory_uri() . '/woo-styles.css', null, 1.0, 'screen' );
			wp_enqueue_style( 'dash-woo-styles' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'dash_woo_custom_style' );


	// ----- 3. Product columns filter
	if ( ! function_exists( 'dash_loop_shop_columns' ) ) {
		function dash_loop_shop_columns(){
			$qty = (dash_get_option('store_columns') != '') ? dash_get_option('store_columns') : '3';
			return $qty;
		}
	}
	add_filter('loop_shop_columns', 'dash_loop_shop_columns');


	// ----- 4. Custom catalog order
	if ( ! function_exists( 'dash_default_catalog_orderby' ) ) {
		function dash_default_catalog_orderby(){
			return 'date'; // Can also use title and price
		}
	}
	if ( !get_option('woocommerce_default_catalog_orderby') ) {
		add_filter('woocommerce_default_catalog_orderby', 'dash_default_catalog_orderby');
	}


	// ----- 5. Woocommerce Main content wrapper
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	if (!function_exists('dash_theme_wrapper_start')) {
		function dash_theme_wrapper_start() { ?>
			<main class="site-content<?php if (function_exists('dash_main_content_class')) dash_main_content_class(); ?>" itemscope="itemscope" itemprop="mainContentOfPage"><!-- Main content -->
		<?php }
	}

	if (!function_exists('dash_theme_wrapper_end')) {
		function dash_theme_wrapper_end() { ?>
			</main><!-- end of Main content -->
		<?php }
	}

	add_action('woocommerce_before_main_content', 'dash_theme_wrapper_start', 13);
	add_action('woocommerce_after_main_content', 'dash_theme_wrapper_end', 10);


	// ----- 6. Changing 'add to cart' buttons text
	if ( ! function_exists( 'dash_custom_woocommerce_product_add_to_cart_link' ) ) {
		function dash_custom_woocommerce_product_add_to_cart_link() {
			global $product;
			$class = implode( ' ', array_filter( array(
					'button',
					'product_type_' . $product->get_type(),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			) ) );
			return '<a rel="nofollow"
								 href="'.esc_url( $product->add_to_cart_url() ).'"
								 data-quantity="'.esc_attr( isset( $quantity ) ? $quantity : 1 ).'"
								 data-product_id="'.esc_attr( $product->get_id() ).'"
								 data-product_sku="'.esc_attr( $product->get_sku() ).'"
								 class="'.esc_attr( isset( $class ) ? $class : 'button' ).'">'.$product->add_to_cart_text().'</a>';
		}
		add_filter('woocommerce_loop_add_to_cart_link', 'dash_custom_woocommerce_product_add_to_cart_link');
	}

	// Changing by product type on archive pages
	if ( ! function_exists( 'dash_custom_woocommerce_product_add_to_cart_text' ) ) {
		function dash_custom_woocommerce_product_add_to_cart_text() {
			global $product;
			$product_type = $product->get_type();

			switch ( $product_type ) {
				case 'external':
					$text = esc_html__('BUY', 'dashstore');
					return '<i class="custom-icon-logout"></i><span>'.esc_attr($text).'</span>';
				break;
				case 'grouped':
					$text = esc_html__('BUY', 'dashstore');
					return '<i class="custom-icon-search"></i><span>'.esc_attr($text).'</span>';
				break;
				case 'simple':
					$text = esc_html__('BUY', 'dashstore');
					if ( !$product->is_in_stock() ) {
						return '<i class="custom-icon-search"></i><span>'.esc_attr($text).'</span>';
					} else {
						return  '<i class="custom-icon-basket"></i><span>'.esc_attr($text).'</span>';
					}
				break;
				case 'variable':
					$text = esc_html__('BUY', 'dashstore');
					return '<i class="custom-icon-search"></i><span>'.esc_attr($text).'</span>';
				break;
				default:
					$text = esc_html__('BUY', 'dashstore');
					if ( !$product->is_in_stock() ) {
						return '<i class="custom-icon-search"></i><span>'.esc_attr($text).'</span>';
					} else {
						return  '<i class="custom-icon-basket"></i><span>'.esc_attr($text).'</span>';
					}
			}
		}
	}
	add_filter( 'woocommerce_product_add_to_cart_text' , 'dash_custom_woocommerce_product_add_to_cart_text' );


	// ----- 7. Modifying Product Loop layout
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

	// Adding advanced shop title
	add_filter( 'woocommerce_page_title', 'dash_custom_woocommerce_page_title');
	if (!function_exists('dash_custom_woocommerce_page_title')) {
		function dash_custom_woocommerce_page_title( $page_title ) {
			if ( is_shop() ) {
				return esc_html__('All Products', 'dashstore');
			} else {
				return esc_attr($page_title);
			}
		}
	}

	// Modifying shop control buttons
	add_action( 'woocommerce_before_main_content', 'dash_shop_controls_wrapper_start', 7 );
	if (!function_exists('dash_shop_controls_wrapper_start')) {
		function dash_shop_controls_wrapper_start(){
			if ( !is_product() ) { ?>
			<div class="shop-controls-wrapper">
		<?php }
		}
	}

	add_action( 'woocommerce_before_main_content', 'dash_shop_controls_wrapper_end', 11 );
	if (!function_exists('dash_shop_controls_wrapper_end')) {
		function dash_shop_controls_wrapper_end(){
			if ( !is_product() ) { ?>
			</div>
		<?php }
		}
	}

	// Removing Result Count
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	add_action( 'woocommerce_before_main_content', 'woocommerce_result_count', 8 );

	// Removing Catalog Ordering
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	add_action( 'woocommerce_before_main_content', 'woocommerce_catalog_ordering', 10 );

	// Adding view all Link
	add_action( 'woocommerce_before_main_content', 'dash_view_all_link', 9 );
	if ( ! function_exists( 'dash_view_all_link' ) ) {
		function dash_view_all_link(){
			global $wp_query;
			if ( $wp_query->max_num_pages > 1 ) { ?>
				<a rel="nofollow" class="view-all" href="?showall=1"><?php esc_html_e('View All', 'dashstore'); ?></a>
			<?php }
			if( isset( $_GET['showall'] ) ){
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) ); ?>
			    <a rel="nofollow" class="view-all" href="<?php echo esc_url($shop_page_url); ?>"><?php esc_html_e('View Less', 'dashstore'); ?></a>
			<?php }
		}
	}

	// Products per page filter
	if ( ! function_exists( 'dash_show_products_per_page' ) ) {
		function dash_show_products_per_page() {
			if( isset( $_GET['showall'] ) ){
				$qty = '-1';
			} else {
				$qty = (dash_get_option('store_per_page') != '') ? dash_get_option('store_per_page') : '6';
			}
			return $qty;
		}
	}
	add_filter('loop_shop_per_page', 'dash_show_products_per_page', 20 );

	// Adding list/grid view
	function dash_view_switcher() { ?>
		<div class="pt-view-switcher">
			<span><?php esc_html_e('View:', 'dashstore'); ?></span>
			<span class="pt-list<?php if(dash_get_option('default_list_type')=='list') echo ' active';?>" title="<?php esc_html_e('List View', 'dashstore'); ?>"><i class="fa fa-th-list"></i></span>
			<span class="pt-grid<?php if(dash_get_option('default_list_type')=='grid') echo ' active';?>" title="<?php esc_html_e('Grid View', 'dashstore'); ?>"><i class="fa fa-th"></i></span>
		</div>
	<?php }

	if ( dash_get_option('list_grid_switcher') === 'on' ) {
		add_action( 'woocommerce_before_shop_loop', 'dash_view_switcher', 35 );
	}

	// Removing sale flash
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

	// Removing Rating
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

	// Modifying Pagination args
	function dash_new_pagination_args($args) {
		$args['prev_text'] = '<i class="fa fa-chevron-left"></i>';
		$args['next_text'] = '<i class="fa fa-chevron-right"></i>';
		return $args;
	}
	add_filter('woocommerce_pagination_args','dash_new_pagination_args');

	// Lanquage Settings for shop tooltips on hover
	add_action( 'wp_footer', 'dash_shop_tooltips');

	if (!function_exists('dash_shop_tooltips')) {
		function dash_shop_tooltips() {
		?>
		<script type="text/javascript">
			msg_quick = '<?php esc_html_e('Quickview', 'dashstore') ?>';
			msg_compare = '<?php esc_html_e('Compare', 'dashstore') ?>';
			msg_added = '<?php esc_html_e('Added to Compare List', 'dashstore') ?>';
			msg_wish = '<?php esc_html_e('Add to Wish List', 'dashstore') ?>';
			msg_wish_details = '<?php esc_html_e('Added to Wish List', 'dashstore') ?>';
			msg_wish_view = '<?php esc_html_e('View Wish List', 'dashstore') ?>';
		</script>
		<?php
		}
	}

	// Inner Product wrapper
	function dash_inner_product_wrapper_start() {
			echo '<div class="inner-product-content">';
	}
	function dash_inner_product_wrapper_end() {
			echo '</div>';
	}
	add_action('woocommerce_before_shop_loop_item', 'dash_inner_product_wrapper_start', 5);
	add_action('woocommerce_after_shop_loop_item', 'dash_inner_product_wrapper_end', 35);

	// Product extra Images wrapper
	function dash_product_extra_imgs_wrapper_start() {
		echo '<div class="product-img-wrapper">';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_product_extra_imgs_wrapper_start', 5);

	function dash_product_extra_imgs_wrapper_end() {
		echo '</div>';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_product_extra_imgs_wrapper_end', 35);

	// Product extra gallery
	function dash_product_extra_gallery_start() {
		global $product;
		$attachment_ids = $product->get_gallery_image_ids();
		$show_gallery = get_post_meta( $product->get_id(), 'pt_product_extra_gallery' );
		$slider_init = '';
		if ( $attachment_ids && ($show_gallery[0] == 'on') ) {
			$slider_init = ' pt-extra-gallery-wrapper';
		}
		echo '<div class="pt-extra-gallery-img images'.esc_attr($slider_init).'">
						<a rel="nofollow" class="pt-extra-gallery" href="'.get_the_permalink().'" title="'.__('View details', 'dashstore').'">';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_product_extra_gallery_start', 6);

	remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 7);

	function dash_product_extra_gallery_end() {
		global $product;

		// Adding extra gallery if turned on
		$attachment_ids = $product->get_gallery_image_ids();
		$show_gallery = get_post_meta(  $product->get_id(), 'pt_product_extra_gallery' );

		if ( $attachment_ids && ($show_gallery[0] == 'on') ) {
			$gallery_images = array();
			foreach ($attachment_ids as $attachment_id) {
		 		$image = wp_get_attachment_image( $attachment_id, 'shop_catalog' );
				$gallery_images[] = $image;
			}
		}
		if ( !empty($gallery_images) ) {
			foreach ($gallery_images as $gallery_image) {
				echo $gallery_image;
			}
		}
		echo '</a>';
		if ( $attachment_ids && ($show_gallery[0] == 'on') ) {
			echo '<span class="prod-prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
						<span class="prod-next"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
		}
		echo '</div>';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_product_extra_gallery_end', 8);

	// Additional btns wrapper
	function dash_additional_btns_wrapper_start() {
		echo '<div class="additional-buttons">';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_additional_btns_wrapper_start', 9);
	add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 16);

	if( ( class_exists('YITH_Woocompare_Frontend') ) && ( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link'), 20 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $yith_woocompare->obj, 'add_compare_link'), 17  );
	}

	function dash_additional_btns() {
		// add to wishlist button
		if ( ( class_exists( 'YITH_WCWL_Shortcode' ) ) && ( get_option('yith_wcwl_enabled') == true ) ) {
			$atts = array(
							'per_page' => 10,
							'pagination' => 'no',
						);
			echo YITH_WCWL_Shortcode::add_to_wishlist($atts);
		}
		// wcv loop sold by
		if ( function_exists('dash_loop_sold_by') && dash_get_option('show_wcv_loop_sold_by')=='on' ) {
				dash_loop_sold_by( get_the_ID() );
		}
		echo '<span class="product-tooltip"></span>';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_additional_btns', 20);

	function dash_additional_btns_wrapper_end() {
		echo '</div>';
	}
	add_action('woocommerce_before_shop_loop_item_title', 'dash_additional_btns_wrapper_end', 21);

	// Adding new custom Badge
	if ( ! function_exists( 'dash_output_custom_badge' ) ) {
		function dash_output_custom_badge(){
			global $product;
			$badge_text = get_post_meta( $product->get_id(), 'custom-badge-text' );
			$badge_class = get_post_meta( $product->get_id(), 'custom-badge-class' );
			if (isset($badge_class[0]) && ($badge_class[0] != '')) { $new_class = $badge_class[0]; } else { $new_class='custom-badge'; }
			if ( isset($badge_text[0]) && ( $badge_text[0] != '') ) { ?>
				<span class="<?php echo esc_attr($new_class); ?>"><?php echo esc_attr($badge_text[0]); ?></span>
			<?php }
		}
	}
	add_action( 'woocommerce_after_shop_loop_item', 'dash_output_custom_badge', 34 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 33 );

	// Product description wrapper
	function handy_product_description_wrapper_start() {
		global $product;
		echo '<div class="product-description-wrapper">
						<h3 class="entry-title product-title">
							<a href="'.esc_url(get_the_permalink()).'" class="link-to-product">
								'.esc_attr(get_the_title()).'
							</a>
						</h3>';
		if ( $product->get_short_description() ) : ?>
			<div class="short-description">
				<?php echo $product->get_short_description(); ?>
			</div>
		<?php endif;
	}
	add_action( 'woocommerce_after_shop_loop_item', 'handy_product_description_wrapper_start', 10);

	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 12 );

	// Moving add_to_cart button
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	if ( ! function_exists( 'dash_output_variables' ) ) {
		function dash_output_variables() {
			global $product;
			if( $product->is_type('variable') && (is_shop() || is_product_category() || is_product_tag()) ){
				woocommerce_variable_add_to_cart();
				wc_get_template_part( 'loop/add-to-cart.php' );
			} else {
				wc_get_template_part( 'loop/add-to-cart.php' );
			}
		}
	}
	add_action( 'woocommerce_after_shop_loop_item', 'dash_output_variables', 15 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 15 );

	function handy_product_description_wrapper_end() {
		echo '</div>';
	}
	add_action( 'woocommerce_after_shop_loop_item', 'handy_product_description_wrapper_end', 20);


	// ----- 8. Modifying Single Product layout

	// Breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	if ( (dash_get_option('store_breadcrumbs')) === 'on' ) {
		add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 6 );
	}

	if (!function_exists('dash_breadcrumbs_wrap_begin')) {
		function dash_breadcrumbs_wrap_begin(){
			if ( (dash_get_option('store_breadcrumbs')==='off' && !is_product()) || dash_get_option('store_breadcrumbs')==='on' ) { ?>
			<div class="breadcrumbs-wrapper"><div class="container"><div class="row">
		<?php }
		}
	}

	if (!function_exists('dash_breadcrumbs_wrap_end')) {
		function dash_breadcrumbs_wrap_end(){
			if ( (dash_get_option('store_breadcrumbs')==='off' && !is_product()) || dash_get_option('store_breadcrumbs')==='on' ) { ?>
			</div></div></div>
		<?php }
		}
	}

	add_action( 'woocommerce_before_main_content', 'dash_breadcrumbs_wrap_begin', 4 );
	add_action( 'woocommerce_before_main_content', 'dash_breadcrumbs_wrap_end', 12 );

	add_filter( 'woocommerce_breadcrumb_defaults', 'dash_custom_breadcrumbs' );
	if (!function_exists('dash_custom_breadcrumbs')) {
		function dash_custom_breadcrumbs() {
			return array(
				'delimiter' => '<span> / </span>',
				'wrap_before' => '<nav class="woocommerce-breadcrumb">',
				'wrap_after' => '</nav>',
				'before' => '',
				'after' => '',
				'home' => esc_html_x( 'Home', 'breadcrumb', 'dashstore' ),
			);
		}
	}

	// Product images
	if ( version_compare( WOOCOMMERCE_VERSION, "3.0" ) >= 0 ) {
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	// Images wrapper
	if (!function_exists('dash_images_wrapper_start')) {
		function dash_images_wrapper_start(){ ?>
			<div class="images-wrapper">
		<?php }
	}
	if (!function_exists('dash_images_wrapper_end')) {
		function dash_images_wrapper_end(){ ?>
			</div>
		<?php }
	}
	add_action('woocommerce_before_single_product_summary', 'dash_images_wrapper_start', 5);
	add_action('woocommerce_before_single_product_summary', 'dash_images_wrapper_end', 25);

	// Special buttons wrapper
	if (!function_exists('dash_special_btns_wrapper_start')) {
		function dash_special_btns_wrapper_start(){ ?>
			<div class="special-buttons-wrapper">
		<?php }
	}
	if (!function_exists('dash_special_btns_wrapper_end')) {
		function dash_special_btns_wrapper_end(){ ?>
			</div>
		<?php }
	}
	add_action('woocommerce_single_product_summary', 'dash_special_btns_wrapper_start', 35);
	add_action('woocommerce_single_product_summary', 'dash_special_btns_wrapper_end', 38);

	// Compare button moving
	if( ( class_exists('YITH_Woocompare_Frontend') ) && ( get_option('yith_woocompare_compare_button_in_product_page') == 'yes' ) ) {
		remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link'), 35 );
		add_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link'), 37  );
	}

	// Wishlist button moving
	if ( ( class_exists( 'YITH_WCWL_Shortcode' ) ) && ( get_option('yith_wcwl_enabled') == true ) && ( get_option('yith_wcwl_button_position') == 'shortcode' ) ) {
		function output_wishlist_button() {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
		add_action( 'woocommerce_single_product_summary', 'output_wishlist_button', 36  );
	}

	// Social shares
	if (dash_get_option('use_pt_shares_for_product')=='on') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
		add_action('woocommerce_single_product_summary', 'dash_share_buttons_output', 50);
	}

	// Tabs modification
	if (!function_exists('dash_custom_product_tabs')) {
		function dash_custom_product_tabs( $tabs ) {

			global $product;
			$product_content = $product->get_description();

			if ($product_content && $product_content!=='') {
				$tabs['description']['priority'] = 10;
			} else {
				unset( $tabs['description'] );
			}

			if( $product->has_attributes() || $product->has_dimensions() || $product->has_weight() ) {
				$tabs['additional_information']['title'] = esc_html__( 'Specification', 'dashstore' );
				$tabs['additional_information']['priority'] = 20;
			} else {
				unset( $tabs['additional_information'] );
			}

			return $tabs;

		}
	}
	add_filter( 'woocommerce_product_tabs', 'dash_custom_product_tabs', 98 );

	// Reviews avatar size
	if ( ! function_exists( 'dash_custom_review_gravatar' ) ) {
		function dash_custom_review_gravatar() {
			return '70';
		}
	}
	add_filter('woocommerce_review_gravatar_size', 'dash_custom_review_gravatar');

	// Up-sells Products
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
	if (dash_get_option('show_upsells')=='on') {
		if (!function_exists('dash_output_upsells')) {
			function dash_output_upsells() {
				$upsell_qty = dash_get_option('upsells_qty');
				woocommerce_upsell_display( esc_attr($upsell_qty), esc_attr($upsell_qty) ); // Display $per_page products in $cols
			}
		}
		add_action('woocommerce_after_single_product_summary', 'dash_output_upsells', 20);
	}

	// Related Products
	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	if (!function_exists('dash_output_related_products')) {
		function dash_output_related_products($args) {
			$related_qty = dash_get_option('related_products_qty');
			$args['posts_per_page'] = esc_attr($related_qty); // related products
			$args['columns'] = esc_attr($related_qty); // arranged in columns
			return $args;
		}
	}
	add_filter( 'woocommerce_output_related_products_args', 'dash_output_related_products' );

	if (dash_get_option('show_related_products')=='on') {
		add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 30);
	}


	// ----- 9. Adding single product pagination
	if ( dash_get_option('product_pagination') === 'on' ) {
		if ( ! function_exists( 'dash_single_product_pagi' ) ) {
			function dash_single_product_pagi(){
				if(is_product()) :
				?>
				<nav class="navigation single-product-navi">
					<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'dashstore' ); ?></h1>
						<div class="nav-links">
							<?php previous_post_link('%link', '<i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;'.esc_html__('Previous Product', 'dashstore')); ?>
							<?php next_post_link('%link', esc_html__('Next Product', 'dashstore').'&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>'); ?>
						</div>
				</nav>
				<?php
				endif;
			}
		}
		add_action( 'woocommerce_after_single_product_summary', 'dash_single_product_pagi', 5 );
	}


	// ----- 10. Checkout modification
	// Adding new mark-up
	if (!function_exists('dash_checkout_wrapper_start')) {
		function dash_checkout_wrapper_start(){ ?>
			<div class="order-wrapper col-xs-12 col-md-6">
		<?php }
	}
	add_action( 'woocommerce_checkout_after_customer_details', 'dash_checkout_wrapper_start', 2);

	if (!function_exists('dash_checkout_wrapper_end')) {
		function dash_checkout_wrapper_end(){ ?>
			</div>
		<?php }
	}
	add_action( 'woocommerce_checkout_after_order_review', 'dash_checkout_wrapper_end', 1);

	if (!function_exists('dash_checkout_details_wrapper_start')) {
		function dash_checkout_details_wrapper_start(){ ?>
			<div class="row">
				<div class="customer-details-wrapper col-xs-12 col-md-6">
		<?php }
	}
	add_action( 'woocommerce_checkout_before_customer_details', 'dash_checkout_details_wrapper_start', 1);

	if (!function_exists('dash_checkout_details_wrapper_end')) {
		function dash_checkout_details_wrapper_end(){ ?>
			</div>
		<?php }
	}
	add_action( 'woocommerce_checkout_after_customer_details', 'dash_checkout_details_wrapper_end', 1);

	if (!function_exists('dash_checkout_details_wrapper_row_end')) {
		function dash_checkout_details_wrapper_row_end(){ ?>
			</div>
		<?php }
	}
	add_action( 'woocommerce_checkout_after_order_review', 'dash_checkout_details_wrapper_row_end', 2);

	// Add payment method heading
	if (!function_exists('dash_payments_heading')) {
		function dash_payments_heading(){ ?>
			<h3 id="payment_heading"><?php esc_html_e('Payment Methods', 'dashstore'); ?></h3>
		<?php }
	}
	add_action( 'woocommerce_review_order_before_payment', 'dash_payments_heading');

	// Custom chekout fields order output
	if ( ! function_exists( 'dash_default_address_fields' ) ) {
		function dash_default_address_fields( $fields ) {
		    $fields = array(
				'first_name' => array(
					'label'    => esc_html__( 'First Name', 'dashstore' ),
					'required' => true,
					'class'    => array( 'form-row-wide' ),
				),
				'last_name' => array(
					'label'    => esc_html__( 'Last Name', 'dashstore' ),
					'required' => true,
					'class'    => array( 'form-row-wide' ),
					'clear'    => true
				),
				'company' => array(
					'label' => esc_html__( 'Company Name', 'dashstore' ),
					'class' => array( 'form-row-wide' ),
				),
				'address_1' => array(
					'label'       => esc_html__( 'Address', 'dashstore' ),
					'placeholder' => esc_html_x( 'Street address', 'placeholder', 'dashstore' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' )
				),
				'address_2' => array(
					'label'       => esc_html__( 'Additional address info', 'dashstore' ),
					'placeholder' => esc_html_x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'dashstore' ),
					'class'       => array( 'form-row-wide', 'address-field' ),
					'required'    => false,
					'clear'    	  => true
				),
				'country' => array(
					'type'     => 'country',
					'label'    => esc_html__( 'Country', 'dashstore' ),
					'required' => true,
					'class'    => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
				),
				'city' => array(
					'label'       => esc_html__( 'Town / City', 'dashstore' ),
					'placeholder' => esc_html__( 'Town / City', 'dashstore' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' )
				),
				'state' => array(
					'type'        => 'state',
					'label'       => esc_html__( 'State / County', 'dashstore' ),
					'placeholder' => esc_html__( 'State / County', 'dashstore' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' ),
					'validate'    => array( 'state' )
				),
				'postcode' => array(
					'label'       => esc_html__( 'Postcode / Zip', 'dashstore' ),
					'placeholder' => esc_html__( 'Postcode / Zip', 'dashstore' ),
					'required'    => true,
					'class'       => array( 'form-row-wide', 'address-field' ),
					'clear'       => true,
					'validate'    => array( 'postcode' )
				),
			);
			return $fields;
		}
	}
	add_filter( 'woocommerce_default_address_fields' , 'dash_default_address_fields' );


	// ----- 11. Add meta box for activating extra gallery on product hover

	add_action( 'add_meta_boxes', 'dash_product_extra_gallery_metabox' );
	add_action( 'save_post', 'dash_product_extra_gallery_save' );

	function dash_product_extra_gallery_metabox() {
	    add_meta_box( 'product_extra_gallery', 'Product Extra Gallery', 'dash_product_extra_gallery_call', 'product', 'side', 'default' );
	}

	function dash_product_extra_gallery_call($post) {
		global $post;
		wp_nonce_field( 'dash_product_extra_gallery_call', 'dash_product_extra_gallery_nonce' );
		// Get previous meta data
		$values = get_post_custom($post->ID);
		$check = isset( $values['pt_product_extra_gallery'] ) ? esc_attr( $values['pt_product_extra_gallery'][0] ) : 'off';
		?>
		<div class="product-extra-gallery">
			<label for="pt_product_extra_gallery"><input type="checkbox" name="pt_product_extra_gallery" id="pt_product_extra_gallery" <?php checked( $check, 'on' ); ?> /><?php esc_html_e( 'Use extra gallery for this product', 'dashstore' ) ?></label>
			<p><?php esc_html_e( 'Check the checkbox if you want to use extra gallery (appeared on hover) for this product. The first 3 images of the product gallery are going to be used for gallery.', 'dashstore'); ?></p>
		</div>
		<?php
	}

	// When the post is saved, saves our custom data
	function dash_product_extra_gallery_save( $post_id ) {
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	            return;

	    if ( ( isset ( $_POST['dash_product_extra_gallery_nonce'] ) ) && ( ! wp_verify_nonce( $_POST['dash_product_extra_gallery_nonce'], 'dash_product_extra_gallery_call' ) ) )
	            return;

	    if ( ! current_user_can( 'edit_post', $post_id ) ) {
	            return;
	    }

	    // OK, we're authenticated: we need to find and save the data
	    $chk = isset( $_POST['pt_product_extra_gallery'] ) && $_POST['pt_product_extra_gallery'] ? 'on' : 'off';
		update_post_meta( $post_id, 'pt_product_extra_gallery', $chk );
	}


	// ----- 12. Add meta box for adding custom Product Badge

	add_action( 'add_meta_boxes', 'dash_product_custom_badge_metabox' );
	add_action( 'save_post', 'dash_product_custom_badge_save' );

	function dash_product_custom_badge_metabox() {
	    add_meta_box( 'product_custom_badge', 'Product Custom Badge', 'dash_product_custom_badge_call', 'product', 'side', 'default' );
	}

	function dash_product_custom_badge_call($post) {
		global $post;
		wp_nonce_field( 'dash_product_custom_badge_call', 'dash_product_custom_badge_nonce' );
		// Get previous meta data
		$stored_meta = get_post_meta( $post->ID );
		?>
		<div class="product-custom-badge">
			<p><?php esc_html_e( 'This block should be used for adding custom "Badge/Label". Below you can enter your own text for the label & add additional class for further CSS styling', 'dashstore'); ?></p>
		    <p>
		        <label for="custom-badge-text"><?php esc_html_e( 'Label Text', 'dashstore' )?></label>
		        <input type="text" name="custom-badge-text" id="custom-badge-text" value="<?php if ( isset ( $stored_meta['custom-badge-text'] ) ) echo esc_attr($stored_meta['custom-badge-text'][0]); ?>" />
		    </p>
		    <p>
		        <label for="custom-badge-class"><?php esc_html_e( 'Label Class', 'dashstore' )?></label>
		        <input type="text" name="custom-badge-class" id="custom-badge-class" value="<?php if ( isset ( $stored_meta['custom-badge-class'] ) ) echo esc_attr($stored_meta['custom-badge-class'][0]); ?>" />
		    </p>
		</div>
		<?php
	}

	// When the post is saved, saves our custom data
	function dash_product_custom_badge_save( $post_id ) {
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	            return;

	    if ( ( isset ( $_POST['dash_product_custom_badge_nonce'] ) ) && ( ! wp_verify_nonce( $_POST['dash_product_custom_badge_nonce'], 'dash_product_custom_badge_call' ) ) )
	            return;

	    if ( ! current_user_can( 'edit_post', $post_id ) ) {
	            return;
	    }

	    // OK, we're authenticated: we need to find and save the data
	    if( isset( $_POST[ 'custom-badge-text' ] ) ) {
			update_post_meta( $post_id, 'custom-badge-text', sanitize_text_field( $_POST[ 'custom-badge-text' ] ) );
		}
	    if( isset( $_POST[ 'custom-badge-class' ] ) ) {
			update_post_meta( $post_id, 'custom-badge-class', sanitize_text_field( $_POST[ 'custom-badge-class' ] ) );
		}
	}


	// ----- 13. Shop on front page functions
	if (!function_exists('dash_front_page_shop_output')) {

		function dash_front_page_shop_output() {
			remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

			// Adding content area if front page
			function dash_output_shop_description() {
				if ( is_post_type_archive( 'product' ) ) {
					$shop_page   = get_post( wc_get_page_id( 'shop' ) );
					if ( $shop_page ) {
						$description = wc_format_content( $shop_page->post_content );
						if ( $description ) {
							echo '<div class="entry-content row">' . $description . '</div>';
						}
					}
				}
			}
			add_action( 'woocommerce_archive_description', 'dash_output_shop_description', 10 );

			// Removing controls if shop = front_page
			if ( is_post_type_archive( 'product' ) && is_front_page() ) {
				remove_action( 'woocommerce_before_main_content', 'dash_shop_controls_wrapper_start', 7 );
				remove_action( 'woocommerce_before_main_content', 'woocommerce_result_count', 8 );
				remove_action( 'woocommerce_before_main_content', 'dash_view_all_link', 9 );
				remove_action( 'woocommerce_before_main_content', 'woocommerce_catalog_ordering', 10 );
				remove_action( 'woocommerce_before_main_content', 'dash_shop_controls_wrapper_end', 11 );
				remove_action( 'woocommerce_before_main_content', 'dash_breadcrumbs_wrap_begin', 4 );
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 6 );
				remove_action( 'woocommerce_before_main_content', 'dash_breadcrumbs_wrap_end', 12 );
				remove_action( 'woocommerce_before_shop_loop', 'dash_view_switcher', 35 );

				// new position for shop controls
				add_action( 'woocommerce_before_shop_loop', 'dash_shop_controls_wrapper_start', 10 );
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 11 );
				add_action( 'woocommerce_before_shop_loop', 'dash_view_all_link', 12 );
				add_action( 'woocommerce_before_shop_loop', 'dash_view_switcher', 13 );
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 14 );
				add_action( 'woocommerce_before_shop_loop', 'dash_shop_controls_wrapper_end', 15 );
			}

			// new title position if shop = front_page
			function dash_new_title_pos() {
				if ( apply_filters( 'woocommerce_show_page_title', true ) && is_front_page() ) : ?>
					<h1 class="page-title shop-front"><?php esc_attr(woocommerce_page_title()); ?></h1>
				<?php endif;
			}
			add_action('woocommerce_before_shop_loop', 'dash_new_title_pos', 1);
		}
	}

	if ( dash_get_option('front_page_shop') === 'on') {
		add_action('woocommerce_before_main_content','dash_front_page_shop_output', 1);
	}


	// ----- 14. Catalog Mode Function
	if (dash_get_option('catalog_mode') == 'on') {
		remove_action( 'woocommerce_after_shop_loop_item', 'dash_output_variables', 15 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 15 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 12 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}


	// ----- 15. Variables Products fix
	// Display Price For Variable Product With Same Variations Prices
	add_filter('woocommerce_available_variation', 'dash_variables_price_fix', 10, 3);

	if ( ! function_exists( 'dash_variables_price_fix' ) ) {
		function dash_variables_price_fix( $value, $object = null, $variation = null ) {
			if ($value['price_html'] == '') {
				$value['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
			}
			return $value;
		}
	}

} // end of file
