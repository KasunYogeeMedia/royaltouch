<?php
/**
 * Default Theme Options and Internal Theme Settings
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_options_theme_setup2')) {
    add_action( 'after_setup_theme', 'good_wine_shop_options_theme_setup2', 2 );
    function good_wine_shop_options_theme_setup2() {
        good_wine_shop_options_create();
    }
}

// Step 1: Load default settings and previously saved mods
if (!function_exists('good_wine_shop_options_theme_setup5')) {
    add_action( 'after_setup_theme', 'good_wine_shop_options_theme_setup5', 5 );
    function good_wine_shop_options_theme_setup5() {
        good_wine_shop_load_theme_options();
    }
}

// Step 2: Load current theme customization mods
if (is_customize_preview()) {
    if (!function_exists('good_wine_shop_load_custom_options')) {
        add_action( 'wp_loaded', 'good_wine_shop_load_custom_options' );
        function good_wine_shop_load_custom_options() {
            good_wine_shop_load_theme_options();
        }
    }
}

// Load current values for each customizable option
if ( !function_exists('good_wine_shop_load_theme_options') ) {
    function good_wine_shop_load_theme_options() {
        $options = good_wine_shop_storage_get('options');
        foreach ($options as $k=>$v) {
            if (isset($v['std'])) {
                if (strpos($v['std'], '$good_wine_shop_')!==false) {
                    $func = substr($v['std'], 1);
                    if (function_exists($func)) {
                        $v['std'] = $func($k);
                    }
                }
                good_wine_shop_storage_set_array2('options', $k, 'val', isset($_GET[$k])
                    ? good_wine_shop_get_value_gpc($k)
                    : get_theme_mod($k, $v['std'])
                );
            }
        }
        do_action('good_wine_shop_action_load_options');
    }
}

// Override options with stored page/post meta
if ( !function_exists('good_wine_shop_override_theme_options') ) {
    add_action( 'wp', 'good_wine_shop_override_theme_options', 1 );
    function good_wine_shop_override_theme_options($query=null) {
        if (is_page_template('blog.php')) {
            good_wine_shop_storage_set('blog_archive', true);
            good_wine_shop_storage_set('blog_template', get_the_ID());
        }
        good_wine_shop_storage_set('blog_mode', good_wine_shop_detect_blog_mode());
        if (is_singular()) {
            good_wine_shop_storage_set('options_meta', get_post_meta(get_the_ID(), 'good_wine_shop_options', true));
        }
    }
}


// Return customizable option value
if (!function_exists('good_wine_shop_get_theme_option')) {
    function good_wine_shop_get_theme_option($name, $defa='', $strict_mode=false, $post_id=0) {
        $rez = $defa;
        $from_post_meta = false;
        if ($post_id > 0) {
            if (!good_wine_shop_storage_isset('post_options_meta', $post_id))
                good_wine_shop_storage_set_array('post_options_meta', $post_id, get_post_meta($post_id, 'good_wine_shop_options', true));
            if (good_wine_shop_storage_isset('post_options_meta', $post_id, $name)) {
                $tmp = good_wine_shop_storage_get_array('post_options_meta', $post_id, $name);
                if (!good_wine_shop_is_inherit($tmp)) {
                    $rez = $tmp;
                    $from_post_meta = true;
                }
            }
        }
        if (!$from_post_meta && good_wine_shop_storage_isset('options')) {
            if ( !good_wine_shop_storage_isset('options', $name) ) {
                $rez = $tmp = '_not_exists_';
                if (function_exists('trx_addons_get_option'))
                    $rez = trx_addons_get_option($name, $tmp, false);
                if ($rez === $tmp) {
                    if ($strict_mode) {
                        $s = debug_backtrace();
                        $s = array_shift($s);
                        echo '<pre>' . sprintf(esc_html__('Undefined option "%s" called from:', 'good-wine-shop'), $name);
                        if (function_exists('dco')) dco($s);
                        else print_r($s);
                        echo '</pre>';
                        wp_die();
                    } else
                        $rez = $defa;
                }
            } else {
                $blog_mode = good_wine_shop_storage_get('blog_mode');
                // Override option from GET or POST for current blog mode
                if (!empty($blog_mode) && isset($_REQUEST[$name.'_'.trim($blog_mode)])) {
                    $rez = sanitize_text_field($_REQUEST[$name.'_'.trim($blog_mode)]);
                    // Override option from GET
                } else if (isset($_REQUEST[$name])) {
                    $rez = sanitize_text_field($_REQUEST[$name]);
                    // Override option from current page settings (if exists)
                } else if (good_wine_shop_storage_isset('options_meta', $name) && !good_wine_shop_is_inherit(good_wine_shop_storage_get_array('options_meta', $name))) {
                    $rez = good_wine_shop_storage_get_array('options_meta', $name);
                    // Override option from current blog mode settings: 'home', 'search', 'page', 'post', 'blog', etc. (if exists)
                } else if (!empty($blog_mode) && good_wine_shop_storage_isset('options', $name.'_'.trim($blog_mode), 'val') && !good_wine_shop_is_inherit(good_wine_shop_storage_get_array('options', $name.'_'.trim($blog_mode), 'val'))) {
                    $rez = good_wine_shop_storage_get_array('options', $name.'_'.trim($blog_mode), 'val');
                    // Get saved option value
                } else if (good_wine_shop_storage_isset('options', $name, 'val')) {
                    $rez = good_wine_shop_storage_get_array('options', $name, 'val');
                    // Get GOOD_WINE_SHOP_Addons option value
                } else if (function_exists('trx_addons_get_option')) {
                    $rez = trx_addons_get_option($name, $defa, false);
                }
            }
        }
        return $rez;
    }
}


// Check if customizable option exists
if (!function_exists('good_wine_shop_check_theme_option')) {
    function good_wine_shop_check_theme_option($name) {
        return good_wine_shop_storage_isset('options', $name);
    }
}

// Get dependencies list from the Theme Options
if ( !function_exists('good_wine_shop_get_theme_dependencies') ) {
    function good_wine_shop_get_theme_dependencies() {
        $options = good_wine_shop_storage_get('options');
        $depends = array();
        foreach ($options as $k=>$v) {
            if (isset($v['dependency']))
                $depends[$k] = $v['dependency'];
        }
        return $depends;
    }
}

// Return internal theme setting value
if (!function_exists('good_wine_shop_get_theme_setting')) {
    function good_wine_shop_get_theme_setting($name) {
        return good_wine_shop_storage_isset('settings', $name) ? good_wine_shop_storage_get_array('settings', $name) : false;
    }
}


// Set theme setting
if ( !function_exists( 'good_wine_shop_set_theme_setting' ) ) {
    function good_wine_shop_set_theme_setting($option_name, $value) {
        if (good_wine_shop_storage_isset('settings', $option_name))
            good_wine_shop_storage_set_array('settings', $option_name, $value);
    }
}


// -----------------------------------------------------------------
// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// -- Internal theme settings
// -----------------------------------------------------------------
good_wine_shop_storage_set('settings', array(

    'custom_sidebars'	=> 8,									// How many custom sidebars will be registered (in addition to theme preset sidebars): 0 - 10

    'ajax_views_counter'=> true,								// Use AJAX for increment posts counter (if cache plugins used)
    // or increment posts counter then loading page (without cache plugin)
    'breadcrumbs_max_level' 	=> 3,							// Max number of the nested categories in the breadcrumbs (0 - unlimited)

    'ajax_search_types'			=> 'post,page',					// Comma separated (without spaces) post types which can be searched
    'ajax_search_posts_count'	=> 4,							// How many posts showed in the search results

    'use_mediaelements'	=> true,								// Load script "Media Elements" to play video and audio

    'max_excerpt_length'=> 60,									// Max words number for the excerpt in the blog style 'Excerpt'.
    // For style 'Classic' - get half from this value
    'message_maxlength'	=> 1000,								// Max length of the message from contact form

    'admin_dummy_timeout' => 1200,								// Timeframe for PHP scripts when import dummy data
    'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
));



// -----------------------------------------------------------------
// -- Theme fonts (Google and/or custom fonts)
// -----------------------------------------------------------------
good_wine_shop_storage_set('theme_fonts', array(
    'p' => array(					// Text
        'family'=> '"Lato", sans-serif',
        'link'	=> 'Lato:400,700'
    ),
    'h1' => array(
        'family'=> '"Crimson Text", cursive',
        'link'  => 'Crimson+Text:400,400i,600,600i,700,700i'
    ),
    'h2' => array(
        'family'=> '"Crimson Text", cursive'
    ),
    'h3' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'h4' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'h5' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'h6' => array(
        'family'=> '"Crimson Text", cursive'
    ),
    'input' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'logo' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'info' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'menu' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'submenu' => array(
        'family'=> '"Lato", sans-serif'
    ),
    'decor' => array(
        'family'=>'"Passion One", cursive',
        'link'=>'Passion+One:400'
    )
));


// -----------------------------------------------------------------
// -- Theme colors for customizer
// -- Attention! Inner scheme must be last in the array below
// -----------------------------------------------------------------
good_wine_shop_storage_set('schemes', array(

    // Color scheme: 'default'
    'default' => array(
        'title'	 => esc_html__('Default', 'good-wine-shop'),
        'colors' => array(

            // Whole block border and background
            'bg_color'				=> '#ffffff',
            'bd_color'				=> '#b6b4b3',

            // Text and links colors
            'text'					=> '#7a7a7a',
            'text_light'			=> '#9d9c9c',
            'text_dark'				=> '#2a2a2a',
            'text_link'				=> '#e39a31',
            'text_hover'			=> '#2a2a2a',

            // Alternative blocks (submenu, buttons, tabs, etc.)
            'alter_bg_color'		=> '#f7f7f7',
            'alter_bg_hover'		=> '#e5e5e5',
            'alter_bd_color'		=> '#d7d7d7',
            'alter_bd_hover'		=> '#c5c5c5',
            'alter_text'			=> '#5f5f5f',
            'alter_light'			=> '#9d9c9c',
            'alter_dark'			=> '#2a2a2a',
            'alter_link'			=> '#e39a31',
            'alter_hover'			=> '#2a2a2a',

            // Input fields (form's fields and textarea)
            'input_bg_color'		=> '#ffffff',
            'input_bg_hover'		=> '#ffffff',
            'input_bd_color'		=> '#cccccc',
            'input_bd_hover'		=> '#1d1d1d',
            'input_text'			=> '#7a7a7a',
            'input_light'			=> '#a1a1a1',
            'input_dark'			=> '#1d1d1d',

            // Inverse blocks (text and links on accented bg)
            'inverse_text'			=> '#ffffff',
            'inverse_light'			=> '#f0f0f0',
            'inverse_dark'			=> '#ffffff',
            'inverse_link'			=> '#e39a31',
            'inverse_hover'			=> '#ffffff',

            // Additional accented colors (if used in the current theme)
            'accent2'               =>  '#a12332',
        )
    )

));



// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('good_wine_shop_options_create')) {

    function good_wine_shop_options_create() {

        good_wine_shop_storage_set('options', array(

            // Section 'Title & Tagline' - add theme options in the standard WP section
            'title_tagline' => array(
                "title" => esc_html__('Title, Tagline & Site icon', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Specify site title and tagline (if need) and upload the site icon', 'good-wine-shop') ),
                "type" => "section"
            ),


            // Section 'Header' - add theme options in the standard WP section
            'header_image' => array(
                "title" => esc_html__('Header', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select or upload logo images, select header type and widgets set for the header', 'good-wine-shop') ),
                "type" => "section"
            ),
            'header_image_override' => array(
                "title" => esc_html__('Header image override', 'good-wine-shop'),
                "desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 0,
                "type" => "checkbox"
            ),
            'header_video' => array(
                "title" => esc_html__('Header video', 'good-wine-shop'),
                "desc" => wp_kses_data( __("Select video to use it as background for the header", 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => '',
                "type" => "video"
            ),
            'header_fullheight' => array(
                "title" => esc_html__('Fullheight Header', 'good-wine-shop'),
                "desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 0,
                "type" => "checkbox"
            ),
            'header_style' => array(
                "title" => esc_html__('Header style', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select style to display the site header', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 'header-1',
                "options" => good_wine_shop_get_list_header_styles(),
                "type" => "select"
            ),
            'header_position' => array(
                "title" => esc_html__('Header position', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select position to display the site header', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 'default',
                "options" => good_wine_shop_get_list_header_positions(),
                "type" => "select"
            ),
            'header_scheme' => array(
                "title" => esc_html__('Header Color Scheme', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select color scheme to decorate header area', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 'inherit',
                "options" => good_wine_shop_get_list_schemes(true),
                "refresh" => false,
                "type" => "select"
            ),
            'header_greetings' => array(
                "title" => esc_html__('Greetings in header', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Put here greeting text to insert into the Header "Style 1"', 'good-wine-shop') ),
                "dependency" => array(
                    'header_style' => array('header-1')
                ),
                "std" => esc_html__('Welcome to our website!', 'good-wine-shop'),
                "type" => "text"
            ),
            'header_info' => array(
                "title" => esc_html__('Contact info in header', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Put here contact info to insert into the Header "Style 1"', 'good-wine-shop') ),
                "dependency" => array(
                    'header_style' => array('header-1')
                ),
                "std" => '',
                "type" => "text"
            ),





            'menu_style' => array(
                "title" => esc_html__('Menu style', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select style to display the main menu', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 'top',
                "options" => good_wine_shop_get_list_menu_styles(),
                "type" => "switch"
            ),
            'menu_scheme' => array(
                "title" => esc_html__('Menu Color Scheme', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select color scheme to decorate main menu area', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 'inherit',
                "options" => good_wine_shop_get_list_schemes(true),
                "refresh" => false,
                "type" => "select"
            ),
            'menu_cache' => array(
                "title" => esc_html__('Use menu cache', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Use cache for the menu (increase theme speed, decrease queries number). Attention! Please, save menu again after change permalink settings!', 'good-wine-shop') ),
                "std" => 1,
                "type" => "checkbox"
            ),
            'search_style' => array(
                "title" => esc_html__('Search in the header', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select style of the search field in the header', 'good-wine-shop') ),
                "std" => 'expand',
                "options" => array(
                    'expand' => esc_html__('Expand', 'good-wine-shop'),
                    'fullscreen' => esc_html__('Fullscreen', 'good-wine-shop')
                ),
                "type" => "switch"
            ),
            'header_widgets' => array(
                "title" => esc_html__('Header widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop'),
                    "desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'good-wine-shop') ),
                ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'header_columns' => array(
                "title" => esc_html__('Header columns', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "dependency" => array(
                    'header_widgets' => array('^hide')
                ),
                "std" => 0,
                "options" => good_wine_shop_get_list_range(0,6),
                "type" => "select"
            ),
            'header_wide' => array(
                "title" => esc_html__('Header fullwide', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 1,
                "type" => "checkbox"
            ),
            'show_page_title' => array(
                "title" => esc_html__('Show Page Title', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Do you want to show page title area (page/post/category title and breadcrumbs)?', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'good-wine-shop')
                ),
                "std" => 1,
                "type" => "checkbox"
            ),
            'show_breadcrumbs' => array(
                "title" => esc_html__('Show breadcrumbs', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Do you want to show breadcrumbs in the page title area?', 'good-wine-shop') ),
                "std" => 0,
                "type" => "checkbox"
            ),
            'logo' => array(
                "title" => esc_html__('Logo', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select or upload site logo', 'good-wine-shop') ),
                "std" => '',
                "type" => "image"
            ),
            'logo_retina' => array(
                "title" => esc_html__('Logo for Retina', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'good-wine-shop') ),
                "std" => '',
                "type" => "image"
            ),
            'mobile_layout_width' => array(
                "title" => esc_html__('Mobile layout from', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Window width to show mobile layout of the header', 'good-wine-shop') ),
                "std" => 959,
                "type" => "text"
            ),



            // Section 'Content'
            'content' => array(
                "title" => esc_html__('Content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Options for the content area', 'good-wine-shop') ),
                "type" => "section",
            ),
            'body_style' => array(
                "title" => esc_html__('Body style', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select width of the body content', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page,cpt_team,cpt_courses',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "refresh" => false,
                "std" => 'wide',
                "options" => good_wine_shop_get_list_body_styles(),
                "type" => "select"
            ),
            'color_scheme' => array(
                "title" => esc_html__('Site Color Scheme', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select color scheme to decorate whole site', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "std" => 'default',
                "options" => good_wine_shop_get_list_schemes(true),
                "refresh" => false,
                "type" => "select"
            ),
            'expand_content' => array(
                "title" => esc_html__('Expand content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page,cpt_team,cpt_courses',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "refresh" => false,
                "std" => 0,
                "type" => "checkbox"
            ),
            'remove_margins' => array(
                "title" => esc_html__('Remove margins', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Remove margins above and below the content area', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page,cpt_team,cpt_courses',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "refresh" => false,
                "std" => 0,
                "type" => "checkbox"
            ),
            'sidebar_widgets' => array(
                "title" => esc_html__('Sidebar widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page,cpt_team,cpt_courses',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "std" => 'sidebar_widgets',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'sidebar_scheme' => array(
                "title" => esc_html__('Color Scheme', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select color scheme to decorate sidebar', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page,cpt_team,cpt_courses',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "std" => 'default',
                "options" => good_wine_shop_get_list_schemes(true),
                "refresh" => false,
                "type" => "select"
            ),
            'sidebar_position' => array(
                "title" => esc_html__('Sidebar position', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select position to show sidebar', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page,cpt_team,cpt_courses',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "refresh" => false,
                "std" => 'right',
                "options" => good_wine_shop_get_list_sidebars_positions(),
                "type" => "select"
            ),
            'widgets_above_page' => array(
                "title" => esc_html__('Widgets above the page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_above_content' => array(
                "title" => esc_html__('Widgets above the content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_below_content' => array(
                "title" => esc_html__('Widgets below the content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_below_page' => array(
                "title" => esc_html__('Widgets below the page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Widgets', 'good-wine-shop')
                ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'good-wine-shop'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'good-wine-shop') ),
                "std"   => wp_kses_post( __( 'I agree that my submitted data is being collected and stored.', 'good-wine-shop') ),
                "type"  => "text"
            ),



            // Section 'Footer'
            'footer' => array(
                "title" => esc_html__('Footer', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select set of widgets and columns number for the site footer', 'good-wine-shop') ),
                "type" => "section"
            ),
            'footer_scheme' => array(
                "title" => esc_html__('Color Scheme', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Footer', 'good-wine-shop')
                ),
                "std" => 'default',
                "options" => good_wine_shop_get_list_schemes(true),
                "refresh" => false,
                "type" => "select"
            ),
            'footer_widgets' => array(
                "title" => esc_html__('Footer widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Footer', 'good-wine-shop')
                ),
                "std" => 'footer_widgets',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'footer_columns' => array(
                "title" => esc_html__('Footer columns', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Footer', 'good-wine-shop')
                ),
                "dependency" => array(
                    'footer_widgets' => array('^hide')
                ),
                "std" => 3,
                "options" => good_wine_shop_get_list_range(0,6),
                "type" => "select"
            ),
            'footer_wide' => array(
                "title" => esc_html__('Footer fullwide', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Footer', 'good-wine-shop')
                ),
                "std" => 0,
                "type" => "checkbox"
            ),
            'logo_in_footer' => array(
                "title" => esc_html__('Show logo', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Show logo in the footer', 'good-wine-shop') ),
                'refresh' => false,
                "std" => 0,
                "type" => "checkbox"
            ),
            'logo_footer' => array(
                "title" => esc_html__('Logo for footer', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'good-wine-shop') ),
                "dependency" => array(
                    'logo_in_footer' => array('1')
                ),
                "std" => '',
                "type" => "image"
            ),
            'logo_footer_retina' => array(
                "title" => esc_html__('Logo for footer (Retina)', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'good-wine-shop') ),
                "dependency" => array(
                    'logo_in_footer' => array('1')
                ),
                "std" => '',
                "type" => "image"
            ),
            'socials_in_footer' => array(
                "title" => esc_html__('Show social icons', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'good-wine-shop') ),
                "std" => 0,
                "type" => "checkbox"
            ),
            'copyright' => array(
                "title" => esc_html__('Copyright', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Copyright text in the footer', 'good-wine-shop') ),
                "std" => esc_html__('AxiomThemes &copy; {Y}. All rights reserved. Terms of use and Privacy Policy', 'good-wine-shop'),
                "refresh" => false,
                "type" => "editor"
            ),



            // Section 'Homepage' - settings for home page
            'homepage' => array(
                "title" => esc_html__('Homepage', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select blog style and widgets to display on the homepage', 'good-wine-shop') ),
                "type" => "section"
            ),
            'expand_content_home' => array(
                "title" => esc_html__('Expand content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden on the Homepage', 'good-wine-shop') ),
                "refresh" => false,
                "std" => 1,
                "type" => "checkbox"
            ),
            'blog_style_home' => array(
                "title" => esc_html__('Blog style', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select posts style for the homepage', 'good-wine-shop') ),
                "std" => 'excerpt',
                "options" => good_wine_shop_get_list_blog_styles(),
                "type" => "select"
            ),
            'first_post_large_home' => array(
                "title" => esc_html__('First post large', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Make first post large (with Excerpt layout) on the Classic layout of the Homepage', 'good-wine-shop') ),
                "dependency" => array(
                    'blog_style_home' => array('classic')
                ),
                "std" => 0,
                "type" => "checkbox"
            ),
            'header_widgets_home' => array(
                "title" => esc_html__('Header widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select set of widgets to show in the header on the homepage', 'good-wine-shop') ),
                "std" => 'header_widgets',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'sidebar_widgets_home' => array(
                "title" => esc_html__('Sidebar widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select sidebar to show on the homepage', 'good-wine-shop') ),
                "std" => 'sidebar_widgets',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'sidebar_position_home' => array(
                "title" => esc_html__('Sidebar position', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select position to show sidebar on the homepage', 'good-wine-shop') ),
                "refresh" => false,
                "std" => 'right',
                "options" => good_wine_shop_get_list_sidebars_positions(),
                "type" => "select"
            ),
            'widgets_above_page_home' => array(
                "title" => esc_html__('Widgets above the page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_above_content_home' => array(
                "title" => esc_html__('Widgets above the content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_below_content_home' => array(
                "title" => esc_html__('Widgets below the content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_below_page_home' => array(
                "title" => esc_html__('Widgets below the page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),



            // Section 'Blog archive'
            'blog' => array(
                "title" => esc_html__('Blog archive', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Options for the blog archive', 'good-wine-shop') ),
                "type" => "section",
            ),
            'expand_content_blog' => array(
                "title" => esc_html__('Expand content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden on the blog archive', 'good-wine-shop') ),
                "refresh" => false,
                "std" => 1,
                "type" => "checkbox"
            ),
            'blog_style' => array(
                "title" => esc_html__('Blog style', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select posts style for the blog archive', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "dependency" => array(
                    '#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
                ),
                "std" => 'excerpt',
                "options" => good_wine_shop_get_list_blog_styles(),
                "type" => "select"
            ),
            'parent_cat' => array(
                "title" => esc_html__('Category to show', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select category to show in the blog archive', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "dependency" => array(
                    '#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
                ),
                "std" => '0',
                "options" => good_wine_shop_array_merge(array(0 => esc_html__('- Select category -', 'good-wine-shop')), good_wine_shop_get_list_categories()),
                "type" => "select"
            ),
            'posts_per_page' => array(
                "title" => esc_html__('Posts per page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('How many posts will be displayed on this page', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "dependency" => array(
                    '#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
                ),
                "hidden" => true,
                "std" => '10',
                "type" => "text"
            ),
            "blog_pagination" => array(
                "title" => esc_html__('Pagination style', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "std" => "links",
                "options" => good_wine_shop_get_list_paginations(),
                "type" => "select"
            ),
            'related_post_placeholder' => array(
                "title" => esc_html__('Related post image placeholder', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Show blank image if post has no featured image', 'good-wine-shop') ),
                "refresh" => false,
                "std" => 0,
                "type" => "checkbox"
            ),
            'show_filters' => array(
                "title" => esc_html__('Show filters', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "dependency" => array(
                    '#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
                    'blog_style' => array('portfolio', 'gallery')
                ),
                "hidden" => true,
                "std" => 0,
                "type" => "checkbox"
            ),
            'first_post_large' => array(
                "title" => esc_html__('First post large', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Make first post large (with Excerpt layout) on the Classic layout of blog archive', 'good-wine-shop') ),
                "dependency" => array(
                    'blog_style' => array('classic')
                ),
                "std" => 0,
                "type" => "checkbox"
            ),
            "blog_content" => array(
                "title" => esc_html__('Posts content', 'good-wine-shop'),
                "desc" => wp_kses_data( __("Show full post's content in the blog or only post's excerpt", 'good-wine-shop') ),
                "std" => "excerpt",
                "options" => good_wine_shop_get_list_blog_content(),
                "type" => "select"
            ),
            "blog_animation" => array(
                "title" => esc_html__('Animation for posts', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select animation to show posts in the blog', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Content', 'good-wine-shop')
                ),
                "dependency" => array(
                    '#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
                ),
                "std" => "none",
                "options" => good_wine_shop_get_list_animations_in(),
                "type" => "select"
            ),
            "animation_on_mobile" => array(
                "title" => esc_html__('Allow animation on mobile', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Allow extended animation effects on mobile devices', 'good-wine-shop') ),
                "std" => 'yes',
                "dependency" => array(
                    'blog_animation' => array('^none')
                ),
                "options" => good_wine_shop_get_list_yesno(),
                "type" => "switch"
            ),
            'header_widgets_blog' => array(
                "title" => esc_html__('Header widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select set of widgets to show in the header on the blog archive', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'sidebar_widgets_blog' => array(
                "title" => esc_html__('Sidebar widgets', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select sidebar to show on the blog archive', 'good-wine-shop') ),
                "std" => 'sidebar_widgets',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'sidebar_position_blog' => array(
                "title" => esc_html__('Sidebar position', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select position to show sidebar on the blog archive', 'good-wine-shop') ),
                "refresh" => false,
                "std" => 'right',
                "options" => good_wine_shop_get_list_sidebars_positions(),
                "type" => "select"
            ),
            'widgets_above_page_blog' => array(
                "title" => esc_html__('Widgets above the page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_above_content_blog' => array(
                "title" => esc_html__('Widgets above the content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_below_content_blog' => array(
                "title" => esc_html__('Widgets below the content', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),
            'widgets_below_page_blog' => array(
                "title" => esc_html__('Widgets below the page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'good-wine-shop') ),
                "std" => 'hide',
                "options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
                "type" => "select"
            ),





            // Section 'Colors' - choose color scheme and customize separate colors from it
            'scheme' => array(
                "title" => esc_html__('Color scheme editor', 'good-wine-shop'),
                "desc" => wp_kses_data( __("<b>Simple settings</b> - you can change only accented color, used for links, buttons and some accented areas.", 'good-wine-shop') )
                    . '<br>'
                    . wp_kses_data( __("<b>Advanced settings</b> - change all scheme's colors and get full control over the appearance of your site!", 'good-wine-shop') ),
                "priority" => 1000,
                "type" => "section"
            ),

            'color_settings' => array(
                "title" => esc_html__('Color settings', 'good-wine-shop'),
                "desc" => '',
                "std" => 'simple',
                "options" => good_wine_shop_get_list_user_skills(),
                "refresh" => false,
                "type" => "switch"
            ),

            'color_scheme_editor' => array(
                "title" => esc_html__('Color Scheme', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Select color scheme to edit colors', 'good-wine-shop') ),
                "std" => 'default',
                "options" => good_wine_shop_get_list_schemes(),
                "refresh" => false,
                "type" => "select"
            ),

            'scheme_storage' => array(
                "title" => esc_html__('Colors storage', 'good-wine-shop'),
                "desc" => esc_html__('Hidden storage of the all color from the all color shemes (only for internal usage)', 'good-wine-shop'),
                "std" => '',
                "refresh" => false,
                "type" => "hidden"
            ),

            'scheme_info_single' => array(
                "title" => esc_html__('Colors for single post/page', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Specify colors for single post/page (not for alter blocks)', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "type" => "info"
            ),

            'bg_color' => array(
                "title" => esc_html__('Background color', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Background color of the whole page', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'bd_color' => array(
                "title" => esc_html__('Border color', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the bordered elements, separators, etc.', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),

            'text' => array(
                "title" => esc_html__('Text', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Plain text color on single page/post', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'text_light' => array(
                "title" => esc_html__('Light text', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the post meta: post date and author, comments number, etc.', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'text_dark' => array(
                "title" => esc_html__('Dark text', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the headers, strong text, etc.', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'text_link' => array(
                "title" => esc_html__('Links', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of links and accented areas', 'good-wine-shop') ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'text_hover' => array(
                "title" => esc_html__('Links hover', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Hover color for links and accented areas', 'good-wine-shop') ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),

            'scheme_info_alter' => array(
                "title" => esc_html__('Colors for alternative blocks', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Specify colors for alternative blocks - rectangular blocks with its own background color (posts in homepage, blog archive, search results, widgets on sidebar, footer, etc.)', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "type" => "info"
            ),

            'alter_bg_color' => array(
                "title" => esc_html__('Alter background color', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Background color of the alternative blocks', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_bg_hover' => array(
                "title" => esc_html__('Alter hovered background color', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Background color for the hovered state of the alternative blocks', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_bd_color' => array(
                "title" => esc_html__('Alternative border color', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Border color of the alternative blocks', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_bd_hover' => array(
                "title" => esc_html__('Alternative hovered border color', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Border color for the hovered state of the alter blocks', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_text' => array(
                "title" => esc_html__('Alter text', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Text color of the alternative blocks', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_light' => array(
                "title" => esc_html__('Alter light', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the info blocks inside block with alternative background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_dark' => array(
                "title" => esc_html__('Alter dark', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the headers inside block with alternative background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_link' => array(
                "title" => esc_html__('Alter link', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the links inside block with alternative background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'alter_hover' => array(
                "title" => esc_html__('Alter hover', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the hovered links inside block with alternative background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),

            'scheme_info_input' => array(
                "title" => esc_html__('Colors for the form fields', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Specify colors for the form fields and textareas', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "type" => "info"
            ),

            'input_bg_color' => array(
                "title" => esc_html__('Inactive background', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Background color of the inactive form fields', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'input_bg_hover' => array(
                "title" => esc_html__('Active background', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Background color of the focused form fields', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'input_bd_color' => array(
                "title" => esc_html__('Inactive border', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the border in the inactive form fields', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'input_bd_hover' => array(
                "title" => esc_html__('Active border', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the border in the focused form fields', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'input_text' => array(
                "title" => esc_html__('Inactive field', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the text in the inactive fields', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'input_light' => array(
                "title" => esc_html__('Disabled field', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the disabled field', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'input_dark' => array(
                "title" => esc_html__('Active field', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the active field', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),

            'scheme_info_inverse' => array(
                "title" => esc_html__('Colors for inverse blocks', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Specify colors for inverse blocks, rectangular blocks with background color equal to the links color or one of accented colors (if used in the current theme)', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "type" => "info"
            ),

            'inverse_text' => array(
                "title" => esc_html__('Inverse text', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the text inside block with accented background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'inverse_light' => array(
                "title" => esc_html__('Inverse light', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the info blocks inside block with accented background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'inverse_dark' => array(
                "title" => esc_html__('Inverse dark', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the headers inside block with accented background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'inverse_link' => array(
                "title" => esc_html__('Inverse link', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the links inside block with accented background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),
            'inverse_hover' => array(
                "title" => esc_html__('Inverse hover', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Color of the hovered links inside block with accented background', 'good-wine-shop') ),
                "dependency" => array(
                    'color_settings' => array('^simple')
                ),
                "std" => '$good_wine_shop_get_scheme_color',
                "refresh" => false,
                "type" => "color"
            ),


            // Hidden options for override in the posts
            'media_title' => array(
                "title" => esc_html__('Media title', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'post',
                    'section' => esc_html__('Title', 'good-wine-shop')
                ),
                "hidden" => true,
                "std" => '',
                "type" => "text"
            ),
            'media_author' => array(
                "title" => esc_html__('Media author', 'good-wine-shop'),
                "desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'good-wine-shop') ),
                "override" => array(
                    'mode' => 'post',
                    'section' => esc_html__('Title', 'good-wine-shop')
                ),
                "hidden" => true,
                "std" => '',
                "type" => "text"
            ),

        ));
    }
}
?>