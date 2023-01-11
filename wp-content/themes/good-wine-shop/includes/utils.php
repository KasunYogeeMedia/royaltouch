<?php
/**
 * Theme tags and utilities
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



/* Custom fields
----------------------------------------------------------------------------------------------------- */

// Show theme specific fields in Post (and Page) options
function good_wine_shop_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		
		case 'mediamanager':
			wp_enqueue_media( );
			$title = empty($field['data_type']) || $field['data_type']=='image'
							? esc_html__( 'Choose Image', 'good-wine-shop')
							: esc_html__( 'Choose Media', 'good-wine-shop');
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager good_wine_shop_media_selector"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] 
					? esc_html__( 'Choose Images', 'good-wine-shop') 
					: $title
					).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] 
					? esc_html__( 'Add to Gallery', 'good-wine-shop') 
					: $title
					).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-type="'.esc_attr(!empty($field['data_type']) ? $field['data_type'] : 'image').'"
				data-linked-field="'.esc_attr($field['linked_field_id']).'"
				>' . (isset($field['multiple']) && $field['multiple'] 
					? esc_html__( 'Choose Images', 'good-wine-shop') 
					: esc_html($title)
				) . '</a>';
			break;
	}
	return apply_filters('good_wine_shop_filter_show_custom_field', $output, $id, $field, $value);
}





/* Arrays manipulations
----------------------------------------------------------------------------------------------------- */

// Merge arrays and lists (preserve number indexes)
if (!function_exists('good_wine_shop_array_merge')) {
	function good_wine_shop_array_merge($a1, $a2) {
		for ($i = 1; $i < func_num_args(); $i++){
			$arg = func_get_arg($i);
			if (is_array($arg) && count($arg)>0) {
				foreach($arg as $k=>$v) {
					$a1[$k] = $v;
				}
			}
		}
		return $a1;
	}
}

// Inserts any number of scalars or arrays at the point
// in the haystack immediately after the search key ($needle) was found,
// or at the end if the needle is not found or not supplied.
if (!function_exists('good_wine_shop_array_insert')) {
	function good_wine_shop_array_insert_after(&$haystack, $needle, $stuff){
		if (! is_array($haystack) ) return -1;

		$new_array = array();
		for ($i = 2; $i < func_num_args(); ++$i){
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				if ($i==2)
					$new_array = $arg;
				else
					$new_array = good_wine_shop_array_merge($new_array, $arg);
			} else 
				$new_array[] = $arg;
		}

		$i = 0;
		if (is_array($haystack) && count($haystack) > 0) {
			foreach($haystack as $key => $value){
				$i++;
				if ($key == $needle) break;
			}
		}

		$haystack = good_wine_shop_array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true));

		return $i;
    }
}

// Inserts any number of scalars or arrays at the point
// in the haystack immediately before the search key ($needle) was found,
// or at the end if the needle is not found or not supplied.
if (!function_exists('good_wine_shop_array_before')) {
	function good_wine_shop_array_insert_before(&$haystack, $needle, $stuff){
		if (! is_array($haystack) ) return -1;

		$new_array = array();
		for ($i = 2; $i < func_num_args(); ++$i){
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				if ($i==2)
					$new_array = $arg;
				else
					$new_array = good_wine_shop_array_merge($new_array, $arg);
			} else 
				$new_array[] = $arg;
		}

		$i = 0;
		if (is_array($haystack) && count($haystack) > 0) {
			foreach($haystack as $key => $value){
				if ($key === $needle) break;
				$i++;
			}
		}

		$haystack = good_wine_shop_array_merge(array_slice($haystack, 0, $i, true), $new_array, array_slice($haystack, $i, null, true));

		return $i;
    }
}





/* HTML & CSS
----------------------------------------------------------------------------------------------------- */

// Return first tag from text
if (!function_exists('good_wine_shop_get_tag')) {
	function good_wine_shop_get_tag($text, $tag_start, $tag_end) {
		$val = '';
		if (($pos_start = strpos($text, $tag_start))!==false) {
            $pos_end = $tag_end ? strpos( $text, $tag_end, $pos_start ) : false;
			if ($pos_end===false) {
                $tag_end = substr( $tag_start, 0, 1 ) == '<' ? '>' : ']';
				$pos_end = strpos($text, $tag_end, $pos_start);
			}
			$val = substr($text, $pos_start, $pos_end+strlen($tag_end)-$pos_start);
		}
		return $val;
	}
}

