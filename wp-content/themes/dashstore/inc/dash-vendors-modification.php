<?php

/*------- WC Vendors modifications ----------*/

/* Contents:
	1. Custom WC Vendors "Sold by"
	2. Add extra fields to vendors settings
	3. Related products by vendors
	4. Add media Upload script for WC Vendors
	5. Add extra info for vendors on "My Account"
	6. Simple feedback form for customers
	7. New Image Sizes for vendors
	8. Modifying Vendor's rating tab
 */

if ( class_exists('WCV_Vendors') ) {

	// ----- 1. Custom WC Vendors "Sold by"
	// "Sold by" in product loop
	if ( !function_exists('dash_loop_sold_by') ) {
		function dash_loop_sold_by($product_id) {
			$vendor_id     = WCV_Vendors::get_vendor_from_product( $product_id );
			$store_title   = WCV_Vendors::get_vendor_sold_by( $vendor_id );
			if ( WCV_Vendors::is_vendor( $vendor_id ) ) {
				if ( class_exists('WCVendors_Pro') ) {
					$store_id = WCVendors_Pro_Vendor_Controller::get_vendor_store_id( $vendor_id );
					$url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id );
					echo '<div class="sold-by-container">';
					echo '<span>'.esc_html__('Sold by: ', 'dashstore').'</span><a title="'.esc_html__('Visit Vendor Store', 'dashstore').'" href="'.esc_url($url).'">'.esc_attr($store_title).'</a>';
					echo '</div>';
				} else {
					$url = WCV_Vendors::get_vendor_shop_page( $vendor_id );
					echo '<div class="sold-by-container">';
					echo '<span>'.esc_html__('Sold by: ', 'dashstore').'</span><a title="'.esc_html__('Visit Vendor Store', 'dashstore').'" href="'.esc_url($url).'">'.esc_attr($store_title).'</a>';
					echo '</div>';
				}
			}
		}
		remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
		if ( class_exists('WCVendors_Pro') ) {
			remove_action( 'woocommerce_after_shop_loop_item', array( $wcvendors_pro->wcvendors_pro_store_controller, 'loop_sold_by'), 8 );
		}
	}

	// "Sold by" in product meta
	function dash_sold_by_wrapper_start() {
		echo '<span class="sold-by-wrapper">';
	}
	function dash_sold_by_wrapper_end() {
		echo '</span>';
	}
	function dash_sold_by_meta_custom_message($message) {
		$message = WC_Vendors::$pv_options->get_option( 'sold_by_label' ).': ';
    return $message;
	}
	add_filter( 'wcvendors_cart_sold_by_meta', 'dash_sold_by_meta_custom_message', 10, 1);
	add_action( 'woocommerce_product_meta_start', 'dash_sold_by_wrapper_start', 9 );
	add_action( 'woocommerce_product_meta_start', 'dash_sold_by_wrapper_end', 11 );

	// Deactivate wcv pro styles
	if ( class_exists('WCVendors_Pro') ) {
		add_action( 'wp_print_styles', 'dash_deregister_styles', 100 );
		function dash_deregister_styles() {
			wp_deregister_style( 'wcv-pro-store-style' );
		}
	}

	// ----- 2. Add extra fields to vendors settings

	// Fields for WC Vendors Free
	if ( !class_exists('WCVendors_Pro') ) {
		// frontend
		add_action( 'wcvendors_settings_frontend_after_shop_description', 'dash_add_frontend_vendor_fields' );
		// Save data from new fields
		add_action( 'wcvendors_shop_settings_saved', 'dash_save_new_vendor_fields' );
		add_action( 'wcvendors_update_admin_user', 'dash_save_new_vendor_fields' );
		// Add new fields to user profile (for admin)
		add_action( 'show_user_profile', 'dash_add_backend_vendor_fields' );
		add_action( 'edit_user_profile', 'dash_add_backend_vendor_fields' );
		// Save data from user profile (for admin)
		add_action( 'personal_options_update', 'dash_save_new_vendor_fields' );
		add_action( 'edit_user_profile_update', 'dash_save_new_vendor_fields' );
	}

	// back end
	function dash_add_backend_vendor_fields($user) { ?>

	  <h3><?php esc_html_e( 'Vendor Extra Options', 'dashstore' ); ?></h3>

	  <table class="form-table">
	  	<tbody>
	  	  <?php $user_id = $user->ID; ?>

		  <tr>
		    <th><?php esc_html_e( 'Upload Logo Image', 'dashstore' ); ?></th>
		    <td>
		    	<input name="pv_logo_image" id="pv_logo_image" type="text" value="<?php echo esc_url( get_user_meta( $user_id, 'pv_logo_image', true ) ); ?>" />
				<span id="pv_logo_image_button" class="button pt_upload_image_button"><?php esc_html_e( 'Upload', 'dashstore' ); ?></span>
			</td>
		  </tr>

		  <tr>
		    <th><?php esc_html_e( 'Logo Position', 'dashstore' ); ?></th>
		    <td>
		    <?php $value = get_user_meta( $user_id,'pv_logo_position', true );
		    	  if ( $value == '' ) $value = 'left'; ?>
			    <input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_left" value="left" <?php checked( $value, 'left'); ?>/><label for="logo_position_left"><?php esc_html_e( ' Left', 'dashstore' ); ?></label><br />
				<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_center" value="center" <?php checked( $value, 'center'); ?>/><label for="logo_position_center"><?php esc_html_e( ' Center', 'dashstore' ); ?></label><br />
				<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_right" value="right" <?php checked( $value, 'right'); ?>/><label for="logo_position_right"><?php esc_html_e( ' Right', 'dashstore' ); ?></label>
			</td>
		  </tr>

		  <tr>
		    <th><?php esc_html_e( 'Products Carousel', 'dashstore' ); ?></th>
		    <td>
		    <?php $value = get_user_meta( $user_id,'pv_featured_carousel', true ); ?>
		    	<label for="pv_featured_carousel">
		    		<input type="checkbox" name="pv_featured_carousel" id="pv_featured_carousel" <?php checked( $value, 'on' ); ?> />
		    		<?php esc_html_e( 'Check if you want to add carousel with featured products to your shop page', 'dashstore' ) ?>
		    	</label>
			</td>
		  </tr>

		  <tr>
		    <th><?php esc_html_e( 'Vendor question form', 'dashstore' ); ?></th>
		    <td>
		    <?php $value = get_user_meta( $user_id,'pv_question_form', true ); ?>
		    	<label for="pv_question_form">
		    		<input type="checkbox" name="pv_question_form" id="pv_question_form" <?php checked( $value, 'on' ); ?> />
		    		<?php esc_html_e( 'Check if you want to add "Ask a question about this product" form to "Seller Tab" on each of your products', 'dashstore' ) ?>
		    	</label>
			</td>
		  </tr>

		</tbody>
	  </table>

	<?php
	}
	// front end
	function dash_add_frontend_vendor_fields() { ?>

	  <?php $user_id = get_current_user_id(); ?>

		<h2 class="settings-title"><?php esc_html_e( 'Vendor Extra Options', 'dashstore' ); ?></h2>

		<div class="pv_logo_image_container">
	    <p><strong><?php esc_html_e( 'Upload Logo Image', 'dashstore' ); ?></strong><br/><br/>
		    <input name="pv_logo_image" id="pv_logo_image" type="text" value="<?php echo esc_url( get_user_meta( $user_id, 'pv_logo_image', true ) ); ?>" />
			<span id="pv_logo_image_button" class="button pt_upload_image_button"><?php esc_html_e( 'Upload', 'dashstore' ); ?></span>
		</p>
	  </div>

	  <div class="pv_logo_position_container">
	    <p><strong><?php esc_html_e( 'Logo Position', 'dashstore' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_logo_position', true );
	    	  if ( $value == '' ) $value = 'left'; ?>
	    <p>
		    <input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_left" value="left" <?php checked( $value, 'left'); ?>/><label for="logo_position_left"><?php esc_html_e( ' Left', 'dashstore' ); ?></label><br />
			<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_center" value="center" <?php checked( $value, 'center'); ?>/><label for="logo_position_center"><?php esc_html_e( ' Center', 'dashstore' ); ?></label><br />
			<input type="radio" class="input-radio" name="pv_logo_position" id="logo_position_right" value="right" <?php checked( $value, 'right'); ?>/><label for="logo_position_right"><?php esc_html_e( ' Right', 'dashstore' ); ?></label>
		</p>
	  </div>

	  <div class="pv_featured_carousel_container">
	    <p><strong><?php esc_html_e( 'Products Carousel', 'dashstore' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_featured_carousel', true ); ?>
	    <p>
	    	<input type="checkbox" class="input-checkbox" name="pv_featured_carousel" id="pv_featured_carousel" <?php checked( $value, 'on' ); ?> /><label class="checkbox" for="pv_featured_carousel"><?php esc_html_e( 'Check if you want to add carousel with featured products to your shop page', 'dashstore' ) ?></label>
		</p>
	  </div>

	  <div class="pv_question_form_container">
	    <p><strong><?php esc_html_e( 'Vendor question form', 'dashstore' ); ?></strong></p>
	    <?php $value = get_user_meta( $user_id,'pv_question_form', true ); ?>
	    <p>
	    	<input type="checkbox" class="input-checkbox" name="pv_question_form" id="pv_question_form" <?php checked( $value, 'on' ); ?> /><label class="checkbox" for="pv_question_form"><?php esc_html_e( 'Check if you want to add "Ask a question about this product" form to "Seller Tab" on each of your products', 'dashstore' ) ?></label>
		</p>
	  </div>

	<?php }
	// Save new fields
	function dash_save_new_vendor_fields($user_id) {
		if ( isset( $_POST['pv_logo_image'] ) ) {
			update_user_meta( $user_id, 'pv_logo_image', $_POST['pv_logo_image'] );
		}
		if ( isset( $_POST['pv_logo_position'] ) ) {
			update_user_meta( $user_id, 'pv_logo_position', $_POST['pv_logo_position'] );
		}
		if ( isset( $_POST['pv_featured_carousel'] ) ) {
		    update_user_meta( $user_id, 'pv_featured_carousel', $_POST['pv_featured_carousel'] );
		} else {
		  	update_user_meta( $user_id, 'pv_featured_carousel', 'off' );
		}
		if ( isset( $_POST['pv_question_form'] ) ) {
		    update_user_meta( $user_id, 'pv_question_form', $_POST['pv_question_form'] );
		} else {
		  	update_user_meta( $user_id, 'pv_question_form', 'off' );
		}
	}

	// Fields for WC Vendors Pro
	if ( class_exists('WCVendors_Pro') ) {
		add_action( 'wcvendors_settings_after_vacation_mode', 'dash_add_frontend_vendor_pro_fields' );
		add_action( 'wcv_pro_store_settings_saved', 'dash_save_new_vendor_pro_fields' );
		add_action( 'wcv_after_variations_tab', 'dash_add_frontend_vendor_pro_product_fields' );
		add_action( 'wcv_save_product_meta', 'dash_save_new_vendor_pro_product_fields' );
	}
	// New fields to store settings
	function dash_add_frontend_vendor_pro_fields() {
		$user_id = get_current_user_id(); ?>

			<hr style="clear: both;" />
			<h2><?php esc_html_e( 'Vendor Extra Options', 'dashstore' ); ?></h2>

			<?php
			$featured_carousel_value = get_user_meta( $user_id, 'dash_vendor_featured_carousel', true );
			WCVendors_Pro_Form_Helper::input( array(
				'id' => 'dash_vendor_featured_carousel',
				'label' => esc_html__( 'Check if you want to add carousel with featured products to your shop page', 'dashstore' ),
				'desc_tip' => false,
				'description' => '',
				'type' => 'checkbox',
				'value'	=> $featured_carousel_value,
				)
			);

			$question_form_value = get_user_meta( $user_id, 'dash_vendor_question_form', true );
			WCVendors_Pro_Form_Helper::input( array(
				'id' => 'dash_vendor_question_form',
				'label' => esc_html__( 'Check if you want to add "Ask a question about this product" form to "Seller Tab" on each of your products', 'dashstore' ),
				'desc_tip' => false,
				'description' => '',
				'type' => 'checkbox',
				'value'	=> $question_form_value,
				)
			);
  }
	// Save data from new fields
	function dash_save_new_vendor_pro_fields( $user_id ) {
		if ( isset( $_POST['dash_vendor_featured_carousel'] ) ) {
				update_user_meta( $user_id, 'dash_vendor_featured_carousel', $_POST['dash_vendor_featured_carousel'] );
		} else {
				update_user_meta( $user_id, 'dash_vendor_featured_carousel', 'no' );
		}
		if ( isset( $_POST['dash_vendor_question_form'] ) ) {
				update_user_meta( $user_id, 'dash_vendor_question_form', $_POST['dash_vendor_question_form'] );
		} else {
				update_user_meta( $user_id, 'dash_vendor_question_form', 'no' );
		}
	}

	// Extra fields for products
	function dash_add_frontend_vendor_pro_product_fields( $object_id ) { ?>
		<hr style="clear: both;" />
		<h2><?php esc_html_e( 'Product Extra Options', 'dashstore' ); ?></h2>

		<?php $pt_product_extra_gallery = ( get_post_meta($object_id, 'pt_product_extra_gallery', true) != '' ) ? get_post_meta($object_id, 'pt_product_extra_gallery') : 'no';
					$pt_vendor_special_offers_carousel = ( get_post_meta($object_id, 'pt_vendor_special_offers_carousel', true) != '' ) ? get_post_meta($object_id, 'pt_vendor_special_offers_carousel') : 'no';

					WCVendors_Pro_Form_Helper::input( array(
						'id' => 'pt_product_extra_gallery',
						'label' => esc_html__( 'Use extra gallery for this product', 'dashstore' ),
						'desc_tip' => true,
						'description' => esc_html__( 'Check the checkbox if you want to use extra gallery (appeared on hover) for this product. The first 3 images of the product gallery are going to be used for gallery.', 'dashstore' ),
						'type' => 'checkbox',
						'value'	=> $pt_product_extra_gallery,
						)
					);

					WCVendors_Pro_Form_Helper::input( array(
						'id' => 'pt_vendor_special_offers_carousel',
						'label' => esc_html__( 'Add this product to "Special Offers" carousel', 'dashstore' ),
						'desc_tip' => true,
						'description' => esc_html__( 'Check the checkbox if you want to add this product to the "Special Offers" carousel on your Vendor Store Page.', 'dashstore' ),
						'type' => 'checkbox',
						'value'	=> $pt_vendor_special_offers_carousel,
						)
					);
	}
	// Save data from new fields
	function dash_save_new_vendor_pro_product_fields( $post_id ) {
		if ( isset($_POST['pt_product_extra_gallery']) ) {
				update_post_meta( $post_id, 'pt_product_extra_gallery', $_POST['pt_product_extra_gallery'] );
		} else {
				update_post_meta( $post_id, 'pt_product_extra_gallery', 'no' );
		}
		if ( isset($_POST['pt_vendor_special_offers_carousel']) ) {
				update_post_meta( $post_id, 'pt_vendor_special_offers_carousel', $_POST['pt_vendor_special_offers_carousel'] );
		} else {
				update_post_meta( $post_id, 'pt_vendor_special_offers_carousel', 'no' );
		}
	}


	// ----- 3. Related products by vendors
	if (dash_get_option('show_wcv_related_products')=='on') {
		if (!function_exists('dash_output_vendors_related_products')) {
			function dash_output_vendors_related_products() {
				global $product;

				$vendor = get_the_author_meta('ID');
				$posts_per_page = (dash_get_option('wcv_qty') != '') ? dash_get_option('wcv_qty') : '3';
				$sold_by = WCV_Vendors::get_vendor_sold_by( $vendor );

				$args = apply_filters('woocommerce_related_products_args', array(
					'post_type'						=> 'product',
					'ignore_sticky_posts'	=> 1,
					'no_found_rows' 			=> 1,
					'posts_per_page' 			=> $posts_per_page,
					'orderby' 						=> 'name',
					'author' 							=> $vendor,
					'post__not_in'				=> array($product->get_id())
				) );

				$products = new WP_Query( $args );
				if ( $products->have_posts() ) : ?>

				<div class="wcv-related products">

					<h2><?php echo esc_html__( 'More Products by ', 'dashstore' ).esc_attr($sold_by); ?></h2>

					<?php woocommerce_product_loop_start(); ?>

						<?php while ( $products->have_posts() ) : $products->the_post(); ?>

							<?php wc_get_template_part( 'content', 'product' ); ?>

						<?php endwhile; // end of the loop. ?>

					<?php woocommerce_product_loop_end(); ?>

				</div>

				<?php endif;
				wp_reset_postdata();
			}
		}
		add_action('woocommerce_after_single_product_summary', 'dash_output_vendors_related_products', 15);
	}

	// Disable mini header on single product page
	if (WC_Vendors::$pv_options->get_option( 'shop_headers_enabled' ) ) {
		remove_action( 'woocommerce_before_single_product', array('WCV_Vendor_Shop', 'vendor_mini_header'));
	}


	// ----- 4. Add media Upload script for WC Vendors
	if ( !class_exists('WCVendors_Pro') ) {
		function dash_add_media_upload_scripts(){
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
	  add_action( 'wp_enqueue_scripts', 'dash_add_media_upload_scripts' );
	  add_action( 'admin_enqueue_scripts', 'dash_add_media_upload_scripts' );
	}


	// ----- 5. Add extra info for vendors on "My Account"
	if (!function_exists('dash_add_vendors_info')) {
		function dash_add_vendors_info() {
			$user = wp_get_current_user();
			if ( in_array( 'vendor', (array) $user->roles ) ) { ?>
				<div class="account-vendor-options">
					<h2><?php esc_html_e("Vendor's Options", 'dashstore'); ?></h2>
						<?php // Get url's for vendors pages
						if ( class_exists('WCV_Vendors') ) {
								$dashboard_url = get_permalink(WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' ));
							}
							if ( class_exists('WCVendors_Pro') ) {
									$dashboard_url = get_permalink(WCVendors_Pro::get_option( 'dashboard_page_id' ));
							} ?>
							<p><?php esc_html_e('Follow this link to get to the vendor dashboard, where you can control your store, add products, generate reports on accomplished deals etc.', 'dashstore'); ?></p>
							<a class="btn btn-primary rounded" href="<?php echo esc_url($dashboard_url); ?>" title="<?php esc_html_e('Go to Vendor Dashboard', 'dashstore'); ?>" rel="nofollow" target="_self"><?php esc_html_e('Go to Vendor Dashboard', 'dashstore'); ?></a>
				</div>
			<?php } elseif ( in_array( 'pending_vendor', (array) $user->roles ) ) { ?>
				<div class="account-vendor-options">
					<h2><?php esc_html_e("Vendor's Options", 'dashstore'); ?></h2>
							<p><?php esc_html_e('Your account has not yet been approved to become a vendor. When it is, you will receive an email telling you that your account is approved!', 'dashstore'); ?></p>
				</div>

			<?php }
		}
		add_action( 'woocommerce_before_my_account', 'dash_add_vendors_info' );
	}


	// ----- 6. Simple feedback form for customers
	if ( dash_get_option('enable_vendors_product_feedback') == 'on') {

		// Start session for proper captcha validation
		add_action('init', 'dash_start_session', 1);
		add_action('wp_logout', 'dash_end_session');
		add_action('wp_login', 'dash_end_session');
		function dash_start_session() {
			if(!session_id()) {
				session_start();
			}
		}
		function dash_end_session() {
			if(session_id()) {
				session_destroy();
			}
		}

		/* Enqueue scripts & styles */
		function dash_vendor_feedback_scripts() {
			wp_enqueue_script( 'ajax-wcv-feedback-script', get_template_directory_uri(). '/js/ajax-wcv-feedback-script.js', array('jquery'), '1.0', true );
		    wp_localize_script( 'ajax-wcv-feedback-script', 'ajax_wcv_form_object', array(
		        'ajaxurl' => admin_url( 'admin-ajax.php' ),
		        'loadingmessage' => esc_html__('Sending e-mail, please wait...', 'dashstore')
		    ));
		}
		add_action( 'wp_ajax_nopriv_pt_ajax_send_mail_to_vendor', 'dash_deliver_mail' );
		add_action( 'wp_ajax_pt_ajax_send_mail_to_vendor', 'dash_deliver_mail' );
		add_action( 'init', 'dash_vendor_feedback_scripts' );

		/* Add feedback form to Seller Info tab */
		if (!function_exists('dash_html_form_code')) {
			function dash_html_form_code() {
				// Start session for captcha validation
				$_SESSION['captcha-rand'] = isset($_SESSION['captcha-rand']) ? $_SESSION['captcha-rand'] : rand(100, 999);

				$output = '<div class="vendor-feed-container">';
				$output .= '<a class="btn btn-primary rounded" role="button" data-toggle="collapse" href="#collapseFeedForm" aria-expanded="false" aria-controls="collapseExample">
								 '.esc_html__('Ask a question about this Product', 'dashstore').'
								</a>';
				$output .= '<div class="collapse" id="collapseFeedForm">';
				$output .= '<form id="vendor-feedback" class="about-product-question" method="post">
							 '.wp_nonce_field('ajax-vendor-feedback-nonce', 'security').
							 '<input id="vendor-mail" type="hidden" name="cf-vendor-mail" value="'.esc_url(get_the_author_meta('user_email')).'">';
				$output .= '<div>';
				$output .= '<p class="form-row form-row-wide">
							<label for="sender-name">'.esc_html__('Your Name ', 'dashstore').'<abbr title="required" class="required">*</abbr></label>
							<input required aria-required="true" id="sender-name" type="text" name="name" title="'.esc_html__('Digits and Letters only.', 'dashstore').'" pattern="'.apply_filters('feedback_form_sender_pattern', '[a-zA-Z0-9 ]+').'" value="' . ( isset( $_POST["name"] ) ? esc_attr( $_POST["name"] ) : '' ) . '" />
							<input type="text" name="firstname" id="sender-firstname" maxlength="50" value="' . ( isset( $_POST["firstname"] ) ? esc_attr( $_POST["firstname"] ) : '' ) . '" />
							<input type="text" name="lastname" id="sender-lastname" maxlength="50" value="' . ( isset( $_POST["lastname"] ) ? esc_attr( $_POST["lastname"] ) : '' ) . '" />
							</p>';
				$output .= '<p class="form-row form-row-wide">
							<label for="sender-email">'.esc_html__('Your Email ', 'dashstore').'<abbr title="required" class="required">*</abbr></label>
							<input required aria-required="true" id="sender-email" type="email" name="email" value="' . ( isset( $_POST["email"] ) ? esc_attr( $_POST["email"] ) : '' ) . '" />
							</p>';
				$output .= '<p class="form-row form-row-wide">
							<label for="subject">'.esc_html__('Subject ', 'dashstore').'<abbr title="required" class="required">*</abbr></label>
							<input required aria-required="true" title="'.esc_html__('Digits and Letters only.', 'dashstore').'" id="subject" type="text" name="subject" pattern="'.apply_filters('feedback_form_subject_pattern', '[a-zA-Z0-9 ]+').'" value="' . ( isset( $_POST["subject"] ) ? esc_attr( $_POST["subject"] ) : esc_html__('Question about ', 'dashstore').esc_attr(get_the_title()) ) . '" />
							</p>';
				$output .= '</div>';
				$output .= '<div>';
				$output .= '<p class="form-row form-row-wide">
							<label for="captcha">'.esc_html__('Captcha, enter number: ', 'dashstore').$_SESSION['captcha-rand'].' <abbr title="required" class="required">*</abbr></label>
							<input required aria-required="true" id="captcha" type="text" name="captcha" maxlength="3" pattern="\d*" value="' . ( isset( $_POST["captcha"] ) ? esc_html( $_POST["captcha"] ) : '' ) . '" />
							</p>';
				$output .= '<p class="form-row form-row-wide">
							<label for="text-message">'.esc_html__('Your Message ', 'dashstore').'<abbr title="required" class="required">*</abbr></label>
							<textarea required aria-required="true" id="text-message" name="message">' . ( isset( $_POST["message"] ) ? esc_attr( $_POST["message"] ) : '' ) . '</textarea>
							</p>';
				$output .= '</div>';
				$output .= '<input class="submit-btn" type="submit" name="cf-submitted" value="'.esc_html__('Send', 'dashstore').'">
							<p class="status"></p>';
				$output .= '</form>';
				$output .= '</div></div>';

				$vendor_id = WCV_Vendors::get_vendor_from_product( $product_id );
				$question_form = get_user_meta( $vendor_id , 'pv_question_form', true );

				if ( $question_form === 'yes' ) {
					return $output;
				}
			}
			add_filter( 'wcv_after_seller_info_tab', 'dash_html_form_code' );
		}

		/* Delivery handle for form */
		if (!function_exists('dash_deliver_mail')) {
			function dash_deliver_mail() {
				// First check the nonce, if it fails the function will break
				check_ajax_referer( 'ajax-vendor-feedback-nonce', 'security' );

				$error = false;
				$sent = false;

				// sanitize form values
				$name      = sanitize_text_field( $_POST["sender"] );
				$email     = sanitize_email( $_POST["sender-email"] );
				$subject   = sanitize_text_field( $_POST["subject"] );
				$message   = esc_textarea( $_POST["text"] );
				$to        = sanitize_email( $_POST["to-email"] );
				$firstname = sanitize_text_field( $_POST["sender-first-name"] );
				$lastname  = sanitize_text_field( $_POST["sender-last-name"] );
				$captcha   = esc_html( $_POST["captcha"] );

				// Validate captcha
				if ( $captcha != $_SESSION['captcha-rand'] ) {
						$error = true;
						echo json_encode( array( 'message' => esc_html__('Please enter the correct number for captcha. If still incorrect please refresh browser.', 'dashstore'), ) );
						die();
				}

				// Validate first honeypot field
				if ( strlen($firstname)>0 || strlen($lastname)>0 ) {
						$error = true;
						echo json_encode( array( 'message' => esc_html__('An unexpected error occurred.', 'dashstore'), ) );
						die();
				}

				$headers = "From: $name <$email>" . "\r\n";

				if ( wp_mail( $to, $subject, $message, $headers ) && $error == false) {
						$sent = true;
						echo json_encode( array( 'message' => esc_html__('Thanks for contacting me, expect a response soon.', 'dashstore'), ) );
				} else {
						echo json_encode( array( 'message' => esc_html__('An unexpected error occurred.', 'dashstore'), ) );
				}

				// Send message and unset captcha variabele
				if(isset($sent) && $sent == true) {
					unset($_SESSION['captcha-rand']);
				}

				die();
			}
		}

	} // end of if dash_get_option('enable_vendors_product_feedback') == 'on'


	// ----- 7. New Image Sizes for vendors
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'dash-vendor-main-logo', 150, 150, false );
		add_image_size( 'dash-vendor-logo-icon', 50, 50, true );
	}


	// ----- 8. Modifying Vendor's rating tab
	if ( class_exists('WCVendors_Pro') ) {
		// Remove rating tab
		if (!function_exists('remove_vendors_rating_tab')) {
			function remove_vendors_rating_tab($tabs) {
				if ( $tabs['vendor_ratings_tab'] ) {
					unset( $tabs['vendor_ratings_tab'] );
				}
				return $tabs;
			}
			add_filter( 'woocommerce_product_tabs', 'remove_vendors_rating_tab' );
		}

		// Add rating to seller info tab
		if (!function_exists('dash_additional_vendors_info')) {
			function dash_additional_vendors_info() {
				$vendor_id     = WCV_Vendors::get_vendor_from_product( get_the_ID() );
				if ( WCV_Vendors::is_vendor( $vendor_id ) ) {
					// Store logo
					$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), 'dash-vendor-main-logo' );
					$store_icon = '';
					if ( is_array( $store_icon_src ) ) {
						$store_icon = '<img src="'. esc_url($store_icon_src[0]).'" alt="vendor logo" class="store-icon" />';
					}
					// Socials
					$twitter_username 	= get_user_meta( $vendor_id , '_wcv_twitter_username', true );
					$instagram_username = get_user_meta( $vendor_id , '_wcv_instagram_username', true );
					$facebook_url 		  = get_user_meta( $vendor_id , '_wcv_facebook_url', true );
					$linkedin_url 		  = get_user_meta( $vendor_id , '_wcv_linkedin_url', true );
					$youtube_url 		    = get_user_meta( $vendor_id , '_wcv_youtube_url', true );
					$googleplus_url 	  = get_user_meta( $vendor_id , '_wcv_googleplus_url', true );
					$socials = '';
					if ( $facebook_url != '') { $socials .= '<li><a href="'.esc_url($facebook_url).'" target="_blank"><i class="fa fa-facebook-square"></i></a></li>'; }
					if ( $instagram_username != '') { $socials .= '<li><a href="//instagram.com/'.esc_html($instagram_username).'" target="_blank"><i class="fa fa-instagram"></i></a></li>'; }
					if ( $twitter_username != '') { $socials .= '<li><a href="//twitter.com/'.esc_html($twitter_username).'" target="_blank"><i class="fa fa-twitter-square"></i></a></li>'; }
					if ( $googleplus_url != '') { $socials .= '<li><a href="'.esc_url($googleplus_url).'" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>'; }
					if ( $youtube_url != '') { $socials .= '<li><a href="'.esc_url($youtube_url).'" target="_blank"><i class="fa fa-youtube-square"></i></a></li>'; }
					if ( $linkedin_url != '') { $socials .= '<li><a href="'.esc_url($linkedin_url).'" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>'; }
						// Ratings
						$ratings = '';
						if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) {
							$average_rate = WCVendors_Pro_Ratings_Controller::get_ratings_average( $vendor_id );
							$rate_count = WCVendors_Pro_Ratings_Controller::get_ratings_count( $vendor_id );
							$url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id ) . '?ratings=all';
							if ( $average_rate !=0 ) {
								$ratings .= esc_html__('Rating: ', 'dashstore').'<span>'.esc_attr($average_rate).'</span>'.__(' based on ', 'dashstore').sprintf( _n( '1 rating.', '%s ratings.', $rate_count, 'dashstore' ), $rate_count);
								$ratings .= '<a href="'.esc_url($url).'">'.esc_html__('View all ratings', 'dashstore').'</a>';
							} else {
								$ratings .= esc_html__("Rating: This Seller still doesn't have any ratings yet.", 'dashstore');
							}
						}

						// Output all info
						$store_url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id );
						$store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );
						$store_info = '<div class="pv_additional_seller_info">';
						if ($store_icon != '') {
							$store_info .= '<div class="store-brand">'.$store_icon.'</div>';
						}
						$store_info .= '<div class="store-info">';
						$store_info .= '<h3><a href="'.esc_url($store_url).'">'.esc_attr($store_name).'</a></h3>';
					$store_info .= '<div class="rating-container">'.$ratings.'</div>';
					if ($socials != '') {
							$store_info .= '<ul class="social-icons">'.$socials.'</ul>';
						}
						$store_info .= '</div></div>';
						return $store_info;
				}
			}
			add_filter( 'wcv_before_seller_info_tab', 'dash_additional_vendors_info' );
		}
	}

	/* Removing empty label */
	function dash_remove_label_on_signup() {
		return array(
			'type' => 'hidden',
			'id' => '_wcv_vendor_application_id',
			'value'	=> get_current_user_id(),
			'show_label' => false
		);
	}
	add_filter( 'wcv_vendor_application_id', 'dash_remove_label_on_signup');

} // end of file
