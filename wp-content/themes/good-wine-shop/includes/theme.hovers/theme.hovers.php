<?php
/**
 * Generate custom CSS for theme hovers
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('good_wine_shop_hovers_theme_setup3')) {
	add_action( 'after_setup_theme', 'good_wine_shop_hovers_theme_setup3', 3 );
	function good_wine_shop_hovers_theme_setup3() {
		// Add 'Menu hover' option
		good_wine_shop_storage_set_array_after('options', 'menu_cache', array(
			'menu_hover' => array(
				"title" => esc_html__('Menu hover', 'good-wine-shop'),
				"desc" => wp_kses_data( __('Select hover effect to decorate main menu', 'good-wine-shop') ),
				"std" => 'fade',
				"options" => array(
					'fade'			=> esc_html__('Fade',		'good-wine-shop'),
					'fade_box'		=> esc_html__('Fade Box',	'good-wine-shop'),
					'slide_line'	=> esc_html__('Slide Line',	'good-wine-shop'),
					'slide_box'		=> esc_html__('Slide Box',	'good-wine-shop'),
					'zoom_line'		=> esc_html__('Zoom Line',	'good-wine-shop'),
					'path_line'		=> esc_html__('Path Line',	'good-wine-shop'),
					'roll_down'		=> esc_html__('Roll Down',	'good-wine-shop'),
					'color_line'	=> esc_html__('Color Line',	'good-wine-shop'),
				),
				"type" => "select"
				),
			'menu_animation_in' => array( 
				"title" => esc_html__('Submenu show animation', 'good-wine-shop'),
				"desc" => wp_kses_data( __('Select animation to show submenu ', 'good-wine-shop') ),
				"std" => "fadeInUpSmall",
				"options" => good_wine_shop_get_list_animations_in(),
				"type" => "select"
				),
			'menu_animation_out' => array( 
				"title" => esc_html__('Submenu hide animation', 'good-wine-shop'),
				"desc" => wp_kses_data( __('Select animation to hide submenu ', 'good-wine-shop') ),
				"std" => "fadeOutDownSmall",
				"options" => good_wine_shop_get_list_animations_out(),
				"type" => "select"
				)
			)
		);
		// Add 'Buttons hover' option
		good_wine_shop_storage_set_array_before('options', 'sidebar_widgets', array(
			'button_hover' => array(
				"title" => esc_html__("Button's hover", 'good-wine-shop'),
				"desc" => wp_kses_data( __('Select hover effect to decorate all theme buttons', 'good-wine-shop') ),
				"std" => 'slide_left',
				"options" => array(
					'fade'			=> esc_html__('Fade',				'good-wine-shop'),
					'slide_left'	=> esc_html__('Slide from Left',	'good-wine-shop'),
					'slide_right'	=> esc_html__('Slide from Right',	'good-wine-shop'),
					'slide_top'		=> esc_html__('Slide from Top',		'good-wine-shop'),
					'slide_bottom'	=> esc_html__('Slide from Bottom',	'good-wine-shop'),
				),
				"type" => "select"
			),
			'image_hover' => array(
				"title" => esc_html__("Image's hover", 'good-wine-shop'),
				"desc" => wp_kses_data( __('Select hover effect to decorate all theme images', 'good-wine-shop') ),
				"std" => 'icon',
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'good-wine-shop')
				),
				"options" => array(
					'dots'	=> esc_html__('Dots',	'good-wine-shop'),
					'icon'	=> esc_html__('Icon',	'good-wine-shop'),
					'icons'	=> esc_html__('Icons',	'good-wine-shop'),
					'zoom'	=> esc_html__('Zoom',	'good-wine-shop'),
					'fade'	=> esc_html__('Fade',	'good-wine-shop'),
					'slide'	=> esc_html__('Slide',	'good-wine-shop'),
					'pull'	=> esc_html__('Pull',	'good-wine-shop'),
					'border'=> esc_html__('Border',	'good-wine-shop')
				),
				"type" => "select"
			) )
		);
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_hovers_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_hovers_theme_setup9', 9 );
	function good_wine_shop_hovers_theme_setup9() {
		add_action( 'wp_enqueue_scripts',		'good_wine_shop_hovers_frontend_scripts', 1010 );
		add_filter( 'good_wine_shop_filter_localize_script','good_wine_shop_hovers_localize_script' );
		add_filter( 'good_wine_shop_filter_merge_scripts',	'good_wine_shop_hovers_merge_scripts' );
		add_filter( 'good_wine_shop_filter_merge_styles',	'good_wine_shop_hovers_merge_styles' );
		add_filter( 'good_wine_shop_filter_get_css', 		'good_wine_shop_hovers_get_css', 10, 3 );
	}
}
	
// Enqueue hover styles and scripts
if ( !function_exists( 'good_wine_shop_hovers_frontend_scripts' ) ) {
	
	function good_wine_shop_hovers_frontend_scripts() {
		if ( good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('includes/theme.hovers/jquery.slidemenu.js')) && in_array(good_wine_shop_get_theme_option('menu_hover'), array('slide_line', 'slide_box')) )
            wp_enqueue_script( 'slidemenu', good_wine_shop_get_file_url('includes/theme.hovers/jquery.slidemenu.js'), array('jquery') );
		if ( good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('includes/theme.hovers/theme.hovers.js')) )
            wp_enqueue_script( 'good-wine-shop-hovers', good_wine_shop_get_file_url('includes/theme.hovers/theme.hovers.js'), array('jquery') );
		if ( good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('includes/theme.hovers/theme.hovers.css')) )
            wp_enqueue_style( 'good-wine-shop-hovers',  good_wine_shop_get_file_url('includes/theme.hovers/theme.hovers.css'), array(), null );
	}
}

// Merge hover effects into single js
if (!function_exists('good_wine_shop_hovers_merge_scripts')) {
	
	function good_wine_shop_hovers_merge_scripts($js) {
		return $js
				. good_wine_shop_fgc(good_wine_shop_get_file_dir('includes/theme.hovers/jquery.slidemenu.js'))
				. good_wine_shop_fgc(good_wine_shop_get_file_dir('includes/theme.hovers/theme.hovers.js'));
	}
}

// Merge hover effects into single css
if (!function_exists('good_wine_shop_hovers_merge_styles')) {
	
	function good_wine_shop_hovers_merge_styles($css) {
		return $css
				. good_wine_shop_fgc(good_wine_shop_get_file_dir('includes/theme.hovers/theme.hovers.css'));
	}
}

// Add hover effect's vars into localize array
if (!function_exists('good_wine_shop_hovers_localize_script')) {
	
	function good_wine_shop_hovers_localize_script($arr) {
		$arr['menu_hover'] = good_wine_shop_get_theme_option('menu_hover');
		$arr['menu_hover_color'] = good_wine_shop_get_scheme_color('text_hover', good_wine_shop_get_theme_option( 'menu_scheme' ));
		$arr['button_hover'] = good_wine_shop_get_theme_option('button_hover');
		return $arr;
	}
}

// Add hover icons on the featured image
if ( !function_exists('good_wine_shop_hovers_add_icons') ) {
	function good_wine_shop_hovers_add_icons($hover, $args=array()) {

		// Additional parameters
		$args = array_merge(array(
			'image' => null
		), $args);
	
		// Hover style 'Icons and 'Zoom'
		if (in_array($hover, array('icons', 'zoom'))) {
			if ($args['image'])
				$large_image = $args['image'];
			else {
				$attachment = wp_get_attachment_image_src( get_post_thumbnail_id(), 'masonry-big' );
				if (!empty($attachment[0]))
					$large_image = $attachment[0];
			}
			?>
			<div class="icons">
				<a href="<?php esc_url(the_permalink()); ?>" aria-hidden="true" class="icon-link<?php if (empty($large_image)) echo ' single_icon'; ?>"></a>
				<?php if (!empty($large_image)) { ?>
				<a href="<?php echo esc_url($large_image); ?>" aria-hidden="true" class="icon-search" title="<?php the_title_attribute(); ?>"></a>
				<?php } ?>
			</div>
			<?php
	
		// Hover style 'Shop'
		} else if ($hover == 'shop') {
			global $product;
			?>
			<div class="icons sc_item_button">
                <a href="<?php esc_url(the_permalink()); ?>" aria-hidden="true" class="shop_link sc_button sc_button_default sc_button_size_small"><?php echo esc_html__('Details', 'good-wine-shop') ?></a>
				<?php
				if (!is_object($args['cat']) && $product->is_purchasable() && $product->is_in_stock()) {
					echo apply_filters( 'woocommerce_loop_add_to_cart_link',
										'<a rel="nofollow" href="' . esc_url($product->add_to_cart_url()) . '" 
														aria-hidden="true" 
														data-quantity="1" 
														data-product_id="' . esc_attr( $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id() ) . '"
														data-product_sku="' . esc_attr( $product->get_sku() ) . '"
														class="shop_cart add_to_cart_button sc_button sc_button_default sc_button_size_small'
																. ' product_type_' . $product->get_type()
																. ($product->supports( 'ajax_add_to_cart' ) ? ' ajax_add_to_cart' : '')
																. '">' . esc_html__('Add to Cart', 'good-wine-shop') . '</a>',
										$product );
				}
				?>
			</div>
			<?php

		// Hover style 'Icon'
		} else if ($hover == 'icon') {
			?><div class="icons"><a href="<?php esc_url(the_permalink()); ?>" aria-hidden="true" class="icon-glass"></a></div><?php

		// Hover style 'Dots'
		} else if ($hover == 'dots') {
			?><a href="<?php esc_url(the_permalink()); ?>" aria-hidden="true" class="icons"><span></span><span></span><span></span></a><?php

		// Hover style 'Fade', 'Slide', 'Pull', 'Border'
		} else if (in_array($hover, array('fade', 'pull', 'slide', 'border'))) {
			?>
			<div class="post_info">
				<div class="post_info_back">
					<h4 class="post_title"><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h4>
					<div class="post_descr">
						<?php
						good_wine_shop_show_post_meta(array(
									'categories' => false,
									'date' => true,
									'edit' => false,
									'seo' => false,
									'share' => false,
									'counters' => 'comments,views',
									'echo' => true
									));
						// Remove the condition below if you want display excerpt
						if (false) {
							?><div class="post_excerpt"><?php the_excerpt(); ?></div><?php
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}

// Add styles into CSS
if ( !function_exists( 'good_wine_shop_hovers_get_css' ) ) {
	
	function good_wine_shop_hovers_get_css($css, $colors, $fonts) {
		if (isset($css['colors'])) {
			$css['fonts'] .= <<<CSS
CSS;
		}

		if (isset($css['colors'])) {
			$css['colors'] .= <<<CSS

/* ================= MAIN MENU ITEM'S HOVERS ==================== */

