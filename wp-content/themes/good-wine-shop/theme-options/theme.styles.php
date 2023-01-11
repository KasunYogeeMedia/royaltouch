<?php
/**
 * Generate custom CSS
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */


			
// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the theme.customizer.color-scheme.js
if (!function_exists('good_wine_shop_customizer_add_theme_colors')) {
	function good_wine_shop_customizer_add_theme_colors($colors) {
		if (substr($colors['text'], 0, 1) == '#') {
			$colors['text_dark_mask']  = good_wine_shop_hex2rgba( $colors['text_dark'], 0.5 );
			$colors['text_dark_alpha'] = good_wine_shop_hex2rgba( $colors['text_dark'], 0.2 );
            $colors['text_dark_alpha_03'] = good_wine_shop_hex2rgba( $colors['text_dark'], 0.3 );
            $colors['text_dark_alpha_07'] = good_wine_shop_hex2rgba( $colors['text_dark'], 0.7 );
			$colors['text_link_alpha'] = good_wine_shop_hex2rgba( $colors['text_link'], 0.6 );
			$colors['text_link_alpha_02'] = good_wine_shop_hex2rgba( $colors['text_link'], 0.2 );
			$colors['bd_color_alpha']  = good_wine_shop_hex2rgba( $colors['bd_color'],  0.5 );
			$colors['bg_color_alpha']  = good_wine_shop_hex2rgba( $colors['bg_color'],  0.85 );
			$colors['bg_color_alpha_0']  = good_wine_shop_hex2rgba( $colors['bg_color'],  0 );
            $colors['bg_color_alpha_03']  = good_wine_shop_hex2rgba( $colors['bg_color'],  0.3 );
            $colors['bg_color_alpha_07']  = good_wine_shop_hex2rgba( $colors['bg_color'],  0.7 );
            $colors['alter_bd_color_alpha_05']  = good_wine_shop_hex2rgba( $colors['alter_bd_color'],  0.5 );
		} else {
			$colors['text_dark_mask']  = '{{ data.text_dark_mask }}';
            $colors['text_dark_alpha'] = '{{ data.text_dark_alpha }}';
            $colors['text_dark_alpha_03'] = '{{ data.text_dark_alpha_03 }}';
            $colors['text_dark_alpha_07'] = '{{ data.text_dark_alpha_07 }}';
			$colors['text_link_alpha'] = '{{ data.text_link_alpha }}';
			$colors['text_link_alpha_02'] = '{{ data.text_link_alpha_02 }}';
			$colors['bd_color_alpha']  = '{{ data.bd_color_alpha }}';
			$colors['bg_color_alpha']  = '{{ data.bg_color_alpha }}';
			$colors['bg_color_alpha_0']  = '{{ data.bg_color_alpha_0 }}';
            $colors['bg_color_alpha_03']  = '{{ data.bg_color_alpha_03 }}';
            $colors['bg_color_alpha_07']  = '{{ data.bg_color_alpha_07 }}';
            $colors['alter_bd_color_alpha_05']  = '{{ data.alter_bd_color_alpha_05 }}';
		}
		return $colors;
	}
}



