// autofetch signup dates from term when selected
jQuery(document).ready(function() {

	if (jQuery("#DialogDataObjectManager_Popup_AddForm_SignupExpiresNot").is(":checked"))
	{
		jQuery("#DialogDataObjectManager_Popup_AddForm #SignupEnd").hide();
	}

	jQuery("#DialogDataObjectManager_Popup_AddForm_SignupExpiresNot").click(function() {
		var checkbox = jQuery("#DialogDataObjectManager_Popup_AddForm #SignupEnd");
		
		if (checkbox.is(':visible'))
			checkbox.hide();
		else 
			checkbox.show();
		
		top.SetIframeHeight();
	});
});
