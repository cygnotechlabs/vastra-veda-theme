<?php
/**
 * Search form.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
?>
<form role="search" method="get" class="vv-searchform vv-searchform--inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text"><?php esc_html_e( 'Search for:', 'vastra-veda' ); ?></label>
	<input type="search" class="vv-searchform__input" name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search', 'vastra-veda' ); ?>">
	<button type="submit" class="vv-searchform__submit"><?php vv_the_icon( 'arrow', 18 ); ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'vastra-veda' ); ?></span></button>
</form>
