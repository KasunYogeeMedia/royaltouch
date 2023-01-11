<?php
/**
 * Theme functions: init, enqueue scripts and styles, include required files and widgets
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

/**
 * Fire the wp_body_open action.
 *
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action('wp_body_open');
    }
}

// Theme storage
$GOOD_WINE_SHOP_STORAGE = array(
	'required_plugins' => array(					// Theme required plugin's slugs
		'trx_addons',
		'js_composer',
		'woocommerce',
        'essgrids',
        'wp-gdpr-compliance',
        'contact-form-7',
        'trx_updater',
        'elegro-payment'
	),


    // Theme-specific URLs (will be escaped in place of the output)
    'theme_pro_key' => 'env-axiom',
    'theme_demo_url' => 'http://goodwine.axiomthemes.com',
    'theme_doc_url'  => 'http://goodwine.axiomthemes.com/doc',

    'theme_video_url' => 'https://www.youtube.com/channel/UCBjqhuwKj3MfE3B6Hg2oA8Q',

    'theme_support_url'  => 'https://axiom.ticksy.com/',
    'theme_download_url'=> 'https://1.envato.market/c/1262870/275988/4415?subId1=axiom&u=themeforest.net/item/good-wine-wine-house-winery-wine-shop/19399667',
);

// Framework directory path from theme root
if ( ! defined( 'GOOD_WINE_SHOP_THEME_PATH' ) )	define( 'GOOD_WINE_SHOP_THEME_PATH',	trailingslashit( get_template_directory() ) );

//-------------------------------------------------------
//-- Theme init
//-------------------------------------------------------

if ( !function_exists('good_wine_shop_theme_setup1') ) {
	
	add_action( 'after_setup_theme', 'good_wine_shop_theme_setup1', 1 );

	function good_wine_shop_theme_setup1() {
        // Make theme available for translation
        // Translations can be filed in the /languages directory
        // Attention! Translations must be loaded before first call any translation functions!
        load_theme_textdomain( 'good-wine-shop', get_template_directory() . '/languages' );

		// Set theme content width
		$GLOBALS['content_width'] = apply_filters( 'good_wine_shop_filter_content_width', 1170 );
	}
}

if ( !function_exists('good_wine_shop_theme_setup') ) {
	
	add_action( 'after_setup_theme', 'good_wine_shop_theme_setup' );

	function good_wine_shop_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Add thumb sizes
		// ATTENTION! If you change list below - check filter's names in the 'trx_addons_filter_get_thumb_size' hook
		$thumb_sizes = apply_filters('good_wine_shop_filter_add_thumb_sizes', array(
			'good_wine_shop-thumb-huge'	=> array(1170, 731, true),
			'good_wine_shop-thumb-big' 	=> array( 770, 481, true),
			'good_wine_shop-thumb-med' 	=> array( 370, 231, true),
			'good_wine_shop-thumb-small'	=> array( 270, 169, true),
			'good_wine_shop-thumb-tiny' 	=> array(  75,  75, true),
			'good_wine_shop-thumb-portrait'=> array( 370, 493, true),
			'good_wine_shop-thumb-avatar'	=> array( 370, 370, true),
			'good_wine_shop-thumb-masonry'	=> array( 370,   0, false),		// Only downscale, not crop
			'good_wine_shop-thumb-masonry-big' => array( 770,   0, false)	// Only downscale, not crop
			)
		);
		$mult = good_wine_shop_get_theme_option('retina_ready', 1);
		if ($mult > 1) $GLOBALS['content_width'] = apply_filters( 'good_wine_shop_filter_content_width', 1170*$mult);
		foreach ($thumb_sizes as $k=>$v) {
			// Add Original dimensions
			add_image_size( $k, $v[0], $v[1], $v[2]);
			// Add Retina dimensions
			if ($mult > 1) add_image_size( $k.'-@retina', $v[0]*$mult, $v[1]*$mult, $v[2]);
		}
		
		// Custom header setup
		add_theme_support( 'custom-header', array(
			'header-text'=>false
			)
		);

		// Custom backgrounds setup
		add_theme_support( 'custom-background', array()	);
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add theme menus
		add_theme_support('nav-menus');
		
		// Switch default markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style( array_merge(
			array(
				'css/editor-style.css',
				good_wine_shop_get_file_url('css/fontello/css/fontello-embedded.css')
			),
			good_wine_shop_theme_fonts_for_editor()
			)
		);
	
		// Register navigation menu
		register_nav_menus(array(
			'menu_main' => esc_html__('Main Menu', 'good-wine-shop'),
			)
		);

		// Excerpt filters
		add_filter( 'excerpt_length',						'good_wine_shop_excerpt_length' );
		add_filter( 'excerpt_more',							'good_wine_shop_excerpt_more' );
		
		// Add required meta tags in the head
		add_action('wp_head',		 						'good_wine_shop_wp_head', 1);

		// Enqueue scripts and styles for frontend
		add_action('wp_enqueue_scripts', 					'good_wine_shop_wp_scripts', 1000);			//priority 1000 - load styles before plugin custom styles with priority 1100
		add_action('wp_enqueue_scripts', 					'good_wine_shop_wp_scripts_responsive', 2000);	//priority 2000 - load responsive after all other styles
		
		// Add body classes
		add_filter( 'body_class',							'good_wine_shop_add_body_classes' );

		// Enqueue scripts and styles for admin
		add_action("admin_enqueue_scripts",					'good_wine_shop_admin_scripts');

		// Register sidebars
		add_action('widgets_init', 							'good_wine_shop_widgets_init');

		// TGM Activation plugin
		add_action('tgmpa_register',						'good_wine_shop_register_plugins');

		// Set options for importer (before other plugins)
		add_filter( 'good_wine_shop_filter_importer_options',		'good_wine_shop_importer_set_options', 9 );
	}

}


//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------
if ( !function_exists('good_wine_shop_image_sizes') ) {
	add_filter( 'image_size_names_choose', 'good_wine_shop_image_sizes' );
	function good_wine_shop_image_sizes( $sizes ) {
		$thumb_sizes = apply_filters('good_wine_shop_filter_add_thumb_sizes', array(
			'good_wine_shop-thumb-full'	=> esc_html__( 'Fullsize image', 'good-wine-shop' ),
			'good_wine_shop-thumb-big'		=> esc_html__( 'Large image', 'good-wine-shop' ),
			'good_wine_shop-thumb-med'		=> esc_html__( 'Medium image', 'good-wine-shop' ),
			'good_wine_shop-thumb-small'	=> esc_html__( 'Small image', 'good-wine-shop' ),
			'good_wine_shop-thumb-avatar'	=> esc_html__( 'Big square avatar', 'good-wine-shop' ),
			'good_wine_shop-thumb-tiny'	=> esc_html__( 'Small square avatar', 'good-wine-shop' ),
			'good_wine_shop-thumb-masonry'	=> esc_html__( 'Masonry (scaled)', 'good-wine-shop' ),
			'good_wine_shop-thumb-masonry-big'	=> esc_html__( 'Masonry Large (scaled)', 'good-wine-shop' )
			)
		);
		$mult = good_wine_shop_get_theme_option('retina_ready', 1);
		foreach($thumb_sizes as $k=>$v) {
			$sizes[$k] = $v;
			if ($mult > 1) $sizes[$k.'-@retina'] = $v.' '.esc_html__('@2x', 'good-wine-shop' );
		}
		return $sizes;
	}
}


//-------------------------------------------------------
//-- Theme scripts and styles
//-------------------------------------------------------

// Load frontend scripts
if ( !function_exists( 'good_wine_shop_wp_scripts' ) ) {
	
	function good_wine_shop_wp_scripts() {
		
		// Enqueue styles
		//------------------------
		
		// Links to selected fonts
		$links = good_wine_shop_theme_fonts_links();
		if (count($links) > 0) {
			foreach ($links as $slug => $link) {
				wp_enqueue_style( 'good-wine-shop-font-'.trim($slug), $link );
			}
		}
		
		// Fontello styles must be loaded before main stylesheet
		// This style NEED theme prefix, because style 'fontello' some plugin contain different set of characters
		// and can't be used instead this style!
		wp_enqueue_style( 'fontello-icons',  good_wine_shop_get_file_url('css/fontello/css/fontello-embedded.css') );

		// Main stylesheet
		wp_enqueue_style( 'good-wine-shop-main', get_stylesheet_uri(), array(), null );
		
		// Animations
		if ( (good_wine_shop_get_theme_option('blog_animation')!='none' || good_wine_shop_get_theme_option('menu_animation_in')!='none' || good_wine_shop_get_theme_option('menu_animation_out')!='none') && (good_wine_shop_get_theme_option('animation_on_mobile')=='yes' || !wp_is_mobile()) && !good_wine_shop_vc_is_frontend())
			wp_enqueue_style( 'good-wine-shop-animation',	good_wine_shop_get_file_url('css/animation.css') );

		// Custom colors
		if ( !is_customize_preview() && !isset($_GET['color_scheme']) && good_wine_shop_is_off(good_wine_shop_get_theme_option('debug_mode')) )
			wp_enqueue_style( 'good-wine-shop-colors', good_wine_shop_get_file_url('css/__colors.css') );
		else
			wp_add_inline_style( 'good-wine-shop-main', good_wine_shop_customizer_get_css() );

		// Merged styles
		if ( good_wine_shop_is_off(good_wine_shop_get_theme_option('debug_mode')) )
			wp_enqueue_style( 'good-wine-shop-styles', good_wine_shop_get_file_url('css/__styles.css') );

		// Add post nav background
		good_wine_shop_add_bg_in_post_nav();


		// Enqueue scripts	
		//------------------------
		
		// Modernizr will load in head before other scripts and styles
		if ( substr(good_wine_shop_get_theme_option('blog_style'), 0, 7) == 'gallery' || substr(good_wine_shop_get_theme_option('blog_style'), 0, 9) == 'portfolio' )
			wp_enqueue_script( 'modernizr', good_wine_shop_get_file_url('js/theme.gallery/modernizr.min.js'), array(), null, false );
		
		// Merged scripts
		if ( good_wine_shop_is_off(good_wine_shop_get_theme_option('debug_mode')) )
			wp_enqueue_script( 'good-wine-shop-init', good_wine_shop_get_file_url('js/__scripts.js'), array('jquery') );
		else {
			// Skip link focus
			wp_enqueue_script( 'skip-link-focus-fix', good_wine_shop_get_file_url('js/skip-link-focus-fix.js') );
			// Superfish Menu
			wp_enqueue_script( 'superfish', good_wine_shop_get_file_url('js/superfish.js'), array('jquery') );
			// Background video
			$header_video = good_wine_shop_get_theme_option('header_video');
			if (!empty($header_video) && !good_wine_shop_is_inherit($header_video))
				wp_enqueue_script( 'bideo', good_wine_shop_get_file_url('js/bideo.js'), array() );
			// Theme scripts
			wp_enqueue_script( 'good-wine-shop-utils', good_wine_shop_get_file_url('js/_utils.js'), array('jquery') );
			wp_enqueue_script( 'good-wine-shop-init', good_wine_shop_get_file_url('js/_init.js'), array('jquery') );
		}
		
		// Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Media elements library	
		if (good_wine_shop_get_theme_setting('use_mediaelements')) {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		wp_localize_script( 'good-wine-shop-init', 'GOOD_WINE_SHOP_STORAGE', apply_filters( 'good_wine_shop_filter_localize_script', array(
			// AJAX parameters
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))),
			
			// Site base url
			'site_url' => get_site_url(),
			
			// User logged in
			'user_logged_in' => is_user_logged_in() ? true : false,
			
			// Menu width for mobile mode
			'mobile_layout_width' => max(480, good_wine_shop_get_theme_option('mobile_layout_width')),

			// Use menu cache
			'menu_cache' => good_wine_shop_is_on(good_wine_shop_get_theme_option('menu_cache')),

			// Menu animation
			'menu_animation_in' => good_wine_shop_get_theme_option('menu_animation_in'),
            'menu_animation_out' => good_wine_shop_get_theme_option('menu_animation_out'),

			// Video background
			'background_video' => good_wine_shop_get_theme_option('header_video'),

			// Video and Audio tag wrapper
			'use_mediaelements' => good_wine_shop_get_theme_setting('use_mediaelements') ? true : false,

			// Messages max length
			'message_maxlength'	=> intval(good_wine_shop_get_theme_setting('message_maxlength')),
						
			// Site color scheme
			'site_scheme' => 'scheme_' . trim(good_wine_shop_get_theme_option('color_scheme')),

			
			// Internal vars - do not change it!
			
			// Flag for review mechanism
			'admin_mode' => false,

			// E-mail mask
			'email_mask' => '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$',
			
			// Strings for translation
			'strings' => array(
					'ajax_error'		=> esc_html__('Invalid server answer!', 'good-wine-shop'),
					'error_global'		=> esc_html__('Error data validation!', 'good-wine-shop'),
					'name_empty' 		=> esc_html__("The name can't be empty", 'good-wine-shop'),
					'name_long'			=> esc_html__('Too long name', 'good-wine-shop'),
					'email_empty'		=> esc_html__('Too short (or empty) email address', 'good-wine-shop'),
					'email_long'		=> esc_html__('Too long email address', 'good-wine-shop'),
					'email_not_valid'	=> esc_html__('Invalid email address', 'good-wine-shop'),
					'text_empty'		=> esc_html__("The message text can't be empty", 'good-wine-shop'),
					'text_long'			=> esc_html__('Too long message text', 'good-wine-shop'),
					'search_error'		=> esc_html__('Search error! Try again later.', 'good-wine-shop'),
					'send_complete'		=> esc_html__("Send message complete!", 'good-wine-shop'),
					'send_error'		=> esc_html__('Transmit failed!', 'good-wine-shop')
					)
			))
		);
	}
}

// Load responsive styles (priority 2000 - load it after main styles and plugins custom styles)
if ( !function_exists( 'good_wine_shop_wp_scripts_responsive' ) ) {
	
	function good_wine_shop_wp_scripts_responsive() {
		wp_enqueue_style( 'good-wine-shop-responsive', good_wine_shop_get_file_url('css/responsive.css') );
	}
}

//  Add meta tags and inline scripts in the header for frontend
if (!function_exists('good_wine_shop_wp_head')) {
	
	function good_wine_shop_wp_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="//gmpg.org/xfn/11">
		<link rel="pingback" href="<?php esc_url(bloginfo( 'pingback_url' )); ?>">
		<?php
	}
}

// Add theme specified classes into the body
if ( !function_exists('good_wine_shop_add_body_classes') ) {
	
	function good_wine_shop_add_body_classes( $classes ) {
		$classes[] = 'body_tag';	// Need for the .scheme_self
		$classes[] = 'body_style_' . trim(good_wine_shop_get_theme_option('body_style'));
		$classes[] = 'scheme_' . trim(good_wine_shop_get_theme_option('color_scheme'));
		$blog_mode = good_wine_shop_storage_get('blog_mode');
		$classes[] = 'blog_mode_' . trim($blog_mode);
		if (in_array($blog_mode, array('post', 'page'))) {
			$classes[] = 'is_single';
		} else {
			$classes[] = ' is_stream';
			$classes[] = 'blog_style_'.trim(good_wine_shop_get_theme_option('blog_style'));
		}
		if (good_wine_shop_sidebar_present()) {
            if (is_archive() && !have_posts()) {
                $classes[] = 'sidebar_hide expand_content';
            } else {
			$classes[] = 'sidebar_show sidebar_' . trim(good_wine_shop_get_theme_option('sidebar_position')) ;
            }

		} else {
			$classes[] = 'sidebar_hide';
			if (good_wine_shop_is_on(good_wine_shop_get_theme_option('expand_content')))
				 $classes[] = 'expand_content';
		}
		if (good_wine_shop_is_on(good_wine_shop_get_theme_option('remove_margins')))
			 $classes[] = 'remove_margins';
		$classes[] = 'header_style_' . trim(good_wine_shop_get_theme_option("header_style"));
		$classes[] = 'header_position_' . trim(good_wine_shop_get_theme_option("header_position"));
		$classes[] = 'header_title_' . (good_wine_shop_need_page_title() ? 'on' : 'off');
		$classes[] = 'menu_style_' . trim(good_wine_shop_get_theme_option("menu_style"));
		$classes[] = 'no_layout';
		return $classes;
	}
}
	
// Load required styles and scripts for admin mode
if ( !function_exists( 'good_wine_shop_admin_scripts' ) ) {
	
	function good_wine_shop_admin_scripts() {

		// Add theme styles
		wp_enqueue_style(  'good-wine-shop-admin',  good_wine_shop_get_file_url('css/admin.css') );

		// Links to selected fonts
		$screen = get_current_screen();
		if (good_wine_shop_allow_override($screen->id) && good_wine_shop_allow_override($screen->post_type)) {
			// Load fontello icons
			// This style NEED theme prefix, because style 'fontello' some plugin contain different set of characters
			// and can't be used instead this style!
			wp_enqueue_style(  'fontello-icons', good_wine_shop_get_file_url('css/fontello/css/fontello-embedded.css') );
			// Load theme fonts
			$links = good_wine_shop_theme_fonts_links(false);
			if (count($links) > 0) {
				foreach ($links as $slug => $link) {
					wp_enqueue_style( 'good-wine-shop-font-'.trim($slug), $link );
				}
			}
		}

		// Add theme scripts
		wp_enqueue_script( 'good-wine-shop-utils', good_wine_shop_get_file_url('js/_utils.js'), array('jquery') );
		wp_enqueue_script( 'good-wine-shop-admin', good_wine_shop_get_file_url('js/_admin.js'), array('jquery') );

		wp_localize_script( 'good-wine-shop-admin', 'GOOD_WINE_SHOP_STORAGE', apply_filters( 'good_wine_shop_filter_localize_script_admin', array(
			'admin_mode' => true,
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))),
			'importer_error_msg' => esc_html__('Errors that occurred during the import process:', 'good-wine-shop'),
			'user_logged_in' => true
			))
		);
	}
}


//-------------------------------------------------------
//-- Sidebars and widgets
//-------------------------------------------------------

// Register widgetized areas
if ( !function_exists('good_wine_shop_widgets_init') ) {
	
	function good_wine_shop_widgets_init() {
		$sidebars = good_wine_shop_get_list_sidebars();
		if (is_array($sidebars) && count($sidebars) > 0) {
			$args = good_wine_shop_get_widgets_args();
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array_merge( array(
													'name'          => $name,
													'id'            => $id
												),
												$args
											)
				);
			}
		}
	}
}


//-------------------------------------------------------
//-- Theme fonts
//-------------------------------------------------------

// Return links for all theme fonts
if ( !function_exists('good_wine_shop_theme_fonts_links') ) {
	function good_wine_shop_theme_fonts_links($all_tags=true) {
		$links = array();
		
		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$google_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Google fonts: on or off', 'good-wine-shop' ) );
		$custom_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Custom fonts (included in the theme): on or off', 'good-wine-shop' ) );
		
		if ( ($google_fonts_enabled || $custom_fonts_enabled) && !good_wine_shop_storage_empty('theme_fonts') ) {
			$theme_fonts = (array)good_wine_shop_storage_get('theme_fonts');
			if (count($theme_fonts) > 0) {
				$google_fonts = '';
				foreach ($theme_fonts as $tag => $font) {
					if (!$all_tags && in_array($tag, array('logo', 'info', 'menu', 'submenu', 'decor'))) continue;	// Skip non content tags (for admin mode)
					if (empty($font['family']) || good_wine_shop_is_inherit($font['family']) || (empty($font['link']) && empty($font['css']))) continue;
					$font_name = str_replace('"', '', ($pos=strpos($font['family'], ','))!==false ? substr($font['family'], 0, $pos) : $font['family']);
					if (!empty($font['css'])) {
						if ($custom_fonts_enabled) {
							$links[str_replace(' ', '-', $font_name)] = $font['css'];
						}
					} else {
						if ($google_fonts_enabled) {
							$google_fonts .= ($google_fonts ? '|' : '') 
											. (!empty($font['link']) ? $font['link'] : str_replace(' ', '+', $font_name).':400,400italic,700,700italic');
						}
					}
				}
				if ($google_fonts && $google_fonts_enabled)
					$links['google_fonts'] = good_wine_shop_get_protocol() . '://fonts.googleapis.com/css?family=' . trim($google_fonts) . '&subset=latin,latin-ext';
			}
		}
		return $links;
	}
}

// Return links for WP Editor
if ( !function_exists('good_wine_shop_theme_fonts_for_editor') ) {
	function good_wine_shop_theme_fonts_for_editor() {
		$links = array_values(good_wine_shop_theme_fonts_links(false));
		if (is_array($links) && count($links) > 0) {
			for ($i=0; $i<count($links); $i++) {
				$links[$i] = str_replace(',', '%2C', $links[$i]);
			}
		}
		return $links;
	}
}


//-------------------------------------------------------
//-- The Excerpt
//-------------------------------------------------------
if ( !function_exists('good_wine_shop_excerpt_length') ) {
	function good_wine_shop_excerpt_length( $length ) {
		return max(1, good_wine_shop_get_theme_setting('max_excerpt_length'));
	}
}

if ( !function_exists('good_wine_shop_excerpt_more') ) {
	function good_wine_shop_excerpt_more( $more ) {
		return '&hellip;';
	}
}



//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'themerex_importer_set_options' ) ) {
    add_filter( 'trx_addons_filter_importer_options', 'good_wine_shop_importer_set_options', 9 );
    function good_wine_shop_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( GOOD_WINE_SHOP_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = GOOD_WINE_SHOP_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( good_wine_shop_get_protocol().'://demofiles.axiomthemes.com/good-wine-shop/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'trx_addons',
                'js_composer',
                'woocommerce',
                'essential-grid',
                'contact-form-7'
            );

            $options['theme_slug'] = 'good-wine-shop';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Good Wine Shop Demo', 'good-wine-shop' );
            $options['files']['default']['domain_dev'] = esc_url('https://goodwine.axiomthemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url('https://goodwine.axiomthemes.com'); // Demo-site domain

        }
        return $options;
    }
}


//-------------------------------------------------------
//-- Third party plugins
//-------------------------------------------------------

// Register optional plugins
if ( !function_exists( 'good_wine_shop_register_plugins' ) ) {
	function good_wine_shop_register_plugins() {
		tgmpa(	apply_filters('good_wine_shop_filter_tgmpa_required_plugins', array(
				// Plugins to include in the autoinstall queue.
				)),
				array(
					'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
					'default_path' => '',                      // Default absolute path to bundled plugins.
					'menu'         => 'tgmpa-install-plugins', // Menu slug.
					'parent_slug'  => 'themes.php',            // Parent menu slug.
					'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
					'has_notices'  => true,                    // Show admin notices or not.
					'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
					'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
					'is_automatic' => true,                    // Automatically activate plugins after installation or not.
					'message'      => ''                       // Message to output right before the plugins table.
				)
			);
	}
}



//-------------------------------------------------------
//-- Include theme (or child) PHP-files
//-------------------------------------------------------

require_once trailingslashit( get_template_directory() ) . 'includes/utils.php';
require_once trailingslashit( get_template_directory() ) . 'includes/storage.php';
require_once trailingslashit( get_template_directory() ) . 'includes/lists.php';
require_once trailingslashit( get_template_directory() ) . 'includes/wp.php';

require_once trailingslashit( get_template_directory() ) . 'includes/theme.tags.php';
require_once trailingslashit( get_template_directory() ) . 'includes/theme.hovers/theme.hovers.php';

if (is_admin()) {
	require_once trailingslashit( get_template_directory() ) . 'includes/tgmpa/class-tgm-plugin-activation.php';
}

require_once trailingslashit( get_template_directory() ) . 'theme-options/theme.customizer.php';

// Plugins support
if (is_array($GOOD_WINE_SHOP_STORAGE['required_plugins']) && count($GOOD_WINE_SHOP_STORAGE['required_plugins']) > 0) {
	foreach ($GOOD_WINE_SHOP_STORAGE['required_plugins'] as $plugin_slug) {
		require_once trailingslashit( get_template_directory() ) . 'plugins/plugin.'.trim(sanitize_file_name($plugin_slug)).'.php';
	}
}

?>