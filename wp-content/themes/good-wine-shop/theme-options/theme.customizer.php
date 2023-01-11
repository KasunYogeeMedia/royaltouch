<?php
/**
 * Theme customizer
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

//--------------------------------------------------------------
//-- Register Customizer Controls
//--------------------------------------------------------------

define('CUSTOMIZE_PRIORITY', 200);		// Start priority for the new controls

if (!function_exists('good_wine_shop_customizer_register_controls')) {
    add_action( 'customize_register', 'good_wine_shop_customizer_register_controls', 11 );
    function good_wine_shop_customizer_register_controls( $wp_customize ) {

        // Setup standard WP Controls
        // ---------------------------------

        // Remove unused sections
        $wp_customize->remove_section( 'colors');
        $wp_customize->remove_section( 'static_front_page');

        // Reorder standard WP sections
        $sec = $wp_customize->get_panel( 'nav_menus' );
        if (is_object($sec)) $sec->priority = 30;
        $sec = $wp_customize->get_panel( 'widgets' );
        if (is_object($sec)) $sec->priority = 40;
        $sec = $wp_customize->get_section( 'title_tagline' );
        if (is_object($sec)) $sec->priority = 50;
        $sec = $wp_customize->get_section( 'background_image' );
        if (is_object($sec)) $sec->priority = 60;
        $sec = $wp_customize->get_section( 'header_image' );
        if (is_object($sec)) $sec->priority = 80;

        // Modify standard WP controls
        $sec = $wp_customize->get_control( 'blogname' );
        if (is_object($sec)) $sec->description      = esc_html__('Use "[[" and "]]" to modify style and color of parts of the text, "||" to break current line', 'good-wine-shop');
        $sec = $wp_customize->get_setting( 'blogname' );
        if (is_object($sec)) $sec->transport = 'postMessage';

        $sec = $wp_customize->get_setting( 'blogdescription' );
        if (is_object($sec)) $sec->transport = 'postMessage';

        $sec = $wp_customize->get_section( 'background_image' );
        if (is_object($sec)) {
            $sec->title = esc_html__('Background', 'good-wine-shop');
            $sec->description = esc_html__('Used only if "Content - Body style" equal to "boxed"', 'good-wine-shop');
        }

        // Move standard option 'Background Color' to the section 'Background Image'
        $wp_customize->add_setting( 'background_color', array(
            'default'        => get_theme_support( 'custom-background', 'default-color' ),
            'theme_supports' => 'custom-background',
            'transport'		 => 'postMessage',
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
            'label'   => esc_html__( 'Background color', 'good-wine-shop' ),
            'section' => 'background_image',
        ) ) );


        // Add Theme specific controls
        // ---------------------------------

        $panels = array('');
        $p = 0;
        $sections = array('');
        $s = 0;
        $i = 0;

        $options = good_wine_shop_storage_get('options');

        foreach ($options as $id=>$opt) {

            $i++;

            if (!empty($opt['hidden'])) continue;

            if ($opt['type'] == 'panel') {

                $sec = $wp_customize->get_panel( $id );
                if ( is_object($sec) && !empty($sec->title) ) {
                    $sec->title      = $opt['title'];
                    $sec->description= $opt['desc'];
                    if ( !empty($opt['priority']) )	$sec->priority = $opt['priority'];
                } else {
                    $wp_customize->add_panel( esc_attr($id) , array(
                        'title'      => $opt['title'],
                        'description'=> $opt['desc'],
                        'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i
                    ) );
                }
                array_push($panels, $id);
                $p++;

            } else if ($opt['type'] == 'panel_end') {

                array_pop($panels);
                $p--;

            } else if ($opt['type'] == 'section') {

                $sec = $wp_customize->get_section( $id );
                if ( is_object($sec) && !empty($sec->title) ) {
                    $sec->title      = $opt['title'];
                    $sec->description= $opt['desc'];
                    if ( !empty($opt['priority']) )	$sec->priority = $opt['priority'];
                } else {
                    $wp_customize->add_section( esc_attr($id) , array(
                        'title'      => $opt['title'],
                        'description'=> $opt['desc'],
                        'panel'  => esc_attr($panels[$p]),
                        'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i
                    ) );
                }
                array_push($sections, $id);
                $s++;

            } else if ($opt['type'] == 'section_end') {

                array_pop($sections);
                $s--;

            } else if ($opt['type'] == 'select') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                    'type'     => 'select',
                    'choices'  => $opt['options']
                ) );

            } else if ($opt['type'] == 'radio') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                    'type'     => 'radio',
                    'choices'  => $opt['options']
                ) );

            } else if ($opt['type'] == 'switch') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( new Good_Wine_Shop_Customize_Switch_Control( $wp_customize, $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority' => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                    'choices'  => $opt['options']
                ) ) );

            } else if ($opt['type'] == 'checkbox') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                    'type'     => 'checkbox'
                ) );

            } else if ($opt['type'] == 'color') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'sanitize_hex_color',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                ) ) );

            } else if ($opt['type'] == 'image') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority' => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                ) ) );

            } else if (in_array($opt['type'], array('media', 'audio', 'video'))) {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority' => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                ) ) );

            } else if ($opt['type'] == 'info') {

                $wp_customize->add_setting( $id, array(
                    'default'           => '',
                    'sanitize_callback' => 'good_wine_shop_sanitize_value',
                    'transport'         => 'postMessage'
                ) );

                $wp_customize->add_control( new Good_Wine_Shop_Customize_Info_Control( $wp_customize, $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority' => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                ) ) );

            } else if ($opt['type'] == 'hidden') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_html',
                    'transport'         => 'postMessage'
                ) );

                $wp_customize->add_control( new Good_Wine_Shop_Customize_Hidden_Control( $wp_customize, $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority' => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                ) ) );

            } else if ($opt['type'] == 'editor') {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_simple_html',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage',

                ) );

                $wp_customize->add_control( $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                    'type'        => 'textarea'
                ) );
            } else {

                $wp_customize->add_setting( $id, array(
                    'default'           => good_wine_shop_get_theme_option($id),
                    'sanitize_callback' => 'good_wine_shop_sanitize_html',
                    'transport'         => !isset($opt['refresh']) || $opt['refresh'] ? 'refresh' : 'postMessage'
                ) );

                $wp_customize->add_control( $id, array(
                    'label'    => $opt['title'],
                    'description' => $opt['desc'],
                    'section'  => esc_attr($sections[$s]),
                    'priority'	 => !empty($opt['priority']) ? $opt['priority'] : CUSTOMIZE_PRIORITY+$i,
                    'type'     => $opt['type']	//'text'
                ) );
            }

        }
    }
}


// Create custom controls for customizer
if (!function_exists('good_wine_shop_customizer_custom_controls')) {
    add_action( 'customize_register', 'good_wine_shop_customizer_custom_controls' );
    function good_wine_shop_customizer_custom_controls( $wp_customize ) {

        class Good_Wine_Shop_Customize_Info_Control extends WP_Customize_Control {
            public $type = 'info';

            public function render_content() {
                ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <span class="customize-control-description desctiption"><?php echo esc_html( $this->description ); ?></span>
                </label>
                <?php
            }
        }

        class Good_Wine_Shop_Customize_Switch_Control extends WP_Customize_Control {
            public $type = 'switch';

            public function render_content() {
                ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <span class="customize-control-description desctiption"><?php echo esc_html( $this->description ); ?></span>
                    <?php
                    if (is_array($this->choices) && count($this->choices)>0) {
                        foreach ($this->choices as $k=>$v) {
                            ?><label><input type="radio" name="_customize-radio-<?php echo esc_attr($this->id); ?>" <?php $this->link(); ?> value="<?php echo esc_attr($k); ?>">
                            <?php echo esc_html($v); ?></label><?php
                        }
                    }
                    ?>
                </label>
                <?php
            }
        }

        class Good_Wine_Shop_Customize_Hidden_Control extends WP_Customize_Control {
            public $type = 'info';

            public function render_content() {
                ?>
                <input type="hidden" name="_customize-hidden-<?php echo esc_attr($this->id); ?>" <?php $this->link(); ?> value="">
                <?php
            }
        }

    }
}


// Sanitize plain value
if (!function_exists('good_wine_shop_sanitize_value')) {
    function good_wine_shop_sanitize_value($value) {
        return empty($value) ? $value : trim(strip_tags($value));
    }
}


// Sanitize html value
if (!function_exists('good_wine_shop_sanitize_html')) {
    function good_wine_shop_sanitize_html($value) {
        return empty($value) ? $value : wp_kses_data($value);
    }
}

// Sanitize html value
if (!function_exists('good_wine_shop_sanitize_simple_html')) {
    function good_wine_shop_sanitize_simple_html($value) {
        return empty($value) ? '' : $value;
    }
}


//--------------------------------------------------------------
// Save custom settings in CSS file
//--------------------------------------------------------------

// Save CSS with custom colors and fonts after save custom options
if (!function_exists('good_wine_shop_customizer_action_save_after')) {
    add_action('customize_save_after', 'good_wine_shop_customizer_action_save_after');
    function good_wine_shop_customizer_action_save_after($api=false) {
        $settings = $api->settings();
        // Store new schemes colors
        $schemes = good_wine_shop_unserialize($settings['scheme_storage']->value());
        if (is_array($schemes) && count($schemes) > 0)
            good_wine_shop_storage_set('schemes', $schemes);
        // Regenerate CSS with new colors
        good_wine_shop_customizer_save_css();
    }
}

// Save CSS with custom colors and fonts after switch theme
if (!function_exists('good_wine_shop_customizer_action_switch_theme')) {
    
    function good_wine_shop_customizer_action_switch_theme() {
        good_wine_shop_customizer_save_css();
    }
}

// Save CSS with custom colors and fonts into custom.css
if (!function_exists('good_wine_shop_customizer_save_css')) {
    add_action('trx_addons_action_save_options', 'good_wine_shop_customizer_save_css');
    function good_wine_shop_customizer_save_css() {
        $msg = 	'/* ' . esc_html__("ATTENTION! This file was generated automatically! Don't change it!!!", 'good-wine-shop')
            . "\n----------------------------------------------------------------------- */\n";

        // Save CSS with custom colors and fonts into custom.css
        $css = good_wine_shop_customizer_get_css();
        $file = good_wine_shop_get_file_dir('css/__colors.css');
        if (file_exists($file)) good_wine_shop_fpc($file, $msg . $css );

        // Merge CSS and JS with static styles and scripts

        // Merge styles
        $css = '';
        if ( ($css = apply_filters( 'good_wine_shop_filter_merge_styles', $css )) != '')
            good_wine_shop_fpc( good_wine_shop_get_file_dir('css/__styles.css'), $msg . good_wine_shop_minify_css( $css ) );

        // Merge scripts
        $js = good_wine_shop_fgc(good_wine_shop_get_file_dir('js/skip-link-focus.js'))
            . good_wine_shop_fgc(good_wine_shop_get_file_dir('js/superfish.js'))
            . good_wine_shop_fgc(good_wine_shop_get_file_dir('js/bideo.js'))
            . good_wine_shop_fgc(good_wine_shop_get_file_dir('js/_utils.js'))
            . good_wine_shop_fgc(good_wine_shop_get_file_dir('js/_init.js'));
        if ( ($js = apply_filters( 'good_wine_shop_filter_merge_scripts', $js )) != '')
            good_wine_shop_fpc( good_wine_shop_get_file_dir('js/__scripts.js'), $msg . good_wine_shop_minify_js( $js ) );
    }
}


