<?php
/**
 * WordPress utilities
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


/* Page preloader
------------------------------------------------------------------------------------- */

// Add plugin specific classes to the body tag
if ( !function_exists('trx_addons_body_classes') ) {
	add_filter( 'body_class', 'trx_addons_body_classes' );
	function trx_addons_body_classes( $classes ) {
		if (!trx_addons_is_off(trx_addons_get_option('page_preloader')))
			$classes[] = 'preloader';
		return $classes;
	}
}

// Add page preloader into body
if (!function_exists('trx_addons_add_page_preloader')) {
	add_action('wp_footer', 'trx_addons_add_page_preloader', 1);
	function trx_addons_add_page_preloader() {
		if ( ($preloader=trx_addons_get_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=trx_addons_get_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}

// Add page preloader styles into head
if (!function_exists('trx_addons_add_page_preloader_styles')) {
	add_action('wp_head', 'trx_addons_add_page_preloader_styles');
	function trx_addons_add_page_preloader_styles() {
		if (($preloader=trx_addons_get_option('page_preloader'))!='none') {
			$image = trx_addons_get_option('page_preloader_image');
			$bg_color = trx_addons_get_option('page_preloader_bg_color');
			if (!empty($bg_color)) $bg_color = 'background-color:' . esc_attr($bg_color) . ';';
			?>
			<style type="text/css">
			<!--
				#page_preloader {
					<?php
					if (!empty($bg_color)) {
						?>background-color: <?php echo esc_attr($bg_color); ?>;<?php
					}
					if ($preloader=='custom' && $image) {
						?>background-image: url(<?php echo esc_url($image); ?>);<?php
					}
					?>
				}
			-->
			</style>
			<?php
		}
	}
}



/* Scroll to top button
------------------------------------------------------------------------------------- */

// Add button into body
if (!function_exists('trx_addons_add_scroll_to_top')) {
	add_action('wp_footer', 'trx_addons_add_scroll_to_top', 100);
	function trx_addons_add_scroll_to_top() {
		if (trx_addons_is_on(trx_addons_get_option('scroll_to_top'))) {
			?><a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up" title="<?php esc_attr_e('Scroll to top', 'trx_addons'); ?>"></a><?php
		}
	}
}



/* Post views and likes
-------------------------------------------------------------------------------- */

//Return Post Views number
if (!function_exists('trx_addons_get_post_views')) {
	function trx_addons_get_post_views($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Views number
if (!function_exists('trx_addons_set_post_views')) {
	function trx_addons_set_post_views($id=0, $counter=-1) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_post_meta($id, $count_key, $count);
		}
	}
}

// Increment Post Views number
if (!function_exists('trx_addons_inc_post_views')) {
	function trx_addons_inc_post_views($id=0, $inc=0) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, $count);
		} else {
			$count += $inc;
			update_post_meta($id, $count_key, $count);
		}
		return $count;
	}
}

//Return Post Likes number
if (!function_exists('trx_addons_get_post_likes')) {
	function trx_addons_get_post_likes($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Likes number
if (!function_exists('trx_addons_set_post_likes')) {
	function trx_addons_set_post_likes($id=0, $counter=-1) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_post_meta($id, $count_key, $count);
		}
	}
}

// Increment Post Likes number
if (!function_exists('trx_addons_inc_post_likes')) {
	function trx_addons_inc_post_likes($id=0, $inc=0) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'trx_addons_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, $count);
		} else {
			$count += $inc;
			update_post_meta($id, $count_key, $count);
		}
		return $count;
	}
}


// Set post likes/views counters when save/publish post
if ( !function_exists( 'trx_addons_init_post_counters' ) ) {
	add_action('save_post',	'trx_addons_init_post_counters');
	function trx_addons_init_post_counters($id) {
		global $post_type, $post;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $id;
		}
		// check permissions
		if (empty($post_type) || !current_user_can('edit_'.$post_type, $id)) {
			return $id;
		}
		if ( !empty($post->ID) && $id==$post->ID ) {
			trx_addons_get_post_views($id);
			trx_addons_get_post_likes($id);
		}
	}
}


