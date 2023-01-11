<?php
/**
 * Shortcode: Socials
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_socials_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_socials_load_scripts_front');
	function trx_addons_sc_socials_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-sc_socials', trx_addons_get_file_url('shortcodes/socials/socials.css'), array(), null );
		}
	}
}
	
// Merge contact form specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_socials_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_socials_merge_styles');
	function trx_addons_sc_socials_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('shortcodes/socials/socials.css'));
	}
}



// trx_sc_socials
//-------------------------------------------------------------
/*
[trx_sc_socials id="unique_id" icons="encoded_json_data"]
*/
if ( !function_exists( 'trx_addons_sc_socials' ) ) {
	function trx_addons_sc_socials($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_socials', $atts, array(
			// Individual params
			"type" => "default",
			"icons" => "",
			"align" => "",
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

		if (function_exists('vc_param_group_parse_atts'))
			$atts['icons'] = (array) vc_param_group_parse_atts( $atts['icons'] );
		if (!is_array($atts['icons']) || count($atts['icons']) == 0) return '';

		ob_start();
		set_query_var('trx_addons_args_sc_socials', $atts);
		if (($fdir = trx_addons_get_file_dir('shortcodes/socials/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_socials', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_socials", "trx_addons_sc_socials");
}


// Add [trx_sc_socials] in the VC shortcodes list
if (!function_exists('trx_addons_sc_socials_add_in_vc')) {
	function trx_addons_sc_socials_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_socials",
				"name" => esc_html__("Socials", 'trx_addons'),
				"description" => wp_kses_data( __("Insert social icons with links on your profiles", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_socials',
				"class" => "trx_sc_socials",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Layout", 'trx_addons'),
						"description" => wp_kses_data( __("Select shortcodes's layout", 'trx_addons') ),
						"admin_label" => true,
						"class" => "",
						"std" => "default",
						"value" => apply_filters('trx_addons_sc_type', array(
							esc_html__('Default', 'trx_addons') => 'default'
						), 'trx_sc_socials' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Icons alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment of the icons", 'trx_addons') ),
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
						'type' => 'param_group',
						'param_name' => 'icons',
						'heading' => esc_html__( 'Icons', 'trx_addons' ),
						"description" => wp_kses_data( __("Select social icons and specify link for each item", 'trx_addons') ),
						'value' => urlencode( json_encode( array(
							array(
								'link' => '',
								'icon_fontawesome' => 'empty',
								'icon_openiconic' => 'empty',
								'icon_typicons' => 'empty',
								'icon_entypo' => 'empty',
								'icon_linecons' => 'empty'
							),
						) ) ),
						'params' => array_merge(array(
								array(
									'type' => 'textfield',
									'heading' => esc_html__( 'Link', 'trx_addons' ),
									'param_name' => 'link',
									'admin_label' => true,
									'description' => esc_html__( 'URL to the social profile', 'trx_addons' ),
								),
							), trx_addons_vc_add_icon_param() ),
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
				)
			), 'trx_sc_socials' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Socials extends WPBakeryShortCode {}
		}

	}
	add_action('after_setup_theme', 'trx_addons_sc_socials_add_in_vc', 11);
}
?>