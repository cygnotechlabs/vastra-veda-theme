<?php
/**
 * Full-screen search overlay.
 *
 * @package VastraVeda
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="vv-search" class="vv-overlay vv-overlay--search" hidden>
	<div class="vv-overlay__bar">
		<span class="vv-overlay__eyebrow"><?php esc_html_e( 'Search the collection', 'vastra-veda' ); ?></span>
		<button type="button" class="vv-circle-btn vv-circle-btn--ghost" data-vv-search-close>
			<?php vv_the_icon( 'close', 18 ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close search', 'vastra-veda' ); ?></span>
		</button>
	</div>

	<div class="vv-overlay__body">
		<form role="search" method="get" class="vv-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="vv-search-field"><?php esc_html_e( 'Search for:', 'vastra-veda' ); ?></label>
			<input type="search" id="vv-search-field" class="vv-searchform__input"
				placeholder="<?php esc_attr_e( 'Kanjivaram, Banarasi, Organza…', 'vastra-veda' ); ?>"
				value="<?php echo esc_attr( get_search_query() ); ?>" name="s" autocomplete="off">
			<?php if ( vv_is_woocommerce_active() ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<button type="submit" class="vv-searchform__submit">
				<?php vv_the_icon( 'arrow', 20 ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'vastra-veda' ); ?></span>
			</button>
		</form>

		<?php
		$terms = vv_get_category_terms( 6 );
		if ( $terms ) :
			?>
			<div class="vv-overlay__suggest">
				<span class="vv-eyebrow"><?php esc_html_e( 'Popular', 'vastra-veda' ); ?></span>
				<ul>
					<?php foreach ( $terms as $term ) : ?>
						<li><a href="<?php echo esc_url( $term['url'] ); ?>"><?php echo esc_html( $term['name'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</div>
