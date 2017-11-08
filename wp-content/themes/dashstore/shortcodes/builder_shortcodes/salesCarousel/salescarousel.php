<?php

if ( ! class_exists( 'IG_Salescarousel' ) ) {

	class IG_Salescarousel extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode']        = strtolower( __CLASS__ );
			$this->config['name']             = esc_html__( 'PT Sales Carousel', 'dashstore' );
			$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );
            $this->config['edit_using_ajax'] = true;
            $this->config['exception'] = array(
				'default_content'  => esc_html__( 'PT Sales Carousel', 'dashstore' ),
				'data-modal-title' => esc_html__( 'PT Sales Carousel', 'dashstore' ),

				'admin_assets' => array(
					// Shortcode initialization
					'row.js',
					'ig-colorpicker.js',
				),
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'content' => array(
					array(
						'name'    => esc_html__( 'Element Title', 'dashstore' ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'role'    => 'title',
						'tooltip' => esc_html__( 'Set title for current element for identifying easily', 'dashstore' )
					),
					array(
						'id'            => 'carousel_items',
						'type'          => 'group',
						'shortcode'     => ucfirst( __CLASS__ ),
						'sub_item_type' => $this->config['has_subshortcode'],
						'sub_items'     => array(
							array('std' => ''),
							array('std' => ''),
						),
					),
				),
				'styling' => array(
					array(
						'name'                 => esc_html__( 'Dimension', 'dashstore' ),
						'container_class'      => 'combo-group',
						'id'                   => 'dimension',
						'type'                 => 'dimension',
						'extended_ids'         => array( 'dimension_width', 'dimension_height', 'dimension_width_unit' ),
						'dimension_width'      => array( 'std' => '' ),
						'dimension_height'     => array( 'std' => '' ),
						'dimension_width_unit' => array(
							'options' => array( 'px' => 'px', '%' => '%' ),
							'std'     => 'px',
						),
                        'tooltip' => esc_html__( 'Set width and height of element', 'dashstore' ),
					),
					array(
                        'name'    => esc_html__( 'Transition Type', 'dashstore' ),
                        'id'      => 'transition_type',
                        'type'    => 'select',
                        'std'     => 'fade',
                        'options' => array(
							'fade' => esc_html__( 'Fade', 'dashstore' ),
							'backSlide' => esc_html__( 'Back Slide', 'dashstore' ),
							'goDown' => esc_html__( 'Go Down', 'dashstore' ),
							'fadeUp' => esc_html__( 'Fade Up', 'dashstore' ),
						),
                    ),
					array(
						'name'    => esc_html__( 'Show Page Navigation', 'dashstore' ),
						'id'      => 'show_indicator',
						'type'    => 'radio',
						'std'     => 'yes',
						'options' => array( 'yes' => esc_html__( 'Yes', 'dashstore' ), 'no' => esc_html__( 'No', 'dashstore' ) ),
                        'tooltip' => esc_html__( 'Show/hide navigation buttons under your carousel', 'dashstore' ),
					),
					array(
						'name'    => esc_html__( 'Show Arrows', 'dashstore' ),
						'id'      => 'show_arrows',
						'type'    => 'radio',
						'std'     => 'yes',
						'options' => array( 'yes' => esc_html__( 'Yes', 'dashstore' ), 'no' => esc_html__( 'No', 'dashstore' ) ),
                        'tooltip' => esc_html__( 'Show/hide arrow buttons', 'dashstore' ),
					),
					array(
						'name'       => esc_html__( 'Autoplay', 'dashstore' ),
						'id'         => 'autoplay',
						'type'       => 'radio',
						'std'        => 'false',
						'options'    => array( 'yes' => esc_html__( 'Yes', 'dashstore' ), 'no' => esc_html__( 'No', 'dashstore' ) ),
                        'tooltip' => esc_html__( 'Whether to running your carousel automatically or not', 'dashstore' ),
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
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			$arr_params    = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );

			$html_output = '';
			$lazy_param = '';

			// Container Styles
			$container_class = 'pt-sales-carousel '.$css_suffix;
			$container_id = uniqid('owl',false);
			if ($arr_params['lazyload'] == 'yes') {	$lazy_param = ' data-expand="-100"'; $container_class = $container_class.' lazyload'; }
			$container_class = ( ! empty( $container_class ) ) ? ' class="' . $container_class . '"' : '';

			$styles        = array();
			if ( ! empty( $dimension_width ) )
				$styles[] = "width : {$dimension_width}{$dimension_width_unit};";
			if ( ! empty( $dimension_height ) )
				$styles[] = "height : {$dimension_height}px;";
			$styles = trim( implode( ' ', $styles ) );
			$styles = ! empty( $styles ) ? "style='$styles'" : '';

			// Carousel Parameters
			$owlAutoPlay = 'false';
			if ( $autoplay == 'yes' )
				$owlAutoPlay = 'true';
			$owlPagination = 'false';
			if ( $show_indicator == 'yes' )
				$owlPagination = 'true';
			$owlTransition = $transition_type;

			// Get Carousel Items
			$sub_shortcode         = IG_Pb_Helper_Shortcode::remove_autop( $content );
			$items                 = explode( '<!--separate-->', $sub_shortcode );
			$carousel_content      = "" . implode( '', $items ) . '';

			// Output Carousel
			$html_output .= "<div{$container_class} id='{$container_id}'{$lazy_param}>";
			if ($el_title && $el_title!='') { $html_output .= "<h3>{$el_title}</h3>"; }
			$html_output .= "<div class='wrapper'>";
			if ( $show_arrows == 'yes' ) { $html_output .= "<span class='prev'></span><span class='next'></span>"; }
			$html_output .= "<ul class='products' {$styles}>";
			$html_output .= $carousel_content;
			$html_output .= "</ul></div></div>";

			$html_output.='
				<script type="text/javascript">
					(function($) {
						$(document).ready(function() {
							var owl = $("#'.$container_id.' ul.products");

							owl.owlCarousel({
								navigation : false,
								pagination : '.$owlPagination.',
								autoPlay   : '.$owlAutoPlay.',
								slideSpeed : 300,
								paginationSpeed : 400,
								singleItem : true,
								transitionStyle : "'.$owlTransition.'",
							});

							// Custom Navigation Events
							$("#'.$container_id.'").find(".next").click(function(){
								owl.trigger("owl.next");
							})
							$("#'.$container_id.'").find(".prev").click(function(){
								owl.trigger("owl.prev");
							})
						});
					})(jQuery);
				</script>';

			return $this->element_wrapper( $html_output, $arr_params );
		}

	}

}