// Decode html-entities in the shortcode parameters
if (!function_exists('good_wine_shop_html_decode')) {
	function good_wine_shop_html_decode($prm) {
		if (is_array($prm) && count($prm) > 0) {
			foreach ($prm as $k=>$v) {
				if (is_string($v))
					$prm[$k] = wp_specialchars_decode($v, ENT_QUOTES);
			}
		}
		return $prm;
	}
}

// Return string with position rules for the style attr
if (!function_exists('good_wine_shop_get_css_position_from_values')) {
	function good_wine_shop_get_css_position_from_values($top='',$right='',$bottom='',$left='',$width='',$height='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left','width','height');
		}
		$output = '';
		foreach ($top as $k=>$v) {
			$imp = substr($v, 0, 1);
			if ($imp == '!') $v = substr($v, 1);
			if ($v != '') $output .= ($k=='width' ? 'width' : ($k=='height' ? 'height' : 'margin-'.esc_attr($k))) . ':' . esc_attr(good_wine_shop_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
		}
		return $output;
	}
}

// Return value for the style attr
if (!function_exists('good_wine_shop_prepare_css_value')) {
	function good_wine_shop_prepare_css_value($val) {
		if ($val != '') {
			$ed = substr($val, -1);
			if ('0'<=$ed && $ed<='9') $val .= 'px';
		}
		return $val;
	}
}

// Return array with classes from css-file
if (!function_exists('good_wine_shop_parse_icons_classes')) {
	function good_wine_shop_parse_icons_classes($css) {
		$rez = array();
		if (!file_exists($css)) return $rez;
		$file = good_wine_shop_fga($css);
		if (!is_array($file) || count($file) == 0) return $rez;
		foreach ($file as $row) {
			if (substr($row, 0, 1)!='.') continue;
			$name = '';
			for ($i=1; $i<strlen($row); $i++) {
				$ch = substr($row, $i, 1);
				if (in_array($ch, array(':', '{', '.', ' '))) break;
				$name .= $ch;
			}
			if ($name!='') $rez[] = $name;
		}
		return $rez;
	}
}

// Minify CSS string
if (!function_exists('good_wine_shop_minify_css')) {
	function good_wine_shop_minify_css($css) {
		$css = preg_replace("/\r*\n*/", "", $css);
		$css = preg_replace("/\s{2,}/", " ", $css);
		$css = preg_replace("/\s*>\s*/", ">", $css);
		$css = preg_replace("/\s*:\s*/", ":", $css);
		$css = preg_replace("/\s*{\s*/", "{", $css);
		$css = preg_replace("/\s*;*\s*}\s*/", "}", $css);
        $css = str_replace(', ', ',', $css);
        $css = preg_replace("/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/", "", $css);
        return $css;
	}
}

// Minify JS string
if (!function_exists('good_wine_shop_minify_js')) {
	function good_wine_shop_minify_js($js) {
		// Remove multi-row comments
		$pos = 0;
		while (($pos = strpos($js, '/*', $pos))!==false) {
			if (($pos2 = strpos($js, '*/', $pos))!==false)
				$js = substr($js, 0, $pos) . substr($js, $pos2+2);
			else
				break;
		}
		// Remove single-line comments
		$pos = -1;
		while (($pos = strpos($js, '//', $pos+1))!==false) {
			if ($js[$pos-1]!='\\') {
				$pos2 = strpos($js, "\n", $pos);
				if ($pos2==false) $pos2 = strlen($js);
				$js = substr($js, 0, $pos) . substr($js, $pos2);
			}
		}
		// Remove spaces before/after {}()
		$js = preg_replace('/\s+/', ' ', $js);
		$js = preg_replace('/([;}{\)\(])\s+/', '$1 ', $js);
		$js = preg_replace('/\s+([;}{\)\(])/', ' $1', $js);
		$js = preg_replace('/(else)\s+/', '$1 ', $js);
		return $js;
	}
}





/* GET, POST, COOKIE, SESSION manipulations
----------------------------------------------------------------------------------------------------- */

// Get GET, POST value
if (!function_exists('good_wine_shop_get_value_gp')) {
	function good_wine_shop_get_value_gp($name, $defa='') {
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		}
		return $rez;
	}
}


// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('good_wine_shop_get_value_gps')) {
	function good_wine_shop_get_value_gps($name, $defa='', $page='') {
		$putToSession = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_SESSION[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_SESSION[$name.($page!='' ? '_'.($page) : '')]));
			$putToSession = false;
		}
		if ($putToSession)
			good_wine_shop_set_session_value($name, $rez, $page);
		return $rez;
	}
}

