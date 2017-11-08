<?php /* Shopping Cart Widget */

if ( ! defined( 'ABSPATH' ) ) exit;

class Dash_Widget_Cart extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'woocommerce_widget_cart',
			esc_html__('WooCommerce Cart (DashStore Theme)', 'dashstore'),
			array('description' => esc_html__( "Dash Store special widget. Display the user's Cart in the sidebar.", 'dashstore' ),
				  'classname' => 'woocommerce widget_shopping_cart',
			)
		);
	}

	public function form( $instance ) {
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'dashstore' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hide_if_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_if_empty') ); ?>"<?php checked( $hide_if_empty ); ?> />
		<label for="<?php echo esc_attr( $this->get_field_id('hide_if_empty') ); ?>"><?php esc_html_e( 'Hide if cart is empty', 'dashstore' ); ?></label></p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['hide_if_empty'] = empty( $new_instance['hide_if_empty'] ) ? 0 : 1;
		return $instance;
	}

	public function widget( $args, $instance ) {
		global $woocommerce;

		extract( $args );

        if (dash_get_option('catalog_mode') == 'on') return;

		if ( is_cart() || is_checkout() ) return;

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? esc_html__( 'Cart', 'dashstore' ) : $instance['title'], $instance, $this->id_base );

		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;

		echo $before_widget;

		if( dash_get_option('cart_count') == 'on' ) $cart_count = '<a class="cart-contents"><span class="count">'. esc_attr( WC()->cart->cart_contents_count ) . '</span></a>';
        else $cart_count = '';

		echo '<div class="heading"><i class="custom-icon-basket"></i>'.$cart_count.'</div>';

		if ( $hide_if_empty )
			echo '<div class="hide_cart_widget_if_empty">';

		if (  (WC()->cart->cart_contents_count) >= 3 ) {
		?>
			<div class="excerpt-wrapper">
				<p class="message"><?php echo sprintf(esc_html( _n('You have 1 item in your shopping cart','You have %d items in your shopping cart', $woocommerce->cart->cart_contents_count, 'dashstore') ), $woocommerce->cart->cart_contents_count);?></p>
				<a class="view-cart" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php esc_html_e('View your shopping cart', 'dashstore'); ?>">
					<?php esc_html_e('Details', 'dashstore'); ?><i class="fa fa-angle-right"></i>
				</a>
				<p class="total"><?php esc_html_e('Subtotal: ', 'dashstore');?><?php echo $woocommerce->cart->get_cart_subtotal();?></p>
				<p class="buttons"><a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>" class="button checkout wc-forward" title="<?php esc_html_e( 'Checkout', 'dashstore' ) ?>"><?php esc_html_e( 'Checkout', 'dashstore' ) ?></a></p>
			</div>
		<?php
		} else {
			// Insert cart widget placeholder - code in woocommerce.js will update this on page load
			echo '<div class="widget_shopping_cart_content"></div>';
		}

		if ( $hide_if_empty )
			echo '</div>';
		?>

		<script type="text/javascript">
		jQuery(document).ready(function($){
			$(window).load(function(){

				var settings = {
				    interval: 100,
				    timeout: 200,
				    over: mousein_triger,
				    out: mouseout_triger
					};

				function mousein_triger(){
					$(this).find('.widget_shopping_cart_content').css('visibility', 'visible');
					$(this).find('.excerpt-wrapper').css('visibility', 'visible');
					$(this).addClass('hovered');
				}
				function mouseout_triger() {
					$(this).removeClass('hovered');
					$(this).find('.widget_shopping_cart_content').delay(300).queue(function() {
        				$(this).css('visibility', 'hidden').dequeue();
    				});
    				$(this).find('.excerpt-wrapper').delay(300).queue(function() {
        				$(this).css('visibility', 'hidden').dequeue();
    				});
				}

				$('header .widget_shopping_cart').hoverIntent(settings);

			});
		});
		</script>

		<?php echo $after_widget;
	}

}

add_action( 'widgets_init', create_function( '', 'register_widget( "Dash_Widget_Cart" );' ) );

/* Adding product counter */
function dash_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();
	?>
    <?php if ( dash_get_option('cart_count') == 'on' ) : ?>
    <a class="cart-contents"><span class="count"><?php echo esc_attr( WC()->cart->cart_contents_count ); ?></span></a>
	<?php endif; ?>
    <?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}

add_filter('add_to_cart_fragments', 'dash_header_add_to_cart_fragment');
