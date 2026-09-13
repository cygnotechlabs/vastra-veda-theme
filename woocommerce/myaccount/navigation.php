<?php
/**
 * Account navigation.
 * Override of woocommerce/templates/myaccount/navigation.php
 *
 * @package VastraVeda
 * @version 9.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );

$vv_user = wp_get_current_user();
?>
<nav class="woocommerce-MyAccount-navigation vv-account__nav" aria-label="<?php esc_attr_e( 'Account', 'vastra-veda' ); ?>">

	<div class="vv-account__whoami">
		<span class="vv-account__avatar"><?php echo get_avatar( $vv_user->ID, 96, '', '', array( 'class' => 'vv-account__avatar-img' ) ); ?></span>
		<span class="vv-account__name"><?php echo esc_html( $vv_user->display_name ); ?></span>
		<span class="vv-account__email"><?php echo esc_html( $vv_user->user_email ); ?></span>
	</div>

	<ul class="vv-account__list">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
					<?php vv_the_icon( vv_account_icon( $endpoint ), 17 ); ?>
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php
do_action( 'woocommerce_after_account_navigation' );
