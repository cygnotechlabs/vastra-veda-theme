<?php
/**
 * WooCommerce integration.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Category data used by the "Shop by category" carousel, the search overlay
 * and the slide-out menu. Falls back to demo entries so the homepage looks
 * right on a brand-new install with no products yet.
 * ---------------------------------------------------------------------- */

function vv_get_category_terms( $limit = 8 ) {
	$out = array();

	if ( vv_is_woocommerce_active() ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => (int) $limit,
				'parent'     => 0,
				'orderby'    => 'menu_order',
				'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
			)
		);

		if ( ! is_wp_error( $terms ) && $terms ) {
			$i = 0;
			foreach ( $terms as $term ) {
				$i++;
				$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				$img      = $thumb_id ? wp_get_attachment_image_url( (int) $thumb_id, 'vv-portrait' ) : '';

				$out[] = array(
					'name'  => $term->name,
					'url'   => get_term_link( $term ),
					'image' => $img ? $img : vv_placeholder_url( 'cat-' . ( ( ( $i - 1 ) % 6 ) + 1 ) ),
					'count' => (int) $term->count,
				);
			}
			return $out;
		}
	}

	/* Demo fallback — replace by creating product categories in WooCommerce. */
	$demo = array( 'Kanjivaram', 'Banarasi', 'Organza', 'Chiffon', 'Tussar Silk', 'Linen' );
	$demo = array_slice( $demo, 0, (int) $limit );

	foreach ( $demo as $i => $name ) {
		$out[] = array(
			'name'  => $name,
			'url'   => vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ),
			'image' => vv_placeholder_url( 'cat-' . ( ( $i % 6 ) + 1 ) ),
			'count' => 0,
		);
	}

	return $out;
}

/* -------------------------------------------------------------------------
 * Everything below only runs when WooCommerce is active.
 * ---------------------------------------------------------------------- */

if ( ! vv_is_woocommerce_active() ) {
	return;
}

/* Strip the default wrappers and rebuild them to match the theme shell. */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

function vv_woo_wrapper_start() {
	echo '<div class="vv-woo-wrap"><div class="vv-shell">';
}
add_action( 'woocommerce_before_main_content', 'vv_woo_wrapper_start', 10 );

function vv_woo_wrapper_end() {
	echo '</div></div>';
}
add_action( 'woocommerce_after_main_content', 'vv_woo_wrapper_end', 10 );

/* Archive header — editorial title block instead of the default page title. */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_before_main_content', 'vv_woo_archive_header', 6 );
function vv_woo_archive_header() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}
	$title = is_shop() ? woocommerce_page_title( false ) : single_term_title( '', false );
	?>
	<header class="vv-page-hero vv-page-hero--shop">
		<div class="vv-shell">
			<h1 class="vv-display vv-page-hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php
			$desc = is_product_taxonomy() ? term_description() : '';
			if ( $desc ) {
				echo '<div class="vv-page-hero__sub">' . wp_kses_post( $desc ) . '</div>';
			}
			?>
			<span class="vv-rule" aria-hidden="true"></span>
		</div>
	</header>
	<?php
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 5 );

/* Loop settings. */
add_filter( 'loop_shop_columns', function () { return 4; }, 20 );
add_filter( 'loop_shop_per_page', function () { return 12; }, 20 );

/* Rebuild the product card: image wrapper, hover second image, quiet meta. */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 14 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action( 'woocommerce_before_shop_loop_item_title', 'vv_loop_media', 10 );
function vv_loop_media() {
	global $product;
	if ( ! $product ) {
		return;
	}

	$gallery = $product->get_gallery_image_ids();
	$hover   = ! empty( $gallery ) ? wp_get_attachment_image_url( (int) $gallery[0], 'vv-product' ) : '';

	echo '<div class="vv-card__media">';
	echo woocommerce_get_product_thumbnail( 'vv-product' ); // phpcs:ignore WordPress.Security.EscapeOutput

	if ( $hover ) {
		printf(
			'<img class="vv-card__hover" src="%s" alt="" loading="lazy" decoding="async" />',
			esc_url( $hover )
		);
	}

	if ( $product->is_on_sale() ) {
		echo '<span class="vv-badge">' . esc_html__( 'Sale', 'vastra-veda' ) . '</span>';
	} elseif ( vv_product_is_new( $product ) ) {
		echo '<span class="vv-badge vv-badge--new">' . esc_html__( 'New', 'vastra-veda' ) . '</span>';
	}

	echo '<span class="vv-card__quick">' . esc_html__( 'View', 'vastra-veda' ) . '</span>';

	if ( function_exists( 'vv_wishlist_button' ) ) {
		vv_wishlist_button( $product->get_id(), 'icon' );
	}

	echo '</div>';
}

add_action( 'woocommerce_shop_loop_item_title', 'vv_loop_title', 10 );
function vv_loop_title() {
	global $product;
	$terms = get_the_terms( get_the_ID(), 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		echo '<span class="vv-card__cat">' . esc_html( $terms[0]->name ) . '</span>';
	}
	echo '<h3 class="vv-card__title">' . esc_html( get_the_title() ) . '</h3>';
}

/**
 * Product published within the last 30 days.
 */
function vv_product_is_new( $product ) {
	$created = $product->get_date_created();
	if ( ! $created ) {
		return false;
	}
	return ( time() - $created->getTimestamp() ) < ( 30 * DAY_IN_SECONDS );
}

/* Sale flash / price markup tweaks. */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

/* Single product layout tweaks. */
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 65 );

/* Related products: 4 across, single row. */
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
} );

/* Floating cart pill count refresh. */
add_filter( 'woocommerce_add_to_cart_fragments', 'vv_cart_fragment' );
function vv_cart_fragment( $fragments ) {
	ob_start();
	echo '<span class="vv-cart-count">' . esc_html( vv_cart_count() ) . '</span>';
	$fragments['span.vv-cart-count'] = ob_get_clean();
	return $fragments;
}

/* Loop add-to-cart styling. */
add_filter( 'woocommerce_loop_add_to_cart_args', function ( $args ) {
	$args['class'] = trim( ( isset( $args['class'] ) ? $args['class'] : '' ) . ' vv-card__cart' );
	return $args;
}, 10, 1 );

/* Quantity input: keep it simple and themable. */
add_filter( 'woocommerce_quantity_input_classes', function ( $classes ) {
	$classes[] = 'vv-qty__input';
	return $classes;
} );

/* Tell Woo this theme handles HPOS-compatible templates fine. */
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
