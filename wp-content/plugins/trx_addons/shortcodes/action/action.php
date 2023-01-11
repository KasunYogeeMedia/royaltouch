<?php
/**
 * Shortcode: Action
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_action_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_action_load_scripts_front');
	function trx_addons_sc_action_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-sc_action', trx_addons_get_file_url('shortcodes/action/action.css'), array(), null );
		}
	}
}
	
// Merge contact form specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_action_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_action_merge_styles');
	function trx_addons_sc_action_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('shortcodes/action/action.css'));
	}
}



// trx_sc_action
//-------------------------------------------------------------
/*
[trx_sc_action id="unique_id" columns="2" values="encoded_json_data"]
*/
if ( !function_exists( 'trx_addons_sc_action' ) ) {
	function trx_addons_sc_action($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_action', $atts, array(
			// Individual params
			"type" => "default",
			"columns" => "",
			"slider" => 0,
			"slides_space" => 0,
			"actions" => "",
			"bg_image" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_image" => '',
			"link_text" => esc_html__('Learn more', 'trx_addons'),
			"title_align" => "left",
			"title_style" => "default",
            "sticker" => 0,
			// Dimensions
			"full_height" => 0,
			"height" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);

		if (function_exists('vc_param_group_parse_atts'))
			$atts['actions'] = (array) vc_param_group_parse_atts( $atts['actions'] );
		if (!is_array($atts['actions']) || count($atts['actions']) == 0) return '';

		if (empty($atts['columns'])) $atts['columns'] = count($atts['actions']);
		$atts['columns'] = max(1, min(count($atts['actions']), $atts['columns']));
		$atts['slider'] = $atts['slider'] > 0 && count($atts['actions']) > $atts['columns'];
		$atts['slides_space'] = max(0, (int) $atts['slides_space']);

		if (!empty($atts['height'])) $atts['css'] = 'height:'.trim($atts['height']).';overflow:hidden;'.$atts['css'];

		foreach ($atts['actions'] as $k=>$v)
			if (!empty($v['description'])) $atts['actions'][$k]['description'] = preg_replace( '/\\[(.*)\\]/', '<b>$1</b>', vc_value_from_safe( $v['description'] ) );

		ob_start();
		set_query_var('trx_addons_args_sc_action', $atts);
		if (($fdir = trx_addons_get_file_dir('shortcodes/action/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_action', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_action", "trx_addons_sc_action");
}


// Add [trx_sc_action] in the VC shortcodes list
if (!function_exists('trx_addons_sc_action_add_in_vc')) {
	function trx_addons_sc_action_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_action",
				"name" => esc_html__("Action", 'trx_addons'),
				"description" => wp_kses_data( __("Insert 'Call to action' or custom Events as slider or columns layout", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_action',
				"class" => "trx_sc_action",
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
							esc_html__('Default', 'trx_addons') => 'default',
							esc_html__('Event', 'trx_addons') => 'event'
						), 'trx_sc_action' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'trx_addons'),
						"description" => wp_kses_data( __("Specify number of columns for icons. If empty - auto detect by items number", 'trx_addons') ),
						"type" => "textfield"
					),
                    array(
                        'param_name' => 'sticker',
                        'heading' => esc_html__( 'Case Specials', 'trx_addons' ),
                        'description' => esc_html__( 'Enable "Case Specials" sticker', 'trx_addons' ),
                        "std" => "0",
                        "value" => array(esc_html__("Enable", 'trx_addons') => "1" ),
                        "type" => "checkbox"
                    ),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'trx_addons'),
						"description" => wp_kses_data( __("Show items as slider", 'trx_addons') ),
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Slider", 'trx_addons') => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space", 'trx_addons'),
						"description" => wp_kses_data( __("Space between slides", 'trx_addons') ),
						'dependency' => array(
							'element' => 'slider',
							'value' => '1'
						),
						"value" => "0",
						"type" => "textfield"
					),
					array(
						'type' => 'param_group',
						'param_name' => 'actions',
						'heading' => esc_html__( 'Actions', 'trx_addons' ),
						"description" => wp_kses_data( __("Select icons, specify title and/or description for each item", 'trx_addons') ),
						'value' => urlencode( json_encode( array(
							array(
								'title' => esc_html__( 'One', 'trx_addons' ),
								'subtitle' => '',
								'description' => '',
								'link' => '',
								'link_text' => '',
								'bg_image' => '',
								'date' => '',
								'info' => '',
								'color' => '',
								'image' => '',
								'icon_fontawesome' => 'empty',
								'icon_openiconic' => 'empty',
								'icon_typicons' => 'empty',
								'icon_entypo' => 'empty',
								'icon_linecons' => 'empty'
							),
						) ) ),
						'params' => array_merge(array(
								array(
									'param_name' => 'title',
									'heading' => esc_html__( 'Title', 'trx_addons' ),
									'description' => esc_html__( 'Enter title of the item', 'trx_addons' ),
									'type' => 'textfield',
								),
								array(
									'param_name' => 'subtitle',
									'heading' => esc_html__( 'Subtitle', 'trx_addons' ),
									'description' => esc_html__( 'Enter subtitle of the item', 'trx_addons' ),
									'type' => 'textfield',
								),
								array(
									'param_name' => 'date',
									'heading' => esc_html__( 'Date', 'trx_addons' ),
									'description' => esc_html__( 'Specify date (and/or time) of this event', 'trx_addons' ),
									'type' => 'textfield',
								),
								array(
									'param_name' => 'info',
									'heading' => esc_html__( 'Info', 'trx_addons' ),
									'description' => esc_html__( 'Additional info for this item', 'trx_addons' ),
									'type' => 'textfield',
								),
								array(
									'type' => 'textarea_safe',
									'heading' => esc_html__( 'Description', 'trx_addons' ),
									'param_name' => 'description',
									'description' => esc_html__( 'Enter short description of the item', 'trx_addons' ),
								),
								array(
									'type' => 'textfield',
									'heading' => esc_html__( 'Link', 'trx_addons' ),
									'param_name' => 'link',
									'description' => esc_html__( 'URL to link this item', 'trx_addons' ),
								),
								array(
									"param_name" => "link_text",
									"heading" => esc_html__("Link's text", 'trx_addons'),
									"description" => wp_kses_data( __("Caption of the item's link", 'trx_addons') ),
									"type" => "textfield"
								),
								array(
									'type' => 'colorpicker',
									'heading' => esc_html__( 'Color', 'trx_addons' ),
									'param_name' => 'color',
									'description' => esc_html__( 'Select custom color for this item', 'trx_addons' ),
								),
								array(
									"param_name" => "image",
									"heading" => esc_html__("Image", 'trx_addons'),
									"description" => wp_kses_data( __("Select or upload image or specify URL from other site to use it as item's icon", 'trx_addons') ),
									"type" => "attach_image"
								),
								array(
									"param_name" => "bg_image",
									"heading" => esc_html__("Background image", 'trx_addons'),
									"description" => wp_kses_data( __("Select or upload image or specify URL from other site to use it as background of this item", 'trx_addons') ),
									"type" => "attach_image"
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
					array(
						"param_name" => "height",
						"heading" => esc_html__("Height", 'trx_addons'),
						"description" => wp_kses_data( __("Height of the block", 'trx_addons') ),
						"group" => esc_html__('Dimensions', 'trx_addons'),
						"type" => "textfield"
					),
					array(
						"param_name" => "full_height",
						"heading" => esc_html__("Full height", 'trx_addons'),
						"description" => wp_kses_data( __("Stretch the height of the element to the full screen's height", 'trx_addons') ),
						"group" => esc_html__('Dimensions', 'trx_addons'),
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Full Height", 'trx_addons') => "1" ),
						"type" => "checkbox"
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
			), 'trx_sc_action' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Action extends WPBakeryShortCode {}
		}

	}
	add_action('after_setup_theme', 'trx_addons_sc_action_add_in_vc', 11);
}
?>