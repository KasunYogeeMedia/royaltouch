/**
 * Debug utilities (for internal use only!)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */


function trx_addons_debug_object(obj) {
	"use strict";
	var html = arguments[1] ? arguments[1] : false;				// Tags decorate
	var recursive = arguments[2] ? arguments[2] : false;		// Show inner objects (arrays)
	var showMethods = arguments[3] ? arguments[3] : false;		// Show object's methods
	var level = arguments[4] ? arguments[4] : 0;				// Nesting level (for internal usage only)
	var dispStr = "";
	var addStr = "";
	if (level>0) {
		dispStr += (obj===null ? "null" : typeof(obj)) + (html ? "\n<br />" : "\n");
		addStr = trx_addons_replicate(html ? '&nbsp;' : ' ', level*2);
	}
	if (obj!==null) {
		for (var prop in obj) {
			if (!showMethods && typeof(obj[prop])=='function')	// || prop=='innerHTML' || prop=='outerHTML' || prop=='innerText' || prop=='outerText')
				continue;
			if (recursive && (typeof(obj[prop])=='object' || typeof(obj[prop])=='array') && obj[prop]!=obj)
				dispStr += addStr + (html ? "<b>" : "")+prop+(html ? "</b>" : "")+'='+trx_addons_debug_object(obj[prop], html, recursive, showMethods, level+1);
			else
				dispStr += addStr + (html ? "<b>" : "")+prop+(html ? "</b>" : "")+'='+(typeof(obj[prop])=='string' ? '"' : '')+obj[prop]+(typeof(obj[prop])=='string' ? '"' : '')+(html ? "\n<br />" : "\n");
		}
	}
	return dispStr;	//decodeURI(dispStr);
}

function trx_addons_debug_log(s) {
	if (TRX_ADDONS_STORAGE['user_logged_in']) {
		if (jQuery('#debug_log').length == 0) {
			jQuery('body').append('<div id="debug_log"><span id="debug_log_close" onclick="jQuery(\'#debug_log\').hide();">x</span><div id="debug_log_content"></div></div>'); 
		}
		jQuery('#debug_log_content').append('<br>'+s);
		jQuery('#debug_log').show();
	}
}

if (window.dcl===undefined) function dcl(s) { console.log(s); }
if (window.dco===undefined) function dco(s,h,r) { console.log(trx_addons_debug_object(s,h,r)); }
if (window.dal===undefined) function dal(s) { if (TRX_ADDONS_STORAGE['user_logged_in']) alert(s); }
if (window.dao===undefined) function dao(s,h,r) { if (TRX_ADDONS_STORAGE['user_logged_in']) alert(trx_addons_debug_object(s,h,r)); }
if (window.ddl===undefined) function ddl(s) { trx_addons_debug_log(s); }
if (window.ddo===undefined) function ddo(s,h,r) { trx_addons_debug_log(trx_addons_debug_object(s,h,r)); }
