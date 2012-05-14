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
	});
	
	jQuery("#DialogDataObjectManager_Popup_DetailForm_TermID").change(function() {
		var termID = jQuery(this).val();
		
		jQuery.ajax({
			url: 'admin/coursebooking/getSignupDatesFromTerm',
			dataType: 'json',
			data: { 
				'termID': termID 
			},
			success: function(data) {
				jQuery("#DialogDataObjectManager_Popup_DetailForm_SignupStart-date").val(data['Start']['Date']);
				jQuery("#DialogDataObjectManager_Popup_DetailForm_SignupStart-time").val(data['Start']['Time']);
				
				if (data['End']['Enabled'] == true) {
					var checkbox = jQuery('#DialogDataObjectManager_Popup_DetailForm_SignupExpiresNot');
					if (checkbox.is(':checked'))
						checkbox.click();
				}
				
				jQuery("#DialogDataObjectManager_Popup_DetailForm_SignupEnd-date").val(data['End']['Date']);
				jQuery("#DialogDataObjectManager_Popup_DetailForm_SignupEnd-time").val(data['End']['Time']);				
			}
		});
	});
	
	jQuery("#DialogDataObjectManager_Popup_DetailForm_Subjects").change(function () {
		var subjectID = parseInt(jQuery("#DialogDataObjectManager_Popup_DetailForm_SubjectsFirst").val());
		var termID = parseInt(jQuery("#DialogDataObjectManager_Popup_DetailForm_TermID").val());

		if (!isNaN(subjectID)) {
			jQuery.ajax({
				url: 'admin/coursebooking/getDefaultsFromCourseSubject',
				dataType: 'json',
				data: { 
					'subjectID': subjectID,
					'termID': termID
				},
				success: function(data) {
					jQuery("#DialogDataObjectManager_Popup_DetailForm_CourseCode").val(data['CourseCodeNextID']);
					jQuery('#DialogDataObjectManager_Popup_DetailForm_EducationAreaID').val(data['EducationArea']['ID']);
					jQuery('#DialogDataObjectManager_Popup_DetailForm_EducationAreaIDText').val(data['EducationArea']['Text']);
					jQuery('#DialogDataObjectManager_Popup_DetailForm_CourseMainClassID').val(data['CourseMainClass']['ID']);
					jQuery('#DialogDataObjectManager_Popup_DetailForm_CourseMainClassIDText').val(data['CourseMainClass']['Text']);					
				}
			});
		}
	});
	
	// changing term
	jQuery("#DialogDataObjectManager_Popup_DetailForm_TermID").change(function () {
		var subjectID = parseInt(jQuery("#DialogDataObjectManager_Popup_DetailForm_SubjectsFirst").val());
		var termID = parseInt(jQuery("#DialogDataObjectManager_Popup_DetailForm_TermID").val());

		if (!isNaN(subjectID)) {
			jQuery.ajax({
				url: 'admin/coursebooking/getDefaultsFromCourseSubject',
				dataType: 'json',
				data: { 
					'subjectID': subjectID,
					'termID': termID
				},
				success: function(data) {
					jQuery("#DialogDataObjectManager_Popup_DetailForm_CourseCode").val(data['CourseCodeNextID']);
				}
			});
		}
	});	
	
	// languages
	jQuery("#DialogDataObjectManager_Popup_DetailForm div.fieldgroup.translationGroup").contents().find("div.fieldgroupField").hide();
	jQuery("#DialogDataObjectManager_Popup_DetailForm_Languages").change(function () {
		var languages = jQuery(this).val();

		jQuery.ajax({
			url: 'admin/coursebooking/getLocalesForCourseLanguages',
			dataType: 'text',
			data: { 
				'languages': languages 
			},
			success: function(data) {
				//jQuery("#DialogDataObjectManager_Popup_DetailForm_CourseCode").val(data);
				//updateSummaryTab();
				if (data.length) {
					jQuery("#DialogDataObjectManager_Popup_DetailForm div.fieldgroup.translationGroup").contents().find("div.fieldgroupField").hide();
					
					var language_array = data.split(',');
					
					for(var i=0;i<language_array.length;i++) {
						var langLocale = language_array[i];
						jQuery("#DialogDataObjectManager_Popup_DetailForm_Name_" + langLocale).parent().show();
						jQuery("#DialogDataObjectManager_Popup_DetailForm_CourseDescription_" + langLocale).parent().show();
						top.SetIframeHeight();
					}
				}
			}
		});
	});	
	
	jQuery('#DialogDataObjectManager_Popup_DetailForm_DesiredLessions').change(function() {
		var desiredLessions = parseFloat(jQuery(this).val());
		var totalLessions = parseFloat(jQuery('#DialogDataObjectManager_Popup_DetailForm_TotalLessions').html());
		if (desiredLessions > 0 && desiredLessions != totalLessions)
			jQuery('#DialogDataObjectManager_Popup_DetailForm_TotalLessionsWarning').show();
		else
			jQuery('#DialogDataObjectManager_Popup_DetailForm_TotalLessionsWarning').hide();
	});
	
	// Trigger change in languages to show language fields
	jQuery("#DialogDataObjectManager_Popup_DetailForm_Languages").trigger('change');
	jQuery("#DialogDataObjectManager_Popup_DetailForm_DesiredLessions").trigger('change');
});

function showCourseSubjects(request, response) {
	AdvancedDropdownField_showWithCheckbox('DialogDataObjectManager_Popup_DetailForm_Subjects', request, response);
}

function selectCourseSubject(event, ui) {
	return AdvancedDropdownField_selectCheckbox('DialogDataObjectManager_Popup_DetailForm_Subjects', event, ui);
}

function showCourseLanguages(request, response) {
	AdvancedDropdownField_showWithCheckbox('DialogDataObjectManager_Popup_DetailForm_Languages', request, response);
}

function selectCourseLanguage(event, ui) {
	return AdvancedDropdownField_selectCheckbox('DialogDataObjectManager_Popup_DetailForm_Languages', event, ui);
}