//--------------------------------------------------------------
// Color schemes manipulations
//--------------------------------------------------------------

// Load saved values into color schemes
if (!function_exists('good_wine_shop_load_schemes')) {
    add_action('good_wine_shop_action_load_options', 'good_wine_shop_load_schemes');
    function good_wine_shop_load_schemes() {
        $schemes = good_wine_shop_unserialize(good_wine_shop_get_theme_option('scheme_storage'));
        if (is_array($schemes) && count($schemes) > 0)
            good_wine_shop_storage_set('schemes', $schemes);
    }
}

// Return specified color from current (or specified) color scheme
if ( !function_exists( 'good_wine_shop_get_scheme_color' ) ) {
    function good_wine_shop_get_scheme_color($color_name, $scheme = '') {
        if (empty($scheme)) $scheme = good_wine_shop_get_theme_option( 'color_scheme' );
        if (empty($scheme) || good_wine_shop_storage_empty('schemes', $scheme)) $scheme = 'default';
        $colors = good_wine_shop_storage_get_array('schemes', $scheme, 'colors');
        return $colors[$color_name];
    }
}

// Return colors from current color scheme
if ( !function_exists( 'good_wine_shop_get_scheme_colors' ) ) {
    function good_wine_shop_get_scheme_colors($scheme = '') {
        if (empty($scheme)) $scheme = good_wine_shop_get_theme_option( 'color_scheme' );
        if (empty($scheme) || good_wine_shop_storage_empty('schemes', $scheme)) $scheme = 'default';
        return good_wine_shop_storage_get_array('schemes', $scheme, 'colors');
    }
}

