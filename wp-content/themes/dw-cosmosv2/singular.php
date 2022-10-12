<?php get_header(); ?>	
	<div class="blog-column">
		<div id="blog-container">
			<div class="singlealign">
				<div class="blog-content">
					<?php the_post(); ?>
					<div <?php post_class(); ?>>
						<h3 class="blog-title">
							<?php the_title(); ?>
						</h3>
						<div class="main">
							<div class="thumbsingle"><?php the_post_thumbnail( 'dw-cosmosv2-single-post-thumbnail' ); ?></div>
							<div class="scontent"><?php the_content(); ?></div>
							<?php 
							if ( get_the_author() ) :
								dw_cosmosv2_post_meta();
							endif;
							?>
							<?php wp_link_pages(); ?>
						</div>
					</div>

					<?php comments_template(); ?>
					<div class="postnav">
					<?php 
					if ( is_single() ) :
						the_post_navigation(
							array(
								'prev_text' => '&#171; %title',
								'next_text' => '%title &#187;',
							) 
						); 
					endif;
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
