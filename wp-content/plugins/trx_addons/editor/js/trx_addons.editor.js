/* WP Editor add plugin
-----------------------------------------------------------------*/

// Strings for translation
var TRX_ADDONS_EDITOR = {
	'plugin_author':			'ThemeREX',
	'plugin_description':		'ThemeREX Addons Buttons',

	// Menu items
	'styleselect_title':		'Extra styles for the selected text',
	'tooltip_title':			'Add tooltip to the selected text',

	// Error messages
	'error_text_not_selected':	'First select the letter!',
	'error_empty_value':		'Text is empty!',
};

(function() {
	tinymce.create('tinymce.plugins.Trx_addons', {
		
		/**
		* Returns information about the plugin as a name/value array.
		* The current keys are longname, author, authorurl, infourl and version.
		*
		* @return {Object} Name/value array containing information about the plugin.
		*/
		getInfo : function() {
			return {
				longname : TRX_ADDONS_EDITOR['plugin_description'],
				author : TRX_ADDONS_EDITOR['plugin_author'],
				authorurl : 'http://themeforest.net/user/themerex',
				infourl : 'http://themeforest.net/user/themerex',
				version : "1.0"
			};
		},
		
		
		/**
		* Initializes the plugin, this will be executed after the plugin has been created.
		* This call is done before the editor instance has finished it's initialization so use the onInit event
		* of the editor instance to intercept that event.
		*
		* @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		* @param {string} url Absolute URL to where the plugin is located.
		*/
		init : function(ed, url) {

			/*
			// Menu button
			ed.addButton('trx_addons_menu', {
				type: 'menubutton',
				title : TRX_ADDONS_EDITOR['plugin_menu_title'],
				icon: false,
				//text: TRX_ADDONS_EDITOR['plugin_menu_text'],
				image: url + '/../images/trx_addons.png',
				menu: [
					{
						text: TRX_ADDONS_EDITOR['plugin_menu_item_inline'],
						menu: [
							{
								text: TRX_ADDONS_EDITOR['plugin_menu_item_dropcap'],
								onclick: function() { trx_addons_editor_dropcap(ed); }
							}
						]
					},
					{
						text: TRX_ADDONS_EDITOR['plugin_menu_item_list_style'],
						menu: [
							{
								text: TRX_ADDONS_EDITOR['plugin_menu_item_list_asterisk'],
								onclick: function() { trx_addons_editor_list(ed); }
							}
						]
					}
				]
			});		
			*/	
			// Standard Button 'StyleSelect'
			ed.buttons.styleselect.text = '';
			ed.buttons.styleselect.tooltip = TRX_ADDONS_EDITOR['styleselect_title'],
			ed.buttons.styleselect.icon = 'style';
			ed.buttons.styleselect.image = url + '/../images/style.png';
			
			// Custom Button 'Tooltip'
			ed.addButton('trx_addons_tooltip', {
				title: TRX_ADDONS_EDITOR['tooltip_title'],
				image: url + '/../images/tooltip.png',
				onclick: function() { trx_addons_editor_tooltip(ed); }
			});
			/* or
			// Custom Button 'Tooltip'
			ed.addButton('trx_addons_tooltip', {
				title : TRX_ADDONS_EDITOR['tooltip_title'],
				cmd : 'trx_addons_tooltip',
				image : url + '/../images/tooltip.png'
			});		
			ed.addCommand('trx_addons_tooltip', function() {
				trx_addons_editor_tooltip(ed);
			});
			*/
		},
		
		/**
		* Creates control instances based in the incomming name. This method is normally not
		* needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		* but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		* method can be used to create those.
		*
		* @param {String} n Name of the control to create.
		* @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		* @return {tinymce.ui.Control} New control instance or null if no control was created.
		*/
		createControl : function(n, cm) {
			return null;
		},
	});
		
	// Register plugin
	tinymce.PluginManager.add( 'trx_addons', tinymce.plugins.Trx_addons );
})();


// Add tooltip to the selected text
function trx_addons_editor_tooltip(ed) {
	//ed.insertContent('&nbsp;<strong>Menu item 1 here!</strong>&nbsp;');
	var selected_text = ed.selection.getContent();
	if (selected_text) {
		var tooltip = prompt("Enter tooltip text", '');
		if (tooltip) {
			ed.execCommand('mceInsertContent', 0, '<span class="trx_addons_tooltip" data-tooltip="' + tooltip.replace(/"/g, "''") + '">' + selected_text + '</span>');
			//or
			//ed.insertContent('<span class="trx_addons_dropcap">' + selected_text + '</span>');
		} else {
			alert(TRX_ADDONS_EDITOR['error_empty_value']);
		}
	} else  {
		alert(TRX_ADDONS_EDITOR['error_text_not_selected']);
	}
}