// Return list schemes
if ( !function_exists( 'good_wine_shop_get_theme_schemes' ) ) {
    function good_wine_shop_get_theme_schemes() {
        $list = array();
        $schemes = good_wine_shop_storage_get('schemes');
        if (is_array($schemes) && count($schemes) > 0) {
            foreach ($schemes as $slug => $scheme) {
                $list[$slug] = $scheme['title'];
            }
        }
        return $list;
    }
}

// Return theme fonts settings
if ( !function_exists( 'good_wine_shop_get_theme_fonts' ) ) {
    function good_wine_shop_get_theme_fonts($tag = '') {
        return !empty($tag) && !good_wine_shop_storage_empty('theme_fonts', $tag)
            ? good_wine_shop_storage_get_array('theme_fonts', $tag)
            : good_wine_shop_storage_get('theme_fonts');
    }
}


//--------------------------------------------------------------
// Customizer JS and CSS
//--------------------------------------------------------------

// Binds JS listener to make Customizer color_scheme control.
// Passes color scheme data as colorScheme global.
if ( !function_exists( 'good_wine_shop_customizer_control_js' ) ) {
    add_action( 'customize_controls_enqueue_scripts', 'good_wine_shop_customizer_control_js' );
    function good_wine_shop_customizer_control_js() {
        wp_enqueue_style( 'good-wine-shop-customizer', good_wine_shop_get_file_url('theme-options/theme.customizer.css') );
        wp_enqueue_script( 'good-wine-shop-customizer-color-scheme-control', good_wine_shop_get_file_url('theme-options/theme.customizer.color-scheme.js'), array( 'customize-controls', 'iris', 'underscore', 'wp-util' ) );
        wp_localize_script( 'good-wine-shop-customizer-color-scheme-control', 'good_wine_shop_color_schemes', good_wine_shop_storage_get('schemes') );
        wp_localize_script( 'good-wine-shop-customizer-color-scheme-control', 'good_wine_shop_dependencies', good_wine_shop_get_theme_dependencies() );
    }
}

