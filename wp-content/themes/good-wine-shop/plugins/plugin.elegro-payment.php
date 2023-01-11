<?php
/* elegro Crypto Payment support functions
------------------------------------------------------------------------------- */


// Check if this plugin installed and activated
if ( ! function_exists( 'good_wine_shop_exists_elegro_payment' ) ) {
	function good_wine_shop_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}


/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_elegro_payment_theme_setup9')) {
    add_action('after_setup_theme', 'good_wine_shop_elegro_payment_theme_setup9', 9);
    function good_wine_shop_elegro_payment_theme_setup9()
    {
        
        if (is_admin()) {
            add_filter('good_wine_shop_filter_tgmpa_required_plugins', 'good_wine_shop_elegro_payment_tgmpa_required_plugins');
        }
    }
}



// Filter to add in the required plugins list
if (!function_exists('good_wine_shop_elegro_payment_tgmpa_required_plugins')) {
    function good_wine_shop_elegro_payment_tgmpa_required_plugins($list = array())
    {
        if (in_array('elegro-payment', good_wine_shop_storage_get('required_plugins'))) {

            $list[] = array(
                'name' => esc_html__('elegro Crypto Payment', 'good-wine-shop'),
                'slug' => 'elegro-payment',
                'required' => false
            );
        }
        return $list;
    }
}


// Add our ref to the link
if ( !function_exists( 'trx_addons_elegro_payment_add_ref' ) ) {
    add_filter( 'woocommerce_settings_api_form_fields_elegro', 'trx_addons_elegro_payment_add_ref' );
    function trx_addons_elegro_payment_add_ref( $fields ) {
        if ( ! empty( $fields['listen_url']['description'] ) ) {
            $fields['listen_url']['description'] = preg_replace( '/href="([^"]+)"/', 'href="$1?ref=246218d7-a23d-444d-83c5-a884ecfa4ebd"', $fields['listen_url']['description'] );
        }
        return $fields;
    }
}