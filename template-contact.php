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

	<?php
	$vv_routes = array();
	$vv_route_defaults = array(
		1 => array( __( 'Orders & delivery', 'vastra-veda' ), __( 'Where is my parcel, can I change an address, can I exchange a size.', 'vastra-veda' ), 'orders@example.com' ),
		2 => array( __( 'Wholesale & stockists', 'vastra-veda' ), __( 'Bulk enquiries, look books, and terms for boutiques and studios.', 'vastra-veda' ), 'trade@example.com' ),
		3 => array( __( 'Press & collaboration', 'vastra-veda' ), __( 'Shoots, features, and working together on a collection.', 'vastra-veda' ), 'studio@example.com' ),
	);
	for ( $vv_i = 1; $vv_i <= 3; $vv_i++ ) {
		$vv_t = get_theme_mod( "vv_contact_r{$vv_i}_title", $vv_route_defaults[ $vv_i ][0] );
		$vv_d = get_theme_mod( "vv_contact_r{$vv_i}_text",  $vv_route_defaults[ $vv_i ][1] );
		$vv_e = get_theme_mod( "vv_contact_r{$vv_i}_email", $vv_route_defaults[ $vv_i ][2] );
		if ( $vv_t || $vv_e ) {
			$vv_routes[] = array( $vv_t, $vv_d, $vv_e );
		}
	}
	?>
	<?php if ( $vv_routes ) : ?>
		<section class="vv-routes">
			<div class="vv-shell vv-routes__grid">
				<?php foreach ( $vv_routes as $vv_n => $vv_route ) : ?>
					<div class="vv-route">
						<span class="vv-route__num"><?php echo esc_html( vv_roman( $vv_n + 1 ) ); ?></span>
						<h2 class="vv-route__title"><?php echo esc_html( $vv_route[0] ); ?></h2>
						<?php if ( $vv_route[1] ) : ?>
							<p class="vv-route__text"><?php echo esc_html( $vv_route[1] ); ?></p>
						<?php endif; ?>
						<?php if ( $vv_route[2] ) : ?>
							<a class="vv-route__mail" href="mailto:<?php echo esc_attr( $vv_route[2] ); ?>">
								<?php echo esc_html( $vv_route[2] ); ?><?php vv_the_icon( 'arrow', 14 ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

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

	<?php
	$vv_faqs = array();
	$vv_faq_defaults = array(
		1 => array( __( 'How long does delivery take?', 'vastra-veda' ), __( 'Within India, three to five working days once the piece has been checked and packed. International orders take seven to twelve, and we send the tracking number the moment it leaves us.', 'vastra-veda' ) ),
		2 => array( __( 'Can I return or exchange a saree?', 'vastra-veda' ), __( 'Yes, within seven days of delivery, unworn and with the tags on. Handloom pieces carry small irregularities from the loom — those are part of the weave rather than a fault, so they are not grounds for return.', 'vastra-veda' ) ),
		3 => array( __( 'Do you ship outside India?', 'vastra-veda' ), __( 'We do. Duties and taxes in the destination country are payable by you, and are not included at checkout.', 'vastra-veda' ) ),
		4 => array( __( 'How should I care for a handloom saree?', 'vastra-veda' ), __( 'Dry clean the first two or three times, then hand wash cold if the weave allows it. Store folded in cotton, never plastic, and refold along a different line every few months so the zari does not crease in one place.', 'vastra-veda' ) ),
	);
	for ( $vv_i = 1; $vv_i <= 4; $vv_i++ ) {
		$vv_q = get_theme_mod( "vv_contact_q{$vv_i}", $vv_faq_defaults[ $vv_i ][0] );
		$vv_a = get_theme_mod( "vv_contact_a{$vv_i}", $vv_faq_defaults[ $vv_i ][1] );
		if ( $vv_q && $vv_a ) {
			$vv_faqs[] = array( $vv_q, $vv_a );
		}
	}
	?>
	<?php if ( $vv_faqs ) : ?>
		<div class="vv-shell vv-faq">
			<div class="vv-faq__head">
				<h2 class="vv-display vv-faq__title"><?php vv_the_headline( get_theme_mod( 'vv_contact_faq_heading', 'BEFORE YOU *write*' ) ); ?></h2>
				<?php $vv_faq_page = get_page_by_path( 'faqs' ); ?>
				<?php if ( $vv_faq_page ) : ?>
					<a class="vv-section-head__link" href="<?php echo esc_url( get_permalink( $vv_faq_page ) ); ?>">
						<?php esc_html_e( 'All FAQs', 'vastra-veda' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="vv-faq__list">
				<?php foreach ( $vv_faqs as $vv_n => $vv_faq ) : ?>
					<details class="vv-faq__item"<?php echo 0 === $vv_n ? ' open' : ''; ?>>
						<summary>
							<span class="vv-faq__q"><?php echo esc_html( $vv_faq[0] ); ?></span>
							<span class="vv-faq__sign" aria-hidden="true"></span>
						</summary>
						<div class="vv-faq__a"><p><?php echo esc_html( $vv_faq[1] ); ?></p></div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

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
