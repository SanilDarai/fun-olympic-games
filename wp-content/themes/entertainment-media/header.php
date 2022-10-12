<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php echo esc_attr(get_bloginfo('html_type')); ?>; charset=<?php echo esc_attr(get_bloginfo('charset')); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.2, user-scalable=yes" />

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php
	if ( function_exists( 'wp_body_open' ) )
	{
		wp_body_open();
	}else{
		do_action('wp_body_open');
	}
?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'entertainment-media' ); ?></a>

<div class="topheader py-2 text-center text-md-left">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 align-self-center">
				<div class="logo">
		    		<div class="logo-image">
		    			<?php echo esc_url( the_custom_logo() ); ?>
			    	</div>
			    	<div class="logo-content">
				    	<?php
				    		if ( get_theme_mod('entertainment_media_display_header_title', true) == true ) :
					      		echo '<a href="' . esc_url(home_url('/')) . '" title="' . esc_attr(get_bloginfo('name')) . '">';
					      			echo esc_attr(get_bloginfo('name'));
					      		echo '</a>';
					      	endif;

					      	if ( get_theme_mod('entertainment_media_display_header_text', true) == true ) :
				      			echo '<span>'. esc_attr(get_bloginfo('description')) . '</span>';
				      		endif;
			    		?>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 align-self-center">
				<?php if ( get_theme_mod('entertainment_media_header_google_translator') ) : ?>
					<?php echo do_shortcode('[google-translator]'); ?>
				<?php endif; ?>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 align-self-center">
				<?php if ( get_theme_mod('entertainment_media_sign_in_btn_link') || get_theme_mod('entertainment_media_sign_in_btn_text') ) : ?>
					<a href="<?php echo esc_html(get_theme_mod('entertainment_media_sign_in_btn_link'));?>"><?php echo esc_html(get_theme_mod('entertainment_media_sign_in_btn_text'));?></a>
				<?php endif; ?>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 align-self-center">
				<?php $entertainment_media_settings = get_theme_mod( 'entertainment_media_social_links_settings' ); ?>
				<div class="social-links text-center text-md-right">
					<?php if ( is_array($entertainment_media_settings) || is_object($entertainment_media_settings) ){ ?>
					    	<?php foreach( $entertainment_media_settings as $entertainment_media_setting ) { ?>
						        <a href="<?php echo esc_url( $entertainment_media_setting['link_url'] ); ?>">
						            <i class="<?php echo esc_attr( $entertainment_media_setting['link_text'] ); ?> mr-3"></i>
						        </a>
					    	<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<header id="site-navigation" class="header text-center text-md-left py-2">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-8 col-sm-8 align-self-center">
				<?php if(has_nav_menu('main-menu')){ ?>
					<button class="menu-toggle my-2 py-2 px-3" aria-controls="top-menu" aria-expanded="false" type="button">
						<span aria-hidden="true"><?php esc_html_e( 'Menu', 'entertainment-media' ); ?></span>
					</button>
					<nav id="main-menu" class="close-panal">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'main-menu',
								'container' => 'false'
							));
						?>
						<button class="close-menu my-2 p-2" type="button">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
						</button>
					</nav>
				<?php }?>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 align-self-center">
				<div class="header-search text-center py-3 py-md-0">
	            	<?php if ( get_theme_mod('entertainment_media_search_box_enable', true) == true ) : ?>
	            		<form method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
				            <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'entertainment-media'); ?></label>
				            <input type="search" id="woocommerce-product-search-field" class="search-field " placeholder="<?php echo esc_html('Search Here','entertainment-media'); ?>" value="<?php echo get_search_query(); ?>" name="s"/>
				            <button type="submit" value="" class="search-button"><i class="fas fa-search"></i></button>
				            <input type="hidden" name="post_type" value="product"/>
			          	</form>
	            	<?php endif; ?>
	            </div>
	       	</div>
	   	</div>
	</div>
</header>