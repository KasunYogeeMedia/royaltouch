<?php
/**
 * The template for displaying 'Header menu'
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$header_image = get_query_var('good_wine_shop_header_image');

$good_wine_shop_menu_header = good_wine_shop_get_nav_menu('menu_header');

// Store menu layout for the mobile menu
set_query_var('good_wine_shop_menu_header', $good_wine_shop_menu_header);

if (!empty($good_wine_shop_menu_header)) {
	?>
	<div class="top_panel_navi_header 
				<?php if ($header_image!='') echo ' with_bg_image'; ?>
				scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('header_scheme')) 
													? good_wine_shop_get_theme_option('color_scheme') 
													: good_wine_shop_get_theme_option('header_scheme')
											); ?>">
		<div class="menu_header_wrap clearfix">
			<div class="content_wrap">
				<?php
				// Top menu
				?><nav class="menu_header_nav_area menu_hover_<?php echo esc_attr(good_wine_shop_get_theme_option('menu_hover')); ?>"><?php
                    good_wine_shop_show_layout($good_wine_shop_menu_header);
				?></nav>
			</div>
		</div>
	</div><!-- /.top_panel_navi_top -->
	<?php
}
?>