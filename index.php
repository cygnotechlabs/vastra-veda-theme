<?php
/**
 * Fallback template / blog index.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

get_header();

vv_page_hero( is_home() && ! is_front_page() ? get_the_title( get_option( 'page_for_posts' ) ) : get_bloginfo( 'name' ) );
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
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => vv_icon( 'arrow-left', 16 ),
					'next_text' => vv_icon( 'arrow', 16 ),
				)
			);
			?>
		<?php else : ?>
			<p class="vv-empty"><?php esc_html_e( 'Nothing here yet.', 'vastra-veda' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
