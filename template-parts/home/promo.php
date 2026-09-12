<?php
/**
 * Section 3 — split promo ("Own your drape").
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

$vv_btn_url = get_theme_mod( 'vv_promo_btn_url' );
if ( ! $vv_btn_url ) {
	$vv_btn_url = vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}
?>
<section class="vv-promo">
	<div class="vv-promo__inner">

		<div class="vv-promo__text">
			<h2 class="vv-display vv-promo__title">
				<?php vv_the_headline( get_theme_mod( 'vv_promo_heading', 'OWN YOUR *drape*' . "\n" . 'EXPLORE SAREES MADE' . "\n" . '*for your* UNIQUE STYLE.' ) ); ?>
			</h2>

			<a class="vv-btn vv-btn--green" href="<?php echo esc_url( $vv_btn_url ); ?>">
				<?php echo esc_html( get_theme_mod( 'vv_promo_btn_text', __( 'Shop Now', 'vastra-veda' ) ) ); ?>
			</a>
		</div>

		<div class="vv-promo__media">
			<img src="<?php echo esc_url( vv_image_url( 'vv_promo_image', 'promo', 'vv-hero' ) ); ?>"
				alt="" loading="lazy" decoding="async">
		</div>

	</div>
</section>
