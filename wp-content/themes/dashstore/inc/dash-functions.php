<?php /*-------Dash Theme Functions----------*/

/* Contents:
	- Custom excerpt "more" text
	- Custom media fields
	- Custom meta output functions
	- Post views counter function
	- Custom social links for users
	- Custom comments walker
	- Custom comment form
	- Maintenance Mode function
	- Main Site wrapper functions
	- Header functions
		- Header background
		- Adding itemprop to all nav menus
		- Page title function
	- Page Content functions
		- Adaptive class for main content
	- Single post functions
		- Extra class for adaptive styles
  - Footer functions
    - Footer custom background
    - Footer Shortcode section
  - Extra Features
    - Add favicon function
    - Scroll to top button
  - Extra markup for tiny mce
  - Theme Options Extra Functions
 */


// ----- Custom excerpt "more" text
if ( !function_exists('dash_excerpt_more') ) {
	function dash_excerpt_more() {
		if ( !dash_get_option('blog_read_more_text')=='') {
			return dash_get_option('blog_read_more_text');
		} else { return esc_html__('Read More', 'dashstore'); }
	}
	add_filter('dash_more', 'dash_excerpt_more');
}


// ----- Custom media fields
function dash_custom_media_fields( $form_fields, $post ) {
	$form_fields['portfolio_filter'] = array(
		'label' => esc_html__('Portfolio Filters', 'dashstore'),
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'portfolio_filter', true ),
		'helps' => esc_html__('Used only for Portfolio and Gallery Pages Isotope filtering', 'dashstore'),
	);
	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'dash_custom_media_fields', 10, 2 );

function dash_custom_media_fields_save( $post, $attachment ) {
	if( isset( $attachment['portfolio_filter'] ) )
		update_post_meta( $post['ID'], 'portfolio_filter', $attachment['portfolio_filter'] );
	return $post;
}
add_filter( 'attachment_fields_to_save', 'dash_custom_media_fields_save', 10, 2 );


// ----- Custom meta output functions
if (!function_exists('dash_entry_publication_time')) {
	function dash_entry_publication_time() { ?>
	    <div class="time-wrapper">
				<?php esc_html_e('Published: ', 'dashstore'); ?>
	    	<?php printf( '<time class="entry-date" datetime="%1$s" itemprop="datePublished">%2$s&nbsp;%3$s,&nbsp;%4$s</time>',
			      esc_attr( get_the_date('c') ),
			      esc_html( get_the_date('M') ),
			      esc_html( get_the_date('j') ),
			      esc_html( get_the_date('Y') )
	    	); ?>
	    </div>
	<?php }
}

if (!function_exists('dash_entry_comments_counter')) {
	function dash_entry_comments_counter() { ?>
	    <div class="post-comments" itemprop="interactionCount">
	    	<i class="fa fa-comments"></i>
	    	<?php comments_popup_link( '(0)', '(1)', '(%)', 'comments-link', esc_html__('Commenting: OFF', 'dashstore') ); ?>
	    </div>
	<?php }
}

if (!function_exists('dash_entry_post_cats')) {
	function dash_entry_post_cats() {
	    if ( get_the_category_list() ) { ?>
	    	<div class="post-cats" itemprop="articleSection"><?php esc_html_e('Categories: ', 'dashstore'); echo get_the_category_list( esc_html__( ', ', 'dashstore' ) ); ?></div>
	    <?php }
	}
}

if (!function_exists('dash_entry_post_tags')) {
	function dash_entry_post_tags() {
	    if ( get_the_tag_list() ) { ?>
	    	<div class="post-tags"><?php esc_html_e('Tags: ', 'dashstore'); echo get_the_tag_list( '', esc_html__( ', ', 'dashstore' ) ); ?></div>
		<?php }
	}
}

if (!function_exists('dash_entry_author')) {
	function dash_entry_author() {
	    printf( '<div class="post-author" itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">'.esc_html__('Author: ', 'dashstore').'<a href="%1$s" title="%2$s" rel="author" itemprop="url"><span itemprop="name">%3$s</span></a></div>',
	      esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
	      sprintf( esc_html__('View all posts by %s', 'dashstore'), get_the_author() ),
	      get_the_author()
	    );
	}
}

