<?php
/**
 * Reusable template helpers.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

/**
 * Turn an editor-friendly string into an editorial headline.
 *
 * Anything wrapped in *asterisks* becomes the italic display-serif accent, e.g.
 *   "TRADITION *and* MODERN GRACE *in* EVERY DRAPE"
 * renders as
 *   TRADITION <em>and</em> MODERN GRACE <em>in</em> EVERY DRAPE
 *
 * Line breaks in the field are preserved.
 *
 * @param string $text Raw string from the Customizer.
 * @return string Safe HTML.
 */
function vv_headline( $text ) {
	$text = wp_strip_all_tags( (string) $text );
	$text = esc_html( $text );
	$text = preg_replace( '/\*(.+?)\*/u', '<em>$1</em>', $text );
	$text = nl2br( $text, false );

	return $text;
}

function vv_the_headline( $text ) {
	echo wp_kses( vv_headline( $text ), array( 'em' => array(), 'br' => array() ) ); // phpcs:ignore
}

/**
 * Inline SVG icon set.
 *
 * @param string $name Icon slug.
 * @param int    $size Pixel size.
 */
function vv_icon( $name, $size = 18 ) {
	$s = (int) $size;
	$icons = array(
		'search' => '<circle cx="11" cy="11" r="6.4"/><path d="M15.8 15.8 21 21"/>',
		'bag'    => '<path d="M6 8h12l-1 12H7L6 8Z"/><path d="M9.2 8V6.6a2.8 2.8 0 0 1 5.6 0V8"/>',
		'close'  => '<path d="M6 6l12 12M18 6 6 18"/>',
		'arrow'  => '<path d="M4 12h15"/><path d="m13 6 6 6-6 6"/>',
		'arrow-left' => '<path d="M20 12H5"/><path d="m11 6-6 6 6 6"/>',
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'minus'  => '<path d="M5 12h14"/>',
		'plus'   => '<path d="M12 5v14M5 12h14"/>',
		'heart'  => '<path d="M12 20s-7-4.6-7-9.4A4.1 4.1 0 0 1 12 7.7a4.1 4.1 0 0 1 7 2.9C19 15.4 12 20 12 20Z"/>',
		'menu'   => '<path d="M4 8h16M4 16h16"/>',
		'eye'    => '<path d="M2.5 12S6 5.8 12 5.8 21.5 12 21.5 12 18 18.2 12 18.2 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/>',
		'eye-off'=> '<path d="M4 4l16 16"/><path d="M9.9 5.2A9.6 9.6 0 0 1 12 5c6 0 9.5 7 9.5 7a17 17 0 0 1-3 3.8M6.4 7.5A16.6 16.6 0 0 0 2.5 12S6 19 12 19a9.4 9.4 0 0 0 3.5-.7"/>',
		'user'   => '<circle cx="12" cy="8.4" r="3.6"/><path d="M4.8 19.6a7.2 7.2 0 0 1 14.4 0"/>',
		'box'    => '<path d="M12 3.6 20 8v8l-8 4.4L4 16V8l8-4.4Z"/><path d="M4 8l8 4.4L20 8M12 12.4V20.4"/>',
		'pin'    => '<path d="M12 21s6.5-5.6 6.5-10.3A6.5 6.5 0 0 0 5.5 10.7C5.5 15.4 12 21 12 21Z"/><circle cx="12" cy="10.5" r="2.4"/>',
		'logout' => '<path d="M9 4.5H5.5a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5H9"/><path d="M15 8l4 4-4 4M19 12H9"/>',
		/* Social glyphs — simple outline marks, sized for the 36px rings. */
		'facebook'  => '<path d="M14.6 7.2h1.6V4.6h-2.2c-1.9 0-3.1 1.2-3.1 3.2v1.9H8.6v2.6h2.3V19.4h2.7v-7.1h2.2l.4-2.6h-2.6V8.2c0-.7.3-1 1-1Z"/>',
		'instagram' => '<rect x="4.4" y="4.4" width="15.2" height="15.2" rx="4.4"/><circle cx="12" cy="12" r="3.6"/><circle cx="16.6" cy="7.4" r=".9" fill="currentColor" stroke="none"/>',
		'youtube'   => '<rect x="3.2" y="6.4" width="17.6" height="11.2" rx="3.4"/><path d="m10.4 9.6 4.6 2.4-4.6 2.4V9.6Z"/>',
		'x'         => '<path d="m5 5 14 14M19 5 5 19"/>',
		'linkedin'  => '<rect x="4.4" y="4.4" width="15.2" height="15.2" rx="2.4"/><path d="M8.2 10.6v6M8.2 8.1v.1M12 16.6v-3.4a1.9 1.9 0 0 1 3.8 0v3.4M12 16.6v-6"/>',
		'pinterest' => '<circle cx="12" cy="12" r="7.6"/><path d="M10.2 19.2 12 12.4M9.8 11.2c0-1.6 1.2-2.8 2.7-2.8s2.5 1 2.5 2.5c0 1.9-1 3.2-2.4 3.2-.7 0-1.3-.5-1.1-1.2"/>',
		'whatsapp'  => '<path d="M20 12a8 8 0 1 1-3.4-6.5L20 4l-1.4 3.4A7.9 7.9 0 0 1 20 12Z"/><path d="M9.4 9.6c0 3 2 5 5 5 .9 0 1.2-.6 1-1.1l-1.3-.7-.9.8c-1-.4-1.7-1.1-2.1-2.1l.8-.9-.7-1.3c-.5-.2-1.1.1-1.1 1Z"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="vv-icon vv-icon--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		$s,
		$icons[ $name ]
	);
}

