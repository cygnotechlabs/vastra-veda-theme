<?php
/**
 * Template Name: Our Story
 *
 * An editorial page: full-bleed cover, a lede, your page content in a reading
 * column, three craft pillars and a closing invitation.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$vv_cover = has_post_thumbnail()
		? get_the_post_thumbnail_url( null, 'vv-hero' )
		: vv_placeholder_url( 'story-cover', 'hero-2' );
	?>

	<header class="vv-story-hero">
		<div class="vv-story-hero__media" style="background-image:url('<?php echo esc_url( $vv_cover ); ?>')"></div>
		<span class="vv-story-hero__veil" aria-hidden="true"></span>
		<div class="vv-shell vv-story-hero__inner">
			<span class="vv-eyebrow"><?php echo esc_html( get_theme_mod( 'vv_story_eyebrow', __( 'Our story', 'vastra-veda' ) ) ); ?></span>
			<h1 class="vv-display vv-story-hero__title"><?php vv_the_headline( get_the_title() ); ?></h1>
		</div>
	</header>

	<?php if ( has_excerpt() ) : ?>
		<section class="vv-section vv-story-lede">
			<div class="vv-shell">
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<section class="vv-section vv-story-body">
		<div class="vv-shell vv-shell--narrow vv-prose">
			<?php
			the_content();
			wp_link_pages( array( 'before' => '<div class="vv-linkpages">', 'after' => '</div>' ) );
			?>
		</div>
	</section>

	<?php
	$vv_pillars = array();
	for ( $vv_i = 1; $vv_i <= 3; $vv_i++ ) {
		$vv_title = get_theme_mod( "vv_story_p{$vv_i}_title" );
		$vv_text  = get_theme_mod( "vv_story_p{$vv_i}_text" );
		if ( $vv_title || $vv_text ) {
			$vv_pillars[] = array( 'title' => $vv_title, 'text' => $vv_text );
		}
	}

	if ( ! $vv_pillars ) {
		$vv_pillars = array(
			array(
				'title' => __( 'Woven, not printed', 'vastra-veda' ),
				'text'  => __( 'Every piece is made on a loom by a named weaver. We list the cluster it came from, because the hands matter as much as the cloth.', 'vastra-veda' ),
			),
			array(
				'title' => __( 'Small runs', 'vastra-veda' ),
				'text'  => __( 'We buy in tens, not thousands. When a weave sells out it may not return — which is the point of something handmade.', 'vastra-veda' ),
			),
			array(
				'title' => __( 'Paid before sold', 'vastra-veda' ),
				'text'  => __( 'Weavers are paid when the saree leaves the loom, not when it leaves our shelf. Their month should not depend on our season.', 'vastra-veda' ),
			),
		);
	}
	?>
	<section class="vv-section vv-pillars">
		<div class="vv-shell">
			<span class="vv-rule" aria-hidden="true"></span>
			<div class="vv-pillars__grid">
				<?php foreach ( $vv_pillars as $vv_n => $vv_pillar ) : ?>
					<div class="vv-pillar">
						<span class="vv-pillar__num"><?php echo esc_html( vv_roman( $vv_n + 1 ) ); ?></span>
						<h2 class="vv-pillar__title"><?php echo esc_html( $vv_pillar['title'] ); ?></h2>
						<p><?php echo esc_html( $vv_pillar['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="vv-story-cta">
		<div class="vv-shell">
			<h2 class="vv-display vv-story-cta__title">
				<?php vv_the_headline( get_theme_mod( 'vv_story_cta_heading', 'FIND THE ONE *that waits* FOR YOU' ) ); ?>
			</h2>
			<a class="vv-btn vv-btn--green" href="<?php echo esc_url( vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>">
				<?php echo esc_html( get_theme_mod( 'vv_story_cta_button', __( 'Shop the collection', 'vastra-veda' ) ) ); ?>
			</a>
		</div>
	</section>

	<?php
endwhile;

get_footer();
