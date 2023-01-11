// Called from the main 'ready' event
jQuery(document).on('action.ready', function(e) {
    "use strict";
	// Slide effect for main menu
	if (GOOD_WINE_SHOP_STORAGE['menu_hover']=='slide_line' || GOOD_WINE_SHOP_STORAGE['menu_hover']=='slide_box') {
		setTimeout(function() {
			"use strict";
			jQuery('#menu_main').spasticNav({
				style: GOOD_WINE_SHOP_STORAGE['menu_hover']=='slide_line' ? 'line' : 'box',
				color: GOOD_WINE_SHOP_STORAGE['menu_hover_color'],
				colorOverride: false
			});
		}, 500);
	}
});

// Buttons decoration (add 'hover' class)
// Attention! Not use cont.find('selector')! Use jQuery('selector') instead!
jQuery(document).on('action.init_hidden_elements', function(e, cont) {
    "use strict";
	if (GOOD_WINE_SHOP_STORAGE['button_hover']) {
		jQuery('button:not(.search_submit):not([class*="sc_button_hover_"]),\
				.theme_button:not([class*="sc_button_hover_"]),\
				.sc_item_button > a:not([class*="sc_button_hover_"]):not([class*="sc_button_simple"]),\
				.sc_form_field button:not([class*="sc_button_hover_"]),\
				.sc_price_link:not([class*="sc_button_hover_"]),\
				.sc_action_item_link:not([class*="sc_button_hover_"]),\
				.more-link:not([class*="sc_button_hover_"]),\
				.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_"]),\
				.woocommerce .button:not([class*="sc_button_hover_"]),\
				.woocommerce-page .button:not([class*="sc_button_hover_"]),\
				#buddypress a.button:not([class*="sc_button_hover_"])'
				).addClass('sc_button_hover_'+GOOD_WINE_SHOP_STORAGE['button_hover']);
		if (GOOD_WINE_SHOP_STORAGE['button_hover']!='arrow') {
			jQuery('input[type="submit"]:not([class*="sc_button_hover_"]),\
					input[type="button"]:not([class*="sc_button_hover_"]),\
					.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_"]),\
					.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:not([class*="sc_button_hover_"]),\
					.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a,\
					.woocommerce nav.woocommerce-pagination ul li a:not([class*="sc_button_hover_"]),\
					.tribe-events-button:not([class*="sc_button_hover_"]),\
					.tribe-events-cal-links a:not([class*="sc_button_hover_"]),\
					.tribe-events-sub-nav li a:not([class*="sc_button_hover_"]),\
					.isotope_filters_button:not([class*="sc_button_hover_"]),\
					#btn-buy,\
					.trx_addons_scroll_to_top:not([class*="sc_button_hover_"]),\
					.socials_wrap:not(.socials_footer_wrap):not(.socials_type_drop) .social_icons:not([class*="sc_button_hover_"]),\
					.slider_prev:not([class*="sc_button_hover_"]), .slider_next:not([class*="sc_button_hover_"]),\
					.wp-block-tag-cloud > a:not([class*="sc_button_hover_"]),\
					.tagcloud > a:not([class*="sc_button_hover_"])'
					).addClass('sc_button_hover_'+GOOD_WINE_SHOP_STORAGE['button_hover']);
		}
		// Remove hover class
		jQuery('.mejs-controls button,\
				.widget_contacts .social_icons,\
				.menu_style_side .trx_addons_scroll_to_top').removeClass('sc_button_hover_'+GOOD_WINE_SHOP_STORAGE['button_hover']);

        // Remove class hover_xxx
        jQuery('.pswp__button').removeClass('sc_button_hover_'+ GOOD_WINE_SHOP_STORAGE['button_hover']);
	}
	
});

