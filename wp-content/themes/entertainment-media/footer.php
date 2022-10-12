<footer>
  <div class="container">
    <?php
      if ( is_active_sidebar('entertainment-media-footer-sidebar')) {
        echo '<div class="row sidebar-area footer-area">';
          dynamic_sidebar('entertainment-media-footer-sidebar');
        echo '</div>';
      }
    ?>
    <div class="row">
      <div class="col-md-12">
        <p class="mb-0 py-3 text-center text-md-center">
          <?php
            if (!get_theme_mod('entertainment_media_footer_text') ) { ?>
              <?php esc_html_e('&copy; right reserved by Fun Olympics 2022',''); ?>
            <?php } else {
              echo esc_html(get_theme_mod('entertainment_media_footer_text'));
            }
          ?>
          <?php if ( get_theme_mod('entertainment_media_copyright_enable', true) == true ) : ?>
            <?php 
            /* translators: %s: Misbah WP */ 
            printf( esc_html__( '', 'entertainment-media' ), '' ); ?>
            <a href="<?php echo esc_url(__('https://wordpress.org', 'entertainment-media' )); ?>" rel="generator"><?php  /* translators: %s: WordPress */  printf( esc_html__( '', '' ), '' ); ?></a>
          <?php endif; ?>
        </p>
      </div>
    </div>
    <?php if ( get_theme_mod('entertainment_media_scroll_enable_setting', true) == true ) : ?>
      <div class="scroll-up">
        <a href="#tobottom"><i class="fa fa-arrow-up"></i></a>
      </div>
    <?php endif; ?>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
