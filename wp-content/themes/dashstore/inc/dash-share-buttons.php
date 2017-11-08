<?php // Share buttons

class dashShareButtons
{
	public function getAll() {
		$included_socialnets = array(
			'facebook',
			'twitter',
			'pinterest',
			'google',
			'mail'
		);
		foreach ($included_socialnets as $soc_net) {
			$button_array[] = self::buildSocialButton($soc_net);
		}
		return '<div class="social-links"><span>'.esc_html__('Shares: ', 'dashstore').'</span>'.implode('', $button_array).'</div>';
	}

	private $included_socialnets = array('facebook', 'twitter', 'pinterest', 'google', 'mail');

	private function buildSocialButton($this_one) {
		$charmap = array(
			'facebook' => 'facebook',
			'twitter' => 'twitter',
			'pinterest' => 'pinterest',
			'google' => 'google-plus',
			'mail' => 'envelope-o'
		);
		$titlemap = array(
			'facebook' => esc_html__('Share this article on Facebook', 'dashstore'),
			'twitter' => esc_html__('Share this article on Twitter', 'dashstore'),
			'pinterest' => esc_html__('Share an image of this article on Pinterest', 'dashstore'),
			'google' => esc_html__('Share this article on Google+', 'dashstore'),
			'mail' => esc_html__('Email this article to a friend', 'dashstore'),
		);

		return '<div class="pt-post-share" data-service="'.esc_attr($this_one).'" data-postID="'.get_the_ID().'">
					<a href="'.$this->getSocialUrl($this_one).'" target="_blank">
						<i class="fa fa-'.esc_attr($charmap[$this_one]).'" title="'.esc_attr($titlemap[$this_one]).'"></i>
					</a>
					<span class="sharecount">('.esc_html($this->getShareCount($this_one)).')</span>
				</div>';
	}

	private function getSocialUrl($service) {
		global $post;
		$text = urlencode(esc_html__('A great post: ', 'dashstore').$post->post_title);
		$escaped_url = urlencode(get_permalink());
		$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : null;

		switch ($service) {
			case "twitter" :
				$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$escaped_url.'&amp;text='.esc_attr($text).'&amp;url='.esc_url($escaped_url);
				break;

			case "facebook" :
				$api_link = 'https://www.facebook.com/sharer/sharer.php?u='.$escaped_url;
				break;

			case "google" :
				$api_link = 'https://plus.google.com/share?url='.$escaped_url;
				break;

			case "pinterest" :
				if (isset($image)) {
					$api_link = 'http://pinterest.com/pin/create/bookmarklet/?media='.esc_url($image[0]).'&amp;url='.$escaped_url.'&amp;title='.esc_attr(get_the_title()).'&amp;description='.esc_html( $post->post_excerpt );
				}
				else {
					$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
				}
				break;

			case "mail" :
				$subject = esc_html__('Check this!', 'dashstore');
				$body = esc_html__('See more at: ', 'dashstore');
				$api_link = 'mailto:?subject='.str_replace('&amp;','%26',rawurlencode($subject)).'&body='.str_replace('&amp;','%26',rawurlencode($body).$escaped_url);
				break;
		}

		return $api_link;
	}

	private function getShareCount($service) {
		$count = get_post_meta( get_the_ID(), "_post_".$service."_shares", true ); // get post shares
		if( empty( $count ) ) {
			add_post_meta( get_the_ID(), "_post_".$service."_shares", 0, true ); // create post shares meta if not exist
			$count = 0;
		}
		return $count;
	}
}

/* Frontend output */
function dash_share_buttons_output() {
	if (!is_feed() && !is_home()) {
		$my_buttons = new dashShareButtons;
		$out = $my_buttons->getAll();
	}
	echo $out;
}

/* Enqueue scripts */
function dash_share_scripts() {
	wp_enqueue_script( 'dash-share-buttons', get_template_directory_uri(). '/js/post-share.js', array('jquery'), '1.0', true );
	wp_localize_script( 'dash-share-buttons', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
	);
}
add_action( 'init', 'dash_share_scripts' );

/* Share post counters */
add_action( 'wp_ajax_nopriv_dash_post_share_count', 'dash_post_share_count' );
add_action( 'wp_ajax_dash_post_share_count', 'dash_post_share_count' );

function dash_post_share_count() {
	$nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Nope!' );

	$post_id = $_POST['post_id']; // post id
	$service = $_POST['service'];
	$count = get_post_meta( $post_id, "_post_".$service."_shares", true ); // post like count

	if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
		$GLOBALS["super_cache_enabled"]=1;
		wp_cache_post_change( $post_id );
	}
	update_post_meta( $post_id, "_post_".$service."_shares", ++$count ); // +1 count post meta
	echo esc_attr($count); // update count on front end

	die();
}
