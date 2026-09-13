<?php
/**
 * Homepage.
 *
 * Sections, in the order they appear:
 *   1. Intro slider          template-parts/home/hero.php
 *   2. Shop by category      template-parts/home/categories.php
 *   3. Split promo           template-parts/home/promo.php
 *   4. From our stories      template-parts/home/stories.php
 *   5. Editorial cards       template-parts/home/editorial.php
 *   6. New arrivals          template-parts/home/arrivals.php (off by default)
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/categories' );
get_template_part( 'template-parts/home/promo' );
get_template_part( 'template-parts/home/stories' );
get_template_part( 'template-parts/home/editorial' );
get_template_part( 'template-parts/home/arrivals' );

/* Anything typed into the static front page in the editor renders below. */
if ( is_page() ) {
	while ( have_posts() ) {
		the_post();
		$content = get_the_content();
		if ( trim( wp_strip_all_tags( $content ) ) ) {
			echo '<section class="vv-section"><div class="vv-shell vv-prose">';
			the_content();
			echo '</div></section>';
		}
	}
}

get_footer();
