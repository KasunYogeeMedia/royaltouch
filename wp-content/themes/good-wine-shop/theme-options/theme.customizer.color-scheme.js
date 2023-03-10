/* global good_wine_shop_color_schemes, good_wine_shop_dependencies, Color */

/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {
	"use strict";
	var cssTemplate = {},
		updateCSS = true;

	for (var i in good_wine_shop_color_schemes) {
		cssTemplate[i] = wp.template( 'good_wine_shop-color-scheme-'+i );
	}
	
	// Set initial state of controls
	api.bind('ready', function() {
		jQuery('#customize-theme-controls .control-section').each(function () {
			good_wine_shop_customizer_check_dependencies(jQuery(this));
		});
		good_wine_shop_customizer_change_color_scheme(api('color_scheme_editor')());
	});
	
	// On change any control - check for dependencies
	api.bind('change', function(obj) {
		if (obj.id == 'scheme_storage') return;
		good_wine_shop_customizer_check_dependencies(jQuery('#customize-theme-controls #customize-control-'+obj.id).parents('.control-section'));
		good_wine_shop_customizer_refresh_preview(obj);
	});

	// Check for dependencies
	function good_wine_shop_customizer_check_dependencies(cont) {
		"use strict";
		cont.find('.customize-control').each(function() {
			"use strict";
			var id = jQuery(this).attr('id');
			if (id == undefined) return;
			id = id.replace('customize-control-', '');
			var depend = false;
			for (var fld in good_wine_shop_dependencies) {
				if (fld == id) {
					depend = good_wine_shop_dependencies[id];
					break;
				}
			}
			if (depend) {
				var dep_cnt = 0, dep_all = 0;
				var dep_cmp = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
				var dep_strict = typeof depend.strict != 'undefined';
				var fld=null, val='';
				for (var i in depend) {
					if (i == 'compare' || i == 'strict') continue;
					dep_all++;
					fld = cont.find('[data-customize-setting-link="'+i+'"]');
					if (fld.length > 0) {
						val = fld.attr('type')=='checkbox' || fld.attr('type')=='radio' 
									? (fld.parents('.customize-control').find('[data-customize-setting-link]:checked').length > 0
										? fld.parents('.customize-control').find('[data-customize-setting-link]:checked').val()
										: 0
										)
									: fld.val();
						if (val===undefined) val = '';
						for (var j in depend[i]) {
							if ( 
								   (depend[i][j]=='not_empty' && val!='') 										// Main field value is not empty - show current field
								|| (depend[i][j]=='is_empty' && val=='')										// Main field value is empty - show current field
								|| (val!=='' && (!isNaN(depend[i][j]) 											// Main field value equal to specified value - show current field
													? val==depend[i][j]
													: (dep_strict 
															? val==depend[i][j]
															: val.indexOf(depend[i][j])==0
														)
												)
									)
								|| (val!='' && (''+depend[i][j]).charAt(0)=='^' && val.indexOf(depend[i][j].substr(1))==-1)	// Main field value not equal to specified value - show current field
							) {
								dep_cnt++;
								break;
							}
						}
					} else
						dep_all--;
					if (dep_cnt > 0 && dep_cmp == 'or')
						break;
				}
				if ((dep_cnt > 0 && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
					jQuery(this).show().removeClass('good_wine_shop_options_no_use');
				} else {
					jQuery(this).hide().addClass('good_wine_shop_options_no_use');
				}
			}
		});
	}

	// Refresh preview area on change any control
	function good_wine_shop_customizer_refresh_preview(obj) {
		"use strict";
		if (obj.transport!='postMessage') return;
		var id = obj.id, val = obj();
		var processed = false;
		// Update the CSS whenever a color setting is changed.
		if (id == 'color_scheme_editor') {
			good_wine_shop_customizer_change_color_scheme(val);
		} else if (updateCSS) {
			var simple = api('color_settings')()=='simple';
			for (var opt in good_wine_shop_color_schemes['default'].colors) {
				if (opt == id) {
					// Store new value in the color table
					good_wine_shop_customizer_update_color_scheme(opt, val);
					// Duplicate colors if simple
					if (simple) {
						if (id == 'text_link') {
							api('alter_link').set( val );
							api.control( 'alter_link' ).container.find( '.color-picker-hex' )
								.data( 'data-default-color', val )
								.wpColorPicker( 'defaultColor', val );
							good_wine_shop_customizer_update_color_scheme('alter_link', val);
						} else if (id == 'text_hover') {
							api('alter_hover').set( val );
							api.control( 'alter_hover' ).container.find( '.color-picker-hex' )
								.data( 'data-default-color', val )
								.wpColorPicker( 'defaultColor', val );
							good_wine_shop_customizer_update_color_scheme('alter_hover', val);
						}
					}
					processed = true;
					break;
				}
			}
			// Refresh CSS
			if (processed) good_wine_shop_customizer_update_css();
		}
		// Send message to previewer
		if (!processed) {
			api.previewer.send( 'refresh-other-controls', {id: id, value: val} );
		}
	}
	

	// Store new value in the color table
	function good_wine_shop_customizer_update_color_scheme(opt, value) {
		"use strict";
		good_wine_shop_color_schemes[api('color_scheme_editor')()].colors[opt] = value;
		api('scheme_storage').set(good_wine_shop_serialize(good_wine_shop_color_schemes))
	}
	

	// Change color scheme - update colors and generate css
	function good_wine_shop_customizer_change_color_scheme(value) {
		"use strict";
		updateCSS = false;
		for (var opt in good_wine_shop_color_schemes[value].colors) {
			if (api(opt) == undefined) continue;
			api( opt ).set( good_wine_shop_color_schemes[value].colors[opt] );
			api.control( opt ).container.find( '.color-picker-hex' )
				.data( 'data-default-color', good_wine_shop_color_schemes[value].colors[opt] )
				.wpColorPicker( 'defaultColor', good_wine_shop_color_schemes[value].colors[opt] );
		}
		updateCSS = true;
		good_wine_shop_customizer_update_css();
	}
	
	// Generate the CSS for the current Color Scheme and send it to the preview window
	function good_wine_shop_customizer_update_css() {
		"use strict";

		if (!updateCSS) return;
	
		var css = '';

		for (var scheme in good_wine_shop_color_schemes) {
			
			var colors = [];
			
			// Copy all colors!
			for (var i in good_wine_shop_color_schemes[scheme].colors) {
				colors[i] = good_wine_shop_color_schemes[scheme].colors[i];
			}
			
			// Make theme specific colors and tints
			if (window.good_wine_shop_customizer_add_theme_colors) colors = good_wine_shop_customizer_add_theme_colors(colors);

			// Make styles and add into css
			css += cssTemplate[scheme]( colors );
		}
		api.previewer.send( 'refresh-color-scheme-css', css );
	}

	// Add custom colors into color scheme
	// Attention! Don't forget setup custom colors also in the theme.styles.php
	function good_wine_shop_customizer_add_theme_colors(colors) {
		colors.text_dark_mask  = Color( colors.text_dark ).toCSS( 'rgba', 0.5 );
		colors.text_dark_alpha = Color( colors.text_dark ).toCSS( 'rgba', 0.2 );
		colors.text_link_alpha = Color( colors.text_link ).toCSS( 'rgba', 0.6 );
		colors.text_link_alpha_02 = Color( colors.text_link ).toCSS( 'rgba', 0.2 );
		colors.bd_color_alpha  = Color( colors.bd_color ).toCSS(  'rgba', 0.5 );
		colors.bg_color_alpha  = Color( colors.bg_color ).toCSS(  'rgba', 0.85 );
		colors.bg_color_alpha_0  = Color( colors.bg_color ).toCSS(  'rgba', 0 );
		return colors;
	}

} )( wp.customize );
