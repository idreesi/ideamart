<?php
/**
 *  Vendor Main Header - Hooked into archive-product page
*
 *  THIS FILE WILL LOAD ON VENDORS STORE URLs (such as yourdomain.com/vendors/bobs-store/)
 *
 * @author WCVendors
 * @package WCVendors
 * @version 1.3.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Get Variables */
global $wpdb;

$logo_src = get_user_meta( get_the_author_meta('ID'), 'pv_logo_image', true );
$logo_pos = get_user_meta( get_the_author_meta('ID'), 'pv_logo_position', true );
$featured_carousel = get_user_meta( get_the_author_meta('ID'), 'pv_featured_carousel', true );

if ( $logo_src && $logo_src != '') {
	$id = $wpdb->get_var( $wpdb->prepare(
			"SELECT ID FROM $wpdb->posts WHERE BINARY guid = %s",
			$logo_src
	) );
	$store_icon_src = wp_get_attachment_image_src( $id, 'dash-vendor-main-logo' );
	if ( is_array( $store_icon_src ) ) {
		$logo_src = $store_icon_src[0];
	}
}

switch ($logo_pos) {
	case 'left':
		$logo_class = ' col-lg-4 col-md-4 col-sm-12';
		$heading_class = ' col-lg-8 col-md-8 col-sm-12';
	break;
	case 'center':
		$logo_class = ' col-lg-12 col-md-12 col-sm-12 center-pos';
		$heading_class = ' col-lg-12 col-md-12 col-sm-12 center-pos';
	break;
	case 'right':
		$logo_class = ' col-md-4 col-lg-4 col-sm-12 col-lg-push-8 col-md-push-8 right-pos';
		$heading_class = ' col-lg-8 col-md-8 col-sm-12 col-lg-pull-4 col-md-pull-4';
	break;
}

if ($logo_src == '') {
	$heading_class = ' col-lg-12 col-md-12 col-sm-12 center-pos';
}

if ( dash_show_layout() == 'layout-one-col' ) {
	$slides = 4;
} else {
	$slides = 3;
}

$meta_query   = WC()->query->get_meta_query();
$meta_query[] = array(
	'key'   => '_featured',
	'value' => 'yes'
);

$args = array(
	'post_type'				=> 'product',
	'post_status' 			=> 'publish',
	'author_name'			=> $vendor->user_nicename,
	'ignore_sticky_posts'	=> 1,
	'posts_per_page' 		=> $slides,
	'orderby' 				=> 'date',
	'order' 				=> 'desc',
	'meta_query'            => $meta_query,
);

$products = new WP_Query( $args ); ?>

<div class="vendors-shop-description">

	<div class="row">
		<?php if ( $logo_src && $logo_src!='') : ?>
			<div class="logo-wrap<?php echo esc_attr($logo_class);?>">
				<img class="store-icon" src="<?php echo esc_url($logo_src); ?>" alt="<?php echo esc_attr($shop_name); ?> logo" />
			</div>
		<?php endif; ?>
		<div class="vendors-title-wrap<?php echo esc_attr($heading_class); ?>">
			<h1><?php echo esc_attr($shop_name); ?></h1>
		</div>
	</div>

	<div class="entry-vendor-content"><?php echo $shop_description; ?></div>

	<?php if ( $featured_carousel == 'on' && $products->have_posts() ) : ?>
		<div class="pt-woo-shortcode with-slider"
			 data-owl="container"
			 data-owl-slides="<?php echo esc_attr($slides); ?>"
			 data-owl-type="woo_shortcode"
			 data-owl-navi="custom"
		>
			<div class="title-wrapper">
				<h3><?php _e('Special Offers', 'dashstore')?></h3>
				<div class="slider-navi">
					<span class="prev"></span>
					<span class="next"></span>
				</div>
			</div>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>

</div>
