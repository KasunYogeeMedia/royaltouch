<?php
/**
 * The template to display image and page description
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$image = good_wine_shop_get_theme_option('header_title_image');
$text = good_wine_shop_get_theme_option('header_title_text');
if (!empty($image) || !empty($text)) {
	?>
	<div class="top_panel_title_2_wrap">
		<div class="content_wrap">
			<div class="top_panel_title_2">
				<?php
				if (!empty($image)) {
					$attr = good_wine_shop_getimagesize($image);
					echo '<div class="top_panel_title_2_image"><img src="'.esc_url($image).'" alt="'.esc_attr__('Image', 'good-wine-shop').'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'></div>';
				}
				if (!empty($text)) {
					echo '<div class="top_panel_title_2_text">'.trim($text).'</div>';
				}
				?>
			</div>
		</div>
	</div>
	<?php
}
?>