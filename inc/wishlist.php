<?php
/**
 * A small, self-contained wishlist.
 *
 * No plugin required. Signed-in customers keep their list in user meta;
 * guests keep it in a cookie, and it is merged into their account on login.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

const VV_WISHLIST_META   = '_vv_wishlist';
const VV_WISHLIST_COOKIE = 'vv_wishlist';
const VV_WISHLIST_DAYS   = 60;

/* -------------------------------------------------------------------------
 * Storage
 * ---------------------------------------------------------------------- */

/**
 * @return int[] Product IDs, newest first.
 */
function vv_wishlist_items() {
	if ( is_user_logged_in() ) {
		$ids = get_user_meta( get_current_user_id(), VV_WISHLIST_META, true );
	} elseif ( isset( $_COOKIE[ VV_WISHLIST_COOKIE ] ) ) {
		$ids = explode( ',', sanitize_text_field( wp_unslash( $_COOKIE[ VV_WISHLIST_COOKIE ] ) ) );
	} else {
		$ids = array();
	}

	$ids = array_filter( array_map( 'absint', (array) $ids ) );

	return array_values( array_unique( $ids ) );
}

function vv_wishlist_save( array $ids ) {
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );

	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), VV_WISHLIST_META, $ids );
		return;
	}

	$value = implode( ',', $ids );

	if ( function_exists( 'wc_setcookie' ) ) {
		wc_setcookie( VV_WISHLIST_COOKIE, $value, time() + ( VV_WISHLIST_DAYS * DAY_IN_SECONDS ) );
		return;
	}

	if ( ! headers_sent() ) {
		setcookie( VV_WISHLIST_COOKIE, $value, time() + ( VV_WISHLIST_DAYS * DAY_IN_SECONDS ), COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
	}
	$_COOKIE[ VV_WISHLIST_COOKIE ] = $value;
}

function vv_wishlist_has( $product_id ) {
	return in_array( (int) $product_id, vv_wishlist_items(), true );
}

/**
 * Feed the count used by the drawer and the account dashboard.
 */
add_filter( 'vv_wishlist_count', function () {
	return count( vv_wishlist_items() );
} );

/**
 * Carry a guest list into the account on login.
 */
add_action( 'wp_login', function ( $login, $user ) {
	if ( empty( $_COOKIE[ VV_WISHLIST_COOKIE ] ) ) {
		return;
	}

	$guest = array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_COOKIE[ VV_WISHLIST_COOKIE ] ) ) ) ) );
	$saved = (array) get_user_meta( $user->ID, VV_WISHLIST_META, true );

	update_user_meta( $user->ID, VV_WISHLIST_META, array_values( array_unique( array_merge( $saved, $guest ) ) ) );

	if ( ! headers_sent() ) {
		setcookie( VV_WISHLIST_COOKIE, '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
	}
}, 10, 2 );

/* -------------------------------------------------------------------------
 * AJAX toggle
 * ---------------------------------------------------------------------- */

function vv_wishlist_ajax() {
	check_ajax_referer( 'vv-wishlist', 'nonce' );

	$id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	if ( ! $id || 'product' !== get_post_type( $id ) ) {
		wp_send_json_error( array( 'message' => __( 'Unknown product.', 'vastra-veda' ) ), 400 );
	}

	$ids = vv_wishlist_items();
	$in  = in_array( $id, $ids, true );

	if ( $in ) {
		$ids = array_diff( $ids, array( $id ) );
	} else {
		array_unshift( $ids, $id );
	}

	vv_wishlist_save( $ids );

	wp_send_json_success( array(
		'added' => ! $in,
		'count' => count( $ids ),
	) );
}
add_action( 'wp_ajax_vv_wishlist', 'vv_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_vv_wishlist', 'vv_wishlist_ajax' );

/* -------------------------------------------------------------------------
 * Button
 * ---------------------------------------------------------------------- */

/**
 * Heart toggle. Echoes nothing when WooCommerce is inactive.
 *
 * @param int    $product_id Product.
 * @param string $style      'icon' (card overlay) or 'inline' (product page).
 */
function vv_wishlist_button( $product_id = 0, $style = 'icon' ) {
	if ( ! vv_is_woocommerce_active() ) {
		return;
	}

	$product_id = $product_id ? (int) $product_id : get_the_ID();
	$active     = vv_wishlist_has( $product_id );
	?>
	<button type="button"
		class="vv-wish vv-wish--<?php echo esc_attr( $style ); ?><?php echo $active ? ' is-on' : ''; ?>"
		data-vv-wish="<?php echo esc_attr( $product_id ); ?>"
		aria-pressed="<?php echo $active ? 'true' : 'false'; ?>">
		<span class="vv-wish__icon"><?php vv_the_icon( 'heart', 'inline' === $style ? 18 : 16 ); ?></span>
		<?php if ( 'inline' === $style ) : ?>
			<span class="vv-wish__label">
				<?php echo esc_html( $active ? __( 'Saved', 'vastra-veda' ) : __( 'Add to wishlist', 'vastra-veda' ) ); ?>
			</span>
		<?php else : ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Add to wishlist', 'vastra-veda' ); ?></span>
		<?php endif; ?>
	</button>
	<?php
}

/**
 * The page holding [vastra_wishlist] or the Wishlist template.
 */
function vv_wishlist_page_url() {
	$page = get_page_by_path( 'wishlist' );

	return $page ? get_permalink( $page ) : home_url( '/wishlist/' );
}