function vv_the_icon( $name, $size = 18 ) {
	echo vv_icon( $name, $size ); // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Brand mark — custom logo if set, otherwise the site title in the display serif.
 */
function vv_site_brand() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="vv-brand" href="%1$s" rel="home">%2$s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Default copy for the three intro slides. Shared by the Customizer and the
 * template so the homepage looks complete on a fresh install.
 */
function vv_hero_defaults() {
	return array(
		1 => "TRADITION *and*\nMODERN GRACE\n*in* EVERY DRAPE",
		2 => "WOVEN *by* HAND\nWORN *with*\nINTENTION",
		/* Slide 3 is off until a third photograph exists — set a headline in
		   Customize → 1 · Intro slider to switch it on. */
		3 => "",
	);
}

/**
 * Artwork shipped with the theme, used until an image is set in the Customizer.
 *
 * Real photography (.jpg/.jpeg/.png/.webp) in assets/images/ wins over the
 * generated .svg placeholder of the same name, so dropping "hero-2.jpg" in
 * that folder replaces slide two without touching a template.
 *
 * @param string $slug One of: hero-1, hero-2, hero-3, cat-1..cat-6, promo, product.
 */
function vv_placeholder_url( $slug, $fallback = '' ) {
	foreach ( array_filter( array( $slug, $fallback ) ) as $candidate ) {
		$candidate = sanitize_file_name( $candidate );
		foreach ( array( 'jpg', 'jpeg', 'png', 'webp', 'avif', 'svg' ) as $ext ) {
			if ( file_exists( VV_DIR . '/assets/images/' . $candidate . '.' . $ext ) ) {
				return VV_URI . '/assets/images/' . $candidate . '.' . $ext;
			}
		}
	}

	return VV_URI . '/assets/images/' . sanitize_file_name( $slug ) . '.svg';
}

/**
 * Resolve a Customizer image setting to a URL, falling back to a placeholder.
 *
 * @param string $mod      Theme mod key.
 * @param string $fallback Placeholder slug.
 * @param string $size     Registered image size.
 */
function vv_image_url( $mod, $fallback, $size = 'vv-hero', $fallback_alt = '' ) {
	$value = get_theme_mod( $mod );

	if ( is_numeric( $value ) ) {
		$src = wp_get_attachment_image_src( (int) $value, $size );
		if ( $src ) {
			return $src[0];
		}
	}
	if ( is_string( $value ) && $value ) {
		return $value;
	}

	return vv_placeholder_url( $fallback, $fallback_alt );
}

/**
 * Cart count used by the floating pill (safe when WooCommerce is inactive).
 */
function vv_cart_count() {
	if ( ! vv_is_woocommerce_active() || is_null( WC()->cart ) ) {
		return 0;
	}
	return (int) WC()->cart->get_cart_contents_count();
}

/**
 * Floating "My Cart (0)" pill, bottom-right of every page.
 */
function vv_cart_pill() {
	$url = vv_is_woocommerce_active() ? wc_get_cart_url() : home_url( '/cart/' );
	?>
	<a class="vv-cart-pill" href="<?php echo esc_url( $url ); ?>" data-vv-cart-toggle>
		<span class="vv-cart-pill__icon"><?php vv_the_icon( 'bag', 18 ); ?></span>
		<span class="vv-cart-pill__label">
			<?php esc_html_e( 'My Cart', 'vastra-veda' ); ?>
			(<span class="vv-cart-count"><?php echo esc_html( vv_cart_count() ); ?></span>)
		</span>
	</a>
	<?php
}

/**
 * Section eyebrow + editorial heading + optional link, used by every home section.
 *
 * @param array $args heading, link_url, link_text, rule (bool), align.
 */
function vv_section_head( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'heading'   => '',
			'link_url'  => '',
			'link_text' => '',
			'rule'      => true,
			'class'     => '',
		)
	);

	if ( ! $args['heading'] ) {
		return;
	}
	?>
	<div class="vv-section-head <?php echo esc_attr( $args['class'] ); ?>">
		<div class="vv-section-head__row">
			<h2 class="vv-display vv-section-head__title"><?php vv_the_headline( $args['heading'] ); ?></h2>
			<?php if ( $args['link_url'] && $args['link_text'] ) : ?>
				<a class="vv-section-head__link" href="<?php echo esc_url( $args['link_url'] ); ?>">
					<?php echo esc_html( $args['link_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php if ( $args['rule'] ) : ?>
			<span class="vv-rule" aria-hidden="true"></span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Post/page title area for interior pages.
 */
function vv_page_hero( $title, $subtitle = '' ) {
	?>
	<header class="vv-page-hero">
		<div class="vv-shell">
			<h1 class="vv-display vv-page-hero__title"><?php vv_the_headline( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="vv-page-hero__sub"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</header>
	<?php
}

/**
 * Footer link list — the assigned menu if there is one, otherwise a sensible
 * default so a fresh install still matches the design.
 *
 * @param string $location Registered nav-menu location.
 * @param array  $fallback label => url.
 */
function vv_footer_menu( $location, $fallback = array() ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => 'vv-footer__list',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}

	if ( ! $fallback ) {
		return;
	}

	echo '<ul class="vv-footer__list">';
	foreach ( $fallback as $label => $url ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Social profiles set in the Customizer, in display order.
 *
 * @return array of [ icon, label, url ]
 */
function vv_social_links() {
	$networks = array(
		'facebook'  => __( 'Facebook', 'vastra-veda' ),
		'instagram' => __( 'Instagram', 'vastra-veda' ),
		'youtube'   => __( 'YouTube', 'vastra-veda' ),
		'x'         => __( 'X', 'vastra-veda' ),
		'pinterest' => __( 'Pinterest', 'vastra-veda' ),
		'whatsapp'  => __( 'WhatsApp', 'vastra-veda' ),
	);

	$defaults = array( 'facebook', 'instagram', 'youtube', 'x' );
	$out      = array();

	foreach ( $networks as $key => $label ) {
		$url = get_theme_mod( 'vv_social_' . $key, in_array( $key, $defaults, true ) ? '#' : '' );
		if ( ! $url ) {
			continue;
		}
		$out[] = array(
			'icon'  => $key,
			'label' => $label,
			'url'   => $url,
		);
	}

	return $out;
}

/* -------------------------------------------------------------------------
 * Slide-out drawer helpers
 * ---------------------------------------------------------------------- */

/**
 * 1 => I, 4 => IV, 9 => IX …  (menus never get near the upper bound)
 */
function vv_roman( $number ) {
	$number = (int) $number;
	if ( $number < 1 || $number > 3999 ) {
		return (string) $number;
	}

	$map = array(
		'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
		'C' => 100,  'XC' => 90,  'L' => 50,  'XL' => 40,
		'X' => 10,   'IX' => 9,   'V' => 5,   'IV' => 4,
		'I' => 1,
	);

	$out = '';
	foreach ( $map as $glyph => $value ) {
		while ( $number >= $value ) {
			$out    .= $glyph;
			$number -= $value;
		}
	}

	return $out;
}

/**
 * Wishlist size. No wishlist ships with the theme — a plugin (or your own
 * code) can hook this filter to supply the real number.
 */
function vv_wishlist_count() {
	return (int) apply_filters( 'vv_wishlist_count', 0 );
}

function vv_is_cart_url( $url ) {
	if ( ! $url || ! vv_is_woocommerce_active() ) {
		return false;
	}
	return untrailingslashit( $url ) === untrailingslashit( wc_get_cart_url() );
}

function vv_is_wishlist_url( $url ) {
	if ( ! $url ) {
		return false;
	}
	return (bool) preg_match( '#/wishlist/?($|\?)#i', $url );
}

/**
 * Drawer items used when no menu is assigned to the "Slide-out menu" location,
 * so a fresh install already matches the design.
 *
 * @return array of [ label, url, count|null ]
 */
function vv_drawer_fallback_items() {
	$account  = vv_is_woocommerce_active() ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
	$cart     = vv_is_woocommerce_active() ? wc_get_cart_url() : home_url( '/cart/' );

	$shop = vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

	return array(
		array( 'label' => __( 'Sarees', 'vastra-veda' ),       'url' => $shop,                      'count' => null ),
		array( 'label' => __( 'New Arrivals', 'vastra-veda' ), 'url' => add_query_arg( 'orderby', 'date', $shop ), 'count' => null ),
		array( 'label' => __( 'Profile', 'vastra-veda' ),      'url' => $account,                   'count' => null ),
		array( 'label' => __( 'Wishlist', 'vastra-veda' ),     'url' => home_url( '/wishlist/' ),   'count' => vv_wishlist_count() ),
		array( 'label' => __( 'Cart', 'vastra-veda' ),         'url' => $cart,                      'count' => vv_cart_count() ),
		array( 'label' => __( 'Our Story', 'vastra-veda' ),    'url' => home_url( '/our-story/' ),  'count' => null ),
		array( 'label' => __( 'Contact Us', 'vastra-veda' ),   'url' => home_url( '/contact-us/' ), 'count' => null ),
	);
}

/**
 * Estimated reading time for a post, in whole minutes (min 1).
 */
function vv_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );

	return max( 1, (int) round( $words / 200 ) );
}

/**
 * A post's first category name, for the card eyebrow.
 */
function vv_post_eyebrow( $post_id = null ) {
	$terms = get_the_category( $post_id ? $post_id : get_the_ID() );
	if ( $terms && ! is_wp_error( $terms ) ) {
		return $terms[0]->name;
	}
	return __( 'Journal', 'vastra-veda' );
}

/**
 * Social sign-in buttons under the auth forms.
 *
 * These are off until you wire a social-login plugin and paste its endpoint
 * URLs into Customize → Account. Dead buttons are worse than none, so nothing
 * renders when the URLs are empty.
 */
function vv_auth_social() {
	$providers = array(
		'google' => array( __( 'Google', 'vastra-veda' ), get_theme_mod( 'vv_account_google_url' ) ),
		'apple'  => array( __( 'Apple', 'vastra-veda' ),  get_theme_mod( 'vv_account_apple_url' ) ),
	);
	$providers = array_filter( $providers, function ( $p ) { return ! empty( $p[1] ); } );

	if ( ! $providers ) {
		return;
	}
	?>
	<div class="vv-auth__or"><span><?php esc_html_e( 'Or continue with', 'vastra-veda' ); ?></span></div>
	<div class="vv-auth__social">
		<?php foreach ( $providers as $key => $p ) : ?>
			<a class="vv-auth__social-btn vv-auth__social-btn--<?php echo esc_attr( $key ); ?>"
				href="<?php echo esc_url( $p[1] ); ?>" rel="nofollow">
				<?php echo esc_html( $p[0] ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Icon for each WooCommerce account menu endpoint.
 */
function vv_account_icon( $endpoint ) {
	$map = array(
		'dashboard'       => 'user',
		'orders'          => 'box',
		'downloads'       => 'box',
		'edit-address'    => 'pin',
		'edit-account'    => 'user',
		'payment-methods' => 'box',
		'customer-logout' => 'logout',
		'wishlist'        => 'heart',
	);

	return isset( $map[ $endpoint ] ) ? $map[ $endpoint ] : 'arrow';
}