// Get GET, POST, COOKIE value and save it (if need)
if (!function_exists('good_wine_shop_get_value_gpc')) {
	function good_wine_shop_get_value_gpc($name, $defa='', $page='', $exp=0) {
		$putToCookie = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_COOKIE[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_COOKIE[$name.($page!='' ? '_'.($page) : '')]));
			$putToCookie = false;
		}
		if ($putToCookie)
			setcookie($name.($page!='' ? '_'.($page) : ''), $rez, $exp, '/');
		return $rez;
	}
}

// Save value into session
if (!function_exists('good_wine_shop_set_session_value')) {
	function good_wine_shop_set_session_value($name, $value, $page='') {
		if (!session_id()) session_start();
		$_SESSION[$name.($page!='' ? '_'.($page) : '')] = $value;
	}
}

// Save value into session
if (!function_exists('good_wine_shop_del_session_value')) {
	function good_wine_shop_del_session_value($name, $page='') {
		if (!session_id()) session_start();
		unset($_SESSION[$name.($page!='' ? '_'.($page) : '')]);
	}
}





/* Colors manipulations
----------------------------------------------------------------------------------------------------- */

if (!function_exists('good_wine_shop_hex2rgb')) {
	function good_wine_shop_hex2rgb($hex) {
		$dec = hexdec(substr($hex, 0, 1)== '#' ? substr($hex, 1) : $hex);
		return array('r'=> $dec >> 16, 'g'=> ($dec & 0x00FF00) >> 8, 'b'=> $dec & 0x0000FF);
	}
}

if (!function_exists('good_wine_shop_hex2rgba')) {
	function good_wine_shop_hex2rgba($hex, $alpha) {
		$rgb = good_wine_shop_hex2rgb($hex);
		return 'rgba('.intval($rgb['r']).','.intval($rgb['g']).','.intval($rgb['b']).','.floatval($alpha).')';
	}
}

if (!function_exists('good_wine_shop_hex2hsb')) {
	function good_wine_shop_hex2hsb ($hex) {
		return good_wine_shop_rgb2hsb(good_wine_shop_hex2rgb($hex));
	}
}

if (!function_exists('good_wine_shop_rgb2hsb')) {
	function good_wine_shop_rgb2hsb ($rgb) {
		$hsb = array();
		$hsb['b'] = max(max($rgb['r'], $rgb['g']), $rgb['b']);
		$hsb['s'] = ($hsb['b'] <= 0) ? 0 : round(100*($hsb['b'] - min(min($rgb['r'], $rgb['g']), $rgb['b'])) / $hsb['b']);
		$hsb['b'] = round(($hsb['b'] /255)*100);
		if (($rgb['r']==$rgb['g']) && ($rgb['g']==$rgb['b'])) $hsb['h'] = 0;
		else if($rgb['r']>=$rgb['g'] && $rgb['g']>=$rgb['b']) $hsb['h'] = 60*($rgb['g']-$rgb['b'])/($rgb['r']-$rgb['b']);
		else if($rgb['g']>=$rgb['r'] && $rgb['r']>=$rgb['b']) $hsb['h'] = 60  + 60*($rgb['g']-$rgb['r'])/($rgb['g']-$rgb['b']);
		else if($rgb['g']>=$rgb['b'] && $rgb['b']>=$rgb['r']) $hsb['h'] = 120 + 60*($rgb['b']-$rgb['r'])/($rgb['g']-$rgb['r']);
		else if($rgb['b']>=$rgb['g'] && $rgb['g']>=$rgb['r']) $hsb['h'] = 180 + 60*($rgb['b']-$rgb['g'])/($rgb['b']-$rgb['r']);
		else if($rgb['b']>=$rgb['r'] && $rgb['r']>=$rgb['g']) $hsb['h'] = 240 + 60*($rgb['r']-$rgb['g'])/($rgb['b']-$rgb['g']);
		else if($rgb['r']>=$rgb['b'] && $rgb['b']>=$rgb['g']) $hsb['h'] = 300 + 60*($rgb['r']-$rgb['b'])/($rgb['r']-$rgb['g']);
		else $hsb['h'] = 0;
		$hsb['h'] = round($hsb['h']);
		return $hsb;
	}
}

