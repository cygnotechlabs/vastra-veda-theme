<?php
/**
 * Comments.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="vv-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="vv-comments__title vv-display">
			<?php
			printf(
				esc_html( _n( '%s note', '%s notes', get_comments_number(), 'vastra-veda' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="vv-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'avatar_size' => 44,
					'short_ping' => true,
				)
			);
			?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'         => __( 'Leave a note', 'vastra-veda' ),
			'class_submit'        => 'vv-btn vv-btn--green',
			'comment_notes_before' => '',
		)
	);
	?>
</div>
