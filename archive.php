<?php
/**
 * Archives.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
get_header();
vv_page_hero( get_the_archive_title(), wp_strip_all_tags( get_the_archive_description() ) );
?>
<section class="vv-section">
	<div class="vv-shell">
		<?php if ( have_posts() ) : ?>
			<div class="vv-grid vv-grid--3">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content/content', get_post_type() );
				}
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<p class="vv-empty"><?php esc_html_e( 'Nothing found.', 'vastra-veda' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
