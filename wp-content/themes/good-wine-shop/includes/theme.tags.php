<?php
/**
 * Theme tags
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */


//----------------------------------------------------------------------
//-- Common tags
//----------------------------------------------------------------------

// Return true if current page need title
if ( !function_exists('good_wine_shop_need_page_title') ) {
	function good_wine_shop_need_page_title() {
		return good_wine_shop_is_on(good_wine_shop_get_theme_option('show_page_title')) 
					&& !is_front_page() 
					&& apply_filters('good_wine_shop_filter_need_page_title', good_wine_shop_storage_isset('blog_archive') || is_page() || is_single() || is_category() || is_tag() || is_year() || is_month() || is_day() || is_author() || is_search());
	}
}


//----------------------------------------------------------------------
//-- Post parts
//----------------------------------------------------------------------

// Show post meta block: post date, author, categories, counters, etc.
if ( !function_exists('good_wine_shop_show_post_meta') ) {
	function good_wine_shop_show_post_meta($args=array()) {
		$args = array_merge(array(
			'categories' => true,
			'date' => true,
			'edit' => true,
			'seo' => false,
			'share' => false,
			'counters' => false,
			'echo' => true
			), $args);

		if (!$args['echo']) ob_start();

		?><div class="post_meta"><?php
			// Post categories
			if ( !empty($args['categories']) && !is_attachment() && !is_page() ) {
				$cats = get_post_type()=='post' ? get_the_category_list(', ') : apply_filters('good_wine_shop_filter_get_post_categories', '');
				if (!empty($cats)) {
					?>
					<span class="post_meta_item post_categories"><?php good_wine_shop_show_layout($cats); ?></span>
					<?php
				}
			}
			// Post date
			if ( !empty($args['date']) && !is_attachment() && !is_page() ) {
				$dt = get_post_type()=='post' ? get_the_date() : apply_filters('good_wine_shop_filter_get_post_date', '');
				if (!empty($dt)) {
					?>
					<span class="post_meta_item post_date<?php if (!empty($args['seo'])) echo ' date updated'; ?>"<?php if (!empty($args['seo'])) echo ' itemprop="datePublished"'; ?>><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($dt); ?></a></span>
					<?php
				}
			}
			// Post counters
			if ( !empty($args['counters']) ) {
                good_wine_shop_show_layout(good_wine_shop_get_post_counters($args['counters']));
			}
			// Socials share
			if ( !empty($args['share']) ) {
				$output = good_wine_shop_get_share_links(array(
						'type' => 'drop',
						'caption' => esc_html__('Share', 'good-wine-shop'),
						'echo' => false
					));
				if ($output) {
					?>
					<span class="post_meta_item post_share"><?php good_wine_shop_show_layout($output); ?></span>
					<?php
				}
			}
			// Edit page link
			if ( !empty($args['edit']) ) {
				edit_post_link( esc_html__( 'Edit', 'good-wine-shop' ), '<span class="post_meta_item post_edit icon-pencil">', '</span>' );
			}
		?></div><!-- .post_meta --><?php
		
		if (!$args['echo']) {
			$rez = ob_get_contents();
			ob_end_clean();
			return $rez;
		}
	}
}

