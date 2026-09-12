<?php
/**
 * Header.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="vv-skip-link screen-reader-text" href="#vv-main"><?php esc_html_e( 'Skip to content', 'vastra-veda' ); ?></a>

<div id="vv-page" class="vv-page">

	<header id="vv-header" class="vv-header" data-vv-header>
		<div class="vv-header__inner">

			<div class="vv-header__brand">
				<?php vv_site_brand(); ?>
			</div>

			<div class="vv-header__actions">

				<nav class="vv-nav" aria-label="<?php esc_attr_e( 'Primary', 'vastra-veda' ); ?>">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'vv-nav__list',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
					} else {
						echo '<ul class="vv-nav__list">';
						printf(
							'<li><a href="%s">%s</a></li>',
							esc_url( vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ),
							esc_html__( 'New Arrivals', 'vastra-veda' )
						);
						printf(
							'<li><a href="%s">%s</a></li>',
							esc_url( vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ),
							esc_html__( 'Sarees', 'vastra-veda' )
						);
						echo '</ul>';
					}
					?>
				</nav>

				<button type="button" class="vv-circle-btn" data-vv-search-open
					aria-controls="vv-search" aria-expanded="false">
					<?php vv_the_icon( 'search', 17 ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Search', 'vastra-veda' ); ?></span>
				</button>

				<button type="button" class="vv-circle-btn vv-burger" data-vv-menu-open
					aria-controls="vv-offcanvas" aria-expanded="false">
					<span class="vv-burger__lines" aria-hidden="true"><i></i><i></i></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'vastra-veda' ); ?></span>
				</button>

			</div>
		</div>
	</header>

	<?php get_template_part( 'template-parts/overlay-search' ); ?>
	<?php get_template_part( 'template-parts/overlay-menu' ); ?>

	<main id="vv-main" class="vv-main">