if (!function_exists('good_wine_shop_hsb2rgb')) {
	function good_wine_shop_hsb2rgb($hsb) {
		$rgb = array();
		$h = round($hsb['h']);
		$s = round($hsb['s']*255/100);
		$v = round($hsb['b']*255/100);
		if ($s == 0) {
			$rgb['r'] = $rgb['g'] = $rgb['b'] = $v;
		} else {
			$t1 = $v;
			$t2 = (255-$s)*$v/255;
			$t3 = ($t1-$t2)*($h%60)/60;
			if ($h==360) $h = 0;
			if ($h<60) { 		$rgb['r']=$t1; $rgb['b']=$t2; $rgb['g']=$t2+$t3; }
			else if ($h<120) {	$rgb['g']=$t1; $rgb['b']=$t2; $rgb['r']=$t1-$t3; }
			else if ($h<180) {	$rgb['g']=$t1; $rgb['r']=$t2; $rgb['b']=$t2+$t3; }
			else if ($h<240) {	$rgb['b']=$t1; $rgb['r']=$t2; $rgb['g']=$t1-$t3; }
			else if ($h<300) {	$rgb['b']=$t1; $rgb['g']=$t2; $rgb['r']=$t2+$t3; }
			else if ($h<360) {	$rgb['r']=$t1; $rgb['g']=$t2; $rgb['b']=$t1-$t3; }
			else {				$rgb['r']=0;   $rgb['g']=0;   $rgb['b']=0; }
		}
		return array('r'=>round($rgb['r']), 'g'=>round($rgb['g']), 'b'=>round($rgb['b']));
	}
}

if (!function_exists('good_wine_shop_rgb2hex')) {
	function good_wine_shop_rgb2hex($rgb) {
		$hex = array(
			dechex($rgb['r']),
			dechex($rgb['g']),
			dechex($rgb['b'])
		);
		return '#'.(strlen($hex[0])==1 ? '0' : '').($hex[0]).(strlen($hex[1])==1 ? '0' : '').($hex[1]).(strlen($hex[2])==1 ? '0' : '').($hex[2]);
	}
}

if (!function_exists('good_wine_shop_hsb2hex')) {
	function good_wine_shop_hsb2hex($hsb) {
		return good_wine_shop_rgb2hex(good_wine_shop_hsb2rgb($hsb));
	}
}






/* String manipulations
----------------------------------------------------------------------------------------------------- */

// Replace macros in the string
if (!function_exists('good_wine_shop_prepare_macros')) {
	function good_wine_shop_prepare_macros($str) {
		return str_replace(
			array("{{",  "}}",   "[[",  "]]",   "||"),
			array("<i>", "</i>", "<b>", "</b>", "<br>"),
			$str);
	}
}

// Remove macros from the string
if (!function_exists('good_wine_shop_remove_macros')) {
	function good_wine_shop_remove_macros($str) {
		return str_replace(
			array("{{", "}}", "[[", "]]", "||"),
			array("",   "",   "",   "",   ""),
			$str);
	}
}

// Check value for "on" | "off" | "inherit" values
if (!function_exists('good_wine_shop_is_on')) {
	function good_wine_shop_is_on($prm) {
		return $prm>0 || in_array(strtolower($prm), array('true', 'on', 'yes', 'show'));
	}
}
if (!function_exists('good_wine_shop_is_off')) {
	function good_wine_shop_is_off($prm) {
		return empty($prm) || $prm===0 || in_array(strtolower($prm), array('false', 'off', 'no', 'none', 'hide'));
	}
}
if (!function_exists('good_wine_shop_is_inherit')) {
	function good_wine_shop_is_inherit($prm) {
		return in_array(strtolower($prm), array('inherit'));
	}
}

// Return truncated string
if (!function_exists('good_wine_shop_strshort')) {
	function good_wine_shop_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return '';
		if ($maxlength == 0)
			return '';
		if ($maxlength >= strlen($str)) 
			return strip_tags($str);
		$str = substr(strip_tags($str), 0, $maxlength - strlen($add));
		$ch = substr($str, $maxlength - strlen($add), 1);
		if ($ch != ' ') {
			for ($i = strlen($str) - 1; $i > 0; $i--)
				if (substr($str, $i, 1) == ' ') break;
			$str = trim(substr($str, 0, $i));
		}
		if (!empty($str) && strpos(',.:;-', substr($str, -1))!==false) $str = substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('good_wine_shop_unserialize')) {
	function good_wine_shop_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}





/* Date & Time
----------------------------------------------------------------------------------------------------- */

