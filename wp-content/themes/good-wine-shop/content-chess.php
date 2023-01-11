<?php
/**
 * The Classic template for displaying content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$blog_style = explode('_', good_wine_shop_get_theme_option('blog_style'));
$columns = empty($blog_style[1]) ? 1 : max(1, $blog_style[1]);
$expanded = !good_wine_shop_sidebar_present() && good_wine_shop_is_on(good_wine_shop_get_theme_option('expand_content'));
$post_format = get_post_format();
$post_format = empty($post_format) ? 'standard' : str_replace('post-format-', '', $post_format);
$animation = good_wine_shop_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($columns).' post_format_'.esc_attr($post_format) ); ?>
	<?php echo (!good_wine_shop_is_off($animation) ? ' data-animation="'.esc_attr(good_wine_shop_get_animation_classes($animation)).'"' : ''); ?>
	>

	<?php
	// Add anchor
	if ($columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'"]');
	}

	// Featured image
	good_wine_shop_show_post_featured( array(
											'class' => $columns == 1 ? 'trx-stretch-height' : '',
											'show_no_image' => true,
											'thumb_as_bg' => true,
											'thumb_size' => good_wine_shop_get_thumb_size(
																	strpos(good_wine_shop_get_theme_option('body_style'), 'full')!==false
																		? ( $columns > 1 ? 'big' : 'original' )
																		: (	$columns > 1 ? 'med' : 'big')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		if ( !in_array($post_format, array('link', 'aside', 'status', 'quote')) ) {
			?><div class="post_header entry-header"><?php 
				// Post title
				the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );

				// Post meta
				good_wine_shop_show_post_meta(array(
					'share' => false,
					'counters' => false
					)
				);
			?></div><!-- .entry-header --><?php
		}		
		?>
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$show_learn_more = !in_array($post_format, array('link', 'aside', 'status', 'quote'));
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($post_format, array('link', 'aside', 'status', 'quote'))) {
					the_content();
				} else {
					the_excerpt();
				}
				?>
			</div>
			<?php
			// Post meta
			if (in_array($post_format, array('link', 'aside', 'status', 'quote'))) {
				good_wine_shop_show_post_meta(array(
					'share' => false,
					'counters' => $columns < 3 ? 'comments' : ''
					)
				);
			}
			// More button
			if ( $show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'good-wine-shop'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>