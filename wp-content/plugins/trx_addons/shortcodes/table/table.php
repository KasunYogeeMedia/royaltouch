<?php
/**
 * Shortcode: Table
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.3
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_table_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_table_load_scripts_front');
	function trx_addons_sc_table_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-sc_table', trx_addons_get_file_url('shortcodes/table/table.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_table_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_table_merge_styles');
	function trx_addons_sc_table_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('shortcodes/table/table.css'));
	}
}


// trx_sc_table
//-------------------------------------------------------------
/*
[trx_sc_table id="unique_id" style="default" aligh="left"]
*/
if ( !function_exists( 'trx_addons_sc_table' ) ) {
	function trx_addons_sc_table($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_table', $atts, array(
			// Individual params
			"type" => "default",
			"width" => "100%",
			"align" => "none",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_image" => '',
			"link_text" => esc_html__('Learn more', 'trx_addons'),
			"title_align" => "left",
			"title_style" => "default",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		$atts['css'] .= trx_addons_get_css_dimensions_from_values($atts['width']);

		$atts['content'] = do_shortcode(str_replace(
											array('<p><table', 'table></p>', '><br />'),
											array('<table', 'table>', '>'),
											html_entity_decode($content, ENT_COMPAT, 'UTF-8')
											)
							);
		
		set_query_var('trx_addons_args_sc_table', $atts);
		
		ob_start();
		if (($fdir = trx_addons_get_file_dir('shortcodes/table/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_table', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_table", "trx_addons_sc_table");
}


// Add [trx_sc_table] in the VC shortcodes list
if (!function_exists('trx_addons_sc_table_add_in_vc')) {
	function trx_addons_sc_table_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_table",
				"name" => esc_html__("Table", 'trx_addons'),
				"description" => wp_kses_data( __("Insert a table", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_table',
				"class" => "trx_sc_table",
				'content_element' => true,
				'is_container' => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Layout", 'trx_addons'),
						"description" => wp_kses_data( __("Select shortcode's layout", 'trx_addons') ),
						"admin_label" => true,
						"std" => "default",
						"value" => apply_filters('trx_addons_sc_type', array(
							esc_html__('Default', 'trx_addons') => 'default'
						), 'trx_sc_table' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Table alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment of the table", 'trx_addons') ),
						"admin_label" => true,
						"value" => array(
							esc_html__('None', 'trx_addons') => 'none',
							esc_html__('Left', 'trx_addons') => 'left',
							esc_html__('Center', 'trx_addons') => 'center',
							esc_html__('Right', 'trx_addons') => 'right'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "width",
						"heading" => esc_html__("Width", 'trx_addons'),
						"description" => wp_kses_data( __("Width of the table", 'trx_addons') ),
						"value" => '100%',
						"type" => "textfield"
					),
					array(
						'heading' => __( 'Content', 'trx_addons' ),
						"description" => wp_kses_data( __("Content, created with any table-generator, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/", 'trx_addons') ),
						'param_name' => 'content',
						'value' => '',
						'holder' => 'div',
						'type' => 'textarea_html',
					),
					array(
						"param_name" => "title_style",
						"heading" => esc_html__("Title style", 'trx_addons'),
						"description" => wp_kses_data( __("Select style of the title and subtitle", 'trx_addons') ),
						"admin_label" => true,
						"group" => esc_html__('Titles', 'trx_addons'),
						"std" => "default",
						"value" => apply_filters('trx_addons_sc_title_style', array(
							esc_html__('Default', 'trx_addons') => 'default',
							esc_html__('Shadow', 'trx_addons') => 'shadow'
						), 'trx_sc_action' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title_align",
						"heading" => esc_html__("Title alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment of the title, subtitle and description", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"std" => "default",
						"value" => array(
							esc_html__('Default', 'trx_addons') => 'default',
							esc_html__('Left', 'trx_addons') => 'left',
							esc_html__('Center', 'trx_addons') => 'center',
							esc_html__('Right', 'trx_addons') => 'right'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => wp_kses_data( __("Title of the block. Enclose any words in {{ and }} to accent them", 'trx_addons') ),
						"admin_label" => true,
						"group" => esc_html__('Titles', 'trx_addons'),
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'trx_addons'),
						"description" => wp_kses_data( __("Subtitle for the block", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'trx_addons'),
						"description" => wp_kses_data( __("Description of this block", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"type" => "textarea_safe"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'trx_addons'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => esc_html__("Button's text", 'trx_addons'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"type" => "textfield"
					),
					array(
						"param_name" => "link_image",
						"heading" => esc_html__("Button's image", 'trx_addons'),
						"description" => wp_kses_data( __("Select the promo image from the library for this button", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"type" => "attach_image"
					),
					// Common VC parameters
					'id' => array(
						"param_name" => "id",
						"heading" => esc_html__("Element ID", 'trx_addons'),
						"description" => wp_kses_data( __("ID for current element", 'trx_addons') ),
						"group" => esc_html__('ID &amp; Class', 'trx_addons'),
						"admin_label" => true,
						"type" => "textfield"
					),
					'class' => array(
						"param_name" => "class",
						"heading" => esc_html__("Element CSS class", 'trx_addons'),
						"description" => wp_kses_data( __("CSS class for current element", 'trx_addons') ),
						"group" => esc_html__('ID &amp; Class', 'trx_addons'),
						"admin_label" => true,
						"type" => "textfield"
					),
					'css' => array(
						'param_name' => 'css',
						'heading' => __( 'CSS box', 'trx_addons' ),
						'group' => __( 'Design Options', 'trx_addons' ),
						'type' => 'css_editor'
					)
				),
				
			), 'trx_sc_table' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Table extends WPBakeryShortCode {}
		}

	}
	add_action('after_setup_theme', 'trx_addons_sc_table_add_in_vc', 11);
}
?>