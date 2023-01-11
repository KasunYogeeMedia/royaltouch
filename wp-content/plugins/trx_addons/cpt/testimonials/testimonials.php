<?php
/**
 * ThemeREX Addons Custom post type: Testimonials
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// -----------------------------------------------------------------
// -- Custom post type registration
// -----------------------------------------------------------------

// Define Custom post type and taxonomy constants
if ( ! defined('TRX_ADDONS_CPT_TESTIMONIALS_PT') ) define('TRX_ADDONS_CPT_TESTIMONIALS_PT', 'cpt_testimonial');
if ( ! defined('TRX_ADDONS_CPT_TESTIMONIALS_TAXONOMY') ) define('TRX_ADDONS_CPT_TESTIMONIALS_TAXONOMY', 'cpt_testimonials_group');


// Register post type and taxonomy
if (!function_exists('trx_addons_cpt_testimonials_init')) {
	add_action( 'init', 'trx_addons_cpt_testimonials_init' );
	function trx_addons_cpt_testimonials_init() {

		// Add Testimonials to the Meta Box support
		global $TRX_ADDONS_STORAGE;	// Need to declare global, because this file included inside autoload function!
		$TRX_ADDONS_STORAGE['post_types'][] = TRX_ADDONS_CPT_TESTIMONIALS_PT;
		$TRX_ADDONS_STORAGE['meta_box_'.TRX_ADDONS_CPT_TESTIMONIALS_PT] = array(
			"subtitle" => array(
				"title" => esc_html__("Item's subtitle",  'trx_addons'),
				"desc" => wp_kses_data( __("Testimonial author's position or any other text", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			)
		);

		// Register post type and taxonomy
		register_post_type( TRX_ADDONS_CPT_TESTIMONIALS_PT, array(
			'label'               => esc_html__( 'Testimonial', 'trx_addons' ),
			'description'         => esc_html__( 'Testimonial Description', 'trx_addons' ),
			'labels'              => array(
				'name'                => esc_html__( 'Testimonials', 'trx_addons' ),
				'singular_name'       => esc_html__( 'Testimonial', 'trx_addons' ),
				'menu_name'           => esc_html__( 'Testimonials', 'trx_addons' ),
				'parent_item_colon'   => esc_html__( 'Parent Item:', 'trx_addons' ),
				'all_items'           => esc_html__( 'All Testimonials', 'trx_addons' ),
				'view_item'           => esc_html__( 'View Testimonial', 'trx_addons' ),
				'add_new_item'        => esc_html__( 'Add New Testimonial', 'trx_addons' ),
				'add_new'             => esc_html__( 'Add New', 'trx_addons' ),
				'edit_item'           => esc_html__( 'Edit Testimonial', 'trx_addons' ),
				'update_item'         => esc_html__( 'Update Testimonial', 'trx_addons' ),
				'search_items'        => esc_html__( 'Search Testimonial', 'trx_addons' ),
				'not_found'           => esc_html__( 'Not found', 'trx_addons' ),
				'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'trx_addons' ),
			),
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt'),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'menu_icon'			  => 'dashicons-format-status',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => '59.4',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			)
		);

		register_taxonomy( TRX_ADDONS_CPT_TESTIMONIALS_TAXONOMY, TRX_ADDONS_CPT_TESTIMONIALS_PT, array(
			'post_type' 		=> TRX_ADDONS_CPT_TESTIMONIALS_PT,
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => esc_html__( 'Testimonials Group', 'trx_addons' ),
				'singular_name'     => esc_html__( 'Group', 'trx_addons' ),
				'search_items'      => esc_html__( 'Search Groups', 'trx_addons' ),
				'all_items'         => esc_html__( 'All Groups', 'trx_addons' ),
				'parent_item'       => esc_html__( 'Parent Group', 'trx_addons' ),
				'parent_item_colon' => esc_html__( 'Parent Group:', 'trx_addons' ),
				'edit_item'         => esc_html__( 'Edit Group', 'trx_addons' ),
				'update_item'       => esc_html__( 'Update Group', 'trx_addons' ),
				'add_new_item'      => esc_html__( 'Add New Group', 'trx_addons' ),
				'new_item_name'     => esc_html__( 'New Group Name', 'trx_addons' ),
				'menu_name'         => esc_html__( 'Testimonial Group', 'trx_addons' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => TRX_ADDONS_CPT_TESTIMONIALS_TAXONOMY )
			)
		);
	}
}

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_cpt_testimonials_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_cpt_testimonials_load_scripts_front');
	function trx_addons_cpt_testimonials_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-cpt_testimonials', trx_addons_get_file_url('cpt/testimonials/testimonials.css'), array(), null );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_cpt_testimonials_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_cpt_testimonials_merge_styles');
	function trx_addons_cpt_testimonials_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('cpt/testimonials/testimonials.css'));
	}
}



// trx_sc_testimonials
//-------------------------------------------------------------
/*
[trx_sc_testimonials id="unique_id" type="default" cat="category_slug or id" count="3" columns="3" slider="0|1"]
*/
if ( !function_exists( 'trx_addons_sc_testimonials' ) ) {
	function trx_addons_sc_testimonials($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_testimonials', $atts, array(
			// Individual params
			"type" => "default",
			"columns" => "",
			"cat" => "",
			"count" => 3,
			"slider" => 0,
			"slides_space" => 0,
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

		$atts['count'] = max(1, (int) $atts['count']);
		$atts['slider'] = max(0, (int) $atts['slider']);

		ob_start();
		set_query_var('trx_addons_args_sc_testimonials', $atts);
		if (($fdir = trx_addons_get_file_dir('cpt/testimonials/tpl.'.trx_addons_esc($atts['type']).'.php')) != '') { include $fdir; }
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_testimonials', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_sc_testimonials", "trx_addons_sc_testimonials");
}


// Add [trx_sc_testimonials] in the VC shortcodes list
if (!function_exists('trx_addons_sc_testimonials_add_in_vc')) {
	function trx_addons_sc_testimonials_add_in_vc() {

		if (!trx_addons_exists_visual_composer()) return;

		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_testimonials",
				"name" => esc_html__("Testimonials", 'trx_addons'),
				"description" => wp_kses_data( __("Display testimonials from specified group", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_testimonials',
				"class" => "trx_sc_testimonials",
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
							esc_html__('Simple', 'trx_addons') => 'simple'
						), 'trx_sc_testimonials' ),
						"type" => "dropdown"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Group", 'trx_addons'),
						"description" => wp_kses_data( __("Testimonials group", 'trx_addons') ),
						"value" => array_merge(array(esc_html__('- Select category -', 'trx_addons') => 0), array_flip(trx_addons_get_list_terms(false, TRX_ADDONS_CPT_TESTIMONIALS_TAXONOMY))),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Count", 'trx_addons'),
						"description" => wp_kses_data( __("Specify number of items to display", 'trx_addons') ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'trx_addons'),
						"description" => wp_kses_data( __("Specify number of columns. If empty - auto detect by items number", 'trx_addons') ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'trx_addons'),
						"description" => wp_kses_data( __("Show items as slider", 'trx_addons') ),
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Slider", 'trx_addons') => "1" ),
						"admin_label" => true,
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
						"param_name" => "title_style",
						"heading" => esc_html__("Title style", 'trx_addons'),
						"description" => wp_kses_data( __("Select style of the title and subtitle", 'trx_addons') ),
						"group" => esc_html__('Titles', 'trx_addons'),
						"admin_label" => true,
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
			), 'trx_sc_testimonials' ) );
			
		class WPBakeryShortCode_Trx_Sc_Testimonials extends WPBakeryShortCode {}

	}
	add_action('after_setup_theme', 'trx_addons_sc_testimonials_add_in_vc', 11);
}
?>