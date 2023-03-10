/**
 * Shortcode Icons
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4
 */

/* global jQuery:false, TRX_ADDONS_STORAGE:false */

// Init handlers
jQuery(document).on('action.init_shortcodes', function(e, container) {
	"use strict";
	
	var time = 50;
	container.find('.sc_icon_type_svg:not(.inited)').each(function(idx) {
		"use strict";
		var cont = jQuery(this);
		var id = cont.addClass('inited').attr('id');
		if (id === undefined) {
			id = 'sc_icons_'+Math.random();
			id = id.replace('.', '');
		} else
			id += '_'+idx;
		cont.find('svg').attr('id', id);
		setTimeout( function(){
			cont.css('visibility', 'visible');
			var obj = new Vivus(id, {type: 'async', duration: 80});
			cont.data('svg_obj', obj);
			cont.parent().hover(
				function() {
					cont.data('svg_obj').reset().play();
				},
				function() {
				}
			);
		}, time);
		time += 500;
	});
});
