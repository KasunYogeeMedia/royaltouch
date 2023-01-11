<?php
/**
 * HTML manipulations
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}



/* CSS & JS
-------------------------------------------------------------------------------- */

// Return string with position rules for the style attr
if (!function_exists('trx_addons_get_css_position_from_values')) {
	function trx_addons_get_css_position_from_values($top='',$right='',$bottom='',$left='',$width='',$height='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left','width','height');
		}
		$output = '';
		if (is_array($top) && count($top) > 0) {
			foreach ($top as $k=>$v) {
				$imp = substr($v, 0, 1);
				if ($imp == '!') $v = substr($v, 1);
				if ($v != '') $output .= ($k=='width' ? 'width' : ($k=='height' ? 'height' : 'margin-'.esc_attr($k))) . ':' . esc_attr(trx_addons_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
			}
		}
		return $output;
	}
}

// Return string with dimensions rules for the style attr
if (!function_exists('trx_addons_get_css_dimensions_from_values')) {
	function trx_addons_get_css_dimensions_from_values($width='',$height='') {
		if (!is_array($width)) {
			$width = compact('width','height');
		}
		$output = '';
		if (is_array($width) && count($width) > 0) {
			foreach ($width as $k=>$v) {
				$imp = substr($v, 0, 1);
				if ($imp == '!') $v = substr($v, 1);
				if ($v != '') $output .= esc_attr($k) . ':' . esc_attr(trx_addons_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
			}
		}
		return $output;
	}
}

// Minify CSS string
if (!function_exists('trx_addons_minify_css')) {
	function trx_addons_minify_css($css) {
		$css = preg_replace("/\r*\n*/", "", $css);
		$css = preg_replace("/\s{2,}/", " ", $css);
        //$css = str_ireplace('@CHARSET "UTF-8";', "", $css);
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
if (!function_exists('trx_addons_minify_js')) {
    add_filter('trx_addons_filter_prepare_js', 'trx_addons_minify_js', 10, 2);
    function trx_addons_minify_js($js, $minify=true) {
        if ($minify) {
            // Remove multi-row comments
            //$js = preg_replace('/(\/\*)[^(\*\/)]*(\*\/)/', '', $js);
            $pos = 0;
            while (($pos = strpos($js, '/*', $pos))!==false) {
                if (($pos2 = strpos($js, '*/', $pos))!==false)
                    $js = substr($js, 0, $pos) . substr($js, $pos2+2);
                else
                    break;
            }
            // Remove single-line comments
            //$js = preg_replace('/\s*\/\/[^\n]*\n/', '', $js);
            $pos = -1;
            while (($pos = strpos($js, '//', $pos+1))!==false) {
                // Remove if previous symbol not in \ : " '
                if ($js[$pos-1]!='\\' && $js[$pos-1]!=':' && $js[$pos-1]!='"' && $js[$pos-1]!="'") {
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
            //$js = preg_replace('/([}])\s+(else)/', '$1else', $js);
            //$js = preg_replace('/([}])\s+(var)/', '$1;var', $js);
            //$js = preg_replace('/([{};])\s+(\$)/', '$1\$', $js);
        }
        return $js;
    }
}

// Return value for the style attr
if (!function_exists('trx_addons_prepare_css_value')) {
	function trx_addons_prepare_css_value($val) {
		if ($val != '') {
			$ed = substr($val, -1);
			if ('0'<=$ed && $ed<='9') $val .= 'px';
		}
		return $val;
	}
}

// Return class for the columns wrapper
if (!function_exists('trx_addons_get_columns_wrap_class')) {
	function trx_addons_get_columns_wrap_class($fluid=false) {
		return trx_addons_get_option('columns_wrap_class')!='' 
					? trx_addons_get_option('columns_wrap_class') . ($fluid && trx_addons_get_option('columns_wrap_class_fluid')!='' ? ' '.trx_addons_get_option('columns_wrap_class_fluid') : '') 
					: 'trx_addons_columns_wrap' . ($fluid ? ' columns_fluid' : '');
	}
}

// Return class for the single column
if (!function_exists('trx_addons_get_column_class')) {
	function trx_addons_get_column_class($num, $all) {
		return str_replace(array('$1', '$2'), array($num, $all), trx_addons_get_option('column_class')!='' ? trx_addons_get_option('column_class') : 'trx_addons_column-$1_$2');
	}
}

// Return array with classes from css-file
if (!function_exists('trx_addons_parse_icons_classes')) {
	function trx_addons_parse_icons_classes($css) {
		$rez = array();
		if (!file_exists($css)) return $rez;
		$file = file($css);
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


/* HTML
-------------------------------------------------------------------------------- */

// Return first tag from text
if (!function_exists('trx_addons_get_tag')) {
	function trx_addons_get_tag($text, $tag_start, $tag_end) {
		$val = '';
		if (($pos_start = strpos($text, $tag_start))!==false) {
			$pos_end = strpos($text, $tag_end, $pos_start);
			if ($pos_end===false) {
				$tag_end = '>';
				$pos_end = strpos($text, $tag_end, $pos_start);
			}
			$val = substr($text, $pos_start, $pos_end+strlen($tag_end)-$pos_start);
		}
		return $val;
	}
}

// Replace {{ and }} to the < and > in the string
// (this is allow use html tags in the some shortcode parameters)
if (!function_exists('trx_addons_parse_codes')) {
	function trx_addons_parse_codes($text, $tag_start='{{', $tag_end='}}') {
		return str_replace(array($tag_start, $tag_end), array('<', '>'), $text);
	}
}




/* URL utilities
-------------------------------------------------------------------------------- */

// Return internal page link - if is customize mode - full url else only hash part
if (!function_exists('trx_addons_get_hash_link')) {
	function trx_addons_get_hash_link($hash) {
		if (strpos($hash, 'http')!==0) {
			if ($hash[0]!='#') $hash = '#'.$hash;
			if (is_customize_preview()) $hash = trx_addons_get_protocol().'://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"]) . $hash;
		}
		return $hash;
	}
}

// Return current site protocol
if (!function_exists('trx_addons_get_protocol')) {
	function trx_addons_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}

// Add parameters to URL
if (!function_exists('trx_addons_add_to_url')) {
	function trx_addons_add_to_url($url, $prm) {
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

// Set e-mail content type
if (!function_exists('trx_addons_set_html_content_type')) {
	function trx_addons_set_html_content_type() {
		return 'text/plain';
	}
	add_filter( 'wp_mail_content_type', 'trx_addons_set_html_content_type' );
}

// Decode html-entities in the shortcode parameters
if (!function_exists('trx_addons_html_decode')) {
	function trx_addons_html_decode($prm) {
		if (is_array($prm) && count($prm) > 0) {
			foreach ($prm as $k=>$v) {
				if (is_string($v))
					$prm[$k] = htmlspecialchars_decode($v, ENT_QUOTES);
			}
		}
		return $prm;
	}
}

// Return current site protocol
if (!function_exists('trx_addons_get_protocol')) {
	function trx_addons_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}




/* GET, POST and SESSION utilities
-------------------------------------------------------------------------------- */

// Return GET or POST value
if (!function_exists('trx_addons_get_value_gp')) {
	function trx_addons_get_value_gp($name, $defa='') {
		$rez = $defa;
		$magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1;
		if (isset($_GET[$name])) {
			$rez = $magic ? stripslashes(trim($_GET[$name])) : trim($_GET[$name]);
		} else if (isset($_POST[$name])) {
			$rez = $magic ? stripslashes(trim($_POST[$name])) : trim($_POST[$name]);
		}
		return $rez;
	}
}

// Return GET or POST or COOKIE value
if (!function_exists('trx_addons_get_value_gpc')) {
	function trx_addons_get_value_gpc($name, $defa='') {
		$rez = $defa;
		$magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1;
		if (isset($_GET[$name])) {
			$rez = $magic ? stripslashes(trim($_GET[$name])) : trim($_GET[$name]);
		} else if (isset($_POST[$name])) {
			$rez = $magic ? stripslashes(trim($_POST[$name])) : trim($_POST[$name]);
		} else if (isset($_COOKIE[$name])) {
			$rez = $magic ? stripslashes(trim($_COOKIE[$name])) : trim($_COOKIE[$name]);
		}
		return $rez;
	}
}


// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('trx_addons_get_value_gps')) {
	function trx_addons_get_value_gps($name, $defa='') {
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_SESSION[$name])) {
			$rez = stripslashes(trim($_SESSION[$name]));
		}
		return $rez;
	}
}

// Save value into session
if (!function_exists('trx_addons_set_session_value')) {
	function trx_addons_set_session_value($name, $value) {
		if (!session_id()) session_start();
		$_SESSION[$name] = $value;
	}
}

// Delete value from session
if (!function_exists('trx_addons_del_session_value')) {
	function trx_addons_del_session_value($name) {
		if (!session_id()) session_start();
		unset($_SESSION[$name]);
	}
}
?>