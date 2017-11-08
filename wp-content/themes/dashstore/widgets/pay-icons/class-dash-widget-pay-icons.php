<?php /* Dash Payment Icons */

if ( ! defined( 'ABSPATH' ) ) exit;

class Dash_Payment_Icons extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'dash_pay_icons',
			esc_html__('Dash Payment Icons', 'dashstore'),
			array( 'description' => esc_html__( 'Dash Store special widget. Add payment methods icons', 'dashstore' ), )
		);
	}

	public function form( $instance ) {

		$defaults = array(
			'title' 		=> 'We Accept',
			'precontent'    => '',
			'postcontent'   => '',
			'americanexpress'	=> false,
			'discover'			=> false,
			'maestro'			=> false,
			'mastercard'		=> false,
			'paypal'			=> false,
			'visa'				=> false,
			'westernunion'		=> false,
			'dinersclub'			=> false,
			'cirrus'				=> false,
			'solo'			=> false,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id ('precontent') ); ?>"><?php esc_html_e('Pre-Content', 'dashstore'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('precontent') ); ?>" name="<?php echo esc_attr( $this->get_field_name('precontent') ); ?>" rows="2" cols="25"><?php echo esc_attr( $instance['precontent'] ); ?></textarea>
		</p>

		<?php
		$params = array(
			'americanexpress' 		=> esc_html__( 'American Express', 'dashstore' ),
			'discover'				=> esc_html__( 'Discover', 'dashstore' ),
			'maestro'				=> esc_html__( 'Maestro', 'dashstore' ),
			'mastercard'			=> esc_html__( 'Mastercard', 'dashstore' ),
			'paypal'				=> esc_html__( 'PayPal', 'dashstore' ),
			'visa'					=> esc_html__( 'Visa', 'dashstore' ),
			'westernunion'			=> esc_html__( 'Western Union', 'dashstore' ),
			'dinersclub'				=> esc_html__( 'Diners Club', 'dashstore' ),
			'cirrus'					=> esc_html__( 'Cirrus', 'dashstore' ),
			'solo'				=> esc_html__( 'Solo', 'dashstore' ),
		);

		foreach ($params as $key => $value) { ?>
			<p style="display:inline-block; width:40%; padding-right:5%; margin:0;">
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" <?php checked( (bool) $instance[ $key ] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_attr( $value ); ?></label>
			</p>
		<?php } ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id ('postcontent') ); ?>"><?php esc_html_e('Post-Content', 'dashstore'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('postcontent') ); ?>" name="<?php echo esc_attr( $this->get_field_name('postcontent') ); ?>" rows="2" cols="25"><?php echo esc_attr( $instance['postcontent'] ); ?></textarea>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['precontent'] = stripslashes( $new_instance['precontent'] );
		$instance['postcontent'] = stripslashes( $new_instance['postcontent'] );
		$instance['americanexpress'] = ( $new_instance['americanexpress'] );
		$instance['discover'] = ( $new_instance['discover'] );
		$instance['maestro'] = ( $new_instance['maestro'] );
		$instance['mastercard'] = ( $new_instance['mastercard'] );
		$instance['paypal'] = ( $new_instance['paypal'] );
		$instance['visa'] = ( $new_instance['visa'] );
		$instance['westernunion'] = ( $new_instance['westernunion'] );
		$instance['dinersclub'] = ( $new_instance['dinersclub'] );
		$instance['cirrus'] = ( $new_instance['cirrus'] );
		$instance['solo'] = ( $new_instance['solo'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $wpdb;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$payment_icons = array();
		$payment_icons['americanexpress'] = (isset($instance['americanexpress']) ? $instance['americanexpress'] : false );
		$payment_icons['discover'] = (isset($instance['discover']) ? $instance['discover'] : false );
		$payment_icons['maestro'] = (isset($instance['maestro']) ? $instance['maestro'] : false );
		$payment_icons['mastercard'] = (isset($instance['mastercard']) ? $instance['mastercard'] : false );
		$payment_icons['paypal'] = (isset($instance['paypal']) ? $instance['paypal'] : false );
		$payment_icons['visa'] = (isset($instance['visa']) ? $instance['visa'] : false );
		$payment_icons['westernunion'] = (isset($instance['westernunion']) ? $instance['westernunion'] : false );
		$payment_icons['dinersclub'] = (isset($instance['dinersclub']) ? $instance['dinersclub'] : false );
		$payment_icons['cirrus'] = (isset($instance['cirrus']) ? $instance['cirrus'] : false );
		$payment_icons['solo'] = (isset($instance['solo']) ? $instance['solo'] : false );
		$precontent = (isset($instance['precontent']) ? $instance['precontent'] : '' );
		$postcontent = (isset($instance['postcontent']) ? $instance['postcontent'] : '' );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . esc_attr( $title ) . $after_title;

		if ( ! empty( $precontent ) )
			echo '<div class="precontent">' . esc_attr( $precontent ) . '</div>';
		?>

			<ul class="pt-widget-pay-icons">
				<?php
				 foreach ($payment_icons as $payment_icon => $value) {
					 if ($value == 'on') { ?>
					<li class="option-title">
						<img src="<?php echo get_template_directory_uri().'/widgets/pay-icons/img/'.$payment_icon.'-icon.png'; ?>" alt="<?php echo $payment_icon; ?>" />
					</li>
				<?php } } ?>
			</ul>

		<?php
		if ( ! empty( $postcontent ) )
			echo '<div class="postcontent">' . esc_attr( $postcontent ) . '</div>';

		echo $after_widget;
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "Dash_Payment_Icons" );' ) );
