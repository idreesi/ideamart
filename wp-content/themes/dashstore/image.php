<?php /* The template for displaying image attachments */

get_header(); ?>

	<main class="site-content<?php if (function_exists('dash_main_content_class')) dash_main_content_class(); ?>" itemscope="itemscope" itemprop="mainContentOfPage"><!-- Main content -->

		<?php // Start the Loop.
			while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemprop="ImageObject"><!-- Article ID-<?php the_ID(); ?> -->

				<aside class="attachment-img"><!-- Img Wrapper -->
					<?php echo wp_get_attachment_image( $post->ID, 'attach-page-image-thumb', false, array('itemprop' => 'thumbnail') ); ?>
				</aside><!-- end of Img Wrapper -->

				<div class="attachment-description">
					<header class="entry-header"><!-- Article's Header -->
						<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>
					</header><!-- end of Article's Header -->

					<div class="entry-content"><!-- Content -->

						<?php if ( has_excerpt() ) : ?>
							<div class="entry-caption" itemprop="caption">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $post->post_content ) ) : ?>
							<div class="entry-description">
								<?php echo $post->post_content; ?>
							</div>
						<?php endif; ?>

					</div><!-- end of Content -->

					<footer class="entry-meta"><!-- Article's Footer -->
						<div class="date">
							<strong><?php esc_html_e('Date:&nbsp;&nbsp;&nbsp;', 'dashstore'); ?></strong>
							<?php dash_entry_publication_time();?>
						</div>

						<?php if ( $post->portfolio_filter ) { ?>
							<div class="tags">
								<strong><?php esc_html_e('Tags:&nbsp;&nbsp;&nbsp;', 'dashstore'); ?></strong>
								<?php echo esc_attr($post->portfolio_filter); ?>
							</div>
						<?php }?>

						<div class="comments">
							<strong><?php esc_html_e('Comments:&nbsp;&nbsp;&nbsp;', 'dashstore'); ?></strong>
							<?php dash_entry_comments_counter(); ?>
						</div>

						<div class="source">
							<strong><?php esc_html_e('Source Image:&nbsp;&nbsp;&nbsp;', 'dashstore'); ?></strong>
							<?php
								$metadata = wp_get_attachment_metadata();
								printf( '<span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s" itemprop="contentUrl">%3$s (%4$s &times; %5$s)</a></span>',
									esc_url( wp_get_attachment_url() ),
									esc_html__( 'Link to full-size image', 'dashstore' ),
									esc_html__( 'Full resolution', 'dashstore' ),
									esc_attr($metadata['width']),
									esc_attr($metadata['height'])
								);
							 ?>
						</div>
					</footer><!-- end of Article's Footer -->
				</div>

				<aside class="entry-meta-bottom"><!-- Additional Meta -->
					<?php if ( function_exists( 'dash_share_buttons_output' ) ) { dash_share_buttons_output(); } ?>
					<?php if ( function_exists( 'dash_entry_post_views' ) ) { dash_entry_post_views(); } ?>
					<?php if ( function_exists( 'dash_display_like_button' ) ) { dash_display_like_button( get_the_ID() ); } ?>
					<?php if ( dash_get_option('post_pagination')=='on' ) { 
					$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
					$next     = get_adjacent_post( false, '', false );
					if ( ! $next && ! $previous ) {
						return;
					} else { ?>
						<nav class="navigation post-navigation"><!-- Image Nav -->
							<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'dashstore' ); ?></h1>
							<div class="nav-links">
								<?php
									previous_image_link( '%link', '<i class="fa fa-angle-left"></i>' . esc_html__( '&nbsp;&nbsp;&nbsp;Previous Image', 'dashstore' ) );
									next_image_link( '%link', esc_html__( 'Next Image&nbsp;&nbsp;&nbsp;', 'dashstore' ) . '<i class="fa fa-angle-right"></i>' );
								?>
							</div>
						</nav><!-- end of Image Nav -->
					<?php } } ?>
				</aside><!-- end of Additional Meta -->

			</article><!-- end of Article ID-<?php the_ID(); ?> -->

			<?php comments_template(); ?>

		<?php endwhile; // end of the loop. ?>

	</main><!-- end of Main content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
