<?php
/**
 * Single post (journal entry).
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'vv-single' ); ?>>
		<header class="vv-page-hero">
			<div class="vv-shell">
				<span class="vv-eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
				<h1 class="vv-display vv-page-hero__title"><?php the_title(); ?></h1>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="vv-single__cover"><?php the_post_thumbnail( 'full' ); ?></figure>
		<?php endif; ?>

		<div class="vv-section">
			<div class="vv-shell vv-shell--narrow vv-prose">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<div class="vv-linkpages">', 'after' => '</div>' ) );
				?>
			</div>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="vv-shell vv-shell--narrow">';
		comments_template();
		echo '</div>';
	}
endwhile;

get_footer();