// AJAX: Set post likes/views number
if ( !function_exists( 'trx_addons_callback_post_counter' ) ) {
	add_action('wp_ajax_post_counter', 			'trx_addons_callback_post_counter');
	add_action('wp_ajax_nopriv_post_counter',	'trx_addons_callback_post_counter');
	function trx_addons_callback_post_counter() {
		
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'', 'counter' => 0);
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$response['counter'] = trx_addons_inc_post_likes($id, (int) $_REQUEST['likes']);
		} else if (isset($_REQUEST['views'])) {
			$response['counter'] = trx_addons_inc_post_views($id, (int) $_REQUEST['views']);
		}
		echo json_encode($response);
		die();
	}
}


// Add post/page views counter feature
if (!function_exists('trx_addons_add_post_views_counter')) {
	add_action('wp_footer', 'trx_addons_add_post_views_counter');
	function trx_addons_add_post_views_counter() {
		if (!is_singular()) return;
		?>
		<!-- Post/page views count increment -->
		<script type="text/javascript">
			jQuery(document).ready(function() {
				setTimeout(function() {
					jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
						action: 'post_counter',
						nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
						post_id: <?php echo (int) get_the_ID(); ?>,
						views: 1
					}).done(function(response) {
						var rez = {};
						try {
							rez = JSON.parse(response);
						} catch (e) {
							rez = { error: TRX_ADDONS_STORAGE['ajax_error'] };
							console.log(response);
						}
						if (rez.error === '') {
							jQuery('.post_counters_single .post_counters_views .post_counters_number').html(rez.counter);
						}
					});
				}, 10);
			});
		</script>
		<?php
	}
}

// Return post likes/views counter layout
if ( !function_exists( 'trx_addons_get_post_counters' ) ) {
	function trx_addons_get_post_counters($counters='views', $show=false) {
		$post_id = get_the_ID();
		$output = '';
		if (strpos($counters, 'comments')!==false && (!is_singular() || have_comments() || comments_open())) {
			$post_comments = get_comments_number();
			$output .= ' <a href="'.esc_url(get_comments_link()).'" class="post_counters_item post_counters_comments trx_addons_icon-comment">'
							. '<span class="post_counters_number">'	. trim($post_comments) . '</span>'
							. '<span class="post_counters_label">' . _n( 'Comment', 'Comments', $post_comments, 'trx_addons' ) . '</span>'
						.'</a> ';
		}
		if (strpos($counters, 'views')!==false) {
			$post_views = trx_addons_get_post_views($post_id);
			$output .= ' <a href="' . esc_url(get_permalink()) . '" class="post_counters_item post_counters_views trx_addons_icon-eye">'
							. '<span class="post_counters_number">' . trim($post_views) . '</span>'
							. '<span class="post_counters_label">' . _n( 'View', 'Views', $post_views, 'trx_addons' ) . '</span>'
						. '</a> ';
		}
		if (strpos($counters, 'likes')!==false) {
			$post_likes = trx_addons_get_post_likes($post_id);
			$likes = isset($_COOKIE['trx_addons_likes']) ? $_COOKIE['trx_addons_likes'] : '';
			$allow = strpos($likes, ','.($post_id).',')===false;
			$output .= ' <a href="#" class="post_counters_item post_counters_likes trx_addons_icon-heart'.(!empty($allow) ? '-empty enabled' : ' disabled').'"
				title="'.(!empty($allow) ? esc_attr__('Like', 'trx_addons') : esc_attr__('Dislike', 'trx_addons')).'"
				data-postid="' . esc_attr($post_id) . '"
				data-likes="' . esc_attr($post_likes) . '"
				data-title-like="' . esc_attr__('Like', 'trx_addons') . '"
				data-title-dislike="' . esc_attr__('Dislike', 'trx_addons') . '">'
					. '<span class="post_counters_number">' . trim($post_likes) . '</span>'
					. '<span class="post_counters_label">' . _n( 'Like', 'Likes', $post_likes, 'trx_addons' ) . '</span>'
				. '</a> ';
		}
		if ($show) echo trim($output);
		return $output;
	}
}



