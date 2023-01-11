<?php
/**
 * The style "classic" of the Blogger
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$args = get_query_var('trx_addons_args_sc_blogger');

if ($args['slider']) {
	?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
}

$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$post_link = get_permalink();
$post_title = get_the_title();
$image_hover = good_wine_shop_get_theme_option('image_hover');

?><div id="post-<?php the_ID(); ?>"	<?php post_class( 'sc_blogger_item post_format_'.esc_attr($post_format) ); ?>><?php

	// Featured image
	if ( has_post_thumbnail() ) {
		?><div class="sc_blogger_item_featured post_featured with_thumb hover_<?php echo esc_attr($image_hover); ?>">
			<?php the_post_thumbnail( trx_addons_get_thumb_size($args['columns'] > 2 ? 'medium' : 'big'), array( 'alt' => get_the_title() ) ); ?>
			<div class="mask"></div>
			<?php good_wine_shop_hovers_add_icons($image_hover); ?>
		</div><?php
	}
	
	// Post content
	?><div class="sc_blogger_item_content entry-content"><?php

		// Post title
		if ( !in_array($post_format, array('link', 'aside', 'status', 'quote')) ) {
			?><div class="sc_blogger_item_header entry-header"><?php 
				// Post title
				the_title( sprintf( '<h4 class="sc_blogger_item_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
			?></div><!-- .entry-header --><?php
		}		

		// Post content
			?><div class="sc_blogger_item_excerpt">
				<div class="sc_blogger_item_excerpt_text">
					<?php
					$show_more = !in_array($post_format, array('link', 'aside', 'status', 'quote'));
					if (has_excerpt()) {
						the_excerpt();
					} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
						the_content( esc_html__( 'More info', 'good-wine-shop' ) );
						$show_more = false;
					} else if (!$show_more) {
						the_content();
					} else {
						the_excerpt();
					}
					?>
				</div>
				<?php
				// Post meta
				if (in_array($post_format, array('link', 'aside', 'status', 'quote'))) {
					trx_addons_sc_show_post_meta(array(
						'date' => true
						)
					);
				}
				// More button
				if ( $show_more ) {
					?><div class="sc_item_button sc_button_wrap"><a class="more-link sc_button sc_button_default sc_button_size_small" href="<?php echo esc_url(get_permalink()); ?>"><span class="sc_button_text"><span class="sc_button_title"><?php esc_html_e('Details', 'good-wine-shop'); ?></span></span></a></div><?php
				}
			?></div><!-- .sc_blogger_item_excerpt --><?php
		
		
	?></div><!-- .entry-content --><?php
	
?></div><!-- .sc_blogger_item --><?php

if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
?>