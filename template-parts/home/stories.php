<?php
/**
 * Section — "From our stories": a six-tile mosaic of social / lookbook posts.
 *
 * Layout matches the Figma frame: two tall tiles (with a play button) each
 * followed by a column of two stacked tiles.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_theme_mod( 'vv_stories_on', true ) ) {
	return;
}

$vv_link_url  = get_theme_mod( 'vv_stories_link_url' );
$vv_link_text = get_theme_mod( 'vv_stories_link_text', __( 'Follow Us', 'vastra-veda' ) );

$vv_tiles = array();
for ( $i = 1; $i <= 6; $i++ ) {
	$vv_tiles[] = array(
		'image' => vv_image_url( "vv_stories_{$i}_image", 'story-' . $i, 'vv-portrait', 'cat-' . $i ),
		'link'  => get_theme_mod( "vv_stories_{$i}_link", $vv_link_url ),
		/* The two tall tiles carry the play affordance, as in the design. */
		'video' => in_array( $i, array( 1, 4 ), true ),
	);
}
?>
<section class="vv-section vv-stories">
	<div class="vv-shell">

		<?php
		vv_section_head(
			array(
				'heading'   => get_theme_mod( 'vv_stories_heading', 'FROM OUR *stories*' ),
				'link_url'  => $vv_link_url ? $vv_link_url : '#',
				'link_text' => $vv_link_text,
			)
		);
		?>

		<p class="vv-stories__intro">
			<?php echo esc_html( get_theme_mod( 'vv_stories_intro', __( 'Discover sarees, styling moments, and the latest from Vastra Veda on Instagram.', 'vastra-veda' ) ) ); ?>
		</p>

		<div class="vv-stories__grid">
			<?php foreach ( $vv_tiles as $vv_i => $vv_tile ) : ?>
				<?php
				$vv_tag  = $vv_tile['link'] ? 'a' : 'div';
				$vv_href = $vv_tile['link'] ? ' href="' . esc_url( $vv_tile['link'] ) . '" target="_blank" rel="noopener noreferrer"' : '';
				?>
				<<?php echo esc_attr( $vv_tag ) . $vv_href; // phpcs:ignore ?>
					class="vv-tile<?php echo $vv_tile['video'] ? ' vv-tile--video' : ''; ?>">
					<img src="<?php echo esc_url( $vv_tile['image'] ); ?>" alt="" loading="lazy" decoding="async">
					<?php if ( $vv_tile['video'] ) : ?>
						<span class="vv-tile__play" aria-hidden="true">
							<svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.5v13l11-6.5-11-6.5Z"/></svg>
						</span>
					<?php endif; ?>
					<?php if ( $vv_tile['link'] ) : ?>
						<span class="screen-reader-text"><?php printf( esc_html__( 'Story %d', 'vastra-veda' ), (int) $vv_i + 1 ); ?></span>
					<?php endif; ?>
				</<?php echo esc_attr( $vv_tag ); ?>>
			<?php endforeach; ?>
		</div>

	</div>
</section>
