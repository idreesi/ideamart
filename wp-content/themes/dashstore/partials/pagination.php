<?php // Blog Pagination.

	$blog_pagination = esc_attr(dash_get_option('blog_pagination'));

	if ( ($wp_query->max_num_pages > 1) && ($blog_pagination == 'infinite') && !is_search() ) {
		if ( is_category() ) {
			echo '<span class="pt-get-more-posts" data-cat="'.get_query_var('cat').'">'.esc_html__('Show More Posts', 'dashstore').'</span>';
		} elseif( is_tag() ) {
			echo '<span class="pt-get-more-posts" data-tag="'.get_query_var('tag_id').'">'.esc_html__('Show More Posts', 'dashstore').'</span>';
		} elseif( is_author() ) {
			echo '<span class="pt-get-more-posts" data-author="'.get_query_var('author').'">'.esc_html__('Show More Posts', 'dashstore').'</span>';
		} else {
			echo '<span class="pt-get-more-posts">'.esc_html__('Show More Posts', 'dashstore').'</span>';
		}
	} elseif ( ($wp_query->max_num_pages > 1) && ($blog_pagination == 'numeric') ) {

		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%'; ?>

		<nav class="pagination"><!-- Paging Nav -->
			<h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'dashstore' ); ?></h1>
				<?php echo paginate_links( array(
								'base'     => $pagenum_link,
								'format'   => $format,
								'total'    => $GLOBALS['wp_query']->max_num_pages,
								'current'  => $paged,
								'mid_size' => 3,
								'add_args' => array_map( 'urlencode', $query_args ),
								'prev_text' => '<i class="fa fa-chevron-left"></i>',
								'next_text' => '<i class="fa fa-chevron-right"></i>',
								'type'      => 'plain',
							   ) ); ?>
		</nav><!-- end of Paging Nav -->

<?php } else {
		// Previous/next page navigation.
		the_posts_pagination( array(
			'prev_text'          => '<i class="fa fa-chevron-left"></i>',
			'next_text'          => '<i class="fa fa-chevron-right"></i>',
			'mid_size' 			 => 3
		) );
	}