// Show post featured block: image, video, audio, etc.
if ( !function_exists('good_wine_shop_show_post_featured') ) {
	function good_wine_shop_show_post_featured($args=array()) {
		$args = array_merge(array(
			'hover' => good_wine_shop_get_theme_option('image_hover'),	// Hover effect
			'class' => '',									// Additional Class for featured block
			'post_info' => '',								// Additional layout after hover
			'thumb_as_bg' => false,							// Put thumb image as block background
			'thumb_size' => '',								// Image size
			'show_no_image' => false						// Display 'no-image.jpg' if post haven't thumbnail
			), $args);

        $blog_style = explode('_', good_wine_shop_get_theme_option('blog_style'));

		if ( post_password_required() ) return;

		$thumb_size = !empty($args['thumb_size']) ? $args['thumb_size'] : good_wine_shop_get_thumb_size(is_attachment() ? 'full' : 'big');

		$css = '';
		if ($args['thumb_as_bg']) {
			if (has_post_thumbnail()) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $thumb_size );
				$image = $image[0];
			} else
				$image = good_wine_shop_get_file_url('images/no-image.jpg');
			$css = ' style="background-image: url('.esc_url($image).');"';
			$args['class'] .= ($args['class'] ? ' ' : '') . 'post_featured_bg';
		}

		if ( is_singular() ) {
			
			if ( is_attachment() ) {
				?>
				<div class="post_featured post_attachment<?php if ($args['class']) echo ' '.trim($args['class']); ?>"<?php if ($css) echo ' '.trim($css); ?>>

					<?php if (!$args['thumb_as_bg']) echo wp_get_attachment_image( get_the_ID(), $thumb_size ); ?>

					<nav id="image-navigation" class="navigation image-navigation">
						<div class="nav-previous"><?php previous_image_link( false, '' ); ?></div>
						<div class="nav-next"><?php next_image_link( false, '' ); ?></div>
					</nav><!-- .image-navigation -->
				
				</div><!-- .post_featured -->
				
				<?php
				if ( has_excerpt() ) {
					?><div class="entry-caption"><?php the_excerpt(); ?></div><!-- .entry-caption --><?php
				}
	
			} else if ( has_post_thumbnail() || !empty($args['show_no_image']) ) {

				?>
				<div class="post_featured<?php if ($args['class']) echo ' '.trim($args['class']); ?>"<?php if ($css) echo ' '.trim($css); ?>>
					<?php
					if (!$args['thumb_as_bg']) {
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( $thumb_size, array(
								'alt' => the_title_attribute( array( 'echo' => false ) ),
								'itemprop' => 'image'
								)
							);
						} else {
							?><img src="<?php echo esc_url(good_wine_shop_get_file_url('images/no-image.jpg')); ?>" alt="<?php the_title_attribute(); ?>"><?php
						}
					}
					?>
				</div><!-- .post_featured -->
				<?php

			}
	
		} else {
	
			$post_format = str_replace('post-format-', '', get_post_format());
			if (empty($post_format)) $post_format='standard';
			$has_thumb = has_post_thumbnail();
			$post_info = !empty($args['post_info']) ? $args['post_info'] : '';
			if ($has_thumb || in_array($post_format, array('gallery', 'image', 'audio', 'video')) || !empty($args['show_no_image'])) {
	
				?><div class="post_featured <?php echo (!empty($has_thumb) || $post_format == 'image' || !empty($args['show_no_image']) 
														? ('with_thumb' . (!in_array($post_format, array('audio', 'video', 'gallery')) || ($post_format=='gallery' && ($has_thumb || $args['thumb_as_bg']))
																				? ' hover_'.esc_attr($args['hover'])
																				: (in_array($post_format, array('video')) ? ' hover_play' : '')
																			)
															)
														: 'without_thumb')
													. (!empty($args['class']) ? ' '.esc_attr($args['class']) : '')
                                                    . ( (($post_format == 'audio') && ($blog_style[0] == 'portfolio')) ? ' hover_icons' : '' );
								?>"<?php if ($css) echo ' '.trim($css); ?>><?php

				// Put the thumb or gallery or image or video from the post
				if ( $args['thumb_as_bg'] ) {
					?><div class="mask"></div><?php
					if (!in_array($post_format, array('audio', 'video'))) {
						good_wine_shop_hovers_add_icons($args['hover']);
					}

				} else if ( $has_thumb ) {
					the_post_thumbnail( $thumb_size, array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) );
					?><div class="mask"></div><?php
					if (!in_array($post_format, array('audio', 'video'))) {
						good_wine_shop_hovers_add_icons($args['hover']);
					}
	
				} else if ($post_format == 'gallery') {
	
					if ( ($output = good_wine_shop_build_slider_layout(array('thumb_size' => $thumb_size, 'controls' => 'yes', 'pagination' => 'yes'))) != '' )
                        good_wine_shop_show_layout($output);
	
				} else if ($post_format == 'image') {
					$image = good_wine_shop_get_post_image();
					if (!empty($image)) {
						$image = good_wine_shop_clear_thumb_size($image);
						?>
						<img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>">
						<div class="mask"></div>
						<?php 
						good_wine_shop_hovers_add_icons($args['hover'], array('image' => $image));
					}
				} else if (!empty($args['show_no_image'])) {
					?>
					<img src="<?php echo esc_url(good_wine_shop_get_file_url('images/no-image.jpg')); ?>" alt="<?php the_title_attribute(); ?>">
					<div class="mask"></div>
					<?php 
					good_wine_shop_hovers_add_icons($args['hover']);
				}
				
				// Put video under the thumb
				if ($post_format == 'video') {
					$video = good_wine_shop_get_post_video('', false);
					if (empty($video))
						$video = good_wine_shop_get_post_iframe('', false);
					if ( empty( $video ) ) {
					    $post_content = good_wine_shop_get_post_content();
							// Only get video from the content if a playlist isn't present.
							$post_content_parsed = good_wine_shop_filter_post_content( $post_content );
							if ( false === strpos( $post_content_parsed, 'wp-playlist-script' ) ) {
								$videos = get_media_embedded_in_content( $post_content_parsed, array( 'video', 'object', 'embed', 'iframe' ) );
								if ( ! empty( $videos ) && is_array( $videos ) ) {
									$video = good_wine_shop_array_get_first( $videos, false );
								}
							}
						}
					if (!empty($video)) {
						if ( $has_thumb ) {
							$video = good_wine_shop_make_video_autoplay($video);
							?><div class="post_video_hover" data-video="<?php echo esc_attr($video); ?>"></div><?php 
						}
						?><div class="post_video video_frame"><?php 
							if ( !$has_thumb ) {
                                good_wine_shop_show_layout($video);
							}
						?></div><?php
					}
	
				}
				
				// Put audio over the thumb
                if (($post_format == 'audio') && (!in_array($blog_style[0], array('chess', 'portfolio')))) {
					$audio = good_wine_shop_get_post_audio('', false);
					if (empty($audio))
						$audio = good_wine_shop_get_post_iframe('', false);
					if (!empty($audio)) {
						//Show metadata (for the future version)
						if (false && function_exists('wp_read_audio_metadata')) {
							$src = good_wine_shop_get_post_audio($audio);
							$uploads = wp_upload_dir();
							if (strpos($src, $uploads['baseurl'])!==false) {
								$metadata = wp_read_audio_metadata( $src );
							}
						}
						?><div class="post_audio"><?php 
							$media_author = good_wine_shop_get_theme_option('media_author', '', false, get_the_ID());
							$media_title = good_wine_shop_get_theme_option('media_title', '', false, get_the_ID());
							if ( !good_wine_shop_is_inherit($media_author) ) {
								?><div class="post_audio_author"><?php good_wine_shop_show_layout($media_author); ?></div><?php
							}
							if ( !good_wine_shop_is_inherit($media_title) ) {
								?><div class="post_audio_title"><?php good_wine_shop_show_layout($media_title); ?></div><?php
							}
                            good_wine_shop_show_layout($audio);
						?></div><?php
					}
				}
				
				// Put optional info block over the thumb
                good_wine_shop_show_layout($post_info);
				?></div><?php
			}
		}
	}
}

