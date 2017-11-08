<?php // Logo & Hgroup Sidebar

	$logo_position = dash_get_option('site_logo_position');
	switch ($logo_position) {
		case 'left':
			$logo_class = ' col-lg-3 col-md-3 col-sm-12';
			$sidebar_class = ' col-lg-9 col-md-12 col-sm-12';
		break;
		case 'center':
			$logo_class = ' col-lg-4 col-md-4 col-sm-12 col-md-offset-4 col-lg-offset-4 center-pos';
			$sidebar_class = ' col-lg-12 col-md-12 col-sm-12 center-pos';
		break;
		case 'right':
			$logo_class = ' col-md-3 col-lg-3 col-sm-12 col-lg-push-9 col-md-push-9 right-pos';
			$sidebar_class = ' col-lg-9 col-md-12 col-sm-12 col-lg-pull-3 right-pos';
		break;
		default:
			$logo_class = ' col-md-3 col-sm-12';
			$sidebar_class = ' col-md-9 col-sm-12';
	}; ?>

	<?php /* Check if logo img exists */
		$img_url = dash_get_option('site_logo');
		if ( $img_url && $img_url !='' ) {
			$upload_dir = wp_upload_dir();
			$upload_basedir = $upload_dir['basedir'];
			$upload_subdir = substr( strstr($img_url, 'uploads'), 7 );
			$img_file = $upload_basedir . $upload_subdir;
		} ?>

	<?php if ( dash_get_option('site_logo') && file_exists( $img_file ) ) : ?>
		<div class="site-logo<?php echo esc_attr($logo_class);?>" itemscope="itemscope" itemtype="http://schema.org/Organization">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php esc_attr( bloginfo( 'name' ) );?>" itemprop="url">
					<img src="<?php echo esc_url(dash_get_option('site_logo')) ?>" alt="<?php esc_html( bloginfo( 'description' ) ); ?>" itemprop="logo" />
				</a>
		</div>
	<?php else : ?>
		<div class="header-group<?php echo esc_attr($logo_class);?>">
			<h1 class="site-title" itemprop="headline">
				<?php if ( !is_front_page() ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home" itemprop="url">
						<?php esc_attr( bloginfo( 'name' ) ); ?>
					</a>
				<?php else : esc_attr( bloginfo( 'name' ) ); endif; ?>
			</h1>
			<h2 class="site-description" itemprop="description"><?php esc_html( bloginfo( 'description' ) ); ?></h2>
		</div>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'hgroup-sidebar' ) ) : ?>
	    <div class="hgroup-sidebar<?php echo esc_attr($sidebar_class);?>">
	        <?php dynamic_sidebar( 'hgroup-sidebar' ); ?>
	    </div>
	<?php endif; ?>
