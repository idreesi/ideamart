<?php /* The template used for displaying page content */ ?>

	<div class="page-content entry-content"><!-- Page content -->
		
		<?php if ( function_exists('dash_output_page_title') ) dash_output_page_title(); ?>

		<?php the_content(); ?>
		
		<?php wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dashstore' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '%',
			'separator'   => '&nbsp;',
		) ); ?>

	</div><!-- end of Page content -->
