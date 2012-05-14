jQuery(function() {	
	jQuery("input.time").change(function() {
		var editForm = jQuery(this).attr('id').search("DetailForm") != -1 ? true : false;
		
		if (editForm) {
			var lessions = calculateNumberOfLessions('#DialogDataObjectManager_Popup_DetailForm_TimeStart-time', '#DialogDataObjectManager_Popup_DetailForm_TimeEnd-time');
			
			jQuery('#DialogDataObjectManager_Popup_DetailForm_Lessions').val(lessions);
			jQuery('#DialogDataObjectManager_Popup_DetailForm_TeacherLessions').val(lessions);
			jQuery('#DialogDataObjectManager_Popup_DetailForm_Teacher2Lessions').val(lessions);
		} else {
			var lessions = calculateNumberOfLessions('#DialogDataObjectManager_Popup_AddForm_TimeStart-time', '#DialogDataObjectManager_Popup_AddForm_TimeEnd-time');
			
			jQuery('#DialogDataObjectManager_Popup_AddForm_Lessions').val(lessions);
			jQuery('#DialogDataObjectManager_Popup_AddForm_TeacherLessions').val(lessions);
			jQuery('#DialogDataObjectManager_Popup_AddForm_Teacher2Lessions').val(lessions);
		}
	});	
});

function calculateNumberOfLessions(startID, stopID) {
	var start = jQuery(startID).val().split(':');
	var end = jQuery(stopID).val().split(':');
	var result = 0;

	if (start.length == 2 && end.length == 2) {	
		var startDate = new Date(0, 0, 0, start[0], start[1], 0);
		var endDate = new Date(0, 0, 0, end[0], end[1], 0);
		result = endDate.getTime() - startDate.getTime();

		result /= 1000.0; // to seconds
		result /= 60; // to minutes
		result /= 45.0; // to 45 min lessions
		
		lessions = result / 45.0;
		if (lessions > 2)
			result -= parseInt(parseInt(lessions-2) / 2) * 15;	// 15 minutes pause between every 2 lessions		
		
		// Fix decimals
		result *= 100;
		result = Math.floor(result);
		result /= 100;
		
		if (isNaN(result))
			result = 0;
	} 

	return result;
}
