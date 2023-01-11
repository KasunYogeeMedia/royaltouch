<?php
/**
 * The template for homepage posts with "Excerpt" style
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

good_wine_shop_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	?><div class="posts_container"><?php
	
	$stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$sticky_out = is_array($stickies) && count($stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	while ( have_posts() ) { the_post(); 
		if ($sticky_out && !is_sticky()) {
			$sticky_out = false;
			?></div><?php
		}
		get_template_part( 'content', $sticky_out && is_sticky() ? 'sticky' : 'excerpt' );
	}
	if ($sticky_out) {
		$sticky_out = false;
		?></div><?php
	}
	
	?></div><?php

	good_wine_shop_show_pagination();

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>