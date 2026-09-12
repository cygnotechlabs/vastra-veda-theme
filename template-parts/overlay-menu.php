<?php
/**
 * Slide-out (hamburger) menu.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="vv-offcanvas" class="vv-overlay vv-overlay--menu" hidden>
	<div class="vv-overlay__bar">
		<span class="vv-overlay__eyebrow"><?php esc_html_e( 'Menu', 'vastra-veda' ); ?></span>
		<button type="button" class="vv-circle-btn vv-circle-btn--ghost" data-vv-menu-close>
			<?php vv_the_icon( 'close', 18 ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'vastra-veda' ); ?></span>
		</button>
	</div>

	<div class="vv-overlay__body vv-overlay__body--menu">
		<nav class="vv-bignav" aria-label="<?php esc_attr_e( 'Slide-out', 'vastra-veda' ); ?>">
			<?php
			if ( has_nav_menu( 'offcanvas' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'offcanvas',
						'container'      => false,
						'menu_class'     => 'vv-bignav__list',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
			} else {
				$shop = vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
				echo '<ul class="vv-bignav__list">';
				printf( '<li><a href="%s">%s</a></li>', esc_url( $shop ), esc_html__( 'New Arrivals', 'vastra-veda' ) );
				printf( '<li><a href="%s">%s</a></li>', esc_url( $shop ), esc_html__( 'Sarees', 'vastra-veda' ) );
				printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/about/' ) ), esc_html__( 'Our Story', 'vastra-veda' ) );
				printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/contact/' ) ), esc_html__( 'Contact', 'vastra-veda' ) );
				echo '</ul>';
			}
			?>
		</nav>

		<div class="vv-overlay__meta">
			<?php
			$terms = vv_get_category_terms( 8 );
			if ( $terms ) :
				?>
				<div>
					<span class="vv-eyebrow"><?php esc_html_e( 'Shop by fabric', 'vastra-veda' ); ?></span>
					<ul class="vv-linklist">
						<?php foreach ( $terms as $term ) : ?>
							<li><a href="<?php echo esc_url( $term['url'] ); ?>"><?php echo esc_html( $term['name'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<div>
				<span class="vv-eyebrow"><?php esc_html_e( 'Account', 'vastra-veda' ); ?></span>
				<ul class="vv-linklist">
					<?php if ( vv_is_woocommerce_active() ) : ?>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'My account', 'vastra-veda' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'vastra-veda' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'vastra-veda' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
