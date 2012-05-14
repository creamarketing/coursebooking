jQuery(function() {
	jQuery('#ResetPasswordLink').click(function () {
		
		var title = ss.i18n._t('Account.TITLE_RESETPASSWORD', 'Reset password');
		var fieldSet = jQuery(this).parents('fieldset').filter(':first');
		var className = fieldSet.find('input[name="ctf[ClassName]"]').val();
		var childID = fieldSet.find('input[name="ctf[childID]"]').val();
		
		var buttonsOptions = {};
		
		buttonsOptions['Ok'] = function() {
			jQuery(this).dialog('close');
		};
				
		jQuery('#ResetPasswordDialog').dialog({
			modal: true,
			resizable: false,
			draggable: false,
			width: 200,
			height: 160,
			buttons: buttonsOptions,
			title: title,
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
				
				jQuery(this).html(ss.i18n._t('Account.SENDINGPASSWORDRESETLINK', 'Sending password reset link') + '...');
							
				jQuery.ajax({
					url: 'admin/coursebooking/sendPasswordResetLink',
					data: {
						'accountType': className,
						'accountID': childID
					},
					success: function(data) {
						jQuery('#ResetPasswordDialog').html(ss.i18n._t('Account.SENTPASSWORDRESETLINK', 'Password reset link sent') + '.');

						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					},
					error: function() {
						jQuery('#ResetPasswordDialog').html(ss.i18n._t('Account.ERRORSENDINGLINK', 'Unable to send password reset link') + '!');

						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					}
				});				
			}
		});
	});
	
	jQuery('#SendAccountInfo').click(function () {
		
		var title = ss.i18n._t('Account.TITLE_SENDACCOUNTINFO', 'Account information');
		var fieldSet = jQuery(this).parents('fieldset').filter(':first');
		var className = fieldSet.find('input[name="ctf[ClassName]"]').val();
		var childID = fieldSet.find('input[name="ctf[childID]"]').val();
		
		var buttonsOptions = {};
		
		buttonsOptions['Ok'] = function() {
			jQuery(this).dialog('close');
		};
				
		jQuery('#SendAccountInfoDialog').dialog({
			modal: true,
			resizable: false,
			draggable: false,
			width: 200,
			height: 160,
			buttons: buttonsOptions,
			title: title,
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
				
				jQuery(this).html(ss.i18n._t('Account.SENDINGACCOUNTINFO', 'Sending account information') + '...');
							
				jQuery.ajax({
					url: 'admin/coursebooking/sendAccountInformation',
					data: {
						'accountType': className,
						'accountID': childID
					},
					success: function(data) {
						jQuery('#SendAccountInfoDialog').html(ss.i18n._t('Account.SENTACCOUNTINFO', 'Account information sent') + '.');

						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					},
					error: function() {
						jQuery('#SendAccountInfoDialog').html(ss.i18n._t('Account.ERRORSENDINGACCOUNTINFO', 'Unable to send account information') + '!');

						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					}
				});				
			}
		});
	});	
});