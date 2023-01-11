<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Thumb image
$thumb_image = has_post_thumbnail() 
			? wp_get_attachment_image_src(get_post_thumbnail_id(), good_wine_shop_get_thumb_size('portrait')) 
			: (good_wine_shop_is_on(good_wine_shop_get_theme_option('related_post_placeholder')) ? good_wine_shop_get_file_url('images/no-image.jpg') : '');
if (is_array($thumb_image)) $thumb_image = $thumb_image[0];
$link = get_permalink();
?>
<div class="related_item related_item_style_1">
	<?php if (!empty($thumb_image)) { ?>
		<div class="post_featured" style="background-image: url(<?php echo esc_url($thumb_image); ?>)">
			<div class="post_header entry-header">
				<div class="post_categories"><?php the_category( '' ); ?></div>
				<h6 class="post_title entry-title"><a href="<?php echo esc_url($link); ?>"><?php echo the_title(); ?></a></h6>
				<?php
				if ( in_array(get_post_type(), array( 'post', 'attachment' ) ) ) {
					?><span class="post_date"><a href="<?php echo esc_url($link); ?>"><?php echo get_the_date(); ?></a></span><?php
				}
				?>
			</div>
		</div>
	<?php } ?>
</div>
