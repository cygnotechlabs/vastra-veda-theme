<?php
/**
 * Nav-menu walker for the slide-out drawer.
 *
 * Renders each item as: roman numeral · label · optional count.
 *
 * A count is shown when the item points at the WooCommerce cart page, or when
 * the menu item carries one of these CSS classes (Appearance → Menus →
 * Screen Options → CSS Classes):
 *
 *   vv-count-cart      →  live cart count
 *   vv-count-wishlist  →  wishlist count (filter 'vv_wishlist_count')
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

class VV_Drawer_Walker extends Walker_Nav_Menu {

	/** @var int Running index, used for the roman numeral. */
	protected $vv_index = 0;

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="vv-drawer__sub">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$url     = ! empty( $item->url ) ? $item->url : '';

		$count = null;
		if ( in_array( 'vv-count-cart', $classes, true ) || vv_is_cart_url( $url ) ) {
			$count = vv_cart_count();
		} elseif ( in_array( 'vv-count-wishlist', $classes, true ) || vv_is_wishlist_url( $url ) ) {
			$count = vv_wishlist_count();
		}

		if ( 0 === $depth ) {
			$this->vv_index++;
			$numeral = vv_roman( $this->vv_index );
		} else {
			$numeral = '';
		}

		$classes[] = 'vv-drawer__item';
		if ( 0 !== $depth ) {
			$classes[] = 'vv-drawer__item--sub';
		}

		$output .= sprintf(
			'<li class="%s">',
			esc_attr( implode( ' ', array_filter( array_unique( $classes ) ) ) )
		);

		$output .= sprintf( '<a class="vv-drawer__link" href="%s">', esc_url( $url ) );

		if ( $numeral ) {
			$output .= '<span class="vv-drawer__num" aria-hidden="true">' . esc_html( $numeral ) . '</span>';
		}

		$output .= '<span class="vv-drawer__label">' . esc_html( $item->title ) . '</span>';

		if ( null !== $count ) {
			$output .= '<span class="vv-drawer__meta">' . esc_html(
				sprintf(
					/* translators: %s: number of items */
					_n( '%s Item', '%s Items', (int) $count, 'vastra-veda' ),
					number_format_i18n( (int) $count )
				)
			) . '</span>';
		}

		$output .= '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