// Binds JS handlers to make the Customizer preview reload changes asynchronously.
if ( !function_exists( 'good_wine_shop_customizer_preview_js' ) ) {
    add_action( 'customize_preview_init', 'good_wine_shop_customizer_preview_js' );
    function good_wine_shop_customizer_preview_js() {
        wp_enqueue_script( 'good-wine-shop-customize-preview', good_wine_shop_get_file_url('theme-options/theme.customizer.preview.js'), array( 'customize-preview' ) );
    }
}

// Output an Underscore template for generating CSS for the color scheme.
// The template generates the css dynamically for instant display in the Customizer preview.
if ( !function_exists( 'good_wine_shop_customizer_css_template' ) ) {
    add_action( 'customize_controls_print_footer_scripts', 'good_wine_shop_customizer_css_template' );
    function good_wine_shop_customizer_css_template() {
        $colors = array(

            // Whole block border and background
            'bg_color'				=> '{{ data.bg_color }}',
            'bd_color'				=> '{{ data.bd_color }}',

            // Text and links colors
            'text'					=> '{{ data.text }}',
            'text_light'			=> '{{ data.text_light }}',
            'text_dark'				=> '{{ data.text_dark }}',
            'text_link'				=> '{{ data.text_link }}',
            'text_hover'			=> '{{ data.text_hover }}',

            // Alternative blocks (submenu, buttons, tabs, etc.)
            'alter_bg_color'		=> '{{ data.alter_bg_color }}',
            'alter_bg_hover'		=> '{{ data.alter_bg_hover }}',
            'alter_bd_color'		=> '{{ data.alter_bd_color }}',
            'alter_bd_hover'		=> '{{ data.alter_bd_hover }}',
            'alter_text'			=> '{{ data.alter_text }}',
            'alter_light'			=> '{{ data.alter_light }}',
            'alter_dark'			=> '{{ data.alter_dark }}',
            'alter_link'			=> '{{ data.alter_link }}',
            'alter_hover'			=> '{{ data.alter_hover }}',

            // Input fields (form's fields and textarea)
            'input_bg_color'		=> '{{ data.input_bg_color }}',
            'input_bg_hover'		=> '{{ data.input_bg_hover }}',
            'input_bd_color'		=> '{{ data.input_bd_color }}',
            'input_bd_hover'		=> '{{ data.input_bd_hover }}',
            'input_text'			=> '{{ data.input_text }}',
            'input_light'			=> '{{ data.input_light }}',
            'input_dark'			=> '{{ data.input_dark }}',

            // Inverse blocks (with background equal to the links color or one of accented colors)
            'inverse_text'			=> '{{ data.inverse_text }}',
            'inverse_light'			=> '{{ data.inverse_light }}',
            'inverse_dark'			=> '{{ data.inverse_dark }}',
            'inverse_link'			=> '{{ data.inverse_link }}',
            'inverse_hover'			=> '{{ data.inverse_hover }}',

            // Additional accented colors (if used in the current theme)
            
            'accent2'			=> '{{ data.accent2 }}',
        );

        $schemes = array_keys(good_wine_shop_get_list_schemes());
        if (count($schemes) > 0) {
            $tmpl_holder = 'script';
            foreach ($schemes as $scheme) {
                echo '<' . trim($tmpl_holder) . ' type="text/html" id="tmpl-good_wine_shop-color-scheme-'.esc_attr($scheme).'">'
                    . trim(good_wine_shop_customizer_get_css( $colors, false, false, $scheme ))
                    . '</' . trim($tmpl_holder) . '>';
            }
        }
    }
}