// Return full content of the post/page
if ( ! function_exists( 'good_wine_shop_get_post_content' ) ) {
	function good_wine_shop_get_post_content( $apply_filters=false ) {
		global $post;
		return $apply_filters ? apply_filters( 'the_content', $post->post_content ) : $post->post_content;
	}
}

// to avoid conflicts with Gutenberg
if ( ! function_exists( 'good_wine_shop_filter_post_content' ) ) {
	function good_wine_shop_filter_post_content( $content ) {
		$content = apply_filters( 'good_wine_shop_filter_post_content', $content );
		global $wp_embed;
		if ( is_object( $wp_embed ) ) {
			$content = $wp_embed->autoembed( $content );
		}
		return do_shortcode( $content );
	}
}

// Return first key (by default) or value from associative array
if ( ! function_exists( 'good_wine_shop_array_get_first' ) ) {
	function good_wine_shop_array_get_first( &$arr, $key = true ) {
		foreach ( $arr as $k => $v ) {
			break;
		}
		return $key ? $k : $v;
	}
}

// Add featured image as background image to post navigation elements.
if ( !function_exists('good_wine_shop_add_bg_in_post_nav') ) {
	function good_wine_shop_add_bg_in_post_nav() {
		if ( ! is_single() ) return;
	
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		$css      = '';
	
		if ( is_attachment() && $previous->post_type == 'attachment' ) return;
	
		if ( $previous && has_post_thumbnail( $previous->ID ) ) {
			$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), good_wine_shop_get_thumb_size('med') );
			$css .= '.post-navigation .nav-previous a .nav-arrow { background-image: url(' . esc_url( $prevthumb[0] ) . '); }';
		} else
			$css .= '.post-navigation .nav-previous a .nav-arrow { background-color: rgba(128,128,128,0.05); border-color:rgba(128,128,128,0.1); }';
	
		if ( $next && has_post_thumbnail( $next->ID ) ) {
			$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), good_wine_shop_get_thumb_size('med') );
			$css .= '.post-navigation .nav-next a .nav-arrow { background-image: url(' . esc_url( $nextthumb[0] ) . '); }';
		} else
			$css .= '.post-navigation .nav-next a .nav-arrow { background-color: rgba(128,128,128,0.05); border-color:rgba(128,128,128,0.1); }';
	
		wp_add_inline_style( 'good-wine-shop-main', $css );
	}
}

