jQuery(function() {
	jQuery('tr.course').click(function() {
		
		jQuery(this).siblings('.selected').click();

		var desc = jQuery(this).next();
	
		jQuery(this).toggleClass('selected');
		desc.toggleClass('selected');
		
		desc.is(':hidden') ? desc.show() : desc.hide();
	});
	
	jQuery('button.signupButton').click(function() {
		var courseID = jQuery(this).attr('id').replace('Signup_', '');
		
		var contentDiv = '<div id="DialogContent"></div>';
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		jQuery('body').append('<div id="Dialog_Course' + courseID + '">' + contentDiv + ajaxLoader + '</div>');
		
		var dialogTitle = ss.i18n._t('SignupDialog.TITLE', 'Course signup');
	
		var buttonsOptions = {};
		var acceptText = ss.i18n._t('SignupDialog.ACCEPT', 'Accept');
		var cancelText = ss.i18n._t('SignupDialog.CANCEL', 'Cancel');
		
		buttonsOptions[acceptText] = function() {
			jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');

			jQuery.ajax({
				url: 'signup/postSignup',
				data: {
					'courseID': courseID,
					'locale': ss.i18n.getLocale()
				}, 
				type: 'POST',
				success: function(data) {
					var dialog = jQuery('#Dialog_Course' + courseID);
					dialog.dialog('close');
					window.location.reload(true);
				},
				error: function() {
					var dialog = jQuery('#Dialog_Course' + courseID);
					jQuery('#DialogAjaxLoader', dialog).hide(); 
					jQuery('#DialogContent', dialog).html('Error!');
					dialog.show();
					jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
				}
			});
		};

		buttonsOptions[cancelText] = function() {
			jQuery(this).dialog('close');
		};

		jQuery('div#Dialog_Course' + courseID).dialog({
			title: dialogTitle,
			modal: true,
			draggable: false,
			resizeable: false,
			height: 300,
			width: 500,
			buttons: buttonsOptions,
			close: function() {
				jQuery(this).remove();
			},
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
											
				jQuery.ajax({
					url: 'signup/preSignup',
					data: {
						'courseID': courseID,
						'locale': ss.i18n.getLocale()
					}, 
					type: 'POST',
					success: function(data) {
						var dialog = jQuery('#Dialog_Course' + courseID);
						jQuery('#DialogAjaxLoader', dialog).hide(); 
						jQuery('#DialogContent', dialog).html(data);
						dialog.show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					},
					error: function() {
						var dialog = jQuery('#Dialog_Course' + courseID);
						jQuery('#DialogAjaxLoader', dialog).hide(); 
						jQuery('#DialogContent', dialog).html('Error!');
						dialog.show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					}
				});
			}			
		});
	});
	
	jQuery('button.cancelButton').click(function() {
		var courseID = jQuery(this).attr('id').replace('Cancel_', '');
		
		var contentDiv = '<div id="DialogContent"></div>';
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		jQuery('body').append('<div id="Dialog_Course' + courseID + '">' + contentDiv + ajaxLoader + '</div>');
		
		var dialogTitle = ss.i18n._t('SignupCancelDialog.TITLE', 'Course cancel');
	
		var buttonsOptions = {};
		var acceptText = ss.i18n._t('SignupCancelDialog.ACCEPT', 'Accept');
		var cancelText = ss.i18n._t('SignupCancelDialog.CANCEL', 'Cancel');
		
		buttonsOptions[acceptText] = function() {
			jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');

			jQuery.ajax({
				url: 'signup/postSignupCancel',
				data: {
					'courseID': courseID,
					'locale': ss.i18n.getLocale()
				}, 
				type: 'POST',
				success: function(data) {
					var dialog = jQuery('#Dialog_Course' + courseID);
					dialog.dialog('close');
					window.location.reload(true);
				},
				error: function() {
					var dialog = jQuery('#Dialog_Course' + courseID);
					jQuery('#DialogAjaxLoader', dialog).hide(); 
					jQuery('#DialogContent', dialog).html('Error!');
					dialog.show();
					jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
				}
			});
		};

		buttonsOptions[cancelText] = function() {
			jQuery(this).dialog('close');
		};

		jQuery('div#Dialog_Course' + courseID).dialog({
			title: dialogTitle,
			modal: true,
			draggable: false,
			resizeable: false,
			height: 300,
			width: 500,
			buttons: buttonsOptions,
			close: function() {
				jQuery(this).remove();
			},
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
											
				jQuery.ajax({
					url: 'signup/preSignupCancel',
					data: {
						'courseID': courseID,
						'locale': ss.i18n.getLocale()
					}, 
					type: 'POST',
					success: function(data) {
						var dialog = jQuery('#Dialog_Course' + courseID);
						jQuery('#DialogAjaxLoader', dialog).hide(); 
						jQuery('#DialogContent', dialog).html(data);
						dialog.show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					},
					error: function() {
						var dialog = jQuery('#Dialog_Course' + courseID);
						jQuery('#DialogAjaxLoader', dialog).hide(); 
						jQuery('#DialogContent', dialog).html('Error!');
						dialog.show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					}
				});
			}			
		});
	});
	
	
	jQuery('.googleMapsLink').click(function () {
		window.open(this.href);
		return false;
	});
});