// Add scheme name in each selector in the CSS (priority 100 - after complete css)
if (!function_exists('good_wine_shop_customizer_add_scheme_in_css')) {
    add_action( 'good_wine_shop_filter_get_css', 'good_wine_shop_customizer_add_scheme_in_css', 100, 4 );
    function good_wine_shop_customizer_add_scheme_in_css($css, $colors, $fonts, $scheme) {
        $rez = '';
        $in_comment = $in_rule = false;
        $allow = true;
        $scheme_class = '.scheme_' . trim($scheme) . ' ';
        $self_class = '.scheme_self';
        $self_class_len = strlen($self_class);
        $css_str = str_replace(array('{{', '}}'), array('[[',']]'), $css['colors']);
        for ($i=0; $i<strlen($css_str); $i++) {
            $ch = $css_str[$i];
            if ($in_comment) {
                $rez .= $ch;
                if ($ch=='/' && $css_str[$i-1]=='*') {
                    $in_comment = false;
                    $allow = !$in_rule;
                }
            } else if ($in_rule) {
                $rez .= $ch;
                if ($ch=='}') {
                    $in_rule = false;
                    $allow = !$in_comment;
                }
            } else {
                if ($ch=='/' && $css_str[$i+1]=='*') {
                    $rez .= $ch;
                    $in_comment = true;
                } else if ($ch=='{') {
                    $rez .= $ch;
                    $in_rule = true;
                } else if ($ch==',') {
                    $rez .= $ch;
                    $allow = true;
                } else if (strpos(" \t\r\n", $ch)===false) {
                    if ($allow && substr($css_str, $i, $self_class_len) == $self_class) {
                        $rez .= trim($scheme_class);
                        $i += $self_class_len - 1;
                    } else
                        $rez .= ($allow ? $scheme_class : '') . $ch;
                    $allow = false;
                } else {
                    $rez .= $ch;
                }
            }
        }
        $rez = str_replace(array('[[',']]'), array('{{', '}}'), $rez);
        $css['colors'] = $rez;
        return $css;
    }
}




