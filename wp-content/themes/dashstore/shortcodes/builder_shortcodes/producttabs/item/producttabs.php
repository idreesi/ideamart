<?php
if ( ! class_exists( 'IG_Item_Producttabs' ) ) {

	class IG_Item_Producttabs extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => esc_html__( 'Product Tab', 'dashstore' )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => esc_html__( 'Heading', 'dashstore' ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
						'std'   => '',
                        'tooltip' => esc_html__( 'Set the text of your heading items', 'dashstore' ),
					),
					array(
                        'name' 	  => esc_html__( "Enter Category ID", 'dashstore' ),
                        'id'      => 'category_id',
						'type'    => 'text_number',
						'class'   => 'input-mini',
                        'std'     => '',
                        'validate' => 'number',
                    ),
					array(
						'name'     => esc_html__( "Number of products to show", 'dashstore' ),
						'id'       => 'products_qty',
						'type'     => 'text_number',
						'std'      => '',
						'class'    => 'input-mini',
						'validate' => 'number',
						'tooltip' => esc_html__( "Number of products to show in tab contents", 'dashstore' ),
					),
					array(
						'name'    => esc_html__( 'Background for tabs content', 'dashstore' ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => esc_html__( 'Select background image (position for background: right bottom)', 'dashstore' )
					),
					array(
						'name'  => esc_html__( 'Text for heading section', 'dashstore' ),
						'id'    => 'contents_title',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
                        'tooltip' => esc_html__( 'Enter heading text for tab contents', 'dashstore' ),
					),
					array(
						'name'  => esc_html__( 'From Price section', 'dashstore' ),
						'id'    => 'contents_from',
						'type'  => 'text_number',
						'class' => 'input-mini',
						'validate' => 'number',
                        'tooltip' => esc_html__( 'Enter lowest price for current category', 'dashstore' ),
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

			$products_qty = ( isset($products_qty) ? $products_qty : '5' );
			$html_output = '';
			$tab_bg = '';

			if ($image_file) {
				$tab_bg = ' style="background:url('.esc_url($image_file).') no-repeat right bottom transparent;"';
			}
			
			if ( isset( $category_id ) ) {
				
				/* Output tab anchor */
				$term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
				$html_output .= '<!-- tab-nav --><li>';
				if ($heading && $heading!='') {
					$html_output .= $heading;
				} else {
					$html_output .= $term['name'];
				}
				$html_output .='<i class="fa fa-angle-right"></i></li><!-- #tab-nav -->';

				/* Get products by id */
				$args = array(
				    'post_type'             => 'product',
				    'post_status'           => 'publish',
				    'ignore_sticky_posts'   => 1,
				    'posts_per_page'        => $products_qty,
				    'meta_query'            => array(
				        array(
				            'key'           => '_visibility',
				            'value'         => array('catalog', 'visible'),
				            'compare'       => 'IN'
				        )
				    ),
				    'tax_query'             => array(
				        array(
				            'taxonomy'      => 'product_cat',
				            'terms'         => $category_id,
				            'operator'      => 'IN'
				        )
				    )
				);

				$products = new WP_Query($args);
				if ($products) {
					$html_output .= '<!-- tab-content --><li class=""'.$tab_bg.'>';
					$html_output .= '<div class="product-links">';
					while( $products->have_posts() ) : $products->the_post();
						$product = new WC_Product( get_the_ID() );

						$html_output .= '<div class="link-wrapper"><a href="'. esc_url( $product->get_permalink() ) .'" title="'. esc_html__( 'Check Now', 'dashstore') .'">';
						$html_output .= '<h3>'. esc_attr( $product->get_title() ) .'</h3>';
						$html_output .= '<div class="popup-wrapper">'. $product->get_image( 'shop_thumbnail' );
						if ( $price_html = $product->get_price_html() ) {
							$html_output .= '<div class="price-wrapper">'. $product->get_price_html() .'</div>';
						}
						$html_output .= '</div></a></div>';
					endwhile;
					$html_output .= '</div>';

					if ($contents_title && $contents_title!='') {
						$html_output .= '<div class="description-wrap"><h3>'.esc_attr($contents_title).'</h3>';
						if ($contents_from && $contents_title!='') {
							$html_output .= '<span class="from"><span class="badge">'.esc_html__('FROM', 'dashstore').'</span><strong>'.get_woocommerce_currency_symbol().'&nbsp;'.esc_attr($contents_from).'</strong></span>';
						}
						$html_output .= '</div>';
					}
					$html_output .= '</li><!-- #tab-content -->';
				}
				wp_reset_postdata();
			}

			return $html_output;
		}

	}

}
