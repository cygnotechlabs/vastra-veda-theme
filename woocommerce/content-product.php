<?php
/**
 * Product card in loops.
 * Override of woocommerce/templates/content-product.php
 *
 * @package VastraVeda
 * @version 9.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'vv-card', $product ); ?>>
	<?php
	/**
	 * Opens the product link wrapper.
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Media: thumbnail, hover image, badge.
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * Category eyebrow + title.
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * Price.
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * Closes the link wrapper and prints the add-to-cart action.
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
