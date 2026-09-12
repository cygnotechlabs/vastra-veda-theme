<?php
/**
 * Post card.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'vv-postcard' ); ?>>
	<a class="vv-postcard__link" href="<?php the_permalink(); ?>">
		<span class="vv-postcard__media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'vv-portrait' );
			} else {
				printf( '<img src="%s" alt="" loading="lazy">', esc_url( vv_placeholder_url( 'cat-1' ) ) );
			}
			?>
		</span>
		<span class="vv-eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
		<h2 class="vv-postcard__title"><?php the_title(); ?></h2>
		<span class="vv-postcard__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></span>
	</a>
</article>
