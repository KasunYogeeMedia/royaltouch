<?php
/**
 * The Gallery template to display posts
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
$image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($columns).' post_format_'.esc_attr($post_format) ); ?>
	<?php echo (!good_wine_shop_is_off($animation) ? ' data-animation="'.esc_attr(good_wine_shop_get_animation_classes($animation)).'"' : ''); ?>
	data-size="<?php if (!empty($image[1]) && !empty($image[2])) echo intval($image[1]) .'x' . intval($image[2]); ?>"
	data-src="<?php if (!empty($image[0])) echo esc_url($image[0]); ?>"
	>

	<?php
	$image_hover = good_wine_shop_get_theme_option('image_hover');
	if (in_array($image_hover, array('icons', 'zoom'))) $image_hover = 'dots';
	// Featured image
	good_wine_shop_show_post_featured(array(
		'hover' => $image_hover,
		'thumb_size' => good_wine_shop_get_thumb_size( 'masonry-big' ),
		'show_no_image' => true,
		'class' => '',
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. good_wine_shop_show_post_meta(array(
									'categories' => true,
									'date' => true,
									'edit' => false,
									'seo' => false,
									'share' => true,
									'counters' => 'comments',
									'echo' => false
									))
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'good-wine-shop') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>