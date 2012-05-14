jQuery(function() {	
	jQuery("#GenerateReportButton").click(function() {
		
		var participator = jQuery("#Form_ParticipatorReportsForm_Participator");
		var validParticipator = participator.val() > 0 ? true : false;
		
		if (!validParticipator)
			participator.siblings(".AdvancedDropdown").effect("highlight", {color: "#ff8080"}, 1000);		
		
		if (!validParticipator)
			return;
		
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		// add iframe container div containing the iframe to the body
		jQuery('body').append('<div id="ReportDialog" class="iframe_wrap" style="display:none;"><iframe id="ReportDialog_iframe" src="about:blank" frameborder="0" width="100%" height="100%" style="display: none"></iframe>' + ajaxLoader + '<iframe id="ReportDialog_iframe_downloader" src="about:blank" style="display: hidden; width: 0px; height: 0px; z-index: -1000"></iframe></div>');
		
		var domDialog = jQuery('#ReportDialog');
		var dialogTitle = ss.i18n._t('ParticipatorReports.TITLE', 'Participator courses report');
		var closeText = ss.i18n._t('TeacherHoursReports.CLOSE', 'Close');
		var pdfText = ss.i18n._t('TeacherHoursReports.SAVEASPDF', 'Save as PDF');
		var printText = ss.i18n._t('TeacherHoursReports.PRINT', 'Print');
		
		var buttonsOptions = {};
		buttonsOptions[closeText] = function() {
			jQuery(this).dialog('close');
		};
		buttonsOptions[pdfText] = function() {
			var queryData = jQuery("form#Form_ParticipatorReportsForm").formSerialize();
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
				
				var queryData = jQuery("form#Form_ParticipatorReportsForm").formSerialize();
								
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
	
	jQuery('#Form_ParticipatorReportsForm_Participator').change(function() {
		jQuery('#Form_ParticipatorReportsForm_ParticipatorReportType').trigger('change');
	});
	
	jQuery('#Form_ParticipatorReportsForm_ParticipatorReportType').change(function() {
		
		if (jQuery(this).val() == 'courserequest-invoice') {
			jQuery('#Term').hide();
			
			var queryData = 'participatorID=' + jQuery('#Form_ParticipatorReportsForm_Participator').val();
								
			jQuery.ajax({
					url: 'admin/coursebooking/getInvoicesForParticipator',
					dataType: 'json',
					data: queryData,
					success: function(data) {
						jQuery('#Form_ParticipatorReportsForm_InvoiceNumber').children().remove();
						for (var i=0; i < data.length; i++) {
							jQuery('#Form_ParticipatorReportsForm_InvoiceNumber').append(jQuery('<option></option>').attr('value', data[i].ID).text(data[i].Number));
						}
						jQuery('#InvoiceNumber').show();						
					}
			});
		} 
		else {
			jQuery('#InvoiceNumber').hide();
			jQuery('#Term').show();			
		}
	});
	
	
});