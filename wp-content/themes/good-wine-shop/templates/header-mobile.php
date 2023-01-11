<?php
/**
 * The template to show mobile menu
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile scheme_dark">
	<div class="menu_mobile_inner">
		<a class="menu_mobile_close icon-cancel"></a><?php
	
		// Main menu
		?>
		<nav class="menu_mobile_nav_area">
			<?php
			echo str_replace(
					array('id="menu_main', 'id="menu-', 'class="menu_main'),
					array('id="menu_mobile', 'id="menu_mobile-', 'class="menu_mobile'),
					get_query_var('good_wine_shop_menu_main')
					);
			?>
		</nav><?php
	
		// Search field
		?>
		<div class="search_mobile">
			<div class="search_form_wrap">
				<form role="search" method="get" class="search_form" action="<?php echo esc_url(home_url('/')); ?>">
					<input type="text" class="search_field" placeholder="<?php esc_attr_e('Search ...', 'good-wine-shop'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
					<button type="submit" class="search_submit icon-search" title="<?php esc_attr_e('Start search', 'good-wine-shop'); ?>"></button>
				</form>
			</div>
		</div>
		<?php

        // Login

        do_action('trx_addons_action_login');
		
		// Social icons
		if ( ($output = good_wine_shop_get_socials_links()) != '') {
			?><div class="socials_mobile"><?php good_wine_shop_show_layout($output); ?></div><?php
		}
		?>
	</div>
</div>