// Return CSS with custom colors and fonts
if (!function_exists('good_wine_shop_customizer_get_css')) {

	function good_wine_shop_customizer_get_css($colors=null, $fonts=null, $minify=true, $only_scheme='') {

		$css = $rez = array(
			'fonts' => '',
			'colors' => ''
		);
		
		// Prepare fonts
		if ($fonts===null)	$fonts = good_wine_shop_get_theme_fonts();
		if ($fonts) {
			$tags = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'link', 'info', 'menu', 'submenu', 'logo', 'slogan', 'button', 'input', 'decor');
			foreach ($tags as $tag) {
				if (!isset($fonts[$tag]))
					$fonts[$tag] = array( 'family' => 'inherit');
			}
			
			$rez['fonts'] = <<<FONTS

body {	font-family: {$fonts['p']['family']}; }

h1 {	font-family: {$fonts['h1']['family']};}
h2 {	font-family: {$fonts['h2']['family']};}
h3 {	font-family: {$fonts['h3']['family']};}
h4 {	font-family: {$fonts['h4']['family']};}
h5 {	font-family: {$fonts['h5']['family']};}
h6 {	font-family: {$fonts['h6']['family']};}

a {		font-family: {$fonts['link']['family']};}

input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
textarea,
.select_container,
.select_container select {
	font-family: {$fonts['input']['family']};
}
.post_date, .post_meta_item, .post_counters_item,
.post_item .more-link,
.comments_list_wrap .comment_date,
.comments_list_wrap .comment_time,
.comments_list_wrap .comment_counters,
.comments_list_wrap .comment_reply a,
.widget_area .post_item .post_info .post_info_item,
aside .post_item .post_info .post_info_item,
.widget_area .post_item .post_info .post_info_item a,
aside .post_item .post_info .post_info_item a,
.widget_area .post_info_counters .post_counters_item,
aside .post_info_counters .post_counters_item,
.widget_area .post-date,
aside .post-date,
.widget_area .rss-date,
aside .rss-date {
	font-family: {$fonts['info']['family']};
}

.logo,
.logo_footer_text {
	font-family: {$fonts['logo']['family']};
}

.menu_main_nav > li,
.menu_main_nav > li > a,
.menu_header_nav > li,
.menu_header_nav > li > a,
.menu_mobile .menu_mobile_nav_area > ul > li,
.menu_mobile .menu_mobile_nav_area > ul > li > a {
	font-family: {$fonts['menu']['family']};
}
.menu_main_nav > li li,
.menu_main_nav > li li > a,
.menu_header_nav > li li,
.menu_header_nav > li li > a,
.menu_mobile .menu_mobile_nav_area > ul > li li,
.menu_mobile .menu_mobile_nav_area > ul > li li > a {
	font-family: {$fonts['submenu']['family']};
}

.breadcrumbs,
blockquote {
	font-family: {$fonts['h6']['family']};
}

button,
input[type="button"],
input[type="reset"],
input[type="submit"],
table > thead > tr, table > body > tr:first-child, table th,
.widget_product_tag_cloud a,
.widget_tag_cloud a,
.widget_area .post_item .post_categories,
aside .post_item .post_categories,
.format-audio .post_featured .post_audio_title,
.related_wrap .post_categories,
.related_wrap .post_date,
.nav-links, .page_links, .nav-links-old, .nav-links-more,
.good_wine_shop_tabs .good_wine_shop_tabs_titles,
.comments_list_wrap .comment_posted,
.menu_footer_nav_area ul li {
	font-family: {$fonts['h5']['family']};
}

aside li,
.wp-block-calendar th,
.widget_calendar th,
figure figcaption,
.single .nav-links,
.wp-caption .wp-caption-text,
.wp-caption .wp-caption-dd,
.wp-caption-overlay .wp-caption .wp-caption-text,
.wp-caption-overlay .wp-caption .wp-caption-dd {
	font-family: {$fonts['h6']['family']};
}

FONTS;
		}

		$schemes = empty($only_scheme) ? array_keys(good_wine_shop_get_list_schemes()) : array($only_scheme);
		if (count($schemes) > 0) {
			$step = 1;
			foreach ($schemes as $scheme) {
				// Prepare colors
				if (empty($only_scheme)) $colors = good_wine_shop_get_scheme_colors($scheme);
		
				// Make theme-specific colors and tints
				$colors = good_wine_shop_customizer_add_theme_colors($colors);
		
		        // Make styles
				$rez['colors'] = <<<CSS

/* Common tags */
h1, h1 a {
    color: {$colors['text_dark']};
}
.page_title h1,
.page_title h1 a {
    color: {$colors['text_link']};
}
h1 a:hover {
    color: {$colors['text_hover']};
}
.single .page_title h1 {
    color: {$colors['text_dark']};
}

h2, h3, h4, h5, h6,
h2 a, h3 a, h4 a, h5 a, h6 a {
	color: {$colors['text_dark']};
}
h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {
	color: {$colors['text_link']};
}

dt, b, strong/*, i, em*/ {	
	color: {$colors['text_dark']};
}
s, strike, del {	
	color: {$colors['text_light']};
}

a {
	color: {$colors['text_link']};
}
a:hover {
	color: {$colors['text_hover']};
}

blockquote {
	color: {$colors['text_dark']};
}
blockquote cite,
blockquote a,
aside blockquote a {
	color: {$colors['text_link']};
}
blockquote a:hover,
aside blockquote a:hover {
	color: {$colors['text_hover']};
}

table {
	color: {$colors['text']};
}
table td strong {
	color: {$colors['text_dark']};
}
td {
	border-color: {$colors['text_link']};
}
table > thead > tr, table > body > tr:first-child, table th {
	color: {$colors['text_link']};
}

figure figcaption,
.wp-caption .wp-caption-text,
.wp-caption .wp-caption-dd,
.wp-caption-overlay .wp-caption .wp-caption-text,
.wp-caption-overlay .wp-caption .wp-caption-dd {
	color: {$colors['input_light']};
	background-color: {$colors['text_dark']};
}
ul > li:before {
	color: {$colors['text_link']};
}


/* Form fields */

button[disabled],
input[type="submit"][disabled],
input[type="button"][disabled],
.comments_wrap .form-submit input[type="submit"][disabled]{
    background-color: {$colors['text_light']} !important;
    color: {$colors['text']} !important;
}

fieldset {
	border-color: {$colors['bd_color']};
}
fieldset legend {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}
input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
.select_container,
textarea {
	color: {$colors['input_light']};
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
.widget_search form {
    color: {$colors['text_dark']};
	background-color: {$colors['input_bg_color']};
}
.widget_search input[type="search"],
.widget_search input[type="text"] {
    border-color: {$colors['input_text']};
    color: {$colors['text_dark']};
}
.select_container select {
	color: {$colors['input_light']};
}
.select_container {
	border-color: {$colors['input_bd_color']};
}
.select2-container .select2-choice {
	border-color: {$colors['input_bd_color']};
}
input[type="text"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
input[type="search"]:focus,
input[type="password"]:focus,
.select_container:hover,
.select2-container .select2-choice:hover,
textarea:focus {
	color: {$colors['input_dark']};
	border-color: {$colors['input_bd_hover']};
	background-color: {$colors['input_bg_hover']};
}
.select_container select:focus {
	color: {$colors['input_dark']};
	border-color: {$colors['input_bd_hover']};
}
.select_container:after {
	color: {$colors['input_light']};
	border-color: {$colors['input_light']};
}
.select_container:hover:after {
	color: {$colors['input_dark']};
	border-color: {$colors['input_dark']};
}
.widget_search form:hover:after {
	color: {$colors['text_link']};
}
input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
	color: {$colors['text_dark']};
}
.widget_search input::-webkit-input-placeholder {
    color: {$colors['text_dark']};
}
.widget_search input::-moz-placeholder {
    color: {$colors['text_dark']};
}
.widget_search input:-moz-placeholder {
    color: {$colors['text_dark']};
}
.widget_search input:-ms-input-placeholder {
    color: {$colors['text_dark']};
}
input[type="radio"] + label:before,
input[type="checkbox"] + label:before {
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
button,
input[type="reset"],
input[type="submit"],
input[type="button"] {
	border-color: {$colors['text_dark']};
	background-color: transparent;
	color: {$colors['text_dark']};
}
input[type="submit"]:not([class*="sc_button_hover_"]):hover,
input[type="reset"]:not([class*="sc_button_hover_"]):hover,
input[type="button"]:not([class*="sc_button_hover_"]):hover,
button:not([class*="sc_button_hover_"]):hover,
input[type="submit"]:not([class*="sc_button_hover_"]):focus,
input[type="reset"]:not([class*="sc_button_hover_"]):focus,
input[type="button"]:not([class*="sc_button_hover_"]):focus,
button:not([class*="sc_button_hover_"]):focus {
	border-color: {$colors['text_link']};
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.trx_addons_popup_form_field_submit input[type="submit"].submit_button {
    border-color: {$colors['text_dark']};
	color: {$colors['text_dark']};
}
.trx_addons_popup_form_field_submit input[type="submit"].submit_button:hover {
    color: {$colors['inverse_text']};
    border-color: {$colors['text_link']};
}


/* WP Standard classes */
.sticky {
	border-color: {$colors['bd_color']};
}
.sticky .label_sticky {
	border-top-color: {$colors['text_link']};
}
	

/* Page */
body {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}
#page_preloader,
.scheme_self.header_position_under .page_content_wrap,
.page_wrap {
	background-color: {$colors['bg_color']};
}
.preloader_wrap > div {
	background-color: {$colors['text_link']};
}

/* Header */
.user-header {
    background-color: {$colors['input_dark']};
    color: {$colors['bg_color_alpha_07']};
}
.user-header a {
    color: {$colors['inverse_text']};
}
.user-header a:hover {
    color: {$colors['text_link']};
}
.user-header_cart .cart_summa {
    color: {$colors['text_link']};
}
.user-header_cart .sidebar_cart,
.menu_main_cart .sidebar_cart {
    background-color: {$colors['bg_color']};
    border-color: {$colors['text_link']};
}
.user-header_cart .sidebar_cart:before,
.menu_main_cart .sidebar_cart:before {
    border-bottom-color: {$colors['text_link']};
}
.user-header_cart .sidebar_cart .cart_list li,
.menu_main_cart .sidebar_cart .cart_list li {
    border-bottom-color: {$colors['bd_color']};
    color: {$colors['text_dark']};
}
.menu_main_cart .top_panel_cart_button {
    color: {$colors['inverse_text']};
}
.menu_main_cart .top_panel_cart_button:hover {
    color: {$colors['text_link']};
}
.menu_main_cart .cart_items {
    color: {$colors['inverse_text']};
}
.menu_main_cart .contact_cart_totals {
    background-color: {$colors['text_link']};
}
.scheme_self.top_panel {
    background-color: {$colors['text_dark']};
}

.user-header .sidebar_cart a,
.woocommerce .user-header_cart .sidebar_cart span.amount,
.woocommerce-page .user-header_cart .sidebar_cart span.amount{
    color: {$colors['text_link']};
}
.user-header .sidebar_cart a:hover {
    color: {$colors['text_dark']};
}

/* Logo */
.logo,
.logo:hover {
    color: {$colors['text_link']};
}
.logo b {
	color: {$colors['text_dark']};
}
.logo i {
	color: {$colors['text_link']};
}
.logo_text {
	color: {$colors['inverse_text']};
}
.logo:hover .logo_text {
	color: {$colors['text_link']};
}
.logo_slogan {
	color: {$colors['text_link']};
}

/* Social items */
.socials_wrap .social_item a,
.socials_wrap .social_item a i {
	color: {$colors['text_light']};
}
.socials_wrap .social_item a:hover,
.socials_wrap .social_item a:hover i {
	color: {$colors['text_dark']};
}

/* Search */
.search_wrap .search_field {
	color: {$colors['text']};
}
.search_wrap .search_field:focus {
	color: {$colors['text_dark']};
}
.search_wrap .search_submit {
	color: {$colors['inverse_text']};
}
.search_wrap .search_submit:hover,
.search_wrap .search_submit:focus {
	color: {$colors['inverse_link']};
}

.post_item_none_search .search_wrap .search_submit, 
.post_item_none_search .search_wrap .search_submit,
.post_item_none_archive .search_wrap .search_submit, 
.post_item_none_archive .search_wrap .search_submit {
	color: {$colors['text_dark']};
	background-color: transparent !important;
}
.post_item_none_search .search_wrap .search_submit:hover, 
.post_item_none_search .search_wrap .search_submit:focus,
.post_item_none_archive .search_wrap .search_submit:hover, 
.post_item_none_archive .search_wrap .search_submit:focus {
	color: {$colors['text_link']};
	background-color: transparent !important;
}


/* Search style 'Expand' */
.search_style_expand.search_opened {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.search_style_expand.search_opened .search_submit {
	color: {$colors['text']};
}
.search_style_expand .search_submit:hover,
.search_style_expand .search_submit:focus {
	color: {$colors['inverse_link']};
}

/* Search style 'Fullscreen' */
.search_style_fullscreen.search_opened .search_form_wrap {
	background-color: {$colors['bg_color_alpha']};
}
.search_style_fullscreen.search_opened .search_form {
	border-color: {$colors['text_dark']};
}
.search_style_fullscreen.search_opened .search_close,
.search_style_fullscreen.search_opened .search_field,
.search_style_fullscreen.search_opened .search_submit {
	color: {$colors['input_dark']};
}
.search_style_fullscreen.search_opened .search_close:hover,
.search_style_fullscreen.search_opened .search_field:hover,
.search_style_fullscreen.search_opened .search_field:focus,
.search_style_fullscreen.search_opened .search_submit:hover,
.search_style_fullscreen.search_opened .search_submit:focus {
	color: {$colors['input_text']};
}
.search_style_fullscreen.search_opened input::-webkit-input-placeholder {color:{$colors['input_light']}; opacity: 1;}
.search_style_fullscreen.search_opened input::-moz-placeholder          {color:{$colors['input_light']}; opacity: 1;}/* Firefox 19+ */
.search_style_fullscreen.search_opened input:-moz-placeholder           {color:{$colors['input_light']}; opacity: 1;}/* Firefox 18- */
.search_style_fullscreen.search_opened input:-ms-input-placeholder      {color:{$colors['input_light']}; opacity: 1;}

/* Search results */
.search_wrap .search_results {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
}
.search_wrap .search_results:after {
	background-color: {$colors['bg_color']};
	border-left-color: {$colors['bd_color']};
	border-top-color: {$colors['bd_color']};
}
.search_wrap .search_results .search_results_close {
	color: {$colors['text_light']};
}
.search_wrap .search_results .search_results_close:hover {
	color: {$colors['text_dark']};
}
.search_results.widget_area .post_item + .post_item {
	border-top-color: {$colors['bd_color']};
}


/* Main menu */
.menu_header_nav > li > a,
.menu_main_nav > li > a {
	color: {$colors['inverse_text']};
}
.menu_header_nav > li > a:hover,
.menu_header_nav > li.sfHover > a,
.menu_header_nav > li.current-menu-item > a,
.menu_header_nav > li.current-menu-parent > a,
.menu_header_nav > li.current-menu-ancestor > a,
.menu_main_nav > li > a:hover,
.menu_main_nav > li.sfHover > a,
.menu_main_nav > li.current-menu-item > a,
.menu_main_nav > li.current-menu-parent > a,
.menu_main_nav > li.current-menu-ancestor > a {
	color: {$colors['text_link']};
}
.menu_header_nav > li+li:before,
.menu_main_nav > li+li:before {
	border-color: {$colors['inverse_text']};
}
.menu_header_nav > li+li a:before,
.menu_main_nav > li+li a:before {
	border-color: {$colors['bg_color_alpha_03']};
}
/* Submenu */
.menu_main_nav > li ul {
	background-color: {$colors['bg_color']};
	border-color: {$colors['text_link']};
}
.menu_main_nav > li > ul:before {
	border-bottom-color: {$colors['text_link']};
}
.menu_main_nav > li > ul ul:before {
	border-right-color: {$colors['text_link']};
}
.menu_main_nav > li > ul ul.submenu_left:before {
	border-right-color: transparent;
	border-left-color: {$colors['text_link']};
}
.menu_main_nav > li li > a {
	color: {$colors['text_dark']};
}
.menu_main_nav > li li > a:hover,
.menu_main_nav > li li.sfHover > a {
	color: {$colors['text_link']};
}
.menu_main_nav > li li.current-menu-item > a,
.menu_main_nav > li li.current-menu-parent > a,
.menu_main_nav > li li.current-menu-ancestor > a {
	color: {$colors['text_link']};
}
.top_panel_navi.state_fixed .menu_main_wrap {
	background-color: {$colors['alter_dark']};
}

/* Mobile menu */
.menu_side_inner,
.menu_mobile_inner {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}
.menu_mobile_close:before,
.menu_mobile_close:after {
    border-color: {$colors['text_dark']};
}
.menu_mobile_button,
.menu_mobile_button:before {
	border-color: {$colors['bg_color']};
}
.menu_mobile_close:hover:before,
.menu_mobile_close:hover:after,
.menu_mobile_button:hover,
.menu_mobile_button:hover:before {
	border-color: {$colors['text_link']};
}
.menu_side_wrap .menu_mobile_button,
.menu_side_wrap .menu_mobile_button:before {
    color: {$colors['text_light']};
}
.menu_side_wrap .menu_mobile_button:hover,
.menu_side_wrap .menu_mobile_button:hover:before {
    color: {$colors['text_link']};
}

.menu_mobile_inner a {
	color: {$colors['text_dark']};
}
.menu_mobile_inner a:hover,
.menu_mobile_inner .current-menu-ancestor > a,
.menu_mobile_inner .current-menu-item > a {
	color: {$colors['text_link']};
}
.menu_mobile_inner .search_mobile .search_submit {
	color: {$colors['input_light']};
}
.menu_mobile_inner .search_mobile .search_submit:focus,
.menu_mobile_inner .search_mobile .search_submit:hover {
	color: {$colors['input_dark']};
}

/* Page title and breadcrumbs */
.top_panel_title_wrap .content_wrap {
    background-color: {$colors['bg_color']};
}
.top_panel_title .post_meta {
	color: {$colors['text_link']};
}
.breadcrumbs,
.breadcrumbs a {
	color: {$colors['text_light']};
}
.breadcrumbs a:hover {
	color: {$colors['text_dark']};
}

/* Page image and text */
.top_panel_title_2_text {
	color: {$colors['text_dark']};
}

/* Top panel with bg image */
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li > a,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li > a {
	color: {$colors['inverse_text']};
}
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li > a:hover,
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li.sfHover > a,
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li.current-menu-item > a,
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li.current-menu-parent > a,
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li.current-menu-ancestor > a,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li > a:hover,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li.sfHover > a,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li.current-menu-item > a,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li.current-menu-parent > a,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li.current-menu-ancestor > a {
	color: {$colors['inverse_hover']};
}
.scheme_self.top_panel_navi_header.with_bg_image .menu_header_nav > li+li:before,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .menu_main_nav > li+li:before {
	border-color: {$colors['inverse_text']};
}
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .search_wrap:not(.search_opened) .search_submit {
	color: {$colors['inverse_text']};
}
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .search_wrap:not(.search_opened) .search_submit:hover,
.scheme_self.top_panel_navi.with_bg_image:not(.state_fixed) .search_wrap:not(.search_opened) .search_submit:focus {
	color: {$colors['inverse_hover']};
}
.scheme_self.top_panel.with_bg_image.header_fullheight .page_caption {
	color: {$colors['inverse_text']};
}
.scheme_self.top_panel.with_bg_image.header_fullheight .top_panel_title:after {
	background-color: {$colors['inverse_text']};
}
.scheme_self.top_panel.with_bg_image.header_fullheight .top_panel_title .post_meta {
	color: {$colors['inverse_text']};
}
.scheme_self.top_panel.with_bg_image.header_fullheight .breadcrumbs,
.scheme_self.top_panel.with_bg_image.header_fullheight .breadcrumbs a {
	color: {$colors['inverse_text']};
}
.scheme_self.top_panel.with_bg_image.header_fullheight .breadcrumbs a:hover {
	color: {$colors['inverse_hover']};
}
.scheme_self.top_panel.with_bg_image.header_fullheight .top_panel_title_2_text {
	color: {$colors['inverse_text']};
}


/* Tabs */
.good_wine_shop_tabs .good_wine_shop_tabs_titles li a {
	color: {$colors['text_dark']};
}
.good_wine_shop_tabs .good_wine_shop_tabs_titles li a:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.good_wine_shop_tabs .good_wine_shop_tabs_titles li.ui-state-active a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}

/* Post layouts */
.post_item {
	color: {$colors['text']};
}
.post_item .post_meta,
.post_item .post_meta_item,
.post_item .post_date a,
.post_item .post_meta_item a,
.post_item .post_date:before,
.post_item .post_meta_item:before,
.post_counters .socials_share .socials_caption:before,
.post_counters .socials_share .socials_caption:hover:before,
.post_item .post_meta_item:hover:before {
	color: {$colors['text_link']};
}
.post_item .post_date a:hover,
.post_item a.post_meta_item:hover,
.post_item .post_meta_item a:hover {
	color: {$colors['text_hover']};
}
.post_item .post_meta_item.post_categories,
.post_item .post_meta_item.post_categories a {
	color: {$colors['text_link']};
}
.post_item .post_meta_item.post_categories a:hover {
	color: {$colors['text_hover']};
}

.post_meta_item .social_items,
.post_meta_item .social_items:before {
	background-color: {$colors['bg_color']};
	border-color: {$colors['bd_color']};
	color: {$colors['text_light']};
}

.scheme_self.gallery_preview:before {
	background-color: {$colors['bg_color']};
}

.post_featured:after {
	background-color: {$colors['bg_color']};
}

.post_item .more-link {
	border-color: {$colors['text_dark']};
}

/* Post Formats */
.format-audio .post_featured.with_thumb .post_audio_author,
.format-audio .post_featured.with_thumb .post_audio_title {
	color: {$colors['inverse_text']};
}
.format-audio .post_featured.without_thumb .post_audio {
	border-color: {$colors['bd_color']};
}
.format-audio .post_featured.without_thumb .post_audio_author {
	color: {$colors['text_link']};
}
.format-audio .post_featured.without_thumb .post_audio_title,
.without_thumb .mejs-controls .mejs-currenttime,
.without_thumb .mejs-controls .mejs-duration {
	color: {$colors['text_dark']};
}

.mejs-controls .mejs-button {
	color: {$colors['inverse_text']};
	background: {$colors['text_link']};
}
.mejs-controls .mejs-button:hover {
	background: {$colors['text_dark']};
}
.mejs-controls .mejs-time-rail .mejs-time-current,
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
    background: {$colors['text_dark']};
}
.mejs-controls .mejs-time-rail .mejs-time-total,
.mejs-controls .mejs-time-rail .mejs-time-loaded,
.mejs-container .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total {
	background: {$colors['inverse_light']};
}
.post_format_audio .post_featured.without_thumb .post_audio {
    border-color: {$colors['text_link']};
}
.post_item_single .post_content .mejs-container {
    border-color: {$colors['text_link']};
}
.post_item_single .mejs-container .mejs-controls .mejs-time {
    color: {$colors['text_dark']};
}

.format-aside .post_content_inner {
	color: {$colors['text']};
	border-color: {$colors['text_link']};
}

.format-link .post_content_inner,
.format-status .post_content_inner {
	color: {$colors['text_dark']};
}

.format-chat p > b,
.format-chat p > strong {
	color: {$colors['text_dark']};
}

.post_layout_chess .post_content_inner:after {
	background: linear-gradient(to top, {$colors['bg_color']} 0%, {$colors['bg_color_alpha_0']} 100%) no-repeat scroll right top / 100% 100% {$colors['bg_color_alpha_0']};
}

/* Pagination */
.nav-links-old {
	color: {$colors['text_dark']};
}
.nav-links-old a:hover {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}

.page_links > a,
.nav-links .page-numbers {
	color: {$colors['text_light']};
	border-color: {$colors['text_light']};
}
.page_links > a:hover,
.nav-links a.page-numbers:hover {
    color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}
.page_links > span:not(.page_links_title),
.nav-links .page-numbers.current {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}

/* Single post */
.post_item_single .post_header .post_date {
	color: {$colors['text_light']};
}
.post_item_single .post_header .post_categories,
.post_item_single .post_header .post_categories a {
	color: {$colors['text_link']};
}
.post_item_single .post_meta_item,
.post_item_single .post_meta_item:before,
.post_item_single .post_meta_item:hover:before,
.post_item_single .post_meta_item a,
.post_item_single .post_meta_item a:before,
.post_item_single .post_meta_item a:hover:before,
.post_item_single .post_meta_item .socials_caption,
.post_item_single .post_meta_item .socials_caption:before,
.post_item_single .post_edit a {
	color: {$colors['text_light']};
}
.post_item_single .post_meta_item:hover,
.post_item_single .post_meta_item > a:hover,
.post_item_single .post_meta_item .socials_caption:hover,
.post_item_single .post_edit a:hover {
	color: {$colors['text_hover']};
}
.post_item_single .post_content .post_meta_label,
.post_item_single .post_content .post_meta_item:hover .post_meta_label {
	color: {$colors['text_light']};
}
.post_item_single .post_content .post_tags,
.post_item_single .post_content .post_tags a {
	color: {$colors['text_dark']};
}
.post_item_single .post_content .post_tags a:hover {
	color: {$colors['text_link']};
}
.post_item_single .post_content .post_meta .post_share .social_item a {
	color: {$colors['inverse_text']} !important;
	background-color: {$colors['text_link']};
}
.post_item_single .post_content .post_meta .post_share .social_item a.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_dark']} 50%, {$colors['text_link']} 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.post_item_single .post_content .post_meta .post_share .social_item a.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_dark']} 50%, {$colors['text_link']} 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.post_item_single .post_content .post_meta .post_share .social_item a.sc_button_hover_slide_top {	background: linear-gradient(to bottom,	{$colors['text_dark']} 50%, {$colors['text_link']} 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.post_item_single .post_content .post_meta .post_share .social_item a.sc_button_hover_slide_bottom {background: linear-gradient(to top,		{$colors['text_dark']} 50%, {$colors['text_link']} 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.post_item_single .post_content .post_meta .post_share .social_item a:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['text_hover']};
}

.post-password-form input[type="submit"] {
	border-color: {$colors['text_dark']};
}
.post-password-form input[type="submit"]:hover,
.post-password-form input[type="submit"]:focus {
	color: {$colors['bg_color']};
}

/* Single post navi */
.nav-links-single .nav-links {
	border-color: {$colors['bd_color']};
}
.nav-links-single .nav-links a .meta-nav {
	color: {$colors['text_light']};
}
.nav-links-single .nav-links a .post_date {
	color: {$colors['text_light']};
}
.nav-links-single .nav-links a:hover .meta-nav,
.nav-links-single .nav-links a:hover .post_date {
	color: {$colors['text_dark']};
}
.nav-links-single .nav-links a:hover .post-title {
	color: {$colors['text_link']};
}

/* Author info */
.author_info {
	border-color: {$colors['bd_color']};
}
.author_info a {
	color: {$colors['text_dark']};
}
.author_info a:hover {
	color: {$colors['text_link']};
}

/* Related posts */
.related_wrap .related_item_style_1 .post_header {
	background-color: {$colors['bg_color_alpha']};
}
.related_wrap .related_item_style_1:hover .post_header {
	background-color: {$colors['bg_color']};
}
.related_wrap .related_item_style_1 .post_date a {
	color: {$colors['text']};
}
.related_wrap .related_item_style_1:hover .post_date a {
	color: {$colors['text_light']};
}
.related_wrap .related_item_style_1:hover .post_date a:hover {
	color: {$colors['text_dark']};
}

/* Comments */
.comments_list_wrap .comment_info,
.comments_list_wrap .comment_counters a {
	color: {$colors['text_link']};
}
.comments_list_wrap .comment_counters a:before {
	color: {$colors['text_link']};
}
.comments_list_wrap .comment_counters a:hover:before,
.comments_list_wrap .comment_counters a:hover {
	color: {$colors['text_hover']};
}
.comments_list_wrap .comment_text {
	color: {$colors['text']};
}
.comments_form_wrap {
	border-color: {$colors['bd_color']};
}
.comments_wrap .comments_notes {
	color: {$colors['text_light']};
}


/* Page 404 */
.post_item_404 .page_title {
	color: {$colors['text_light']};
}
.post_item_404 .page_description {
	color: {$colors['text_link']};
}
.post_item_404 .go_home {
	border-color: {$colors['text_dark']};
}

/* Sidebar */
.sidebar_inner {
	background-color: {$colors['bg_color']};
	color: {$colors['text']};
}
.sidebar_inner aside + aside {
	border-color: {$colors['text_light']};
}
.sidebar_inner h1, .sidebar_inner h2, .sidebar_inner h3, .sidebar_inner h4, .sidebar_inner h5, .sidebar_inner h6,
.sidebar_inner h1 a, .sidebar_inner h2 a, .sidebar_inner h3 a, .sidebar_inner h4 a, .sidebar_inner h5 a, .sidebar_inner h6 a {
	color: {$colors['text_dark']};
}


/* Widgets */
aside {
	color: {$colors['text']};
}
aside a,
.sidebar .widget_title > a:hover{
	color: {$colors['text_link']};
}

aside a:hover {
	color: {$colors['text_hover']};
}
aside li:before {
	background-color: {$colors['text_link']};
}

.footer_wrap aside {
    color: {$colors['inverse_text']};
}
figure figcaption > a:hover,
.footer_wrap aside a {
    color: {$colors['alter_light']};
}
.footer_wrap aside a:hover {
    color: {$colors['text_link']};
}

.widget_area_inner .post_item .post_info .post_info_item,
aside .post_item .post_info .post_info_item,
.widget_area_inner .post_item .post_info .post_info_item a,
aside .post_item .post_info .post_info_item a,
.widget_area_inner .post_info_counters .post_counters_item,
aside .post_info_counters .post_counters_item {
	color: {$colors['text_light']};
}
.widget_area_inner .post_item .post_info .post_info_item a:hover,
aside .post_item .post_info .post_info_item a:hover,
.widget_area_inner .post_info_counters .post_counters_item:hover,
aside .post_info_counters .post_counters_item:hover {
	color: {$colors['text_dark']};
}
.widget_area_inner .post_item .post_title a:hover,
aside .post_item .post_title a:hover {
	color: {$colors['text_link']};
}

/* Archive */
.widget_archive li {
	color: {$colors['text_dark']};
}

/* Calendar */
.wp-block-calendar caption,
.wp-block-calendar tbody td a,
.wp-block-calendar th,
.widget_calendar caption,
.widget_calendar tbody td a,
.widget_calendar th {
	color: {$colors['text_dark']};
}
.wp-block-calendar tbody td,
.widget_calendar tbody td {
	color: {$colors['text']};
}
.wp-block-calendar tbody td a:after,
.widget_calendar tbody td a:after {
	color: {$colors['text_link']};
}
.wp-block-calendar tbody td a:hover,
.wp-block-calendar tbody td a:hover:after,
.widget_calendar tbody td a:hover,
.widget_calendar tbody td a:hover:after {
	color: {$colors['text_link']};
}
.wp-block-calendar td#today:before,
.widget_calendar td#today:before {
	border-color: {$colors['text_link']};
}
.wp-block-calendar #prev a,
.wp-block-calendar #next a,
.widget_calendar #prev a,
.widget_calendar #next a {
	color: {$colors['text_link']};
}
.wp-block-calendar #prev a:hover,
.wp-block-calendar #next a:hover,
.widget_calendar #prev a:hover,
.widget_calendar #next a:hover {
	color: {$colors['text_hover']};
}
.footer_wrap .widget_calendar #prev a,
.footer_wrap .widget_calendar #next a {
	color: {$colors['text']};
}
.footer_wrap .widget_calendar #prev a:hover,
.footer_wrap .widget_calendar #next a:hover {
	color: {$colors['text_link']};
}