/* Comment's likes
-------------------------------------------------------------------------------- */


//Return Comment's Likes number
if (!function_exists('trx_addons_get_comment_likes')) {
	function trx_addons_get_comment_likes($id=0){
		if (!$id) $id = get_comment_ID();
		$count_key = 'trx_addons_comment_likes_count';
		$count = get_comment_meta($id, $count_key, true);
		if ($count===''){
			delete_comment_meta($id, $count_key);
			add_comment_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Comment's Likes number
if (!function_exists('trx_addons_set_comment_likes')) {
	function trx_addons_set_comment_likes($id=0, $counter=-1) {
		if (!$id) $id = get_comment_ID();
		$count_key = 'trx_addons_comment_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_comment_meta($id, $count_key);
			add_comment_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_comment_meta($id, $count_key, $count);
		}
	}
}

// Increment Post Likes number
if (!function_exists('trx_addons_inc_comment_likes')) {
	function trx_addons_inc_comment_likes($id=0, $inc=0) {
		if (!$id) $id = get_comment_ID();
		$count_key = 'trx_addons_comment_likes_count';
		$count = get_comment_meta($id, $count_key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_comment_meta($id, $count_key);
			add_comment_meta($id, $count_key, $count);
		} else {
			$count += $inc;
			update_comment_meta($id, $count_key, $count);
		}
		return $count;
	}
}


// Set comment likes counter when save/publish post
if ( !function_exists( 'trx_addons_init_comment_counters' ) ) {
	add_action('comment_post',	'trx_addons_init_comment_counters', 10, 2);
	function trx_addons_init_comment_counters($id, $status='') {
		if ( !empty($id) ) {
			trx_addons_get_comment_likes($id);
		}
	}
}


// AJAX: Set comment likes number
if ( !function_exists( 'trx_addons_callback_comment_counter' ) ) {
	add_action('wp_ajax_comment_counter', 		'trx_addons_callback_comment_counter');
	add_action('wp_ajax_nopriv_comment_counter','trx_addons_callback_comment_counter');
	function trx_addons_callback_comment_counter() {
		
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'', 'counter' => 0);
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$response['counter'] = trx_addons_inc_comment_likes($id, (int) $_REQUEST['likes']);
		}
		echo json_encode($response);
		die();
	}
}


// Return post likes/views counter layout
if ( !function_exists( 'trx_addons_get_comment_counters' ) ) {
	function trx_addons_get_comment_counters($counters='likes', $show=false) {
		$comment_id = get_comment_ID();
		$output = '';
		if (strpos($counters, 'likes')!==false) {
			$comment_likes = trx_addons_get_comment_likes($comment_id);
			$likes = isset($_COOKIE['trx_addons_comment_likes']) ? $_COOKIE['trx_addons_comment_likes'] : '';
			$allow = strpos($likes, ','.($comment_id).',')===false;
			$output .= '<a href="#" class="comment_counters_item comment_counters_likes trx_addons_icon-heart'.(!empty($allow) ? '-empty enabled' : ' disabled').'"
				title="'.(!empty($allow) ? esc_attr__('Like', 'trx_addons') : esc_attr__('Dislike', 'trx_addons')).'"
				data-commentid="' . esc_attr($comment_id) . '"
				data-likes="' . esc_attr($comment_likes) . '"
				data-title-like="' . esc_attr__('Like', 'trx_addons') . '"
				data-title-dislike="' . esc_attr__('Dislike', 'trx_addons') . '">'
					. '<span class="comment_counters_number">' . trim($comment_likes) . '</span>'
					. '<span class="comment_counters_label">' . esc_html__('Likes', 'trx_addons') . '</span>'
				. '</a>';
		}
		if ($show) echo trim($output);
		return $output;
	}
}
		




