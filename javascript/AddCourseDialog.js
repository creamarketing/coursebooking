// signup end time enable?
jQuery(document).ready(function() {
	jQuery("#DialogDataObjectManager_Popup_AddForm_MainLocationID").change(function() {
		for (var i=0;i<7;i++) {
			var elemID = "#DialogDataObjectManager_Popup_AddForm_Rec-" + i + "-Location";
			var elemTextID = "#DialogDataObjectManager_Popup_AddForm_Rec-" + i + "-LocationText";
			
			jQuery(elemID).val(jQuery(this).val());
			jQuery(elemTextID).val(jQuery("#DialogDataObjectManager_Popup_AddForm_MainLocationIDSelect option:selected").text());
		}
	});

	jQuery("#DialogDataObjectManager_Popup_AddForm_MainTeacher").change(function() {
		for (var i=0;i<7;i++) {
			var elemID = "#DialogDataObjectManager_Popup_AddForm_Rec-" + i + "-Teacher";
			var elemTextID = "#DialogDataObjectManager_Popup_AddForm_Rec-" + i + "-TeacherText";
			
			jQuery(elemID).val(jQuery(this).val());		
			jQuery(elemTextID).val(jQuery("#DialogDataObjectManager_Popup_AddForm_MainTeacherSelect option:selected").text());
		}
	});
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_MainTeacher2").change(function() {
		for (var i=0;i<7;i++) {
			var elemID = "#DialogDataObjectManager_Popup_AddForm_Rec-" + i + "-Teacher2";
			var elemTextID = "#DialogDataObjectManager_Popup_AddForm_Rec-" + i + "-Teacher2Text";
			
			jQuery(elemID).val(jQuery(this).val());		
			jQuery(elemTextID).val(jQuery("#DialogDataObjectManager_Popup_AddForm_MainTeacher2Select option:selected").text());
		}
	});	

	// Must click on day before fields are enabled
	jQuery("#DialogDataObjectManager_Popup_AddForm input.weekday-selection-checkbox").siblings('.weekday-row').hide();
	
	jQuery("#DialogDataObjectManager_Popup_AddForm input.weekday-selection-checkbox").click(function() {
		jQuery(this).siblings('.weekday-row').each(function () {
			if (jQuery(this).is(':visible'))
				jQuery(this).hide();
			else
				jQuery(this).show();
			
			top.SetIframeHeight();
		});
	});	

	// Auto calculate lessions
	jQuery(".weekday-selection-timefield").change(function() {
		var re = /([0-9])-Time/;
		var input_id = jQuery(this).attr('id');
		
		var matchArray = re.exec(input_id);
		if (matchArray.length) {
			var id = '#DialogDataObjectManager_Popup_AddForm_Rec-' + matchArray[1];
			var lessions = calculateNumberOfLessions(id + '-Time-Start', id + '-Time-End');
			
			jQuery(id + '-Lessions').val(lessions);
			jQuery(id + '-Teacher-Lessions').val(lessions);
			jQuery(id + '-Teacher2-Lessions').val(lessions);
		}
	});
	
	// Create summary
	
	jQuery("button#Form_AddCourseFormDialogTabs_CheckBookingButton").click(function() {
		
		var startDate = jQuery("#DialogDataObjectManager_Popup_AddForm_RecDateStart");
		var endDate = jQuery("#DialogDataObjectManager_Popup_AddForm_RecDateEnd");
		var regex=/^([0-2][0-9]|3[01]).(0[0-9]|1[0-2]).[0-9]{4}$/;

		var validStart = regex.test(startDate.val());
		var validEnd = regex.test(endDate.val());

		if (!validStart) 
			startDate.effect("highlight", { color: "#ff8080" }, 2000);
		if (!validEnd)
			endDate.effect("highlight", { color: "#ff8080" }, 2000);
		
		if (!validStart || !validEnd) 
			return;
		
		jQuery("button#Form_AddCourseFormDialogTabs_CheckBookingButton").attr("disabled", "disabled");
		jQuery("div#Form_AddCourseFormDialogTabs_DateCheckAjaxLoader").css("visibility", "visible");
		
		var queryData = jQuery("form#DialogDataObjectManager_Popup_AddForm").formSerialize();
		
		jQuery.ajax({
			url: 'admin/coursebooking/checkCourseDates',
			dataType: 'html',
			data: queryData,
			success: function(data) {
				jQuery("div#Form_AddCourseFormDialogTabs_SummaryBox").html(data);
				jQuery("button#Form_AddCourseFormDialogTabs_CheckBookingButton").removeAttr('disabled');
				jQuery("div#Form_AddCourseFormDialogTabs_DateCheckAjaxLoader").css("visibility", "hidden");
				
				//jQuery("div.dialogtabset").tabs("select", 1);
				//jQuery("div.dialogtabset").tabs("select", 2);
				jQuery("div#Form_AddCourseFormDialogTabs_SummaryBox").dialog({
					title: 'Summary',
					minWidth: 450,
					maxWidth: 450,
					width: 450,
					minHeight: 250,
					maxHeight: 250,
					height: 250,
					resizeable: false
				});
			},
			error: function() {
				jQuery("button#Form_AddCourseFormDialogTabs_CheckBookingButton").removeAttr('disabled');
				jQuery("div#Form_AddCourseFormDialogTabs_DateCheckAjaxLoader").css("visibility", "hidden");
				
				jQuery("div#Form_AddCourseFormDialogTabs_SummaryBox").html('Error');
			}
		})
	});


	// Stuff for summary
	
	jQuery("#DialogDataObjectManager_Popup_AddForm #TabSet").bind('tabsselect', function(event, ui) {
		updateSummaryTab();
	});	
});

