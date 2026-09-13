<?php
/**
 * Edit account ("Profile").
 * Override of woocommerce/templates/myaccount/form-edit-account.php
 *
 * @package VastraVeda
 * @version 9.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<header class="vv-account__head">
	<span class="vv-eyebrow"><?php esc_html_e( 'Your details', 'vastra-veda' ); ?></span>
	<h2 class="vv-account__hello"><?php esc_html_e( 'Profile', 'vastra-veda' ); ?></h2>
	<p class="vv-account__sub"><?php esc_html_e( 'How we address you, where we write to you, and the password that keeps it yours.', 'vastra-veda' ); ?></p>
</header>

<form class="woocommerce-EditAccountForm edit-account vv-form vv-profile" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="vv-form__row">
		<p class="vv-field">
			<label for="account_first_name"><?php esc_html_e( 'First name', 'vastra-veda' ); ?></label>
			<input type="text" class="vv-input" name="account_first_name" id="account_first_name"
				autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>
		<p class="vv-field">
			<label for="account_last_name"><?php esc_html_e( 'Last name', 'vastra-veda' ); ?></label>
			<input type="text" class="vv-input" name="account_last_name" id="account_last_name"
				autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</p>
	</div>

	<p class="vv-field">
		<label for="account_display_name"><?php esc_html_e( 'Display name', 'vastra-veda' ); ?></label>
		<input type="text" class="vv-input" name="account_display_name" id="account_display_name"
			value="<?php echo esc_attr( $user->display_name ); ?>" />
		<em class="vv-field__hint"><?php esc_html_e( 'This is how your name appears on orders and reviews.', 'vastra-veda' ); ?></em>
	</p>

	<p class="vv-field">
		<label for="account_email"><?php esc_html_e( 'Email address', 'vastra-veda' ); ?></label>
		<input type="email" class="vv-input" name="account_email" id="account_email"
			autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

	<fieldset class="vv-profile__pw">
		<legend><?php esc_html_e( 'Password change', 'vastra-veda' ); ?></legend>

		<p class="vv-field">
			<label for="password_current"><?php esc_html_e( 'Current password', 'vastra-veda' ); ?></label>
			<span class="vv-input-wrap">
				<input type="password" class="vv-input" name="password_current" id="password_current" autocomplete="off" />
				<button type="button" class="vv-reveal" data-vv-reveal aria-label="<?php esc_attr_e( 'Show password', 'vastra-veda' ); ?>"><?php vv_the_icon( 'eye', 18 ); ?></button>
			</span>
			<em class="vv-field__hint"><?php esc_html_e( 'Leave the password fields empty to keep the one you have.', 'vastra-veda' ); ?></em>
		</p>

		<div class="vv-form__row">
			<p class="vv-field">
				<label for="password_1"><?php esc_html_e( 'New password', 'vastra-veda' ); ?></label>
				<span class="vv-input-wrap">
					<input type="password" class="vv-input" name="password_1" id="password_1" autocomplete="off" />
					<button type="button" class="vv-reveal" data-vv-reveal aria-label="<?php esc_attr_e( 'Show password', 'vastra-veda' ); ?>"><?php vv_the_icon( 'eye', 18 ); ?></button>
				</span>
			</p>
			<p class="vv-field">
				<label for="password_2"><?php esc_html_e( 'Confirm new password', 'vastra-veda' ); ?></label>
				<input type="password" class="vv-input" name="password_2" id="password_2" autocomplete="off" />
			</p>
		</div>
	</fieldset>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
	<button type="submit" class="vv-btn-block woocommerce-Button button" name="save_account_details"
		value="<?php esc_attr_e( 'Save changes', 'vastra-veda' ); ?>">
		<?php esc_html_e( 'Save changes', 'vastra-veda' ); ?>
	</button>
	<input type="hidden" name="action" value="save_account_details" />

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