/* Widgets utilities
------------------------------------------------------------------------------------- */

// Prepare widgets args - substitute id and class in parameter 'before_widget'
if (!function_exists('trx_addons_prepare_widgets_args')) {
	function trx_addons_prepare_widgets_args($args, $id, $class) {
		if (!empty($args['before_widget'])) $args['before_widget'] = str_replace(array('%1$s', '%2$s'), array($id, $class), $args['before_widget']);
		return $args;
	}
}




/* Blog utilities
------------------------------------------------------------------------------------- */

// Show pages links
if (!function_exists('trx_addons_pagination')) {
	function trx_addons_pagination($args=array()) {
		$args = array_merge(array(
			'class' => '',				// Additional 'class' attribute for the pagination section
			'button_class' => '',		// Additional 'class' attribute for the each page button
			'base_link' => '',			// Base link for each page. If specified - all pages use it and add '&page=XX' to the end of this link. Else - use get_pagenum_link()
			'total_posts' => 0,			// Total posts number
			'posts_per_page' => 0,		// Posts per page
			'total_pages' => 0,			// Total pages (instead total_posts, otherwise - calculate number of pages)
			'cur_page' => 0,			// Current page
			'near_pages' => 2,			// Number of pages to be displayed before and after the current page
			'group_pages' => 10,		// How many pages in group
			'pages_text' => '', 		//__('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'trx_addons'),
			'cur_text' => "%PAGE_NUMBER%",
			'page_text' => "%PAGE_NUMBER%",
			'first_text'=> __('&laquo; First', 'trx_addons'),
			'last_text' => __("Last &raquo;", 'trx_addons'),
			'prev_text' => __("&laquo; Prev", 'trx_addons'),
			'next_text' => __("Next &raquo;", 'trx_addons'),
			'dot_text' => "&hellip;",
			'before' => '',
			'after' => ''
			),  is_array($args) ? $args 
				: (is_int($args) ? array( 'cur_page' => $args ) 		// If send number parameter - use it as offset
					: array( 'class' => $args )));						// If send string parameter - use it as 'class' name
		if (empty($args['before']))	$args['before'] = '<div class="trx_addons_pagination'.(!empty($args['class']) ? ' '.$args['class'] : '').'">';
		if (empty($args['after'])) 	$args['after'] = '</div>';
		
		extract($args);
		
		global $wp_query;
	
		// Detect total pages
		if ($total_pages == 0) {
			if ($total_posts == 0) $total_posts = $wp_query->found_posts;
			if ($posts_per_page == 0) $posts_per_page = (int) get_query_var('posts_per_page');
			$total_pages = ceil($total_posts / $posts_per_page);
		}
		
		if ($total_pages < 2) return;
		
		// Detect current page
		if ($cur_page == 0) {
			$cur_page = (int) get_query_var('paged');
			if ($cur_page == 0) $cur_page = (int) get_query_var('page');
			if ($cur_page <= 0) $cur_page = 1;
		}
		// Near pages
		$show_pages_start = $cur_page - $near_pages;
		$show_pages_end = $cur_page + $near_pages;
		// Current group
		$cur_group = ceil($cur_page / $group_pages);
	
		$output = $before;
	
		// Page XX from XXX
		if ($pages_text) {
			$pages_text = str_replace(
				array("%CURRENT_PAGE%", "%TOTAL_PAGES%"),
				array(number_format_i18n($cur_page),number_format_i18n($total_pages)),
				$pages_text);
			$output .= '<span class="'.esc_attr($class).'_pages '.$button_class.'">' . $pages_text . '</span>';
		}
		if ($cur_page > 1) {
			// First page
			$first_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $first_text);
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page=1' : get_pagenum_link()).'" data-page="1" class="'.esc_attr($class).'_first '.$button_class.'">'.$first_text.'</a>';
			// Prev page
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($cur_page-1) : get_pagenum_link($cur_page-1)).'" data-page="'.esc_attr($cur_page-1).'" class="'.esc_attr($class).'_prev '.$button_class.'">'.$prev_text.'</a>';
		}
		// Page buttons
		$group = 1;
		$dot1 = $dot2 = false;
		for ($i = 1; $i <= $total_pages; $i++) {
			if ($i % $group_pages == 1) {
				$group = ceil($i / $group_pages);
				if ($group != $cur_group)
					$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.$i : get_pagenum_link($i)).'" data-page="'.esc_attr($i).'" class="'.esc_attr($class).'_group '.$button_class.'">'.$i.'-'.min($i+$group_pages-1, $total_pages).'</a>';
			}
			if ($group == $cur_group) {
				if ($i < $show_pages_start) {
					if (!$dot1) {
						$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($show_pages_start-1) : get_pagenum_link($show_pages_start-1)).'" data-page="'.esc_attr($show_pages_start-1).'" class="'.esc_attr($class).'_dot '.$button_class.'">'.$dot_text.'</a>';
						$dot1 = true;
					}
				} else if ($i > $show_pages_end) {
					if (!$dot2) {
						$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($show_pages_end+1) : get_pagenum_link($show_pages_end+1)).'" data-page="'.esc_attr($show_pages_end+1).'" class="'.esc_attr($class).'_dot '.$button_class.'">'.$dot_text.'</a>';
						$dot2 = true;
					}
				} else if ($i == $cur_page) {
					$cur_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $cur_text);
					$output .= '<span class="'.esc_attr($class).'_current active '.$button_class.'">'.$cur_text.'</span>';
				} else {
					$text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $page_text);
					$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.trim($i) : get_pagenum_link($i)).'" data-page="'.esc_attr($i).'" class="'.$button_class.'">'.$text.'</a>';
				}
			}
		}
		if ($cur_page < $total_pages) {
			// Next page
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($cur_page+1) : get_pagenum_link($cur_page+1)).'" data-page="'.esc_attr($cur_page+1).'" class="'.esc_attr($class).'_next '.$button_class.'">'.$next_text.'</a>';
			// Last page
			$last_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $last_text);
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.trim($total_pages) : get_pagenum_link($total_pages)).'" data-page="'.esc_attr($total_pages).'" class="'.esc_attr($class).'_last '.$button_class.'">'.$last_text.'</a>';
		}
		$output .= $after;
		echo trim($output);
	}
}


