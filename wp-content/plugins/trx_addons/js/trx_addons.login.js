/**
 * Login and Register
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

/* global jQuery:false */
/* global TRX_ADDONS_STORAGE:false */

jQuery(document).ready(function() {
	"use strict";

	// Forms validation
    //----------------------------------------------

	// Login form
	jQuery('form.trx_addons_popup_form_login:not(.inited)').addClass('inited').submit(function(e){
		"use strict";
		var rez = trx_addons_login_validate(jQuery(this));
		if (!rez)
			e.preventDefault();
		return rez;
	});
	
	// Registration form
	jQuery('form.trx_addons_popup_form_register:not(.inited)').addClass('inited').submit(function(e){
		"use strict";
		var rez = trx_addons_registration_validate(jQuery(this));
		if (!rez)
			e.preventDefault();
		return rez;
	});
});


// Login form
function trx_addons_login_validate(form) {
	"use strict";
	form.find('input').removeClass('trx_addons_field_error');
	var error = trx_addons_form_validate(form, {
		error_message_time: 4000,
		exit_after_first_error: true,
		rules: [
			{
				field: "log",
				min_length: { value: 1, message: TRX_ADDONS_STORAGE['msg_login_empty'] },
				max_length: { value: 60, message: TRX_ADDONS_STORAGE['msg_login_long'] }
			},
			{
				field: "pwd",
				min_length: { value: 4, message: TRX_ADDONS_STORAGE['msg_password_empty'] },
				max_length: { value: 60, message: TRX_ADDONS_STORAGE['msg_password_long'] }
			}
		]
	});
    if (TRX_ADDONS_STORAGE['login_via_ajax'] && !error) {
		jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
			action: 'trx_addons_login_user',
			nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
			redirect_to: form.find('#redirect_to').length == 1 ? form.find('#redirect_to').val() : '',
			remember: form.find('#rememberme').val(),
			user_log: form.find('#log').val(),
			user_pwd: form.find('#pwd').val()
		}).done(function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch(e) {
				rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
			}
			var result = form.find(".trx_addons_message_box").toggleClass("trx_addons_message_box_error", false).toggleClass("trx_addons_message_box_success", false);
			if (rez.error === '') {
				result.addClass("trx_addons_message_box_success").html(TRX_ADDONS_STORAGE['msg_login_success']);
				setTimeout(function() {
					if (rez.redirect_to != '') {
						location.href = rez.redirect_to;
					} else {
						location.reload();
					}
				}, 3000);
			} else {
				result.addClass("trx_addons_message_box_error").html(TRX_ADDONS_STORAGE['msg_login_error'] + (rez.error!==undefined ?  '<br>' + rez.error : ''));
			}
			result.fadeIn().delay(3000).fadeOut();
		});
	}
	return !TRX_ADDONS_STORAGE['login_via_ajax'] && !error;
}


// Registration form
function trx_addons_registration_validate(form) {
	"use strict";
	form.find('input').removeClass('trx_addons_field_error');
	var error = trx_addons_form_validate(form, {
		error_message_time: 4000,
		exit_after_first_error: true,
		rules: [
			{
				field: "agree",
				state: { value: 'checked', message: TRX_ADDONS_STORAGE['msg_not_agree'] }
			},
			{
				field: "log2",
				min_length: { value: 1, message: TRX_ADDONS_STORAGE['msg_login_empty'] },
				max_length: { value: 60, message: TRX_ADDONS_STORAGE['msg_login_long'] }
			},
			{
				field: "email",
				min_length: { value: 7, message: TRX_ADDONS_STORAGE['msg_email_not_valid'] },
				max_length: { value: 60, message: TRX_ADDONS_STORAGE['msg_email_long'] },
				mask: { value: TRX_ADDONS_STORAGE['email_mask'], message: TRX_ADDONS_STORAGE['msg_email_not_valid'] }
			},
			{
				field: "pwd2",
				min_length: { value: 4, message: TRX_ADDONS_STORAGE['msg_password_empty'] },
				max_length: { value: 60, message: TRX_ADDONS_STORAGE['msg_password_long'] }
			},
			{
				field: "pwd22",
				equal_to: { value: 'pwd2', message: TRX_ADDONS_STORAGE['msg_password_not_equal'] }
			}
		]
	});
	if (!error) {
		jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
			action: 'trx_addons_registration_user',
			nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
			redirect_to: form.find('#redirect_to').length == 1 ? form.find('#redirect_to').val() : '',
			user_name: 	form.find('#log2').val(),
			user_email: form.find('#email').val(),
			user_pwd: 	form.find('#pwd2').val()
		}).done(function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
			}
			var result = form.find(".trx_addons_message_box").toggleClass("trx_addons_message_box_error", false).toggleClass("trx_addons_message_box_success", false);
			if (rez.error === '') {
				result.addClass("trx_addons_message_box_success").html(TRX_ADDONS_STORAGE['msg_registration_success']);
				setTimeout(function() {
					if (rez.redirect_to != '') {
						location.href = rez.redirect_to;
					} else {
						jQuery('#trx_addons_login_popup .trx_addons_tabs_title_login > a').trigger('click');
					}
				}, 3000);
			} else {
				result.addClass("trx_addons_message_box_error").html(TRX_ADDONS_STORAGE['msg_registration_error'] + (rez.error!==undefined ?  '<br>' + rez.error : ''));
			}
			result.fadeIn().delay(3000).fadeOut();
		});
	}
	return false;
}
