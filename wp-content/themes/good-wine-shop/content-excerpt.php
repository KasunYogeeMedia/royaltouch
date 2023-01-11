<?php
/**
 * The default template for displaying content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$full_content = good_wine_shop_get_theme_option('blog_content') != 'excerpt' || in_array($post_format, array('link', 'aside', 'status', 'quote'));
$animation = good_wine_shop_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($post_format) ); ?>
	<?php echo (!good_wine_shop_is_off($animation) ? ' data-animation="'.esc_attr(good_wine_shop_get_animation_classes($animation)).'"' : ''); ?>
	><?php

	// Featured image
	good_wine_shop_show_post_featured(array( 'thumb_size' => good_wine_shop_get_thumb_size( strpos(good_wine_shop_get_theme_option('body_style'), 'wide')!==false ? 'full' : 'big' ) ));

	// Title and post meta
	?>
	<div class="post_header entry-header">
		<?php
		// Post title
		the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		// Post meta
		good_wine_shop_show_post_meta();
		?>
	</div><!-- .post_header --><?php
	
	// Post content
	?><div class="post_content entry-content"><?php
		if ($full_content) {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'good-wine-shop' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'good-wine-shop' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$show_learn_more = !in_array($post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($post_format, array('link', 'aside', 'status', 'quote'))) {
					the_content();
				} else {
					the_excerpt();
				}
			?></div><?php
			// More button
			if ( $show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'good-wine-shop'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</article>