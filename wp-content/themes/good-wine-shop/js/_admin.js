/* global jQuery:false */
/* global GOOD_WINE_SHOP_STORAGE:false */

jQuery(document).ready(function() {
	"use strict";
	// Init Media manager variables
	GOOD_WINE_SHOP_STORAGE['media_id'] = '';
	GOOD_WINE_SHOP_STORAGE['media_frame'] = [];
	GOOD_WINE_SHOP_STORAGE['media_link'] = [];
	jQuery('.good_wine_shop_media_selector').on('click', function(e) {
		good_wine_shop_show_media_manager(this);
		e.preventDefault();
		return false;
	});

	// Standard WP Color Picker
	if (jQuery('.good_wine_shop_color_selector').length > 0) {
		jQuery('.good_wine_shop_color_selector').wpColorPicker({
			// you can declare a default color here,
			// or in the data-default-color attribute on the input

			// a callback to fire whenever the color changes to a valid color
			change: function(e, ui){
				jQuery(e.target).val(ui.color).trigger('change');
			},
	
			// a callback to fire when the input is emptied or an invalid color
			clear: function(e) {
				jQuery(e.target).prev().trigger('change')
			},
		});
	}
});

function good_wine_shop_show_media_manager(el) {
	"use strict";

	GOOD_WINE_SHOP_STORAGE['media_id'] = jQuery(el).attr('id');
	GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']] ) {
		GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']].open();
		return false;
	}

	// Create the media frame.
	GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']] = wp.media({
		// Popup layout (if comment next row - hide filters and image sizes popups)
		frame: 'post',
		// Set the title of the modal.
		title: GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('type') ? GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('type') : 'image'
		},
		// Multiple choise
		multiple: GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']].on( 'insert select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('linked-field')).eq(0);
		var attachment = null, attachment_url = '';
		if (GOOD_WINE_SHOP_STORAGE['media_link'][GOOD_WINE_SHOP_STORAGE['media_id']].data('multiple')===true) {
			GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']].state().get('selection').map( function( att ) {
				attachment_url += (attachment_url ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment_url = val + (val ? "\n" : '') + attachment_url;
		} else {
			attachment = GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']].state().get('selection').first().toJSON();
			attachment_url = attachment.url;
			var sizes_selector = jQuery('.media-modal-content .attachment-display-settings select.size');
			if (sizes_selector.length > 0) {
				var size = good_wine_shop_get_listbox_selected_value(sizes_selector.get(0));
				if (size != '') attachment_url = attachment.sizes[size].url;
			}
		}
		field.val(attachment_url);
		if (attachment_url.indexOf('.jpg') > 0 || attachment_url.indexOf('.png') > 0 || attachment_url.indexOf('.gif') > 0) {
			var preview = field.siblings('.good_wine_shop_override_options_field_preview');
			if (preview.length != 0) {
				if (preview.find('img').length == 0)
					preview.append('<img src="'+attachment_url+'">');
				else 
					preview.find('img').attr('src', attachment_url);
			} else {
				preview = field.siblings('img');
				if (preview.length != 0)
					preview.attr('src', attachment_url);
			}
		}
		field.trigger('change');
	});

	// Finally, open the modal.
	GOOD_WINE_SHOP_STORAGE['media_frame'][GOOD_WINE_SHOP_STORAGE['media_id']].open();
	return false;
}