.wp-block-calendar #prev a:before,
.wp-block-calendar #next a:before,
.widget_calendar #prev a:before,
.widget_calendar #next a:before {
	background-color: {$colors['bg_color']};
}

.footer_wrap .widget_calendar #prev a:before,
.footer_wrap .widget_calendar #next a:before {
	background-color: {$colors['alter_dark']};
}

/* Categories */
.widget_categories li {
	color: {$colors['text_dark']};
}

/* Tag cloud */
.widget_product_tag_cloud a,
.widget_tag_cloud a {
	color: {$colors['text_link']};
	border-color: {$colors['text_link']};
}

.widget_product_tag_cloud a:not([class*="sc_button_hover_"]),
.widget_tag_cloud a:not([class*="sc_button_hover_"]),
.widget_product_tag_cloud a.sc_button_hover_slide_left,
.wp-block-tag-cloud a.sc_button_hover_slide_left,
.widget_tag_cloud a.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.widget_product_tag_cloud a.sc_button_hover_slide_right,
.widget_tag_cloud a.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.widget_product_tag_cloud a.sc_button_hover_slide_top,
.widget_tag_cloud a.sc_button_hover_slide_top {		background: linear-gradient(to bottom,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.widget_product_tag_cloud a.sc_button_hover_slide_bottom,
.widget_tag_cloud a.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.wp-block-tag-cloud a:hover,
.widget_product_tag_cloud a:hover,
.widget_tag_cloud a:hover {
	color: {$colors['inverse_text']} !important;
	border-color: {$colors['text_dark']};
    background-position: left bottom;
	
}
.widget_product_tag_cloud a:not([class*="sc_button_hover_"]):hover,
.widget_tag_cloud a:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['text_dark']};
}

