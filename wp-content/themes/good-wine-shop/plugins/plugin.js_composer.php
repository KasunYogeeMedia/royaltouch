<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_vc_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_vc_theme_setup9', 9 );
	function good_wine_shop_vc_theme_setup9() {
		if (good_wine_shop_exists_visual_composer()) {
			add_action( 'wp_enqueue_scripts', 										'good_wine_shop_vc_frontend_scripts', 1100 );
			add_filter( 'good_wine_shop_filter_merge_styles',						'good_wine_shop_vc_merge_styles' );
			add_filter( 'good_wine_shop_filter_merge_scripts',						'good_wine_shop_vc_merge_scripts' );
			add_filter( 'good_wine_shop_filter_get_css',							'good_wine_shop_vc_get_css', 10, 3 );
			if (is_admin()) {
				add_filter( 'good_wine_shop_filter_importer_options',				'good_wine_shop_vc_importer_set_options' );
				add_action( 'good_wine_shop_action_importer_params',				'good_wine_shop_vc_importer_show_params', 10, 1 );
				add_action( 'good_wine_shop_action_importer_import',				'good_wine_shop_vc_importer_import', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import_fields',		'good_wine_shop_vc_importer_import_fields', 10, 1 );
			}
	
			// Add/Remove params in the standard VC shortcodes
			//-----------------------------------------------------
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,					'good_wine_shop_vc_add_params_classes', 10, 3 );
			
			// Color scheme
			$scheme = array(
				"param_name" => "scheme",
				"heading" => esc_html__("Color scheme", 'good-wine-shop'),
				"description" => wp_kses_data( __("Select color scheme to decorate this block", 'good-wine-shop') ),
				"group" => esc_html__('Colors', 'good-wine-shop'),
				"admin_label" => true,
				"value" => array_flip(good_wine_shop_get_list_schemes(true)),
				"type" => "dropdown"
			);
			vc_add_param("vc_row", $scheme);
			vc_add_param("vc_row_inner", $scheme);
			vc_add_param("vc_column", $scheme);
			vc_add_param("vc_column_inner", $scheme);
			vc_add_param("vc_column_text", $scheme);
			
			// Alter height and hide on mobile for Empty Space
			vc_add_param("vc_empty_space", array(
				"param_name" => "alter_height",
				"heading" => esc_html__("Alter height", 'good-wine-shop'),
				"description" => wp_kses_data( __("Select alternative height instead value from the field above", 'good-wine-shop') ),
				"admin_label" => true,
				"value" => array(
					esc_html__('Tiny', 'good-wine-shop') => 'tiny',
					esc_html__('Small', 'good-wine-shop') => 'small',
					esc_html__('Medium', 'good-wine-shop') => 'medium',
					esc_html__('Large', 'good-wine-shop') => 'large',
					esc_html__('Huge', 'good-wine-shop') => 'huge',
					esc_html__('From the value above', 'good-wine-shop') => 'none'
				),
				"type" => "dropdown"
			));
			vc_add_param("vc_empty_space", array(
				"param_name" => "hide_on_mobile",
				"heading" => esc_html__("Hide on mobile", 'good-wine-shop'),
				"description" => wp_kses_data( __("Hide this block on the mobile devices, when the columns are arranged one under another", 'good-wine-shop') ),
				"admin_label" => true,
				"std" => 0,
				"value" => array(esc_html__("Hide on mobile", 'good-wine-shop') => "1" ),
				"type" => "checkbox"
			));
			
			// Add Narrow style to the Progress bars
			vc_add_param("vc_progress_bar", array(
				"param_name" => "narrow",
				"heading" => esc_html__("Narrow", 'good-wine-shop'),
				"description" => wp_kses_data( __("Use narrow style for the progress bar", 'good-wine-shop') ),
				"std" => 0,
				"value" => array(esc_html__("Narrow style", 'good-wine-shop') => "1" ),
				"type" => "checkbox"
			));
			
			// Add param 'Closeable' to the Message Box
			vc_add_param("vc_message", array(
				"param_name" => "closeable",
				"heading" => esc_html__("Closeable", 'good-wine-shop'),
				"description" => wp_kses_data( __("Add 'Close' button to the message box", 'good-wine-shop') ),
				"std" => 0,
				"value" => array(esc_html__("Closeable", 'good-wine-shop') => "1" ),
				"type" => "checkbox"
			));
		}
		if (is_admin()) {
			add_filter( 'good_wine_shop_filter_importer_required_plugins',		'good_wine_shop_vc_importer_required_plugins', 10, 2 );
			add_filter( 'good_wine_shop_filter_tgmpa_required_plugins',		'good_wine_shop_vc_tgmpa_required_plugins' );
			add_filter( 'vc_iconpicker-type-fontawesome',						'good_wine_shop_vc_iconpicker_type_fontawesome' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'good_wine_shop_exists_visual_composer' ) ) {
	function good_wine_shop_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'good_wine_shop_vc_is_frontend' ) ) {
	function good_wine_shop_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'good_wine_shop_vc_tgmpa_required_plugins' ) ) {
	
	function good_wine_shop_vc_tgmpa_required_plugins($list=array()) {
		if (in_array('js_composer', good_wine_shop_storage_get('required_plugins'))) {
			$path = good_wine_shop_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder', 'good-wine-shop'),
					'slug' 		=> 'js_composer',
                    'version'	=> '6.1',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}
	
// Enqueue VC custom styles
if ( !function_exists( 'good_wine_shop_vc_frontend_scripts' ) ) {
	
	function good_wine_shop_vc_frontend_scripts() {
		if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('css/plugin.js_composer.css')))
            wp_enqueue_style( 'good-wine-shop-plugin-js-composer',  good_wine_shop_get_file_url('css/plugin.js_composer.css'), array(), null );
		if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('js/plugin.js_composer.js')))
            wp_enqueue_script( 'good-wine-shop-plugin-js-composer', good_wine_shop_get_file_url('js/plugin.js_composer.js'), array('jquery') );
	}
}
	
