<?php
/**
 * Template Name: Contact
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

get_header();

$vv_rows = array(
	'address' => array( 'pin',   __( 'Studio', 'vastra-veda' ),  get_theme_mod( 'vv_contact_address', "Vastra Veda\nAngamaly, Kerala 683572\nIndia" ) ),
	'phone'   => array( 'arrow', __( 'Phone', 'vastra-veda' ),   get_theme_mod( 'vv_contact_phone', '+91 00000 00000' ) ),
	'email'   => array( 'arrow', __( 'Email', 'vastra-veda' ),   get_theme_mod( 'vv_contact_email', get_option( 'admin_email' ) ) ),
	'hours'   => array( 'arrow', __( 'Hours', 'vastra-veda' ),   get_theme_mod( 'vv_contact_hours', __( "Monday to Saturday\n10am – 7pm IST", 'vastra-veda' ) ) ),
);
?>

<?php while ( have_posts() ) : the_post(); ?>

	<header class="vv-page-hero">
		<div class="vv-shell">
			<span class="vv-eyebrow"><?php esc_html_e( 'We would love to hear from you', 'vastra-veda' ); ?></span>
			<h1 class="vv-display vv-page-hero__title"><?php vv_the_headline( get_the_title() ); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="vv-page-hero__sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<span class="vv-rule" aria-hidden="true"></span>
		</div>
	</header>

	<section class="vv-section vv-contact">
		<div class="vv-shell vv-contact__grid">

			<aside class="vv-contact__aside">
				<?php foreach ( $vv_rows as $vv_key => $vv_row ) : ?>
					<?php if ( ! $vv_row[2] ) { continue; } ?>
					<div class="vv-contact__row">
						<span class="vv-eyebrow"><?php echo esc_html( $vv_row[1] ); ?></span>
						<p>
							<?php
							if ( 'email' === $vv_key ) {
								printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $vv_row[2] ) );
							} elseif ( 'phone' === $vv_key ) {
								printf( '<a href="tel:%1$s">%2$s</a>', esc_attr( preg_replace( '/[^\d+]/', '', $vv_row[2] ) ), esc_html( $vv_row[2] ) );
							} else {
								echo nl2br( esc_html( $vv_row[2] ) );
							}
							?>
						</p>
					</div>
				<?php endforeach; ?>

				<?php $vv_socials = vv_social_links(); ?>
				<?php if ( $vv_socials ) : ?>
					<div class="vv-contact__row">
						<span class="vv-eyebrow"><?php esc_html_e( 'Elsewhere', 'vastra-veda' ); ?></span>
						<ul class="vv-socials vv-socials--dark">
							<?php foreach ( $vv_socials as $vv_s ) : ?>
								<li><a class="vv-socials__link" href="<?php echo esc_url( $vv_s['url'] ); ?>">
									<?php vv_the_icon( $vv_s['icon'], 16 ); ?>
									<span class="screen-reader-text"><?php echo esc_html( $vv_s['label'] ); ?></span>
								</a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</aside>

			<div class="vv-contact__main">

				<?php vv_contact_notice(); ?>

				<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
					<div class="vv-prose vv-contact__copy"><?php the_content(); ?></div>
				<?php endif; ?>

				<form class="vv-form vv-contact__form" method="post" action="">
					<input type="hidden" name="vv_contact" value="1">
					<?php wp_nonce_field( 'vv-contact', 'vv_contact_nonce' ); ?>
					<p class="vv-hp" aria-hidden="true">
						<label><?php esc_html_e( 'Leave this empty', 'vastra-veda' ); ?>
							<input type="text" name="vv_hp" tabindex="-1" autocomplete="off"></label>
					</p>

					<div class="vv-form__row">
						<p class="vv-field">
							<label for="vv_name"><?php esc_html_e( 'Your name', 'vastra-veda' ); ?></label>
							<input class="vv-input" type="text" id="vv_name" name="vv_name" required>
						</p>
						<p class="vv-field">
							<label for="vv_email"><?php esc_html_e( 'Email address', 'vastra-veda' ); ?></label>
							<input class="vv-input" type="email" id="vv_email" name="vv_email"
								placeholder="<?php esc_attr_e( 'you@example.com', 'vastra-veda' ); ?>" required>
						</p>
					</div>

					<p class="vv-field">
						<label for="vv_subject"><?php esc_html_e( 'Subject', 'vastra-veda' ); ?></label>
						<input class="vv-input" type="text" id="vv_subject" name="vv_subject"
							placeholder="<?php esc_attr_e( 'An order, a weave, a wholesale enquiry…', 'vastra-veda' ); ?>">
					</p>

					<p class="vv-field">
						<label for="vv_message"><?php esc_html_e( 'Message', 'vastra-veda' ); ?></label>
						<textarea class="vv-input vv-textarea" id="vv_message" name="vv_message" rows="6" required></textarea>
					</p>

					<button type="submit" class="vv-btn-block"><?php esc_html_e( 'Send message', 'vastra-veda' ); ?></button>
				</form>
			</div>
		</div>

		<?php $vv_map = get_theme_mod( 'vv_contact_map' ); ?>
		<?php if ( $vv_map ) : ?>
			<div class="vv-shell">
				<div class="vv-contact__map">
					<iframe src="<?php echo esc_url( $vv_map ); ?>" loading="lazy" title="<?php esc_attr_e( 'Map', 'vastra-veda' ); ?>"
						referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			</div>
		<?php endif; ?>
	</section>

<?php endwhile; ?>

<?php
get_footer();
