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

	<?php
	$vv_lede = has_excerpt()
		? get_the_excerpt()
		: get_theme_mod( 'vv_story_lede', __( 'We began with one question — who actually wove this? Every saree here can answer it, by name, by loom, by village.', 'vastra-veda' ) );
	?>
	<?php if ( $vv_lede ) : ?>
		<section class="vv-section vv-story-lede">
			<div class="vv-shell">
				<p><?php echo esc_html( $vv_lede ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
		<section class="vv-section vv-story-body">
			<div class="vv-shell vv-shell--narrow vv-prose">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<div class="vv-linkpages">', 'after' => '</div>' ) );
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$vv_splits = array(
		1 => array(
			'default_title' => __( 'The loom comes first', 'vastra-veda' ),
			'default_text'  => __( "Every piece starts on a handloom, not a print table. A single six-yard saree can take a weaver between four days and three weeks, depending on the density of the zari and how much of the border is worked by hand.\n\nWe buy the whole run when it comes off the loom, so nobody is left holding stock they wove on speculation.", 'vastra-veda' ),
			'default_note'  => __( 'Kanchipuram · Varanasi · Bhagalpur', 'vastra-veda' ),
			'fallback_img'  => 'cat-2',
		),
		2 => array(
			'default_title' => __( 'Chosen by hand, twice', 'vastra-veda' ),
			'default_text'  => __( "Once at the cluster, where we look at the weave against daylight, and again before it is folded and sent — for slubs, for pulled zari, for a border that wandered.\n\nWhat reaches you has been through two pairs of hands that were allowed to say no.", 'vastra-veda' ),
			'default_note'  => __( 'Every piece, every order', 'vastra-veda' ),
			'fallback_img'  => 'story-1',
		),
	);
	?>

	<?php foreach ( $vv_splits as $vv_n => $vv_split ) : ?>
		<?php
		$vv_title = get_theme_mod( "vv_story_s{$vv_n}_title", $vv_split['default_title'] );
		$vv_text  = get_theme_mod( "vv_story_s{$vv_n}_text", $vv_split['default_text'] );
		$vv_note  = get_theme_mod( "vv_story_s{$vv_n}_note", $vv_split['default_note'] );
		$vv_img   = vv_image_url( "vv_story_s{$vv_n}_image", 'story-split-' . $vv_n, 'vv-portrait', $vv_split['fallback_img'] );

		if ( ! $vv_title && ! $vv_text ) {
			continue;
		}
		?>
		<section class="vv-split<?php echo 0 === $vv_n % 2 ? ' vv-split--flip' : ''; ?>">
			<div class="vv-shell vv-split__inner">
				<figure class="vv-split__media">
					<img src="<?php echo esc_url( $vv_img ); ?>" alt="" loading="lazy" decoding="async">
				</figure>
				<div class="vv-split__text">
					<span class="vv-split__num"><?php echo esc_html( vv_roman( $vv_n ) ); ?></span>
					<h2 class="vv-split__title"><?php echo esc_html( $vv_title ); ?></h2>
					<div class="vv-split__body"><?php echo wpautop( esc_html( $vv_text ) ); ?></div>
					<?php if ( $vv_note ) : ?>
						<span class="vv-split__note"><?php echo esc_html( $vv_note ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endforeach; ?>

	<?php
	$vv_stats = array();
	for ( $vv_i = 1; $vv_i <= 4; $vv_i++ ) {
		$vv_defaults = array(
			1 => array( '40+', __( 'Weaving families', 'vastra-veda' ) ),
			2 => array( '9',   __( 'Clusters across India', 'vastra-veda' ) ),
			3 => array( '100%',__( 'Handloom, no power looms', 'vastra-veda' ) ),
			4 => array( '48h', __( 'Paid after the loom', 'vastra-veda' ) ),
		);
		$vv_fig = get_theme_mod( "vv_story_stat{$vv_i}_figure", $vv_defaults[ $vv_i ][0] );
		$vv_lab = get_theme_mod( "vv_story_stat{$vv_i}_label", $vv_defaults[ $vv_i ][1] );
		if ( $vv_fig || $vv_lab ) {
			$vv_stats[] = array( $vv_fig, $vv_lab );
		}
	}
	?>
	<?php if ( $vv_stats ) : ?>
		<section class="vv-figures">
			<div class="vv-shell vv-figures__grid">
				<?php foreach ( $vv_stats as $vv_stat ) : ?>
					<div class="vv-figure">
						<span class="vv-figure__num"><?php echo esc_html( $vv_stat[0] ); ?></span>
						<span class="vv-figure__label"><?php echo esc_html( $vv_stat[1] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

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

	<?php
	$vv_quote = get_theme_mod( 'vv_story_quote', __( 'A saree should outlive the occasion it was bought for. That is the only brief we give a weaver.', 'vastra-veda' ) );
	$vv_cite  = get_theme_mod( 'vv_story_quote_cite', __( 'Vastra Veda', 'vastra-veda' ) );
	?>
	<?php if ( $vv_quote ) : ?>
		<section class="vv-pull">
			<div class="vv-shell vv-shell--narrow">
				<blockquote class="vv-pull__quote">&ldquo;<?php echo esc_html( $vv_quote ); ?>&rdquo;</blockquote>
				<?php if ( $vv_cite ) : ?>
					<cite class="vv-pull__cite"><?php echo esc_html( $vv_cite ); ?></cite>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

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