if (!function_exists('dash_entry_post_views')) {
	function dash_entry_post_views() {
	    global $post;
	    $views = get_post_meta ($post->ID,'views',true);
	    if ($views) { ?>
	      <div class="post-views"><span><?php esc_html_e('Views: ', 'dashstore'); ?></span><i class="fa fa-eye"></i>(<?php echo esc_attr($views); ?>)</div>
	    <?php } else { ?>
	    	<div class="post-views"><span><?php esc_html_e('Views: ', 'dashstore'); ?></span><i class="fa fa-eye"></i>(0)</div>
	    <?php }
	}
}


// ----- Post views counter function
if ( ! function_exists( 'dash_postviews' ) ) {
    function dash_postviews() {

    /* ------------ Settings -------------- */
    $meta_key       = 'views';  	  // The meta key field, which will record the number of views.
    $who_count      = 0;            // Whose visit to count? 0 - All of them. 1 - Only the guests. 2 - Only registred users.
    $exclude_bots   = 1;            // Exclude bots, robots, spiders, and other mischief? 0 - no. 1 - yes.

    global $user_ID, $post;
        if(is_singular()) {
            $id = (int)$post->ID;
            static $post_views = false;
            if($post_views) return true;
            $post_views = (int)get_post_meta($id,$meta_key, true);
            $should_count = false;
            switch( (int)$who_count ) {
                case 0: $should_count = true;
                    break;
                case 1:
                    if( (int)$user_ID == 0 )
                        $should_count = true;
                    break;
                case 2:
                    if( (int)$user_ID > 0 )
                        $should_count = true;
                    break;
            }
            if( (int)$exclude_bots==1 && $should_count ){
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $notbot = "Mozilla|Opera"; //Chrome|Safari|Firefox|Netscape - all equals Mozilla
                $bot = "Bot/|robot|Slurp/|yahoo";
                if ( !preg_match("/$notbot/i", $useragent) || preg_match("!$bot!i", $useragent) )
                    $should_count = false;
            }
            if($should_count)
                if( !update_post_meta($id, $meta_key, ($post_views+1)) ) add_post_meta($id, $meta_key, 1, true);
        }
        return true;
    }
}
add_action('wp_head', 'dash_postviews');


// ----- Custom social links for users
if ( ! function_exists( 'dash_new_author_contacts' ) ) {
	function dash_new_author_contacts( $contactmethods ) {
	  // Add Twitter
	  if ( !isset( $contactmethods['twitter'] ) )
	    $contactmethods['twitter'] = 'Twitter';

	  // Add Google Plus
	  if ( !isset( $contactmethods['googleplus'] ) )
	    $contactmethods['googleplus'] = 'Google Plus';

	  // Add Facebook
	  if ( !isset( $contactmethods['facebook'] ) )
	    $contactmethods['facebook'] = 'Facebook';

	  return $contactmethods;
	}
}
add_filter( 'user_contactmethods', 'dash_new_author_contacts', 10, 1 );

if ( ! function_exists( 'dash_output_author_contacts' ) ) {
	function dash_output_author_contacts() {
	    global $post;
	    $twitter = get_the_author_meta( 'twitter', $post->post_author );
	    $facebook = get_the_author_meta( 'facebook', $post->post_author );
	    $googleplus = get_the_author_meta( 'googleplus', $post->post_author );

	    if (isset($facebook) || isset($twitter) || isset($googleplus)) { ?>
	       <div class="author-contacts">
	    <?php }

	    if (isset($twitter)) echo '<a href="'.esc_url($twitter).'" rel="author" target="_blank"><i class="fa fa-twitter-square"></i></a>';
	    if (isset($facebook)) echo '<a href="'.esc_url($facebook).'" rel="author" target="_blank"><i class="fa fa-facebook-square"></i></a>';
	    if (isset($googleplus)) echo '<a href="'.esc_url($googleplus).'" rel="author" target="_blank"><i class="fa fa-google-plus-square"></i></a>';

	    if (isset($facebook) || isset($twitter) || isset($googleplus)) { ?>
	       </div>
	    <?php }
	}
}