/* fade box */
.menu_hover_fade_box .menu_main_nav > a:hover,
.menu_hover_fade_box .menu_main_nav > li > a:hover,
.menu_hover_fade_box .menu_main_nav > li.sfHover > a {
	color: {$colors['alter_link']};
	background-color: {$colors['alter_bg_color']};
}

/* slide_line */
.menu_hover_slide_line .menu_main_nav > li#blob {
	background-color: {$colors['text_link']};
}

/* slide_box */
.menu_hover_slide_box .menu_main_nav > li#blob {
	background-color: {$colors['alter_bg_color']};
}

/* zoom_line */
.menu_hover_zoom_line .menu_main_nav > li > a:before {
	background-color: {$colors['text_link']};
}

/* path_line */
.menu_hover_path_line .menu_main_nav > li:before,
.menu_hover_path_line .menu_main_nav > li:after,
.menu_hover_path_line .menu_main_nav > li > a:before,
.menu_hover_path_line .menu_main_nav > li > a:after {
	background-color: {$colors['text_link']};
}

/* roll_down */
.menu_hover_roll_down .menu_main_nav > li > a:before {
	background-color: {$colors['text_link']};
}

/* color_line */
.menu_hover_color_line .menu_main_nav > li > a:before {
	background-color: {$colors['text_dark']};
}
.menu_hover_color_line .menu_main_nav > li > a:after,
.menu_hover_color_line .menu_main_nav > li.menu-item-has-children > a:after {
	background-color: {$colors['text_link']};
}
.menu_hover_color_line .menu_main_nav > li.sfHover > a,
.menu_hover_color_line .menu_main_nav > li > a:hover,
.menu_hover_color_line .menu_main_nav > li > a:focus {
	color: {$colors['text_link']};
}


