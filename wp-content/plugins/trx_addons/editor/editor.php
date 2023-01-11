<?php
/**
 * Add buttons in the WP text editor
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

	
// Load required styles and scripts for admin mode
if ( !function_exists( 'trx_addons_editor_load_scripts_admin' ) ) {
	add_action("admin_enqueue_scripts", 'trx_addons_editor_load_scripts_admin');
	function trx_addons_editor_load_scripts_admin() {
		// Add styles in the WP text editor
		add_editor_style( array(
							trx_addons_get_file_url('css/font-icons/css/trx_addons_icons-embedded.css'),
							trx_addons_get_file_url('editor/css/trx_addons.editor.css')
							)
						 );	
	}
}
	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_editor_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_editor_load_scripts_front');
	function trx_addons_editor_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-editor', trx_addons_get_file_url('editor/css/trx_addons.editor.css'), array(), null );
		}
	}
}
	
// Merge editor specific styles into single stylesheet
if ( !function_exists( 'trx_addons_editor_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_editor_merge_styles');
	function trx_addons_editor_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('editor/css/trx_addons.editor.css'));
	}
}



// Init TinyMCE
//--------------------------------------------------------------
if ( !function_exists( 'trx_addons_editor_init' ) ) {
	add_filter( 'tiny_mce_before_init', 'trx_addons_editor_init');
	function trx_addons_editor_init($opt) {
		
		$style_formats = array(
			array(
				'title' => esc_html__('Headers', 'trx_addons'),
				'items' => array(
					array(
						'title' => esc_html__('No margin', 'trx_addons'),
						'selector' => 'h1,h2,h3,h4,h5,h6',
						'classes' => 'trx_addons_no_margins'
					)
				)
			),
			array(
				'title' => esc_html__('Blockquotes', 'trx_addons'),
				'items' => array(
					array(
						'title' => esc_html__('Style 1', 'trx_addons'),
						'selector' => 'blockquote',
						'classes' => 'trx_addons_blockquote_style_1'
					),
					array(
						'title' => esc_html__('Style 2', 'trx_addons'),
						'selector' => 'blockquote',
						'classes' => 'trx_addons_blockquote_style_2'
					)
				)
			),
			array(
				'title' => esc_html__('List styles', 'trx_addons'),
				'items' => array(
					array(
						'title' => esc_html__('Asterisk', 'trx_addons'),
						'selector' => 'ul',
						'classes' => 'trx_addons_list trx_addons_list_asterisk'
					),
					array(
						'title' => esc_html__('Circle', 'trx_addons'),
						'selector' => 'ul',
						'classes' => 'trx_addons_list trx_addons_list_circle'
					),
					array(
						'title' => esc_html__('Dot', 'trx_addons'),
						'selector' => 'ul',
						'classes' => 'trx_addons_list trx_addons_list_dot'
					),
					array(
						'title' => esc_html__('Parameters', 'trx_addons'),
						'selector' => 'ul',
						'classes' => 'trx_addons_list trx_addons_list_parameters'
					),
				)
			),
			array(
				'title' => esc_html__('Inline', 'trx_addons'),
				'items' => array(
					array(
						'title' => esc_html__('Accent text', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_accent'
					),
					array(
						'title' => esc_html__('Accent background', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_accent_bg'
					),
					array(
						'title' => esc_html__('Dark text', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_dark'
					),
					array(
						'title' => esc_html__('Inverse text', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_inverse'
					),
					array(
						'title' => esc_html__('Big font', 'trx_addons'),
						'inline' => 'big'
					),
					array(
						'title' => esc_html__('Small font', 'trx_addons'),
						'inline' => 'small'
					),
					array(
						'title' => esc_html__('Tiny text', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_tiny_text'
					),
					array(
						'title' => esc_html__('Dropcap 1', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_dropcap trx_addons_dropcap_style_1'
					),
					array(
						'title' => esc_html__('Dropcap 2', 'trx_addons'),
						'inline' => 'span',
						'classes' => 'trx_addons_dropcap trx_addons_dropcap_style_2'
					),
				)
			)
		);
		/*
		array(
			'title' => 'Warning Box',
			'block' => 'div',
			'classes' => 'warning box',
			'wrapper' => true
		),
		array(
			'title' => 'Red Uppercase Text',
			'inline' => 'span',
			'styles' => array(
				'color' => '#ff0000',
				'fontWeight' => 'bold',
				'textTransform' => 'uppercase'
			)
		)
		*/
		$opt['style_formats'] = json_encode( $style_formats );		
		return $opt;
	}
}

// Add buttons in array
if ( !function_exists( 'trx_addons_editor_add_buttons' ) ) {
	add_filter( 'mce_external_plugins', 'trx_addons_editor_add_buttons' );
	function trx_addons_editor_add_buttons($buttons) {
		$buttons['trx_addons'] = trx_addons_get_file_url('editor/js/trx_addons.editor.js');
		return $buttons;
	}
}

// Register buttons in TinyMCE
if ( !function_exists( 'trx_addons_editor_register_buttons' ) ) {
	add_filter( 'mce_buttons', 'trx_addons_editor_register_buttons' );
	function trx_addons_editor_register_buttons($buttons) {
		array_push( $buttons, 'styleselect', 'trx_addons_tooltip' );
		return $buttons;
	}
}

// Register buttons 2 in TinyMCE
if ( !function_exists( 'trx_addons_editor_register_buttons_2' ) ) {
	add_filter( 'mce_buttons_2', 'trx_addons_editor_register_buttons_2' );
	function trx_addons_editor_register_buttons_2($buttons) {
		array_splice( $buttons, 1, 0, array('sub', 'sup') );
		return $buttons;
	}
}
?>