// Return current page number
if (!function_exists('trx_addons_get_current_page')) {
	function trx_addons_get_current_page() {
		if ( ($page = trx_addons_get_value_gp('page', -999)) == -999)
			if ( !($page = get_query_var('paged')) )
				if ( !($page = get_query_var('page')) )
					$page = 1;
		return $page;
	}
}





/* Query manipulations
------------------------------------------------------------------------------------- */

// Add sorting parameter in query arguments
if (!function_exists('trx_addons_query_add_sort_order')) {
	function trx_addons_query_add_sort_order($args, $orderby='date', $order='desc') {
		$q = array();
		$q['order'] = $order;
		if ($orderby == 'comments') {
			$q['orderby'] = 'comment_count';
		} else if ($orderby == 'title' || $orderby == 'alpha') {
			$q['orderby'] = 'title';
		} else if ($orderby == 'rand' || $orderby == 'random')  {
			$q['orderby'] = 'rand';
		} else {
			$q['orderby'] = 'post_date';
		}
		$q = apply_filters('trx_addons_filter_add_sort_order', $q, $orderby, $order);
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
if (!function_exists('trx_addons_query_add_posts_and_cats')) {
	function trx_addons_query_add_posts_and_cats($args, $ids='', $post_type='', $cat='', $taxonomy='') {
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
				if (empty($taxonomy))
					$taxonomy = 'category';
				if ($taxonomy == 'category') {				// Add standard categories
					if (is_array($cats)) {
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
if (!function_exists('trx_addons_query_add_filters')) {
	function trx_addons_query_add_filters($args, $filters=false) {
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
				} else
					$args = apply_filters('trx_addons_filter_query_add_filters', $args, $v);
			}
		}
		return $args;
	}
}

// Return string with categories links
if (!function_exists('trx_addons_get_post_categories')) {
	function trx_addons_get_post_categories($delimiter=', ', $id=false) {
		$output = '';
		$categories = get_the_category($id);
		if ( !empty( $categories ) ) {
			foreach( $categories as $category )
				$output .= ($output ? $delimiter : '') . '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( esc_attr__( 'View all posts in %s', 'trx_addons' ), $category->name ) . '">' . esc_html( $category->name ) . '</a>';
		}
		return $output;
	}
}

// Return string with terms links
if (!function_exists('trx_addons_get_post_terms')) {
	function trx_addons_get_post_terms($delimiter=', ', $id=false, $taxonomy='category') {
		$output = '';
		$terms = get_the_terms($id, $taxonomy);
		if ( !empty( $terms ) ) {
			foreach( $terms as $term )
				$output .= ($output ? $delimiter : '') . '<a href="' . esc_url( get_term_link( $term->term_id, $taxonomy ) ) . '" title="' . sprintf( esc_attr__( 'View all posts in %s', 'trx_addons' ), $term->name ) . '">' . esc_html( $term->name ) . '</a>';
		}
		return $output;
	}
}

// Return terms objects by taxonomy name (directly from db)
if (!function_exists('trx_addons_get_terms_by_taxonomy_from_db')) {
	function trx_addons_get_terms_by_taxonomy_from_db($tax_types = 'post_format') {
		global $wpdb;
		if (!is_array($tax_types)) $tax_types = array($tax_types);
		$terms = $wpdb->get_results( $wpdb->prepare(
												"SELECT DISTINCT terms.*, tax.taxonomy, tax.parent, tax.count"
														. " FROM {$wpdb->terms} AS terms"
														. " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id=terms.term_id"
														. " WHERE tax.taxonomy IN ('" . join(",", array_fill(0, count($tax_types), '%s')) . "')"
														. " ORDER BY terms.name",
												$tax_types
												),
									OBJECT
									);
		for ($i=0; $i<count($terms); $i++) {
			$terms[$i]->link = get_term_link($terms[$i]->slug, $terms[$i]->taxonomy);
		}
		return $terms;
	}
}




/* Lists
------------------------------------------------------------------------------------- */

// Return list of categories
if ( !function_exists( 'trx_addons_get_list_categories' ) ) {
	function trx_addons_get_list_categories($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
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
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'trx_addons_get_list_terms' ) ) {
	function trx_addons_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		static $list = array();
		if (empty($list[$taxonomy])) {
			$list[$taxonomy] = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
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
					'pad_counts'               => false
					)
				);
			} else {
				$terms = trx_addons_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $term) {
					$list[$taxonomy][$term->term_id] = $term->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list[$taxonomy]) : $list[$taxonomy];
	}
}

