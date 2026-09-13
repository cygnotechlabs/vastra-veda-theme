<?php
/**
 * One-click site scaffolding.
 *
 * A theme ships templates, not content — so the pages its menus point at do
 * not exist until somebody makes them. This creates the missing ones, assigns
 * the right template to each, and builds the five menus, once, on request.
 *
 * Idempotent: anything already present is left exactly as it is.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

/**
 * The pages this theme's navigation assumes.
 *
 * @return array slug => [ title, template, body ]
 */
function vv_required_pages() {
	return array(
		'our-story' => array(
			__( 'Our Story', 'vastra-veda' ),
			'template-our-story.php',
			__( 'Write your story here. The cover image comes from this page’s featured image, and the opening line from its excerpt.', 'vastra-veda' ),
		),
		'contact-us' => array(
			__( 'Contact Us', 'vastra-veda' ),
			'template-contact.php',
			'',
		),
		'wishlist' => array(
			__( 'Wishlist', 'vastra-veda' ),
			'template-wishlist.php',
			'',
		),
		'about-us' => array(
			__( 'About Us', 'vastra-veda' ),
			'',
			__( 'Tell people who you are.', 'vastra-veda' ),
		),
		'blog' => array(
			__( 'Blog', 'vastra-veda' ),
			'',
			'',
		),
		'shipping-info' => array(
			__( 'Shipping Info', 'vastra-veda' ),
			'',
			__( 'Add your delivery timelines, charges and courier partners here.', 'vastra-veda' ),
		),
		'returns-exchange' => array(
			__( 'Returns & Exchange', 'vastra-veda' ),
			'',
			__( 'Add your returns window and process here.', 'vastra-veda' ),
		),
		'faqs' => array(
			__( 'FAQs', 'vastra-veda' ),
			'',
			__( 'Add the questions customers actually ask.', 'vastra-veda' ),
		),
		'privacy-policy' => array(
			__( 'Privacy Policy', 'vastra-veda' ),
			'',
			__( 'Replace this with your privacy policy. Do not publish the site with this placeholder in place.', 'vastra-veda' ),
		),
		'terms-conditions' => array(
			__( 'Terms & Conditions', 'vastra-veda' ),
			'',
			__( 'Replace this with your terms. Do not publish the site with this placeholder in place.', 'vastra-veda' ),
		),
		'shipping-policy' => array(
			__( 'Shipping Policy', 'vastra-veda' ),
			'',
			__( 'Replace this with your shipping policy.', 'vastra-veda' ),
		),
		'refund-policy' => array(
			__( 'Refund Policy', 'vastra-veda' ),
			'',
			__( 'Replace this with your refund policy.', 'vastra-veda' ),
		),
	);
}

function vv_missing_pages() {
	$missing = array();

	foreach ( vv_required_pages() as $slug => $meta ) {
		if ( ! get_page_by_path( $slug ) ) {
			$missing[ $slug ] = $meta;
		}
	}

	return $missing;
}

/**
 * Create everything that is missing. Returns a short report.
 */
function vv_scaffold_site() {
	$made = array();

	foreach ( vv_missing_pages() as $slug => $meta ) {
		list( $title, $template, $body ) = $meta;

		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $body ? '<!-- wp:paragraph --><p>' . esc_html( $body ) . '</p><!-- /wp:paragraph -->' : '',
		) );

		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}

		if ( $template ) {
			update_post_meta( $id, '_wp_page_template', $template );
		}

		$made[ $slug ] = $id;
	}

	/* Posts page */
	$blog = get_page_by_path( 'blog' );
	if ( $blog && ! get_option( 'page_for_posts' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $blog->ID );
	}

	vv_scaffold_menus();

	return $made;
}

/**
 * Build the menus the design expects, and only those not already assigned.
 */
