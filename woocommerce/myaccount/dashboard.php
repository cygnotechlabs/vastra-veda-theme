<?php
/**
 * Account dashboard.
 * Override of woocommerce/templates/myaccount/dashboard.php
 *
 * @package VastraVeda
 * @version 9.1.0
 */

defined( 'ABSPATH' ) || exit;

$vv_user   = wp_get_current_user();
$vv_orders = wc_get_orders( array(
	'customer' => get_current_user_id(),
	'limit'    => 3,
	'orderby'  => 'date',
	'order'    => 'DESC',
) );

$vv_order_count = wc_get_customer_order_count( get_current_user_id() );
?>

<header class="vv-account__head">
	<span class="vv-eyebrow"><?php esc_html_e( 'Your account', 'vastra-veda' ); ?></span>
	<h2 class="vv-account__hello">
		<?php
		printf(
			/* translators: %s: customer first name */
			esc_html__( 'Hello, %s', 'vastra-veda' ),
			esc_html( $vv_user->first_name ? $vv_user->first_name : $vv_user->display_name )
		);
		?>
	</h2>
	<p class="vv-account__sub"><?php esc_html_e( 'Track an order, update your addresses, or pick up where you left off.', 'vastra-veda' ); ?></p>
</header>

<div class="vv-account__stats">
	<a class="vv-stat" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
		<span class="vv-stat__num"><?php echo esc_html( number_format_i18n( $vv_order_count ) ); ?></span>
		<span class="vv-stat__label"><?php esc_html_e( 'Orders placed', 'vastra-veda' ); ?></span>
	</a>
	<a class="vv-stat" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>">
		<span class="vv-stat__num"><?php echo esc_html( number_format_i18n( vv_wishlist_count() ) ); ?></span>
		<span class="vv-stat__label"><?php esc_html_e( 'Saved pieces', 'vastra-veda' ); ?></span>
	</a>
	<a class="vv-stat" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>">
		<span class="vv-stat__num"><?php vv_the_icon( 'pin', 22 ); ?></span>
		<span class="vv-stat__label"><?php esc_html_e( 'Addresses', 'vastra-veda' ); ?></span>
	</a>
</div>

<?php if ( $vv_orders ) : ?>
	<section class="vv-account__block">
		<div class="vv-account__block-head">
			<h3><?php esc_html_e( 'Recent orders', 'vastra-veda' ); ?></h3>
			<a class="vv-link-arrow" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
				<?php esc_html_e( 'All orders', 'vastra-veda' ); ?><?php vv_the_icon( 'arrow', 15 ); ?>
			</a>
		</div>

		<ul class="vv-orderlist">
			<?php foreach ( $vv_orders as $vv_order ) : ?>
				<li class="vv-orderlist__row">
					<a href="<?php echo esc_url( $vv_order->get_view_order_url() ); ?>">
						<span class="vv-orderlist__id">#<?php echo esc_html( $vv_order->get_order_number() ); ?></span>
						<span class="vv-orderlist__date"><?php echo esc_html( wc_format_datetime( $vv_order->get_date_created() ) ); ?></span>
						<span class="vv-orderlist__status vv-orderlist__status--<?php echo esc_attr( $vv_order->get_status() ); ?>">
							<?php echo esc_html( wc_get_order_status_name( $vv_order->get_status() ) ); ?>
						</span>
						<span class="vv-orderlist__total"><?php echo wp_kses_post( $vv_order->get_formatted_order_total() ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
<?php else : ?>
	<section class="vv-account__block vv-account__empty">
		<p><?php esc_html_e( 'No orders yet. When you place one, it will appear here with its progress.', 'vastra-veda' ); ?></p>
		<a class="vv-btn vv-btn--green" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
			<?php esc_html_e( 'Start browsing', 'vastra-veda' ); ?>
		</a>
	</section>
<?php endif; ?>

<?php
	/**
	 * Keep WooCommerce's own dashboard hook so plugins still render.
	 */
	do_action( 'woocommerce_account_dashboard' );
