<?php
/**
 * Single page.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	vv_page_hero( get_the_title() );
	?>
	<section class="vv-section">
		<div class="vv-shell">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="vv-featured"><?php the_post_thumbnail( 'full' ); ?></figure>
			<?php endif; ?>
			<div class="vv-prose">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<div class="vv-linkpages">', 'after' => '</div>' ) );
				?>
			</div>
		</div>
	</section>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="vv-shell">';
		comments_template();
		echo '</div>';
	}
endwhile;

get_footer();
