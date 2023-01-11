<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

good_wine_shop_storage_set('blog_archive', true);

// Load scripts for both 'Gallery' and 'Portfolio' layouts!
wp_enqueue_script( 'classie', good_wine_shop_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
wp_enqueue_script( 'imagesloaded', good_wine_shop_get_file_url('js/theme.gallery/imagesloaded.min.js'), array(), null, true );
wp_enqueue_script( 'masonry', good_wine_shop_get_file_url('js/theme.gallery/masonry.min.js'), array(), null, true );
wp_enqueue_script( 'good-wine-shop-gallery-script', good_wine_shop_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$sticky_out = is_array($stickies) && count($stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$show_filters = good_wine_shop_get_theme_option('show_filters');
	$tabs = array();
	if (!good_wine_shop_is_off($show_filters)) {
		$cat = good_wine_shop_get_theme_option('parent_cat');
		$args = array(
			'type'			=> 'post',
			'child_of'		=> $cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> 'category',
			'pad_counts'	=> false
		);
		$portfolio_list = get_categories($args);
		if (is_array($portfolio_list) && count($portfolio_list) > 0) {
			$tabs[$cat] = esc_html__('All', 'good-wine-shop');
			foreach ($portfolio_list as $term) {
				if (isset($term->term_id)) $tabs[$term->term_id] = $term->name;
			}
		}
	}
	if (count($tabs) > 0) {
		$portfolio_filters_ajax = true;
		$portfolio_filters_active = $cat;
		$portfolio_filters_id = 'portfolio_filters';
		if (!is_customize_preview())
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		?>
		<div class="portfolio_filters good_wine_shop_tabs good_wine_shop_tabs_ajax">
			<ul class="portfolio_titles good_wine_shop_tabs_titles">
				<?php
				foreach ($tabs as $id=>$title) {
					?><li><a href="<?php echo esc_url(good_wine_shop_get_hash_link('#'.trim($portfolio_filters_id).'_'.trim($id).'_content')); ?>" data-tab="<?php echo esc_attr($id); ?>"><?php echo esc_html($title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$ppp = good_wine_shop_get_theme_option('posts_per_page');
			if (good_wine_shop_is_inherit($ppp)) $ppp = '';
			foreach ($tabs as $id=>$title) {
				$portfolio_need_content = $id==$portfolio_filters_active || !$portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr($portfolio_filters_id.'_'.trim($id)); ?>_content"
					class="portfolio_content good_wine_shop_tabs_content"
					data-blog-template="<?php echo esc_attr(good_wine_shop_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(good_wine_shop_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($ppp); ?>"
					data-cat="<?php echo esc_attr($id); ?>"
					data-parent-cat="<?php echo esc_attr($cat); ?>"
					data-need-content="<?php echo (false===$portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($portfolio_need_content) 
						good_wine_shop_show_portfolio_posts(array(
							'cat' => $id,
							'parent_cat' => $cat,
							'page' => 1,
							'sticky' => $sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		good_wine_shop_show_portfolio_posts(array(
			'cat' => $id,
			'parent_cat' => $cat,
			'page' => 1,
			'sticky' => $sticky_out
			)
		);
	}

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>