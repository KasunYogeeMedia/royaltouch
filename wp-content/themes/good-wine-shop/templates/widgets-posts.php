<?php
/**
 * The template for displaying posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$post_id    = get_the_ID();
$post_date  = sprintf( esc_html__('%s ago', 'good-wine-shop'), human_time_diff(get_the_time('U')) );
$post_title = get_the_title();
$post_link  = get_permalink();
$post_author_id   = get_the_author_meta('ID');
$post_author_name = get_the_author_meta('display_name');
$post_author_url  = get_author_posts_url($post_author_id, '');

$args = get_query_var('good_wine_shop_args_widgets_posts');
$show_date = isset($args['show_date']) ? (int) $args['show_date'] : 1;
$show_image = isset($args['show_image']) ? (int) $args['show_image'] : 1;
$show_author = isset($args['show_author']) ? (int) $args['show_author'] : 1;
$show_counters = isset($args['show_counters']) ? (int) $args['show_counters'] : 1;
$show_categories = isset($args['show_categories']) ? (int) $args['show_categories'] : 1;

$allow = false;

$output = good_wine_shop_storage_get('output');

$post_counters_output = '';
if ( $show_counters ) {
	$post_counters_output = '<span class="post_info_item post_info_counters">'
								. good_wine_shop_get_post_counters('comments')
							. '</span>';
}


$output .= '<article class="post_item with_thumb">';

if ($show_image) {
	$post_thumb = get_the_post_thumbnail($post_id, good_wine_shop_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($post_thumb) $output .= '<div class="post_thumb">' . ($post_link ? '<a href="' . esc_url($post_link) . '">' : '') . ($post_thumb) . ($post_link ? '</a>' : '') . '</div>';
}

$output .= '<div class="post_content">'
			. ($show_categories ? '<div class="post_categories">'.good_wine_shop_get_post_categories().trim($post_counters_output).'</div>' : '')
			. '<h6 class="post_title">' . ($post_link ? '<a href="' . esc_url($post_link) . '">' : '') . ($post_title) . ($post_link ? '</a>' : '') . '</h6>'
			. '<div class="post_info">'
				. ($show_date 
					? '<span class="post_info_item post_info_posted">'
						. ($post_link ? '<a href="' . esc_url($post_link) . '" class="post_info_date">' : '') 
						. ($post_date) 
						. ($post_link ? '</a>' : '')
						. '</span>'
					: '')
				. ($show_author 
					? '<span class="post_info_item post_info_posted_by">' 
						. esc_html__('by', 'good-wine-shop') . ' ' 
						. ($post_link ? '<a href="' . esc_url($post_author_url) . '" class="post_info_author">' : '') 
						. ($post_author_name) 
						. ($post_link ? '</a>' : '') 
						. '</span>'
					: '')
				. (!$show_categories && $post_counters_output
					? $post_counters_output
					: '')
			. '</div>'
		. '</div>'
	. '</article>';
good_wine_shop_storage_set('output', $output);
?>