<?php

if ( ! class_exists( 'IG_Producttabs' ) ) {

	class IG_Producttabs extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode']        = strtolower( __CLASS__ );
			$this->config['name']             = esc_html__( 'PT Product Tabs', 'dashstore' );
			$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );
            $this->config['edit_using_ajax'] = true;
            $this->config['exception'] = array(
				'default_content'  => esc_html__( 'PT Product Tabs', 'dashstore' ),
				'data-modal-title' => esc_html__( 'PT Product Tabs', 'dashstore' ),
			
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
						'id'            => 'product_items',
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

			// Container Styles
			$container_class = 'pt-product-tabs '.$css_suffix;
			$container_class = ( ! empty( $container_class ) ) ? ' class="' . $container_class . '"' : '';

			$styles        = array();
			if ( ! empty( $dimension_width ) )
				$styles[] = "width : {$dimension_width}{$dimension_width_unit};";
			if ( ! empty( $dimension_height ) )
				$styles[] = "height : {$dimension_height}px;";
			$styles = trim( implode( ' ', $styles ) );
			$styles = ! empty( $styles ) ? " style='$styles'" : '';

			// Get Contents
			$sub_shortcode = IG_Pb_Helper_Shortcode::remove_autop( $content );
			$pieces = explode("<!-- #tab-content -->", $sub_shortcode);
			$nav_links = array();
			$tab_contents = array();
			$first = true;
			foreach ($pieces as $piece) {
				/* Default active class */
				if($first) {
					$first = false;
					// get active nav link
					$r1 = explode('<!-- tab-nav -->', $piece);
					if (isset($r1[1])){
				        $r1 = explode('<!-- #tab-nav -->', $r1[1]);
				        $new_output_1 = str_replace( '<li>', '<li class="active">', $r1[0]);
				        $nav_links[] = $new_output_1;
				    }

				    // get active tab content
				    $r2 = explode('<!-- tab-content -->', $piece);
					if (isset($r2[1])){
				        $r2 = explode('<!-- #tab-content -->', $r2[1]);
				        $new_output_2 = str_replace( '<li class=""', '<li class="active"', $r2[0]);
				        $tab_contents[] = $new_output_2;
				    }
				} else {
					// get nav link
					$r1 = explode('<!-- tab-nav -->', $piece);
					if (isset($r1[1])){
				        $r1 = explode('<!-- #tab-nav -->', $r1[1]);
				        $nav_links[] = $r1[0];
				    }

				    // get tab content
				    $r2 = explode('<!-- tab-content -->', $piece);
					if (isset($r2[1])){
				        $r2 = explode('<!-- #tab-content -->', $r2[1]);
				        $tab_contents[] = $r2[0];
				    }
				}
			}
			unset($piece);
			$nav_links = implode("", $nav_links);
			$tab_contents = implode("", $tab_contents);

			// Output Tabs
			$html_output .= "<div{$container_class}{$styles}>";
			$html_output .= "<ul class='nav-tab'>{$nav_links}</ul>";
			$html_output .= "<ul class='nav-contents'>{$tab_contents}</ul>";
			$html_output .= "</div>";

			return $this->element_wrapper( $html_output, $arr_params );
		}

	}

} 