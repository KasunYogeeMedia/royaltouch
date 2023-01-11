//-------------------------------------------
// Meta Boxes manipulations
//-------------------------------------------
jQuery(document).ready(function() {
	"use strict";

	// jQuery Tabs
	jQuery('#good_wine_shop_override_options_tabs').tabs();

	// Toggle inherit button and cover
	jQuery('#good_wine_shop_override_options_tabs').on('click', '.good_wine_shop_override_options_inherit_lock,.good_wine_shop_override_options_inherit_cover', function (e) {
		"use strict";
		var parent = jQuery(this).parents('.good_wine_shop_override_options_item');
		var inherit = parent.hasClass('good_wine_shop_override_options_inherit_on');
		if (inherit) {
			parent.removeClass('good_wine_shop_override_options_inherit_on').addClass('good_wine_shop_override_options_inherit_off');
			parent.find('.good_wine_shop_override_options_inherit_cover').fadeOut().find('input[type="hidden"]').val('');
		} else {
			parent.removeClass('good_wine_shop_override_options_inherit_off').addClass('good_wine_shop_override_options_inherit_on');
			parent.find('.good_wine_shop_override_options_inherit_cover').fadeIn().find('input[type="hidden"]').val('inherit');
			
		}
		e.preventDefault();
		return false;
	});

    // Check for dependencies
    //-----------------------------------------------------------------------------
    function good_wine_shop_override_options_start_check_dependencies() {
        jQuery( '.good_wine_shop_override_options .good_wine_shop_override_options_section' ).each(
            function () {
                good_wine_shop_override_options_check_dependencies( jQuery( this ) );
            }
        );
    }

    // Check all inner dependencies
    jQuery( document ).ready( good_wine_shop_override_options_start_check_dependencies );
    // Check external dependencies (for example, "Page template" in the page edit mode)
    jQuery( window ).load( good_wine_shop_override_options_start_check_dependencies );
	jQuery('.good_wine_shop_override_options .good_wine_shop_override_options_item_field [name^="good_wine_shop_override_options_field_"]').on('change', function () {
		"use strict";
		good_wine_shop_override_options_check_dependencies(jQuery(this).parents('.good_wine_shop_override_options_section'));
	});

});

// Return value of the field
function good_wine_shop_override_options_get_field_value(fld, num) {
    "use strict";
    var ctrl = fld.parents( '.good_wine_shop_override_options_item_field' );
    var val  = fld.attr( 'type' ) == 'checkbox' || fld.attr( 'type' ) == 'radio'
        ? (ctrl.find( '[name^="good_wine_shop_override_options_field_"]:checked' ).length > 0
                ? (num === true
                        ? ctrl.find( '[name^="good_wine_shop_override_options_field_"]:checked' ).parent().index() + 1
                        : (ctrl.find( '[name^="good_wine_shop_override_options_field_"]:checked' ).val() !== ''
                            && '' + ctrl.find( '[name^="good_wine_shop_override_options_field_"]:checked' ).val() != '0'
                                ? ctrl.find( '[name^="good_wine_shop_override_options_field_"]:checked' ).val()
                                : 1
                        )
                )
                : 0
        )
        : (num === true ? fld.find( ':selected' ).index() + 1 : fld.val());
    if (val === undefined || val === null) {
        val = '';
    }
    return val;
}