// Merge custom styles
if ( !function_exists( 'good_wine_shop_vc_merge_styles' ) ) {
	
	function good_wine_shop_vc_merge_styles($css) {
		return $css . good_wine_shop_fgc(good_wine_shop_get_file_dir('css/plugin.js_composer.css'));
	}
}
	
// Merge custom scripts
if ( !function_exists( 'good_wine_shop_vc_merge_scripts' ) ) {
	
	function good_wine_shop_vc_merge_scripts($js) {
		return $js . good_wine_shop_fgc(good_wine_shop_get_file_dir('js/plugin.js_composer.js'));
	}
}
	
// Add theme icons into VC iconpicker list
if ( !function_exists( 'good_wine_shop_vc_iconpicker_type_fontawesome' ) ) {
	
	function good_wine_shop_vc_iconpicker_type_fontawesome($icons) {
		$list = good_wine_shop_get_list_icons();
		if (!is_array($list) || count($list) == 0) return $icons;
		$rez = array();
		foreach ($list as $icon)
			$rez[] = array($icon => str_replace('icon-', '', $icon));
		return array_merge( $icons, array(esc_html__('Theme Icons', 'good-wine-shop') => $rez) );
	}
}




// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'good_wine_shop_vc_importer_required_plugins' ) ) {
	
	function good_wine_shop_vc_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'js_composer')!==false && !good_wine_shop_exists_visual_composer())
			$not_installed .= '<br>' . esc_html__('WPBakery PageBuilder', 'good-wine-shop');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'good_wine_shop_vc_importer_set_options' ) ) {
	
	function good_wine_shop_vc_importer_set_options($options=array()) {
		if (in_array('js_composer', good_wine_shop_storage_get('required_plugins')) && good_wine_shop_exists_visual_composer()) {
			$options['additional_options'][] = 'wpb_js_templates';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'good_wine_shop_vc_importer_show_params' ) ) {
	
	function good_wine_shop_vc_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('js_composer', good_wine_shop_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_js_composer" id="import_js_composer" /> <label for="import_js_composer"><?php esc_html_e('Import WPBakery PageBuilder', 'good-wine-shop'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'good_wine_shop_vc_importer_import' ) ) {
	
	function good_wine_shop_vc_importer_import($importer, $action) {
		if ( $action == 'import_js_composer' ) {
			// ToDo: place here actions to import VC specific posts, metadata, options, etc.
		}
	}
}

// Display import progress
if ( !function_exists( 'good_wine_shop_vc_importer_import_fields' ) ) {
	
	function good_wine_shop_vc_importer_import_fields($importer) {
		?>
		<tr class="import_js_composer">
			<td class="import_progress_item"><?php esc_html_e('WPBakery PageBuilder meta', 'good-wine-shop'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add params in the standard VC shortcodes
if ( !function_exists( 'good_wine_shop_vc_add_params_classes' ) ) {
	
	function good_wine_shop_vc_add_params_classes($classes, $sc, $atts) {
		if (in_array($sc, array('vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'))) {
			if (!empty($atts['scheme']) && !good_wine_shop_is_inherit($atts['scheme']))
				$classes .= ($classes ? ' ' : '') . 'scheme_' . $atts['scheme'];
		} else if (in_array($sc, array('vc_empty_space'))) {
			if (!empty($atts['alter_height']) && !good_wine_shop_is_off($atts['alter_height']))
				$classes .= ($classes ? ' ' : '') . 'height_' . $atts['alter_height'];
			if (!empty($atts['hide_on_mobile']) && (int) $atts['hide_on_mobile'] == 1)
				$classes .= ($classes ? ' ' : '') . 'hide_on_mobile';
		} else if (in_array($sc, array('vc_progress_bar'))) {
			if (!empty($atts['narrow']) && (int) $atts['narrow']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_progress_bar_narrow';
		} else if (in_array($sc, array('vc_message'))) {
			if (!empty($atts['closeable']) && (int) $atts['closeable']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_message_box_closeable';
		}
		return $classes;
	}
}


// Add VC specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'good_wine_shop_vc_get_css' ) ) {
	
	function good_wine_shop_vc_get_css($css, $colors, $fonts) {
		if (isset($css['colors'])) {
			$css['fonts'] .= <<<CSS

.vc_tta .vc_tta-panel-title {
	font-family: {$fonts['p']['family']};
}
.vc_message_box,
.vc_widget_video + .wpb_text_column,
.vc_widget_slider + .wpb_text_column {
	font-family: {$fonts['h5']['family']};
}

CSS;
		}

		if (isset($css['colors'])) {
			$css['colors'] .= <<<CSS

/* Row and columns */
.scheme_self.wpb_row,
.scheme_self.wpb_column,
.scheme_self.wpb_text_column {
	color: {$colors['text']};
	background-color: {$colors['bg_color']};
}
.scheme_self.vc_row.vc_parallax[class*="scheme_"] .vc_parallax-inner:before {
	background-color: {$colors['bg_color_alpha']};
}

/* Accordion */
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_link']} 50%, {$colors['text_dark']} 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_link']} 50%, {$colors['text_dark']} 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon.sc_button_hover_slide_top {	background: linear-gradient(to bottom,	{$colors['text_link']} 50%, {$colors['text_dark']} 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon.sc_button_hover_slide_bottom {background: linear-gradient(to top,		{$colors['text_link']} 50%, {$colors['text_dark']} 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:before,
.vc_tta.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:after {
	border-color: {$colors['inverse_text']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a {
	color: {$colors['text_dark']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover {
	color: {$colors['text_link']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .sc_button_hover_slide_left {	background-position: left bottom !important; }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .sc_button_hover_slide_right {	background-position: right bottom !important; }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .sc_button_hover_slide_top {	background-position: right top !important; }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .sc_button_hover_slide_bottom {	background-position: right bottom !important; }

.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title > a:hover .vc_tta-controls-icon {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:before,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a .vc_tta-controls-icon:after {
	border-color: {$colors['inverse_text']};
}

/* Tabs */
.vc_tta.vc_general .vc_tta-tab > a {
	color: {$colors['text_dark']};
}

.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active > a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}

.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a.sc_button_hover_slide_top {		background: linear-gradient(to bottom,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active > a.sc_button_hover_slide_left {		background: linear-gradient(to right,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active > a.sc_button_hover_slide_right {		background: linear-gradient(to left,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active > a.sc_button_hover_slide_top {		background: linear-gradient(to bottom,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active > a.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab:hover > a,
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:hover {
	color: {$colors['inverse_text']};
}
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab:hover > a:not([class*="sc_button_hover_"]),
.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['text_link']};
}

/* Separator */
.vc_separator.vc_sep_color_grey .vc_sep_line {
	border-color: {$colors['bd_color']};
}

/* Progress bar */
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar {
	background-color: {$colors['alter_bg_color']};
}
.vc_progress_bar.vc_progress_bar_narrow.vc_progress-bar-color-bar_red .vc_single_bar .vc_bar {
	background-color: {$colors['alter_link']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label {
	color: {$colors['text_dark']};
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	color: {$colors['text_light']};
}

CSS;
		}
		
		return $css;
	}
}
?>