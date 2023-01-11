<?php
$good_wine_shop_search_style = good_wine_shop_get_theme_option('search_style');
$good_wine_shop_search_in_header = get_query_var('good_wine_shop_search_in_header');
set_query_var('good_wine_shop_search_in_header', false);
?>
<div class="search_wrap<?php
	if ($good_wine_shop_search_in_header) {
		echo ' search_style_'.esc_attr($good_wine_shop_search_style);
		if ($good_wine_shop_search_style != 'fullscreen') echo ' search_ajax';
	}
	?>">
	<div class="search_form_wrap">
		<form role="search" method="get" class="search_form" action="<?php echo esc_url(home_url('/')); ?>">
			<input type="text" class="search_field" placeholder="<?php esc_attr_e('Search', 'good-wine-shop'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
			<button type="submit" class="search_submit icon-search"></button>
			<?php if ($good_wine_shop_search_in_header && $good_wine_shop_search_style == 'fullscreen') { ?>
				<a class="search_close icon-cancel"></a>
			<?php } ?>
		</form>
	</div>
	<div class="search_results widget_area"><a href="#" class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
</div>
