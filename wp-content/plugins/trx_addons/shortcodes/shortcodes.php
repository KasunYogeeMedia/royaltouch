<?php
/**
 * ThemeREX Shortcodes
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Include files with shortcodes
if (!function_exists('trx_addons_sc_load')) {
	add_action( 'after_setup_theme', 'trx_addons_sc_load', 2 );
	add_action( 'trx_addons_action_save_options', 'trx_addons_sc_load', 9 );
	function trx_addons_sc_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		$trx_addons_shortcodes = apply_filters('trx_addons_sc_list', array(
			'action',
			'anchor',
			'blogger',
			'button',
			'content',
			'form',
			'googlemap',
			'icons',
			'price',
			'promo',
			'skills',
			'socials',
			'table'
			)
		);
		if (is_array($trx_addons_shortcodes) && count($trx_addons_shortcodes) > 0) {
			foreach ($trx_addons_shortcodes as $s) {
				if ( ($fdir = trx_addons_get_file_dir("shortcodes/{$s}/{$s}.php")) != '') { include_once $fdir; }
			}
		}
	}
}


	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_load_scripts_front');
	function trx_addons_sc_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			trx_addons_enqueue_style( 'trx_addons-sc', trx_addons_get_file_url('shortcodes/shortcodes.css'), array(), null );
			trx_addons_enqueue_script( 'trx_addons-sc', trx_addons_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );
		}
	}
}

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_sc_merge_styles');
	function trx_addons_sc_merge_styles($css) {
		return $css . file_get_contents(trx_addons_get_file_dir('shortcodes/shortcodes.css'));
	}
}

	
// Merge shortcode's specific scripts into single file
if ( !function_exists( 'trx_addons_sc_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_merge_scripts');
	function trx_addons_sc_merge_scripts($js) {
		return $js . file_get_contents(trx_addons_get_file_dir('shortcodes/shortcodes.js'));
	}
}

// Check if shortcode name is in the stack
if (!function_exists('trx_addons_sc_stack_check')) {
    function trx_addons_sc_stack_check($sc) {
        global $TRX_ADDONS_STORAGE;
        return in_array($sc, $TRX_ADDONS_STORAGE['sc_stack']);
    }
}


// Shortcodes parts
//---------------------------------------

// Prepare Id, custom CSS and other parameters in the shortcode's atts
if (!function_exists('trx_addons_sc_prepare_atts')) {
	function trx_addons_sc_prepare_atts($sc, $atts, $defa) {
		// Merge atts with default values
		$atts = trx_addons_html_decode(shortcode_atts(apply_filters('trx_addons_sc_atts', $defa, $sc), $atts));
		// Unsafe item description
		if (!empty($atts['description']))
			$atts['description'] = trim( vc_value_from_safe( $atts['description'] ) );
		// Generate id (if empty)
        if (empty($atts['id']))
        	$atts['id'] = str_replace('trx_', '', $sc) . '_' . str_replace('.', '', mt_rand());
        // Add custom CSS class
        if (!empty($atts['css'])
            && (trx_addons_sc_stack_check('show_layout_vc') || strpos($atts['css'], '.vc_custom_') !== false)
            && defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')
            && function_exists('vc_shortcode_custom_css_class')
        ) {
            $atts['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
                (!empty($atts['class']) ? $atts['class'] . ' ' : '') . vc_shortcode_custom_css_class( $atts['css'], ' ' ),
                $sc,
                $atts);
            $atts['css'] = '';
        }
 		return apply_filters('trx_addons_filter_sc_prepare_atts', $atts, $sc);
	}
}

// Enqueue iconed fonts
if (!function_exists('trx_addons_enqueue_icons')) {
	function trx_addons_enqueue_icons($list='') {
		if (!empty($list) && function_exists('vc_icon_element_fonts_enqueue')) {
			$list = explode(',', $list);
			foreach ($list as $icon_type)
				vc_icon_element_fonts_enqueue($icon_type);
		}
	}
}

// Display title, subtitle and description for some shortcodes
if (!function_exists('trx_addons_sc_show_titles')) {
	function trx_addons_sc_show_titles($sc, $args, $size='') {
		$align = !empty($args['title_align']) ? ' sc_align_'.trim($args['title_align']) : '';
		$style = !empty($args['title_style']) ? ' sc_item_title_style_'.trim($args['title_style']) : '';
		if (!empty($args['title'])) {
			if (empty($size)) $size = is_page() ? 'large' : 'normal';
			$title_tag = apply_filters('trx_addons_filter_sc_item_title_tag', 'large' == $size ? 'h2' : ('tiny' == $size ? 'h4' : 'h3'));
			?><<?php echo esc_attr($title_tag); ?> class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_title_class', 'sc_item_title '.$sc.'_title'.$align.$style, $sc)); ?>"><?php echo trim(trx_addons_str_decorate($args['title'])); ?></<?php echo esc_attr($title_tag); ?>><?php
		}
        if (!empty($args['subtitle'])) {
            ?><h6 class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_subtitle_class', 'sc_item_subtitle '.$sc.'_subtitle'.$align.$style, $sc)); ?>"><?php echo trim(trx_addons_str_decorate($args['subtitle'])); ?></h6><?php
        }
		if (!empty($args['description'])) {
			?><div class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_description_class', 'sc_item_descr '.$sc.'_descr'.$align, $sc)); ?>"><?php echo do_shortcode(trx_addons_str_decorate($args['description'])); ?></div><?php
		}
	}
}

// Display link button or image for some shortcodes
if (!function_exists('trx_addons_sc_show_links')) {
	function trx_addons_sc_show_links($sc, $args) {
		$align = !empty($args['title_align']) ? ' sc_align_'.trim($args['title_align']) : '';
		if (!empty($args['link_image'])) {
			$args['link_image'] = trx_addons_get_attachment_url($args['link_image'], trx_addons_get_thumb_size('medium'));
			$attr = trx_addons_getimagesize($args['link_image']);
			?><div class="<?php echo esc_attr($sc); ?>_button_image sc_item_button_image<?php echo esc_attr($align); ?>"><?php
				if (!empty($args['link'])) {
					?><a href="<?php echo esc_url($args['link']); ?>"><?php
				}
				?><img src="<?php echo esc_url($args['link_image']); ?>" alt=""<?php echo (!empty($attr[3]) ? ' '.trim($attr[3]) : ''); ?>><?php
				if (!empty($args['link'])) {
					?></a><?php
				}
			?></div><?php
		} else if (!empty($args['link']) && !empty($args['link_text'])) {
			if (empty($args['link_style'])) $args['link_style'] = 'default';
			echo str_replace('sc_item_button', 'sc_item_button sc_item_button_'.esc_attr($args['link_style']).' '.esc_attr($sc).'_button'.esc_attr($align), trx_addons_sc_button(apply_filters('trx_addons_filter_sc_item_button_args', array(
				'type' => $args['link_style'],
				'title' => $args['link_text'],
				'link' => $args['link']
				), $sc)));
		}
	}
}

// Show post meta block: post date, author, categories, counters, etc.
if ( !function_exists('trx_addons_sc_show_post_meta') ) {
	function trx_addons_sc_show_post_meta($sc, $args=array()) {
		$args = array_merge(array(
			'categories' => false,
			'date' => false,
			'edit' => false,
			'seo' => false,
			'share' => false,
			'counters' => ''
			), $args);
		?><div class="<?php echo esc_attr($sc); ?>_post_meta post_meta"><?php
			// Post categories
			if ( !empty($args['categories']) ) {
				?><span class="post_meta_item post_categories"><?php the_category( ', ' ); ?></span><?php
			}
			// Post date
			if ( !empty($args['date']) && in_array( get_post_type(), array( 'post', 'page', 'attachment' ) ) ) {
				?><span class="post_meta_item post_date<?php if (!empty($args['seo'])) echo ' date updated'; ?>"<?php if (!empty($args['seo'])) echo ' itemprop="datePublished"'; ?>><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo get_the_date(); ?></a></span><?php
			}
			// Post counters
			if ( !empty($args['counters']) ) {
				echo str_replace('post_counters_item', 'post_meta_item post_counters_item', trx_addons_get_post_counters($counters));
			}
			// Socials share
			if ( !empty($args['share']) ) {
				$output = trx_addons_get_share_links(array(
						'type' => 'drop',
						'caption' => esc_html__('Share', 'trx_addons'),
						'echo' => false
					));
				if ($output) {
					?><span class="post_meta_item post_share"><?php echo trim($output); ?></span><?php
				}
			}
			// Edit page link
			if ( !empty($args['edit']) ) {
				edit_post_link( esc_html__( 'Edit', 'trx_addons' ), '<span class="post_meta_item post_edit">', '</span>' );
			}
		?></div><!-- .post_meta --><?php
	}
}
?>