/* RSS */
.widget_rss .widget_title a:first-child {
	color: {$colors['text_link']};
}
.widget_rss .widget_title a:first-child:hover {
	color: {$colors['text_hover']};
}
.widget_rss .rss-date {
	color: {$colors['text_light']};
}

/* MailChimp */
.widget_text .mc4wp-form input[type="submit"] {
	border-color: {$colors['text_dark']};
	background-color: {$colors['text_dark']};
	color: {$colors['bg_color']};
}
.widget_text .mc4wp-form input[type="submit"]:hover {
	color: {$colors['text_dark']};
}
.widget_text .socials_wrap a {
	color: {$colors['text_dark']};
}
.widget_text .socials_wrap a:hover {
	color: {$colors['text_hover']};
}

/* Footer */
.scheme_self.site_footer_wrap {
	background-color: {$colors['input_dark']};
	color: {$colors['inverse_text']};
}
.footer_wrap_inner.widget_area_inner {
    background-color: {$colors['alter_dark']};
    color: {$colors['inverse_text']};
}
.site_footer_wrap h1, .site_footer_wrap h2, .site_footer_wrap h3, .site_footer_wrap h4, .site_footer_wrap h5, .site_footer_wrap h6,
.site_footer_wrap h1 a, .site_footer_wrap h2 a, .site_footer_wrap h3 a, .site_footer_wrap h4 a, .site_footer_wrap h5 a, .site_footer_wrap h6 a {
	color: {$colors['inverse_text']};
}
.logo_footer_wrap_inner:after {
	background-color: {$colors['text']};
}
.socials_footer_wrap_inner .social_item .social_icons {
	border-color: {$colors['text']};
	color: {$colors['text']};
}
.socials_footer_wrap_inner .social_item .social_icons:hover {
	border-color: {$colors['text_dark']};
	color: {$colors['text_dark']};
}
.menu_footer_nav_area ul li a {
	color: {$colors['inverse_text']};
}
.menu_footer_nav_area ul li a:hover {
	color: {$colors['text_link']};
}
.menu_footer_nav_area ul li+li:before {
	border-color: {$colors['bg_color_alpha_03']};
}
.copyright_wrap_inner {
	color: {$colors['inverse_text']};
}
.copyright_wrap_inner a {
	color: {$colors['inverse_text']};
}
.copyright_wrap_inner a:hover {
	color: {$colors['text_link']};
}
.copyright_wrap_inner .copyright_text {
	color: {$colors['inverse_text']};
}

