<?php if ( get_theme_mod('entertainment_media_featured_movies_section_enable') ) : ?>

<div class="featured_movies py-5">
	<div class="container">
		<?php if ( get_theme_mod('entertainment_media_featured_movies_heading') ) : ?>
			<h3 class="pb-4"><?php echo esc_html(get_theme_mod('entertainment_media_featured_movies_heading')); ?></h3>
		<?php endif; ?>
        <?php $args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'category_name' =>  get_theme_mod('entertainment_media_featured_moviess_category'),
			'posts_per_page' => 9,
		); ?>
		<div class="row">
		    <?php $arr_posts = new WP_Query( $args );
		    	if ( $arr_posts->have_posts() ) :
		      	while ( $arr_posts->have_posts() ) :
		        $arr_posts->the_post();
		        ?>
		        <div class="col-lg-3 col-md-6 col-sm-6">
		        	<div class="box mb-4">
		                <?php if ( has_post_thumbnail() ) :
							the_post_thumbnail();
						endif; ?>
		                <div class="box-content">
	                        <h4 class="title"><?php the_title(); ?></h4>
	                        <p class="post"><?php echo wp_trim_words( get_the_content(), get_theme_mod('entertainment_media_post_excerpt_number',10) ); ?></p>
	                        <a class="icon" href="<?php the_permalink(); ?>"><?php esc_html_e('Play Now','entertainment-media'); ?></a>
		                </div>
		            </div>
			    </div>
		      	<?php
		    endwhile;
		    wp_reset_postdata();
		    endif; ?>
		</div>
	</div>
</div>

<?php endif; ?>