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
		3 => "HEIRLOOM *silks*\nFOR EVERY\nNEW *beginning*",
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
function vv_placeholder_url( $slug ) {
	$slug = sanitize_file_name( $slug );

	foreach ( array( 'jpg', 'jpeg', 'png', 'webp', 'avif', 'svg' ) as $ext ) {
		if ( file_exists( VV_DIR . '/assets/images/' . $slug . '.' . $ext ) ) {
			return VV_URI . '/assets/images/' . $slug . '.' . $ext;
		}
	}

	return VV_URI . '/assets/images/' . $slug . '.svg';
}

/**
 * Resolve a Customizer image setting to a URL, falling back to a placeholder.
 *
 * @param string $mod      Theme mod key.
 * @param string $fallback Placeholder slug.
 * @param string $size     Registered image size.
 */
function vv_image_url( $mod, $fallback, $size = 'vv-hero' ) {
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

	return vv_placeholder_url( $fallback );
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