/* Buttons */
.theme_button,
.more-link,
.socials_share:not(.socials_type_drop) .social_icons,
.comments_wrap .form-submit input[type="submit"] {
	color: {$colors['text_dark']} !important;
	border-color: {$colors['text_dark']};
	background-color: transparent !important;
}
.theme_button:not([class*="sc_button_hover_"]),
.more-link:not([class*="sc_button_hover_"]),
.socials_share:not(.socials_type_drop) .social_icons:not([class*="sc_button_hover_"]),
.comments_wrap .form-submit input[type="submit"]:not([class*="sc_button_hover_"]) {
    background: linear-gradient(to right, {$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0);
}
.theme_button:hover,
.more-link:hover,
.socials_share:not(.socials_type_drop) .social_icons:hover,
.comments_wrap .form-submit input[type="submit"]:hover,
.comments_wrap .form-submit input[type="submit"]:focus {
	color: {$colors['inverse_text']} !important;
	border-color: {$colors['text_link']} !important;
    background-position: left bottom;
}
.theme_button:not([class*="sc_button_hover_"]):hover,
.more-link:not([class*="sc_button_hover_"]):hover,
.socials_share:not(.socials_type_drop) .social_icons:not([class*="sc_button_hover_"]):hover,
.comments_wrap .form-submit input[type="submit"]:not([class*="sc_button_hover_"]):hover,
.comments_wrap .form-submit input[type="submit"]:not([class*="sc_button_hover_"]):focus {
	background-color: {$colors['text_dark']} !important;
}

.mfp-close[class*='sc_button_hover_'] {
    background: none !important;
    color: {$colors['text_dark']} !important;
}
.mfp-close[class*='sc_button_hover_']:hover {
    background: {$colors['text_link']} !important;
    color: {$colors['inverse_text']} !important;
}

.format-video .post_featured.with_thumb .post_video_hover {
	color: {$colors['inverse_text']};
}
.format-video .post_featured.with_thumb .post_video_hover.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.format-video .post_featured.with_thumb .post_video_hover.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.format-video .post_featured.with_thumb .post_video_hover.sc_button_hover_slide_top {	background: linear-gradient(to bottom,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.format-video .post_featured.with_thumb .post_video_hover.sc_button_hover_slide_bottom {background: linear-gradient(to top,		{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.format-video .post_featured.with_thumb .post_video_hover:hover {
	color: {$colors['text_link']};
}

.theme_scroll_down {
	color: {$colors['text_dark']};
}
.theme_scroll_down:hover {
	color: {$colors['text_link']};
}

/* Third-party plugins */

.mfp-bg {
	background-color: {$colors['bg_color_alpha']};
}
.mfp-image-holder .mfp-close,
.mfp-iframe-holder .mfp-close {
	color: {$colors['text_dark']};
}
.mfp-image-holder .mfp-close:hover,
.mfp-iframe-holder .mfp-close:hover {
	color: {$colors['text_link']};
}



CSS;
				
				$rez = apply_filters('good_wine_shop_filter_get_css', $rez, $colors, $fonts, $scheme);
				
				$css['colors'] .= $rez['colors'];
				if ($step == 1) $css['fonts'] = $rez['fonts'];
				$step++;
			}
		}
				
		$css_str = (!empty($css['fonts']) ? $css['fonts'] : '')
					. (!empty($css['colors']) ? $css['colors'] : '');
		return $minify ? good_wine_shop_minify_css($css_str) : $css_str;
	}
}
?>