// ----- Custom comments walker
if ( ! class_exists('dash_comments_walker')) {
	class dash_comments_walker extends Walker_Comment {
	    var $tree_type = 'comment';
	    var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

	    // wrapper for child comments list
	    function start_lvl( &$output, $depth = 0, $args = array() ) {
	        $GLOBALS['comment_depth'] = $depth + 1; ?>
	        <div class="child-comments comments-list">
	    <?php }

	    // closing wrapper for child comments list
	    function end_lvl( &$output, $depth = 0, $args = array() ) {
	        $GLOBALS['comment_depth'] = $depth + 1; ?>
	        </div>
	    <?php }

	    // HTML for comment template
	    function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
	      $depth++;
	      $GLOBALS['comment_depth'] = $depth;
	      $GLOBALS['comment'] = $comment;
	      $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' );
	      $add_below = 'comment';

				if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type )	{ ?>
					<article <?php comment_class( '', $comment ); ?> id="comment-<?php comment_ID() ?>">
						<header class="comment-meta">
							<?php esc_html_e( 'Pingback:', 'dashstore' ); ?> <?php esc_url(comment_author_link( $comment )); ?> <?php esc_url(edit_comment_link('Edit','','')); ?>
						</header>
				<?php } else { ?>
	    <article <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope="itemscope" itemtype="http://schema.org/UserComments">
	      <figure class="gravatar"><?php echo get_avatar( $comment, 70, '', esc_html__("Author's gravatar", "dashstore") ); ?></figure>

	      <header class="comment-meta">
	        <h2 class="comment-author" itemprop="creator" itemscope="itemscope" itemtype="http://schema.org/Person">
	          <?php esc_html_e('Posted by ', 'dashstore'); ?>
	          <?php if (get_comment_author_url() != '') { ?>
	            <a class="comment-author-link" href="<?php esc_url(comment_author_url()); ?>" itemprop="url"><span itemprop="name"><?php esc_attr(comment_author()); ?></span></a>
	          <?php } else { ?>
	            <span class="author" itemprop="name"><?php esc_attr(comment_author()); ?></span>
	          <?php } ?>
	        </h2>
	        <?php esc_html_e(' on ', 'dashstore'); ?>
	        <time class="comment-meta-time" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>" itemprop="commentTime"><?php comment_date('F jS, Y') ?><?php esc_html_e(', at ', 'dashstore');?><?php comment_time('g:i a') ?></time>
	        <?php edit_comment_link('Edit','',''); ?>
	        <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	      </header>

	      <?php if ($comment->comment_approved == '0') : ?>
	        <p class="comment-meta-item"><?php esc_html_e('Your comment is awaiting moderation.', 'dashstore'); ?></p>
	      <?php endif; ?>

	      <div class="comment-content post-content" itemprop="commentText">
	        <?php comment_text() ?>
	      </div>
				<?php } // end for else ?>

	    <?php }
	    // end_el – closing HTML for comment template
	    function end_el( &$output, $comment, $depth = 0, $args = array() ) { ?>
	        </article>
	    <?php }

	}
}