// Prepare month names in date for translation
if (!function_exists('good_wine_shop_get_date_translations')) {
	function good_wine_shop_get_date_translations($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				esc_html__('January', 'good-wine-shop'),
				esc_html__('February', 'good-wine-shop'),
				esc_html__('March', 'good-wine-shop'),
				esc_html__('April', 'good-wine-shop'),
				esc_html__('May', 'good-wine-shop'),
				esc_html__('June', 'good-wine-shop'),
				esc_html__('July', 'good-wine-shop'),
				esc_html__('August', 'good-wine-shop'),
				esc_html__('September', 'good-wine-shop'),
				esc_html__('October', 'good-wine-shop'),
				esc_html__('November', 'good-wine-shop'),
				esc_html__('December', 'good-wine-shop'),
				esc_html__('Jan', 'good-wine-shop'),
				esc_html__('Feb', 'good-wine-shop'),
				esc_html__('Mar', 'good-wine-shop'),
				esc_html__('Apr', 'good-wine-shop'),
				esc_html__('May', 'good-wine-shop'),
				esc_html__('Jun', 'good-wine-shop'),
				esc_html__('Jul', 'good-wine-shop'),
				esc_html__('Aug', 'good-wine-shop'),
				esc_html__('Sep', 'good-wine-shop'),
				esc_html__('Oct', 'good-wine-shop'),
				esc_html__('Nov', 'good-wine-shop'),
				esc_html__('Dec', 'good-wine-shop'),
			),
			$dt);
	}
}






/* Media: images, galleries, audio, video
----------------------------------------------------------------------------------------------------- */

// Get image sizes from image url (if image in the uploads folder)
if (!function_exists('good_wine_shop_getimagesize')) {
	function good_wine_shop_getimagesize($url) {
	
		// Get upload path & dir
		$upload_info = wp_upload_dir();

		// Where check file
		$locations = array(
			'uploads' => array(
				'dir' => $upload_info['basedir'],
				'url' => $upload_info['baseurl']
				),
			'child' => array(
				'dir' => get_stylesheet_directory(),
				'url' => get_stylesheet_directory_uri()
				),
			'theme' => array(
				'dir' => get_template_directory(),
				'url' => get_template_directory_uri()
				)
			);
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		$img_size = false;
		
		foreach ($locations as $key=>$loc) {
			// if the schemes differe, images don't show up.
			if (!strncmp($url, $https_prefix, strlen($https_prefix)))
				$loc['url'] = str_replace($http_prefix, $https_prefix, $loc['url']);
			else if (!strncmp($url, $http_prefix, strlen($http_prefix)))
				$loc['url'] = str_replace($https_prefix, $http_prefix, $loc['url']);		
			
			// Check if $img_url is local.
			if ( false === strpos($url, $loc['url']) ) continue;
			
			// Get path of image.
			$img_path = $loc['dir'] . str_replace($loc['url'], '', $url);
		
			// Check if img path exists, and is an image indeed.
			if ( !file_exists($img_path)) continue;
	
			// Get image size
			$img_size = getimagesize($img_path);
			break;
		}
		
		return $img_size;
	}
}

// Clear thumb sizes from image name
if (!function_exists('good_wine_shop_clear_thumb_size')) {
	function good_wine_shop_clear_thumb_size($url) {
		$pi = pathinfo($url);
		$parts = explode('-', $pi['filename']);
		$suff = explode('x', $parts[count($parts)-1]);
		if (count($suff)==2 && (int) $suff[0] > 0 && (int) $suff[1] > 0) {
			array_pop($parts);
			$url = $pi['dirname'] . '/' . join('-', $parts) . '.' . $pi['extension'];
		}
		return $url;
	}
}

// Add thumb sizes to image name
if (!function_exists('good_wine_shop_add_thumb_size')) {
	function good_wine_shop_add_thumb_size($url, $thumb_size, $check_exists=true) {
		$pi = pathinfo($url);
		$parts = explode('-', $pi['filename']);
		// Remove image sizes from filename
		$suff = explode('x', $parts[count($parts)-1]);
		if (count($suff)==2 && (int) $suff[0] > 0 && (int) $suff[1] > 0) {
			array_pop($parts);
		}
		// Add new image sizes
		global $_wp_additional_image_sizes;
		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) && in_array( $thumb_size, array_keys( $_wp_additional_image_sizes ) ) )
			$parts[] = intval( $_wp_additional_image_sizes[$thumb_size]['width'] ) . 'x' . intval( $_wp_additional_image_sizes[$thumb_size]['height'] );
		$pi['filename'] = join('-', $parts);
		$new_url = $pi['dirname'] . '/' . $pi['filename'] . '.' . $pi['extension'];
		if ($check_exists) {
			$uploads_info = wp_upload_dir();
			$uploads_url = $uploads_info['baseurl'];
			$uploads_dir = $uploads_info['basedir'];
			if (strpos($new_url, $uploads_url)!==false) {
				if (!file_exists(str_replace($uploads_url, $uploads_dir, $new_url)))
					$new_url = $url;
			}
		}
		return $new_url;
	}
}

