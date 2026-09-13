<?php
/**
 * Section — "Take a moment to read": three editorial cards.
 *
 * Pulls the latest posts. Until posts exist, three demo cards from the design
 * are shown so the homepage is never half-empty.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_theme_mod( 'vv_editorial_on', true ) ) {
	return;
}

$vv_cards = array();

$vv_q = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
	)
);

if ( $vv_q->have_posts() ) {
	while ( $vv_q->have_posts() ) {
		$vv_q->the_post();
		$vv_cards[] = array(
			'image'   => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'vv-hero' ) : vv_placeholder_url( 'post-' . ( count( $vv_cards ) + 1 ), 'cat-1' ),
			'eyebrow' => vv_post_eyebrow(),
			'title'   => get_the_title(),
			'url'     => get_permalink(),
			'meta'    => vv_post_eyebrow(),
			'minutes' => vv_reading_time(),
		);
	}
	wp_reset_postdata();
} else {
	$vv_cards = array(
		array(
			'image'   => vv_placeholder_url( 'post-1', 'promo' ),
			'eyebrow' => __( 'Pairing Guide', 'vastra-veda' ),
			'title'   => __( 'Two shades of gold, one evening', 'vastra-veda' ),
			'url'     => '',
			'meta'    => __( 'Lookbook', 'vastra-veda' ),
			'minutes' => 4,
		),
		array(
			'image'   => vv_placeholder_url( 'post-2', 'cat-2' ),
			'eyebrow' => __( 'Sister Story', 'vastra-veda' ),
			'title'   => __( 'Jewel-tone twinning, from Jaipur to Jodhpur', 'vastra-veda' ),
			'url'     => '',
			'meta'    => __( 'Styling', 'vastra-veda' ),
			'minutes' => 5,
		),
		array(
			'image'   => vv_placeholder_url( 'post-3', 'hero-2' ),
			'eyebrow' => __( 'Care Guide', 'vastra-veda' ),
			'title'   => __( 'Storing silk through the humid months', 'vastra-veda' ),
			'url'     => '',
			'meta'    => __( 'Care', 'vastra-veda' ),
			'minutes' => 4,
		),
	);
}

$vv_all_url = get_theme_mod( 'vv_editorial_link_url' );
if ( ! $vv_all_url ) {
	$vv_posts_page = (int) get_option( 'page_for_posts' );
	$vv_all_url    = $vv_posts_page ? get_permalink( $vv_posts_page ) : home_url( '/blog/' );
}
?>
<section class="vv-section vv-editorial">
	<div class="vv-shell">

		<h2 class="vv-display vv-editorial__title">
			<?php vv_the_headline( get_theme_mod( 'vv_editorial_heading', 'TAKE A MOMENT *to read*' . "\n" . 'STORIES, ARTICLES &' . "\n" . '*more from* VASTRA VEDA.' ) ); ?>
		</h2>

		<span class="vv-rule" aria-hidden="true"></span>

		<div class="vv-editorial__bar">
			<a class="vv-section-head__link" href="<?php echo esc_url( $vv_all_url ); ?>">
				<?php echo esc_html( get_theme_mod( 'vv_editorial_link_text', __( 'View All', 'vastra-veda' ) ) ); ?>
			</a>
		</div>

		<div class="vv-editorial__grid">
			<?php foreach ( $vv_cards as $vv_i => $vv_card ) : ?>
				<article class="vv-article">
					<?php if ( $vv_card['url'] ) : ?>
						<a class="vv-article__link" href="<?php echo esc_url( $vv_card['url'] ); ?>">
					<?php else : ?>
						<div class="vv-article__link">
					<?php endif; ?>

						<span class="vv-article__media">
							<img src="<?php echo esc_url( $vv_card['image'] ); ?>" alt="" loading="lazy" decoding="async">
							<span class="vv-article__num"><?php echo esc_html( sprintf( '%02d', $vv_i + 1 ) ); ?></span>
							<span class="vv-article__mark" aria-hidden="true"><?php vv_the_icon( 'arrow', 14 ); ?></span>
						</span>

						<span class="vv-article__eyebrow"><?php echo esc_html( $vv_card['eyebrow'] ); ?></span>
						<h3 class="vv-article__title"><?php echo esc_html( $vv_card['title'] ); ?></h3>
						<span class="vv-article__meta">
							<?php echo esc_html( $vv_card['meta'] ); ?>
							<i aria-hidden="true">&bull;</i>
							<?php printf( esc_html__( '%d min read', 'vastra-veda' ), (int) $vv_card['minutes'] ); ?>
						</span>

					<?php echo $vv_card['url'] ? '</a>' : '</div>'; // phpcs:ignore ?>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
