<?php
/**
 * The template for displaying Featured image in the single post
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

if ( get_query_var('good_wine_shop_header_image')=='' && is_singular() && has_post_thumbnail() && in_array(get_post_type(), array('post', 'page')) )  {
	set_query_var('good_wine_shop_featured_showed', true);
	$src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
	if (!empty($src[0])) {
		?><div class="post_featured post_featured_fullwide" style="background-image:url(<?php echo esc_url($src[0]); ?>);"></div><?php
	}
}
?>