// ----- Custom comment form
if ( ! function_exists( 'dash_comment_form' ) ) {
	function dash_comment_form() {

	    $commenter = wp_get_current_commenter();
	    $req = get_option( 'require_name_email' );
	    $aria_req = ( $req ? " aria-required='true'" : '' );
	    $user = wp_get_current_user();
	    $user_identity = $user->exists() ? $user->display_name : '';

	    $custom_args = array(
	        'id_form'           => 'commentform',
	        'id_submit'         => 'submit',
	        'title_reply'       => esc_html__( 'Leave Your Comment', 'dashstore' ),
	        'title_reply_to'    => esc_html__( 'Leave Your Comment to %s', 'dashstore' ),
	        'cancel_reply_link' => esc_html__( 'Cancel Reply', 'dashstore' ),
	        'label_submit'      => esc_html__( 'Submit Comment', 'dashstore' ),

	        'comment_field' =>  '<p class="comment-form-comment">
	                             <label for="comment">'.esc_html__( 'Comment', 'dashstore' ).'</label>
	                             <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" aria-describedby="form-allowed-tags" placeholder="'.esc_html__('Comment:', 'dashstore').'"></textarea>
	                             </p>',

	        'must_log_in' => '<p class="must-log-in">'.
	                          sprintf( wp_kses( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'dashstore' ), $allowed_html=array('a' => array('href'=>array())) ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ).
	                         '</p>',

	        'logged_in_as' => '<p class="logged-in-as">'.
	                           sprintf( '%1$s<a href="%2$s">%3$s</a>. <a href="%4$s" title="%5$s">%6$s</a>',
	                            esc_html__('Logged in as ', 'dashstore'),
	                            esc_url(admin_url( 'profile.php' )),
	                            $user_identity,
	                            wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ),
	                            esc_html__('Log out of this account', 'dashstore'),
	                            esc_html__('Log out?', 'dashstore') ).
	                          '</p>',

	        'comment_notes_before' => false,

	        'comment_notes_after' => false,

	        'fields' => apply_filters( 'comment_form_default_fields', array(
	            'author' =>
	                        '<p class="comment-form-author">
	                        <label for="author">'. esc_html__( 'Name', 'dashstore' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label>
	                        <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" aria-required="true" placeholder="' . ( $req ? esc_html__( 'Name (required):', 'dashstore' ) : esc_html__( 'Name:', 'dashstore' ) ) . '" />
	                        </p>',

	            'email' =>
	                        '<p class="comment-form-email">
	                        <label for="email">'. esc_html__( 'E-mail', 'dashstore' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label>
	                        <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-required="true" aria-describedby="email-notes" placeholder="' . ( $req ? esc_html__( 'E-mail (will not be published, required):', 'dashstore' ) : esc_html__( 'E-mail (will not be published):', 'dashstore' ) ) . '" />
	                        </p>',

	            'url' =>
	                        '<p class="comment-form-url">
	                        <label for="url">'. esc_html__( 'Website', 'dashstore' ) . '</label>
	                        <input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" placeholder="' . esc_html__( 'Website:', 'dashstore' ) . '" />
	                        </p>',
	        )),
	    );
	    comment_form( $custom_args );
	}
}


// ----- Maintenance Mode function
$maintenance_mode = (dash_get_option('site_maintenance_mode') != '') ? dash_get_option('site_maintenance_mode') : 'off';
if ( $maintenance_mode=='on' || ( isset($_GET['MAINTENANCE'] ) && $_GET['MAINTENANCE'] == 'true' ) ) {
	define('DASH_IN_MAINTENANCE', true);
} else {
	define('DASH_IN_MAINTENANCE', false);
}

if ( ! function_exists( 'dash_maintenance' ) ) {
	function dash_maintenance(){
	    global $pagenow;
	    if(
	       defined('DASH_IN_MAINTENANCE')
	       && DASH_IN_MAINTENANCE
	       && $pagenow !== 'wp-login.php'
	       && ! is_user_logged_in() ) {
	       		$protocol = "HTTP/1.0";
				if ( "HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"] ) {
					$protocol = "HTTP/1.1";
				}
			    header( "$protocol 503 Service Unavailable", true, 503 );
			    header( "Retry-After: 3600" );
			    header( "Content-Type: text/html; charset=utf-8" );

		    	require_once( get_template_directory() . '/inc/dash-maintenance.php');
		    	die();
	    }
	    return false;
	}
}
add_action('wp_loaded', 'dash_maintenance');


// ----- Main Site wrapper functions
if (!function_exists('dash_site_wrapper_start')) {
	function dash_site_wrapper_start() {
		if (dash_get_option('site_layout')=='boxed') { ?>
			<div class="site-wrapper container">
				<div class="row">
		<?php } else { ?>
			<div class="site-wrapper">
		<?php }
	}
}

if (!function_exists('dash_site_wrapper_end')) {
	function dash_site_wrapper_end() {
		if (dash_get_option('site_layout')=='boxed') { ?>
			</div></div>
		<?php } else { ?>
			</div>
		<?php }
	}
}

// ----- Header functions
// Header background
if (!function_exists('dash_custom_header_bg')) {
	function dash_custom_header_bg() {
		$background = dash_get_option('header_bg');
		if ( $background ) {
			if ( $background['image'] ) {
				echo ' style="background-image:url('. esc_url($background['image']) .');
										  background-repeat:'. esc_attr($background['repeat']) .';
										  background-position:'. esc_attr($background['position']) .';
										  background-attachment:'. esc_attr($background['attachment']) .';
											background-color:'. esc_attr($background['color']) .'"';
			} else {
				echo ' style="background-color:'. esc_attr($background['color']) .';"';
			}
		} else {
			return false;
		};
	}
}

