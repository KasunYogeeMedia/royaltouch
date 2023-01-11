<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_revslider_theme_setup9', 9 );
	function good_wine_shop_revslider_theme_setup9() {
		if (good_wine_shop_exists_revslider()) {
			if (is_admin()) {
				add_action( 'good_wine_shop_action_importer_params',			'good_wine_shop_revslider_importer_show_params', 10, 1 );
				add_action( 'good_wine_shop_action_importer_clear_tables',	'good_wine_shop_revslider_importer_clear_tables', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import',			'good_wine_shop_revslider_importer_import', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import_fields',	'good_wine_shop_revslider_importer_import_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'good_wine_shop_filter_importer_required_plugins',	'good_wine_shop_revslider_importer_required_plugins', 10, 2 );
			add_filter( 'good_wine_shop_filter_tgmpa_required_plugins',		'good_wine_shop_revslider_tgmpa_required_plugins' );
		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'good_wine_shop_exists_revslider' ) ) {
	function good_wine_shop_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'good_wine_shop_revslider_tgmpa_required_plugins' ) ) {
	
	function good_wine_shop_revslider_tgmpa_required_plugins($list=array()) {
		if (in_array('revslider', good_wine_shop_storage_get('required_plugins'))) {
			$path = good_wine_shop_get_file_dir('plugins/install/revslider.zip');
			if (file_exists($path)) {
				$list[] = array(
						'name' 		=> esc_html__('Revolution Slider', 'good-wine-shop'),
						'slug' 		=> 'revslider',
                        'version'	=> '6.0.4',
						'source'	=> $path,
						'required' 	=> false
					);
			}
		}
		return $list;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check RevSlider in the required plugins
if ( !function_exists( 'good_wine_shop_revslider_importer_required_plugins' ) ) {
	
	function good_wine_shop_revslider_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'revslider')!==false && !good_wine_shop_exists_revslider() )
			$not_installed .= '<br>' . esc_html__('Revolution Slider', 'good-wine-shop');
		return $not_installed;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'good_wine_shop_revslider_importer_show_params' ) ) {
	
	function good_wine_shop_revslider_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('revslider', good_wine_shop_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_revslider" id="import_revslider" /> <label for="import_revslider"><?php esc_html_e('Import Revolution Sliders', 'good-wine-shop'); ?></label><br>
		<?php
	}
}

// Clear tables
if ( !function_exists( 'good_wine_shop_revslider_importer_clear_tables' ) ) {
	
	function good_wine_shop_revslider_importer_clear_tables($importer, $clear_tables) {
		if (strpos($clear_tables, 'revslider')!==false && $importer->last_slider==0) {
			if ($importer->options['debug']) dfl(esc_html__('Clear Revolution Slider tables', 'good-wine-shop'));
			global $wpdb;
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_sliders");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_sliders".', 'good-wine-shop' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_slides");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_slides".', 'good-wine-shop' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_static_slides");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_static_slides".', 'good-wine-shop' ) . ' ' . ($res->get_error_message()) );
		}
	}
}

// Import posts
if ( !function_exists( 'good_wine_shop_revslider_importer_import' ) ) {
	
	function good_wine_shop_revslider_importer_import($importer, $action) {
		if ( $action == 'import_revslider' && !empty($importer->options['files'][$importer->options['demo_type']]['file_with_revsliders']) ) {
			if (file_exists(WP_PLUGIN_DIR . '/revslider/revslider.php')) {
				require_once WP_PLUGIN_DIR . '/revslider/revslider.php';
				if ($importer->options['debug']) dfl( esc_html__('Import Revolution sliders', 'good-wine-shop') );
				// Process next slider
				$slider = new RevSlider();
				$sliders = $importer->options['files'][$importer->options['demo_type']]['file_with_revsliders'];
				$attempt = !empty($_POST['attempt']) ? (int) good_wine_shop_get_value_gpc('attempt')+1 : 1;
				for ($i=0; $i<count($sliders); $i++) {
					if ($i+1 <= $importer->last_slider) {
						if ($importer->options['debug']) 
							dfl( sprintf(esc_html__('Skip previously loaded file: %s', 'good-wine-shop'), basename($sliders[$i])) );
						continue;
					}
					if ($importer->options['debug']) 
						dfl( sprintf(esc_html__('Process slider "%s". Attempt %d.', 'good-wine-shop'), basename($sliders[$i]), $attempt) );
					$need_del = false;
					if (!is_array($_FILES)) $_FILES = array();
					if (substr($sliders[$i], 0, 5)=='http:' || substr($sliders[$i], 0, 6)=='https:') {
						$tm = round( 0.9 * max(30, ini_get('max_execution_time')));
						$response = download_url($sliders[$i], $tm);
						if (is_string($response)) {
							$_FILES["import_file"] = array("tmp_name" => $response);
							$need_del = true;
							unset($importer->response['attempt']);
						}
					} else
						$_FILES["import_file"] = array("tmp_name" => good_wine_shop_get_file_dir($sliders[$i]));
					if (!empty($_FILES["import_file"]["tmp_name"])) {
						$response = $slider->importSliderFromPost();
						if ($need_del && file_exists($_FILES["import_file"]["tmp_name"]))
							unlink($_FILES["import_file"]["tmp_name"]);
					} else
						$response = array("success" => false);
					if ($response["success"] == false) {
						$msg = sprintf(esc_html__('Revolution Slider "%s" import error. Attempt %d.', 'good-wine-shop'), basename($sliders[$i]), $attempt);
						if ($attempt < 3) {
							$importer->response['attempt'] = $attempt;
						} else {
							unset($importer->response['attempt']);
							$importer->response['error'] = $msg;
						}
						if ($importer->options['debug'])  {
							dfl( $msg );
							dfo( $response );
						}
					} else {
						if ($importer->options['debug']) 
							dfl( sprintf(esc_html__('Slider "%s" imported', 'good-wine-shop'), basename($sliders[$i])) );
					}
					break;
				}
				// Write last slider into log
				$num = $i + (empty($importer->response['attempt']) ? 1 : 0);
				good_wine_shop_fpc($importer->import_log, $num < count($sliders) ? '0|100|'.$num : '');
				$importer->response['result'] = min(100, round($num / count($sliders) * 100));
			} else {
				if ($importer->options['debug']) 
					dfl( sprintf(esc_html__('Can not locate plugin Revolution Slider: %s', 'good-wine-shop'), WP_PLUGIN_DIR.'/revslider/revslider.php') );
			}
		}
	}
}

// Display import progress
if ( !function_exists( 'good_wine_shop_revslider_importer_import_fields' ) ) {
	
	function good_wine_shop_revslider_importer_import_fields($importer) {
		?>
		<tr class="import_revslider">
			<td class="import_progress_item"><?php esc_html_e('Revolution Slider', 'good-wine-shop'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}
?>