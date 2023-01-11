<?php
/**
 * The template for homepage posts with "Chess" style
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

good_wine_shop_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$sticky_out = is_array($stickies) && count($stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$sticky_out) {
		?><div class="chess_wrap posts_container"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($sticky_out && !is_sticky()) {
			$sticky_out = false;
			?></div><div class="chess_wrap posts_container"><?php
		}
		get_template_part( 'content', $sticky_out && is_sticky() ? 'sticky' :'chess' );
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