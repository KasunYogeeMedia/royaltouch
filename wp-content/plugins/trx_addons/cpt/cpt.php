<?php
/**
 * ThemeREX Addons Custom post types
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Include files with widgets
if (!function_exists('trx_addons_cpt_load')) {
	add_action( 'after_setup_theme', 'trx_addons_cpt_load', 2 );
	add_action( 'trx_addons_action_save_options', 'trx_addons_cpt_load', 9 );
	function trx_addons_cpt_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		$trx_addons_cpt = apply_filters('trx_addons_cpt_list', array(
			'courses',
			'services',
			'team',
			'testimonials'
			)
		);
		if (is_array($trx_addons_cpt) && count($trx_addons_cpt) > 0) {
			foreach ($trx_addons_cpt as $cpt) {
				if (($fdir = trx_addons_get_file_dir("cpt/{$cpt}/{$cpt}.php")) != '') { include_once $fdir; }
			}
		}
	}
}
?>