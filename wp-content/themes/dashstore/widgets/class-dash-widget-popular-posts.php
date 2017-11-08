<?php /* Popular Posts Widget */

if ( ! defined( 'ABSPATH' ) ) exit;

class Dash_Popular_Posts extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'dash_popular_posts',
			esc_html__('Dash Popular Posts', 'dashstore'),
			array( 'description' => esc_html__( 'Dash Store special widget. Outputs a list of the posts with the most user likes', 'dashstore' ), )
		);
	}

	public function form( $instance ) {

		$defaults = array(
			'title' 		=> 'Popular Posts',
			'range'         => 'all',
			'post-quantity' => 3,
			'precontent'    => '',
			'postcontent'   => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'dashstore' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
            <label for="<?php echo esc_attr($this->get_field_id("range")); ?>"><?php esc_html_e('Time Range:', 'dashstore'); ?></label>
        	<select class="widefat" id="<?php echo esc_attr($this->get_field_id("range")); ?>" name="<?php echo esc_attr($this->get_field_name("range")); ?>">
          		<option value="date" <?php selected( $instance["range"], "day" ); ?>><?php esc_html_e('Today', 'dashstore'); ?></option>
           		<option value="comment_count" <?php selected( $instance["range"], "week" ); ?>><?php esc_html_e('Week', 'dashstore'); ?></option>
         		<option value="title" <?php selected( $instance["range"], "month" ); ?>><?php esc_html_e('Month', 'dashstore'); ?></option>
				<option value="author" <?php selected( $instance["range"], "all" ); ?>><?php esc_html_e('All Time', 'dashstore'); ?></option>
        	</select>
        </p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_id('post-quantity')); ?>"><?php esc_html_e( 'How many posts to display: ', 'dashstore' ) ?></label>
			<input size="3" id="<?php echo esc_attr( $this->get_field_id('post-quantity') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post-quantity') ); ?>" type="number" value="<?php echo esc_attr( $instance['post-quantity'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id ('precontent')); ?>"><?php esc_html_e('Pre-Content', 'dashstore'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('precontent')); ?>" name="<?php echo esc_attr($this->get_field_name('precontent')); ?>" rows="2" cols="25"><?php echo esc_attr($instance['precontent']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id ('postcontent')); ?>"><?php esc_html_e('Post-Content', 'dashstore'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('postcontent')); ?>" name="<?php echo esc_attr($this->get_field_name('postcontent')); ?>" rows="2" cols="25"><?php echo esc_attr($instance['postcontent']); ?></textarea>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = ( $new_instance['title'] );
		$instance['range'] = strip_tags( $new_instance['range'] );
		$instance['post-quantity'] = intval( $new_instance['post-quantity'] );
		$instance['precontent'] = stripslashes( $new_instance['precontent'] );
		$instance['postcontent'] = stripslashes( $new_instance['postcontent'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $wpdb, $post;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$precontent = (isset($instance['precontent']) ? $instance['precontent'] : '' );
		$postcontent = (isset($instance['postcontent']) ? $instance['postcontent'] : '' );
		$range = (isset($instance['range']) ? $instance['range'] : 'all' );
		$post_qty = ( isset($instance['post-quantity']) ? $instance['post-quantity'] : 3 );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . esc_attr($title) . $after_title;
		if ( ! empty( $precontent ) ) {
			echo '<div class="precontent">'.esc_attr($precontent).'</div>';
		}

		// Time range variables
		$year = date('Y');
		$today = false;
		$week = false;
		$month = false;
		switch ($range) {
			case 'day':
				$today = date('j');
				$inner_title = esc_html__( 'Today\'s Most Popular Posts', 'dashstore' );
			break;
			case 'week':
				$week = date('W');
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'dashstore' );
			break;
			case 'month':
				$month = date('m');
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'dashstore' );
			break;
			case 'all':
				$year = false;
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'dashstore' );
  			break;
			default:
				$year = false;
				$inner_title = esc_html__( 'This Month\'s Most Popular Posts', 'dashstore' );
		}

		// New Query
		$args = array(
			'year' => $year,
			'day' => $today,
			'w' => $week,
			'monthnum' => $month,
			'post_type' => 'post',
			'meta_key' => '_post_like_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page' => $post_qty
		);

		$pop_posts = new WP_Query( $args );
		if ( $pop_posts->have_posts() ) {
			echo "<div class='favourite-posts'>";
			echo "<h3>" . esc_attr($inner_title) . "</h3>";
			echo '<ul class="widget-posts-list popular-posts">';
			while ( $pop_posts->have_posts() ) {
				$pop_posts->the_post();
				$post_like_count = get_post_meta( $post->ID, "_post_like_count", true ); ?>

				<li class="post-item">
				<?php if ( has_post_thumbnail() ) : ?>
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
						<div class="entry-meta">
							<div class="post-date"><i class="fa fa-calendar-check-o"></i><?php the_time('j M, Y'); ?></div>
							<?php if (intval($post_like_count) > 0) :?>
								<div class="likes-counter"><i class="fa fa-heart-o"></i><?php esc_html_e('Likes: ', 'dashstore');?><strong><?php echo esc_attr($post_like_count); ?></strong></div>
							<?php endif; // Post Comments ?>
						</div>
					</div>
				</li>

			<?php }
			echo "</ul>";
			echo "</div>";
		} else {
			$like_list = '';
			$like_list .= "<div class='favourite-posts'>";
			$like_list .= "<h3>" . esc_html__( 'Nothing yet', 'dashstore' ) . "</h3>";
			$like_list .= "</div>";
			echo $like_list;
		}
		wp_reset_postdata();

		if ( ! empty( $postcontent ) ) {
			echo '<div class="postcontent">'.esc_attr($postcontent).'</div>';
		}

		echo $after_widget;
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "Dash_Popular_Posts" );' ) );
