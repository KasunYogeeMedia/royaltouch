<?php
/* WPBakery PageBuilder Extensions Bundle support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_vc_extensions_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_vc_extensions_theme_setup9', 9 );
	function good_wine_shop_vc_extensions_theme_setup9() {
		if (good_wine_shop_exists_visual_composer()) {
			add_action( 'wp_enqueue_scripts', 										'good_wine_shop_vc_extensions_frontend_scripts', 1100 );
			add_filter( 'good_wine_shop_filter_merge_styles',						'good_wine_shop_vc_extensions_merge_styles' );
			add_filter( 'good_wine_shop_filter_get_css',							'good_wine_shop_vc_extensions_get_css', 10, 3 );
			if (is_admin()) {
				add_filter( 'good_wine_shop_filter_importer_options',				'good_wine_shop_vc_extensions_importer_set_options' );
				add_action( 'good_wine_shop_action_importer_params',				'good_wine_shop_vc_extensions_importer_show_params', 10, 1 );
				add_action( 'good_wine_shop_action_importer_import',				'good_wine_shop_vc_extensions_importer_import', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import_fields',		'good_wine_shop_vc_extensions_importer_import_fields', 10, 1 );
			}
		}
	
		if (is_admin()) {
			add_filter( 'good_wine_shop_filter_importer_required_plugins',		'good_wine_shop_vc_extensions_importer_required_plugins', 10, 2 );
			add_filter( 'good_wine_shop_filter_tgmpa_required_plugins',		'good_wine_shop_vc_extensions_tgmpa_required_plugins' );
		}
	}
}

// Check if VC Extensions installed and activated
if ( !function_exists( 'good_wine_shop_exists_vc_extensions' ) ) {
	function good_wine_shop_exists_vc_extensions() {
		return class_exists('Vc_Manager') && class_exists('VC_Extensions_CQBundle');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'good_wine_shop_vc_extensions_tgmpa_required_plugins' ) ) {
	
	function good_wine_shop_vc_extensions_tgmpa_required_plugins($list=array()) {
		if (in_array('vc-extensions-bundle', good_wine_shop_storage_get('required_plugins'))) {
			$path = good_wine_shop_get_file_dir('plugins/install/vc-extensions-bundle.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder Extensions Bundle', 'good-wine-shop'),
					'slug' 		=> 'vc-extensions-bundle',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}
	
// Enqueue VC custom styles
if ( !function_exists( 'good_wine_shop_vc_extensions_frontend_scripts' ) ) {
	
	function good_wine_shop_vc_extensions_frontend_scripts() {
		if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('css/plugin.vc-extensions-bundle.css')))
            wp_enqueue_style( 'good-wine-shop-plugin-vc-extensions-bundle',  good_wine_shop_get_file_url('css/plugin.vc-extensions-bundle.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'good_wine_shop_vc_extensions_merge_styles' ) ) {
	
	function good_wine_shop_vc_extensions_merge_styles($css) {
		return $css . good_wine_shop_fgc(good_wine_shop_get_file_dir('css/plugin.vc-extensions-bundle.css'));
	}
}




// One-click import support
//------------------------------------------------------------------------

// Check VC Extensions in the required plugins
if ( !function_exists( 'good_wine_shop_vc_extensions_importer_required_plugins' ) ) {
	
	function good_wine_shop_vc_extensions_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'vc-extensions-bundle')!==false && !good_wine_shop_exists_vc_extensions())
			$not_installed .= '<br>' . esc_html__('WPBakery PageBuilder Extensions Bundle', 'good-wine-shop');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'good_wine_shop_vc_extensions_importer_set_options' ) ) {
	
	function good_wine_shop_vc_extensions_importer_set_options($options=array()) {
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'good_wine_shop_vc_extensions_importer_show_params' ) ) {
	
	function good_wine_shop_vc_extensions_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('vc-extensions-bundle', good_wine_shop_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_vc_extensions" id="import_vc_extensions" /> <label for="import_vc_extensions"><?php esc_html_e('Import WPBakery PageBuilder Extensions Bundle', 'good-wine-shop'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'good_wine_shop_vc_extensions_importer_import' ) ) {
	
	function good_wine_shop_vc_extensions_importer_import($importer, $action) {
		if ( $action == 'import_vc_extensions' ) {
			// ToDo: place here actions to import VC specific posts, metadata, options, etc.
		}
	}
}

// Display import progress
if ( !function_exists( 'good_wine_shop_vc_extensions_importer_import_fields' ) ) {
	
	function good_wine_shop_vc_extensions_importer_import_fields($importer) {
		?>
		<tr class="import_vc_extensions">
			<td class="import_progress_item"><?php esc_html_e('WPBakery PageBuilder Extensions Bundle meta', 'good-wine-shop'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}


// Add VC specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'good_wine_shop_vc_extensions_get_css' ) ) {
	
	function good_wine_shop_vc_extensions_get_css($css, $colors, $fonts) {
		if (isset($css['colors'])) {
			$css['fonts'] .= <<<CSS

CSS;
		}

		if (isset($css['colors'])) {
			$css['colors'] .= <<<CSS

CSS;
		}
		
		return $css;
	}
}
?>