<?php
/**
 * The template for displaying side menu
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */
?>
<div class="menu_side_wrap scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('menu_scheme')) 
																	? (good_wine_shop_is_inherit(good_wine_shop_get_theme_option('header_scheme')) 
																		? good_wine_shop_get_theme_option('color_scheme') 
																		: good_wine_shop_get_theme_option('header_scheme')) 
																	: good_wine_shop_get_theme_option('menu_scheme')); ?>">
	<div class="menu_side_inner">
		<a class="menu_mobile_button menu_mobile_button_text"><?php esc_html_e('MENU', 'good-wine-shop'); ?></a>
		<?php
		// Main menu
		$good_wine_shop_menu_main = good_wine_shop_get_nav_menu('menu_main');
		if (empty($good_wine_shop_menu_main)) $good_wine_shop_menu_main = good_wine_shop_get_nav_menu();
		// Store menu layout for the mobile menu
		set_query_var('good_wine_shop_menu_main', $good_wine_shop_menu_main);
		?>
	</div>
</div><!-- /.menu_side_wrap -->