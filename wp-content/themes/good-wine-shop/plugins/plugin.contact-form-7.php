<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_cf7_theme_setup9', 9 );
	function good_wine_shop_cf7_theme_setup9() {

		if (is_admin()) {
			add_filter( 'good_wine_shop_filter_tgmpa_required_plugins',			'good_wine_shop_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'good_wine_shop_cf7_tgmpa_required_plugins' ) ) {
	
	function good_wine_shop_cf7_tgmpa_required_plugins($list=array()) {
		if (in_array('contact-form-7', good_wine_shop_storage_get('required_plugins'))) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> esc_html__('Contact Form 7', 'good-wine-shop'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if cf7 installed and activated
if ( !function_exists( 'good_wine_shop_exists_cf7' ) ) {
	function good_wine_shop_exists_cf7() {
		return class_exists('WPCF7');
	}
}
?>