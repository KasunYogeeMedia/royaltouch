<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WPBakery PageBuilder to make custom page layout:
 * just insert %%BLOG_ARCHIVE%% in the desired place of content
 */

// Get template page's content
$content = '';
$blog_archive_mask = '%%BLOG_ARCHIVE%%';
$blog_archive_subst = '<div class="blog_archive">' . trim($blog_archive_mask) . '</div>';
if ( have_posts() ) {
	the_post(); 
	if (($content = apply_filters('the_content', get_the_content())) != '') {
		if (($pos = strpos($content, $blog_archive_mask)) !== false) {
			$content = preg_replace('/(\<p\>\s*)?'.$blog_archive_mask.'(\s*\<\/p\>)/i', $blog_archive_subst, $content);
		} else
			$content .= $blog_archive_subst;
		$content = explode($blog_archive_mask, $content);
	}
}

// Make new query
$args = array(
	'post_type' => 'post',
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($page_number > 1) {
	$args['paged'] = $page_number;
	$args['ignore_sticky_posts'] = true;
}
$cat = good_wine_shop_get_theme_option('parent_cat');
if ((int) $cat > 0)
	$args['cat'] = (int) $cat;
$ppp = good_wine_shop_get_theme_option('posts_per_page');
if ((int) $ppp != 0)
	$args['posts_per_page'] = (int) $ppp;

query_posts( $args );

// Set query vars in the new query!
if (is_array($content) && count($content) == 2) {
	set_query_var('blog_archive_start', $content[0]);
	set_query_var('blog_archive_end', $content[1]);
}

get_template_part('index');
?>