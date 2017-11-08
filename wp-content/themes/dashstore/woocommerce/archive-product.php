<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php if ( is_front_page() ) do_action( 'woocommerce_archive_description' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */

		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) && !is_front_page() ) : ?>

			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>

		<?php if ( !is_front_page() ) do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php /* Special Filters Sidebar */
			if ( dash_get_option('filters_sidebar')=='on' && is_active_sidebar( 'filters-sidebar' ) ) : ?>
				<div id="filters-sidebar" class="widget-area">
					<span class="filter-head"><?php esc_html_e('Filters:', 'dashstore'); ?></span>
					<?php dynamic_sidebar('filters-sidebar'); ?>
				</div>
			<?php endif; ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

	<?php // Add Extra Sidebar for vendor Widgets
		$vendor_shop = '';
		if ( class_exists('WCV_Vendors') ) {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
		}

		if ( $vendor_shop && $vendor_shop !='' ) {
	        if ( is_active_sidebar( 'vendor-bottom-special-sidebar' ) ) { ?>
	        <div id="vendor-bottom-sidebar" class="widget-area col-xs-12 col-sm-12 col-md-12 sidebar lazyload" data-expand="-100" role="complementary">
	            <div class="row">
	            	<?php dynamic_sidebar( 'vendor-bottom-special-sidebar' ); ?>
	            </div>
	        </div>
	        <?php }
		} ?>

<?php get_footer( 'shop' ); ?>
