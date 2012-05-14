// autofetch signup dates from term when selected
jQuery(document).ready(function() {

	if (jQuery("#DialogDataObjectManager_Popup_DetailForm_SignupExpiresNot").is(":checked"))
	{
		jQuery("#DialogDataObjectManager_Popup_DetailForm #SignupEnd").hide();
	}

	jQuery("#DialogDataObjectManager_Popup_DetailForm_SignupExpiresNot").click(function() {
		var checkbox = jQuery("#DialogDataObjectManager_Popup_DetailForm #SignupEnd");
		
		if (checkbox.is(':visible'))
			checkbox.hide();
		else
			checkbox.show();
			
		top.SetIframeHeight();
	});
});
