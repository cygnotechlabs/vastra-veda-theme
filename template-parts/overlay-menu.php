<?php
/**
 * Slide-out (hamburger) drawer.
 *
 * Assign a menu to the "Slide-out menu" location to control the items.
 * Add the CSS class vv-count-cart or vv-count-wishlist to a menu item to show
 * its live count; the WooCommerce cart page is detected automatically.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="vv-offcanvas" class="vv-overlay vv-overlay--menu" hidden>

	<button type="button" class="vv-drawer__scrim" data-vv-menu-close tabindex="-1">
		<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'vastra-veda' ); ?></span>
	</button>

	<div class="vv-drawer" role="dialog" aria-modal="true"
		aria-label="<?php esc_attr_e( 'Menu', 'vastra-veda' ); ?>">

		<button type="button" class="vv-drawer__close" data-vv-menu-close>
			<?php vv_the_icon( 'close', 18 ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'vastra-veda' ); ?></span>
		</button>

		<span class="vv-drawer__brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>

		<nav class="vv-drawer__nav" aria-label="<?php esc_attr_e( 'Slide-out', 'vastra-veda' ); ?>">
			<?php
			if ( has_nav_menu( 'offcanvas' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'offcanvas',
						'container'      => false,
						'menu_class'     => 'vv-drawer__list',
						'depth'          => 2,
						'walker'         => new VV_Drawer_Walker(),
						'fallback_cb'    => false,
					)
				);
			} else {
				echo '<ul class="vv-drawer__list">';
				foreach ( vv_drawer_fallback_items() as $vv_i => $vv_item ) {
					printf(
						'<li class="vv-drawer__item"><a class="vv-drawer__link" href="%1$s">'
							. '<span class="vv-drawer__num" aria-hidden="true">%2$s</span>'
							. '<span class="vv-drawer__label">%3$s</span>',
						esc_url( $vv_item['url'] ),
						esc_html( vv_roman( $vv_i + 1 ) ),
						esc_html( $vv_item['label'] )
					);

					if ( null !== $vv_item['count'] ) {
						printf(
							'<span class="vv-drawer__meta">%s</span>',
							esc_html(
								sprintf(
									/* translators: %s: number of items */
									_n( '%s item', '%s items', (int) $vv_item['count'], 'vastra-veda' ),
									number_format_i18n( (int) $vv_item['count'] )
								)
							)
						);
					}

					echo '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</nav>

		<?php
		$vv_drawer_note = get_theme_mod( 'vv_drawer_note', __( 'Handloomed with care · Since heritage', 'vastra-veda' ) );
		if ( $vv_drawer_note ) :
			?>
			<p class="vv-drawer__note"><?php echo esc_html( $vv_drawer_note ); ?></p>
		<?php endif; ?>

	</div>
</div>
