<?php
/**
 * The template for displaying main menu
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */
$header_image = get_query_var('good_wine_shop_header_image');
?>
<div class="top_panel_fixed_wrap"></div>
<div class="top_panel_navi 
			<?php if ($header_image!='') echo ' with_bg_image'; ?>
			scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('menu_scheme')) 
												? (good_wine_shop_is_inherit(good_wine_shop_get_theme_option('header_scheme')) 
													? good_wine_shop_get_theme_option('color_scheme') 
													: good_wine_shop_get_theme_option('header_scheme')) 
												: good_wine_shop_get_theme_option('menu_scheme')); ?>"><?php
    // User header
    get_template_part( 'templates/user-header' ); ?>

	<div class="menu_main_wrap clearfix">
		<div class="content_wrap">
			<?php
            // Side menu
            if (good_wine_shop_get_theme_option('menu_style') == 'side') {
                get_template_part( 'templates/header-navi-side' );
            }
			// Logo
			get_template_part( 'templates/header-logo' );
            // Mobile menu button
            ?><a class="menu_mobile_button"></a><?php

			if (good_wine_shop_get_theme_option("menu_style") != 'side') {
				// Main menu
				?><nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(good_wine_shop_get_theme_option('menu_hover')); ?>"><?php
					$good_wine_shop_menu_main = good_wine_shop_get_nav_menu('menu_main');
					if (empty($good_wine_shop_menu_main)) $good_wine_shop_menu_main = good_wine_shop_get_nav_menu();
                    good_wine_shop_show_layout($good_wine_shop_menu_main);
					// Store menu layout for the mobile menu
					set_query_var('good_wine_shop_menu_main', $good_wine_shop_menu_main);
					// Display search field
					set_query_var('good_wine_shop_search_in_header', true);
					get_template_part( 'templates/search-field' );
				?></nav><?php
			}
			?>
		</div>
	</div>
</div><!-- /.top_panel_navi -->