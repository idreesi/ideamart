<?php /* Dash Most Viewed Posts/Products */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dash_Most_Viewed_Posts extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'dash_most_viewed_posts', // Base ID
			esc_html__('Dash Most Viewed Posts', 'dashstore'), // Name
			array('description' => esc_html__( "Dash Store special widget. Displaying number of most viewed posts on your site.", 'dashstore' ), )
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => 'Most Viewed Posts',
			'post-quantity' => 5,
			'post-type' => 'post',
			'sort-order' => false,  // DESC
			'date' => false,
			'comments' => false,
			'category' => '',
			'thumb' => false,
			'excerpt' => false,
			'excerpt-length' => 10,
			'excerpt-more' => '...read more',
			'price' => false,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title: ', 'dashstore' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post-quantity')); ?>"><?php esc_html_e( 'How many posts to display: ', 'dashstore' ) ?></label>
			<input size="3" id="<?php echo esc_attr( $this->get_field_id('post-quantity') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post-quantity') ); ?>" type="number" value="<?php echo esc_attr( $instance['post-quantity'] ); ?>" />
		</p>
		<p>
            <label for="<?php echo esc_attr($this->get_field_id('post-type')); ?>"><?php esc_html_e('Show Posts for next Post Type:', 'dashstore');?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('post-type')); ?>" name="<?php echo esc_attr($this->get_field_name('post-type')); ?>">
                <?php
                    global $wp_post_types;
                    $post_type = isset($instance['post-type']) ? strip_tags($instance['post-type']) : 'post';
                    foreach($wp_post_types as $k=>$sa) {
                        if($sa->exclude_from_search) continue;
                        echo '<option value="'.esc_attr($k).'"' .selected($k,$post_type).'>'.esc_attr($sa->labels->name).'</option>';
                    } ?>
            </select>
        </p>
        <p>
    	    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id("sort-order")); ?>" name="<?php echo esc_attr($this->get_field_name("sort-order")); ?>" <?php checked( (bool) $instance["sort-order"] ); ?> />
            <label for="<?php echo esc_attr($this->get_field_id("sort-order")); ?>"><?php esc_html_e( 'Reverse sort order (ascending)?', 'dashstore' ); ?></label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id("date")); ?>" name="<?php echo esc_attr($this->get_field_name("date")); ?>"<?php checked( (bool) $instance["date"] ); ?> />
            <label for="<?php echo esc_attr($this->get_field_id("date")); ?>"><?php esc_html_e( 'Show publish date?', 'dashstore' ); ?></label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id("comments")); ?>" name="<?php echo esc_attr($this->get_field_name("comments")); ?>"<?php checked( (bool) $instance["comments"] ); ?> />
            <label for="<?php echo esc_attr($this->get_field_id("comments")); ?>"><?php esc_html_e( 'Show number of comments?', 'dashstore' ); ?></label>
        </p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e( 'Specify ID of category (categories) to show: ', 'dashstore' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('category') ); ?>" name="<?php echo esc_attr( $this->get_field_name('category') ); ?>" type="text" value="<?php echo esc_attr( $instance['category'] ); ?>" />
		</p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id("thumb")); ?>" name="<?php echo esc_attr($this->get_field_name("thumb")); ?>"<?php checked( (bool) $instance["thumb"] ); ?> />
            <label for="<?php echo esc_attr($this->get_field_id("thumb")); ?>"><?php esc_html_e( 'Show post thumbnail?', 'dashstore' ); ?></label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id("excerpt")); ?>" name="<?php echo esc_attr($this->get_field_name("excerpt")); ?>"<?php checked( (bool) $instance["excerpt"] ); ?> />
            <label for="<?php echo esc_attr($this->get_field_id("excerpt")); ?>"><?php esc_html_e( 'Show post excerpt?', 'dashstore' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id("excerpt-length")); ?>"><?php esc_html_e( 'Excerpt length (in words):', 'dashstore' ); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id("excerpt-length")); ?>" name="<?php echo esc_attr($this->get_field_name("excerpt-length")); ?>" value="<?php echo esc_attr( $instance['excerpt-length'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id("excerpt-more")); ?>"><?php esc_html_e( 'Excerpt read more text:', 'dashstore' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id("excerpt-more")); ?>" name="<?php echo esc_attr($this->get_field_name("excerpt-more")); ?>" value="<?php echo esc_attr( $instance['excerpt-more'] ); ?>" size="10" />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id("price")); ?>" name="<?php echo esc_attr($this->get_field_name("price")); ?>"<?php checked( (bool) $instance["price"] ); ?> />
            <label for="<?php echo esc_attr($this->get_field_id("price")); ?>"><?php esc_html_e( 'Show price? (products only)', 'dashstore' ); ?></label>
        </p>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post-quantity'] = intval( $new_instance['post-quantity'] );
		$instance['post-type'] = strip_tags( $new_instance['post-type'] );
		$instance['sort-order'] = $new_instance['sort-order'];
		$instance['date'] = $new_instance['date'];
		$instance['comments'] = $new_instance['comments'];
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['thumb'] = $new_instance['thumb'];
		$instance['excerpt'] = $new_instance['excerpt'];
		$instance['excerpt-length'] = absint( $new_instance['excerpt-length'] );
		$instance['excerpt-more'] = strip_tags( $new_instance['excerpt-more'] );
		$instance['price'] = $new_instance['price'];

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);
		global $wpdb, $post;

		$title = apply_filters('widget_title', $instance['title'] );
		$post_num = ( isset($instance['post-quantity']) ? $instance['post-quantity'] : 5 );
		$post_type = ( isset($instance['post-type']) ? $instance['post-type'] : 'post' );
		$sort_order = ( isset($instance['sort-order']) ? $instance['sort-order'] : false );

			if ($sort_order) { $order = 'ASC'; } else { $order = 'DESC'; }

		$show_date = ( isset($instance['date']) ? $instance['date'] : false );
		$show_comments = ( isset($instance['comments']) ? $instance['comments'] : false );
		$categories = ( isset($instance['category']) ? $instance['category'] : '' );
		$show_excerpt = ( isset($instance['excerpt']) ? $instance['excerpt'] : false );
		$excerpt_length = ( isset($instance['excerpt-length']) ? $instance['excerpt-length'] : 10 );
		$excerpt_more = ( isset($instance['excerpt-more']) ? $instance['excerpt-more'] : '...read more' );

		// Excerpt filters
		$new_excerpt_more = create_function('$more', 'return " ";');
		add_filter('excerpt_more', $new_excerpt_more);

		$new_excerpt_length = create_function('$length', 'return "'.$excerpt_length.'";');
		if ( $excerpt_length > 0 ) add_filter('excerpt_length', $new_excerpt_length);

		$show_thumb = ( isset($instance['thumb']) ? $instance['thumb'] : false );
		$show_price = ( isset($instance['price']) ? $instance['price'] : false );
		$cur_postID = $post->ID;

        // The Query
        $query_args = array (
			'posts_per_page' => $post_num,
			'ignore_sticky_posts' => 1,
			'orderby' => 'meta_value_num',
			'meta_key' => 'views',
			'order' => $order,
			'post_type' => $post_type,
			'post_status' => 'publish',
			'cat' => $categories
		);

		$the_query = new WP_Query( $query_args );

		echo $before_widget;
		if ($title) { echo $before_title . esc_attr($title) . $after_title; }

		echo '<ul class="widget-posts-list most-viewed-posts">';
		while ( $the_query->have_posts() ) :
			$the_query->the_post(); ?>

			<li class="post-item">

				<?php if ( $show_thumb && has_post_thumbnail() ) : ?>
				<div class="thumb-wrapper">
					<a rel="bookmark" href="<?php esc_url(the_permalink()); ?>" title="<?php esc_html_e('Click to learn more about ', 'dashstore') . esc_attr(the_title()); ?>">
						<?php the_post_thumbnail('dash-sidebar-thumb'); ?>
					</a>
				</div>
				<?php endif; // Post Thumbnail ?>

				<div class="content-wrapper">
					<h4>
						<a href="<?php esc_url(the_permalink()); ?>" rel="bookmark" title="<?php esc_html_e('Click to learn more about ', 'dashstore') . esc_attr(the_title()); ?>"><?php esc_attr(the_title()); ?></a>
					</h4>

					<?php if ($show_date || $show_comments) : ?>
					<div class="entry-meta">

						<?php if ($show_date) :?>
							<div class="post-date"><i class="fa fa-calendar-check-o"></i><?php the_time('j M, Y'); ?><!--<span class="post-author"><?php esc_html_e(' by ', 'dashstore') . the_author_posts_link(); ?></span>--></div>
						<?php endif; // Post Date & Author ?>

						<?php if ($show_comments) :?>
							<div class="comments-qty"><i class="fa fa-comments-o"></i><?php comments_popup_link( esc_html__('No comments yet', 'dashstore'), esc_html__('1 comment', 'dashstore'), esc_html__('% comments', 'dashstore'), 'comments-link', esc_html__('Comments are off', 'dashstore') ); ?></div>
						<?php endif; // Post Comments ?>

						<div class="views-qty"><i class="fa fa-eye"></i><?php esc_html_e('Views: ', 'dashstore');?><strong><?php echo esc_attr(get_post_meta ($post->ID,'views',true)); ?></strong></div>

					</div>
					<?php endif; ?>

					<?php if ($show_excerpt) : ?>
					<div class="most-viewed-entry-content"><?php the_excerpt(); ?>
						<a href="<?php esc_url(the_permalink()); ?>" class="more-link" rel="bookmark" title="<?php esc_html_e('Read more about ', 'dashstore') . esc_attr(the_title()); ?>"><?php echo $excerpt_more; ?></a>
					</div>
					<?php endif; // Post Content ?>

					<?php
					if ( $show_price ) {
						if ( $post_type === 'product' ) :
							echo '<div class="product-price">';
							woocommerce_template_single_price();
							echo '</div>'; // Product Price if WOO
						elseif ( $post_type === 'wpsc-product' ) :
							echo '<div class="product-price">';
							wpsc_the_product_price_display();
							echo '</div>'; // Product Price if WPEC
						else : return false;
						endif;
					}?>

				</div>

			</li>
		<?php
		endwhile;
		echo '</ul>';
		echo $after_widget;
		wp_reset_postdata();
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "Dash_Most_Viewed_Posts" );' ) );
