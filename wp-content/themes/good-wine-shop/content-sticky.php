<?php
/**
 * The Sticky template for displaying sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$animation = good_wine_shop_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($post_format) ); ?>
	<?php echo (!good_wine_shop_is_off($animation) ? ' data-animation="'.esc_attr(good_wine_shop_get_animation_classes($animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	good_wine_shop_show_post_featured(array(
		'thumb_size' => good_wine_shop_get_thumb_size($columns==1 ? 'big' : ($columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			// Post meta
			good_wine_shop_show_post_meta();
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>