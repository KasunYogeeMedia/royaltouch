<?php
/* ThemeREX Updater support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'good_wine_shop_trx_updater_theme_setup9' ) ) {
    add_action( 'after_setup_theme', 'good_wine_shop_trx_updater_theme_setup9', 9 );
    function good_wine_shop_trx_updater_theme_setup9() {
        if ( is_admin() ) {
            add_filter( 'good_wine_shop_filter_tgmpa_required_plugins', 'good_wine_shop_trx_updater_tgmpa_required_plugins', 8 );
        }
    }
}


// Filter to add in the required plugins list
if ( ! function_exists( 'good_wine_shop_trx_updater_tgmpa_required_plugins' ) ) {
    
    function good_wine_shop_trx_updater_tgmpa_required_plugins( $list = array() ) {
        if (in_array('trx_updater', good_wine_shop_storage_get('required_plugins'))) {
            $path = good_wine_shop_get_file_dir( 'plugins/install/trx_updater.zip' );
                $list[] = array(

                    'name' 		=> esc_html__('ThemeREX Updater', 'good-wine-shop'),
                    'slug'     => 'trx_updater',
                    'version'  => '1.4.1',
                    'source'   => ! empty( $path ) ? $path : 'upload://trx_updater.zip',
                    'required' => false,
                );

        }
        return $list;
    }
}