// Check for dependencies
function good_wine_shop_override_options_check_dependencies(cont) {
    "use strict";
    cont.find( '.good_wine_shop_override_options_item_field' ).each(
        function() {
            var ctrl = jQuery( this ), id = ctrl.data( 'param' );
            if (id === undefined) {
                return;
            }
            var depend = false;
            for (var fld in good_wine_shop_dependencies) {
                if (fld == id) {
                    depend = good_wine_shop_dependencies[id];
                    break;
                }
            }
            if (depend) {
                var dep_cnt    = 0, dep_all = 0;
                var dep_cmp    = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
                var dep_strict = typeof depend.strict != 'undefined';
                var fld        = null, val = '', name = '', subname = '';
                var parts      = '', parts2 = '';
                for (var i in depend) {
                    if (i == 'compare' || i == 'strict') {
                        continue;
                    }
                    dep_all++;
                    name    = i;
                    subname = '';
                    if (name.indexOf( '[' ) > 0) {
                        parts   = name.split( '[' );
                        name    = parts[0];
                        subname = parts[1].replace( ']', '' );
                    }
                    if (name.charAt( 0 ) == '#' || name.charAt( 0 ) == '.') {
                        fld = jQuery( name );
                        if (fld.length > 0 && ! fld.hasClass( 'good_wine_shop_inited' )) {
                            fld.addClass( 'good_wine_shop_inited' ).on(
                                'change', function () {
                                    jQuery( '.good_wine_shop_override_options .good_wine_shop_override_options_section' ).each(
                                        function () {
                                            good_wine_shop_override_options_check_dependencies( jQuery( this ) );
                                        }
                                    );
                                }
                            );
                        }
                    } else {
                        fld = cont.find( '[name="good_wine_shop_override_options_field_' + name + '"]' );
                    }
                    if (fld.length > 0) {
                        val = good_wine_shop_override_options_get_field_value( fld );
                        if (subname !== '') {
                            parts = val.split( '|' );
                            for (var p = 0; p < parts.length; p++) {
                                parts2 = parts[p].split( '=' );
                                if (parts2[0] == subname) {
                                    val = parts2[1];
                                }
                            }
                        }
                        for (var j in depend[i]) {
                            if (
                                (depend[i][j] == 'not_empty' && val !== '')   // Main field value is not empty - show current field
                                || (depend[i][j] == 'is_empty' && val === '') // Main field value is empty - show current field
                                || (val !== '' && ( ! isNaN( depend[i][j] )   // Main field value equal to specified value - show current field
                                            ? val == depend[i][j]
                                            : (dep_strict
                                                    ? val == depend[i][j]
                                                    : ('' + val).indexOf( depend[i][j] ) == 0
                                            )
                                    )
                                )
                                || (val !== '' && ("" + depend[i][j]).charAt( 0 ) == '^' && ('' + val).indexOf( depend[i][j].substr( 1 ) ) == -1)
                            // Main field value not equal to specified value - show current field
                            ) {
                                dep_cnt++;
                                break;
                            }
                        }
                    } else {
                        dep_all--;
                    }
                    if (dep_cnt > 0 && dep_cmp == 'or') {
                        break;
                    }
                }
                if (((dep_cnt > 0 || dep_all == 0) && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
                    ctrl.parents( '.good_wine_shop_override_options_item' ).show().removeClass( 'good_wine_shop_override_options_no_use' );
                } else {
                    ctrl.parents( '.good_wine_shop_override_options_item' ).hide().addClass( 'good_wine_shop_override_options_no_use' );
                }
            }

            // Individual dependencies
            //------------------------------------

            // Remove 'false' to disable color schemes less then main scheme!
            // This behavious is not need for the version with sorted schemes (leave false)
            if (false && id == 'color_scheme') {
                fld = ctrl.find( '[name="good_wine_shop_override_options_field_' + id + '"]' );
                if (fld.length > 0) {
                    val     = good_wine_shop_override_options_get_field_value( fld );
                    var num = good_wine_shop_override_options_get_field_value( fld, true );
                    cont.find( '.good_wine_shop_override_options_item_field' ).each(
                        function() {
                            var ctrl2 = jQuery( this ), id2 = ctrl2.data( 'param' );
                            if (id2 == undefined) {
                                return;
                            }
                            if (id2 == id || id2.substr( -7 ) != '_scheme') {
                                return;
                            }
                            var fld2 = ctrl2.find( '[name="good_wine_shop_override_options_field_' + id2 + '"]' ),
                                val2     = good_wine_shop_override_options_get_field_value( fld2 );
                            if (fld2.attr( 'type' ) != 'radio') {
                                fld2 = fld2.find( 'option' );
                            }
                            fld2.each(
                                function(idx2) {
                                    var dom_obj      = jQuery( this ).get( 0 );
                                    dom_obj.disabled = idx2 != 0 && idx2 < num;
                                    if (dom_obj.disabled) {
                                        if (jQuery( this ).val() == val2) {
                                            if (fld2.attr( 'type' ) == 'radio') {
                                                fld2.each(
                                                    function(idx3) {
                                                        jQuery( this ).get( 0 ).checked = idx3 == 0;
                                                    }
                                                );
                                            } else {
                                                fld2.each(
                                                    function(idx2) {
                                                        jQuery( this ).get( 0 ).selected = idx3 == 0;
                                                    }
                                                );
                                            }
                                        }
                                    }
                                }
                            );
                        }
                    );
                }
            }
        }
    );
}
