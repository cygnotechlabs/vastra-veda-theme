<?php
/**
 * Contact form handling — no plugin required.
 *
 * Posts to itself, validates, emails the shop address and redirects back with
 * a status so a refresh never resubmits.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

function vv_contact_recipient() {
	$to = get_theme_mod( 'vv_contact_email' );

	return is_email( $to ) ? $to : get_option( 'admin_email' );
}

function vv_contact_handle() {
	if ( empty( $_POST['vv_contact'] ) ) {
		return;
	}

	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	/* Bots fill hidden fields; people do not. */
	if ( ! empty( $_POST['vv_hp'] ) ) {
		wp_safe_redirect( add_query_arg( 'vv_sent', '1', $redirect ) );
		exit;
	}

	if ( ! isset( $_POST['vv_contact_nonce'] ) ||
		 ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vv_contact_nonce'] ) ), 'vv-contact' ) ) {
		wp_safe_redirect( add_query_arg( 'vv_sent', 'nonce', $redirect ) );
		exit;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['vv_name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['vv_email'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['vv_subject'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['vv_message'] ?? '' ) );

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'vv_sent', 'invalid', $redirect ) );
		exit;
	}

	$body = sprintf(
		"%s\n\n---\n%s: %s\n%s: %s\n%s: %s\n",
		$message,
		__( 'Name', 'vastra-veda' ), $name,
		__( 'Email', 'vastra-veda' ), $email,
		__( 'Sent from', 'vastra-veda' ), $redirect
	);

	$sent = wp_mail(
		vv_contact_recipient(),
		sprintf(
			/* translators: 1: site name, 2: subject */
			__( '[%1$s] %2$s', 'vastra-veda' ),
			get_bloginfo( 'name' ),
			$subject ? $subject : __( 'Enquiry', 'vastra-veda' )
		),
		$body,
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $name . ' <' . $email . '>',
		)
	);

	wp_safe_redirect( add_query_arg( 'vv_sent', $sent ? '1' : 'failed', $redirect ) );
	exit;
}
add_action( 'template_redirect', 'vv_contact_handle' );

/**
 * Notice for the status in the URL.
 */
function vv_contact_notice() {
	$state = isset( $_GET['vv_sent'] ) ? sanitize_key( wp_unslash( $_GET['vv_sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

	if ( ! $state ) {
		return;
	}

	$map = array(
		'1'       => array( 'ok',   __( 'Thank you — your message is with us. We reply within one working day.', 'vastra-veda' ) ),
		'invalid' => array( 'bad',  __( 'Please add your name, a valid email address and a message.', 'vastra-veda' ) ),
		'nonce'   => array( 'bad',  __( 'That form expired. Please try again.', 'vastra-veda' ) ),
		'failed'  => array( 'bad',  __( 'The message could not be sent just now. Please email us directly.', 'vastra-veda' ) ),
	);

	if ( ! isset( $map[ $state ] ) ) {
		return;
	}

	printf(
		'<p class="vv-notice vv-notice--%1$s" role="status">%2$s</p>',
		esc_attr( $map[ $state ][0] ),
		esc_html( $map[ $state ][1] )
	);
}
