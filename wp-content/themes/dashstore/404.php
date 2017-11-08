<?php /* The template for displaying 404 pages (Not Found) */
get_header(); ?>

		<main class="site-content<?php if (function_exists('dash_main_content_class')) dash_main_content_class(); ?>" itemscope="itemscope" itemprop="mainContentOfPage"><!-- Main content -->

			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( "Oops! That page can't be found.", 'dashstore' ); ?></h1>
			</header>

			<div class="page-content">
				<img src="<?php echo get_template_directory_uri(); echo '/images/404.jpg'; ?>" title="<?php esc_html_e( "Oops! That page can't be found.", 'dashstore' ); ?>" alt="<?php esc_html_e('Not Found .404 page', 'dashstore'); ?>"/> 
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'dashstore' ); ?></p>
				<?php get_search_form(); ?>
			</div>

		</main><!-- end of Main content -->
		
		<?php get_sidebar();?>

<?php get_footer(); ?>
