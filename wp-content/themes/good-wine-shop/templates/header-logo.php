<?php
/**
 * The template for displaying Logo or Site name and slogan in the Header
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Site logo
$logo_image = '';
if (good_wine_shop_get_retina_multiplier(2) > 1)
	$logo_image = good_wine_shop_get_theme_option( 'logo_retina' );
if (empty($logo_image)) 
	$logo_image = good_wine_shop_get_theme_option( 'logo' );
$logo_text   = get_bloginfo( 'name' );
$logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($logo_image) || !empty($logo_text)) {
	?><div class="top_panel_logo"><a class="logo" href="<?php echo esc_url(home_url('/')); ?>"><?php
		if (!empty($logo_image)) {
			$attr = good_wine_shop_getimagesize($logo_image);
			echo '<img src="'.esc_url($logo_image).'" class="logo_main" alt="'.esc_attr__('Image', 'good-wine-shop').'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>' ;
		} else {
			echo !empty($logo_text)
				? '<span class="logo_text">' . trim($logo_text) . '</span>' 
				: '';
			echo !empty($logo_slogan)
				? '<span class="logo_slogan">' . trim($logo_slogan) . '</span>' 
				: '';
		}
	?></a></div><?php
}
?>