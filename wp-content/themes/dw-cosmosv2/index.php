<?php get_header(); ?>

<!-- ? BLOG AREA STARTS -->

<div class="blog-column">

<!-- BEGIN CENTER BLOCK 1 -->

<?php if ( is_active_sidebar( 'center-block' ) ) : ?>

<div class="blog-container">
	<div class="center-content">
		<div class="content-pos-1">
			<div class="content-pos-1-inner"></div>
		</div>
		<div class="content-pos-2">
			<div class="content-pos-2-inner">
				<div class="centerblk"><?php dynamic_sidebar( 'center-block' ); ?></div>
			</div>
		</div>
		<div class="content-pos-3">
			<div class="content-pos-3-inner"></div>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- END CENTER BLOCK 1 -->

<!-- ARTICLES AREA STARS -->

		<?php 
		while ( have_posts() ) : 
			the_post();
			?>
	<div class="storyalign">
		<div class="blog-container">
			<h3 class="blog-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<div class="blog-content">
				<div <?php post_class(); ?>>
					<div class="pcontent">

					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'dw-cosmosv2-homepage-featured' ); ?></a>
						<div class="vertical-line"></div>
					<?php endif; ?>

					<?php the_excerpt(); ?>
					</div>
					<div id="continuelink">
						<?php /* translators: %s = post title */ ?>
						<a href="<?php the_permalink(); ?>"><?php printf( esc_html__( 'Continue reading %s', 'dw-cosmosv2' ), wp_kses_post( get_the_title() ) ); ?></a>
					</div>
					<div class="meta-article">
						<span><?php dw_cosmosv2_post_meta(); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endwhile; ?>

<!-- ARTICLE AREA ENDS -->

<div class="paginationlink">

<?php 
the_posts_pagination(
	array(
		'prev_text' => '&#171; ',
		'next_text' => ' &#187;',
		
	)
); 
?>
</div>
</div>

<?php get_footer(); ?>