// Show related posts
if ( !function_exists('good_wine_shop_show_related_posts') ) {
	function good_wine_shop_show_related_posts($args=array(), $style=1, $title='') {
		$args = array_merge(array(
			'numberposts' => 2,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => 'post',
			'post_status' => 'publish',
			'post__not_in' => array(),
			'category__in' => array()
			), $args);
		
		$args['post__not_in'][] = get_the_ID();
		
		if (empty($args['category__in']) || is_array($args['category__in']) && count($args['category__in']) == 0) {
			$post_categories_ids = array();
			$post_cats = get_the_category(get_the_ID());
			if (is_array($post_cats) && !empty($post_cats)) {
				foreach ($post_cats as $cat) {
					$post_categories_ids[] = $cat->cat_ID;
				}
			}
			$args['category__in'] = $post_categories_ids;
		}
		
		$recent_posts = wp_get_recent_posts( $args, OBJECT );

		if (is_array($recent_posts) && count($recent_posts) > 0) {
			?>
			<section class="related_wrap">
				<div class="columns_wrap posts_container">
					<?php
					global $post;
					foreach( $recent_posts as $post ) {
						setup_postdata($post);
						?><div class="column-1_<?php echo intval(max(2, $args['numberposts'])); ?>"><?php
							 get_template_part('templates/related-posts', $style);
						?></div><?php
					}
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php
		}
	}
}


