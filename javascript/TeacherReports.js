jQuery(function() {	
	jQuery('form#Form_TeacherReportsForm #Week').hide();
	
	jQuery('form#Form_TeacherReportsForm select[name="TeacherReportType"]').change(function() {
		var type = jQuery(this).val();
		if (type == 'agenda') {
			jQuery('form#Form_TeacherReportsForm #Week').show();
			jQuery('form#Form_TeacherReportsForm #Term').hide();
		} 
		else if (type == 'courses') {
			jQuery('form#Form_TeacherReportsForm #Week').hide();
			jQuery('form#Form_TeacherReportsForm #Term').show();			
		}
	});
	
	jQuery("#GenerateReportButton").click(function() {
		
		var teacher = jQuery("#Form_TeacherReportsForm_Teacher");
		var validTeacher = teacher.val() > 0 ? true : false;
		
		if (!validTeacher)
			teacher.siblings(".AdvancedDropdown").effect("highlight", {color: "#ff8080"}, 1000);		
		
		if (!validTeacher)
			return;
		
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		// add iframe container div containing the iframe to the body
		jQuery('body').append('<div id="ReportDialog" class="iframe_wrap" style="display:none;"><iframe id="ReportDialog_iframe" src="about:blank" frameborder="0" width="100%" height="100%" style="display: none"></iframe>' + ajaxLoader + '<iframe id="ReportDialog_iframe_downloader" src="about:blank" style="display: hidden; width: 0px; height: 0px; z-index: -1000"></iframe></div>');
		
		var domDialog = jQuery('#ReportDialog');
		var dialogTitle = ss.i18n._t('TeacherReports.TITLE', 'Teacher courses report');
		var closeText = ss.i18n._t('TeacherHoursReports.CLOSE', 'Close');
		var pdfText = ss.i18n._t('TeacherHoursReports.SAVEASPDF', 'Save as PDF');
		var printText = ss.i18n._t('TeacherHoursReports.PRINT', 'Print');
		
		var buttonsOptions = {};
		buttonsOptions[closeText] = function() {
			jQuery(this).dialog('close');
		};
		buttonsOptions[pdfText] = function() {
			var queryData = jQuery("form#Form_TeacherReportsForm").formSerialize();
			queryData += '&PDF=true';			
			
			jQuery("#ReportDialog_iframe_downloader").ready(function() {

			});

			jQuery("#ReportDialog_iframe_downloader").attr('src', 'admin/coursebooking/getReport?' + queryData);
		};		
		buttonsOptions[printText] = function() {
			try {
				var iframe = jQuery('#ReportDialog_iframe')[0].contentWindow;
				iframe.focus();
				iframe.print();				
			} catch (e) {
				alert("Sorry, unable to print!");
			}

		};				

		jQuery('#ReportDialog').dialog({
			width: 800, 
			height: 600,
			resizeable: false,
			draggable: false,
			modal: true,
			title: dialogTitle,
			buttons: buttonsOptions,
			close: function() {
				jQuery(this).remove();
			},
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
				
				var queryData = jQuery("form#Form_TeacherReportsForm").formSerialize();
								
				jQuery.ajax({
					url: 'admin/coursebooking/getReport',
					data: queryData,
					success: function(data) {
						var iframeContent = jQuery('#ReportDialog_iframe')[0].contentDocument;
						if (!iframeContent) 
							iframeContent = jQuery('#ReportDialog_iframe')[0].contentWindow.document;
						iframeContent.open();
						iframeContent.writeln(data);
						iframeContent.close();
						
						setTimeout("jQuery('#ReportDialog_iframe').attr('height', jQuery('#ReportDialog_iframe').contents().find('body').height() + 20);", 0);
						jQuery('#DialogAjaxLoader').hide(); 
						jQuery('#ReportDialog_iframe').show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					},
					error: function() {
						var iframeContent = jQuery('#ReportDialog_iframe')[0].contentDocument;
						if (!iframeContent) 
							iframeContent = jQuery('#ReportDialog_iframe')[0].contentWindow.document;
						iframeContent.open();
						iframeContent.writeln('Error');
						iframeContent.close();
						
						setTimeout("jQuery('#ReportDialog_iframe').attr('height', jQuery('#ReportDialog_iframe').contents().find('body').height() + 20);", 0);
						jQuery('#DialogAjaxLoader').hide(); 
						jQuery('#ReportDialog_iframe').show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					}
				});
			}
		});
	});
});