// -----------------------------------------------------------------
// -- Page Options section
// -----------------------------------------------------------------

if ( !function_exists('good_wine_shop_init_override') ) {
    add_action( 'after_setup_theme', 'good_wine_shop_init_override' );
    function good_wine_shop_init_override() {
        if ( is_admin() ) {
            add_action("admin_enqueue_scripts", 'good_wine_shop_add_override_scripts');
            add_action('save_post',			'good_wine_shop_save_override');
        }
    }
}

// Load required styles and scripts for admin mode
if ( !function_exists( 'good_wine_shop_add_override_scripts' ) ) {
    add_action("admin_enqueue_scripts", 'good_wine_shop_add_override_scripts');
    function good_wine_shop_add_override_scripts() {
        // If current screen is 'Edit Page' - load fontello
        $screen = get_current_screen();
        if (good_wine_shop_allow_override($screen->id) && good_wine_shop_allow_override($screen->post_type)) {
            wp_enqueue_style( 'fontello-icons',  good_wine_shop_get_file_url('css/fontello/fontello-embedded.css') );
            wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui'));
            wp_enqueue_script( 'good-wine-shop-override-options', good_wine_shop_get_file_url('theme-options/theme.override.js'), array('jquery') );
            wp_localize_script( 'good-wine-shop-override-options', 'good_wine_shop_dependencies', good_wine_shop_get_theme_dependencies() );
        }
    }
}


// Check if override options is allow
if (!function_exists('good_wine_shop_allow_override')) {
    function good_wine_shop_allow_override($post_type) {
        return apply_filters('good_wine_shop_filter_allow_override', in_array($post_type, array('page', 'post')), $post_type);
    }
}
// Add overriden options
if (!function_exists('good_wine_shop_options_override_add_options')) {
    add_filter('good_wine_shop_filter_override_options', 'good_wine_shop_options_override_add_options');
    function good_wine_shop_options_override_add_options($list) {
        global $post_type;
        if (good_wine_shop_allow_override($post_type)) {
            $list[] = array(sprintf('good_wine_shop_override_options_%s', $post_type),
                esc_html__('Theme Options', 'good-wine-shop'),
                'good_wine_shop_show_override',
                $post_type,
                $post_type=='post' ? 'side' : 'advanced',
                'default'
            );
        }
        return $list;
    }
}

