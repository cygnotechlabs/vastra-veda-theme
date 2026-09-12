<?php
/**
 * Footer.
 *
 * Five columns: brand + three link menus + contact/social,
 * separated by hairline rules, over a deep-green band.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

$vv_cols = array(
	array(
		'title'    => get_theme_mod( 'vv_footer_col1_title', __( 'Quick Link', 'vastra-veda' ) ),
		'location' => 'footer_quick',
		'fallback' => array(
			__( 'About Us', 'vastra-veda' )  => home_url( '/about-us/' ),
			__( 'Our Story', 'vastra-veda' ) => home_url( '/our-story/' ),
			__( 'Blog', 'vastra-veda' )      => home_url( '/blog/' ),
		),
	),
	array(
		'title'    => get_theme_mod( 'vv_footer_col2_title', __( 'Support', 'vastra-veda' ) ),
		'location' => 'footer_support',
		'fallback' => array(
			__( 'Contact Us', 'vastra-veda' )          => home_url( '/contact-us/' ),
			__( 'Shipping Info', 'vastra-veda' )       => home_url( '/shipping-info/' ),
			__( 'Returns & Exchange', 'vastra-veda' )  => home_url( '/returns-exchange/' ),
			__( 'FAQs', 'vastra-veda' )                => home_url( '/faqs/' ),
		),
	),
	array(
		'title'    => get_theme_mod( 'vv_footer_col3_title', __( 'Legal', 'vastra-veda' ) ),
		'location' => 'footer_policies',
		'fallback' => array(
			__( 'Privacy Policy', 'vastra-veda' )     => home_url( '/privacy-policy/' ),
			__( 'Terms & Conditions', 'vastra-veda' ) => home_url( '/terms-conditions/' ),
			__( 'Shipping Policy', 'vastra-veda' )    => home_url( '/shipping-policy/' ),
			__( 'Refund Policy', 'vastra-veda' )      => home_url( '/refund-policy/' ),
		),
	),
);

$vv_socials = vv_social_links();
?>
	</main><!-- #vv-main -->

	<footer class="vv-footer">

		<div class="vv-shell">
			<div class="vv-footer__grid">

				<div class="vv-footer__brand">
					<span class="vv-footer__wordmark"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<p class="vv-footer__tagline">
						<?php
						echo nl2br( esc_html( get_theme_mod(
							'vv_footer_tagline',
							__( "A quiet celebration\nof the handloom — sarees and textiles gathered from weaving houses across India, chosen for their craft and the hands that made them.", 'vastra-veda' )
						) ) );
						?>
					</p>
				</div>

				<span class="vv-footer__divider" aria-hidden="true"></span>

				<?php foreach ( $vv_cols as $vv_col ) : ?>
					<nav class="vv-footer__col" aria-label="<?php echo esc_attr( $vv_col['title'] ); ?>">
						<h2 class="vv-footer__heading"><?php echo esc_html( $vv_col['title'] ); ?></h2>
						<?php vv_footer_menu( $vv_col['location'], $vv_col['fallback'] ); ?>
					</nav>
				<?php endforeach; ?>

				<span class="vv-footer__divider" aria-hidden="true"></span>

				<div class="vv-footer__col vv-footer__col--contact">
					<h2 class="vv-footer__heading">
						<?php echo esc_html( get_theme_mod( 'vv_footer_col4_title', __( 'Location', 'vastra-veda' ) ) ); ?>
					</h2>
					<p class="vv-footer__note">
						<?php
						echo nl2br( esc_html( get_theme_mod(
							'vv_footer_col4_text',
							__( 'New drapes, weaving stories and care notes, once in a while.', 'vastra-veda' )
						) ) );
						?>
					</p>

					<?php if ( $vv_socials ) : ?>
						<ul class="vv-socials">
							<?php foreach ( $vv_socials as $vv_social ) : ?>
								<li>
									<a class="vv-socials__link" href="<?php echo esc_url( $vv_social['url'] ); ?>"
										<?php echo '#' === $vv_social['url'] ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>>
										<?php vv_the_icon( $vv_social['icon'], 16 ); ?>
										<span class="screen-reader-text"><?php echo esc_html( $vv_social['label'] ); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

			</div>
		</div>

		<?php if ( is_active_sidebar( 'footer-note' ) ) : ?>
			<div class="vv-shell vv-footer__widget"><?php dynamic_sidebar( 'footer-note' ); ?></div>
		<?php endif; ?>

		<div class="vv-footer__bar">
			<div class="vv-shell vv-footer__bar-inner">
				<p class="vv-footer__copy">
					&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'vastra-veda' ); ?>
				</p>
				<?php
				if ( has_nav_menu( 'footer_bottom' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_bottom',
							'container'      => false,
							'menu_class'     => 'vv-footer__bottom-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				}
				?>
			</div>
		</div>
	</footer>

	<?php vv_cart_pill(); ?>

</div><!-- #vv-page -->

<?php wp_footer(); ?>
</body>
</html>