/* ================= BUTTON'S HOVERS ==================== */

/* Slide */
.sc_button_hover_slide_left, .eg-good-wine-product-wrapper .added_to_cart {	background: linear-gradient(to right,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.sc_button_hover_slide_right {  background: linear-gradient(to left,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.sc_button_hover_slide_top {	background: linear-gradient(to bottom,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }


.sc_promo_button .sc_button_hover_slide_left,
.sc_content_colors_light .sc_button_hover_slide_left,
.slider_style_special .sc_button_hover_slide_left,
.slider_engine_revo .sc_button_hover_slide_left {
    background: linear-gradient(to right,	rgba(0,0,0,0) 50%, {$colors['accent2']} 50%) no-repeat scroll right bottom / 200% 110% rgba(0, 0, 0, 0);
}
.sc_promo_button .sc_button_hover_slide_right,
.sc_content_colors_light .sc_button_hover_slide_right,
.slider_style_special .sc_button_hover_slide_right,
.slider_engine_revo .sc_button_hover_slide_right {
    background: linear-gradient(to left,	rgba(0,0,0,0) 50%, {$colors['accent2']} 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0);
}
.sc_promo_button .sc_button_hover_slide_top,
.sc_content_colors_light .sc_button_hover_slide_top,
.slider_style_special .sc_button_hover_slide_top,
.slider_engine_revo .sc_button_hover_slide_top {
    background: linear-gradient(to bottom,	rgba(0,0,0,0) 50%, {$colors['accent2']} 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0);
}
.sc_promo_button .sc_button_hover_slide_bottom,
.sc_content_colors_light .sc_button_hover_slide_bottom,
.slider_style_special .sc_button_hover_slide_bottom,
.slider_engine_revo .sc_button_hover_slide_bottom {
    background: linear-gradient(to top,		rgba(0,0,0,0) 50%, {$colors['accent2']} 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0);
}

form button.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 110% rgba(0, 0, 0, 0); }
form button.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
form button.sc_button_hover_slide_top {		background: linear-gradient(to bottom,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
form button.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }


/* ================= IMAGE'S HOVERS ==================== */

/* Common styles */
.post_featured .mask,
.format-video .post_featured.with_thumb .mask {
	background-color: {$colors['text_dark_alpha_03']};
}

/* Dots */
.post_featured.hover_dots .icons span {
	background-color: {$colors['text_link']};
}
.post_featured.hover_dots .post_info {
	color: {$colors['inverse_text']};
}

/* Icon */
.post_featured.hover_icon .icons a {
	color: {$colors['text_link']};
}
.post_featured.hover_icon a:hover {
	color: {$colors['inverse_text']};
}

/* Icon and Icons */
.post_featured.hover_icons .icons a {
	background-color: {$colors['bg_color_alpha']};
	color: {$colors['text_dark']};
}
.post_featured.hover_icons a:hover {
	background-color: {$colors['bg_color']};
	color: {$colors['text_link']};
}

/* Fade */
.post_featured.hover_fade .post_info,
.post_featured.hover_fade .post_info a,
.post_featured.hover_fade .post_info .post_meta_item,
.post_featured.hover_fade .post_info .post_meta .post_meta_item:before,
.post_featured.hover_fade .post_info .post_meta .post_meta_item:hover:before {
	color: {$colors['inverse_text']};
}
.post_featured.hover_fade .post_info a:hover {
	color: {$colors['text_link']};
}

/* Slide */
.post_featured.hover_slide .post_info,
.post_featured.hover_slide .post_info a,
.post_featured.hover_slide .post_info .post_meta_item,
.post_featured.hover_slide .post_info .post_meta .post_meta_item:before,
.post_featured.hover_slide .post_info .post_meta .post_meta_item:hover:before {
	color: {$colors['inverse_text']};
}
.post_featured.hover_slide .post_info a:hover {
	color: {$colors['text_link']};
}
.post_featured.hover_slide .post_info .post_title:after {
	background-color: {$colors['inverse_text']};
}

/* Pull */
.post_featured.hover_pull .post_info,
.post_featured.hover_pull .post_info a {
	color: {$colors['inverse_text']};
}
.post_featured.hover_pull .post_info a:hover {
	color: {$colors['text_link']};
}
.post_featured.hover_pull .post_info .post_descr {
	background-color: {$colors['text_dark']};
}

/* Border */
.post_featured.hover_border .post_info,
.post_featured.hover_border .post_info a,
.post_featured.hover_border .post_info .post_meta_item,
.post_featured.hover_border .post_info .post_meta .post_meta_item:before,
.post_featured.hover_border .post_info .post_meta .post_meta_item:hover:before {
	color: {$colors['inverse_text']};
}
.post_featured.hover_border .post_info a:hover {
	color: {$colors['text_link']};
}
.post_featured.hover_border .post_info:before,
.post_featured.hover_border .post_info:after {
	border-color: {$colors['inverse_text']};
}

/* Shop */
.post_featured.hover_shop .icons a {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']} !important;
	background-color:  {$colors['bg_color']};
}
.post_featured.hover_shop .icons a:hover {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']} !important;
	background-color: {$colors['text_link']};
}

CSS;
		}
		
		return $css;
	}
}
?>