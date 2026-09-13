<?php
/**
 * Customizer — everything on the homepage is editable here.
 * Appearance → Customize → Vastra Veda Homepage
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

function vv_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

function vv_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'vv_home',
		array(
			'title'       => __( 'Vastra Veda Homepage', 'vastra-veda' ),
			'priority'    => 20,
			'description' => __( 'Wrap a word in *asterisks* to render it in the italic display serif — e.g. TRADITION *and* MODERN GRACE.', 'vastra-veda' ),
		)
	);

	/* ------------------------------------------------------------------
	 * 1. Intro slider
	 * --------------------------------------------------------------- */
	$wp_customize->add_section(
		'vv_hero',
		array(
			'title' => __( '1 · Intro slider', 'vastra-veda' ),
			'panel' => 'vv_home',
		)
	);

	$wp_customize->add_setting( 'vv_hero_autoplay', array( 'default' => 6000, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'vv_hero_autoplay', array(
		'label'       => __( 'Auto-advance (ms, 0 to disable)', 'vastra-veda' ),
		'section'     => 'vv_hero',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 0, 'step' => 500 ),
	) );

	$vv_hero_defaults = vv_hero_defaults();

	foreach ( array( 1, 2, 3 ) as $i ) {
		$wp_customize->add_setting( "vv_hero_{$i}_image", array( 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				"vv_hero_{$i}_image",
				array(
					'label'     => sprintf( __( 'Slide %d — background image', 'vastra-veda' ), $i ),
					'section'   => 'vv_hero',
					'mime_type' => 'image',
				)
			)
		);

		$wp_customize->add_setting( "vv_hero_{$i}_text", array(
			'default'           => $vv_hero_defaults[ $i ],
			'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( "vv_hero_{$i}_text", array(
			'label'   => sprintf( __( 'Slide %d — headline', 'vastra-veda' ), $i ),
			'section' => 'vv_hero',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( "vv_hero_{$i}_link", array( 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( "vv_hero_{$i}_link", array(
			'label'   => sprintf( __( 'Slide %d — link (optional)', 'vastra-veda' ), $i ),
			'section' => 'vv_hero',
			'type'    => 'url',
		) );
	}

	/* ------------------------------------------------------------------
	 * 2. Shop by category
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_cats', array(
		'title'       => __( '2 · Shop by category', 'vastra-veda' ),
		'panel'       => 'vv_home',
		'description' => __( 'Cards are pulled from your WooCommerce product categories (with their category image). Demo cards show until you create some.', 'vastra-veda' ),
	) );

	$wp_customize->add_setting( 'vv_cats_heading', array(
		'default'           => 'SHOP BY *category*',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_cats_heading', array(
		'label'   => __( 'Heading', 'vastra-veda' ),
		'section' => 'vv_cats',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_cats_intro', array(
		'default'           => __( 'Every saree is made for a different expression of you — the woman who leads, creates, dreams, celebrates, and simply chooses to be herself.', 'vastra-veda' ),
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_cats_intro', array(
		'label'   => __( 'Intro paragraph', 'vastra-veda' ),
		'section' => 'vv_cats',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'vv_cats_link_text', array(
		'default'           => __( 'All Categories', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_cats_link_text', array(
		'label'   => __( 'Link label', 'vastra-veda' ),
		'section' => 'vv_cats',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_cats_link_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'vv_cats_link_url', array(
		'label'   => __( 'Link URL', 'vastra-veda' ),
		'section' => 'vv_cats',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'vv_cats_count', array( 'default' => 6, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'vv_cats_count', array(
		'label'       => __( 'How many categories', 'vastra-veda' ),
		'section'     => 'vv_cats',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 3, 'max' => 12 ),
	) );

	$wp_customize->add_setting( 'vv_cats_pinned', array( 'default' => true, 'sanitize_callback' => 'vv_sanitize_checkbox' ) );
	$wp_customize->add_control( 'vv_cats_pinned', array(
		'label'       => __( 'Pinned horizontal scroll on desktop', 'vastra-veda' ),
		'description' => __( 'The row slides sideways as the visitor scrolls down. Turn off for a plain swipeable row.', 'vastra-veda' ),
		'section'     => 'vv_cats',
		'type'        => 'checkbox',
	) );

	/* ------------------------------------------------------------------
	 * 3. Split promo
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_promo', array(
		'title' => __( '3 · Split promo', 'vastra-veda' ),
		'panel' => 'vv_home',
	) );

	$wp_customize->add_setting( 'vv_promo_heading', array(
		'default'           => 'OWN YOUR *drape*' . "\n" . 'EXPLORE SAREES MADE' . "\n" . '*for your* UNIQUE STYLE.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_promo_heading', array(
		'label'   => __( 'Heading', 'vastra-veda' ),
		'section' => 'vv_promo',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'vv_promo_btn_text', array(
		'default'           => __( 'Shop Now', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_promo_btn_text', array(
		'label'   => __( 'Button label', 'vastra-veda' ),
		'section' => 'vv_promo',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_promo_btn_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'vv_promo_btn_url', array(
		'label'   => __( 'Button URL', 'vastra-veda' ),
		'section' => 'vv_promo',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'vv_promo_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'vv_promo_image',
			array(
				'label'     => __( 'Image', 'vastra-veda' ),
				'section'   => 'vv_promo',
				'mime_type' => 'image',
			)
		)
	);

	/* ------------------------------------------------------------------
	 * 4. From our stories
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_stories', array(
		'title'       => __( '4 · From our stories', 'vastra-veda' ),
		'panel'       => 'vv_home',
		'description' => __( 'Six-tile mosaic. Tiles 1 and 4 are the tall ones and carry the play button.', 'vastra-veda' ),
	) );

	$wp_customize->add_setting( 'vv_stories_on', array( 'default' => true, 'sanitize_callback' => 'vv_sanitize_checkbox' ) );
	$wp_customize->add_control( 'vv_stories_on', array(
		'label'   => __( 'Show this section', 'vastra-veda' ),
		'section' => 'vv_stories',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'vv_stories_heading', array(
		'default'           => 'FROM OUR *stories*',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_stories_heading', array(
		'label'   => __( 'Heading', 'vastra-veda' ),
		'section' => 'vv_stories',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_stories_intro', array(
		'default'           => __( 'Discover sarees, styling moments, and the latest from Vastra Veda on Instagram.', 'vastra-veda' ),
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_stories_intro', array(
		'label'   => __( 'Intro paragraph', 'vastra-veda' ),
		'section' => 'vv_stories',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'vv_stories_link_text', array(
		'default'           => __( 'Follow Us', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_stories_link_text', array(
		'label'   => __( 'Link label', 'vastra-veda' ),
		'section' => 'vv_stories',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_stories_link_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'vv_stories_link_url', array(
		'label'       => __( 'Instagram profile URL', 'vastra-veda' ),
		'description' => __( 'Used for the link and for any tile without its own URL.', 'vastra-veda' ),
		'section'     => 'vv_stories',
		'type'        => 'url',
	) );

	for ( $vv_i = 1; $vv_i <= 6; $vv_i++ ) {
		$wp_customize->add_setting( "vv_stories_{$vv_i}_image", array( 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				"vv_stories_{$vv_i}_image",
				array(
					/* translators: %d: tile number */
					'label'     => sprintf( __( 'Tile %d — image', 'vastra-veda' ), $vv_i ),
					'section'   => 'vv_stories',
					'mime_type' => 'image',
				)
			)
		);

		$wp_customize->add_setting( "vv_stories_{$vv_i}_link", array( 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( "vv_stories_{$vv_i}_link", array(
			/* translators: %d: tile number */
			'label'   => sprintf( __( 'Tile %d — link', 'vastra-veda' ), $vv_i ),
			'section' => 'vv_stories',
			'type'    => 'url',
		) );
	}

	/* ------------------------------------------------------------------
	 * 5. Editorial cards
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_editorial', array(
		'title'       => __( '5 · Editorial cards', 'vastra-veda' ),
		'panel'       => 'vv_home',
		'description' => __( 'Shows your three most recent blog posts. Demo cards appear until you publish one.', 'vastra-veda' ),
	) );

	$wp_customize->add_setting( 'vv_editorial_on', array( 'default' => true, 'sanitize_callback' => 'vv_sanitize_checkbox' ) );
	$wp_customize->add_control( 'vv_editorial_on', array(
		'label'   => __( 'Show this section', 'vastra-veda' ),
		'section' => 'vv_editorial',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'vv_editorial_heading', array(
		'default'           => 'TAKE A MOMENT *to read*' . "\n" . 'STORIES, ARTICLES &' . "\n" . '*more from* VASTRA VEDA.',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_editorial_heading', array(
		'label'   => __( 'Heading', 'vastra-veda' ),
		'section' => 'vv_editorial',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'vv_editorial_link_text', array(
		'default'           => __( 'View All', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_editorial_link_text', array(
		'label'   => __( 'Link label', 'vastra-veda' ),
		'section' => 'vv_editorial',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_editorial_link_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'vv_editorial_link_url', array(
		'label'       => __( 'Link URL', 'vastra-veda' ),
		'description' => __( 'Defaults to your posts page.', 'vastra-veda' ),
		'section'     => 'vv_editorial',
		'type'        => 'url',
	) );

	/* ------------------------------------------------------------------
	 * 4. New arrivals
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_arrivals', array(
		'title' => __( '6 · New arrivals (not in the design)', 'vastra-veda' ),
		'panel' => 'vv_home',
	) );

	$wp_customize->add_setting( 'vv_arrivals_on', array( 'default' => false, 'sanitize_callback' => 'vv_sanitize_checkbox' ) );
	$wp_customize->add_control( 'vv_arrivals_on', array(
		'label'   => __( 'Show the new arrivals row', 'vastra-veda' ),
		'section' => 'vv_arrivals',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'vv_arrivals_heading', array(
		'default'           => 'JUST *in*',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_arrivals_heading', array(
		'label'   => __( 'Heading', 'vastra-veda' ),
		'section' => 'vv_arrivals',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_arrivals_count', array( 'default' => 4, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'vv_arrivals_count', array(
		'label'       => __( 'How many products', 'vastra-veda' ),
		'section'     => 'vv_arrivals',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 2, 'max' => 12 ),
	) );

	/* ------------------------------------------------------------------
	 * 5. Footer
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_footer', array(
		'title' => __( '7 · Footer', 'vastra-veda' ),
		'panel' => 'vv_home',
	) );

	$wp_customize->add_setting( 'vv_footer_tagline', array(
		'default'           => __( "A quiet celebration\nof the handloom — sarees and textiles gathered from weaving houses across India, chosen for their craft and the hands that made them.", 'vastra-veda' ),
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_footer_tagline', array(
		'label'       => __( 'Brand paragraph', 'vastra-veda' ),
		'description' => __( 'Line breaks are preserved.', 'vastra-veda' ),
		'section'     => 'vv_footer',
		'type'        => 'textarea',
	) );

	/* Column headings — the links themselves come from Appearance → Menus. */
	$vv_footer_titles = array(
		'vv_footer_col1_title' => array( __( 'Column 1 heading', 'vastra-veda' ), __( 'Quick Link', 'vastra-veda' ) ),
		'vv_footer_col2_title' => array( __( 'Column 2 heading', 'vastra-veda' ), __( 'Support', 'vastra-veda' ) ),
		'vv_footer_col3_title' => array( __( 'Column 3 heading', 'vastra-veda' ), __( 'Legal', 'vastra-veda' ) ),
		'vv_footer_col4_title' => array( __( 'Column 4 heading', 'vastra-veda' ), __( 'Location', 'vastra-veda' ) ),
	);
	foreach ( $vv_footer_titles as $vv_key => $vv_meta ) {
		$wp_customize->add_setting( $vv_key, array(
			'default'           => $vv_meta[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $vv_key, array(
			'label'   => $vv_meta[0],
			'section' => 'vv_footer',
			'type'    => 'text',
		) );
	}

	$wp_customize->add_setting( 'vv_footer_col4_text', array(
		'default'           => __( 'New drapes, weaving stories and care notes, once in a while.', 'vastra-veda' ),
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'vv_footer_col4_text', array(
		'label'   => __( 'Column 4 text', 'vastra-veda' ),
		'section' => 'vv_footer',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'vv_drawer_note', array(
		'default'           => __( 'Handloomed with care · Since heritage', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_drawer_note', array(
		'label'       => __( 'Slide-out menu footnote', 'vastra-veda' ),
		'description' => __( 'Small line at the bottom of the slide-out menu. Empty hides it.', 'vastra-veda' ),
		'section'     => 'vv_footer',
		'type'        => 'text',
	) );

	/* Social profiles — leave a field empty to hide that icon. */
	$vv_socials = array(
		'facebook'  => array( __( 'Facebook URL', 'vastra-veda' ), '#' ),
		'instagram' => array( __( 'Instagram URL', 'vastra-veda' ), '#' ),
		'youtube'   => array( __( 'YouTube URL', 'vastra-veda' ), '#' ),
		'x'         => array( __( 'X URL', 'vastra-veda' ), '#' ),
		'pinterest' => array( __( 'Pinterest URL', 'vastra-veda' ), '' ),
		'whatsapp'  => array( __( 'WhatsApp URL', 'vastra-veda' ), '' ),
	);
	foreach ( $vv_socials as $vv_key => $vv_meta ) {
		$wp_customize->add_setting( 'vv_social_' . $vv_key, array(
			'default'           => $vv_meta[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'vv_social_' . $vv_key, array(
			'label'       => $vv_meta[0],
			'description' => 'facebook' === $vv_key ? __( 'Empty hides the icon.', 'vastra-veda' ) : '',
			'section'     => 'vv_footer',
			'type'        => 'text',
		) );
	}

	/* ------------------------------------------------------------------
	 * Account (login / register panel)
	 * --------------------------------------------------------------- */
	$wp_customize->add_section( 'vv_account', array(
		'title'       => __( 'Account page', 'vastra-veda' ),
		'priority'    => 25,
		'description' => __( 'The split panel shown to logged-out visitors on My Account.', 'vastra-veda' ),
	) );

	$wp_customize->add_setting( 'vv_account_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'vv_account_image',
			array(
				'label'     => __( 'Side image', 'vastra-veda' ),
				'section'   => 'vv_account',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting( 'vv_account_title', array(
		'default'           => __( 'Welcome', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_account_title', array(
		'label'   => __( 'Heading', 'vastra-veda' ),
		'section' => 'vv_account',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_account_quote', array(
		'default'           => __( '“Every drape is a quiet inheritance, worn forward.”', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_account_quote', array(
		'label'   => __( 'Pull quote', 'vastra-veda' ),
		'section' => 'vv_account',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'vv_account_stamp', array(
		'default'           => __( 'Vastra Veda · Est. Heritage', 'vastra-veda' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'vv_account_stamp', array(
		'label'   => __( 'Small line under the quote', 'vastra-veda' ),
		'section' => 'vv_account',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'vv_account_google_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'vv_account_google_url', array(
		'label'       => __( 'Google sign-in URL', 'vastra-veda' ),
		'description' => __( 'Needs a social-login plugin. Leave both empty and the social buttons are hidden rather than shown dead.', 'vastra-veda' ),
		'section'     => 'vv_account',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'vv_account_apple_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'vv_account_apple_url', array(
		'label'   => __( 'Apple sign-in URL', 'vastra-veda' ),
		'section' => 'vv_account',
		'type'    => 'url',
	) );

	/* Live-refresh the simple text bits. */
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
}
add_action( 'customize_register', 'vv_customize_register' );