// Adding itemprop to all nav menus
if (!function_exists('dash_add_itemprop')) {
	function dash_add_itemprop( $atts, $item, $args ) {
	    $atts['itemprop'] = 'url';
	    return $atts;
	}
	add_filter('nav_menu_link_attributes', 'dash_add_itemprop', 10, 3);
}

// Page title function
if (!function_exists('dash_output_page_title')) {
	function dash_output_page_title() { ?>
		<div class="page-title">
			<?php
			// Archives
			if (is_archive() && ( class_exists('Woocommerce') && !is_woocommerce() ) ) {
				esc_attr( the_archive_title() );
			} else if ( is_archive() ) {
				esc_attr( the_archive_title() );
			}
			// 404
			elseif ( is_404() ) {
				esc_html_e( 'Page 404', 'dashstore' );
			}
			// Search
			elseif ( is_search() ) {
				printf( esc_html__( 'Search Results for: %s', 'dashstore' ), get_search_query() );
			}
			// Blog
			elseif ( is_home() ) {
				esc_html_e( 'Blog', 'dashstore' );
			}
			elseif ( is_home() && get_option( 'page_for_posts' ) ) {
				echo esc_attr( get_the_title( get_option( 'page_for_posts' ) ) );
			}
			else {
				echo esc_attr( get_the_title() );
			}
			?>
		</div>
	<?php }
}


// ----- Page Content functions
// Adaptive class for main content
if (!function_exists('dash_main_content_class')) {
	function dash_main_content_class() {
		if ( dash_show_layout()=='layout-one-col' ) { $content_class = ' col-xs-12 col-md-12 col-sm-12'; }
		elseif ( dash_show_layout()=='layout-two-col-left' ) { $content_class = ' col-xs-12 col-md-9 col-sm-8 col-md-push-3 col-sm-push-4'; }
		else { $content_class = ' col-xs-12 col-md-9 col-sm-8'; }

		/* Live preview */
		if( isset( $_GET['b_type'] ) ){
			$blog_type = $_GET['b_type'];
			if ( $blog_type=='3cols' || $blog_type=='4cols' || $blog_type=='filters' ) {
				$content_class = ' col-xs-12 col-md-12 col-sm-12';
			}
		}
		if( isset( $_GET['shop_w_sidebar'] ) && $_GET['shop_w_sidebar']='true' ){
			$content_class = ' col-xs-12 col-md-9 col-sm-8 col-md-push-3 col-sm-push-4 shop-with-sidebar';
		}

		/* Advanced Blog layout */
		if ( (dash_get_option('blog_frontend_layout')=='grid' ||
				 dash_get_option('blog_frontend_layout')=='isotope') && !is_singular() ) {
			$content_class .= ' grid-layout '.dash_get_option('blog_grid_columns');
		}

		/* Infinite blog extra class */
		if ( class_exists('Woocommerce') ) {
			if ( dash_get_option('blog_pagination')=='infinite' && ( is_home() || is_archive() ) && !is_woocommerce() ) {
				$content_class .= ' infinite-blog';
			}
		} else {
			if ( dash_get_option('blog_pagination')=='infinite' && ( is_home() || is_archive() ) ) {
				$content_class .= ' infinite-blog';
			}
		}

		echo esc_attr($content_class);
	}
}


// ----- Single post functions
// Extra class for adaptive styles
if (!function_exists('dash_single_content_class')) {
	function dash_single_content_class() {
		$extra_class = '';
		if ( (dash_get_option('blog_frontend_layout')=='grid' ||
			  dash_get_option('blog_frontend_layout')=='isotope') &&
			  !is_single() &&
			  !is_search() ) {
			$blog_cols = dash_get_option('blog_grid_columns');
			switch ($blog_cols) {
				case 'cols-2':
					$extra_class = 'col-md-6 col-sm-12 col-xs-12';
				break;
				case 'cols-3':
					$extra_class = 'col-md-4 col-sm-6 col-xs-12';
				break;
				case 'cols-4':
					$extra_class = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
				break;
			}
		}
		/* Live preview */
		if( isset( $_GET['b_type'] ) ){
			$blog_type = $_GET['b_type'];
			switch ($blog_type) {
				case '2cols':
					$extra_class = 'col-md-6 col-sm-12 col-xs-12';
				break;
				case '3cols':
					$extra_class = 'col-md-4 col-sm-6 col-xs-12';
				break;
				case 'filters':
					$extra_class = 'col-md-4 col-sm-6 col-xs-12';
				break;
				case '4cols':
					$extra_class = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
				break;
			}
		}
		return esc_attr($extra_class);
	}
}