function vv_scaffold_menus() {
	$shop = vv_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$acct = vv_is_woocommerce_active() ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
	$cart = vv_is_woocommerce_active() ? wc_get_cart_url() : home_url( '/cart/' );

	$plan = array(
		'primary' => array(
			__( 'Vastra Veda — Primary', 'vastra-veda' ),
			array(
				array( __( 'New Arrivals', 'vastra-veda' ), add_query_arg( 'orderby', 'date', $shop ) ),
				array( __( 'Sarees', 'vastra-veda' ), $shop ),
			),
		),
		'offcanvas' => array(
			__( 'Vastra Veda — Slide-out', 'vastra-veda' ),
			array(
				array( __( 'Sarees', 'vastra-veda' ), $shop ),
				array( __( 'New Arrivals', 'vastra-veda' ), add_query_arg( 'orderby', 'date', $shop ) ),
				array( __( 'Profile', 'vastra-veda' ), $acct ),
				array( __( 'Wishlist', 'vastra-veda' ), 'page:wishlist', 'vv-count-wishlist' ),
				array( __( 'Cart', 'vastra-veda' ), $cart, 'vv-count-cart' ),
				array( __( 'Our Story', 'vastra-veda' ), 'page:our-story' ),
				array( __( 'Contact Us', 'vastra-veda' ), 'page:contact-us' ),
			),
		),
		'footer_quick' => array(
			__( 'Vastra Veda — Footer Quick', 'vastra-veda' ),
			array(
				array( __( 'About Us', 'vastra-veda' ), 'page:about-us' ),
				array( __( 'Our Story', 'vastra-veda' ), 'page:our-story' ),
				array( __( 'Blog', 'vastra-veda' ), 'page:blog' ),
			),
		),
		'footer_support' => array(
			__( 'Vastra Veda — Footer Support', 'vastra-veda' ),
			array(
				array( __( 'Contact Us', 'vastra-veda' ), 'page:contact-us' ),
				array( __( 'Shipping Info', 'vastra-veda' ), 'page:shipping-info' ),
				array( __( 'Returns & Exchange', 'vastra-veda' ), 'page:returns-exchange' ),
				array( __( 'FAQs', 'vastra-veda' ), 'page:faqs' ),
			),
		),
		'footer_policies' => array(
			__( 'Vastra Veda — Footer Legal', 'vastra-veda' ),
			array(
				array( __( 'Privacy Policy', 'vastra-veda' ), 'page:privacy-policy' ),
				array( __( 'Terms & Conditions', 'vastra-veda' ), 'page:terms-conditions' ),
				array( __( 'Shipping Policy', 'vastra-veda' ), 'page:shipping-policy' ),
				array( __( 'Refund Policy', 'vastra-veda' ), 'page:refund-policy' ),
			),
		),
	);

	$locations = get_theme_mod( 'nav_menu_locations', array() );

	foreach ( $plan as $location => $spec ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue; /* the site owner already assigned one — leave it alone */
		}

		list( $menu_name, $items ) = $spec;

		$menu = wp_get_nav_menu_object( $menu_name );
		$menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );

		if ( ! $menu_id || is_wp_error( $menu_id ) ) {
			continue;
		}

		if ( ! $menu ) {
			foreach ( $items as $item ) {
				$label   = $item[0];
				$target  = $item[1];
				$classes = isset( $item[2] ) ? $item[2] : '';

				if ( 0 === strpos( $target, 'page:' ) ) {
					$page = get_page_by_path( substr( $target, 5 ) );
					if ( ! $page ) {
						continue;
					}
					wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-title'     => $label,
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page->ID,
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-classes'   => $classes,
					) );
					continue;
				}

				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'   => $label,
					'menu-item-url'     => $target,
					'menu-item-status'  => 'publish',
					'menu-item-classes' => $classes,
				) );
			}
		}

		$locations[ $location ] = $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/* -------------------------------------------------------------------------
 * Admin prompt
 * ---------------------------------------------------------------------- */

add_action( 'after_switch_theme', 'vv_scaffold_site' );

function vv_scaffold_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$missing = vv_missing_pages();
	if ( ! $missing ) {
		return;
	}

	$url = wp_nonce_url( admin_url( 'index.php?vv_scaffold=1' ), 'vv-scaffold' );
	?>
	<div class="notice notice-warning">
		<p><strong><?php esc_html_e( 'Vastra Veda: some pages the menus point at don’t exist yet.', 'vastra-veda' ); ?></strong></p>
		<p>
			<?php
			printf(
				/* translators: %s: comma separated page titles */
				esc_html__( 'Missing: %s. Until they exist those links return 404.', 'vastra-veda' ),
				esc_html( implode( ', ', wp_list_pluck( $missing, 0 ) ) )
			);
			?>
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $url ); ?>">
				<?php esc_html_e( 'Create the missing pages and menus', 'vastra-veda' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'vv_scaffold_notice' );

function vv_scaffold_run() {
	if ( empty( $_GET['vv_scaffold'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	check_admin_referer( 'vv-scaffold' );

	$made = vv_scaffold_site();

	set_transient( 'vv_scaffold_done', count( $made ), 60 );
	wp_safe_redirect( admin_url( 'edit.php?post_type=page' ) );
	exit;
}
add_action( 'admin_init', 'vv_scaffold_run' );

function vv_scaffold_done_notice() {
	$n = get_transient( 'vv_scaffold_done' );
	if ( false === $n ) {
		return;
	}
	delete_transient( 'vv_scaffold_done' );
	?>
	<div class="notice notice-success is-dismissible">
		<p>
			<?php
			printf(
				/* translators: %d: number of pages created */
				esc_html( _n( '%d page created and the menus assigned.', '%d pages created and the menus assigned.', (int) $n, 'vastra-veda' ) ),
				(int) $n
			);
			?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'vv_scaffold_done_notice' );

/* -------------------------------------------------------------------------
 * Safety net: match a page to its template by slug.
 *
 * Creating "Our Story" by hand and forgetting the Template dropdown leaves the
 * page on page.php, which looks like the theme never deployed. If a page uses
 * the default template but its slug is one this theme has a template for, use
 * that template. An explicit choice in the editor always wins.
 * ---------------------------------------------------------------------- */

function vv_template_by_slug( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	$chosen = get_page_template_slug( get_queried_object_id() );
	if ( $chosen ) {
		return $template; /* the editor made a deliberate choice */
	}

	$map = apply_filters( 'vv_slug_templates', array(
		'our-story'  => 'template-our-story.php',
		'our-stories'=> 'template-our-story.php',
		'about'      => 'template-our-story.php',
		'about-us'   => 'template-our-story.php',
		'contact'    => 'template-contact.php',
		'contact-us' => 'template-contact.php',
		'contactus'  => 'template-contact.php',
		'get-in-touch' => 'template-contact.php',
		'wishlist'   => 'template-wishlist.php',
		'favourites' => 'template-wishlist.php',
	) );

	/* Normalise so contact_us, Contact-Us and contact%20us all land. */
	$slug = strtolower( (string) get_post_field( 'post_name', get_queried_object_id() ) );
	$slug = str_replace( array( '_', '%20', ' ' ), '-', $slug );
	$slug = trim( preg_replace( '/-+/', '-', $slug ), '-' );

	if ( isset( $map[ $slug ] ) && file_exists( VV_DIR . '/' . $map[ $slug ] ) ) {
		return VV_DIR . '/' . $map[ $slug ];
	}

	return $template;
}
add_filter( 'template_include', 'vv_template_by_slug', 20 );