// Return list of post's types
if ( !function_exists( 'trx_addons_get_list_posts_types' ) ) {
	function trx_addons_get_list_posts_types($prepend_inherit=false) {
		static $list = false;
		if ($list === false) $list = get_post_types();
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'trx_addons_get_list_posts' ) ) {
	function trx_addons_get_list_posts($prepend_inherit=false, $opt=array()) {
		static $list = array();
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
		if (!isset($list[$hash])) {
			$list[$hash] = array();
			$list[$hash]['none'] = esc_html__("- Not selected -", 'trx_addons');
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
					$list[$hash][$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list[$hash]) : $list[$hash];
	}
}


// Return list pages
if ( !function_exists( 'trx_addons_get_list_pages' ) ) {
	function trx_addons_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'taxonomy'			=> '',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return trx_addons_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'trx_addons_get_list_users' ) ) {
	function trx_addons_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'trx_addons');
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
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'trx_addons_get_list_icons' ) ) {
	function trx_addons_get_list_icons($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = apply_filters('trx_addons_filter_get_list_icons', $list, $prepend_inherit);
			if ($list === false)
				$list = trx_addons_parse_icons_classes(trx_addons_get_file_dir("css/font-icons/css/trx_addons_icons-codes.css"));
		}
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return input hover effects
if ( !function_exists( 'trx_addons_get_list_input_hover' ) ) {
	function trx_addons_get_list_input_hover($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_input_hover', array(
			'default'	=> esc_html__('Default',	'themerex'),
			'accent'	=> esc_html__('Accented',	'themerex'),
			'path'		=> esc_html__('Path',		'themerex'),
			'jump'		=> esc_html__('Jump',		'themerex'),
			'underline'	=> esc_html__('Underline',	'themerex'),
			'iconed'	=> esc_html__('Iconed',		'themerex'),
		));
		return $prepend_inherit ? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}

