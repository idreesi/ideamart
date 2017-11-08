<?php /* The default template for displaying content. */
/* Add responsive bootstrap classes */
$classes = array();
if (function_exists('dash_single_content_class')) $classes[] = dash_single_content_class();

// Lazyload for posts
$add_lazy_class = true;
if ( dash_get_option('blog_frontend_layout')=='isotope' ||
	 dash_get_option('blog_frontend_layout')=='grid' ||
	 is_single() ||
	 isset( $_GET['b_type'] ) ) {
	$add_lazy_class = false;
}
if ( dash_get_option('lazyload_on_blog')==='on' && $add_lazy_class ) {
	$classes[] = 'lazyload';
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?> itemscope="itemscope" itemtype="http://schema.org/Article" <?php if ( !is_single() ) { echo ' data-expand="-100"'; } ?>><!-- Article ID-<?php the_ID(); ?> -->

	<?php
		if ( is_sticky() && is_home() && ! is_paged() ) {
			printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'dashstore' ) );
		}
	?>

	<header class="entry-header"><!-- Article's Header -->

		<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
			<div class="thumbnail-wrapper">
				<?php the_post_thumbnail('post-thumbnail', array('itemprop'=>'image', 'class'=>'lazyload') );
				if ( ( dash_get_option('blog_frontend_layout')=='grid' || dash_get_option('blog_frontend_layout')=='isotope' || isset( $_GET['b_type'] ) ) && !is_single() && !is_search()) : ?>
					<a class="posts-img-link button" rel="bookmark" href="<?php esc_url(the_permalink()); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Click to read more about %s', 'dashstore' ), the_title_attribute( 'echo=0' ) ) ); ?>" itemprop="url"><?php esc_html_e('Read More', 'dashstore');?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php
			if ( is_single() ) : // Singular page
				the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
			elseif ( is_search() ) : // Search Results
				$title = get_the_title();
	  			$keys = explode(" ",$s);
	  			$title = preg_replace('/('.implode('|', $keys) .')/iu', '<strong class="search-excerpt">\0</strong>', $title); ?>
				<h1 class="entry-title search-title" itemprop="headline">
					<a href="<?php esc_url(the_permalink()); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Click to read more about %s', 'dashstore' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" itemprop="url"><?php echo $title; ?></a>
				</h1>
			<?php else :
				$title = get_the_title();
				if ( empty($title) || $title = '' ) { ?>
					<h1 class="entry-title" itemprop="headline">
						<a href="<?php esc_url(the_permalink()); ?>" title="<?php esc_html_e( 'Click here to read more', 'dashstore' ); ?>" rel="bookmark" itemprop="url"><?php esc_html_e( 'Click here to read more', 'dashstore' ); ?></a>
					</h1>
				<?php } else {
					the_title( '<h1 class="entry-title" itemprop="headline"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" itemprop="url">', '</a></h1>' );
				}
			endif; ?>

		<div class="entry-meta">
			<?php dash_entry_publication_time()?>
			<?php dash_entry_author(); ?>
			<?php dash_entry_post_cats(); ?>
			<?php dash_entry_post_tags(); ?>
		</div>

	</header><!-- end of Article's Header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search
	  	$excerpt = get_the_excerpt();
	  	$keys = explode(" ",$s);
	  	$excerpt = preg_replace('/('.implode('|', $keys) .')/iu', '<strong class="search-excerpt">\0</strong>', $excerpt);
	?>
		<div class="entry-summary" itemprop="articleBody"><!-- Excerpt -->
			<?php echo $excerpt; ?>
		</div><!-- end of Excerpt -->
	<?php else : ?>
		<div class="entry-content" itemprop="articleBody"><!-- Content -->
			<?php the_content( apply_filters( 'dash_more', 'Continue Reading...') ); ?>

			<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dashstore' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '%',
				'separator'   => '&nbsp;',
			) ); ?>
		</div><!-- end of Content -->
	<?php endif; ?>

	<?php /* Footer of the article */ get_template_part( 'partials/post-meta' ); ?>

</article><!-- end of Article ID-<?php the_ID(); ?> -->
