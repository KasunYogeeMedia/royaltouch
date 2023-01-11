<?php
/**
 * The template for displaying Header widgets area
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Header sidebar
$header_name = good_wine_shop_get_theme_option('header_widgets');
$header_present = !good_wine_shop_is_off($header_name) && is_active_sidebar($header_name);
if ($header_present) { 
	good_wine_shop_storage_set('current_sidebar', 'header');
	$header_wide = good_wine_shop_get_theme_option('header_wide');
	ob_start();
	do_action( 'good_wine_shop_before_sidebar' );
    if ( is_active_sidebar( $header_name ) ) {
        dynamic_sidebar( $header_name );
    }
	do_action( 'good_wine_shop_after_sidebar' );
	$widgets_output = ob_get_contents();
	ob_end_clean();
	$widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $widgets_output);
	$need_columns = strpos($widgets_output, 'columns_wrap')===false;
	if ($need_columns) {
		$columns = max(0, (int) good_wine_shop_get_theme_option('header_columns'));
		if ($columns == 0) $columns = min(6, max(1, substr_count($widgets_output, '<aside ')));
		if ($columns > 1)
			$widgets_output = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($columns).' widget ', $widgets_output);
		else
			$need_columns = false;
	}
	?>
	<div class="header_widgets_wrap widget_area<?php echo !empty($header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
		<div class="header_widgets_wrap_inner widget_area_inner">
			<?php 
			if (!$header_wide) { 
				?><div class="content_wrap"><?php
			}
			if ($need_columns) {
				?><div class="columns_wrap"><?php
			}
            good_wine_shop_show_layout(chop($widgets_output));
			if ($need_columns) {
				?></div>	<!-- /.columns_wrap --><?php
			}
			if (!$header_wide) {
				?></div>	<!-- /.content_wrap --><?php
			}
			?>
		</div>	<!-- /.header_widgets_wrap_inner -->
	</div>	<!-- /.header_widgets_wrap -->
<?php
}
?>