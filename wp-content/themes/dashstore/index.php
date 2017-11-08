<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 */

get_header(); ?>

	<main class="site-content<?php if (function_exists('dash_main_content_class')) dash_main_content_class(); ?>" itemscope="itemscope" itemprop="mainContentOfPage"><!-- Main content -->

	<?php if ( function_exists('dash_output_page_title') ) dash_output_page_title(); ?>

	<?php
		// If isotope layout get & output all posts
		if (dash_get_option('blog_frontend_layout')=='isotope' || isset( $_GET['b_type'] ) ) {
			global $wp_query, $query_string; query_posts( $query_string . '&posts_per_page=-1' );
		}

		if ( have_posts() ) {

			// Output filters if needed
			if (dash_get_option('blog_frontend_layout')=='isotope' || ( isset($_GET['b_type']) && $_GET['b_type']=='filters') ) {
				get_template_part( 'partials/post-filters' );
			}

			// Extra wrapper for blog posts
			if ( dash_get_option('blog_frontend_layout')=='grid' || dash_get_option('blog_frontend_layout')=='isotope' || isset( $_GET['b_type'] ) ) { ?>
				<div class='blog-grid-wrapper row' data-isotope=container data-isotope-layout=masonry data-isotope-elements=post>
			<?php }

			// Start the Loop.
			while ( have_posts() ) : the_post();

				get_template_part( 'content', get_post_format() );

			endwhile;

			// Close Extra wrapper for blog posts
			if ( dash_get_option('blog_frontend_layout')=='grid' || dash_get_option('blog_frontend_layout')=='isotope' || isset( $_GET['b_type'] ) ) { echo "</div>"; }

			// Pagination
			if (dash_get_option('blog_frontend_layout')!=='isotope') {
				get_template_part( 'partials/pagination' );
			}

		} else {
			// If no content, include the "No posts found" template.
			get_template_part( 'content', 'none' );
		} ?>

	</main><!-- end of Main content -->

	<?php if ( !isset( $_GET['b_type'] ) || $_GET['b_type'] == '2cols' ) get_sidebar(); ?>

<?php get_footer(); ?>
