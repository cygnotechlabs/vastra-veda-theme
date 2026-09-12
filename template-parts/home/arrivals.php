<?php
/**
 * Section 4 — new arrivals product row.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_theme_mod( 'vv_arrivals_on', true ) || ! vv_is_woocommerce_active() ) {
	return;
}

$vv_count = max( 2, (int) get_theme_mod( 'vv_arrivals_count', 4 ) );

$vv_q = new WP_Query(
	array(
		'post_type'           => 'product',
		'posts_per_page'      => $vv_count,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
		'tax_query'           => array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'exclude-from-catalog',
				'operator' => 'NOT IN',
			),
		),
	)
);

if ( ! $vv_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="vv-section vv-arrivals">
	<div class="vv-shell">
		<?php
		vv_section_head(
			array(
				'heading'   => get_theme_mod( 'vv_arrivals_heading', 'JUST *in*' ),
				'link_url'  => wc_get_page_permalink( 'shop' ),
				'link_text' => __( 'All New Arrivals', 'vastra-veda' ),
			)
		);
		?>

		<ul class="products vv-grid vv-grid--4 columns-4">
			<?php
			while ( $vv_q->have_posts() ) {
				$vv_q->the_post();
				wc_get_template_part( 'content', 'product' );
			}
			?>
		</ul>
	</div>
</section>
<?php
wp_reset_postdata();