// autofetch signup dates from term when selected
jQuery(document).ready(function() {

	jQuery("#DialogDataObjectManager_Popup_AddForm #SignupEnd").hide();

	jQuery("#DialogDataObjectManager_Popup_AddForm_SignupExpiresNot").click(function() {
		var checkbox = jQuery("#DialogDataObjectManager_Popup_AddForm #SignupEnd");
		
		if (checkbox.is(':visible'))
			checkbox.hide();
		else
			checkbox.show();
		
		top.SetIframeHeight();
	});
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_TermID").change(function() {
		var termID = jQuery(this).val();
		
		jQuery.ajax({
			url: 'admin/coursebooking/getSignupDatesFromTerm',
			dataType: 'json',
			data: { 
				'termID': termID 
			},
			success: function(data) {
				jQuery("#DialogDataObjectManager_Popup_AddForm_SignupStart-date").val(data['Start']['Date']);
				jQuery("#DialogDataObjectManager_Popup_AddForm_SignupStart-time").val(data['Start']['Time']);
				
				if (data['End']['Enabled'] == true) {
					var checkbox = jQuery('#DialogDataObjectManager_Popup_AddForm_SignupExpiresNot');
					if (checkbox.is(':checked'))
						checkbox.click();
				}
				
				jQuery("#DialogDataObjectManager_Popup_AddForm_SignupEnd-date").val(data['End']['Date']);
				jQuery("#DialogDataObjectManager_Popup_AddForm_SignupEnd-time").val(data['End']['Time']);
			}
		});
	});
	
	// changing term
	jQuery("#DialogDataObjectManager_Popup_AddForm_TermID").change(function () {
		var subjectID = parseInt(jQuery("#DialogDataObjectManager_Popup_AddForm_SubjectsFirst").val());
		var termID = parseInt(jQuery("#DialogDataObjectManager_Popup_AddForm_TermID").val());

		if (!isNaN(subjectID)) {
			jQuery.ajax({
				url: 'admin/coursebooking/getDefaultsFromCourseSubject',
				dataType: 'json',
				data: { 
					'subjectID': subjectID,
					'termID': termID
				},
				success: function(data) {
					jQuery("#DialogDataObjectManager_Popup_AddForm_CourseCode").val(data['CourseCodeNextID']);
				}
			});
		}
	});		
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_Subjects").change(function () {
		var subjectID = parseInt(jQuery("#DialogDataObjectManager_Popup_AddForm_SubjectsFirst").val());
		var termID = parseInt(jQuery("#DialogDataObjectManager_Popup_AddForm_TermID").val());
		
		if (!isNaN(subjectID)) {			
			jQuery.ajax({
				url: 'admin/coursebooking/getDefaultsFromCourseSubject',
				dataType: 'json',
				data: { 
					'subjectID': subjectID,
					'termID': termID
				},
				success: function(data) {
					jQuery("#DialogDataObjectManager_Popup_AddForm_CourseCode").val(data['CourseCodeNextID']);
					jQuery('#DialogDataObjectManager_Popup_AddForm_EducationAreaID').val(data['EducationArea']['ID']);
					jQuery('#DialogDataObjectManager_Popup_AddForm_EducationAreaIDText').val(data['EducationArea']['Text']);
					jQuery('#DialogDataObjectManager_Popup_AddForm_CourseMainClassID').val(data['CourseMainClass']['ID']);
					jQuery('#DialogDataObjectManager_Popup_AddForm_CourseMainClassIDText').val(data['CourseMainClass']['Text']);					
				}
			});
		}
	});
	
	// languages
	jQuery("#DialogDataObjectManager_Popup_AddForm div.fieldgroup.translationGroup").contents().find("div.fieldgroupField").hide();
	jQuery("#DialogDataObjectManager_Popup_AddForm_Languages").change(function () {
		var languages = jQuery(this).val();

		jQuery.ajax({
			url: 'admin/coursebooking/getLocalesForCourseLanguages',
			dataType: 'text',
			data: { 
				'languages': languages 
			},
			success: function(data) {
				if (data.length) {
					jQuery("#DialogDataObjectManager_Popup_AddForm div.fieldgroup.translationGroup").contents().find("div.fieldgroupField").hide();
					
					var language_array = data.split(',');
					
					for(var i=0;i<language_array.length;i++) {
						var langLocale = language_array[i];
						jQuery("#DialogDataObjectManager_Popup_AddForm_Name_" + langLocale).parent().show();
						jQuery("#DialogDataObjectManager_Popup_AddForm_CourseDescription_" + langLocale).parent().show();
						top.SetIframeHeight();
					}
				}
			}
		});
	});	
});

