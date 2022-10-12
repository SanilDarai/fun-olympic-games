<?php 
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 */
get_header();
?>
<h1 id="index-type"><?php esc_html_e( 'Not Found', 'dw-cosmosv2' ); ?></h1>
<div class="hentry">
	<?php esc_html_e( 'The requested page was not found.', 'dw-cosmosv2' ); ?>
	<?php get_search_form(); ?>
</div>
<?php get_footer(); ?>
