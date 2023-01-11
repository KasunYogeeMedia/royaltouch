<?php
/**
 * Plugin's options
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Load current values for each customizable option
if ( !function_exists('trx_addons_load_options') ) {
	function trx_addons_load_options() {
		global $TRX_ADDONS_STORAGE;
		$options = get_option('trx_addons_options');
		if (isset($TRX_ADDONS_STORAGE['options']) && is_array($TRX_ADDONS_STORAGE['options']) && count($TRX_ADDONS_STORAGE['options']) > 0) {
			foreach ($TRX_ADDONS_STORAGE['options'] as $k=>$v) {
				if (isset($v['std'])) {
					$val = isset($_GET[$k]) 
								? $_GET[$k] 
								: (isset($options[$k])
									? $options[$k]
									: $v['std']
								);
					if (is_array($v['std'])) {
						foreach ($v['std'] as $k1=>$v1) {
							if (!isset($val[$k1])) $val[$k1] = $v1;
						}
						foreach ($val as $k1=>$v1) {
							if (!isset($v['std'][$k1])) unset($val[$k1]);
						}
					}
					$TRX_ADDONS_STORAGE['options'][$k]['val'] = $val;
				}
			}
		}
	}
}


// Return customizable option value
if (!function_exists('trx_addons_get_option')) {
	function trx_addons_get_option($name, $defa='', $strict_mode=true) {
		global $TRX_ADDONS_STORAGE;
		$rez = $defa;
		$part = '';
		if (strpos($name, '[')!==false) {
			$tmp = explode('[', $name);
			$name = $tmp[0];
			$part = substr($tmp[1], 0, -1);
		}
		if ( !isset($TRX_ADDONS_STORAGE['options'][$name]) && $strict_mode ) {
			$s = debug_backtrace();
			//array_shift($s);
			$s = array_shift($s);
			echo '<pre>';
			echo esc_html(sprintf(__('Undefined option "%s" called from:', 'trx_addons'), $name));
			if (function_exists('trx_addons_debug_dump_screen')) 
				trx_addons_debug_dump_screen($s);
			else
				print_r($s);
			echo '</pre>';
			die();
		}
		// Override option from GET
		if (isset($_GET[$name])) {
			if (empty($part))
				$rez = $_GET[$name];
			else if (isset($_GET[$name][$part]))
				$rez = $_GET[$name][$part];
		// Get saved option value
		} else if (isset($TRX_ADDONS_STORAGE['options'][$name]['val'])) {
			if (empty($part))
				$rez = $TRX_ADDONS_STORAGE['options'][$name]['val'];
			else if (isset($TRX_ADDONS_STORAGE['options'][$name]['val'][$part]))
				$rez = $TRX_ADDONS_STORAGE['options'][$name]['val'][$part];
		}
		return $rez;
	}
}

// Get dependencies list from the Plugin's Options
if ( !function_exists('trx_addons_get_options_dependencies') ) {
	function trx_addons_get_options_dependencies($options=null) {
		global $TRX_ADDONS_STORAGE;
		if (!$options) $options = $TRX_ADDONS_STORAGE['options'];
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}

// Return internal setting value
if (!function_exists('trx_addons_get_setting')) {
    function trx_addons_get_setting($name) {
        global $TRX_ADDONS_STORAGE;
        // If options are loaded and specified name is not exists and 'strict_mode' is on - display warning message
        // and dump call's stack
        if ( !isset($TRX_ADDONS_STORAGE['settings'][$name]) ) {
            $s = debug_backtrace();
            // This way display all stack
            //array_shift($s);
            // This way display only last call
            $s = array_shift($s);
            echo '<pre>';
            echo esc_html(sprintf(__('Undefined setting "%s" called from:', 'trx_addons'), $name));
            if (function_exists('trx_addons_debug_dump_screen'))
                trx_addons_debug_dump_screen($s);
            else
                print_r($s);
            echo '</pre>';
            die();
        } else
            return $TRX_ADDONS_STORAGE['settings'][$name];
    }
}


// -----------------------------------------------------------------
// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// -- Internal theme settings
// -----------------------------------------------------------------


// Internal plugin settings - user can't change it
if (!function_exists('trx_addons_init_settings')) {
    add_action( 'after_setup_theme', 'trx_addons_init_settings', 2 );
    add_action( 'trx_addons_action_save_options', 'trx_addons_init_settings', 2 );
    function trx_addons_init_settings() {
        static $loaded = false;
        if ($loaded) return;
        $loaded = true;
        global $TRX_ADDONS_STORAGE;
        $TRX_ADDONS_STORAGE['settings'] = apply_filters('trx_addons_init_settings', array(
            //Type of socials icons: images|icons - Use images or icons as pictograms of the social networks
            'socials_type' => 'icons',
            //Type of other icons: images|icons - Use images or icons as pictograms in other shortcodes (not socials)
            'icons_type' => 'icons',
            //Type of icons selector: vc|internal - Use standard VC parameters for icons or use internal popup with theme icons
            'icons_selector' => 'vc',
            // Prevent simultaneous editing of posts for Gutenberg and other PageBuilders (VC, Elementor)
            'disable_gutenberg_on_other_pagebuilders' => true,
        ));
    }
}


$TRX_ADDONS_STORAGE['options'] = array();

// Section 'General' - main options
$TRX_ADDONS_STORAGE['options']['general_section'] = array(
		"title" => esc_html__('General', 'trx_addons'),
		"desc" => wp_kses_data( __('General options', 'trx_addons') ),
		"type" => "section"
		);
$TRX_ADDONS_STORAGE['options']['debug_mode'] = array(
		"title" => esc_html__('Debug mode', 'trx_addons'),
		"desc" => wp_kses_data( __('Enable debug functions and theme profiler output', 'trx_addons') ),
		"std" => "0",
		"type" => "checkbox"
		);
$TRX_ADDONS_STORAGE['options']['disable_widgets_block_editor'] = array(
	"title" => esc_html__('Disable new Widgets Block Editor', 'trx_addons'),
	"desc" => wp_kses_data( __('Attention! If after the update to WordPress 5.8+ you are having trouble editing widgets or working in Customizer - disable new Widgets Block Editor (used in WordPress 5.8+ instead of a classic widgets panel)', 'trx_addons') ),
	"std" => "0",
	"type" => "checkbox"
	);
$TRX_ADDONS_STORAGE['options']['retina_ready'] = array(
		"title" => esc_html__('Image dimensions', 'trx_addons'),
		"desc" => wp_kses_data( __('Which dimensions will be used for the uploaded images: "Original" or "Retina ready" (twice enlarged)', 'trx_addons') ),
		"std" => "1",
		"size" => "medium",
		"options" => array(
			"1" => esc_html__("Original", 'trx_addons'), 
			"2" => esc_html__("Retina", 'trx_addons')
			),
		"type" => "switch"
		);
$TRX_ADDONS_STORAGE['options']['images_quality'] = array(
		"title" => esc_html__('Quality for cropped images', 'trx_addons'),
		"desc" => wp_kses_data( __('Quality (1-100) to save cropped images. Attention! After change the image quality, you need to regenerate all thumbnails!', 'trx_addons') ),
		"std" => 60,
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['page_preloader'] = array(
		"title" => esc_html__("Show page preloader", 'trx_addons'),
		"desc" => wp_kses_data( __("Select one of predefined styles for the page preloader or upload preloader image", 'trx_addons') ),
		"std" => "none",
		"options" => array(
			'none'   => esc_html__('Hide preloader', 'trx_addons'),
			'circle' => esc_html__('Circle', 'trx_addons'),
			'square' => esc_html__('Square', 'trx_addons'),
			'custom' => esc_html__('Custom', 'trx_addons')
			),
		"type" => "select",
		);
$TRX_ADDONS_STORAGE['options']['page_preloader_image'] = array(
		"title" => esc_html__('Page preloader image',  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload page preloader image for your site. If empty - site not using preloader',  'trx_addons') ),
		"dependency" => array(
			"page_preloader" => array('custom')
		),
		"std" => "",
		"type" => "image"
		);
$TRX_ADDONS_STORAGE['options']['page_preloader_bg_color'] = array(
		"title" => esc_html__('Page preloader bg color',  'trx_addons'),
		"desc" => wp_kses_data( __('Select background color for the page preloader. If empty - not use background color',  'trx_addons') ),
		"std" => "#ffffff",
		"type" => "color"
		);
$TRX_ADDONS_STORAGE['options']['scroll_to_top'] = array(
		"title" => esc_html__('Add "Scroll to Top"', 'trx_addons'),
		"desc" => wp_kses_data( __('Add "Scroll to Top" button when page is scrolled down', 'trx_addons') ),
		"std" => "1",
		"type" => "checkbox"
		);
$TRX_ADDONS_TMP = array( "none" => esc_html__("None", 'trx_addons'));
if (trx_addons_get_file_dir('js/magnific/jquery.magnific-popup.min.js')) $TRX_ADDONS_TMP['magnific'] = esc_html__("Magnific Popup", 'trx_addons');
if (trx_addons_get_file_dir('js/prettyPhoto/jquery.prettyPhoto.min.js')) $TRX_ADDONS_TMP['pretty'] = esc_html__("Pretty Photo", 'trx_addons');
if (count($TRX_ADDONS_TMP) > 1) {
	$TRX_ADDONS_STORAGE['options']['popup_engine'] = array(
		"title" => esc_html__('Popup Engine', 'trx_addons'),
		"desc" => wp_kses_data( __('Select script to show popup windows with images and any other html code', 'trx_addons') ),
		"std" => "magnific",
		"options" => $TRX_ADDONS_TMP,
		"type" => "radio"
		);
}



$TRX_ADDONS_STORAGE['options']['login_info'] = array(
    "title" => esc_html__('Login and Registration',  'trx_addons'),
    "desc" => wp_kses_data( __("Specify parameters of the User's Login and Registration",  'trx_addons') ),
    "type" => "info"
);
$TRX_ADDONS_STORAGE['options']['login_via_ajax'] = array(
    "title" => esc_html__('Login via AJAX', 'trx_addons'),
    "desc" => wp_kses_data( __('Login via AJAX or use direct link on the WP Login page. Uncheck it if you have problem with any login plugin.', 'trx_addons') ),
    "std" => "1",
    "type" => "checkbox"
);
$TRX_ADDONS_STORAGE['options']['login_via_socials'] = array(
    "title" => esc_html__('Login via social profiles',  'trx_addons'),
    "desc" => wp_kses_data( __('Specify shortcode from your Social Login Plugin or any HTML/JS code to make Social Login section',  'trx_addons') ),
    "std" => "",
    "type" => "textarea"
);
$TRX_ADDONS_STORAGE['options']['notify_about_new_registration'] = array(
    "title" => esc_html__('Notify about new registration',  'trx_addons'),
    "desc" => wp_kses_data( __("Send E-mail with a new registration data to the site admin e-mail and/or to the new user's e-mail",  'trx_addons') ),
    "std" => "no",
    "options" => array(
        'no'    => esc_html__('No', 'trx_addons'),
        'both'  => esc_html__('Both', 'trx_addons'),
        'admin' => esc_html__('Admin', 'trx_addons'),
        'user'  => esc_html__('User', 'trx_addons')
    ),
    "type" => "radio" //radio
);


if (trx_addons_get_file_dir('api/less/less.php')) {		
	$TRX_ADDONS_STORAGE['options']['less_compiler'] = array(
		"title" => esc_html__('Less Compiler', 'trx_addons'),
		"desc" => wp_kses_data( __('Select Less Compiler or disable Less', 'trx_addons') ),
		"std" => "none",
		"options" => array(
			"none" => esc_html__("None", 'trx_addons'),
			"less" => esc_html__("Less", 'trx_addons'), 
			"lessc" => esc_html__("Lessc", 'trx_addons')
			),
		"type" => "radio"
		);
	$TRX_ADDONS_STORAGE['options']['less_map'] = array(
		"title" => esc_html__('Generate .map', 'trx_addons'),
		"desc" => wp_kses_data( __('Generate map for .less files. Attention! Enable this option increase memory and time consumption when compiling .less files.', 'trx_addons') ),
		"std" => "none",
		"dependency" => array(
			"strict" => true,
			"less_compiler" => array("less")
		),
		"options" => array(
			"none" => esc_html__("None", 'trx_addons'),
			"internal" => esc_html__("Internal", 'trx_addons'), 
			"external" => esc_html__("External", 'trx_addons'), 
			),
		"type" => "radio"
		);
}



// Section 'API Keys'
$TRX_ADDONS_STORAGE['options']['api_section'] = array(
		"title" => esc_html__('API', 'trx_addons'),
		"desc" => wp_kses_data( __("API Keys for some Web-services", 'trx_addons') ),
		"type" => "section"
		);
$TRX_ADDONS_STORAGE['options']['api_info'] = array(
		"title" => esc_html__('Google API Key', 'trx_addons'),
		"desc" => wp_kses_data( __("Google API Key to access Google map services", 'trx_addons') ),
		"type" => "info"
		);
$TRX_ADDONS_STORAGE['options']['api_google'] = array(
		"title" => esc_html__('Google API Key', 'trx_addons'),
		"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above", 'trx_addons') ),
		"std" => "",
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['api_google_load'] = array(
	"title" => esc_html__('Load Google API script', 'trx_addons'),
	"desc" => wp_kses_data( __("Uncheck this field to disable loading Google API script if it loaded by another plugin", 'trx_addons') ),
	"std" => "1",
	"type" => "checkbox"
);



// Section 'Socials and Share'
$TRX_ADDONS_STORAGE['options']['socials_section'] = array(
		"title" => esc_html__('Socials', 'trx_addons'),
		"desc" => wp_kses_data( __("Links to the social profiles and post's share settings", 'trx_addons') ),
		"type" => "section"
		);
$TRX_ADDONS_STORAGE['options']['socials_info'] = array(
		"title" => esc_html__('Links to your social profiles', 'trx_addons'),
		"desc" => wp_kses_data( __("Links to your favorites social networks", 'trx_addons') ),
		"type" => "info"
		);
$TRX_ADDONS_STORAGE['options']['socials_instagram'] = array(
    "title" => esc_html__('Instagram', 'trx_addons'),
    "desc" => wp_kses_data( __("Link to your profile in the Instagram", 'trx_addons') ),
    "std" => "",
    "type" => "text"
);
$TRX_ADDONS_STORAGE['options']['socials_twitter'] = array(
		"title" => esc_html__('Twitter', 'trx_addons'),
		"desc" => wp_kses_data( __("Link to your profile in the Twitter", 'trx_addons') ),
		"std" => "",
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['socials_facebook'] = array(
		"title" => esc_html__('Facebook', 'trx_addons'),
		"desc" => wp_kses_data( __("Link to your profile in the Facebook", 'trx_addons') ),
		"std" => "",
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['socials_tumblr'] = array(
		"title" => esc_html__('Tumblr', 'trx_addons'),
		"desc" => wp_kses_data( __("Link to your profile in the Tumblr", 'trx_addons') ),
		"std" => "",
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['socials_dribbble'] = array(
		"title" => esc_html__('Dribbble', 'trx_addons'),
		"desc" => wp_kses_data( __("Link to your profile in the Dribbble", 'trx_addons') ),
		"std" => "",
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['socials_pinterest'] = array(
    "title" => esc_html__('Pinterest', 'trx_addons'),
    "desc" => wp_kses_data( __("Link to your profile in the Pinterest", 'trx_addons') ),
    "std" => "",
    "type" => "text"
);
$TRX_ADDONS_STORAGE['options']['share_info'] = array(
		"title" => esc_html__('URL to share posts', 'trx_addons'),
		"desc" => wp_kses_data( __("Specify URLs to share your posts in the social networks. If empty - no share post in this social network", 'trx_addons') ),
		"type" => "info"
		);
$TRX_ADDONS_STORAGE['options']['share_twitter'] = array(
		"title" => esc_html__('Twitter', 'trx_addons'),
		"desc" => wp_kses_data( __("URL to share your posts in the Twitter", 'trx_addons') ),
		"std" => trx_addons_get_share_url('twitter'),
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['share_facebook'] = array(
		"title" => esc_html__('Facebook', 'trx_addons'),
		"desc" => wp_kses_data( __("URL to share your posts in the Facebook", 'trx_addons') ),
		"std" => trx_addons_get_share_url('facebook'),
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['share_tumblr'] = array(
		"title" => esc_html__('Tumblr', 'trx_addons'),
		"desc" => wp_kses_data( __("URL to share your posts in the Tumblr", 'trx_addons') ),
		"std" => trx_addons_get_share_url('tumblr'),
		"type" => "text"
		);
$TRX_ADDONS_STORAGE['options']['share_pinterest'] = array(
    "title" => esc_html__('Pinterest', 'trx_addons'),
    "desc" => wp_kses_data( __("URL to share your posts in the Pinterest", 'trx_addons') ),
    "std" => trx_addons_get_share_url('pinterest'),
    "type" => "text"
);
$TRX_ADDONS_STORAGE['options']['share_mail'] = array(
		"title" => esc_html__('E-mail', 'trx_addons'),
		"desc" => wp_kses_data( __("URL to share your posts via E-mail", 'trx_addons') ),
		"std" => trx_addons_get_share_url('email'),
		"type" => "text"
		);



// Section 'Shortcodes'
$TRX_ADDONS_STORAGE['options']['sc_section'] = array(
		"title" => esc_html__('Shortcodes', 'trx_addons'),
		"desc" => wp_kses_data( __("Shortcodes settings", 'trx_addons') ),
		"type" => "section"
		);
$TRX_ADDONS_STORAGE['options']['sc_anchor_info'] = array(
		"title" => esc_html__('Anchor', 'trx_addons'),
		"desc" => wp_kses_data( __("Settings of the 'Anchor' shortcode", 'trx_addons') ),
		"type" => "info"
		);
$TRX_ADDONS_STORAGE['options']['scroll_to_anchor'] = array(
		"title" => esc_html__('Scroll to Anchor', 'trx_addons'),
		"desc" => wp_kses_data( __('Scroll to Prev/Next anchor on mouse wheel', 'trx_addons') ),
		"std" => "1",
		"type" => "checkbox"
		);
$TRX_ADDONS_STORAGE['options']['update_location_from_anchor'] = array(
		"title" => esc_html__('Update location from Anchor', 'trx_addons'),
		"desc" => wp_kses_data( __("Update browser location bar form the anchor's href when page is scrolling", 'trx_addons') ),
		"std" => "0",
		"type" => "checkbox"
		);


// Section 'CV Card' - on/off CV functionality
if (trx_addons_get_file_dir('cv/cv.php')) {		

	// Contacts - address, phone, email, etc.
	$TRX_ADDONS_STORAGE['options']['contacts_section'] = array(
		"title" => esc_html__('Contacts', 'trx_addons'),
		"desc" => wp_kses_data( __('Address, phone, email, etc.', 'trx_addons') ),
		"type" => "section"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_name'] = array(
		"title" => esc_html__("Name", 'trx_addons'),
		"desc" => wp_kses_data( __("Specify your name for the printed version of Resume", 'trx_addons') ),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_position'] = array(
		"title" => esc_html__("Position", 'trx_addons'),
		"desc" => wp_kses_data( __("Specify your position for the printed version of Resume", 'trx_addons') ),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_photo'] = array(
		"title" => esc_html__('Photo',  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload your photo for the printed version of Resume',  'trx_addons') ),
		"std" => "",
		"type" => "image"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_address'] = array(
		"title" => esc_html__("Address", 'trx_addons'),
		"desc" => wp_kses_data( __("Enter your post address", 'trx_addons') ),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_email'] = array(
		"title" => esc_html__("E-mail", 'trx_addons'),
		"desc" => wp_kses_data( __("Enter your e-mail address", 'trx_addons') ),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_phone'] = array(
		"title" => esc_html__("Phone", 'trx_addons'),
		"desc" => wp_kses_data( __("Enter your phone number", 'trx_addons') ),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['contacts_description'] = array(
		"title" => esc_html__("About me", 'trx_addons'),
		"desc" => wp_kses_data( __("Short description about site owner (for the printed version of Resume)", 'trx_addons') ),
		"std" => '',
		"type" => "textarea"
		);

	// CV Card settings
	$TRX_ADDONS_STORAGE['options']['cv_section'] = array(
		"title" => esc_html__('CV Card', 'trx_addons'),
		"desc" => wp_kses_data( __('CV Card settings', 'trx_addons') ),
		"type" => "section"
		);
	$TRX_ADDONS_STORAGE['options']['cv_info'] = array(
		"title" => esc_html__('General Settings', 'trx_addons'),
		"desc" => wp_kses_data( __('General settings of the CV Card - enable/disable CV functionality, sections order, images for the CV/Blog navigation, etc.', 'trx_addons') ),
		"type" => "info"
		);
	$TRX_ADDONS_STORAGE['options']['cv_enable'] = array(
		"title" => esc_html__('Enable CV Card', 'trx_addons'),
		"desc" => wp_kses_data( __('Enable CV Card functionality on this site', 'trx_addons') ),
		"std" => "0",
		"type" => "checkbox"
		);
	$TRX_ADDONS_STORAGE['options']['cv_home'] = array(
		"title" => esc_html__('Use CV Card as homepage', 'trx_addons'),
		"desc" => wp_kses_data( __('Use CV Card as homepage of your site', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "0",
		"type" => "checkbox"
		);
	$TRX_ADDONS_STORAGE['options']['cv_hide_blog'] = array(
		"title" => esc_html__('Hide blog', 'trx_addons'),
		"desc" => wp_kses_data( __('Hide blog and use CV Card as your main site', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_home" => array(1)
		),
		"std" => "0",
		"type" => "checkbox"
		);
	$TRX_ADDONS_STORAGE['options']['cv_use_splash'] = array(
		"title" => esc_html__('Use splash', 'trx_addons'),
		"desc" => wp_kses_data( __('Show the Splash screen on first visit to the site', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_home" => array(1),
			"cv_hide_blog" => array(0)
		),
		"std" => "0",
		"type" => "checkbox"
		);
	// CV Card parts: About, Resume, Portfolio, Testimonials, Certificates, Contacts
	$std = $opt = array();
	if (trx_addons_get_file_dir('cv/includes/cv.about.php')) {
		$std['about'] = 1;
		$opt['about'] = esc_html__("About Me", 'trx_addons');
	}
	if (trx_addons_get_file_dir('cv/includes/cv.resume.php')) {
		$std['resume'] = 1;
		$opt['resume'] = esc_html__("Resume", 'trx_addons');
	}
	if (trx_addons_get_file_dir('cv/includes/cv.portfolio.php')) {
		$std['portfolio'] = 1;
		$opt['portfolio'] = esc_html__("Portfolio", 'trx_addons');
	}
	if (trx_addons_get_file_dir('cv/includes/cv.testimonials.php')) {
		$std['testimonials'] = 1;
		$opt['testimonials'] = esc_html__("Testimonials", 'trx_addons');
	}
	if (trx_addons_get_file_dir('cv/includes/cv.certificates.php')) {
		$std['certificates'] = 1;
		$opt['certificates'] = esc_html__("Certificates", 'trx_addons');
	}
	$std['contacts'] = 1;
	$opt['contacts'] = esc_html__("Contacts", 'trx_addons');
	$TRX_ADDONS_STORAGE['options']['cv_parts'] = array(
		"title" => esc_html__('Sections', 'trx_addons'),
		"desc" => wp_kses_data( __('Select available sections of the CV Card. Drag items to change their order.', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"dir" => 'vertical',
		"sortable" => true,
		"std" => $std,
		"options" => $opt,
		"type" => "checklist"
		);
	$TRX_ADDONS_STORAGE['options']['cv_ajax_loader'] = array(
		"title" => esc_html__('Use AJAX loader', 'trx_addons'),
		"desc" => wp_kses_data( __('Use AJAX to load inactive tabs content', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "0",
		"type" => "checkbox"
		);
	$TRX_ADDONS_STORAGE['options']['cv_navigation'] = array(
		"title" => esc_html__('Navigation', 'trx_addons'),
		"desc" => wp_kses_data( __('Select style of the navigation between CV sections', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "accordion",
		"options" => array(
			"accordion" => esc_html__("Accordion", 'trx_addons'),
			"buttons" => esc_html__("Buttons", 'trx_addons')
			),
		"type" => "radio"
		);
	$TRX_ADDONS_STORAGE['options']['cv_button_blog'] = array(
		"title" => esc_html__('Small button "Blog"',  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload image for the small button "Blog". If empty - use default image',  'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "",
		"type" => "image"
		);
	$TRX_ADDONS_STORAGE['options']['cv_button_cv'] = array(
		"title" => esc_html__('Small button "VCard"',  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload image for the small button "VCard". If empty - use default image',  'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "",
		"type" => "image"
		);
	$TRX_ADDONS_STORAGE['options']['cv_button_blog2'] = array(
		"title" => esc_html__('Splash button "Blog"',  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload image for the large button "Blog", used on the Spalsh screen. If empty - use default image',  'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_home" => array(1),
			"cv_hide_blog" => array(0),
			"cv_use_splash" => array(1)
		),
		"std" => "",
		"type" => "image"
		);
	$TRX_ADDONS_STORAGE['options']['cv_button_cv2'] = array(
		"title" => esc_html__('Splash button "VCard"',  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload image for the large button "VCard", used on the Spalsh screen. If empty - use default image',  'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_home" => array(1),
			"cv_hide_blog" => array(0),
			"cv_use_splash" => array(1)
		),
		"std" => "",
		"type" => "image"
		);

	$TRX_ADDONS_STORAGE['options']['cv_header_info'] = array(
		"title" => esc_html__('Header Settings', 'trx_addons'),
		"desc" => wp_kses_data( __('Header settings - image/photo, socials and typography', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"type" => "info"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_narrow'] = array(
		"title" => esc_html__('Header narrow', 'trx_addons'),
		"desc" => wp_kses_data( __("Use narrow header or leave same width for the header and content", 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => '0',
		"type" => "checkbox"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_tint'] = array(
		"title" => esc_html__('Header bg tint', 'trx_addons'),
		"desc" => wp_kses_data( __('Select main tint of the CV Header background', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "light",
		"options" => array(
			"light" => esc_html__("Light", 'trx_addons'), 
			"dark" => esc_html__("Dark", 'trx_addons')
			),
		"type" => "radio"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_image'] = array(
		"title" => esc_html__("Header image",  'trx_addons'),
		"desc" => wp_kses_data( __('Select or upload image for the CV Header area',  'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => "",
		"type" => "image"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_image_style'] = array(
		"title" => esc_html__('Header image style', 'trx_addons'),
		"desc" => wp_kses_data( __('Select style of the header image: boxed - small image with border, fit - image fit to the header area, cover - image cover whole header area', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_header_image" => array('not_empty')
		),
		"std" => "fit",
		"options" => array(
			"cover" => esc_html__("Cover", 'trx_addons'), 
			"fit" => esc_html__("Fit", 'trx_addons'), 
			"boxed" => esc_html__("Boxed", 'trx_addons')
			),
		"type" => "radio"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_letter'] = array(
		"title" => esc_html__("Header letter", 'trx_addons'),
		"desc" => wp_kses_data( __("Specify letter to overlap photo", 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_text'] = array(
		"title" => esc_html__("Text in the Header", 'trx_addons'),
		"desc" => wp_kses_data( __("Specify text to display in the Header. If empty - use site name (title)", 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => '',
		"type" => "text"
		);
	$TRX_ADDONS_STORAGE['options']['cv_header_socials'] = array(
		"title" => esc_html__('Social icons', 'trx_addons'),
		"desc" => wp_kses_data( __("Show links to your favorites social networks in the header area", 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1)
		),
		"std" => '1',
		"type" => "checkbox"
		);

	if (trx_addons_get_file_dir('cv/includes/cv.about.php')) {
		$TRX_ADDONS_STORAGE['options']['cv_about_info'] = array(
			"title" => esc_html__('About Me Section', 'trx_addons'),
			"desc" => wp_kses_data( __('Select the page that contains information about you', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[about]" => array(1)
			),
			"type" => "info"
			);
		$TRX_ADDONS_STORAGE['options']['cv_about_title'] = array(
			"title" => esc_html__("Section's title", 'trx_addons'),
			"desc" => wp_kses_data( __("Section's title for this page", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[about]" => array(1)
			),
			"std" => esc_html__('About', 'trx_addons'),
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_about_page'] = array(
			"title" => esc_html__('Page About Me', 'trx_addons'),
			"desc" => wp_kses_data( __('Select the page that contains information about you', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[about]" => array(1)
			),
			"std" => '',
			"options" => trx_addons_get_list_pages(),
			"type" => "select"
			);
	}
	
	if (trx_addons_get_file_dir('cv/includes/cv.resume.php')) {
		$TRX_ADDONS_STORAGE['options']['cv_resume_info'] = array(
			"title" => esc_html__('Resume Section', 'trx_addons'),
			"desc" => wp_kses_data( __('How many posts to be displayed in this section, columns number, use slider, etc.', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[resume]" => array(1)
			),
			"type" => "info"
			);
		$TRX_ADDONS_STORAGE['options']['cv_resume_title'] = array(
			"title" => esc_html__("Section's title", 'trx_addons'),
			"desc" => wp_kses_data( __("Resume section's title", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[resume]" => array(1)
			),
			"std" => esc_html__('Resume', 'trx_addons'),
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['cv_resume_types'] = array(
													'skills' => esc_html__('Skills', 'trx_addons'),
													'work' => esc_html__('Work experience', 'trx_addons'),
													'education' => esc_html__('Education', 'trx_addons'),
													'services' => esc_html__('Services', 'trx_addons')
													);
		$TRX_ADDONS_STORAGE['options']['cv_resume_parts'] = array(
			"title" => esc_html__('Resume parts', 'trx_addons'),
			"desc" => wp_kses_data( __('Select available parts of the Resume section. Drag items to change their order.', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[resume]" => array(1)
			),
			"dir" => 'vertical',
			"sortable" => true,
			"std" => array( 'skills' => 1, 'work' => 1, 'education' => 1, 'services' => 1 ),
			"options" => $TRX_ADDONS_STORAGE['cv_resume_types'],
			"type" => "checklist"
			);
		$TRX_ADDONS_STORAGE['options']['cv_resume_print_full'] = array(
			"title" => esc_html__('Print full version', 'trx_addons'),
			"desc" => wp_kses_data( __("Print whole resume item's content (full version) or only excerpt (short version)", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[resume]" => array(1)
			),
			"std" => '0',
			"type" => "checkbox"
			);
		foreach ($TRX_ADDONS_STORAGE['cv_resume_types'] as $slug => $name) {
			$TRX_ADDONS_STORAGE['options']['cv_resume_panel_'.$slug] = array(
				"title" => esc_html($name),
				"desc" => wp_kses_data( __('How many posts to be displayed in this section, columns number, use slider, etc.', 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1)
				),
				"type" => "panel"
				);
			$TRX_ADDONS_STORAGE['options']['cv_resume_count_'.$slug] = array(
				"title" => esc_html__("Items number", 'trx_addons'),
				"desc" => wp_kses_data( __("How many items to be displayed?", 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1)
				),
				"std" => '4',
				"type" => "text"
				);
			$TRX_ADDONS_STORAGE['options']['cv_resume_columns_'.$slug] = array(
				"title" => esc_html__('Columns number', 'trx_addons'),
				"desc" => wp_kses_data( __("How many columns to use for displaying items?", 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1)
				),
				"std" => '2',
				"type" => "text"
				);
			$TRX_ADDONS_STORAGE['options']['cv_resume_slider_'.$slug] = array(
				"title" => esc_html__('Use Slider', 'trx_addons'),
				"desc" => wp_kses_data( __("Do you want to use Slider to show items?", 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1)
				),
				"std" => '0',
				"type" => "checkbox"
				);
			$TRX_ADDONS_STORAGE['options']['cv_resume_slides_space_'.$slug] = array(
				"title" => esc_html__('Space between slides', 'trx_addons'),
				"desc" => wp_kses_data( __("Specify space between slides (in pixels)", 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1),
					"cv_resume_slider_".$slug => array(1)
				),
				"std" => '30',
				"type" => "text"
				);
			$TRX_ADDONS_STORAGE['options']['cv_resume_narrow_'.$slug] = array(
				"title" => esc_html__('Narrow', 'trx_addons'),
				"desc" => wp_kses_data( __("Use narrow area to show items in this section", 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1)
				),
				"std" => '0',
				"type" => "checkbox"
				);
			$TRX_ADDONS_STORAGE['options']['cv_resume_delimiter_'.$slug] = array(
				"title" => esc_html__('Delimiter', 'trx_addons'),
				"desc" => wp_kses_data( __("Show delimiter between items of this section", 'trx_addons') ),
				"dependency" => array(
					"cv_enable" => array(1),
					"cv_parts[resume]" => array(1)
				),
				"std" => '0',
				"type" => "checkbox"
				);
		}
		$TRX_ADDONS_STORAGE['options']['cv_resume_panel_end'] = array(
			"type" => "panel_end"
			);
	}

	if (trx_addons_get_file_dir('cv/includes/cv.portfolio.php')) {
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_info'] = array(
			"title" => esc_html__('Portfolio Section', 'trx_addons'),
			"desc" => wp_kses_data( __('How many posts to be displayed in this section, columns number, use slider, etc.', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1)
			),
			"type" => "info"
			);
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_title'] = array(
			"title" => esc_html__("Section's title", 'trx_addons'),
			"desc" => wp_kses_data( __("Portfolio section's title", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1)
			),
			"std" => esc_html__('Portfolio', 'trx_addons'),
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_style'] = array(
			"title" => esc_html__('Style', 'trx_addons'),
			"desc" => wp_kses_data( __('Select output style for the Portfolio items', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1)
			),
			"std" => "1",
			"options" => array(
				"1" => esc_html__("Style 1", 'trx_addons'),
				"2" => esc_html__("Style 2", 'trx_addons'),
				"3" => esc_html__("Style 3", 'trx_addons')
				),
			"type" => "radio"
			);
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_count'] = array(
			"title" => esc_html__("Items number", 'trx_addons'),
			"desc" => wp_kses_data( __("How many items to be displayed?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1)
			),
			"std" => '8',
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_columns'] = array(
			"title" => esc_html__('Columns number', 'trx_addons'),
			"desc" => wp_kses_data( __("How many columns to use for displaying items?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1)
			),
			"std" => '4',
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_slider'] = array(
			"title" => esc_html__('Use Slider', 'trx_addons'),
			"desc" => wp_kses_data( __("Do you want to use Slider to show items?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1),
				"cv_portfolio_style" => array(1,2)
			),
			"std" => '0',
			"type" => "checkbox"
			);
		$TRX_ADDONS_STORAGE['options']['cv_portfolio_slides_space'] = array(
			"title" => esc_html__('Space between slides', 'trx_addons'),
			"desc" => wp_kses_data( __("Specify space between slides (in pixels)", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[portfolio]" => array(1),
				"cv_portfolio_style" => array(1,2),
				"cv_portfolio_slider" => array(1)
			),
			"std" => '30',
			"type" => "text"
			);
	}
	
	if (trx_addons_get_file_dir('cv/includes/cv.testimonials.php')) {
		$TRX_ADDONS_STORAGE['options']['cv_testimonials_info'] = array(
			"title" => esc_html__('Testimonials Section', 'trx_addons'),
			"desc" => wp_kses_data( __('How many posts will be displayed in this section, columns number, use slider, etc.', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[testimonials]" => array(1)
			),
			"type" => "info"
			);
		$TRX_ADDONS_STORAGE['options']['cv_testimonials_title'] = array(
			"title" => esc_html__("Section's title", 'trx_addons'),
			"desc" => wp_kses_data( __("Testimonials section's title", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[testimonials]" => array(1)
			),
			"std" => esc_html__('Testimonials', 'trx_addons'),
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_testimonials_count'] = array(
			"title" => esc_html__("Items number", 'trx_addons'),
			"desc" => wp_kses_data( __("How many items to be displayed?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[testimonials]" => array(1)
			),
			"std" => '6',
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_testimonials_columns'] = array(
			"title" => esc_html__('Columns number', 'trx_addons'),
			"desc" => wp_kses_data( __("How many columns to use for displaying items?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[testimonials]" => array(1)
			),
			"std" => '3',
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_testimonials_slider'] = array(
			"title" => esc_html__('Use Slider', 'trx_addons'),
			"desc" => wp_kses_data( __("Do you want to use Slider to show items?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[testimonials]" => array(1)
			),
			"std" => '1',
			"type" => "checkbox"
			);
		$TRX_ADDONS_STORAGE['options']['cv_testimonials_slides_space'] = array(
			"title" => esc_html__('Space between slides', 'trx_addons'),
			"desc" => wp_kses_data( __("Specify space between slides (in pixels)", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[testimonials]" => array(1),
				"cv_testimonials_slider" => array(1)
			),
			"std" => '30',
			"type" => "text"
			);
	}
	
	if (trx_addons_get_file_dir('cv/includes/cv.certificates.php')) {
		$TRX_ADDONS_STORAGE['options']['cv_certificates_info'] = array(
			"title" => esc_html__('Certificates Section', 'trx_addons'),
			"desc" => wp_kses_data( __('How many posts will be displayed in this section, columns number, use slider, etc.', 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[certificates]" => array(1)
			),
			"type" => "info"
			);
		$TRX_ADDONS_STORAGE['options']['cv_certificates_title'] = array(
			"title" => esc_html__("Section's title", 'trx_addons'),
			"desc" => wp_kses_data( __("Certificates section's title", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[certificates]" => array(1)
			),
			"std" => esc_html__('Certificates', 'trx_addons'),
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_certificates_count'] = array(
			"title" => esc_html__("Items number", 'trx_addons'),
			"desc" => wp_kses_data( __("How many items to be displayed?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[certificates]" => array(1)
			),
			"std" => '6',
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_certificates_columns'] = array(
			"title" => esc_html__('Columns number', 'trx_addons'),
			"desc" => wp_kses_data( __("How many columns to use for displaying items?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[certificates]" => array(1)
			),
			"std" => '3',
			"type" => "text"
			);
		$TRX_ADDONS_STORAGE['options']['cv_certificates_slider'] = array(
			"title" => esc_html__('Use Slider', 'trx_addons'),
			"desc" => wp_kses_data( __("Do you want to use Slider to show items?", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[certificates]" => array(1)
			),
			"std" => '1',
			"type" => "checkbox"
			);
		$TRX_ADDONS_STORAGE['options']['cv_certificates_slides_space'] = array(
			"title" => esc_html__('Space between slides', 'trx_addons'),
			"desc" => wp_kses_data( __("Specify space between slides (in pixels)", 'trx_addons') ),
			"dependency" => array(
				"cv_enable" => array(1),
				"cv_parts[certificates]" => array(1),
				"cv_certificates_slider" => array(1)
			),
			"std" => '30',
			"type" => "text"
			);
	}
	$TRX_ADDONS_STORAGE['options']['cv_contacts_info'] = array(
		"title" => esc_html__('Contacts Section', 'trx_addons'),
		"desc" => wp_kses_data( __('Contacts section parameters', 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_parts[contacts]" => array(1)
		),
		"type" => "info"
		);
	$TRX_ADDONS_STORAGE['options']['cv_contacts_title'] = array(
		"title" => esc_html__("Section's title", 'trx_addons'),
		"desc" => wp_kses_data( __("Contacts section's title", 'trx_addons') ),
		"dependency" => array(
			"cv_enable" => array(1),
			"cv_parts[contacts]" => array(1)
		),
		"std" => esc_html__('Contacts', 'trx_addons'),
		"type" => "text"
		);
	
}


// Section 'Theme Specific'
$TRX_ADDONS_STORAGE['options']['theme_specific_section'] = array(
	"title" => esc_html__('Theme specific', 'trx_addons'),
	"desc" => wp_kses_data( __("Theme specific settings", 'trx_addons') ),
	"type" => "section"
	);
$TRX_ADDONS_STORAGE['options']['input_hover'] = array(
	"title" => esc_html__("Input field's hover", 'trx_addons'),
	"desc" => wp_kses_data( __("Select the default hover effect for the shortcode 'form' input fields and for the comment's form (if theme support)", 'trx_addons') ),
	"std" => 'default',
	"options" => trx_addons_get_list_input_hover(),
	"type" => "select"
	);
$TRX_ADDONS_STORAGE['options']['columns_wrap_class'] = array(
	"title" => esc_html__("Column's wrap class", 'trx_addons'),
	"desc" => wp_kses_data( __("Specify theme specific class for the column's wrap. If empty - use plugin's internal grid", 'trx_addons') ),
	"std" => '',
	"type" => "text"
	);
$TRX_ADDONS_STORAGE['options']['columns_wrap_class_fluid'] = array(
	"title" => esc_html__("Column's wrap class for fluid columns", 'trx_addons'),
	"desc" => wp_kses_data( __("Specify theme specific class for the fluid column's wrap. If empty - use plugin's internal grid", 'trx_addons') ),
	"std" => '',
	"type" => "text"
	);
$TRX_ADDONS_STORAGE['options']['column_class'] = array(
	"title" => esc_html__('Class for the single column', 'trx_addons'),
	"desc" => wp_kses_data( __("For example: column-$1_$2, where $1 - column width, $2 - total columns: column-1_4, column-2_3, etc. If empty - use plugin's internal grid", 'trx_addons') ),
	"std" => "",
	"type" => "text"
	);

trx_addons_load_options();
?>