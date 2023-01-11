<?php
/**
 * ThemeREX Widgets
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
if (!function_exists('trx_addons_widgets_load')) {
	add_action( 'after_setup_theme', 'trx_addons_widgets_load', 2 );
	add_action( 'trx_addons_action_save_options', 'trx_addons_widgets_load', 9 );
	function trx_addons_widgets_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		$trx_addons_widgets = apply_filters('trx_addons_widgets_list', array(
			'aboutme',
			'audio',
			'banner',
			'calendar',
			'categories_list',
			'contacts',
			'flickr',
			'popular_posts',
			'recent_news',
			'recent_posts',
			'slider',
			'socials',
			'twitter',
			'video'
			)
		);
		if (is_array($trx_addons_widgets) && count($trx_addons_widgets) > 0) {
			foreach ($trx_addons_widgets as $w) {
				if (($fdir = trx_addons_get_file_dir("widgets/{$w}/{$w}.php")) != '') { include_once $fdir; }
			}
		}
	}
}

// Disable a new Widgets block editor
if (!function_exists('trx_addons_widgets_disable_block_editor')) {
    add_action( 'after_setup_theme', 'trx_addons_widgets_disable_block_editor' );
    function trx_addons_widgets_disable_block_editor() {
        if ( (int) trx_addons_get_option( 'disable_widgets_block_editor' ) > 0 ) {
            remove_theme_support( 'widgets-block-editor' );
        }
    }
}
?>