// Return image size multiplier
if (!function_exists('good_wine_shop_get_thumb_size')) {
	function good_wine_shop_get_thumb_size($ts) {
		static $retina = '-';
		if ($retina=='-') $retina = good_wine_shop_get_retina_multiplier() > 1 ? '-@retina' : '';
		return ($ts=='post-thumbnail' ? '' : 'good_wine_shop-thumb-') . $ts . $retina;
	}
}


// Return url from first <img> tag inserted in post
if (!function_exists('good_wine_shop_get_post_image')) {
	function good_wine_shop_get_post_image($post_text='', $src=true) {
		global $post;
		$img = '';
		if (empty($post_text)) $post_text = $post->post_content;
		if (preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post_text, $matches)) {
			$img = $matches[$src ? 1 : 0][0];
		}
		return $img;
	}
}


// Return url from first <audio> tag inserted in post
if (!function_exists('good_wine_shop_get_post_audio')) {
	function good_wine_shop_get_post_audio($post_text='', $src=true) {
		global $post;
		$img = '';
		if (empty($post_text)) $post_text = $post->post_content;
		if ($src) {
			if (preg_match_all('/<audio.+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post_text, $matches)) {
				$img = $matches[1][0];
			}
		} else {
			$img = good_wine_shop_get_tag($post_text, '<audio', '</audio>');
			if (empty($mg)) {
				$img = do_shortcode(good_wine_shop_get_tag($post_text, '[audio', '[/audio]'));
			}
		}
		return $img;
	}
}


// Return url from first <video> tag inserted in post
if (!function_exists('good_wine_shop_get_post_video')) {
	function good_wine_shop_get_post_video($post_text='', $src=true) {
		global $post;
		$img = '';
		if (empty($post_text)) $post_text = $post->post_content;
		if ($src) {
			if (preg_match_all('/<video.+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post_text, $matches)) {
				$img = $matches[1][0];
			}
		} else {
			$img = good_wine_shop_get_tag($post_text, '<video', '</video>');
			if (empty($mg)) {
				$img = do_shortcode(good_wine_shop_get_tag($post_text, '[video', '[/video]'));
			}
		}
		return $img;
	}
}


// Return url from first <iframe> tag inserted in post
if (!function_exists('good_wine_shop_get_post_iframe')) {
	function good_wine_shop_get_post_iframe($post_text='', $src=true) {
		global $post;
		$img = '';
		if (empty($post_text)) $post_text = $post->post_content;
		if ($src) {
			if (preg_match_all('/<iframe.+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post_text, $matches)) {
				$img = $matches[1][0];
			}
		} else
			$img = good_wine_shop_get_tag($post_text, '<iframe', '</iframe>');
		return $img;
	}
}


// Add 'autoplay' feature in the video
if (!function_exists('good_wine_shop_make_video_autoplay')) {
	function good_wine_shop_make_video_autoplay($video) {
		if (($pos = strpos($video, '<video'))!==false) {
			$video = str_replace('<video', '<video autoplay="autoplay"', $video);
		} else if (($pos = strpos($video, '<iframe'))!==false) {
			if (preg_match('/(<iframe.+src=[\'"])([^\'"]+)([\'"][^>]*>)/i', $video, $matches)) {
				$video = $matches[1] . $matches[2] . (strpos($matches[2], '?')!==false ? '&' : '?') . 'autoplay=1' . $matches[3];
			}
		}
		return $video;
	}
}



/* Enqueue files
----------------------------------------------------------------------------------------------------- */

// Check if file/folder present in the child theme and return path (url) to it. 
// Else - path (url) to file in the main theme dir
if (!function_exists('good_wine_shop_get_file_dir')) {	
	function good_wine_shop_get_file_dir($file, $return_url=false) {
		if ($file[0]=='/') $file = substr($file, 1);
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (file_exists(($child_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
		return $dir;
	}
}

if (!function_exists('good_wine_shop_get_file_url')) {	
	function good_wine_shop_get_file_url($file) {
		return good_wine_shop_get_file_dir($file, true);
	}
}

// Detect folder location with same algorithm as file (see above)
if (!function_exists('good_wine_shop_get_folder_dir')) {	
	function good_wine_shop_get_folder_dir($folder, $return_url=false) {
		if ($folder[0]=='/') $folder = substr($folder, 1);
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (is_dir(($child_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($folder);
		return $dir;
	}
}

if (!function_exists('good_wine_shop_get_folder_url')) {	
	function good_wine_shop_get_folder_url($folder) {
		return good_wine_shop_get_folder_dir($folder, true);
	}
}

// Replace uploads url to current site uploads url
if (!function_exists('good_wine_shop_replace_uploads_url')) {	
	function good_wine_shop_replace_uploads_url($str, $uploads_folder='uploads') {
		static $uploads_url = '', $uploads_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = good_wine_shop_replace_uploads_url($v, $uploads_folder);
			}
		} else if (is_string($str)) {
			if (empty($uploads_url)) {
				$uploads_info = wp_upload_dir();
				$uploads_url = $uploads_info['baseurl'];
				$uploads_len = strlen($uploads_url);
			}
			$break = '\'" ';
			$pos = 0;
			while (($pos = strpos($str, "/{$uploads_folder}/", $pos))!==false) {
				$pos0 = $pos;
				$chg = true;
				while ($pos0) {
					if (strpos($break, substr($str, $pos0, 1))!==false) {
						$chg = false;
						break;
					}
					if (substr($str, $pos0, 5)=='http:' || substr($str, $pos0, 6)=='https:')
						break;
					$pos0--;
				}
				if ($chg) {
					$str = ($pos0 > 0 ? substr($str, 0, $pos0) : '') . ($uploads_url) . substr($str, $pos+strlen($uploads_folder)+1);
					$pos = $pos0 + $uploads_len;
				} else 
					$pos++;
			}
		}
		return $str;
	}
}

// Replace site url to current site url
if (!function_exists('good_wine_shop_replace_site_url')) {	
	function good_wine_shop_replace_site_url($str, $old_url) {
		static $site_url = '', $site_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = good_wine_shop_replace_site_url($v, $old_url);
			}
		} else if (is_string($str)) {
			if (empty($site_url)) {
				$site_url = get_site_url();
				$site_len = strlen($site_url);
				if (substr($site_url, -1)=='/') {
					$site_len--;
					$site_url = substr($site_url, 0, $site_len);
				}
			}
			if (substr($old_url, -1)=='/') $old_url = substr($old_url, 0, strlen($old_url)-1);
			$break = '\'" ';
			$pos = 0;
			while (($pos = strpos($str, $old_url, $pos))!==false) {
				$str = good_wine_shop_unserialize($str);
				if (is_array($str) && count($str) > 0) {
					foreach ($str as $k=>$v) {
						$str[$k] = good_wine_shop_replace_site_url($v, $old_url);
					}
					$str = serialize($str);
					break;
				} else {
					$pos0 = $pos;
					$chg = true;
					while ($pos0 >= 0) {
						if (strpos($break, substr($str, $pos0, 1))!==false) {
							$chg = false;
							break;
						}
						if (substr($str, $pos0, 5)=='http:' || substr($str, $pos0, 6)=='https:')
							break;
						$pos0--;
					}
					if ($chg && $pos0>=0) {
						$str = ($pos0 > 0 ? substr($str, 0, $pos0) : '') . ($site_url) . substr($str, $pos+strlen($old_url));
						$pos = $pos0 + $site_len;
					} else 
						$pos++;
				}
			}
		}
		return $str;
	}
}


// Add parameters to URL
if (!function_exists('good_wine_shop_add_to_url')) {
	function good_wine_shop_add_to_url($url, $prm) {
		if (is_array($prm) && count($prm) > 0) {
			$separator = strpos($url, '?')===false ? '?' : '&';
			foreach ($prm as $k=>$v) {
				$url .= $separator . urlencode($k) . '=' . urlencode($v);
				$separator = '&';
			}
		}
		return $url;
	}
}


// Get domain part from URL
if (!function_exists('good_wine_shop_get_domain_from_url')) {
	function good_wine_shop_get_domain_from_url($url) {
		if (($pos=strpos($url, '://'))!==false) $url = substr($url, $pos+3);
		if (($pos=strpos($url, '/'))!==false) $url = substr($url, 0, $pos);
		return $url;
	}
}



// Init WP Filesystem
if (!function_exists('good_wine_shop_init_filesystem')) {
    add_action( 'after_setup_theme', 'good_wine_shop_init_filesystem', 0);
    function good_wine_shop_init_filesystem() {
        if( !function_exists('WP_Filesystem') ) {
            require_once( ABSPATH .'/wp-admin/includes/file.php' );
        }
        if (is_admin()) {
            $url = admin_url();
            $creds = false;
            // First attempt to get credentials.
            if ( function_exists('request_filesystem_credentials') && false === ( $creds = request_filesystem_credentials( $url, '', false, false, array() ) ) ) {
                // If we comes here - we don't have credentials
                // so the request for them is displaying no need for further processing
                return false;
            }

            // Now we got some credentials - try to use them.
            if ( !WP_Filesystem( $creds ) ) {
                // Incorrect connection data - ask for credentials again, now with error message.
                if ( function_exists('request_filesystem_credentials') ) request_filesystem_credentials( $url, '', true, false );
                return false;
            }

            return true; // Filesystem object successfully initiated.
        } else {
            WP_Filesystem();
        }
        return true;
    }
}



// Put text into specified file
if (!function_exists('good_wine_shop_fpc')) {
    function good_wine_shop_fpc($file, $data, $flag=0) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                // Attention! WP_Filesystem can't append the content to the file!
                // That's why we have to read the contents of the file into a string,
                // add new content to this string and re-write it to the file if parameter $flag == FILE_APPEND!
                return $wp_filesystem->put_contents($file, ($flag==FILE_APPEND ? $wp_filesystem->get_contents($file) : '') . $data, false);
            } else {
                if (good_wine_shop_param_is_on(good_wine_shop_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Put contents to the file "%s" failed', 'good-wine-shop'), $file));
            }
        }
        return false;
    }
}

// Get text from specified file
if (!function_exists('good_wine_shop_fgc')) {
    function good_wine_shop_fgc($file) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return $wp_filesystem->get_contents($file);
            } else {
                if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get contents from the file "%s" failed', 'good-wine-shop'), $file));
            }
        }
        return '';
    }
}

// Get array with rows from specified file
if (!function_exists('good_wine_shop_fga')) {
    function good_wine_shop_fga($file) {
        global $wp_filesystem;
        if (!empty($file)) {
            if (isset($wp_filesystem) && is_object($wp_filesystem)) {
                $file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
                return $wp_filesystem->get_contents_array($file);
            } else {
                if (good_wine_shop_param_is_on(good_wine_shop_get_theme_option('debug_mode')))
                    throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get rows from the file "%s" failed', 'good-wine-shop'), $file));
            }
        }
        return array();
    }
}

// Get text from specified file (local or remote)
if (!function_exists('good_wine_shop_get_local_or_remote_file')) {	
	function good_wine_shop_get_local_or_remote_file($file, $args=array()) {
		$rez = '';
		if (!empty($file)) {
			if (substr($file, 0, 5)=='http:' || substr($file, 0, 6)=='https:') {
				$tm = round( 0.9 * max(30, ini_get('max_execution_time')));
				if (count($args) > 0) {
					$response = wp_remote_post($file, array(
											'timeout'     => $tm,
											'redirection' => $tm,
											'body' => $args
											)
										);
				} else {
					$response = wp_remote_get($file, array(
											'timeout'     => $tm,
											'redirection' => $tm
											)
										);
				}
				if (is_array($response) && isset($response['response']['code']) && $response['response']['code']==200)
					$rez = $response['body'];
			} else {
				if (($file = good_wine_shop_get_file_dir($file)) != '')
					$rez = good_wine_shop_fgc($file);
			}
		}
		return $rez;
	}
}

// Create folder
if (!function_exists('good_wine_shop_mkdir')) {	
	function good_wine_shop_mkdir($folder, $addindex = true) {
		if (is_dir($folder) && $addindex == false) return true;
		$created = wp_mkdir_p(trailingslashit($folder));
		chmod($folder, 0777);
		if ($addindex == false) return $created;
		$index_file = trailingslashit($folder) . 'index.php';
		if (file_exists($index_file)) return $created;
		good_wine_shop_fpc($index_file, "<?php\n// Silence is golden.\n");
		return $created;
	}
}

// Show content with the html layout (if not empty)
if ( !function_exists('good_wine_shop_show_layout') ) {
    function good_wine_shop_show_layout($str, $before='', $after='') {
        if ($str != '') {
            printf("%s%s%s", $before, $str, $after);
        }
    }
}


?>