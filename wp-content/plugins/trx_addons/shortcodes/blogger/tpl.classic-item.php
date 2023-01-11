<?php
/**
 * The style "classic" of the Blogger
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
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

?><div id="post-<?php the_ID(); ?>"	<?php post_class( 'sc_blogger_item post_format_'.esc_attr($post_format) ); ?>><?php

	// Featured image
	if ( has_post_thumbnail() ) {
		$large_image_url = trx_addons_get_attachment_url( get_post_thumbnail_id(), trx_addons_get_thumb_size('big') );
		?><div class="sc_blogger_item_featured trx_addons_hover trx_addons_hover_style_zoomin">
			<?php the_post_thumbnail( trx_addons_get_thumb_size($args['columns'] > 2 ? 'medium' : 'big'), array( 'alt' => get_the_title() ) ); ?>
			<div class="trx_addons_hover_mask"></div>
			<div class="trx_addons_hover_content">
				<a href="<?php echo esc_url($post_link); ?>" class="trx_addons_hover_icon trx_addons_hover_icon_link"></a>
				<a href="<?php echo esc_url($large_image_url); ?>" class="trx_addons_hover_icon trx_addons_hover_icon_zoom"></a>
			</div>
		</div><?php
	}
	
	// Post content
	?><div class="sc_blogger_item_content entry-content"><?php

		// Post title
		if ( !in_array($post_format, array('link', 'aside', 'status', 'quote')) ) {
			?><div class="sc_blogger_item_header entry-header"><?php 
				// Post title
				the_title( sprintf( '<h4 class="sc_blogger_item_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				// Post meta
				trx_addons_sc_show_post_meta('sc_blogger', array(
					'date' => true
					)
				);
			?></div><!-- .entry-header --><?php
		}		

		// Post content
		if (!isset($args['hide_excerpt']) || (int)$args['hide_excerpt']==0) {
			?><div class="sc_blogger_item_excerpt">
				<div class="sc_blogger_item_excerpt_text">
					<?php
					global $post;
					$show_more = !in_array($post_format, array('link', 'aside', 'status', 'quote'));
					if (!empty($post->post_excerpt)) {
						the_excerpt();
					} else if (strpos($post->post_content, '<!--more')!==false) {
						the_content( esc_html__( 'More info', 'trx_addons' ) );
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
					?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Learn more', 'trx_addons'); ?></a></p><?php
				}
			?></div><!-- .sc_blogger_item_excerpt --><?php
		}
		
	?></div><!-- .entry-content --><?php
	
?></div><!-- .sc_blogger_item --><?php

if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
?>