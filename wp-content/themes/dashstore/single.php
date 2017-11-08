<?php /* The Template for displaying all single posts */
get_header(); ?>

	<main class="site-content<?php if (function_exists('dash_main_content_class')) dash_main_content_class(); ?>" itemscope="itemscope" itemprop="mainContentOfPage"><!-- Main content -->


		<?php // Start the Loop.
		while ( have_posts() ) : the_post();

			get_template_part( 'content', get_post_format() );

			// Related posts output
			if ( dash_get_option('post_show_related')=='on' ) {
				get_template_part( 'partials/related-posts' );
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

		endwhile; ?>

	</main><!-- end of Main content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
