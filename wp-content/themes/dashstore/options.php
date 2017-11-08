<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'dashstore-theme';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 */

function optionsframework_options() {

	// Adding Google fonts
	if (class_exists('dashFonts')) {
		$dash_default_fonts = dashFonts::get_default_fonts();
	}
	$font_list = array();
	if ( $dash_default_fonts ) {
		foreach ($dash_default_fonts as $item) {
			$font_option = str_replace(' ', '_', $item);
			$font_name = $item;
			$font_list[$font_option] = $font_name;
		}
		unset($item);
	}

	// On/Off array
	$on_off_array = array(
		'on' => esc_html__( 'On', 'dashstore' ),
		'off' => esc_html__( 'Off', 'dashstore' ),
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	);

	/**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */

	$wp_editor_settings = array(
		'wpautop' => false,
		'textarea_rows' => 3,
		'tinymce' => array( 'plugins' => 'wordpress,wplink' )
	);

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/theme-options/images/';

	// Layout options
	$layout_options = array(
		'one-col' => $imagepath . 'one-col.png',
		'two-col-left' => $imagepath . 'two-col-left.png',
		'two-col-right' => $imagepath . 'two-col-right.png'
	);

	// Typography Options
	$typography_options = array(
		'faces' => $font_list,
		'styles' => array( 'normal' => 'Normal', 'bold' => 'Bold', 'lighter' => 'Light' ),
		'color' => true
	);

	// Color Schemes
	$base_color_scheme_array = array( 'orange_default' => 'Default(Orange)', 'turquoise' => 'Turquoise', 'dark_red' => 'Dark Red', 'blue' => 'Blue');

	$options = array();

	/* Global Site Settings */
	$options[] = array(
		'name' => esc_html__( 'Site Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'site'
	);

	$options[] = array(
		'name' => esc_html__( 'Select layout for site', 'dashstore' ),
		'id' => 'site_layout',
		'std' => 'wide',
		'type' => 'radio',
		'options' => array(
			'wide'  => esc_html__('Wide', 'dashstore'),
			'boxed' => esc_html__('Boxed', 'dashstore'),
		)
	);

	$default_favicon_url = get_site_url().'/wp-content/uploads/2016/02/thumb-copy-1.png';
	$options[] = array(
		'name' => esc_html__( 'Upload image for favicon', 'dashstore' ),
		'desc' => esc_html__( 'Must be in .png, .gif format and minimum 32x32 px', 'dashstore' ),
		'id' => 'site_favicon',
		'std' => $default_favicon_url,
		'type' => 'upload'
	);

	$options[] = array(
		'name' => esc_html__( 'Enable Maintenance Mode for site?', 'dashstore' ),
		'desc' => esc_html__( 'When is ON use /wp-login.php to login to your site', 'dashstore' ),
		'id' => 'site_maintenance_mode',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__('Enter the date when Maintenance Mode expired', 'dashstore'),
		'desc' => esc_html__("Set date in following format (YYYY-MM-DD). If you leave this field blank, countdown clock won't be shown", 'dashstore'),
		'id' => 'maintenance_countdown',
		'std' => '',
		'placeholder' => 'YYYY-MM-DD',
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__( 'Extra Features', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Enable Breadcrumbs for site?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use breadcrumbs", 'dashstore' ),
		'id' => 'site_breadcrumbs',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( "Enable Post like system for site?", 'dashstore' ),
		'desc' => esc_html__( 'Anabling post like functionality on your site + Extra Widgets (Popular Posts, User Likes)', 'dashstore' ),
		'id' => 'site_post_likes',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( "Enable Scroll to Top button for site?", 'dashstore' ),
		'desc' => esc_html__( 'If On appears in bottom right corner of site', 'dashstore' ),
		'id' => 'totop_button',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	/* Header Options */
	$options[] = array(
		'name' => esc_html__( 'Header Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'header'
	);

	$options[] = array(
		'name' => esc_html__( 'Background for header', 'dashstore' ),
		'desc' => esc_html__( 'Add custom background color or image for header section.', 'dashstore' ),
		'id' => 'header_bg',
		'std' => $background_defaults,
		'type' => 'background'
	);

	$options[] = array(
		'name' => esc_html__( 'Logo Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Select position for logo', 'dashstore' ),
		'id' => 'site_logo_position',
		'std' => 'left',
		'type' => 'radio',
		'options' => array(
			'left'  => esc_html__('Left', 'dashstore'),
			'center' => esc_html__('Center', 'dashstore'),
			'right' => esc_html__('Right', 'dashstore'),
		)
	);

	$default_logo_url = get_site_url().'/wp-content/uploads/2016/03/Logo-2.png';
	$options[] = array(
		'name' => esc_html__( 'Upload image for logo', 'dashstore' ),
		'id' => 'site_logo',
		'std' => $default_logo_url,
		'type' => 'upload'
	);

	$options[] = array(
		'name' => esc_html__( 'Top Panel Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( "Enable header's top panel?", 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use header top panel", 'dashstore' ),
		'id' => 'header_top_panel',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Custom background color for top panel', 'dashstore' ),
		'desc' => esc_html__( 'Check to specify custom background color for top panel', 'dashstore' ),
		'id' => 'top_panel_bg',
		'std' => false,
		'class' => 'has_hidden_child',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__( 'Background color for header top panel', 'dashstore' ),
		'id' => 'top_panel_bg_color',
		'std' => '#626262',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Enter info contents', 'dashstore' ),
		'desc' => esc_html__( 'Info appears at center of headers top panel', 'dashstore' ),
		'id' => 'top_panel_info',
		'std' => '<i class="fa fa-map-marker"></i> 102580 Santa Monica BLVD Los Angeles',
		'type' => 'textarea'
	);

	/* Footer Options */
	$options[] = array(
		'name' => esc_html__( 'Footer Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'footer'
	);

	$options[] = array(
		'name' => esc_html__( 'Background for footer', 'dashstore' ),
		'desc' => esc_html__( 'Add custom background color or image for footer section.', 'dashstore' ),
		'id' => 'footer_bg',
		'std' => $background_defaults,
		'type' => 'background'
	);

	$options[] = array(
		'name' => esc_html__( 'Enter sites copyright', 'dashstore' ),
		'desc' => esc_html__( 'Enter copyright (appears at the bottom of site)', 'dashstore' ),
		'id' => 'site_copyright',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => esc_html__( 'Footer shortcode section Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Footer shortcode section', 'dashstore' ),
		'desc' => esc_html__( 'Check to use shortcode section located above footer', 'dashstore' ),
		'id' => 'footer_shortcode_section',
		'std' => true,
		'class' => 'has_hidden_childs',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__( 'Background for footer shortcode section', 'dashstore' ),
		'desc' => esc_html__( 'Add custom background color or image for shortcode section.', 'dashstore' ),
		'id' => 'footer_shortcode_section_bg',
		'class' => 'hidden',
		'std' => $background_defaults,
		'type' => 'background'
	);

	$default_footer_shortcode = '<div class="row hidden-xs hidden-sm">
	<div class="col-md-12 col-sm-12 col-xs-12">
			[ig_banner   image_file="'. get_site_url() .'/wp-content/uploads/2015/07/bottom_wood.jpg" banner_type="with_html" banner_text_position="center center" banner_url="#" banner_button="yes" banner_button_text="VIEW DETAILS" banner_button_position="right center" hover_type="milo" lazyload="no" disabled_el="no" ]<p style="color:#fff;font-family:\'Roboto\',sans-serif;font-size:2.315em;font-weight:lighter;margin: -0.25em 0 0 -10em;white-space: nowrap;">You save money at any sale on orders above <span style="color:#bde830;font-size:48px;vertical-align: middle;">$300</span></p>[/ig_banner]
		</div>
	</div>
	<div class="footer-info-block row">
				<div class="feature col-md-3 col-sm-6 col-xs-12">
					<span class="icon-wrapper">
						<span class="icon custom-icon-basket"><span class="border"></span></span>
					</span>
					<span class="link"><a href="#">How to buy?</a></span>
					<span class="text">Lorem ipsum dolor sit amet</span>
				</div>
				<div class="feature col-md-3 col-sm-6 col-xs-12">
					<span class="icon-wrapper">
						<span class="icon custom-icon-credit-card"><span class="border"></span></span>
					</span>
					<span class="link"><a href="#">Payment methods</a></span>
					<span class="text">Lorem ipsum dolor sit amet</span>
				</div>
				<div class="feature col-md-3 col-sm-6 col-xs-12">
					<span class="icon-wrapper">
						<span class="icon custom-icon-truck"><span class="border"></span></span>
					</span>
					<span class="link"><a href="#">Delivery info</a></span>
					<span class="text">Lorem ipsum dolor sit amet</span>
				</div>
				<div class="feature col-md-3 col-sm-6 col-xs-12">
					<span class="icon-wrapper">
						<span class="icon custom-icon-alert"><span class="border"></span></span>
					</span>
					<span class="link"><a href="#">Guarantees</a></span>
					<span class="text">Lorem ipsum dolor sit amet</span>
				</div>
	</div>';
	$options[] = array(
		'name' => esc_html__( 'Enter shortcode', 'dashstore' ),
		'id' => 'footer_shortcode_section_shortcode',
		'std' => $default_footer_shortcode,
		'class' => 'hidden',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);

	/* Page Templates Options */
	$options[] = array(
		'name' => esc_html__( 'Page Templates Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'templates'
	);

	$options[] = array(
		'name' => esc_html__( 'Front Page Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Front Page shortcode section', 'dashstore' ),
		'desc' => esc_html__( 'Check to use shortcode section located under primary navigation menu', 'dashstore' ),
		'id' => 'front_page_shortcode_section',
		'std' => true,
		'class' => 'has_hidden_childs',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => esc_html__( 'Background shortcode section', 'dashstore' ),
		'desc' => esc_html__( 'Add custom background color or image for shortcode section.', 'dashstore' ),
		'id' => 'front_page_shortcode_section_bg',
		'class' => 'hidden',
		'std' => $background_defaults,
		'type' => 'background'
	);

	$default_front_page_shortcode = '[rev_slider alias="wide-slider"]';
	$options[] = array(
		'name' => esc_html__( 'Enter shortcode', 'dashstore' ),
		'id' => 'front_page_shortcode_section_shortcode',
		'std' => $default_front_page_shortcode,
		'class' => 'hidden',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'name' => esc_html__( 'Enable Front Page special sidebar?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use front page special sidebar located at the bottom of Front Page Template", 'dashstore' ),
		'id' => 'front_page_special_sidebar',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	/* Layout Options */
	$options[] = array(
		'name' => esc_html__( 'Layout Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'layout'
	);

	$options[] = array(
		'name' => esc_html__('Set Front page layout', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the front page', 'dashstore'),
		'id' => "front_layout",
		'std' => "two-col-left",
		'type' => "images",
		'options' => $layout_options
	);

	$options[] = array(
		'name' => esc_html__('Set global layout for Pages', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the Pages of your site', 'dashstore'),
		'id' => "page_layout",
		'std' => "two-col-left",
		'type' => "images",
		'options' => $layout_options
	);

	$options[] = array(
		'name' => esc_html__('Set Blog page layout', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the Blog page', 'dashstore'),
		'id' => "blog_layout",
		'std' => "two-col-right",
		'type' => "images",
		'options' => $layout_options
	);

	$options[] = array(
		'name' => esc_html__('Set Single post view layout', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the single posts', 'dashstore'),
		'id' => "single_layout",
		'std' => "two-col-right",
		'type' => "images",
		'options' => $layout_options
	);

	$options[] = array(
		'name' => esc_html__('Set Products page (Shop page) layout', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the products page', 'dashstore'),
		'id' => "shop_layout",
		'std' => "one-col",
		'type' => "images",
		'options' => $layout_options
	);

	$options[] = array(
		'name' => esc_html__('Set Single Product pages layout', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the single product pages', 'dashstore'),
		'id' => "product_layout",
		'std' => "one-col",
		'type' => "images",
		'options' => $layout_options
	);

	$options[] = array(
		'name' => esc_html__('Set Vendor Store pages layout', 'dashstore'),
		'desc' => esc_html__('Specify the location of sidebars about the content on the vendor store pages', 'dashstore'),
		'id' => "vendor_layout",
		'std' => "one-col",
		'type' => "images",
		'options' => $layout_options
	);

	/* Blog Options */
	$options[] = array(
		'name' => esc_html__( 'Blog Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'wordpress'
	);

	$options[] = array(
		'name' => esc_html__( 'Enter text for Read More button', 'dashstore' ),
		'id' => 'blog_read_more_text',
		'std' => 'Continue Reading <i class="fa fa-angle-right"></i>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => esc_html__( 'Select pagination view for blog page', 'dashstore' ),
		'id' => 'blog_pagination',
		'std' => 'infinite',
		'type' => 'radio',
		'options' => array(
			'infinite'  => esc_html__('Infinite blog', 'dashstore'),
			'numeric' => esc_html__('Numeric pagination', 'dashstore')
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Enable lazyload effects on blog page?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use Lazyload effects on blog page", 'dashstore' ),
		'id' => 'lazyload_on_blog',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Blog Layout Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Select layout for blog', 'dashstore' ),
		'id' => 'blog_frontend_layout',
		'std' => 'list',
		'type' => 'radio',
		'class' => 'hidden-radio-control',
		'options' => array(
			'list'  => esc_html__('List', 'dashstore'),
			'grid'  => esc_html__('Grid', 'dashstore'),
			'isotope' => esc_html__('Isotope with filters', 'dashstore')
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Select number of columns for Blog GRID layout or ISOTOPE layout', 'dashstore' ),
		'id' => 'blog_grid_columns',
		'std' => 'cols-2',
		'type' => 'radio',
		'class' => 'hidden',
		'options' => array(
			'cols-2'  => esc_html__('2 Columns', 'dashstore'),
			'cols-3'  => esc_html__('3 Columns', 'dashstore'),
			'cols-4' => esc_html__('4 Columns', 'dashstore')
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Select what taxonomy will be used for blog filters', 'dashstore' ),
		'id' => 'blog_isotope_filters',
		'std' => 'cats',
		'type' => 'radio',
		'class' => 'hidden',
		'options' => array(
			'cats'  => esc_html__('Categories', 'dashstore'),
			'tags'  => esc_html__('Tags', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Single Post Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Enable single post Prev/Next navigation output?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use single post navigation", 'dashstore' ),
		'id' => 'post_pagination',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Enable single post breadcrumbs?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use breadcrumbs on Single post view", 'dashstore' ),
		'id' => 'post_breadcrumbs',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Enable single post share buttons output?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use share buttons", 'dashstore' ),
		'id' => 'blog_share_buttons',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Enable single post Related Posts output?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to show related posts", 'dashstore' ),
		'id' => 'post_show_related',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Select pagination type for comments', 'dashstore' ),
		'id' => 'comments_pagination',
		'std' => 'numeric',
		'type' => 'radio',
		'options' => array(
			'newold'  => esc_html__('Newer/Older pagination', 'dashstore'),
			'numeric'  => esc_html__('Numeric pagination', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Enable lazyload effects on single post?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use Lazyload effects on single post", 'dashstore' ),
		'id' => 'lazyload_on_post',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	/* Store Options */
	$options[] = array(
		'name' => esc_html__( 'Store Options', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'basket'
	);

	$options[] = array(
		'name' => esc_html__( 'Enable Catalog Mode?', 'dashstore' ),
		'desc' => esc_html__( 'Switch to ON if you want to switch your shop into a catalog mode (no prices, no add to cart)', 'dashstore' ),
		'id' => 'catalog_mode',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Show number of products in the cart widget?', 'dashstore' ),
		'desc' => esc_html__( 'Switch to ON if you want to show a a number of products currently in the cart widget', 'dashstore' ),
		'id' => 'cart_count',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Show store Breadcrumbs?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use breadcrumbs on store page", 'dashstore' ),
		'id' => 'store_breadcrumbs',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Add special sidebar for filters on Store page?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use special sidebar on products page", 'dashstore' ),
		'id' => 'filters_sidebar',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Store as Front page?', 'dashstore' ),
		'desc' => esc_html__( "Switch to On if you want to display Store page on Front page. Don't forget to specify Products Page as static front page in WordPress Reading Settings.", 'dashstore' ),
		'id' => 'front_page_shop',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Add Lazyload to products?', 'dashstore' ),
		'id' => 'catalog_lazyload',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Store Layout Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Enter number of products to show on Store page', 'dashstore' ),
		'id' => 'store_per_page',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => esc_html__( 'Select product quantity per row on Store page', 'dashstore' ),
		'id' => 'store_columns',
		'std' => '4',
		'type' => 'radio',
		'options' => array(
			'3'  => esc_html__('3 Products', 'dashstore'),
			'4'  => esc_html__('4 Products', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Show List/Grid products switcher?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use switcher on products page", 'dashstore' ),
		'id' => 'list_grid_switcher',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Set default view for products (list or grid)', 'dashstore' ),
		'id' => 'default_list_type',
		'std' => 'grid',
		'type' => 'radio',
		'options' => array(
			'grid'  => esc_html__('Grid', 'dashstore'),
			'list'  => esc_html__('List', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Single Product Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Show Single Product pagination (prev/next product)?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use single pagination on product page", 'dashstore' ),
		'id' => 'product_pagination',
		'std' => 'off',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Show single product share buttons?', 'dashstore' ),
		'desc' => esc_html__( "Switch to Off if you don't want to use single product share buttons", 'dashstore' ),
		'id' => 'use_pt_shares_for_product',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Show single product up-sells?', 'dashstore' ),
		'id' => 'show_upsells',
		'std' => 'on',
		'type' => 'radio',
		'class' => 'has_hidden_childs_radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Select how many Up-Sell Products to show on Single product page', 'dashstore' ),
		'id' => 'upsells_qty',
		'std' => '4',
		'type' => 'select',
		'class' => 'hidden',
		'options' => array(
			'2'  => esc_html__('2 products', 'dashstore'),
			'3'  => esc_html__('3 products', 'dashstore'),
			'4'  => esc_html__('4 products', 'dashstore'),
			'5'  => esc_html__('5 products', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Show single product related products?', 'dashstore' ),
		'id' => 'show_related_products',
		'std' => 'on',
		'type' => 'radio',
		'class' => 'has_hidden_childs_radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Select how many Related Products to show on Single product page', 'dashstore' ),
		'id' => 'related_products_qty',
		'std' => '4',
		'type' => 'select',
		'class' => 'hidden',
		'options' => array(
			'2'  => esc_html__('2 products', 'dashstore'),
			'3'  => esc_html__('3 products', 'dashstore'),
			'4'  => esc_html__('4 products', 'dashstore'),
			'5'  => esc_html__('5 products', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'WC Vendors Options', 'dashstore' ),
		'type' => 'info'
	);

	$options[] = array(
		'name' => esc_html__( 'Show WC Vendors Sold by: in Store page loop products?', 'dashstore' ),
		'id' => 'show_wcv_loop_sold_by',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Show single product vendors related products?', 'dashstore' ),
		'id' => 'show_wcv_related_products',
		'std' => 'on',
		'type' => 'radio',
		'class' => 'has_hidden_childs_radio',
		'options' => $on_off_array
	);

	$options[] = array(
		'name' => esc_html__( 'Select how many Vendor Related Products to show on Single product page', 'dashstore' ),
		'id' => 'wcv_qty',
		'std' => '4',
		'type' => 'select',
		'class' => 'hidden',
		'options' => array(
			'2'  => esc_html__('2 products', 'dashstore'),
			'3'  => esc_html__('3 products', 'dashstore'),
			'4'  => esc_html__('4 products', 'dashstore'),
			'5'  => esc_html__('5 products', 'dashstore'),
		)
	);

	$options[] = array(
		'name' => esc_html__( 'Enable simple feedback form for vendors?', 'dashstore' ),
		'desc' => esc_html__( 'Form appears in Seller Info tab on each vendors product', 'dashstore' ),
		'id' => 'enable_vendors_product_feedback',
		'std' => 'on',
		'type' => 'radio',
		'options' => $on_off_array
	);

	/* Color Shemes */
	$options[] = array(
		'name' => esc_html__( 'Typography and Colors', 'dashstore' ),
		'type' => 'heading',
		'icon' => 'eyedropper'
	);

	$options[] = array(
		'name' => esc_html__( 'Enable custom colors and fonts?', 'dashstore' ),
		'id' => 'site_custom_colors',
		'std' => 'off',
		'type' => 'radio',
		'class' => 'has_hidden_childs_radio',
		'options' => $on_off_array
	);

	$options[] = array(
	  "name" => esc_html__( "Base Color Scheme", 'dashstore' ),
	  "desc" => esc_html__( "Choose a predefined base color scheme.", 'dashstore' ),
	  "id" => "base_color_scheme",
	  "std" => "orange_default",
	  "type" => "select",
	  "class" => "hidden mini",
	  "options" => $base_color_scheme_array
	);

	$options[] = array(
		'name' => esc_html__( 'Fonts Options', 'dashstore' ),
		'type' => 'info',
		'class' => 'hidden'
	);

	$options[] = array(
		'name' => esc_html__( 'Primary text typography options', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for all text content', 'dashstore' ),
		'id' => "primary_text_typography",
		'std' => array(
			'size' => '14px',
			'face' => 'Open_Sans',
			'style' => 'normal',
			'color' => '#646565'
		),
		'type' => 'typography',
		'class' => 'hidden',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => esc_html__( 'Secondary text color', 'dashstore' ),
		'desc' => esc_html__( 'Specify secondary color for all meta content(categories, tags, excerpts)', 'dashstore' ),
		'id' => 'secondary_text_color',
		'std' => '#898e91',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Content headings typography options', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for all headings in the content area(page/post/product titles)', 'dashstore' ),
		'id' => "content_headings_typography",
		'std' => array(
			'size' => '30px',
			'face' => 'Roboto',
			'style' => 'bold',
			'color' => '#484747'
		),
		'type' => 'typography',
		'class' => 'hidden',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => esc_html__( 'Sidebar headings typography options', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for all headings in the sidebar area(widget titles)', 'dashstore' ),
		'id' => "sidebar_headings_typography",
		'std' => array(
			'size' => '18px',
			'face' => 'Roboto',
			'style' => 'bold',
			'color' => '#151515'
		),
		'type' => 'typography',
		'class' => 'hidden',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => esc_html__( 'Footer headings typography options', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for all headings in the footer widgets(footer widget titles)', 'dashstore' ),
		'id' => "footer_headings_typography",
		'std' => array(
			'size' => '18px',
			'face' => 'Roboto',
			'style' => 'bold',
			'color' => '#f8f8f6'
		),
		'type' => 'typography',
		'class' => 'hidden',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => esc_html__( 'Footer text color', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for text in the footer area', 'dashstore' ),
		'id' => 'footer_text_color',
		'std' => '#f8f8f6',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Links and Buttons Options', 'dashstore' ),
		'type' => 'info',
		'class' => 'hidden'
	);

	$options[] = array(
		'name' => esc_html__( 'Link color', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for all text links', 'dashstore' ),
		'id' => 'link_color',
		'std' => '#151515',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Link color on hover', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for all hovered text links', 'dashstore' ),
		'id' => 'link_color_hover',
		'std' => '#f7972b',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Primary button typography options', 'dashstore' ),
		'desc' => esc_html__( 'Specify fonts for buttons(product "add to cart", form buttons, etc.)', 'dashstore' ),
		'id' => "button_typography",
		'std' => array(
			'size' => '14px',
			'face' => 'Roboto',
			'style' => 'normal',
			'color' => '#444444'
		),
		'type' => 'typography',
		'class' => 'hidden',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => esc_html__( 'Primary button background color', 'dashstore' ),
		'desc' => esc_html__( 'Specify background color for buttons. Background for hovered buttons equal to main theme color', 'dashstore' ),
		'id' => 'button_background_color',
		'std' => '#f7f8f8',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Primary button text color on hover', 'dashstore' ),
		'desc' => esc_html__( 'Specify text color for hovered buttons', 'dashstore' ),
		'id' => 'button_text_hovered_color',
		'std' => '#ffffff',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Icons and other Elements', 'dashstore' ),
		'type' => 'info',
		'class' => 'hidden'
	);

	$options[] = array(
		'name' => esc_html__( 'Main Theme color', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for decorative elements(icons, buttons, switchers, etc.)', 'dashstore' ),
		'id' => 'main_decor_color',
		'std' => '#f7972b',
		'class' => 'hidden',
		'type' => 'color'
	);

	$options[] = array(
		'name' => esc_html__( 'Theme Border color', 'dashstore' ),
		'desc' => esc_html__( 'Specify color for borders of the theme elements', 'dashstore' ),
		'id' => 'main_border_color',
		'std' => '#e7e4d9',
		'class' => 'hidden',
		'type' => 'color'
	);

	return $options;
}
