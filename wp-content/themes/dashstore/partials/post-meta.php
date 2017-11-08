<?php // Footer meta output
	if ( !is_single() ) : ?>
		<footer class="entry-additional-meta"><!-- Article's Footer -->
			<?php if ( ! post_password_required() && function_exists('dash_entry_comments_counter') ) { dash_entry_comments_counter(); } ?>
			<?php if ( function_exists('dash_get_likes_counter') ) {
				echo dash_get_likes_counter(get_the_ID());
			} ?>
			<?php edit_post_link( esc_html__( 'Edit', 'dashstore' ), '<span class="edit-link">', '</span>' ); ?>
			<a class="link-to-post" href="<?php esc_url(the_permalink()); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Click to read more about %s', 'dashstore' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" itemprop="url"><i class="fa fa-angle-right"></i></a>
		</footer><!-- end of Article's Footer -->
	<?php endif; ?>

	<?php if ( is_single() ) : ?>
		<footer class="entry-footer"><!-- Article's Footer -->
			<div class="entry-meta-bottom">
				<?php if ( function_exists( 'dash_share_buttons_output' ) && dash_get_option('blog_share_buttons')=='on' ) { dash_share_buttons_output(); } ?>
				<?php if ( function_exists( 'dash_entry_post_views' ) ) { dash_entry_post_views(); } ?>
				<?php if ( function_exists( 'dash_display_like_button' ) ) { dash_display_like_button( get_the_ID() ); } ?>
				<?php if( dash_get_option('post_pagination')=='on' ) { 
					$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
					$next     = get_adjacent_post( false, '', false );
					if ( ! $next && ! $previous ) {
						return;
					} else { ?>
					<nav class="navigation post-navigation"><!-- Post Nav -->
						<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'dashstore' ); ?></h1>
						<div class="nav-links">
							<?php
								previous_post_link( '%link', '<i class="fa fa-angle-left"></i>&nbsp;&nbsp;&nbsp;' . esc_html__( 'Previous Post', 'dashstore' ) );
								next_post_link( '%link', esc_html__( 'Next Post', 'dashstore' ) . '&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>' );
							?>
						</div>
					</nav><!-- end of Post Nav -->
					<?php }
				} ?>
			</div>

			<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
				<?php get_template_part( 'partials/author-bio' ); ?>
			<?php endif; ?>
		</footer><!-- end of Article's Footer -->
	<?php endif;