// ----- Footer functions
// Footer custom background
if (!function_exists('dash_custom_footer_bg')) {
	function dash_custom_footer_bg() {
		$background = dash_get_option('footer_bg');
		if ( $background ) {
			if ( $background['image'] ) {
				echo ' style="background-image:url('. esc_url($background['image']) .');
										  background-repeat:'. esc_attr($background['repeat']) .';
										  background-position:'. esc_attr($background['position']) .';
										  background-attachment:'. esc_attr($background['attachment']) .';
											background-color:'. esc_attr($background['color']) .'"';
			} else {
				echo ' style="background-color:'. esc_attr($background['color']) .';"';
			}
		} else {
			return false;
		};
	}
}

// Footer Shortcode section
if (!function_exists('dash_footer_shortcode_section')) {
	function dash_footer_shortcode_section() {
		// Variables
		$shortcode = dash_get_option('footer_shortcode_section_shortcode');
		function dash_footer_shortcode_section_bg() {
			$background = dash_get_option('footer_shortcode_section_bg');
			if ( $background ) {
				if ( $background['image'] ) {
					echo ' style="background-image:url('. esc_url($background['image']) .');
												background-repeat:'. esc_attr($background['repeat']) .';
												background-position:'. esc_attr($background['position']) .';
												background-attachment:'. esc_attr($background['attachment']) .';
												background-color:'. esc_attr($background['color']) .'"';
				} else {
					echo ' style="background-color:'. esc_attr($background['color']) .';"';
				}
			} else {
				return false;
			};
		} ?>

		<div class="footer-shortcode"<?php dash_footer_shortcode_section_bg();?>>
			<div class="container">
				<?php echo do_shortcode( $shortcode ) ?>
			</div>
		</div>

	<?php }
}


// ----- Extra Features
// Add favicon function
if (dash_get_option('site_favicon') && dash_get_option('site_favicon')!='') {
	if ( ! function_exists( 'dash_favicon' ) ) {
		function dash_favicon() { ?>
			<link rel="shortcut icon" href="<?php echo esc_url( dash_get_option('site_favicon') );?>" >
		<?php }
	}
	add_action('wp_head', 'dash_favicon');
}

// Scroll to top button
if (dash_get_option('totop_button') == 'on') {
	if ( ! function_exists( 'dash_add_totop_button' ) ) {
		function dash_add_totop_button() { ?>
			<a href="#0" class="to-top" title="<?php esc_html_e('Back To Top', 'dashstore'); ?>"><i class="fa fa-chevron-up"></i></a>
		<?php }
	}
	add_action('wp_footer', 'dash_add_totop_button');
}


// ----- Extra markup for tiny mce
//add_filter( 'tiny_mce_before_init', 'dash_add_mce_elements' );
//add_action( 'init', 'dash_add_text_editor_elements' );

function dash_add_mce_elements( $init ) {
    $ext = 'a[data-toggle|data-parent|aria-expanded|aria-controls]';
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }
    return $init;
}

function dash_add_text_editor_elements() {
    global $allowedposttags;

    $tags = array( 'a' );
    $new_attributes = array(
    	'data-toggle' => array(),
    	'data-parent' => array(),
    	'aria-expanded' => array(),
    	'aria-controls' => array(),
    );
    foreach ( $tags as $tag ) {
        if ( isset( $allowedposttags[ $tag ] ) && is_array( $allowedposttags[ $tag ] ) )
            $allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $new_attributes );
    }
}


// ----- Theme Options Extra Functions

/* This is an example of how to override a default filter for 'textarea' sanitization and use a different one. */
add_action('admin_init','dash_optionscheck_change_sanitiziation', 100);

function dash_optionscheck_change_sanitiziation() {
	remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
	add_filter( 'of_sanitize_textarea', 'dash_sanitize_textarea' );
}

function dash_sanitize_textarea($input) {
	global $allowedposttags;
	$dash_custom_allowedtags["i"] = array(
		'class' => array(), // Allow classes
		'style' => array() 	// Allow style
	);

	$dash_custom_allowedtags = array_merge($dash_custom_allowedtags, $allowedposttags);
	$output = wp_kses( $input, $dash_custom_allowedtags);
	return $output;
}