function calculateNumberOfLessions(startID, stopID) {
	var start = jQuery(startID).val().split(':');
	var end = jQuery(stopID).val().split(':');
	var result = 0;

	if (start.length == 2 && end.length == 2) {	
		var startDate = new Date(0, 0, 0, start[0], start[1], 0);
		var endDate = new Date(0, 0, 0, end[0], end[1], 0);
		var lessions = 0;
		result = endDate.getTime() - startDate.getTime();

		result /= 1000.0; // to seconds
		result /= 60; // to minutes
		
		lessions = result / 45.0;
		if (lessions > 2)
			result -= parseInt(parseInt(lessions-2) / 2) * 15;	// 15 minutes pause between every 2 lessions
		
		result /= 45.0; // to 45 min lessions
		
		// Fix decimals
		result *= 100;
		result = Math.floor(result);
		result /= 100;
		
		if (isNaN(result))
			result = 0;
	} 

	return result;
}

function updateSummaryTab() {
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Code").html(jQuery("#DialogDataObjectManager_Popup_AddForm_CourseCode").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Term").html(jQuery("#DialogDataObjectManager_Popup_AddForm_TermIDText").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-CourseUnit").html(jQuery("#DialogDataObjectManager_Popup_AddForm_CourseUnitIDText").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-EducationArea").html(jQuery("#DialogDataObjectManager_Popup_AddForm_EducationAreaIDText").val());
	
	var name_sv = jQuery("#DialogDataObjectManager_Popup_AddForm_Name_sv_SE").val();
	var name_fi = jQuery("#DialogDataObjectManager_Popup_AddForm_Name_fi_FI").val();
	var name_en = jQuery("#DialogDataObjectManager_Popup_AddForm_Name_en_US").val();
	
	var desc_sv = jQuery("#DialogDataObjectManager_Popup_AddForm_CourseDescription_sv_SE").val();
	var desc_fi = jQuery("#DialogDataObjectManager_Popup_AddForm_CourseDescription_fi_FI").val();
	var desc_en = jQuery("#DialogDataObjectManager_Popup_AddForm_CourseDescription_en_US").val();	
	
	var span_html = '';
	
	if (name_sv.length) {
		span_html += name_sv + "<br/>"
	}
	if (name_fi.length) {
		span_html += name_fi + "<br/>"
	}	
	if (name_en.length) {
		span_html += name_en + "<br/>"
	}		
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Name").html(span_html);
	
	span_html = "";
	
	if (desc_sv.length) {
		span_html += desc_sv + "<br/><br/>"
	}
	if (desc_fi.length) {
		span_html += desc_fi + "<br/><br/>"
	}	
	if (desc_en.length) {
		span_html += desc_en + "<br/><br/>"
	}		
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Desc").html(span_html);
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-CourseBooks").html(jQuery("#DialogDataObjectManager_Popup_AddForm_CourseBooks").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-MinParticipators").html(jQuery("#DialogDataObjectManager_Popup_AddForm_MinParticipators").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-MaxParticipators").html(jQuery("#DialogDataObjectManager_Popup_AddForm_MaxParticipators").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-CoursePrice").html(jQuery("#DialogDataObjectManager_Popup_AddForm_CoursePrice").val() + ' €');

	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-CourseStart").html(jQuery("#DialogDataObjectManager_Popup_AddForm_RecDateStart").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-CourseEnd").html(jQuery("#DialogDataObjectManager_Popup_AddForm_RecDateEnd").val());


	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-SignupStart").html(jQuery("#DialogDataObjectManager_Popup_AddForm_SignupStart-date").val() + " " + jQuery("#DialogDataObjectManager_Popup_AddForm_SignupStart-time").val());
	if (jQuery('#DialogDataObjectManager_Popup_AddForm_SignupExpiresNot').is(':checked')) {
		jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-SignupEnd").html('Non-stop');
	} 
	else
		jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-SignupEnd").html(jQuery("#DialogDataObjectManager_Popup_AddForm_SignupEnd-date").val() + " " + jQuery("#DialogDataObjectManager_Popup_AddForm_SignupEnd-time").val());

	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Location").html(jQuery("#DialogDataObjectManager_Popup_AddForm_MainLocationIDText").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Teacher").html(jQuery("#DialogDataObjectManager_Popup_AddForm_MainTeacherText").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Teacher2").html(jQuery("#DialogDataObjectManager_Popup_AddForm_MainTeacher2Text").val());
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-IncomeAccount").html(jQuery("#DialogDataObjectManager_Popup_AddForm_IncomeAccountIDText").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-ExpenseAccount").html(jQuery("#DialogDataObjectManager_Popup_AddForm_ExpenseAccountIDText").val());
	
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Subjects").html(jQuery("#DialogDataObjectManager_Popup_AddForm_SubjectsText").val());
	jQuery("#DialogDataObjectManager_Popup_AddForm_Summary-Languages").html(jQuery("#DialogDataObjectManager_Popup_AddForm_LanguagesText").val());	
}

function showCourseSubjects(request, response) {
	AdvancedDropdownField_showWithCheckbox('DialogDataObjectManager_Popup_AddForm_Subjects', request, response);
}

function selectCourseSubject(event, ui) {
	return AdvancedDropdownField_selectCheckbox('DialogDataObjectManager_Popup_AddForm_Subjects', event, ui);
}

function showCourseLanguages(request, response) {
	AdvancedDropdownField_showWithCheckbox('DialogDataObjectManager_Popup_AddForm_Languages', request, response);
}

function selectCourseLanguage(event, ui) {
	return AdvancedDropdownField_selectCheckbox('DialogDataObjectManager_Popup_AddForm_Languages', event, ui);
}
