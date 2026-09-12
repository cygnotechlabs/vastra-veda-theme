<?php
/**
 * Search results.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
get_header();
vv_page_hero(
	sprintf( '*%s*', esc_html( get_search_query() ) ),
	sprintf(
		/* translators: %d: number of results */
		esc_html( _n( '%d result', '%d results', (int) $wp_query->found_posts, 'vastra-veda' ) ),
		(int) $wp_query->found_posts
	)
);
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
			<p class="vv-empty"><?php esc_html_e( 'No matches. Try a fabric — Kanjivaram, Banarasi, Organza.', 'vastra-veda' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