// Show portfolio posts
if ( !function_exists('good_wine_shop_show_portfolio_posts') ) {
	function good_wine_shop_show_portfolio_posts($args=array()) {
		$args = array_merge(array(
			'cat' => 0,
			'parent_cat' => 0,
			'page' => 1,
			'sticky' => false,
			'blog_style' => '',
			'echo' => true
			), $args);

		$blog_style = explode('_', empty($args['blog_style']) ? good_wine_shop_get_theme_option('blog_style') : $args['blog_style']);
		$style = $blog_style[0];
		$columns = empty($blog_style[1]) ? 2 : max(2, $blog_style[1]);

		if ( !$args['echo'] ) {
			ob_start();

			$q_args = array(
				'post_type' => 'post',
				'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
			);
			if ($args['page'] > 1) {
				$q_args['paged'] = $args['page'];
				$q_args['ignore_sticky_posts'] = true;
			}
			if ((int) $args['cat'] > 0)
				$q_args['cat'] = (int) $args['cat'];
			$ppp = good_wine_shop_get_theme_option('posts_per_page');
			if ((int) $ppp != 0)
				$q_args['posts_per_page'] = (int) $ppp;
			
			query_posts( $q_args );
		}

		// Show posts
		$class = 'portfolio_wrap posts_container portfolio_'.trim($columns). ($style!='portfolio' ? ' '.trim($style).'_wrap '.trim($style).'_'.trim($columns) : '');
		if ($args['sticky']) {
			?><div class="columns_wrap sticky_wrap"><?php	
		} else {
			?><div class="<?php echo esc_attr($class); ?>"><?php	
		}
	
		while ( have_posts() ) { the_post(); 
			if ($args['sticky'] && !is_sticky()) {
				$args['sticky'] = false;
				?></div><div class="<?php echo esc_attr($class); ?>"><?php
			}
			get_template_part( 'content', $args['sticky'] && is_sticky() ? 'sticky' : ($style == 'gallery' ? 'portfolio-gallery' : $style) );
		}
		
		?></div><?php
	
		good_wine_shop_show_pagination();
		
		if (!$args['echo']) {
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
}

// AJAX handler for the good_wine_shop_ajax_get_posts action
if ( !function_exists( 'good_wine_shop_ajax_get_posts_callback' ) ) {
	add_action('wp_ajax_good_wine_shop_ajax_get_posts',			'good_wine_shop_ajax_get_posts_callback');
	function good_wine_shop_ajax_get_posts_callback() {
		if ( !wp_verify_nonce( good_wine_shop_get_value_gp('nonce'), admin_url('admin-ajax.php') ) || ! current_user_can( 'manage_options' ) )
			die();
	
		$id = !empty($_REQUEST['blog_template']) ? good_wine_shop_get_value_gpc('blog_template') : 0;
		if ((int)$id > 0) {
			good_wine_shop_storage_set('blog_archive', true);
			good_wine_shop_storage_set('blog_mode', 'blog');
			good_wine_shop_storage_set('options_meta', get_post_meta($id, 'good_wine_shop_options', true));
		}

		$response = array(
			'error'=>'', 
			'data' => good_wine_shop_show_portfolio_posts(array(
							'cat' => (int) good_wine_shop_get_value_gpc('cat'),
							'parent_cat' => (int) good_wine_shop_get_value_gpc('parent_cat'),
							'page' => (int) good_wine_shop_get_value_gpc('page'),
							'blog_style' => trim(good_wine_shop_get_value_gpc('blog_style')),
							'echo' => false
							)
						)
		);

		if (empty($response['data'])) {
			$response['error'] = esc_html__('Sorry, but nothing matched your search criteria.', 'good-wine-shop');
		}
		
		echo json_encode($response);
		wp_die();
	}
}


// Show pagination
if ( !function_exists('good_wine_shop_show_pagination') ) {
	function good_wine_shop_show_pagination() {
		global $wp_query;
		// Pagination
		$pagination = good_wine_shop_get_theme_option('blog_pagination');
		if ($pagination == 'pages') {
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( '<', 'good-wine-shop' ),
				'next_text' => esc_html__( '>', 'good-wine-shop' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'good-wine-shop' ) . ' </span>',
			) );
		} else if ($pagination == 'more' || $pagination == 'infinite') {
			$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
			if ($page_number < $wp_query->max_num_pages) {
				?>
				<div class="nav-links-more<?php if ($pagination == 'infinite') echo ' nav-links-infinite'; ?>">
					<a class="nav-load-more" href="#" 
						data-page="<?php echo esc_attr($page_number); ?>" 
						data-max-page="<?php echo esc_attr($wp_query->max_num_pages); ?>"
						><span><?php esc_html_e('Load more posts', 'good-wine-shop'); ?></span></a>
				</div>
				<?php
			}
		} else if ($pagination == 'links') {
			?>
			<div class="nav-links-old">
				<span class="nav-prev"><?php previous_posts_link( esc_html__('Newest posts', 'good-wine-shop') ); ?></span>
				<span class="nav-next"><?php next_posts_link( esc_html__('Older posts', 'good-wine-shop'), $wp_query->max_num_pages ); ?></span>
			</div>
			<?php
		}
	}
}
?>