<?php
/**
 * @version    $Id$
 * @package    IG Pagebuilder
 * @author     InnoGearsTeam <support@TI.com>
 * @copyright  Copyright (C) 2012 TI.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.TI.com
 * Technical Support:  Feedback - http://www.TI.com
 */
if ( ! class_exists( 'IG_Item_Carousel' ) ) {

	class IG_Item_Carousel extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => esc_html__( 'Carousel Item', 'dashstore' )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => esc_html__( 'Image File', 'dashstore' ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Select background image for item', 'dashstore' )
					),
					array(
            'name'    => esc_html__( 'Image Size', 'dashstore' ),
            'id'      => 'image_size',
            'type'    => 'select',
            'std'     => 'medium',
            'options' => array(
							'thumbnail' => esc_html__( 'Thumbnail', 'dashstore' ),
							'dash-carousel-medium' => esc_html__( 'Medium', 'dashstore' ),
							'dash-carousel-large' => esc_html__( 'Large', 'dashstore' ),
						),
                    ),
					array(
						'name'  => esc_html__( 'Heading', 'dashstore' ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
                        'tooltip' => esc_html__( 'Enter heading text for item', 'dashstore' ),
					),
					array(
						'name'  => esc_html__( 'Short Description', 'dashstore' ),
						'id'    => 'description',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
                        'tooltip' => esc_html__( 'Enter description text for item', 'dashstore' ),
					),
					array(
						'name'       => esc_html__( 'URL for detailed view', 'dashstore' ),
						'id'         => 'url',
						'type'       => 'text_field',
						'class'      => 'input-sm',
						'std'        => '',
						'tooltip'    => esc_html__( 'Url of link for detailed view', 'dashstore' ),
					),
					array(
						'name'       => esc_html__( 'Use as banners rotator?',  'dashstore' ),
						'id'         => 'rotator',
						'type'       => 'radio',
						'std'        => 'no',
						'options'    => array( 'yes' => esc_html__( 'Yes',  'dashstore' ), 'no' => esc_html__( 'No',  'dashstore' ) ),
                        'tooltip' => esc_html__( 'Whether to show carousel as banner rotator with buttons',  'dashstore' ),
					),
					array(
						'name'  => esc_html__( 'Button Text (shown with banner rotator)',  'dashstore' ),
						'id'    => 'btn_txt',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
                        'tooltip' => esc_html__( 'Enter text for button',  'dashstore' ),
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			extract( shortcode_atts( $this->config['params'], $atts ) );

			$show_rotator = false;
			if ( $rotator == 'yes' )
				$show_rotator = true;

			$html_output = '';

			if (! empty( $image_file )) {
				$image_id   = IG_Pb_Helper_Functions::get_image_id( $image_file );
				$img = wp_get_attachment_image( $image_id, $image_size );
			} else {
				$img = '';
			}

			$header = ! empty( $heading ) ? '<h3>'.$heading.'</h3>' : '';
			$text = ! empty( $description ) ? '<span>'.$description.'</span>' : '';
			$quick_view = '<a href="'.$image_file.'" title="'.esc_html__('Quick View', 'dashstore').'" rel="nofollow" data-magnific="link"><i class="fa fa-search"></i></a>';
			$button = ! empty( $url ) ? '<a href="'.$url.'" title="'.esc_html__('Learn More', 'dashstore').'" rel="bookmark"><i class="fa fa-link"></i></a>' : '';

			if ($show_rotator) {
				$html_output .= '<div class="item-wrapper rotator"><figure><figcaption>';
				$html_output .= $header.$text.'</figcaption>';
				$html_output .= $img;
				$html_output .= '<a href="'.$url.'" title="'.esc_html__('Learn More', 'dashstore').'" rel="bookmark">'.$btn_txt.'</a>';
				$html_output .= '</figure></div><!--separate-->';
			} else {
				$html_output .= '<div class="item-wrapper"><figure>';
				$html_output .= $img;
				$html_output .= '<figcaption>';
				$html_output .= '<div class="caption-wrapper">'.$header.$text.'<div class="btn-wrapper">'.$quick_view.$button.'</div></div>';
				$html_output .= '<div class="vertical-helper"></div></figcaption></figure></div><!--separate-->';
			}

			return $html_output;
		}

	}

}
