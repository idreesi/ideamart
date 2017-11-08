<?php /* The template for displaying a "No posts found" message */ ?>

<header class="page-header">
	<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'dashstore' ); ?></h1>
</header>

<div class="page-content">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php esc_html__( 'Ready to publish your first post? ', 'dashstore'); ?>
		<a href="<?php echo admin_url( 'post-new.php' );?>"><?php esc_html__( 'Get started here', 'dashstore' ); ?></a>.
	</p>

	<?php elseif ( is_search() ) : ?>

	<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'dashstore' ); ?></p>
	<?php get_search_form(); ?>

	<?php else : ?>

	<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'dashstore' ); ?></p>
	<?php get_search_form(); ?>

	<?php endif; ?>
</div>
