<?php
/**
 * 404.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="vv-section vv-404">
	<div class="vv-shell vv-shell--narrow">
		<h1 class="vv-display vv-404__title"><?php vv_the_headline( 'THIS *thread* CAME LOOSE' ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for is not here. Let us take you back to the weave.', 'vastra-veda' ); ?></p>
		<a class="vv-btn vv-btn--green" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'vastra-veda' ); ?></a>
		<?php get_search_form(); ?>
	</div>
</section>
<?php
get_footer();
