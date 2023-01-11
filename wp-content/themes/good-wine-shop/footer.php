<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

						// Widgets area inside page content
						good_wine_shop_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					good_wine_shop_create_widgets_area('widgets_below_page');

					$body_style = good_wine_shop_get_theme_option('body_style');
					if ($body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			$footer_scheme =  good_wine_shop_is_inherit(good_wine_shop_get_theme_option('footer_scheme')) ? good_wine_shop_get_theme_option('color_scheme') : good_wine_shop_get_theme_option('footer_scheme');
			?>
			
			<footer class="site_footer_wrap scheme_<?php echo esc_attr($footer_scheme); ?>">
				<?php
				// Footer sidebar
				$footer_name = good_wine_shop_get_theme_option('footer_widgets');
				$footer_present = !good_wine_shop_is_off($footer_name) && is_active_sidebar($footer_name);
				if ($footer_present) { 
					good_wine_shop_storage_set('current_sidebar', 'footer');
					$footer_wide = good_wine_shop_get_theme_option('footer_wide');
					ob_start();
					do_action( 'good_wine_shop_before_sidebar' );
                    if ( is_active_sidebar( $footer_name ) ) {
                        dynamic_sidebar( $footer_name );
                    }
					do_action( 'good_wine_shop_after_sidebar' );
					$out = ob_get_contents();
					ob_end_clean();
					$out = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out);
					$need_columns = true;
					if ($need_columns) {
						$columns = max(0, (int) good_wine_shop_get_theme_option('footer_columns'));
						if ($columns == 0) $columns = min(6, max(1, substr_count($out, '<aside ')));
						if ($columns > 1)
							$out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($columns).' widget ', $out);
						else
							$need_columns = false;
					}
					?>
					<div class="footer_wrap widget_area<?php echo !empty($footer_wide) ? ' footer_fullwidth' : ''; ?> scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('footer_scheme')) ? good_wine_shop_get_theme_option('color_scheme') : good_wine_shop_get_theme_option('footer_scheme')); ?>">
						<div class="footer_wrap_inner widget_area_inner">
							<?php 
							if (!$footer_wide) { 
								?><div class="content_wrap"><?php
							}
							if ($need_columns) {
								?><div class="columns_wrap"><?php
							}
							good_wine_shop_show_layout(chop($out));
							if ($need_columns) {
								?></div><!-- /.columns_wrap --><?php
							}
							if (!$footer_wide) {
								?></div><!-- /.content_wrap --><?php
							}
							?>
						</div><!-- /.footer_wrap_inner -->
					</div><!-- /.footer_wrap -->
				<?php
				}
	
				// Logo
				if (good_wine_shop_is_on(good_wine_shop_get_theme_option('logo_in_footer'))) {
					$logo_image = '';
					if (good_wine_shop_get_retina_multiplier(2) > 1)
						$logo_image = good_wine_shop_get_theme_option( 'logo_footer_retina' );
					if (empty($logo_image)) 
						$logo_image = good_wine_shop_get_theme_option( 'logo_footer' );
					$logo_text   = get_bloginfo( 'name' );
					if (!empty($logo_image) || !empty($logo_text)) {
						?>
						<div class="logo_footer_wrap scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('footer_scheme')) ? good_wine_shop_get_theme_option('color_scheme') : good_wine_shop_get_theme_option('footer_scheme')); ?>">
							<div class="logo_footer_wrap_inner">
								<?php
								if (!empty($logo_image)) {
									$attr = good_wine_shop_getimagesize($logo_image);
									echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($logo_image).'" class="logo_footer_image" alt="'.esc_attr__('Image', 'good-wine-shop').'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'></a>' ;
								} else if (!empty($logo_text)) {
									echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . trim($logo_text) . '</a></h1>';
								}
								?>
							</div>
						</div>
						<?php
					}
				}

				// Socials
				if ( good_wine_shop_is_on(good_wine_shop_get_theme_option('socials_in_footer')) && ($output = good_wine_shop_get_socials_links()) != '') {
					?>
					<div class="socials_footer_wrap socials_wrap scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('footer_scheme')) ? good_wine_shop_get_theme_option('color_scheme') : good_wine_shop_get_theme_option('footer_scheme')); ?>">
						<div class="socials_footer_wrap_inner">
							<?php good_wine_shop_show_layout($output); ?>
						</div>
					</div>
					<?php
				}
				
				// Footer menu
				$menu_footer = good_wine_shop_get_nav_menu('menu_footer');
				if (!empty($menu_footer)) {
					?>
					<div class="menu_footer_wrap scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('footer_scheme')) ? good_wine_shop_get_theme_option('color_scheme') : good_wine_shop_get_theme_option('footer_scheme')); ?>">
						<div class="menu_footer_wrap_inner">
							<nav class="menu_footer_nav_area"><?php good_wine_shop_show_layout($menu_footer); ?></nav>
						</div>
					</div>
					<?php
				}
				
				// Copyright area
				?> 
				<div class="copyright_wrap scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('footer_scheme')) ? good_wine_shop_get_theme_option('color_scheme') : good_wine_shop_get_theme_option('footer_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<div class="copyright_text"><?php
								$copyright = good_wine_shop_get_theme_option('copyright');
								if (!empty($copyright)) {
									if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $copyright, $matches)) {
										$copyright = str_replace($matches[1], date(str_replace(array('{', '}'), '', $matches[1])), $copyright);
									}
                                    good_wine_shop_show_layout($copyright);
								}
							?></div>
						</div>
					</div>
				</div>

			</footer><!-- /.site_footer_wrap -->
			
		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('images/makeup.jpg'))) { ?>
		<img src="<?php echo esc_url(good_wine_shop_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>