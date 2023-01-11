<?php
/**
 * The template for displaying Page title and Breadcrumbs
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Page (category, tag, archive, author) title
if ( good_wine_shop_need_page_title() ) {
	set_query_var('good_wine_shop_title_showed', true);
	$top_icon = good_wine_shop_get_category_icon();
	?>
	<div class="top_panel_title_wrap">
		<div class="content_wrap">
			<div class="top_panel_title">
				<div class="page_title">
					<?php
					// Post meta on the single post
					if ( is_single() )  {
						good_wine_shop_show_post_meta(array(
							'seo' => true,
							'share' => false,
							'counters' => ''
							)
						);
					}
					
					// Blog/Post title
					$blog_title = good_wine_shop_get_blog_title();
					$blog_title_text = $blog_title_class = $blog_title_link = $blog_title_link_text = '';
					if (is_array($blog_title)) {
						$blog_title_text = $blog_title['text'];
						$blog_title_class = !empty($blog_title['class']) ? ' '.$blog_title['class'] : '';
						$blog_title_link = !empty($blog_title['link']) ? $blog_title['link'] : '';
						$blog_title_link_text = !empty($blog_title['link_text']) ? $blog_title['link_text'] : '';
					} else
						$blog_title_text = $blog_title;
					?>
					<h1 class="page_caption<?php echo esc_attr($blog_title_class); ?>"><?php
						if (!empty($top_icon)) {
							?><img src="<?php echo esc_url($top_icon); ?>" alt="<?php esc_attr__('Image', 'good-wine-shop')?>"><?php
						}
						echo wp_kses_post($blog_title_text);
					?></h1>
					<?php
					if (!empty($blog_title_link) && !empty($blog_title_link_text)) {
						?><a href="<?php echo esc_url($blog_title_link); ?>" class="theme_button theme_button_small page_title_link"><?php echo esc_html($blog_title_link_text); ?></a><?php
					}
					
					// Category/Tag description
					if ( is_category() || is_tag() || is_tax() ) 
						the_archive_description( '<div class="page_description">', '</div>' );
					?>
				</div>
				<?php
				// Breadcrumbs
				if (good_wine_shop_is_on(good_wine_shop_get_theme_option('show_breadcrumbs'))) {
					?><div class="breadcrumbs"><?php good_wine_shop_show_layout(good_wine_shop_get_breadcrumbs()); ?></div><?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
}
?>