// Return text for the Privacy Policy checkbox
if (!function_exists('trx_addons_get_privacy_text')) {
    function trx_addons_get_privacy_text() {
        $page = get_option('wp_page_for_privacy_policy');
        return apply_filters( 'trx_addons_filter_privacy_text', wp_kses_post(
                __( 'I agree that my submitted data is being collected and stored.', 'trx_addons' )
                . ( '' != $page
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf(__('For further details on handling user data, see our %s', 'trx_addons'),
                        '<a href="' . esc_url(get_permalink($page)) . '" target="_blank">'
                        . __('Privacy Policy', 'trx_addons')
                        . '</a>')
                    : ''
                )
            )
        );
    }
}


//------------------------------------------------------------
//-- Compatibility Gutenberg and other PageBuilders
//-------------------------------------------------------------

// Prevent simultaneous editing of posts for Gutenberg and other PageBuilders (VC, Elementor)
if ( ! function_exists( 'trx_addons_gutenberg_disable_cpt' ) ) {
    add_action( 'current_screen', 'trx_addons_gutenberg_disable_cpt' );
    function trx_addons_gutenberg_disable_cpt() {
        if ( trx_addons_get_setting( 'disable_gutenberg_on_other_pagebuilders' ) && trx_addons_exists_gutenberg() ) {
            $current_post_type = get_current_screen()->post_type;
            $disable = false;
            if ( !$disable && trx_addons_exists_elementor() ) {
                $post_types = get_post_types_by_support( 'elementor' );
                $disable = is_array($post_types) && in_array($current_post_type, $post_types);
            }
            if ( !$disable && trx_addons_exists_vc() ) {
                $post_types = function_exists('vc_editor_post_types') ? vc_editor_post_types() : array();
                $disable = is_array($post_types) && in_array($current_post_type, $post_types);
            }
            if ( $disable ) {
                remove_filter( 'replace_editor', 'gutenberg_init' );
                remove_action( 'load-post.php', 'gutenberg_intercept_edit_post' );
                remove_action( 'load-post-new.php', 'gutenberg_intercept_post_new' );
                remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
                remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' );
                remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
                remove_action( 'admin_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );
                remove_filter( 'screen_options_show_screen', '__return_false' );
            }
        }
    }
}

// Check if plugin 'WPBakery PageBuilder' (old name is 'Visual Composer') is installed and activated
if ( !function_exists( 'trx_addons_exists_vc' ) ) {
    function trx_addons_exists_vc() {
        return class_exists('Vc_Manager');
    }
}

// Check if plugin 'Elementor' is installed and activated
if ( !function_exists( 'trx_addons_exists_elementor' ) ) {
    function trx_addons_exists_elementor() {
        return class_exists('Elementor\Plugin');
    }
}

// Check if plugin 'Gutenberg' is installed and activated
if ( !function_exists( 'trx_addons_exists_gutenberg' ) ) {
    function trx_addons_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

?>