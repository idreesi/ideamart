<?php

if ( ! class_exists( 'IG_Contact' ) ) :

class IG_Contact extends IG_Pb_Shortcode_Parent {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode'] = strtolower( __CLASS__ );
		$this->config['name'] = esc_html__( 'PT Members', 'dashstore' );
		$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );
        $this->config['edit_using_ajax'] = true;
        $this->config['exception'] = array(
			'default_content'  => esc_html__( 'PT Member Contact',  'dashstore' ),
			'data-modal-title' => esc_html__( 'PT Member Contact',  'dashstore' ),
		);
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'content' => array(
				array(
					'name'    => esc_html__( 'Element Title', 'dashstore' ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => esc_html__( 'Team Contact', 'dashstore' ),
					'role'    => 'title',
					'tooltip' => esc_html__( 'Set title for current element for identifying easily', 'dashstore' )
				),
				array(
					'name'    => esc_html__( 'Image File', 'dashstore' ),
					'id'      => 'image_file',
					'type'    => 'select_media',
					'std'     => '',
					'class'   => 'jsn-input-large-fluid',
					'tooltip' => esc_html__( 'Choose image', 'dashstore' )
				),
				array(
					'name'    => esc_html__( 'Team Member Name', 'dashstore' ),
					'id'      => 'name',
					'type'    => 'text_field',
					'class'   => 'input-sm',
				),
				array(
					'name'    => esc_html__( 'Team Member Occupation', 'dashstore' ),
					'id'      => 'occupation',
					'type'    => 'text_field',
					'class'   => 'input-sm',
				),
				array(
					'name'    => esc_html__( 'Team Member Short Biography', 'dashstore' ),
					'id'      => 'biography',
					'type'    => 'text_field',
					'class'   => 'input-sm',
				),
				array(
					'name'          => esc_html__( 'Buttons', 'dashstore' ),
					'id'            => 'button_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
					),
					'tooltip' 		=> esc_html__( 'Add social network to your contact', 'dashstore' )
				),

			),
			'styling' => array(
				array(
					'name'    => esc_html__( 'Image Position',  'dashstore' ),
					'id'      => 'img_pos',
					'type'    => 'radio',
					'std'     => 'left',
					'options' => array( 'left' => esc_html__( 'Left',  'dashstore' ), 'top' => esc_html__( 'Top',  'dashstore' ), 'center' => esc_html__( 'Center',  'dashstore' ) ),
                    'tooltip' => esc_html__( 'Set image position relative to main container',  'dashstore' ),
				),
				array(
						'name' => esc_html__( 'Add "lazyload" to this element?', 'dashstore' ),
						'id' => 'lazyload',
						'type' => 'radio',
						'std' => 'no',
						'options' => array( 'yes' => esc_html__( 'Yes', 'dashstore' ), 'no' => esc_html__( 'No', 'dashstore' ) ),
				),
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params     = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$html_output = '';
		$lazy_param = '';

		// Container Styles
		$container_class = 'pt-member-contact img-pos-'.$img_pos.' '.$css_suffix;
		if ($arr_params['lazyload'] == 'yes') {	$lazy_param = ' data-expand="-100"'; $container_class = $container_class.' lazyload'; }
		$container_class = ( ! empty( $container_class ) ) ? ' class="' . $container_class . '"' : '';

		// Main Elements
		$image = '';
		if ( $image_file ) {
			$image = '<div class="contact-img-wrapper"><img src="'.esc_url($image_file).'" alt="'.esc_attr($name).'" /></div>';
		}
		$heading = '';
		if ( $name ) {
			$heading = "<h3>{$name}</h3>";
		}
		$sub_heading = '';
		if ( $occupation ) {
			$sub_heading = "<span>{$occupation}</span>";
		}
		$short_bio = '';
		if ( $biography ) {
			$short_bio = "<p>{$biography}</p>";
		}

		$sub_shortcode  = IG_Pb_Helper_Shortcode::remove_autop( $content );
		$items          = explode( '<!--separate-->', $sub_shortcode );
		$items          = array_filter( $items );

		if ($items) {
			$buttons    = "" . implode( '', $items ) . '';
			$social_contacts =  "<div class='contact-btns'>".$buttons."</div>";
		}

		// Shortcode output
		$html_output .= "<div{$container_class}{$lazy_param}>";
		if ( $img_pos == 'top' || $img_pos == 'center' ) { $html_output .= $image; }
		$html_output .= "<div class='text-wrapper'>";
		if ( $img_pos == 'left' ) { $html_output .= $image; }
		if ( $img_pos == 'center' ) {
			$html_output .= '<div class="vertical-wrapper">'.$heading.$sub_heading.'</div>';
		} else {
			$html_output .= $heading.$sub_heading.$short_bio.$social_contacts;
		}
		$html_output .= "</div></div>";

		return $this->element_wrapper( $html_output, $arr_params );
	}
}

endif;
