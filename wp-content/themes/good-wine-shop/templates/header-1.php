<?php
/**
 * The template to display "Header 1"
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$header_css = $header_image = '';
$header_video = wp_is_mobile() ? '' : good_wine_shop_get_theme_option('header_video');
if (true || empty($header_video)) {
	$header_image = get_header_image();
	if (good_wine_shop_is_on(good_wine_shop_get_theme_option('header_image_override')) && apply_filters('good_wine_shop_filter_allow_override_header_image', true)) {
		if (is_category()) {
			if (($cat_img = good_wine_shop_get_category_image()) != '')
				$header_image = $cat_img;
		} else if ((is_singular() || good_wine_shop_storage_isset('blog_archive')) && has_post_thumbnail()) {
			$header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			if (is_array($header_image)) $header_image = $header_image[0];
		}
	}
}
$header_css = $header_image!='' ? ' style="background-image: url('.esc_url($header_image).')"' : '';

// Store header image for navi
set_query_var('good_wine_shop_header_image', $header_image || $header_video);

?><header class="top_panel top_panel_style_1<?php
					echo !empty($header_image) || !empty($header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($header_video!='') echo ' with_bg_video';
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (good_wine_shop_is_on(good_wine_shop_get_theme_option('header_fullheight'))) echo ' header_fullheight trx-stretch-height';
					?> scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('header_scheme')) 
													? good_wine_shop_get_theme_option('color_scheme') 
													: good_wine_shop_get_theme_option('header_scheme')); ?>"
			<?php good_wine_shop_show_layout($header_css); ?>>

    <?php

	// Navigation panel
	get_template_part( 'templates/header-navi' );
	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');
	// Header widgets area
	get_template_part( 'templates/header-widgets' );
?></header>