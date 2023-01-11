<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_essgrids_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_essgrids_theme_setup9', 9 );
	function good_wine_shop_essgrids_theme_setup9() {
		if (good_wine_shop_exists_essgrids()) {
			add_action( 'wp_enqueue_scripts', 									'good_wine_shop_essgrids_frontend_scripts', 1100 );
			add_filter( 'good_wine_shop_filter_merge_styles',					'good_wine_shop_essgrids_merge_styles' );
			add_filter( 'good_wine_shop_filter_get_css',						'good_wine_shop_essgrids_get_css', 10, 3 );
			if (is_admin()) {
				add_action( 'good_wine_shop_action_importer_params',			'good_wine_shop_essgrids_importer_show_params', 10, 1 );
				add_filter( 'good_wine_shop_filter_importer_options',			'good_wine_shop_essgrids_importer_set_options', 10, 1 );
				add_action( 'good_wine_shop_action_importer_clear_tables',		'good_wine_shop_essgrids_importer_clear_tables', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import',			'good_wine_shop_essgrids_importer_import', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import_fields',	'good_wine_shop_essgrids_importer_import_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'good_wine_shop_filter_importer_required_plugins',		'good_wine_shop_essgrids_importer_required_plugins', 10, 2 );
			add_filter( 'good_wine_shop_filter_tgmpa_required_plugins',		'good_wine_shop_essgrids_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'good_wine_shop_exists_essgrids' ) ) {
	function good_wine_shop_exists_essgrids() {
		return defined('EG_PLUGIN_PATH') || defined( 'ESG_PLUGIN_PATH' );
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'good_wine_shop_essgrids_tgmpa_required_plugins' ) ) {
	
	function good_wine_shop_essgrids_tgmpa_required_plugins($list=array()) {
		if (in_array('essgrids', good_wine_shop_storage_get('required_plugins'))) {
			$path = good_wine_shop_get_file_dir('plugins/install/essential-grid.zip');
			if (file_exists($path)) {
				$list[] = array(
						'name' 		=> esc_html__('Essential Grid', 'good-wine-shop'),
						'slug' 		=> 'essential-grid',
                        'version'	=> '2.3.6',
						'source'	=> $path,
						'required' 	=> false
					);
			}
		}
		return $list;
	}
}
	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'good_wine_shop_essgrids_frontend_scripts' ) ) {
	
	function good_wine_shop_essgrids_frontend_scripts() {
			if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('css/plugin.essgrids.css')))
                wp_enqueue_style( 'good-wine-shop-plugin-essgrids',  good_wine_shop_get_file_url('css/plugin.essgrids.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'good_wine_shop_essgrids_merge_styles' ) ) {
	
	function good_wine_shop_essgrids_merge_styles($css) {
		return $css . good_wine_shop_fgc(good_wine_shop_get_file_dir('css/plugin.essgrids.css'));
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'good_wine_shop_essgrids_importer_required_plugins' ) ) {
	
	function good_wine_shop_essgrids_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'essgrids')!==false && !good_wine_shop_exists_essgrids() )
			$not_installed .= '<br>' . esc_html__('Essential Grids', 'good-wine-shop');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'good_wine_shop_essgrids_importer_set_options' ) ) {
	
	function good_wine_shop_essgrids_importer_set_options($options=array()) {
		if ( in_array('essgrids', good_wine_shop_storage_get('required_plugins')) && good_wine_shop_exists_essgrids() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_essgrid'] = str_replace('posts.txt', 'ess_grid.json', $v['file_with_posts']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'good_wine_shop_essgrids_importer_show_params' ) ) {
	
	function good_wine_shop_essgrids_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('essgrids', good_wine_shop_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_essgrids" id="import_essgrids" /> <label for="import_essgrids"><?php esc_html_e('Import Ess.Grids', 'good-wine-shop'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'good_wine_shop_essgrids_importer_import' ) ) {
    function good_wine_shop_essgrids_importer_import($importer, $action) {

        if ( $action == 'import_essgrids' ) {

            if ( ($txt = $importer->get_file($importer->options['files'][$importer->options['demo_type']]['file_with_essgrids'])) != '') {

                $data = json_decode($txt, true);

                try {
                    $im = new Essential_Grid_Import();

                    // Prepare arrays with overwrite flags
                    $tmp = array();
                    if (is_array($data) && count($data) > 0) {
                        foreach ($data as $k=>$v) {
                            if ($k=='grids') {            $name = 'grids'; $name_1= 'grid'; $name_id='id'; }
                            else if ($k=='skins') {        $name = 'skins'; $name_1= 'skin'; $name_id='id'; }
                            else if ($k=='elements') {    $name = 'elements'; $name_1= 'element'; $name_id='id'; }
                            else if ($k=='navigation-skins') {    $name = 'navigation-skins'; $name1= 'nav-skin'; $name_id='id'; }
                            else if ($k=='punch-fonts') {    $name = 'punch-fonts'; $name1= 'punch-fonts'; $name_id='handle'; }
                            else if ($k=='custom-meta') {    $name = 'custom-meta'; $name1= 'custom-meta'; $name_id='handle'; }
                            if ($k=='global-css') {
                                $tmp['import-global-styles'] = "on";
                                $tmp['global-styles-overwrite'] = "append";
                            } else {
                                $tmp['import-'.$name] = "true";
                                $tmp['import-'.$name.'-'.$name_id] = array();
                                if (is_array($v) && count($v) > 0) {
                                    foreach ($v as $v1) {
                                        $tmp['import-'.$name.'-'.$name_id][] = $v1[$name_id];
                                        $tmp[$name_1.'-overwrite-'.$name_id] = 'append';
                                    }
                                }
                            }
                        }
                    }
                    $im->set_overwrite_data($tmp); //set overwrite data global to class

                    $skins = isset($data['skins']) ? $data['skins'] : '';
                    if (!empty($skins) && is_array($skins)){
                        $skins_ids = isset($tmp['import-skins-id']) ? $tmp['import-skins-id'] : '';
                        $skins_imported = $im->import_skins($skins, $skins_ids);
                    }

                    $navigation_skins = isset($data['navigation-skins']) ? $data['navigation-skins'] : '';
                    if (!empty($navigation_skins) && is_array($navigation_skins)){
                        $navigation_skins_ids = isset($tmp['import-navigation-skins-id']) ? $tmp['import-navigation-skins-id'] : '';
                        $navigation_skins_imported = $im->import_navigation_skins($navigation_skins, $navigation_skins_ids);
                    }

                    $grids = isset($data['grids']) ? $data['grids'] : '';
                    if (!empty($grids) && is_array($grids)){
                        $grids_ids = isset($tmp['import-grids-id']) ? $tmp['import-grids-id'] : '';
                        $grids_imported = $im->import_grids($grids, $grids_ids);
                    }

                    $elements = isset($data['elements']) ? $data['elements'] : '';
                    if (!empty($elements) && is_array($elements)){
                        $elements_ids = isset($tmp['import-elements-id']) ? $tmp['import-elements-id'] : '';
                        $elements_imported = $im->import_elements($elements, $elements_ids);
                    }

                    $custom_metas = isset($data['custom-meta']) ? $data['custom-meta'] : '';
                    if (!empty($custom_metas) && is_array($custom_metas)){
                        $custom_metas_handle = isset($tmp['import-custom-meta-handle']) ? $tmp['import-custom-meta-handle'] : '';
                        $custom_metas_imported = $im->import_custom_meta($custom_metas, $custom_metas_handle);
                    }

                    $custom_fonts = isset($data['punch-fonts']) ? $data['punch-fonts'] : '';
                    if (!empty($custom_fonts) && is_array($custom_fonts)){
                        $custom_fonts_handle = isset($tmp['import-punch-fonts-handle']) ? $tmp['import-punch-fonts-handle'] : '';
                        $custom_fonts_imported = $im->import_punch_fonts($custom_fonts, $custom_fonts_handle);
                    }

                    if (isset($tmp['import-global-styles']) && $tmp['import-global-styles'] == 'on'){
                        $global_css = isset($data['global-css']) ? $data['global-css'] : '';
                        $global_styles_imported = $im->import_global_styles($tglobal_css);
                    }

                    if ($importer->options['debug'])
                        dfl( esc_html__('Essential Grid import complete', 'good-wine-shop') );

                } catch (Exception $d) {
                    $msg = sprintf(esc_html__('Essential Grid import error: %s', 'good-wine-shop'), $d->getMessage());
                    $importer->response['error'] = $msg;
                    if ($importer->options['debug'])
                        dfl( $msg );

                }
            }
        }
    }
}

// Display import progress
if ( !function_exists( 'good_wine_shop_essgrids_importer_import_fields' ) ) {
	
	function good_wine_shop_essgrids_importer_import_fields($importer) {
		?>
		<tr class="import_essgrids">
			<td class="import_progress_item"><?php esc_html_e('Essential Grid', 'good-wine-shop'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}



// Add plugin's specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'good_wine_shop_essgrids_get_css' ) ) {
	
	function good_wine_shop_essgrids_get_css($css, $colors, $fonts) {
		if (isset($css['colors'])) {
			$css['fonts'] .= <<<CSS

.esg-grid .eg-winelist-element-25, .esg-grid .eg-winelist-element-30 {
	font-family: {$fonts['h5']['family']};
}
.esg-grid .eg-winelist-element-0 {
	font-family: {$fonts['h6']['family']};
}

CSS;
		}

		if (isset($css['colors'])) {
			$css['colors'] .= <<<CSS

.esg-grid .eg-winelist-element-30 {
	color: {$colors['text_dark']};
}
.esg-grid .eg-winelist-element-30:hover {
	color: {$colors['text_link']};
}
		
CSS;
		}
		
		return $css;
	}
}
?>