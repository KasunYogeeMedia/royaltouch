<?php
/**
 * The template for displaying all single pages
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

get_header();

while ( have_posts() ) { the_post();

	get_template_part( 'content', 'page' );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( !is_front_page() && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}

get_footer();
?>