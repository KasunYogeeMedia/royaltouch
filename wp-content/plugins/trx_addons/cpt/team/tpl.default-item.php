<?php
/**
 * The style "default" of the Team
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_team');

$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
$link = get_permalink();

if ($args['slider']) {
	?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
}
?>
<div class="sc_team_item">
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="sc_team_item_thumb trx_addons_hover trx_addons_hover_style_zoomin">
			<?php
			the_post_thumbnail( apply_filters('trx_addons_filter_team_thumb_size', trx_addons_get_thumb_size('avatar')), array('alt' => get_the_title()) );
			$title = get_the_title();
			$large_image_url = trx_addons_get_attachment_url( get_post_thumbnail_id(), 'full' );
			?>
			<div class="trx_addons_hover_mask"></div>
			<div class="trx_addons_hover_content">
				<h5 class="trx_addons_hover_title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h5>
				<a href="<?php echo esc_url($link); ?>" class="trx_addons_hover_icon trx_addons_hover_icon_link"></a>
				<a href="<?php echo esc_url($large_image_url); ?>" class="trx_addons_hover_icon trx_addons_hover_icon_zoom" title="<?php echo esc_attr($title); ?>"></a>
			</div>
		</div>
	<?php } ?>
	<div class="sc_team_item_info">
		<div class="sc_team_item_header">
			<h4 class="sc_team_item_title"><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></h4>
			<div class="sc_team_item_subtitle"><?php echo trim($meta['subtitle']);?></div>
		</div>
		<div class="sc_team_item_content"><?php the_excerpt(); ?></div>
		<?php
		if (!empty($meta['socials'])) {
			?><div class="sc_team_item_socials"><?php echo trim(trx_addons_get_socials_links_custom($meta['socials'])); ?></div><?php
		}
		?>
		<div class="sc_team_item_button"><a href="<?php echo esc_url($link); ?>" class="sc_button sc_button_simple"><?php esc_html_e('Learn more', 'trx_addons'); ?></a></div>
	</div>
</div>
<?php
if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
?>