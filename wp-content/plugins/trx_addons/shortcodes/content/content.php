<?php
/**
 * Shortcode: Content container
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_content_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_content_load_scripts_front');
	function trx_addons_sc_content_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-sc_content', trx_addons_get_file_url('shortcodes/content/content.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_content_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_content_merge_styles');
	function trx_addons_sc_content_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('shortcodes/content/content.css'));
	}
}


// trx_sc_content
//-------------------------------------------------------------
/*
[trx_sc_content id="unique_id" width="1/2"]
*/
if ( !function_exists( 'trx_addons_sc_content' ) ) {
	function trx_addons_sc_content($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_content', $atts, array(
			// Individual params
			'type' => 'default',
			"width" => "none",
			"float" => 'center',
			"align" => "",
			"padding" => "",
            "colors" => 'normal',
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
		
		$output = '';
		
		$atts['content'] = do_shortcode($content);
		
		if (!empty($atts['content']) || !empty($atts['title']) || !empty($atts['subtitle']) || !empty($atts['description'])) {

			set_query_var('trx_addons_args_sc_content', $atts);

			ob_start();
			if (($fdir = trx_addons_get_file_dir('shortcodes/content/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
			$output = ob_get_contents();
			ob_end_clean();

		}
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_content', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_content", "trx_addons_sc_content");
}


// Add [trx_sc_content] in the VC shortcodes list
if (!function_exists('trx_addons_sc_content_add_in_vc')) {
	function trx_addons_sc_content_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_content",
				"name" => esc_html__("Content area", 'trx_addons'),
				"description" => wp_kses_data( __("Limit content width inside the fullwide rows", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_content',
				"class" => "trx_sc_content",
				'content_element' => true,
				'is_container' => true,
				"js_view" => 'VcColumnView',
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Layout", 'trx_addons'),
						"description" => wp_kses_data( __("Select shortcode's layout", 'trx_addons') ),
						"admin_label" => true,
						"std" => "default",
						"value" => apply_filters('trx_addons_sc_type', array(
							esc_html__('Default', 'trx_addons') => 'default',
						), 'trx_sc_content' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "width",
						"heading" => esc_html__("Width", 'trx_addons'),
						"description" => wp_kses_data( __("Select width of the block", 'trx_addons') ),
						"admin_label" => true,
						"value" => apply_filters('trx_addons_sc_content_width', array(
							esc_html__('Default', 'trx_addons') => 'none',
							esc_html__('1/1', 'trx_addons') => '1_1',
							esc_html__('1/2', 'trx_addons') => '1_2',
							esc_html__('1/3', 'trx_addons') => '1_3',
							esc_html__('2/3', 'trx_addons') => '2_3',
							esc_html__('1/4', 'trx_addons') => '1_4',
							esc_html__('3/4', 'trx_addons') => '3_4'
						)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "float",
						"heading" => esc_html__("Block alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment (floating position) of the block", 'trx_addons') ),
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
						"param_name" => "align",
						"heading" => esc_html__("Text alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment of the inner text in the block", 'trx_addons') ),
						"admin_label" => true,
						"value" => array(
							esc_html__('None', 'trx_addons') => 'none',
							esc_html__('Left', 'trx_addons') => 'left',
							esc_html__('Center', 'trx_addons') => 'center',
							esc_html__('Right', 'trx_addons') => 'right',
							esc_html__('Justify', 'trx_addons') => 'justify'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "padding",
						"heading" => esc_html__("Inner paddings", 'trx_addons'),
						"description" => wp_kses_data( __("Select paddings around of the inner text in the block", 'trx_addons') ),
						"value" => array(
							esc_html__('None', 'trx_addons') => 'none',
							esc_html__('Small', 'trx_addons') => 'small',
							esc_html__('Medium', 'trx_addons') => 'medium',
							esc_html__('Large', 'trx_addons') => 'large'
						),
						"type" => "dropdown"
					),
                    array(
                        "param_name" => "colors",
                        "heading" => esc_html__("Text and titles color", 'trx_addons'),
                        "description" => wp_kses_data( __("Select text and titles color in the block", 'trx_addons') ),
                        "admin_label" => true,
                        "value" => array(
                            esc_html__('Normal', 'trx_addons') => 'normal',
                            esc_html__('Light', 'trx_addons') => 'light'
                        ),
                        "type" => "dropdown"
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
				
			), 'trx_sc_content' ) );
			
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Trx_Sc_Content extends WPBakeryShortCodesContainer {}
		}

	}
	add_action('after_setup_theme', 'trx_addons_sc_content_add_in_vc', 11);
}
?>