<?php
/**
 * Template Name: Wishlist
 *
 * Assign this template to a page (slug "wishlist" keeps the menu links tidy).
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

get_header();

$vv_ids = function_exists( 'vv_wishlist_items' ) ? vv_wishlist_items() : array();
$vv_ids = array_values( array_filter( $vv_ids, function ( $id ) {
	return 'publish' === get_post_status( $id );
} ) );
?>

<header class="vv-page-hero">
	<div class="vv-shell">
		<span class="vv-eyebrow"><?php esc_html_e( 'Saved for later', 'vastra-veda' ); ?></span>
		<h1 class="vv-display vv-page-hero__title"><?php the_title(); ?></h1>
		<p class="vv-page-hero__sub">
			<?php
			echo esc_html(
				$vv_ids
					/* translators: %s: number of saved pieces */
					? sprintf( _n( '%s piece put aside.', '%s pieces put aside.', count( $vv_ids ), 'vastra-veda' ), number_format_i18n( count( $vv_ids ) ) )
					: __( 'Nothing saved yet.', 'vastra-veda' )
			);
			?>
		</p>
		<span class="vv-rule" aria-hidden="true"></span>
	</div>
</header>

<section class="vv-section vv-wishpage">
	<div class="vv-shell">

		<?php if ( ! vv_is_woocommerce_active() ) : ?>

			<p class="vv-empty"><?php esc_html_e( 'The wishlist needs WooCommerce to be active.', 'vastra-veda' ); ?></p>

		<?php elseif ( $vv_ids ) : ?>

			<ul class="products vv-grid vv-grid--4 columns-4" data-vv-wishgrid>
				<?php
				$vv_q = new WP_Query( array(
					'post_type'           => 'product',
					'post__in'            => $vv_ids,
					'orderby'             => 'post__in',
					'posts_per_page'      => 60,
					'ignore_sticky_posts' => 1,
					'no_found_rows'       => true,
				) );

				while ( $vv_q->have_posts() ) {
					$vv_q->the_post();
					wc_get_template_part( 'content', 'product' );
				}
				wp_reset_postdata();
				?>
			</ul>

			<p class="vv-wishpage__note">
				<?php esc_html_e( 'Tap the heart on any piece to take it off this list.', 'vastra-veda' ); ?>
				<?php if ( ! is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
						<?php esc_html_e( 'Sign in to keep it across devices.', 'vastra-veda' ); ?>
					</a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<div class="vv-blank">
				<span class="vv-blank__icon"><?php vv_the_icon( 'heart', 30 ); ?></span>
				<h2 class="vv-blank__title"><?php vv_the_headline( 'NOTHING *saved* YET' ); ?></h2>
				<p><?php esc_html_e( 'Tap the heart on any saree and it will wait for you here — through a browse, a night’s sleep, or a change of mind.', 'vastra-veda' ); ?></p>
				<a class="vv-btn vv-btn--green" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Browse the collection', 'vastra-veda' ); ?>
				</a>
			</div>

		<?php endif; ?>

		<?php
		while ( have_posts() ) {
			the_post();
			if ( trim( wp_strip_all_tags( get_the_content() ) ) ) {
				echo '<div class="vv-prose vv-wishpage__copy">';
				the_content();
				echo '</div>';
			}
		}
		?>
	</div>
</section>

<?php
get_footer();
