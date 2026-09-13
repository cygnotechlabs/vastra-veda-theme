<?php
/**
 * Login / register — split panel.
 * Override of woocommerce/templates/myaccount/form-login.php
 *
 * @package VastraVeda
 * @version 9.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$vv_can_register = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$vv_active       = ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) ? 'register' : 'login'; // phpcs:ignore WordPress.Security.NonceVerification
?>

<div class="vv-auth<?php echo $vv_can_register ? '' : ' vv-auth--login-only'; ?>" data-vv-auth>

	<aside class="vv-auth__aside">
		<img class="vv-auth__aside-img"
			src="<?php echo esc_url( vv_image_url( 'vv_account_image', 'account', 'vv-hero', 'cat-2' ) ); ?>"
			alt="" loading="eager" decoding="async">
		<span class="vv-auth__veil" aria-hidden="true"></span>
		<div class="vv-auth__quote">
			<p><?php echo esc_html( get_theme_mod( 'vv_account_quote', __( '“Every drape is a quiet inheritance, worn forward.”', 'vastra-veda' ) ) ); ?></p>
			<span class="vv-auth__stamp"><?php echo esc_html( get_theme_mod( 'vv_account_stamp', __( 'Vastra Veda · Est. Heritage', 'vastra-veda' ) ) ); ?></span>
		</div>
	</aside>

	<div class="vv-auth__panel">

		<p class="vv-auth__eyebrow">
			<?php
			echo esc_html(
				$vv_can_register
					? __( 'Sign in to your account or create a new one.', 'vastra-veda' )
					: __( 'Sign in to your account.', 'vastra-veda' )
			);
			?>
		</p>

		<h1 class="vv-auth__title"><?php echo esc_html( get_theme_mod( 'vv_account_title', __( 'Welcome', 'vastra-veda' ) ) ); ?></h1>

		<?php if ( $vv_can_register ) : ?>
			<div class="vv-auth__tabs" role="tablist">
				<button type="button" class="vv-auth__tab<?php echo 'login' === $vv_active ? ' is-active' : ''; ?>"
					role="tab" aria-controls="vv-tab-login"
					aria-selected="<?php echo 'login' === $vv_active ? 'true' : 'false'; ?>"
					data-vv-tab="login">
					<?php esc_html_e( 'Login', 'vastra-veda' ); ?>
				</button>
				<button type="button" class="vv-auth__tab<?php echo 'register' === $vv_active ? ' is-active' : ''; ?>"
					role="tab" aria-controls="vv-tab-register"
					aria-selected="<?php echo 'register' === $vv_active ? 'true' : 'false'; ?>"
					data-vv-tab="register">
					<?php esc_html_e( 'Register', 'vastra-veda' ); ?>
				</button>
				<span class="vv-auth__tab-ink" aria-hidden="true"></span>
			</div>
		<?php endif; ?>

		<!-- ------------------------------------------------------- login -->
		<div id="vv-tab-login" class="vv-auth__pane<?php echo 'login' === $vv_active ? ' is-active' : ''; ?>" data-vv-pane="login" role="tabpanel">
			<form class="woocommerce-form woocommerce-form-login login vv-form" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="vv-field">
					<label for="username"><?php esc_html_e( 'Email address', 'vastra-veda' ); ?></label>
					<input type="text" class="vv-input" name="username" id="username" autocomplete="username"
						placeholder="<?php esc_attr_e( 'you@example.com', 'vastra-veda' ); ?>"
						value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore ?>" />
				</p>

				<p class="vv-field">
					<label for="password"><?php esc_html_e( 'Password', 'vastra-veda' ); ?></label>
					<span class="vv-input-wrap">
						<input class="vv-input" type="password" name="password" id="password"
							autocomplete="current-password" placeholder="••••••••" />
						<button type="button" class="vv-reveal" data-vv-reveal aria-label="<?php esc_attr_e( 'Show password', 'vastra-veda' ); ?>">
							<?php vv_the_icon( 'eye', 18 ); ?>
						</button>
					</span>
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="vv-auth__row">
					<label class="vv-check">
						<input class="vv-check__box" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<span><?php esc_html_e( 'Remember me', 'vastra-veda' ); ?></span>
					</label>
					<a class="vv-auth__forgot" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
						<?php esc_html_e( 'Forgot password?', 'vastra-veda' ); ?>
					</a>
				</div>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

				<button type="submit" class="vv-btn-block woocommerce-button button woocommerce-form-login__submit"
					name="login" value="<?php esc_attr_e( 'Login', 'vastra-veda' ); ?>">
					<?php esc_html_e( 'Login', 'vastra-veda' ); ?>
				</button>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

			<?php vv_auth_social(); ?>
		</div>

		<!-- ---------------------------------------------------- register -->
		<?php if ( $vv_can_register ) : ?>
			<div id="vv-tab-register" class="vv-auth__pane<?php echo 'register' === $vv_active ? ' is-active' : ''; ?>" data-vv-pane="register" role="tabpanel">
				<form method="post" class="woocommerce-form woocommerce-form-register register vv-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
						<p class="vv-field">
							<label for="reg_username"><?php esc_html_e( 'Username', 'vastra-veda' ); ?></label>
							<input type="text" class="vv-input" name="username" id="reg_username" autocomplete="username"
								value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore ?>" />
						</p>
					<?php endif; ?>

					<p class="vv-field">
						<label for="reg_email"><?php esc_html_e( 'Email address', 'vastra-veda' ); ?></label>
						<input type="email" class="vv-input" name="email" id="reg_email" autocomplete="email"
							placeholder="<?php esc_attr_e( 'you@example.com', 'vastra-veda' ); ?>"
							value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; // phpcs:ignore ?>" />
					</p>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<p class="vv-field">
							<label for="reg_password"><?php esc_html_e( 'Password', 'vastra-veda' ); ?></label>
							<span class="vv-input-wrap">
								<input type="password" class="vv-input" name="password" id="reg_password"
									autocomplete="new-password" placeholder="••••••••" />
								<button type="button" class="vv-reveal" data-vv-reveal aria-label="<?php esc_attr_e( 'Show password', 'vastra-veda' ); ?>">
									<?php vv_the_icon( 'eye', 18 ); ?>
								</button>
							</span>
						</p>
					<?php else : ?>
						<p class="vv-auth__hint"><?php esc_html_e( 'A link to set your password will be sent to your email address.', 'vastra-veda' ); ?></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

					<button type="submit" class="vv-btn-block woocommerce-Button woocommerce-button button"
						name="register" value="<?php esc_attr_e( 'Create account', 'vastra-veda' ); ?>">
						<?php esc_html_e( 'Create account', 'vastra-veda' ); ?>
					</button>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>

				<?php vv_auth_social(); ?>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
