<?php
/**
 * The Portfolio template for displaying content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$blog_style = explode('_', good_wine_shop_get_theme_option('blog_style'));
$columns = empty($blog_style[1]) ? 2 : max(2, $blog_style[1]);
$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$animation = good_wine_shop_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($columns).' post_format_'.esc_attr($post_format) ); ?>
	<?php echo (!good_wine_shop_is_off($animation) ? ' data-animation="'.esc_attr(good_wine_shop_get_animation_classes($animation)).'"' : ''); ?>
	>

	<?php
	$image_hover = good_wine_shop_get_theme_option('image_hover');
	// Featured image
	good_wine_shop_show_post_featured(array(
		'thumb_size' => good_wine_shop_get_thumb_size('masonry-big'),
		'show_no_image' => true,
		'class' => $image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>