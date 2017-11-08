<?php
/*
 * Dash Post Like System
 */
 
// Enqueue scripts for like system
function dash_like_scripts() {
	wp_enqueue_script( 'dash-like-script', get_template_directory_uri() . '/js/post-like.js', array('jquery'), '1.0', true );
	wp_localize_script( 'dash-like-script', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
	);
}
add_action( 'init', 'dash_like_scripts' );

// Save like data
add_action( 'wp_ajax_nopriv_dash-post-like', 'dash_post_like' );
add_action( 'wp_ajax_dash-post-like', 'dash_post_like' );
function dash_post_like() {
	$nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ();
	
	if ( isset( $_POST['dash_post_like'] ) ) {
	
		$post_id = $_POST['post_id']; // post id
		$post_like_count = get_post_meta( $post_id, "_post_like_count", true ); // post like count
		
		if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
			$GLOBALS["super_cache_enabled"]=1;
			wp_cache_post_change( $post_id );
		}
		
		if ( is_user_logged_in() ) { // user is logged in
			$user_id = get_current_user_id(); // current user
			$meta_POSTS = get_user_option( "_liked_posts", $user_id  ); // post ids from user meta
			$meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
			$liked_POSTS = NULL; // setup array variable
			$liked_USERS = NULL; // setup array variable
			
			if ( count( $meta_POSTS ) != 0 ) { // meta exists, set up values
				$liked_POSTS = $meta_POSTS;
			}
			
			if ( !is_array( $liked_POSTS ) ) // make array just in case
				$liked_POSTS = array();
				
			if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
				$liked_USERS = $meta_USERS[0];
			}		

			if ( !is_array( $liked_USERS ) ) // make array just in case
				$liked_USERS = array();
				
			$liked_POSTS['post-'.$post_id] = $post_id; // Add post id to user meta array
			$liked_USERS['user-'.$user_id] = $user_id; // add user id to post meta array
			$user_likes = count( $liked_POSTS ); // count user likes
	
			if ( !dash_already_liked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Add user ID to post meta
				update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Add post ID to user meta
				update_user_option( $user_id, "_user_like_count", $user_likes ); // +1 count user meta
				echo $post_like_count; // update count on front end

			} else { // unlike the post
				$pid_key = array_search( $post_id, $liked_POSTS ); // find the key
				$uid_key = array_search( $user_id, $liked_USERS ); // find the key
				unset( $liked_POSTS[$pid_key] ); // remove from array
				unset( $liked_USERS[$uid_key] ); // remove from array
				$user_likes = count( $liked_POSTS ); // recount user likes
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Remove user ID from post meta
				update_post_meta($post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Remove post ID from user meta			
				update_user_option( $user_id, "_user_like_count", $user_likes ); // -1 count user meta
				echo esc_html__('already', 'dashstore').$post_like_count; // update count on front end
				
			}
			
		} else { // user is not logged in (anonymous)
			$ip = $_SERVER['REMOTE_ADDR']; // user IP address
			$meta_IPS = get_post_meta( $post_id, "_user_IP" ); // stored IP addresses
			$liked_IPS = NULL; // set up array variable
			
			if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
				$liked_IPS = $meta_IPS[0];
			}
	
			if ( !is_array( $liked_IPS ) ) // make array just in case
				$liked_IPS = array();
				
			if ( !in_array( $ip, $liked_IPS ) ) // if IP not in array
				$liked_IPS['ip-'.$ip] = $ip; // add IP to array
			
			if ( !dash_already_liked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Add user IP to post meta
				update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
				echo $post_like_count; // update count on front end
				
			} else { // unlike the post
				$ip_key = array_search( $ip, $liked_IPS ); // find the key
				unset( $liked_IPS[$ip_key] ); // remove from array
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Remove user IP from post meta
				update_post_meta( $post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
				echo esc_html__('already', 'dashstore').$post_like_count; // update count on front end
				
			}
		}
	}
	
	exit;
}

// Test if user already liked post
function dash_already_liked( $post_id ) { // test if user liked before
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id(); // current user
		$meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
		$liked_USERS = ""; // set up array variable
		
		if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
			$liked_USERS = $meta_USERS[0];
		}
		
		if( !is_array( $liked_USERS ) ) // make array just in case
			$liked_USERS = array();
			
		if ( in_array( $user_id, $liked_USERS ) ) { // True if User ID in array
			return true;
		}
		return false;
		
	} else { // user is anonymous, use IP address for voting
	
		$meta_IPS = get_post_meta( $post_id, "_user_IP" ); // get previously voted IP address
		$ip = $_SERVER["REMOTE_ADDR"]; // Retrieve current user IP
		$liked_IPS = ""; // set up array variable
		
		if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
			$liked_IPS = $meta_IPS[0];
		}
		
		if ( !is_array( $liked_IPS ) ) // make array just in case
			$liked_IPS = array();
		
		if ( in_array( $ip, $liked_IPS ) ) { // True is IP in array
			return true;
		}
		return false;
	}
	
}

// Front end button
function dash_display_like_button( $post_id ) {
	$like_count = get_post_meta( $post_id, "_post_like_count", true ); 
	$count = ( empty( $like_count ) || $like_count == "0" ) ? 'Like' : esc_attr( $like_count );
	if ( dash_already_liked( $post_id ) ) {
		$class = ' liked';
		$title = esc_html__('Unlike', 'dashstore');
		$heart = '<i id="icon-like" class="post-icon-like fa fa-heart"></i>';
	} else {
		$class = '';
		$title = esc_html__('Like', 'dashstore');
		$heart = '<i id="icon-unlike" class="post-icon-unlike fa fa-heart-o"></i>';
	}
	echo '<div class="like-wrapper"><span>'.esc_html__('Likes: ', 'dashstore').'</span>';
	echo '<a href="#" class="pt-post-like'.esc_attr($class).'" data-post_id="'.esc_attr($post_id).'" title="'.esc_attr($title).'">'.$heart.'('.esc_attr($count).')</a>';
	echo '</div>';
}

// Likes Counter Function. Return total number of likes for post
function dash_get_likes_counter($post_id) {
	$post_like_count = get_post_meta( $post_id, "_post_like_count", true );
	$html = '';
	if ( intval($post_like_count) > 0 ) {
		$html = '<div class="likes-counter">';
		$html .= '<i class="fa fa-heart"></i>('.esc_attr($post_like_count).')';
		$html .= '</div>';
	} 
	return $html;
}

