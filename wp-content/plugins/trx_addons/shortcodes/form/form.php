<?php
/**
 * Shortcode: Form
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_form_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_form_load_scripts_front');
	function trx_addons_sc_form_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-sc_form', trx_addons_get_file_url('shortcodes/form/form.css'), array(), null );
			// Load this script always, because it used for the comments and other forms also
			trx_addons_enqueue_script('trx_addons-sc_form', trx_addons_get_file_url('shortcodes/form/form.js'), array('jquery'), null, true );
		}
	}
}
	
// Merge contact form specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_form_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_form_merge_styles');
	function trx_addons_sc_form_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('shortcodes/form/form.css'));
	}
}

	
// Merge contact form specific scripts into single file
if ( !function_exists( 'trx_addons_sc_form_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_form_merge_scripts');
	function trx_addons_sc_form_merge_scripts($js) {
		return $js . file_get_contents(trx_addons_get_file_dir('shortcodes/form/form.js'));
	}
}


// AJAX handler for the send_form action
if ( !function_exists( 'trx_addons_sc_form_ajax_send_sc_form' ) ) {
	add_action('wp_ajax_send_sc_form',			'trx_addons_sc_form_ajax_send_sc_form');
	add_action('wp_ajax_nopriv_send_sc_form',	'trx_addons_sc_form_ajax_send_sc_form');
	function trx_addons_sc_form_ajax_send_sc_form() {
		
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		
		$response = array('error'=>'');
		
		parse_str($_POST['data'], $post_data);
		$post_data = wp_unslash($post_data);
		
		$contact_email = !empty($post_data['form_data']) && (int) $post_data['form_data'] > 0
			? get_transient(sprintf("trx_addons_form_data_%d", (int) $post_data['form_data']))
			: '';
		if (empty($contact_email) && !($contact_email = get_option('admin_email')))
			$response['error'] = esc_html__('Unknown admin email!', 'trx_addons');
		else {
			$user_name	= !empty($post_data['name']) ? $post_data['name'] : '';
			$user_email	= !empty($post_data['email']) ? $post_data['email'] : '';
			$user_website	= !empty($post_data['website']) ? $post_data['website'] : '';
			$user_msg	= !empty($post_data['message']) ? $post_data['message'] : '';
			
			// Attention! Strings below not need html-escaping, because mail is a plain text
			$subj = sprintf(__('Site %s - Contact form message from %s', 'trx_addons'), get_bloginfo('site_name'), $user_name);
			$msg = (!empty($user_name)	? "\n".sprintf(__('Name: %s', 'trx_addons'), $user_name) : '')
			       .  (!empty($user_email) ? "\n".sprintf(__('E-mail: %s', 'trx_addons'), $user_email) : '')
			       .  (!empty($user_website) ? "\n".sprintf(__('Website: %s', 'trx_addons'), $user_website) : '');
			// Extra fields
			foreach ($post_data as $k=>$v) {
				if (in_array($k, array('name', 'email', 'website', 'message', 'form_data'))) continue;
				$msg .= "\n".sprintf("%s: %s", ucfirst($k), $v);
			}
			// Message and site info
			$msg .= (!empty($user_msg)	? "\n\n".trim($user_msg) : '')
			        .  "\n\n............. " . get_bloginfo('site_name') . " (" . esc_url(home_url('/')) . ") ............";
			
			// Try send message via wp_mail(). If failed - try via native php mail() function
			$contact_email = str_replace('|', ',', $contact_email);
			if (!wp_mail($contact_email, $subj, $msg) && !mail($contact_email, $subj, $msg)) {
				$response['error'] = esc_html__('Error send message!', 'trx_addons');
			}
			
			echo json_encode($response);
			die();
		}
	}
}



// trx_sc_form
//-------------------------------------------------------------
/*
[trx_sc_form id="unique_id" style="default"]
*/
if ( !function_exists( 'trx_addons_sc_form' ) ) {
	function trx_addons_sc_form($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_form', $atts, array(
			// Individual params
			"type" => "default",
			"style" => "inherit",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"title_align" => "left",
			"title_style" => "default",
			"labels" => 0,
			"phone" => "",
			"email" => "",
			"address" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);

		set_query_var('trx_addons_args_sc_form', $atts);
		
		ob_start();
		if (($fdir = trx_addons_get_file_dir('shortcodes/form/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_form', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_form", "trx_addons_sc_form");
}


// Add [trx_sc_form] in the VC shortcodes list
if (!function_exists('trx_addons_sc_form_add_in_vc')) {
	function trx_addons_sc_form_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_form",
				"name" => esc_html__("Form", 'trx_addons'),
				"description" => wp_kses_data( __("Insert simple or detailed form", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_form',
				"class" => "trx_sc_form",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Layout", 'trx_addons'),
						"description" => wp_kses_data( __("Select form's layout", 'trx_addons') ),
						"admin_label" => true,
						"class" => "",
						"std" => "default",
						"value" => apply_filters('trx_addons_sc_type', array(
							esc_html__('Default', 'trx_addons') => 'default',
							esc_html__('Modern', 'trx_addons') => 'modern',
							esc_html__('Enquire', 'trx_addons') => 'enquire',
							esc_html__('Detailed', 'trx_addons') => 'detailed'
						), 'trx_sc_form' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", 'trx_addons'),
						"description" => wp_kses_data( __("Select input's style", 'trx_addons') ),
						"admin_label" => true,
						"class" => "",
						"std" => "inherit",
						"value" => array_flip(trx_addons_get_list_input_hover(true)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Fields alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment of the field's text", 'trx_addons') ),
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
						"param_name" => "labels",
						"heading" => esc_html__("Field labels", 'trx_addons'),
						"description" => wp_kses_data( __("Show field's labels", 'trx_addons') ),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array(esc_html__("Show labels", 'trx_addons') => "1" ),
						"type" => "checkbox"
					),
					array(
						'param_name' => 'phone',
						'heading' => esc_html__( 'Your phone', 'trx_addons' ),
						'description' => esc_html__( 'Specify your phone for the detailed form', 'trx_addons' ),
						'dependency' => array(
							'element' => 'type',
							'value' => array('modern', 'detailed')
						),
						'type' => 'textfield',
					),
					array(
						'param_name' => 'email',
						'heading' => esc_html__( 'Your E-mail', 'trx_addons' ),
						'description' => esc_html__( 'Specify your E-mail for the detailed form', 'trx_addons' ),
						'dependency' => array(
							'element' => 'type',
							'value' => array('modern', 'detailed')
						),
						'type' => 'textfield',
					),
					array(
						'param_name' => 'address',
						'heading' => esc_html__( 'Your address', 'trx_addons' ),
						'description' => esc_html__( 'Specify your address for the detailed form', 'trx_addons' ),
						'dependency' => array(
							'element' => 'type',
							'value' => array('modern', 'detailed')
						),
						'type' => 'textfield',
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
						"group" => esc_html__('Titles', 'trx_addons'),
						"admin_label" => true,
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
			), 'trx_sc_form' ) );
			
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Trx_Sc_Form extends WPBakeryShortCode {}
		}

	}
	add_action('after_setup_theme', 'trx_addons_sc_form_add_in_vc', 11);
}
?>