<?php /* Dash Contacts */

if ( ! defined( 'ABSPATH' ) ) exit;

class Dash_Contacts extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'dash_contacts',
			esc_html__('Dash Contacts', 'dashstore'),
			array( 'description' => esc_html__( 'Dash Store special widget. Configurable Address Widget', 'dashstore' ), )
		);
		add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
	}

	/**
     * Upload the Javascripts for the media uploader
    */
    public function upload_scripts()
    {
		$mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
        $modes = array( 'grid', 'list' );
        if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
            $mode = $_GET['mode'];
            update_user_option( get_current_user_id(), 'media_library_mode', $mode );
        }
        if( ! empty ( $_SERVER['PHP_SELF'] ) && 'upload.php' === basename( $_SERVER['PHP_SELF'] ) && 'grid' !== $mode ) {
            wp_enqueue_script( 'media' );
        }
        if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media();
			if ( !wp_script_is( 'dash_upload_media_script', 'enqueued' ) )	wp_enqueue_script( 'dash_upload_media_script', get_template_directory_uri() .'/js/upload-media.js', array('jquery'), '1.0', true);
    }

	public function form( $instance ) {

		$defaults = array(
			'title' 		=> 'Location',
			'precontent'    => '',
			'postcontent'   => '',
			'phone'			=> '',
			'fax' 			=> '',
			'skype' 		=> '',
			'email' 		=> '',
			'address' 		=> '',
			'company_name'  => '',
			'image'         => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'company_name' )); ?>"><?php esc_html_e( 'Company Name:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'company_name' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'company_name' )); ?>" type="text" value="<?php echo esc_attr($instance['company_name']); ?>" />
		</p>
		<p>
            <label for="<?php echo esc_attr($this->get_field_name( 'image' )); ?>"><?php esc_html_e( 'Company Logo:', 'dashstore' ); ?></label>
            <img class="custom_logo_image" src="<?php if ( !empty($instance['image']) ) { echo esc_url($instance['image']); } else { echo '#'; } ?>" style="margin:0 auto 10px;padding:0;width:200px;display:<?php if ( !empty($instance['image']) ) { echo 'block'; } else { echo 'none'; } ?>" />
            <input name="<?php echo esc_attr($this->get_field_name( 'image' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url($instance['image']); ?>" />
            <span class="button button-primary pt_upload_image_button" id="<?php echo esc_attr($this->get_field_id( 'image' )).'_button'; ?>" style="margin:10px 0 0 0;"><?php esc_html_e('Upload Image', 'dashstore'); ?></span>
        </p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id ('precontent')); ?>"><?php esc_html_e('Pre-Content', 'dashstore'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('precontent')); ?>" name="<?php echo esc_attr($this->get_field_name('precontent')); ?>" rows="2" cols="25"><?php echo esc_attr($instance['precontent']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id ('postcontent')); ?>"><?php esc_html_e('Post-Content', 'dashstore'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('postcontent')); ?>" name="<?php echo esc_attr($this->get_field_name('postcontent')); ?>" rows="2" cols="25"><?php echo esc_attr($instance['postcontent']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'phone' )); ?>"><?php esc_html_e( 'Phone:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'phone' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'phone' )); ?>" type="text" value="<?php echo esc_attr($instance['phone']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'fax' )); ?>"><?php esc_html_e( 'Fax:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'fax' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'fax' )); ?>" type="text" value="<?php echo esc_attr($instance['fax']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'skype' )); ?>"><?php esc_html_e( 'Skype:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'skype' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'skype' )); ?>" type="text" value="<?php echo esc_attr($instance['skype']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'email' )); ?>"><?php esc_html_e( 'Email:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'email' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'email' )); ?>" type="text" value="<?php echo esc_attr($instance['email']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'address' )); ?>"><?php esc_html_e( 'Address:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'address' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'address' )); ?>" type="text" value="<?php echo esc_attr($instance['address']); ?>" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['precontent'] = stripslashes( $new_instance['precontent'] );
		$instance['postcontent'] = stripslashes( $new_instance['postcontent'] );
		$instance['phone'] = strip_tags( $new_instance['phone'] );
		$instance['fax'] = strip_tags( $new_instance['fax'] );
		$instance['skype'] = strip_tags( $new_instance['skype'] );
		$instance['email'] = strip_tags( $new_instance['email'] );
		$instance['address'] = strip_tags( $new_instance['address'] );
		$instance['image'] = ( $new_instance['image'] );
		$instance['company_name'] = strip_tags( $new_instance['company_name'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $wpdb;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$precontent = (isset($instance['precontent']) ? $instance['precontent'] : '' );
		$postcontent = (isset($instance['postcontent']) ? $instance['postcontent'] : '' );
		$phone = (isset($instance['phone']) ? $instance['phone'] : '' );
		$fax = (isset($instance['fax']) ? $instance['fax'] : '' );
		$skype = (isset($instance['skype']) ? $instance['skype'] : '' );
		$email = (isset($instance['email']) ? $instance['email'] : '' );
		$address = (isset($instance['address']) ? $instance['address'] : '' );
		$image_url = (isset($instance['image']) ? $instance['image'] : '' );
		$company_name = (isset($instance['company_name']) ? $instance['company_name'] : '' );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . esc_attr($title) . $after_title;
		?>

		<?php if ( ! empty( $precontent ) ) {
			echo '<div class="precontent">'.esc_attr($precontent).'</div>';
		} ?>

			<ul class="pt-widget-contacts" itemprop="sourceOrganization" itemscope="itemscope" itemtype="http://schema.org/LocalBusiness">
				<?php if($company_name != '' ) : ?><li class="option-title a-name"><span class="name" itemprop="name"><?php echo esc_attr($company_name); ?></span></li><?php endif; ?>
				<?php if($image_url != '' ) : ?><li class="option-title a-logo"><span class="logo"><img alt="<?php echo esc_attr($company_name); ?>" src="<?php echo esc_url($image_url); ?>" itemprop="logo" /></span></li><?php endif; ?>
				<?php if($phone != '' ) : ?><li class="option-title a-phone"><span class="phone" itemprop="telephone"><?php echo esc_attr($phone); ?></span></li><?php endif; ?>
				<?php if($fax != '' ) : ?><li class="option-title a-fx"><span class="fax"><?php echo esc_attr($fax); ?></span></li><?php endif; ?>
				<?php if($skype != '' ) : ?><li class="option-title a-skype"><span class="skype"><?php echo esc_attr($skype); ?></span></li><?php endif; ?>
				<?php if($email != '' ) : ?><li class="option-title a-email"><span class="email" itemprop="email"><a title="Email us" href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_attr($email); ?></a></span></li><?php endif; ?>
				<?php if($address != '' ) : ?><li class="option-title a-address"><span class="address" itemprop="address"><?php echo esc_attr($address); ?></span></li><?php endif; ?>
			</ul>

		<?php
		if ( ! empty( $postcontent ) ) {
			echo '<div class="postcontent">'.esc_attr($postcontent).'</div>';
		}

		echo $after_widget;
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "Dash_Contacts" );' ) );