// Callback function to show fields in override options
if (!function_exists('good_wine_shop_show_override')) {
    function good_wine_shop_show_override() {
        global $post, $post_type;
        if (good_wine_shop_allow_override($post_type)) {
            // Load saved options
            $meta = get_post_meta($post->ID, 'good_wine_shop_options', true);
            $tabs_titles = $tabs_content = array();
            $options = good_wine_shop_storage_get('options');
            foreach ($options as $k=>$v) {
                if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
                if (empty($v['override']['section']))
                    $v['override']['section'] = esc_html__('General', 'good-wine-shop');
                if (!isset($tabs_titles[$v['override']['section']])) {
                    $tabs_titles[$v['override']['section']] = $v['override']['section'];
                    $tabs_content[$v['override']['section']] = '';
                }
                $v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
                $tabs_content[$v['override']['section']] .= good_wine_shop_show_override_options_field($k, $v);
            }
            if (count($tabs_titles) > 0) {
                ?>
                <div class="good_wine_shop_override_options">
                    <input type="hidden" name="override_options_post_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
                    <input type="hidden" name="override_options_post_type" value="<?php echo esc_attr($post_type); ?>" />
                    <div id="good_wine_shop_override_options_tabs">
                        <ul><?php
                            $cnt = 0;
                            foreach ($tabs_titles as $k=>$v) {
                                $cnt++;
                                ?><li><a href="#good_wine_shop_override_options_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
                            }
                            ?></ul>
                        <?php
                        $cnt = 0;
                        foreach ($tabs_content as $k=>$v) {
                            $cnt++;
                            ?>
                            <div id="good_wine_shop_override_options_<?php echo esc_attr($cnt); ?>" class="good_wine_shop_override_options_section">
                                <?php good_wine_shop_show_layout($v); ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }
    }
}

// Display single option's field
if ( !function_exists('good_wine_shop_show_override_options_field') ) {
    function good_wine_shop_show_override_options_field($name, $field) {
        $inherit_state = good_wine_shop_is_inherit($field['val']);
        $output = '<div class="good_wine_shop_override_options_item good_wine_shop_override_options_item_'.esc_attr($field['type']).' good_wine_shop_override_options_inherit_'.($inherit_state ? 'on' : 'off' ).'">'
            . '<h4 class="good_wine_shop_override_options_item_title">'
            . esc_html($field['title'])
            . '<span class="good_wine_shop_override_options_inherit_lock" id="good_wine_shop_override_options_inherit_'.esc_attr($name).'"></span>'
            . '</h4>'
            . '<div class="good_wine_shop_override_options_item_data">'
            . '<div class="good_wine_shop_override_options_item_field" data-param="'.esc_attr($name).'">';
        if ($field['type']=='checkbox') {
            $output .= '<label class="good_wine_shop_override_options_item_label">'
                . '<input type="checkbox" name="good_wine_shop_override_options_field_'.esc_attr($name).'" value="1"'.($field['val']==1 ? ' checked="checked"' : '').' />'
                . esc_html($field['title'])
                . '</label>';
        } else if ($field['type']=='switch' || $field['type']=='radio') {
            foreach ($field['options'] as $k=>$v) {
                $output .= '<label class="good_wine_shop_override_options_item_label">'
                    . '<input type="radio" name="good_wine_shop_override_options_field_'.esc_attr($name).'" value="'.esc_attr($k).'"'.($field['val']==$k ? ' checked="checked"' : '').' />'
                    . esc_html($v)
                    . '</label>';
            }
        } else if ($field['type']=='text') {
            $output .= '<input type="text" name="good_wine_shop_override_options_field_'.esc_attr($name).'" value="'.esc_attr(good_wine_shop_is_inherit($field['val']) ? '' : $field['val']).'" />';
        } else if ($field['type']=='textarea') {
            $output .= '<textarea name="good_wine_shop_override_options_field_'.esc_attr($name).'">'.esc_html(good_wine_shop_is_inherit($field['val']) ? '' : $field['val']).'</textarea>';
        } else if ($field['type']=='select') {
            $output .= '<select size="1" name="good_wine_shop_override_options_field_'.esc_attr($name).'">';
            foreach ($field['options'] as $k=>$v) {
                $output .= '<option value="'.esc_attr($k).'"'.($field['val']==$k ? ' selected="selected"' : '').'>'.esc_html($v).'</option>';
            }
            $output .= '</select>';
        } else if (in_array($field['type'], array('image', 'media', 'video', 'audio'))) {
            $output .= '<input type="text" id="good_wine_shop_override_options_field_'.esc_attr($name).'" name="good_wine_shop_override_options_field_'.esc_attr($name).'" value="'.esc_attr(good_wine_shop_is_inherit($field['val']) ? '' : $field['val']).'" />'
                . good_wine_shop_show_custom_field('good_wine_shop_override_options_field_'.esc_attr($name).'_button', array(
                    'type'				=> 'mediamanager',
                    'data_type'			=> $field['type'],
                    'linked_field_id'	=> 'good_wine_shop_override_options_field_'.esc_attr($name)),
                    null)
                . '<div class="good_wine_shop_override_options_field_preview">'
                . (good_wine_shop_is_inherit($field['val']) ? '' : ($field['val'] && $field['type']=='image' ? '<img src="' . esc_url($field['val']) . '" alt="'.esc_attr__('Image', 'good-wine-shop').'">' : basename($field['val'])))
                . '</div>';
        }
        $output .=  	 '<div class="good_wine_shop_override_options_inherit_cover"'.(!$inherit_state ? ' style="display:none;"' : '').'>'
            . '<span class="good_wine_shop_override_options_inherit_label">' . esc_html__('Inherit', 'good-wine-shop') . '</span>'
            . '<input type="hidden" name="good_wine_shop_override_options_inherit_'.esc_attr($name).'" value="'.esc_attr($inherit_state ? 'inherit' : '').'" />'
            . '</div>'
            . '</div>'
            . '<div class="good_wine_shop_override_options_item_description">'
            . (!empty($field['override']['desc']) ? trim($field['override']['desc']) : trim($field['desc']))	// param 'desc' already processed with wp_kses()!
            . '</div>'
            . '</div>'
            . '</div>';
        return $output;
    }
}

// Save data from override options
if (!function_exists('good_wine_shop_save_override')) {
    function good_wine_shop_save_override($post_id) {

        // verify nonce
        if ( !wp_verify_nonce( good_wine_shop_get_value_gp('override_options_post_nonce'), admin_url() ) )
            return $post_id;

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $post_type = isset($_POST['override_options_post_type']) ? good_wine_shop_get_value_gpc('override_options_post_type') : good_wine_shop_get_value_gpc('post_type');

        // check permissions
        $capability = 'page';
        $post_types = get_post_types( array( 'name' => $post_type), 'objects' );
        if (!empty($post_types) && is_array($post_types)) {
            foreach ($post_types  as $type) {
                $capability = $type->capability_type;
                break;
            }
        }
        if (!current_user_can('edit_'.($capability), $post_id)) {
            return $post_id;
        }

        // Save meta
        $meta = array();
        $options = good_wine_shop_storage_get('options');
        foreach ($options as $k=>$v) {
            // Skip not overriden options
            if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
            // Skip inherited options
            if (!empty($_POST['good_wine_shop_override_options_inherit_'.trim($k)])) continue;
            // Get option value from POST
            $meta[$k] = isset($_POST['good_wine_shop_override_options_field_'.trim($k)])
                ? good_wine_shop_get_value_gpc('good_wine_shop_override_options_field_'.trim($k))
                : ($v['type']=='checkbox' ? 0 : '');
        }
        update_post_meta($post_id, 'good_wine_shop_options', $meta);
    }
}


//--------------------------------------------------------------
//-- Load Options list and styles
//--------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'theme-options/theme.options.php';
require_once trailingslashit( get_template_directory() ) . 'theme-options/theme.styles.php';
?>