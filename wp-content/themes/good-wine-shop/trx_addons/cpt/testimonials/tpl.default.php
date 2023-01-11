<?php
/**
 * The style "default" of the Testimonials
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_testimonials');

$query_args = array(
	'post_type' => TRX_ADDONS_CPT_TESTIMONIALS_PT,
	'post_status' => 'publish',
	'posts_per_page' => $args['count'],
	'ignore_sticky_posts' => true,
	'orderby' => 'date',
	'order' => 'desc'
);
$query_args = trx_addons_query_add_posts_and_cats($query_args, '', TRX_ADDONS_CPT_TESTIMONIALS_PT, $args['cat'], TRX_ADDONS_CPT_TESTIMONIALS_TAXONOMY);
$query = new WP_Query( $query_args );
if ($query->found_posts > 0) {
	if ($args['count'] > $query->found_posts) $args['count'] = $query->found_posts;
	$args['columns'] = $args['columns'] < 1 ? $args['count'] : min($args['columns'], $args['count']);
	$args['columns'] = max(1, min(12, (int) $args['columns']));
	$args['slider'] = $args['slider'] > 0 && $args['count'] > $args['columns'];
	$args['slides_space'] = max(0, (int) $args['slides_space']);
	?><div class="sc_testimonials sc_testimonials_default<?php if ($args['slider']) echo ' swiper-slider-container slider_swiper slider_nocontrols slider_pagination'; ?>"<?php
    good_wine_shop_show_layout(($args['columns'] > 1 ? ' data-slides-per-view="' . esc_attr($args['columns']) . '"' : '')
				. ($args['slides_space'] > 0 ? ' data-slides-space="' . esc_attr($args['slides_space']) . '"' : '')
				. ' data-slides-min-width="150"');
				?>
		>
		<?php
		trx_addons_sc_show_titles('sc_testimonials', $args);
		
		if ($args['slider']) {
			?><div class="sc_testimonials_slider sc_item_slider slides swiper-wrapper"><?php
		} else if ($args['columns'] > 1) {
			?><div class="sc_testimonials_columns sc_item_columns <?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?> columns_padding_bottom"><?php
		} else {
			?><div class="sc_testimonials_content sc_item_content"><?php
		}	
			
		while ( $query->have_posts() ) { $query->the_post();

			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			
			if ($args['slider']) {
				?><div class="swiper-slide"><?php
			} else if ($args['columns'] > 1) {
				?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
			}
			?>
			<div class="sc_testimonials_item">
				<div class="sc_testimonials_item_content"><?php the_content(); ?></div>
				<div class="sc_testimonials_item_author">
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="sc_testimonials_item_author_avatar"><?php the_post_thumbnail( apply_filters('trx_addons_filter_testimonials_thumb_size', trx_addons_get_thumb_size('avatar')), array('alt' => the_title_attribute( array( 'echo' => false ) )) ); ?></div>
					<?php } ?>
					<div class="sc_testimonials_item_author_data">
						<h4 class="sc_testimonials_item_author_title"><?php the_title(); ?></h4>
						<div class="sc_testimonials_item_author_subtitle"><?php good_wine_shop_show_layout($meta['subtitle']);?></div>
					</div>
				</div>
			</div>
			<?php
			if ($args['slider'] || $args['columns'] > 1) {
				?></div><?php
			}

		}

		wp_reset_postdata();
	
		?></div><?php

		trx_addons_sc_show_links('sc_testimonials', $args);

	?></div><!-- /.sc_testimonials --><?php
}
?>