<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$sidebar_position = good_wine_shop_get_theme_option('sidebar_position');
if (good_wine_shop_sidebar_present()) {
	$sidebar_name = good_wine_shop_get_theme_option('sidebar_widgets');
	good_wine_shop_storage_set('current_sidebar', 'sidebar');
    if (have_posts()) {
	?>
	<div class="sidebar <?php echo esc_attr($sidebar_position); ?> widget_area<?php if (!good_wine_shop_is_inherit(good_wine_shop_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(good_wine_shop_get_theme_option('color_scheme')); ?>" role="complementary">
		<div class="sidebar_inner">
			<?php
			ob_start();
			do_action( 'good_wine_shop_before_sidebar' );
            if ( is_active_sidebar( $sidebar_name ) ) {
                dynamic_sidebar( $sidebar_name );
            }
			do_action( 'good_wine_shop_after_sidebar' );
			$out = ob_get_contents();
			ob_end_clean();
            good_wine_shop_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
			?>
		</div><!-- /.sidebar_inner -->
	</div><!-- /.sidebar -->
	<?php
    }
}
?>