/* Special sidebar on Theme Options */
add_action('optionsframework_after','optionscheck_display_sidebar', 100);

function optionscheck_display_sidebar() { ?>
<div id="options-sidebar-holder" class="metabox-holder">
  <div id="options-sidebar">
    <h3><i class="custom-icon-help"></i><?php esc_html_e('Need Help?', 'dashstore'); ?></h3>
    <div class="section">
			<p><?php echo wp_kses( __('Please, create ticket at <a href="https://themeszone.freshdesk.com" target="_blank">https://themeszone.freshdesk.com</a> to get help with the theme.', 'dashstore'), $allowed_html=array('a' => array( 'href'=>array(),'target'=>array() )) ); ?></p>
			<p><?php esc_html_e("Our support team will be glad to answer your questions regarding theme usage. We also provide paid customization and paid theme installation services. Please contact support on this matter!", "dashstore"); ?></p>
			<p><?php esc_html_e("Please, be sure to read the online version of this theme's documentation, it contains answers to many questions people usually ask.", "dashstore" ); ?></p>
		</div>
		<div class="support-links">
			<a href="https://themes.zone/docs/dash/" target="_blank" title="<?php esc_html_e('Read Theme Documentation', 'dashstore'); ?>"><i class="custom-icon-docs"></i><?php esc_html_e('Theme Documentation', 'dashstore'); ?></a>
			<span>&nbsp;|&nbsp;</span>
			<a href="https://themeszone.freshdesk.com" target="_blank" title="<?php esc_html_e('Create Support Ticket', 'dashstore'); ?>"><i class="custom-icon-support"></i><?php esc_html_e('Support', 'dashstore'); ?></a>
		</div>
  </div>
</div>
<?php }

/* Add custom image sizes attribute to enhance responsive image functionality */
function dash_content_image_sizes_attr($sizes, $size) {
     $width = $size[0];
     //Page without sidebar
     if (dash_show_layout()=='layout-one-col') {
         if ($width > 878) {
             return '(max-width: 768px) 92vw, (max-width: 992px) 658px, (max-width: 1200px) 878px, 1078px';
         } elseif ($width < 878 && $width > 658) {
             return '(max-width: 768px) 92vw, (max-width: 992px) 658px, 878px';
         } else {
	         	 return '(max-width: ' . $width . 'px) 92vw, ' . $width . 'px';
				 }
     } else {
		 //Page with sidebar
				 if ($width > 786) {
						 return '(max-width: 768px) 92vw, (max-width: 992px) 408px, (max-width: 1200px) 636px, 786px';
				 }
				 if ($width < 786 && $width > 636) {
						 return '(max-width: 768px) 92vw, (max-width: 992px) 408px, 636px';
				 }
				 return '(max-width: ' . $width . 'px) 92vw, ' . $width . 'px';
		 }
}
add_filter('wp_calculate_image_sizes', 'dash_content_image_sizes_attr', 10 , 2);

/* Add custom image sizes attribute to enhance responsive image functionality for post thumbnails */
function dash_post_thumbnail_sizes_attr($attr, $attachment, $size) {
		 switch ($size) {
			 case 'post-thumbnail':
			 		if (dash_show_layout()=='layout-one-col') {
			 				$attr['sizes'] = '(max-width: 768px) 92vw, (max-width: 992px) 658px, (max-width: 1200px) 878px, 1078px';
					} else {
							$attr['sizes'] = '(max-width: 768px) 92vw, (max-width: 992px) 408px, (max-width: 1200px) 636px, 786px';
					}
			 break;
			 case 'shop_single':
					if (dash_show_layout()=='layout-one-col') {
						 $attr['sizes'] = '(max-width: 768px) 92vw, (max-width: 992px) 345px, (max-width: 1200px) 455px, 555px';
				  } else {
						 $attr['sizes'] = '(max-width: 768px) 92vw, (max-width: 992px) 408px, (max-width: 1200px) 636px, 409px';
					}
			 break;
			 case 'full':
					$attr['sizes'] = '(max-width: 768px) 92vw, (max-width: 992px) 970px, (max-width: 1200px) 1170px, 1200px';
			 break;
		 };
     return $attr;
 }
add_filter('wp_get_attachment_image_attributes', 'dash_post_thumbnail_sizes_attr', 10 , 3);
