<?php
/**
 * Vastra Veda functions and definitions.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

define( 'VV_VERSION', '1.0.0' );
define( 'VV_DIR', get_template_directory() );
define( 'VV_URI', get_template_directory_uri() );

/* -------------------------------------------------------------------------
 * 1. Theme setup
 * ---------------------------------------------------------------------- */

function vv_setup() {
	load_theme_textdomain( 'vastra-veda', VV_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-spacing' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary'  => __( 'Primary (header, beside the icons)', 'vastra-veda' ),
			'offcanvas' => __( 'Slide-out menu (hamburger)', 'vastra-veda' ),
			'footer_quick'    => __( 'Footer — Quick Link', 'vastra-veda' ),
			'footer_support'  => __( 'Footer — Support', 'vastra-veda' ),
			'footer_policies' => __( 'Footer — Legal', 'vastra-veda' ),
			'footer_bottom'   => __( 'Footer — Bottom bar (optional)', 'vastra-veda' ),
		)
	);

	// Editorial image sizes used by the homepage sections.
	add_image_size( 'vv-hero', 2400, 1400, true );      // Intro slider background.
	add_image_size( 'vv-portrait', 900, 1200, true );   // Category cards / lookbook.
	add_image_size( 'vv-product', 800, 1067, true );    // Product cards (3:4).

	/* WooCommerce */
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 800,
		'single_image_width'    => 1400,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'vv_setup' );

function vv_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'vv_content_width', 1360 );
}
add_action( 'after_setup_theme', 'vv_content_width', 0 );

/* -------------------------------------------------------------------------
 * 2. Assets
 * ---------------------------------------------------------------------- */

function vv_google_fonts_url() {
	/**
	 * Display serif (italic accents + logo) : Cormorant Garamond
	 * Didone section headings               : Playfair Display
	 * Geometric uppercase headings          : Jost
	 * UI / body                             : DM Sans
	 * Swap these for your licensed brand fonts by filtering 'vv_fonts_url'.
	 */
	$url = 'https://fonts.googleapis.com/css2'
		. '?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500'
		. '&family=Jost:wght@200;300;400;500'
		. '&family=Playfair+Display:ital,wght@0,400;0,500;1,400'
		. '&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500'
		. '&display=swap';

	return apply_filters( 'vv_fonts_url', $url );
}

function vv_assets() {
	$fonts = vv_google_fonts_url();
	if ( $fonts ) {
		wp_enqueue_style( 'vv-fonts', $fonts, array(), null );
	}

	wp_enqueue_style( 'vastra-veda', get_stylesheet_uri(), array(), VV_VERSION );
	wp_enqueue_style( 'vv-theme', VV_URI . '/assets/css/theme.css', array( 'vastra-veda' ), VV_VERSION );

	if ( vv_is_woocommerce_active() ) {
		wp_enqueue_style( 'vv-woocommerce', VV_URI . '/assets/css/woocommerce.css', array( 'vv-theme' ), VV_VERSION );
	}

	wp_enqueue_script( 'vv-theme', VV_URI . '/assets/js/theme.js', array(), VV_VERSION, true );
	wp_localize_script(
		'vv-theme',
		'vvData',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'sliderDelay'  => (int) get_theme_mod( 'vv_hero_autoplay', 6000 ),
			'wishNonce'    => wp_create_nonce( 'vv-wishlist' ),
			'i18n'         => array(
				'closeMenu'   => __( 'Close menu', 'vastra-veda' ),
				'closeSearch' => __( 'Close search', 'vastra-veda' ),
				'saved'       => __( 'Saved', 'vastra-veda' ),
				'save'        => __( 'Add to wishlist', 'vastra-veda' ),
				'itemOne'     => __( '%s item', 'vastra-veda' ),
				'itemMany'    => __( '%s items', 'vastra-veda' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vv_assets' );

/**
 * Dequeue WooCommerce's default block styles we fully override,
 * but keep everything functional.
 */
function vv_body_classes( $classes ) {
	if ( is_front_page() && ! is_paged() ) {
		$classes[] = 'vv-has-transparent-header';
	}
	if ( vv_is_woocommerce_active() ) {
		$classes[] = 'vv-woo';
	}
	$classes[] = 'vv-theme';

	return $classes;
}
add_filter( 'body_class', 'vv_body_classes' );

/* -------------------------------------------------------------------------
 * 3. Includes
 * ---------------------------------------------------------------------- */

require VV_DIR . '/inc/template-tags.php';
require VV_DIR . '/inc/class-vv-drawer-walker.php';
require VV_DIR . '/inc/customizer.php';
require VV_DIR . '/inc/woocommerce.php';
require VV_DIR . '/inc/wishlist.php';
require VV_DIR . '/inc/contact.php';

/* -------------------------------------------------------------------------
 * 4. Widgets
 * ---------------------------------------------------------------------- */

function vv_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Shop sidebar', 'vastra-veda' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Filters shown in the shop drawer.', 'vastra-veda' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Footer note', 'vastra-veda' ),
			'id'            => 'footer-note',
			'description'   => __( 'Small block above the footer legal bar.', 'vastra-veda' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'vv_widgets_init' );

/* -------------------------------------------------------------------------
 * 5. Small helpers
 * ---------------------------------------------------------------------- */

function vv_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Excerpt tuning.
 */
add_filter( 'excerpt_length', function () { return 24; } );
add_filter( 'excerpt_more', function () { return '&hellip;'; } );
