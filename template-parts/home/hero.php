<?php
/**
 * Section 1 — full-screen intro slider.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

$vv_defaults = vv_hero_defaults();
$vv_slides   = array();

foreach ( array( 1, 2, 3 ) as $i ) {
	$text = get_theme_mod( "vv_hero_{$i}_text", $vv_defaults[ $i ] );
	if ( ! trim( (string) $text ) ) {
		continue;
	}
	$vv_slides[] = array(
		'image' => vv_image_url( "vv_hero_{$i}_image", 'hero-' . $i, 'vv-hero' ),
		'text'  => $text,
		'link'  => get_theme_mod( "vv_hero_{$i}_link" ),
	);
}

if ( ! $vv_slides ) {
	return;
}
?>
<section class="vv-hero" data-vv-hero aria-roledescription="carousel"
	aria-label="<?php esc_attr_e( 'Introduction', 'vastra-veda' ); ?>">

	<div class="vv-hero__stage">
		<?php foreach ( $vv_slides as $i => $slide ) : ?>
			<article class="vv-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
				data-vv-slide="<?php echo (int) $i; ?>"
				role="group"
				aria-roledescription="slide"
				aria-label="<?php printf( esc_attr__( 'Slide %1$d of %2$d', 'vastra-veda' ), (int) $i + 1, count( $vv_slides ) ); ?>"
				<?php echo 0 === $i ? '' : 'aria-hidden="true"'; ?>>

				<div class="vv-hero__media" style="background-image:url('<?php echo esc_url( $slide['image'] ); ?>')"></div>
				<div class="vv-hero__scrim" aria-hidden="true"></div>

				<div class="vv-hero__inner">
					<?php
					$tag = ( 0 === $i && is_front_page() ) ? 'h1' : 'p';
					printf( '<%1$s class="vv-display vv-hero__title">', esc_attr( $tag ) );
					vv_the_headline( $slide['text'] );
					printf( '</%1$s>', esc_attr( $tag ) );

					if ( $slide['link'] ) :
						?>
						<a class="vv-btn vv-btn--ghost vv-hero__cta" href="<?php echo esc_url( $slide['link'] ); ?>">
							<?php esc_html_e( 'Discover', 'vastra-veda' ); ?>
						</a>
						<?php
					endif;
					?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $vv_slides ) > 1 ) : ?>
		<div class="vv-hero__rail">
			<div class="vv-hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose slide', 'vastra-veda' ); ?>">
				<?php foreach ( $vv_slides as $i => $slide ) : ?>
					<button type="button" class="vv-dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
						data-vv-dot="<?php echo (int) $i; ?>" role="tab"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<span class="screen-reader-text"><?php printf( esc_html__( 'Go to slide %d', 'vastra-veda' ), (int) $i + 1 ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
			<span class="vv-hero__tick" aria-hidden="true"></span>
			<button type="button" class="vv-hero__skip" data-vv-skip>
				<?php esc_html_e( 'Skip', 'vastra-veda' ); ?>
			</button>
		</div>
	<?php endif; ?>
</section>
