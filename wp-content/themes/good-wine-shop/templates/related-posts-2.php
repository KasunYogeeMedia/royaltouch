<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Thumb image
$thumb_image = has_post_thumbnail() 
			? wp_get_attachment_image_src(get_post_thumbnail_id(), good_wine_shop_get_thumb_size('medium')) 
			:
            (good_wine_shop_is_on(good_wine_shop_get_theme_option('related_post_placeholder')) ? good_wine_shop_get_file_url('images/no-image.jpg') : '');

if (is_array($thumb_image)) $thumb_image = $thumb_image[0];
$link = get_permalink();
$hover = good_wine_shop_get_theme_option('image_hover');
?>
<div class="related_item related_item_style_2">
	<?php if (!empty($thumb_image)) { ?>
		<div class="post_featured<?php if (has_post_thumbnail()) echo ' hover_'.esc_attr($hover); ?>" style="background-image: url(<?php echo esc_url($thumb_image); ?>)">
			<?php
			if (has_post_thumbnail()) {
				?><div class="mask"></div><?php
				good_wine_shop_hovers_add_icons($hover);
			}
			?>
		</div>
	<?php } ?>
    <div class="post_header entry-header">
        <h6 class="post_title entry-title"><a href="<?php echo esc_url($link); ?>"><?php echo the_title(); ?></a></h6>
    </div>
</div>
