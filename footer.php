<?php
/**
 * Footer.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

$vv_shop = vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
	</main><!-- #vv-main -->

	<footer class="vv-footer">
		<div class="vv-shell">

			<div class="vv-footer__top">
				<div class="vv-footer__brand">
					<span class="vv-display vv-footer__wordmark"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<p class="vv-footer__tagline">
						<?php echo esc_html( get_theme_mod( 'vv_footer_tagline', __( 'Handwoven heritage, cut for the way you live now. Made in India, shipped worldwide.', 'vastra-veda' ) ) ); ?>
					</p>
					<a class="vv-link-arrow" href="<?php echo esc_url( $vv_shop ); ?>">
						<?php esc_html_e( 'Explore the collection', 'vastra-veda' ); ?>
						<?php vv_the_icon( 'arrow', 16 ); ?>
					</a>
				</div>

				<div class="vv-footer__cols">
					<?php
					$vv_cols = array(
						'footer_shop'  => __( 'Shop', 'vastra-veda' ),
						'footer_help'  => __( 'Help', 'vastra-veda' ),
						'footer_about' => __( 'House', 'vastra-veda' ),
					);
					foreach ( $vv_cols as $vv_loc => $vv_label ) :
						?>
						<div class="vv-footer__col">
							<span class="vv-eyebrow"><?php echo esc_html( $vv_label ); ?></span>
							<?php
							if ( has_nav_menu( $vv_loc ) ) {
								wp_nav_menu(
									array(
										'theme_location' => $vv_loc,
										'container'      => false,
										'menu_class'     => 'vv-linklist',
										'depth'          => 1,
										'fallback_cb'    => false,
									)
								);
							} else {
								echo '<ul class="vv-linklist"><li><a href="' . esc_url( $vv_shop ) . '">' . esc_html__( 'All sarees', 'vastra-veda' ) . '</a></li></ul>';
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( is_active_sidebar( 'footer-note' ) ) : ?>
				<div class="vv-footer__note"><?php dynamic_sidebar( 'footer-note' ); ?></div>
			<?php endif; ?>

			<div class="vv-footer__bottom">
				<p class="vv-footer__copy">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'vastra-veda' ); ?>
				</p>
				<?php
				if ( has_nav_menu( 'footer_legal' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer_legal',
							'container'      => false,
							'menu_class'     => 'vv-footer__legal',
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
