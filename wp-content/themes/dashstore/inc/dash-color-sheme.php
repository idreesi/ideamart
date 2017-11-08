<?php
// ----- Dash custom color sheme
if ( !function_exists( 'dash_add_inline_styles' ) ) {

	function dash_add_inline_styles() {

		/* Variables */
		$main_text = dash_get_option('primary_text_typography');
		$secondary_text_color = dash_get_option('secondary_text_color');
		$content_headings = dash_get_option('content_headings_typography');
		$sidebar_headings = dash_get_option('sidebar_headings_typography');
		$footer_headings = dash_get_option('footer_headings_typography');
		$footer_text_color = dash_get_option('footer_text_color');
		$link_color = dash_get_option('link_color');
		$link_color_hover = dash_get_option('link_color_hover');
		$main_decor_color = dash_get_option('main_decor_color');
		$button_typography = dash_get_option('button_typography');
		$button_background_color = dash_get_option('button_background_color');
		$button_text_hovered_color = dash_get_option('button_text_hovered_color');
		$main_border_color = dash_get_option('main_border_color');

		$out = '<style type="text/css">
				body.custom-color-sheme {
					font-size: '. esc_attr($main_text['size']) .';
					font-weight: '. esc_attr($main_text['style']) .';
					color: '. esc_attr($main_text['color']) .';
					font-family: "'. esc_attr($main_text['face']) .'", sans-serif;
				}
				.custom-color-sheme .site-content .entry-meta,
				.custom-color-sheme div.product .product_meta,
				.custom-color-sheme .entry-meta-bottom,
				.custom-color-sheme .comments-area .comment-meta
				.custom-color-sheme .pt-searchform .select2-container.search-select .select2-choice,
				.custom-color-sheme .hgroup-sidebar .widget.widget_shopping_cart .widget_shopping_cart_content,
				.custom-color-sheme .hgroup-sidebar .widget.widget_shopping_cart .excerpt-wrapper,
				.custom-color-sheme .site-header,
				.custom-color-sheme .breadcrumbs-wrapper .breadcrumbs a,
				.custom-color-sheme .breadcrumbs-wrapper .woocommerce-breadcrumb a,
				.custom-color-sheme .woocommerce .checkout .woocommerce-checkout-review-order-table .product-quantity,
				.custom-color-sheme .woocommerce-info,
				.custom-color-sheme .woocommerce td.product-name dl.variation,
				.custom-color-sheme div.product .social-links a {
				  color: '. esc_attr($secondary_text_color) .';
				}
				.custom-color-sheme button,
				.custom-color-sheme input,
				.custom-color-sheme select,
				.custom-color-sheme textarea {
					color: '. esc_attr($secondary_text_color) .';
				}
				.custom-color-sheme .entry-title,
				.custom-color-sheme .page-title,
				.custom-color-sheme .related > h2,
				.custom-color-sheme .upsells > h2,
				.custom-color-sheme .wcv-related > h2,
				.custom-color-sheme .cart-collaterals .cross-sells .title-wrapper h2,
				.custom-color-sheme.woocommerce .checkout .woocommerce-billing-fields h3,
				.custom-color-sheme div.product .woocommerce-tabs .panel h2:first-of-type,
				.custom-color-sheme #reviews #respond .comment-reply-title,
				.custom-color-sheme .woocommerce .checkout .woocommerce-billing-fields h3,
				.custom-color-sheme #related_posts .related-posts-title,
				.custom-color-sheme .pt-woo-shortcode.with-slider .title-wrapper h3,
				.custom-color-sheme .pt-posts-shortcode.with-slider .title-wrapper h3 {
					font-size: '. esc_attr($content_headings['size']) .';
					font-weight: '. esc_attr($content_headings['style']) .';
					color: '. esc_attr($content_headings['color']) .';
					font-family: "'. esc_attr($content_headings['face']) .'", sans-serif;
				}
				.custom-color-sheme .pt-woo-shortcode.with-slider .title-wrapper h3,
				.custom-color-sheme .pt-posts-shortcode.with-slider .title-wrapper h3 {
					border-color: '. esc_attr($content_headings['color']) .';
				}
				.custom-color-sheme .widget-title {
					font-size: '. esc_attr($sidebar_headings['size']) .';
					font-weight: '. esc_attr($sidebar_headings['style']) .';
					color: '. esc_attr($sidebar_headings['color']) .';
					font-family: "'. esc_attr($sidebar_headings['face']) .'", sans-serif;
				}
				.custom-color-sheme .site-footer .widget-title {
					font-size: '. esc_attr($footer_headings['size']) .';
					font-weight: '. esc_attr($footer_headings['style']) .';
					color: '. esc_attr($footer_headings['color']) .';
					font-family: "'. esc_attr($footer_headings['face']) .'", sans-serif;
				}
				.custom-color-sheme .site-footer,
				.custom-color-sheme .site-footer a {
					color: '. esc_attr($footer_text_color) .';
				}
				.custom-color-sheme a {
				  color: '. esc_attr($link_color) .';
 				}
				.custom-color-sheme a:hover,
				.custom-color-sheme a:active,
				.custom-color-sheme .widget.widget_dash_shop_filters li.is-checked,
				.custom-color-sheme .widget.widget_dash_shop_filters li:hover,
				.custom-color-sheme .breadcrumbs-wrapper .breadcrumbs a:hover,
				.custom-color-sheme .breadcrumbs-wrapper .woocommerce-breadcrumb a:hover,
				.custom-color-sheme .breadcrumbs-wrapper .breadcrumbs a:active,
				.custom-color-sheme .breadcrumbs-wrapper .woocommerce-breadcrumb a:active {
				  color: '. esc_attr($link_color_hover) .';
				}
				.custom-color-sheme button,
				.custom-color-sheme .button,
				.custom-color-sheme input[type="button"],
				.custom-color-sheme input[type="reset"],
				.custom-color-sheme input[type="submit"],
				.custom-color-sheme table.shop_table.cart td.actions .button,
				.custom-color-sheme div.product .special-buttons-wrapper .compare,
				.custom-color-sheme div.product .special-buttons-wrapper .yith-wcwl-add-to-wishlist a,
				.custom-color-sheme .figure.banner-with-effects.with-button.default-button a {
					font-size: '. esc_attr($button_typography['size']) .';
					font-weight: '. esc_attr($button_typography['style']) .';
					color: '. esc_attr($button_typography['color']) .';
					font-family: "'. esc_attr($button_typography['face']) .'", sans-serif;
					background-color: '. esc_attr($button_background_color) .';
				}
				.custom-color-sheme button:hover,
				.custom-color-sheme .button:hover,
				.custom-color-sheme input[type="button"]:hover,
				.custom-color-sheme input[type="reset"]:hover,
				.custom-color-sheme input[type="submit"]:hover,
				.custom-color-sheme table.shop_table.cart td.actions .button:hover,
				.custom-color-sheme div.product .special-buttons-wrapper .compare:hover,
				.custom-color-sheme div.product .special-buttons-wrapper .yith-wcwl-add-to-wishlist a:hover,
				.custom-color-sheme button:active,
				.custom-color-sheme .button:active,
				.custom-color-sheme div.product .special-buttons-wrapper .compare:active,
				.custom-color-sheme div.product .special-buttons-wrapper .yith-wcwl-add-to-wishlist a:active,
				.custom-color-sheme input[type="button"]:active,
				.custom-color-sheme input[type="reset"]:active,
				.custom-color-sheme input[type="submit"]:active,
				.custom-color-sheme table.shop_table.cart td.actions .button:active,
				.custom-color-sheme .figure.banner-with-effects.with-button.default-button a:hover,
				.custom-color-sheme .figure.banner-with-effects.with-button.default-button a:active {
					color: '. esc_attr($button_text_hovered_color) .';
					background-color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme div.product .special-buttons-wrapper .yith-wcwl-add-to-wishlist a::after,
				.custom-color-sheme div.product .special-buttons-wrapper .compare::after,
				.custom-color-sheme div.product .single_add_to_cart_button::after,
				.custom-color-sheme .footer-info-block .icon {
					color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme div.product .special-buttons-wrapper .yith-wcwl-add-to-wishlist a:hover::after,
				.custom-color-sheme div.product .special-buttons-wrapper .compare:hover::after,
				.custom-color-sheme div.product .single_add_to_cart_button:hover:after {
					color: '. esc_attr($button_text_hovered_color) .';
				}
				.custom-color-sheme li.product .product-description-wrapper .button.add_to_cart_button,
				.custom-color-sheme li.product .product-description-wrapper .button.product_type_variable,
				.custom-color-sheme li.product .product-description-wrapper .button.product_type_simple,
				.custom-color-sheme li.product .product-description-wrapper .outofstock .button,
				.custom-color-sheme li.product .product-description-wrapper .button.product_type_external,
				.custom-color-sheme li.product.list-view .product-description-wrapper .single_variation_wrap .button,
				.custom-color-sheme .site-main .owl-theme .owl-controls .owl-page span {
					background-color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme div.product span.onsale,
				.custom-color-sheme mark, .custom-color-sheme ins,
				.custom-color-sheme .wp-caption,
				.custom-color-sheme li.product span.onsale {
					background-color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme .star-rating span::before,
				.custom-color-sheme ins,
				.custom-color-sheme div.product .social-links a:hover,
				.custom-color-sheme div.product .social-links a:active,
				.custom-color-sheme li.product .product-description-wrapper .price ins,
				.custom-color-sheme li.product .additional-buttons .compare:hover::after,
				.custom-color-sheme li.product .additional-buttons .compare.added:hover::after,
				.custom-color-sheme li.product .additional-buttons .yith-wcwl-add-to-wishlist a:hover::after,
				.custom-color-sheme li.product .additional-buttons .yith-wcwl-wishlistaddedbrowse a:hover::after,
				.custom-color-sheme li.product .additional-buttons .yith-wcwl-wishlistexistsbrowse a:hover::after,
				.custom-color-sheme li.product .additional-buttons .jckqvBtn:hover i,
				.custom-color-sheme p.stars a:hover::after,
				.custom-color-sheme p.stars a.active::after,
				.custom-color-sheme p.stars a:hover ~ a::after,
				.custom-color-sheme p.stars a.active ~ a::after,
				.custom-color-sheme .site-content .entry-additional-meta .post-comments i,
				.custom-color-sheme .site-content .entry-additional-meta .likes-counter i,
				.custom-color-sheme .recent-post-list .recent-post-item i,
				.custom-color-sheme .recent-post-list .most-viewed-item i,
				.custom-color-sheme .most-viewed-list .recent-post-item i,
				.custom-color-sheme .most-viewed-list .most-viewed-item i,
				.custom-color-sheme .entry-meta-bottom .social-links a:hover,
				.custom-color-sheme .entry-meta-bottom .social-links a:active,
				.custom-color-sheme .entry-meta-bottom .like-wrapper a:hover,
				.custom-color-sheme .entry-meta-bottom .like-wrapper a:active,
				.custom-color-sheme .post-list .buttons-wrapper i,
				.custom-color-sheme .hgroup-sidebar .widget.widget_shopping_cart .heading,
				.custom-color-sheme .widget.widget_shopping_cart .total .amount,
				.custom-color-sheme .icon-with-description i,
				.custom-color-sheme .site-footer .widget ul li::before {
					color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme li.product span.onsale::before {
					border-bottom: 14px solid '. esc_attr($main_decor_color) .';
			    border-top: 14px solid '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme .pagination .page-numbers.current,
				.custom-color-sheme .site-footer .widget button,
				.custom-color-sheme .site-footer .widget .button,
				.custom-color-sheme .site-footer .widget input[type="button"],
				.custom-color-sheme .site-footer .widget input[type="reset"],
				.custom-color-sheme .site-footer .widget input[type="submit"] {
					background-color: '. esc_attr($main_decor_color) .';
					border-color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme .slider-navi span:hover,
				.custom-color-sheme .slider-navi span:active {
					color: '. esc_attr($main_decor_color) .';
					border-color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme .pagination a:hover,
				.custom-color-sheme .pagination a:active,
				.custom-color-sheme .pagination a:focus,
				.custom-color-sheme .site-content .entry-additional-meta .link-to-post,
				.custom-color-sheme .widget_dash_categories .pt-categories li .show-children {
					border-color: '. esc_attr($main_decor_color) .';
					color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme .widget_tag_cloud a,
				.custom-color-sheme .footer-info-block .border,
				.custom-color-sheme .figure.banner-with-effects.with-button.default-button a {
					border-color: '. esc_attr($main_decor_color) .';
				}
				.custom-color-sheme .widget_tag_cloud a:hover,
				.custom-color-sheme .widget_tag_cloud a:active,
				.custom-color-sheme .hgroup-sidebar .widget.widget_shopping_cart:hover .heading,
				.custom-color-sheme .hgroup-sidebar .widget.widget_shopping_cart.hovered .heading,
				.custom-color-sheme .widget_dash_categories .pt-categories li .show-children:hover,
				.custom-color-sheme .widget_dash_categories .pt-categories li .show-children:active {
					background-color: '. esc_attr($main_decor_color) .';
					color: '. esc_attr($button_text_hovered_color) .';
				}
				.custom-color-sheme .widget,
				.custom-color-sheme .post-list .post {
					border-color: '. esc_attr($main_border_color) .';
				}
				</style>';
		echo $out;
	}
}

function dash_color_sheme_body_class( $classes ) {
	$classes[] = 'custom-color-sheme';
	return $classes;
}
add_filter( 'body_class', 'dash_color_sheme_body_class' );
add_action( 'wp_head', 'dash_add_inline_styles' );
