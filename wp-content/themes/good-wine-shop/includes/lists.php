<?php
/**
 * Theme lists
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return numbers range
if ( !function_exists( 'good_wine_shop_get_list_range' ) ) {
	function good_wine_shop_get_list_range($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = $i;
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}



// Return styles list
if ( !function_exists( 'good_wine_shop_get_list_styles' ) ) {
	function good_wine_shop_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'good-wine-shop'), $i);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}



// Return schemes list
if ( !function_exists( 'good_wine_shop_get_list_schemes' ) ) {
	function good_wine_shop_get_list_schemes($prepend_inherit=false) {
		if (($list = good_wine_shop_storage_get('list_schemes'))=='') {
			$list = good_wine_shop_get_theme_schemes();
			good_wine_shop_storage_set('list_schemes', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the headers
if ( !function_exists( 'good_wine_shop_get_list_header_styles' ) ) {
	function good_wine_shop_get_list_header_styles($prepend_inherit=false) {
		$list = array(
			'header-1'	=> esc_html__('Header 1',	'good-wine-shop'),
			'header-2'	=> esc_html__('Header 2',	'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the header's positions
if ( !function_exists( 'good_wine_shop_get_list_header_positions' ) ) {
	function good_wine_shop_get_list_header_positions($prepend_inherit=false) {
		$list = array(
			'default'	=> esc_html__('Default','good-wine-shop'),
			'over'		=> esc_html__('Over',	'good-wine-shop'),
			'under'		=> esc_html__('Under',	'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the menu
if ( !function_exists( 'good_wine_shop_get_list_menu_styles' ) ) {
	function good_wine_shop_get_list_menu_styles($prepend_inherit=false) {
		$list = array(
			'top'	=> esc_html__('Top menu',	'good-wine-shop'),
			'side'	=> esc_html__('Side menu',	'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'good_wine_shop_get_list_animations' ) ) {
	function good_wine_shop_get_list_animations($prepend_inherit=false) {
		$list = array(
			'none'			=> esc_html__('- None -',	'good-wine-shop'),
			'bounced'		=> esc_html__('Bounced',	'good-wine-shop'),
			'elastic'		=> esc_html__('Elastic',	'good-wine-shop'),
			'flash'			=> esc_html__('Flash',		'good-wine-shop'),
			'flip'			=> esc_html__('Flip',		'good-wine-shop'),
			'pulse'			=> esc_html__('Pulse',		'good-wine-shop'),
			'rubberBand'	=> esc_html__('Rubber Band','good-wine-shop'),
			'shake'			=> esc_html__('Shake',		'good-wine-shop'),
			'swing'			=> esc_html__('Swing',		'good-wine-shop'),
			'tada'			=> esc_html__('Tada',		'good-wine-shop'),
			'wobble'		=> esc_html__('Wobble',		'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'good_wine_shop_get_list_animations_in' ) ) {
	function good_wine_shop_get_list_animations_in($prepend_inherit=false) {
		$list = array(
			'none'				=> esc_html__('- None -',			'good-wine-shop'),
			'bounceIn'			=> esc_html__('Bounce In',			'good-wine-shop'),
			'bounceInUp'		=> esc_html__('Bounce In Up',		'good-wine-shop'),
			'bounceInDown'		=> esc_html__('Bounce In Down',		'good-wine-shop'),
			'bounceInLeft'		=> esc_html__('Bounce In Left',		'good-wine-shop'),
			'bounceInRight'		=> esc_html__('Bounce In Right',	'good-wine-shop'),
			'elastic'			=> esc_html__('Elastic In',			'good-wine-shop'),
			'fadeIn'			=> esc_html__('Fade In',			'good-wine-shop'),
			'fadeInUp'			=> esc_html__('Fade In Up',			'good-wine-shop'),
			'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'good-wine-shop'),
			'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'good-wine-shop'),
			'fadeInDown'		=> esc_html__('Fade In Down',		'good-wine-shop'),
			'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'good-wine-shop'),
			'fadeInLeft'		=> esc_html__('Fade In Left',		'good-wine-shop'),
			'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'good-wine-shop'),
			'fadeInRight'		=> esc_html__('Fade In Right',		'good-wine-shop'),
			'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'good-wine-shop'),
			'flipInX'			=> esc_html__('Flip In X',			'good-wine-shop'),
			'flipInY'			=> esc_html__('Flip In Y',			'good-wine-shop'),
			'lightSpeedIn'		=> esc_html__('Light Speed In',		'good-wine-shop'),
			'rotateIn'			=> esc_html__('Rotate In',			'good-wine-shop'),
			'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','good-wine-shop'),
			'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'good-wine-shop'),
			'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'good-wine-shop'),
			'rotateInDownRight'	=> esc_html__('Rotate In Down Right','good-wine-shop'),
			'rollIn'			=> esc_html__('Roll In',			'good-wine-shop'),
			'slideInUp'			=> esc_html__('Slide In Up',		'good-wine-shop'),
			'slideInDown'		=> esc_html__('Slide In Down',		'good-wine-shop'),
			'slideInLeft'		=> esc_html__('Slide In Left',		'good-wine-shop'),
			'slideInRight'		=> esc_html__('Slide In Right',		'good-wine-shop'),
			'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'good-wine-shop'),
			'zoomIn'			=> esc_html__('Zoom In',			'good-wine-shop'),
			'zoomInUp'			=> esc_html__('Zoom In Up',			'good-wine-shop'),
			'zoomInDown'		=> esc_html__('Zoom In Down',		'good-wine-shop'),
			'zoomInLeft'		=> esc_html__('Zoom In Left',		'good-wine-shop'),
			'zoomInRight'		=> esc_html__('Zoom In Right',		'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'good_wine_shop_get_list_animations_out' ) ) {
	function good_wine_shop_get_list_animations_out($prepend_inherit=false) {
		$list = array(
			'none'			=> esc_html__('- None -',			'good-wine-shop'),
			'bounceOut'		=> esc_html__('Bounce Out',			'good-wine-shop'),
			'bounceOutUp'	=> esc_html__('Bounce Out Up',		'good-wine-shop'),
			'bounceOutDown'	=> esc_html__('Bounce Out Down',	'good-wine-shop'),
			'bounceOutLeft'	=> esc_html__('Bounce Out Left',	'good-wine-shop'),
			'bounceOutRight'=> esc_html__('Bounce Out Right',	'good-wine-shop'),
			'fadeOut'		=> esc_html__('Fade Out',			'good-wine-shop'),
			'fadeOutUp'		=> esc_html__('Fade Out Up',		'good-wine-shop'),
			'fadeOutUpBig'	=> esc_html__('Fade Out Up Big',	'good-wine-shop'),
			'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','good-wine-shop'),
			'fadeOutDownBig'=> esc_html__('Fade Out Down Big',	'good-wine-shop'),
			'fadeOutDown'	=> esc_html__('Fade Out Down',		'good-wine-shop'),
			'fadeOutLeft'	=> esc_html__('Fade Out Left',		'good-wine-shop'),
			'fadeOutLeftBig'=> esc_html__('Fade Out Left Big',	'good-wine-shop'),
			'fadeOutRight'	=> esc_html__('Fade Out Right',		'good-wine-shop'),
			'fadeOutRightBig'=> esc_html__('Fade Out Right Big','good-wine-shop'),
			'flipOutX'		=> esc_html__('Flip Out X',			'good-wine-shop'),
			'flipOutY'		=> esc_html__('Flip Out Y',			'good-wine-shop'),
			'hinge'			=> esc_html__('Hinge Out',			'good-wine-shop'),
			'lightSpeedOut'	=> esc_html__('Light Speed Out',	'good-wine-shop'),
			'rotateOut'		=> esc_html__('Rotate Out',			'good-wine-shop'),
			'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'good-wine-shop'),
			'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',	'good-wine-shop'),
			'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'good-wine-shop'),
			'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'good-wine-shop'),
			'rollOut'			=> esc_html__('Roll Out',		'good-wine-shop'),
			'slideOutUp'		=> esc_html__('Slide Out Up',	'good-wine-shop'),
			'slideOutDown'		=> esc_html__('Slide Out Down',	'good-wine-shop'),
			'slideOutLeft'		=> esc_html__('Slide Out Left',	'good-wine-shop'),
			'slideOutRight'		=> esc_html__('Slide Out Right','good-wine-shop'),
			'zoomOut'			=> esc_html__('Zoom Out',		'good-wine-shop'),
			'zoomOutUp'			=> esc_html__('Zoom Out Up',	'good-wine-shop'),
			'zoomOutDown'		=> esc_html__('Zoom Out Down',	'good-wine-shop'),
			'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'good-wine-shop'),
			'zoomOutRight'		=> esc_html__('Zoom Out Right',	'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('good_wine_shop_get_animation_classes')) {
	function good_wine_shop_get_animation_classes($animation, $speed='normal', $loop='none') {
		return good_wine_shop_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!good_wine_shop_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'good_wine_shop_get_list_categories' ) ) {
	function good_wine_shop_get_list_categories($prepend_inherit=false) {
		if (($list = good_wine_shop_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			good_wine_shop_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'good_wine_shop_get_list_terms' ) ) {
	function good_wine_shop_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = good_wine_shop_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			good_wine_shop_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'good_wine_shop_get_list_posts_types' ) ) {
	function good_wine_shop_get_list_posts_types($prepend_inherit=false) {
		if (($list = good_wine_shop_storage_get('list_posts_types'))=='') {
			$list = get_post_types();
			good_wine_shop_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'good_wine_shop_get_list_posts' ) ) {
	function good_wine_shop_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = good_wine_shop_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'good-wine-shop');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			good_wine_shop_storage_set($hash, $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'good_wine_shop_get_list_users' ) ) {
	function good_wine_shop_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = good_wine_shop_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'good-wine-shop');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			good_wine_shop_storage_set('list_users', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'good_wine_shop_get_list_menus' ) ) {
	function good_wine_shop_get_list_menus($prepend_inherit=false) {
		if (($list = good_wine_shop_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'good-wine-shop');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			good_wine_shop_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'good_wine_shop_get_list_sidebars' ) ) {
	function good_wine_shop_get_list_sidebars($prepend_inherit=false) {
		if (($list = good_wine_shop_storage_get('list_sidebars'))=='') {
			$list = apply_filters('good_wine_shop_filter_list_sidebars', array(
				'sidebar_widgets'		=> esc_html__('Sidebar Widgets', 'good-wine-shop'),
				'header_widgets'		=> esc_html__('Header Widgets', 'good-wine-shop'),
				'above_page_widgets'	=> esc_html__('Above Page Widgets', 'good-wine-shop'),
				'above_content_widgets' => esc_html__('Above Content Widgets', 'good-wine-shop'),
				'below_content_widgets' => esc_html__('Below Content Widgets', 'good-wine-shop'),
				'below_page_widgets' 	=> esc_html__('Below Page Widgets', 'good-wine-shop'),
				'footer_widgets'		=> esc_html__('Footer Widgets', 'good-wine-shop')
				)
			);
			$custom_sidebars_number = max(0, min(10, good_wine_shop_get_theme_setting('custom_sidebars')));
			if (count((array)$custom_sidebars_number) > 0) {
				for ($i=1; $i <= $custom_sidebars_number; $i++) {
					$list['custom_widgets_'.intval($i)] = sprintf(esc_html__('Custom Widgets %d', 'good-wine-shop'), $i);
				}
			}
			good_wine_shop_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'good_wine_shop_get_list_sidebars_positions' ) ) {
	function good_wine_shop_get_list_sidebars_positions($prepend_inherit=false) {
		$list = array(
			'left'  => esc_html__('Left',  'good-wine-shop'),
			'right' => esc_html__('Right', 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'good_wine_shop_get_list_body_styles' ) ) {
	function good_wine_shop_get_list_body_styles($prepend_inherit=false) {
		$list = array(
			'boxed'		=> esc_html__('Boxed',		'good-wine-shop'),
			'wide'		=> esc_html__('Wide',		'good-wine-shop'),
			'fullwide'	=> esc_html__('Fullwide',	'good-wine-shop'),
			'fullscreen'=> esc_html__('Fullscreen',	'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'good_wine_shop_get_list_blog_styles' ) ) {
	function good_wine_shop_get_list_blog_styles($prepend_inherit=false) {
		$list = array(
			'excerpt'	=> esc_html__('Excerpt','good-wine-shop'),
			'classic_2'	=> esc_html__('Classic /2 columns/',	'good-wine-shop'),
			'classic_3'	=> esc_html__('Classic /3 columns/',	'good-wine-shop'),
			'portfolio_2' => esc_html__('Portfolio /2 columns/','good-wine-shop'),
			'portfolio_3' => esc_html__('Portfolio /3 columns/','good-wine-shop'),
			'portfolio_4' => esc_html__('Portfolio /4 columns/','good-wine-shop'),
			'gallery_2' => esc_html__('Gallery /2 columns/',	'good-wine-shop'),
			'gallery_3' => esc_html__('Gallery /3 columns/',	'good-wine-shop'),
			'gallery_4' => esc_html__('Gallery /4 columns/',	'good-wine-shop'),
			'chess_1'	=> esc_html__('Chess /2 column/',		'good-wine-shop'),
			'chess_2'	=> esc_html__('Chess /4 columns/',		'good-wine-shop'),
			'chess_3'	=> esc_html__('Chess /6 columns/',		'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return blog contents list, prepended inherit
if ( !function_exists( 'good_wine_shop_get_list_blog_content' ) ) {
	function good_wine_shop_get_list_blog_content($prepend_inherit=false) {
		$list = array(
			'excerpt'	=> esc_html__('Excerpt',	'good-wine-shop'),
			'fullpost'	=> esc_html__('Full post',	'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with pagination styles
if ( !function_exists( 'good_wine_shop_get_list_paginations' ) ) {
	function good_wine_shop_get_list_paginations($prepend_inherit=false) {
		$list = array(
			'pages'	=> esc_html__("Page numbers", 'good-wine-shop'),
			'links'	=> esc_html__("Older/Newest", 'good-wine-shop'),
			'more'	=> esc_html__("Load more", 'good-wine-shop'),
			'infinite' => esc_html__("Infinite scroll", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'good_wine_shop_get_list_bg_image_positions' ) ) {
	function good_wine_shop_get_list_bg_image_positions($prepend_inherit=false) {
		$list = array(
			'left top'		=> esc_html__('Left Top', 'good-wine-shop'),
			'center top'	=> esc_html__("Center Top", 'good-wine-shop'),
			'right top'		=> esc_html__("Right Top", 'good-wine-shop'),
			'left center'	=> esc_html__("Left Center", 'good-wine-shop'),
			'center center'	=> esc_html__("Center Center", 'good-wine-shop'),
			'right center'	=> esc_html__("Right Center", 'good-wine-shop'),
			'left bottom'	=> esc_html__("Left Bottom", 'good-wine-shop'),
			'center bottom'	=> esc_html__("Center Bottom", 'good-wine-shop'),
			'right bottom'	=> esc_html__("Right Bottom", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'good_wine_shop_get_list_bg_image_repeats' ) ) {
	function good_wine_shop_get_list_bg_image_repeats($prepend_inherit=false) {
		$list = array(
			'repeat'	=> esc_html__('Repeat', 'good-wine-shop'),
			'repeat-x'	=> esc_html__('Repeat X', 'good-wine-shop'),
			'repeat-y'	=> esc_html__('Repeat Y', 'good-wine-shop'),
			'no-repeat'	=> esc_html__('No Repeat', 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'good_wine_shop_get_list_bg_image_attachments' ) ) {
	function good_wine_shop_get_list_bg_image_attachments($prepend_inherit=false) {
		$list = array(
			'scroll'	=> esc_html__('Scroll', 'good-wine-shop'),
			'fixed'		=> esc_html__('Fixed', 'good-wine-shop'),
			'local'		=> esc_html__('Local', 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'good_wine_shop_get_list_yesno' ) ) {
	function good_wine_shop_get_list_yesno($prepend_inherit=false) {
		$list = array(
			"yes"	=> esc_html__("Yes", 'good-wine-shop'),
			"no"	=> esc_html__("No", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'good_wine_shop_get_list_onoff' ) ) {
	function good_wine_shop_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on"	=> esc_html__("On", 'good-wine-shop'),
			"off"	=> esc_html__("Off", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'good_wine_shop_get_list_showhide' ) ) {
	function good_wine_shop_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'good-wine-shop'),
			"hide" => esc_html__("Hide", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'good_wine_shop_get_list_directions' ) ) {
	function good_wine_shop_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'good-wine-shop'),
			"vertical"   => esc_html__("Vertical", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with 'Simple' and 'Advanced' items
if ( !function_exists( 'good_wine_shop_get_list_user_skills' ) ) {
	function good_wine_shop_get_list_user_skills($prepend_inherit=false) {
		$list = array(
			"simple"  => esc_html__("Simple", 'good-wine-shop'),
			"advanced" => esc_html__("Advanced", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'good_wine_shop_get_list_shapes' ) ) {
	function good_wine_shop_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'good-wine-shop'),
			"square" => esc_html__("Square", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'good_wine_shop_get_list_sizes' ) ) {
	function good_wine_shop_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'good-wine-shop'),
			"small"  => esc_html__("Small", 'good-wine-shop'),
			"medium" => esc_html__("Medium", 'good-wine-shop'),
			"large"  => esc_html__("Large", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'good_wine_shop_get_list_floats' ) ) {
	function good_wine_shop_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none"  => esc_html__("None", 'good-wine-shop'),
			"left"  => esc_html__("Float Left", 'good-wine-shop'),
			"right" => esc_html__("Float Right", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'good_wine_shop_get_list_alignments' ) ) {
	function good_wine_shop_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none"	=> esc_html__("None", 'good-wine-shop'),
			"left"	=> esc_html__("Left", 'good-wine-shop'),
			"center"=> esc_html__("Center", 'good-wine-shop'),
			"right"	=> esc_html__("Right", 'good-wine-shop')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'good-wine-shop');
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'good_wine_shop_get_list_columns' ) ) {
	function good_wine_shop_get_list_columns($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'good-wine-shop'),
			"1_1" => esc_html__("100%", 'good-wine-shop'),
			"1_2" => esc_html__("1/2", 'good-wine-shop'),
			"1_3" => esc_html__("1/3", 'good-wine-shop'),
			"2_3" => esc_html__("2/3", 'good-wine-shop'),
			"1_4" => esc_html__("1/4", 'good-wine-shop'),
			"3_4" => esc_html__("3/4", 'good-wine-shop'),
			"1_5" => esc_html__("1/5", 'good-wine-shop'),
			"2_5" => esc_html__("2/5", 'good-wine-shop'),
			"3_5" => esc_html__("3/5", 'good-wine-shop'),
			"4_5" => esc_html__("4/5", 'good-wine-shop'),
			"1_6" => esc_html__("1/6", 'good-wine-shop'),
			"5_6" => esc_html__("5/6", 'good-wine-shop'),
			"1_7" => esc_html__("1/7", 'good-wine-shop'),
			"2_7" => esc_html__("2/7", 'good-wine-shop'),
			"3_7" => esc_html__("3/7", 'good-wine-shop'),
			"4_7" => esc_html__("4/7", 'good-wine-shop'),
			"5_7" => esc_html__("5/7", 'good-wine-shop'),
			"6_7" => esc_html__("6/7", 'good-wine-shop'),
			"1_8" => esc_html__("1/8", 'good-wine-shop'),
			"3_8" => esc_html__("3/8", 'good-wine-shop'),
			"5_8" => esc_html__("5/8", 'good-wine-shop'),
			"7_8" => esc_html__("7/8", 'good-wine-shop'),
			"1_9" => esc_html__("1/9", 'good-wine-shop'),
			"2_9" => esc_html__("2/9", 'good-wine-shop'),
			"4_9" => esc_html__("4/9", 'good-wine-shop'),
			"5_9" => esc_html__("5/9", 'good-wine-shop'),
			"7_9" => esc_html__("7/9", 'good-wine-shop'),
			"8_9" => esc_html__("8/9", 'good-wine-shop'),
			"1_10"=> esc_html__("1/10", 'good-wine-shop'),
			"3_10"=> esc_html__("3/10", 'good-wine-shop'),
			"7_10"=> esc_html__("7/10", 'good-wine-shop'),
			"9_10"=> esc_html__("9/10", 'good-wine-shop'),
			"1_11"=> esc_html__("1/11", 'good-wine-shop'),
			"2_11"=> esc_html__("2/11", 'good-wine-shop'),
			"3_11"=> esc_html__("3/11", 'good-wine-shop'),
			"4_11"=> esc_html__("4/11", 'good-wine-shop'),
			"5_11"=> esc_html__("5/11", 'good-wine-shop'),
			"6_11"=> esc_html__("6/11", 'good-wine-shop'),
			"7_11"=> esc_html__("7/11", 'good-wine-shop'),
			"8_11"=> esc_html__("8/11", 'good-wine-shop'),
			"9_11"=> esc_html__("9/11", 'good-wine-shop'),
			"10_11"=> esc_html__("10/11", 'good-wine-shop'),
			"1_12"=> esc_html__("1/12", 'good-wine-shop'),
			"5_12"=> esc_html__("5/12", 'good-wine-shop'),
			"7_12"=> esc_html__("7/12", 'good-wine-shop'),
			"10_12"=> esc_html__("10/12", 'good-wine-shop'),
			"11_12"=> esc_html__("11/12", 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return list of locations for the featured content
if ( !function_exists( 'good_wine_shop_get_list_featured_locations' ) ) {
	function good_wine_shop_get_list_featured_locations($prepend_inherit=false) {
		$list = array(
			"default" => esc_html__('As in the post defined', 'good-wine-shop'),
			"center"  => esc_html__('Above the text of the post', 'good-wine-shop'),
			"left"    => esc_html__('To the left the text of the post', 'good-wine-shop'),
			"right"   => esc_html__('To the right the text of the post', 'good-wine-shop'),
			"alter"   => esc_html__('Alternates for each post', 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'good_wine_shop_get_post_format_name' ) ) {
	function good_wine_shop_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'good-wine-shop') : esc_html__('galleries', 'good-wine-shop');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'good-wine-shop') : esc_html__('videos', 'good-wine-shop');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'good-wine-shop') : esc_html__('audios', 'good-wine-shop');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'good-wine-shop') : esc_html__('images', 'good-wine-shop');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'good-wine-shop') : esc_html__('quotes', 'good-wine-shop');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'good-wine-shop') : esc_html__('links', 'good-wine-shop');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'good-wine-shop') : esc_html__('statuses', 'good-wine-shop');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'good-wine-shop') : esc_html__('asides', 'good-wine-shop');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'good-wine-shop') : esc_html__('chats', 'good-wine-shop');
		else						$name = $single ? esc_html__('standard', 'good-wine-shop') : esc_html__('standards', 'good-wine-shop');
		return $name;
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'good_wine_shop_get_post_format_icon' ) ) {
	function good_wine_shop_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return $icon;
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'good_wine_shop_get_list_fonts_styles' ) ) {
	function good_wine_shop_get_list_fonts_styles($prepend_inherit=false) {
		$list = array(
			'i' => esc_html__('I','good-wine-shop'),
			'u' => esc_html__('U', 'good-wine-shop')
		);
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'good_wine_shop_get_list_fonts' ) ) {
	function good_wine_shop_get_list_fonts($prepend_inherit=false) {
		if (($list = good_wine_shop_storage_get('list_fonts'))=='') {
			$list = array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Cardo' => array('family'=>'serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array(
                    'family'=>'cursive',
                    'link'=>'Crimson+Text:400,400i,600,600i,700,700i'
                ),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
                'Passion One' => array(
                    'family'=>'cursive',
                    'link'=>'Passion+One:400'
                ),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
			);
			$list = good_wine_shop_array_merge($list, good_wine_shop_get_list_font_faces());
			good_wine_shop_storage_get('list_fonts', $list);
		}
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'good_wine_shop_get_list_font_faces' ) ) {
    function good_wine_shop_get_list_font_faces($prepend_inherit=false) {
        static $list = false;
        if (is_array($list)) return $list;
        $fonts = good_wine_shop_storage_get('required_custom_fonts');
        $list = array();
        if (is_array($fonts)) {
            foreach ($fonts as $font) {
                if (($url = good_wine_shop_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
                    $list[sprintf(esc_html__('%s (uploaded font)', 'good-wine-shop'), $font)] = array('css' => $url);
                }
            }
        }
        return $list;
    }
}

// Return iconed classes list
if ( !function_exists( 'good_wine_shop_get_list_icons' ) ) {
	function good_wine_shop_get_list_icons($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = good_wine_shop_parse_icons_classes(good_wine_shop_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? good_wine_shop_array_merge(array('inherit' => esc_html__("Inherit", 'good-wine-shop')), $list) : $list;
	}
}
?>