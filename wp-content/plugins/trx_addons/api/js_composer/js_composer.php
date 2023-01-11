<?php
/**
 * Plugin support: WPBakery Page Builder
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


// Check if WPBakery Page Builder installed and activated
// Attention! This function is used in many files and was moved to the api.php
/*
if ( !function_exists( 'trx_addons_exists_visual_composer' ) ) {
	function trx_addons_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}
*/



// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_vc_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_vc_importer_required_plugins', 10, 2 );
	function trx_addons_vc_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'js_composer')!==false && !trx_addons_exists_visual_composer())
			$not_installed .= '<br>' . esc_html__('WPBakery Page Builder', 'trx_addons');
		return $not_installed;
	}
}

// Check if WPBakery Page Builder in frontend editor mode
if ( !function_exists( 'trx_addons_vc_is_frontend' ) ) {
	function trx_addons_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Return icon params for the VC
if ( !function_exists( 'trx_addons_vc_add_icon_param' ) ) {
	function trx_addons_vc_add_icon_param($group='') {
		$params = array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'trx_addons' ),
						'value' => array(
							__( 'Font Awesome', 'trx_addons' ) => 'fontawesome',
/*
							__( 'Open Iconic', 'trx_addons' ) => 'openiconic',
							__( 'Typicons', 'trx_addons' ) => 'typicons',
							__( 'Entypo', 'trx_addons' ) => 'entypo',
							__( 'Linecons', 'trx_addons' ) => 'linecons'
*/
						),
						'std' => '',
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'trx_addons' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => esc_html__( 'Icon', 'trx_addons' ),
						'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
						'param_name' => 'icon_fontawesome',
						'value' => '',
						'settings' => array(
							'emptyIcon' => true,						// default true, display an "EMPTY" icon?
							'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
							'type' => 'fontawesome'

						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'fontawesome',
						),
					),
/*
					array(
						'type' => 'iconpicker',
						'heading' => esc_html__( 'Icon', 'trx_addons' ),
						'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
						'param_name' => 'icon_openiconic',
						'value' => '',
						'settings' => array(
							'emptyIcon' => true,						// default true, display an "EMPTY" icon?
							'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
							'type' => 'openiconic'
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'openiconic',
						),
					),
					array(
						'type' => 'iconpicker',
						'heading' => esc_html__( 'Icon', 'trx_addons' ),
						'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
						'param_name' => 'icon_typicons',
						'value' => '',
						'settings' => array(
							'emptyIcon' => true,						// default true, display an "EMPTY" icon?
							'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
							'type' => 'typicons',
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'typicons',
						),
					),
					array(
						'type' => 'iconpicker',
						'heading' => esc_html__( 'Icon', 'trx_addons' ),
						'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
						'param_name' => 'icon_entypo',
						'value' => '',
						'settings' => array(
							'emptyIcon' => true,						// default true, display an "EMPTY" icon?
							'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
							'type' => 'entypo',
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'entypo',
						),
					),
					array(
						'type' => 'iconpicker',
						'heading' => esc_html__( 'Icon', 'trx_addons' ),
						'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
						'param_name' => 'icon_linecons',
						'value' => '',
						'settings' => array(
							'emptyIcon' => true,						// default true, display an "EMPTY" icon?
							'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
							'type' => 'linecons',
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'linecons',
						),
					)
*/					
				);

		// Add param 'group' if not empty
		if (!empty($group))
			foreach ($params as $k=>$v)
				$params[$k]['group'] = $group;

		return $params;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_vc_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_vc_importer_set_options' );
	function trx_addons_vc_importer_set_options($options=array()) {
		if ( trx_addons_exists_visual_composer() && in_array('js_composer', $options['required_plugins']) ) {
			$options['additional_options'][] = 'wpb_js_templates';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'trx_addons_vc_importer_check_row' ) ) {
	if (is_admin()) add_filter('trx_addons_filter_importer_import_row', 'trx_addons_vc_importer_check_row', 9, 4);
	function trx_addons_vc_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'js_composer')===false) return $flag;
		if ( trx_addons_exists_visual_composer() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='vc_grid_item';
		}
		return $flag;
	}
}

?>