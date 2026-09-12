<?php
/**
 * Section 2 — Shop by category.
 * Desktop: the row is pinned and scrolls sideways as the page scrolls down.
 * Touch / reduced motion: a normal snap-scrolling row.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

$vv_terms = vv_get_category_terms( (int) get_theme_mod( 'vv_cats_count', 6 ) );
if ( ! $vv_terms ) {
	return;
}

$vv_pinned    = (bool) get_theme_mod( 'vv_cats_pinned', true );
$vv_link_url  = get_theme_mod( 'vv_cats_link_url' );
$vv_link_text = get_theme_mod( 'vv_cats_link_text', __( 'All Categories', 'vastra-veda' ) );

if ( ! $vv_link_url ) {
	$vv_link_url = vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}
?>
<section class="vv-cats<?php echo $vv_pinned ? ' is-pinned' : ''; ?>"
	data-vv-cats
	data-pinned="<?php echo $vv_pinned ? '1' : '0'; ?>">

	<div class="vv-shell">
		<?php
		vv_section_head(
			array(
				'heading'   => get_theme_mod( 'vv_cats_heading', 'SHOP BY *category*' ),
				'link_url'  => $vv_link_url,
				'link_text' => $vv_link_text,
			)
		);
		?>
	</div>

	<div class="vv-cats__pin" data-vv-pin>
		<div class="vv-cats__viewport" data-vv-viewport>
			<div class="vv-cats__track" data-vv-track>

				<div class="vv-cats__intro">
					<p><?php echo esc_html( get_theme_mod( 'vv_cats_intro', __( 'Every saree is made for a different expression of you — the woman who leads, creates, dreams, celebrates, and simply chooses to be herself.', 'vastra-veda' ) ) ); ?></p>
				</div>

				<?php foreach ( $vv_terms as $vv_term ) : ?>
					<a class="vv-cat-card" href="<?php echo esc_url( $vv_term['url'] ); ?>">
						<span class="vv-cat-card__media">
							<img src="<?php echo esc_url( $vv_term['image'] ); ?>"
								alt="<?php echo esc_attr( $vv_term['name'] ); ?>"
								loading="lazy" decoding="async">
						</span>
						<span class="vv-cat-card__veil" aria-hidden="true"></span>
						<span class="vv-display vv-cat-card__name"><?php echo esc_html( $vv_term['name'] ); ?></span>
						<?php if ( $vv_term['count'] ) : ?>
							<span class="vv-cat-card__count">
								<?php printf( esc_html( _n( '%d piece', '%d pieces', $vv_term['count'], 'vastra-veda' ) ), (int) $vv_term['count'] ); ?>
							</span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>

				<a class="vv-cat-card vv-cat-card--all" href="<?php echo esc_url( $vv_link_url ); ?>">
					<span class="vv-display vv-cat-card__name"><?php esc_html_e( 'View all', 'vastra-veda' ); ?></span>
					<?php vv_the_icon( 'arrow', 26 ); ?>
				</a>

			</div>
		</div>
	</div>
</section>
