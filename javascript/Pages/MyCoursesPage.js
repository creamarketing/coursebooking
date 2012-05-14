jQuery(function() {
	jQuery('tr.course').click(function() {
		
		jQuery(this).siblings('.selected').click();

		var desc = jQuery(this).next();
	
		jQuery(this).toggleClass('selected');
		desc.toggleClass('selected');
		
		desc.is(':hidden') ? desc.show() : desc.hide();
	});
	
	jQuery('#UnconfirmedCourses button.signupButton').click(function() {
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
	
	jQuery('#UnconfirmedCourses button.cancelButton').click(function() {
		var courseID = jQuery(this).attr('id').replace('Cancel_', '');
		
		var contentDiv = '<div id="DialogContent"></div>';
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		jQuery('body').append('<div id="Dialog_Course' + courseID + '">' + contentDiv + ajaxLoader + '</div>');
		
		var dialogTitle = ss.i18n._t('SignupCancelDialog.TITLE', 'Course signup');
	
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
			height: 250,
			width: 450,
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
	
	jQuery('#UnconfirmedCourses #ConfirmAllCourses').click(function() {	
		var contentDiv = '<div id="DialogContent"></div>';
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		jQuery('body').append('<div id="Dialog_ConfirmCourseRequests">' + contentDiv + ajaxLoader + '</div>');
		
		var dialogTitle = ss.i18n._t('ConfirmDialog.TITLE', 'Course request confirm');
	
		var buttonsOptions = {};
		var acceptText = ss.i18n._t('ConfirmDialog.ACCEPT', 'Accept');
		var cancelText = ss.i18n._t('ConfirmDialog.CANCEL', 'Cancel');
		
		buttonsOptions[acceptText] = function() {
			jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');

			jQuery.ajax({
				url: 'signup/postConfirm',
				data: {
					'locale': ss.i18n.getLocale()
				}, 
				type: 'POST',
				success: function(data) {
					var dialog = jQuery('#Dialog_ConfirmCourseRequests');
					dialog.dialog('close');
					window.location.reload(true);
				},
				error: function() {
					var dialog = jQuery('#Dialog_ConfirmCourseRequests');
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

		jQuery('#Dialog_ConfirmCourseRequests').dialog({
			title: dialogTitle,
			modal: true,
			draggable: false,
			resizeable: false,
			height: 300,
			width: 400,
			buttons: buttonsOptions,
			close: function() {
				jQuery(this).remove();
			},
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
											
				jQuery.ajax({
					url: 'signup/preConfirm',
					data: {
						'locale': ss.i18n.getLocale()
					}, 
					type: 'POST',
					success: function(data) {
						var dialog = jQuery('#Dialog_ConfirmCourseRequests');
						jQuery('#DialogAjaxLoader', dialog).hide(); 
						jQuery('#DialogContent', dialog).html(data);
						dialog.show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					},
					error: function() {
						var dialog = jQuery('#Dialog_ConfirmCourseRequests');
						jQuery('#DialogAjaxLoader', dialog).hide(); 
						jQuery('#DialogContent', dialog).html('Error!');
						dialog.show();
						jQuery(".ui-button:button").attr("disabled",false).removeClass('ui-state-disabled');
					}
				});
			}			
		});
	});

	jQuery('#ConfirmedCourses button.cancelButton').click(function() {
		var courseID = jQuery(this).attr('id').replace('Cancel_', '');
		
		var contentDiv = '<div id="DialogContent"></div>';
		var ajaxLoader = '<div id="DialogAjaxLoader"><h2>Loading...</h2><img src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." /></div>';
		jQuery('body').append('<div id="Dialog_Course' + courseID + '">' + contentDiv + ajaxLoader + '</div>');
		
		var dialogTitle = ss.i18n._t('CancelDialog.TITLE', 'Course cancellation');
	
		var buttonsOptions = {};
		var acceptText = ss.i18n._t('CancelDialog.ACCEPT', 'Accept');
		var cancelText = ss.i18n._t('CancelDialog.CANCEL', 'Cancel');
		
		buttonsOptions[acceptText] = function() {
			jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');

			jQuery.ajax({
				url: 'signup/postCourseCancel',
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
			width: 400,
			buttons: buttonsOptions,
			close: function() {
				jQuery(this).remove();
			},
			open: function() {
				jQuery(".ui-button:button").attr("disabled","disabled").addClass('ui-state-disabled');
											
				jQuery.ajax({
					url: 'signup/preCourseCancel',
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


	// Billing form
	jQuery('.billing-form').each(function(index) {	
		var formID = jQuery(this).attr('id');
		var statusID = formID + '_SaveStatus';
		jQuery('#' + formID + '_PostCode').numeric(false, function() {this.value = "";this.focus();});
		
		var options = {
			target: '#' + statusID,
			beforeSubmit: function(formData, jqForm, options) {
				jQuery('#' + statusID + " + .save-loader").css('display', 'inline-block');
				jQuery('#' + formID + "_SaveButton").attr('disabled', 'disabled');
				jQuery('#' + statusID).stop();
				jQuery('#' + statusID).css('display', 'none');
			},
			success: function(responseText, statusText, xhr, $form) {
				jQuery('#' + statusID + " + .save-loader").css('display', 'none');
				jQuery('#' + formID + "_SaveButton").removeAttr('disabled');
				jQuery('#' + statusID).css('display', 'inline-block');
				jQuery('#' + statusID).stop().css({opacity:1}).clearQueue();
				jQuery('#' + statusID).delay(4000).fadeOut(1000, function() { });
			}
		};
		
		jQuery(this).ajaxForm(options);
	});
	
	// Teacher message form
	jQuery('.teacher-message-form').each(function(index) {	
		var formID = jQuery(this).attr('id');
		var statusID = formID + '_SendStatus';
		
		var options = {
			target: '#' + statusID,
			beforeSubmit: function(formData, jqForm, options) {
				jQuery('#' + statusID + " + .send-loader").css('display', 'inline-block');
				jQuery('#' + formID + "_SendButton").attr('disabled', 'disabled');
				jQuery('#' + statusID).stop();
				jQuery('#' + statusID).css('display', 'none');
			},
			success: function(responseText, statusText, xhr, $form) {
				jQuery('#' + statusID + " + .send-loader").css('display', 'none');
				jQuery('#' + formID + "_SendButton").removeAttr('disabled');
				jQuery('#' + statusID).css('display', 'inline-block');
				jQuery('#' + statusID).stop().css({opacity:1}).clearQueue();
				jQuery('#' + statusID).delay(4000).fadeOut(1000, function() { });
				
				jQuery('#' + formID + "_IM_Subject").val('');
				jQuery('#' + formID + "_IM_Body").val('');
			}
		};
		
		jQuery(this).ajaxForm(options);
	});	

	// Teacher message form
	jQuery('.participators-message-form').each(function(index) {	
		var formID = jQuery(this).attr('id');
		var statusID = formID + '_SendStatus';
			
		var options = {
			target: '#' + statusID,
			beforeSubmit: function(formData, jqForm, options) {
				jQuery('#' + statusID + " + .send-loader").css('display', 'inline-block');
				jQuery('#' + formID + "_SendButton").attr('disabled', 'disabled');
				jQuery('#' + statusID).stop();
				jQuery('#' + statusID).css('display', 'none');
			},
			success: function(responseText, statusText, xhr, $form) {
				jQuery('#' + statusID + " + .send-loader").css('display', 'none');
				jQuery('#' + formID + "_SendButton").removeAttr('disabled');
				jQuery('#' + statusID).css('display', 'inline-block');
				jQuery('#' + statusID).stop().css({opacity:1}).clearQueue();
				jQuery('#' + statusID).delay(4000).fadeOut(1000, function() { });
				
				jQuery('#' + formID + "_IM_Subject").val('');
				jQuery('#' + formID + "_IM_Body").val('');
			}
		};
		
		jQuery(this).ajaxForm(options);
	});	

	// Google maps
	jQuery('.googleMapsLink').click(function () {
		window.open(this.href);
		return false;
	});
	
	// Print participators
	jQuery('button.print-participators').click(function () {
		var printButton = jQuery(this);
		var msgButton = jQuery(this).next('button');
		
		printButton.hide();
		msgButton.hide();
		
		var table = jQuery('<table id="resultsTable"></table>');
		
		table.append(jQuery(this).parents('.desc').filter(':first').parent().children('tr:first-child').clone());
		table.append(jQuery(this).parents('.desc').filter(':first').prev().clone());
		table.append(jQuery(this).parents('.desc').filter(':first').clone());
		table.find('.participators-message-form').prev().hide();
		table.find('.participators-message-form').next().hide();
		table.find('.participators-message-form').next().next().hide();
		table.find('.participators-message-form').next().next().next().hide();
				
		table.printElement({ 
			printMode: 'popup', 
			leaveOpen: true
		});		
		
		printButton.show();
		msgButton.show();
	});
	
	// Agenda
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	var eventURL = courseDatesURL; 

	// init i18n to get correct locale
	ss.i18n.init();
		
	jQuery('#MyAgendaCalendar').fullCalendar({
		events: eventURL,
		header: {
			left: 'prev,next today print',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		timeFormat: {
			agenda: 'HH:mm{ - HH:mm}',
			'':     'HH:mm'
		},
		columnFormat: {
			week: 'ddd d.M',
			day: 'dddd d.M'
		},		
		loading: CalendarLoading,
		axisFormat: 'HH:mm',
		minTime: 8,
		maxTime: 21,
		editable: false,
		selectable: false,
		allDaySlot: false,
		lazyFetching: false,
		aspectRatio: 1,
	    firstDay: 1,
		slotMinutes: 30,
		defaultView: 'agendaWeek',
		buttonText: {
			today: ss.i18n._t('MyAgendaCalendar.TODAY', 'today'),
		    month: ss.i18n._t('MyAgendaCalendar.MONTH', 'month'),
		    week:  ss.i18n._t('MyAgendaCalendar.WEEK', 'week'),
		    day:   ss.i18n._t('MyAgendaCalendar.DAY', 'day')
		},
		allDayText: ss.i18n._t('MyAgendaCalendar.ALLDAY', 'all-day'),
		monthNames: ss.i18n._t('MyAgendaCalendar.MONTHNAMES', ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']),
		monthNamesShort: ss.i18n._t('MyAgendaCalendar.MONTHNAMESSHORT', ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']),
		dayNames: ss.i18n._t('MyAgendaCalendar.DAYNAMES', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
		dayNamesShort: ss.i18n._t('MyAgendaCalendar.DAYNAMESSHORT', ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
		dayClick: function(date, allDay, jsEvent, view) {
	        if (allDay) {
	            jQuery('#MyAgendaCalendar').fullCalendar('gotoDate', date);
				jQuery('#MyAgendaCalendar').fullCalendar('changeView', 'agendaDay');
	        }
	    },
		viewDisplay: function(view) {
			// set aspect ratio depending on which view we are in
			if (view.name == 'month') {
				// month view needs bigger aspect ratio here, since it will grow too big otherwise
				jQuery('#MyAgendaCalendar').fullCalendar('option', 'aspectRatio', 2);
			}
			else {
				jQuery('#MyAgendaCalendar').fullCalendar('option', 'aspectRatio', 1);
			}
		},
		eventClick: function(calEvent, jsEvent, view) {
			if (view.name == 'month') {
				jQuery('#MyAgendaCalendar').fullCalendar('gotoDate', calEvent.start);
				jQuery('#MyAgendaCalendar').fullCalendar('changeView', 'agendaWeek');
			}
			else if (view.name == 'agendaWeek') {
				jQuery('#MyAgendaCalendar').fullCalendar('gotoDate', calEvent.start);
				jQuery('#MyAgendaCalendar').fullCalendar('changeView', 'agendaDay');				
			}
		},
		eventRender: function(event, element, view) {
			var content = "";
			if (view.name == "month") {
				content = event.title;
			}
			else {
				var hour = event.start.getHours();
				if (hour < 10) {
					hour = "0" + hour;
				}
				var minutes = event.start.getMinutes();
				if (minutes < 10) {
					minutes = "0" + minutes;
				}
				var startTime = hour + ":" + minutes;
				startHour = event.end.getHours();
				if (startHour < 10) {
					startHour = "0" + startHour;
				}
				hour = event.end.getHours();
				if (hour < 10) {
					hour = "0" + hour;
				}
				minutes = event.end.getMinutes();
				if (minutes < 10) {
					minutes = "0" + minutes;
				}
				var endTime = hour + ":" + minutes;
				content = startTime + " - " + endTime + "<br />" + event.title
				if (event.description) {
					content += "<br/><br/>" + event.description + "";
				}
				if (event.location) {
					content += "<br /><br/>" + event.location.name;
					content += "<br />" + event.location.address;
				}
			}			
			element.qtip({
				content: content,
				position: {
					corner: {
						target: 'topMiddle',
						tooltip: 'bottomLeft'
					},
					adjust: {
						screen: true
					}
				},
				style: {
					name: 'cream',
					tip: "bottomLeft"
				}
			});			
		}
	});
		
	var tm = 'fc';
	var button = jQuery('<span class="fc-button fc-button-print ' + tm + '-state-default fc-corner-left fc-corner-right">'+
							'<span class="fc-button-inner">' +
								'<span class="fc-button-content">' + ss.i18n._t('MyAgendaCalendar.PRINT', 'print') + '</span>'+
								'<span class="fc-button-effect"><span></span></span>'+
							'</span>'+
						'</span>');
	
	button.click(function() {
		if (!button.hasClass(tm + '-state-disabled')) {
			
			var cloned = jQuery('#MyAgendaCalendar').clone();
			cloned.find('.fc-header .fc-button').css('visibility', 'hidden');
			
			cloned.printElement({ 
				printMode: 'popup', 
				leaveOpen: true
			});
		}
	})
	.mousedown(function() {
		button
			.not('.' + tm + '-state-active')
			.not('.' + tm + '-state-disabled')
			.addClass(tm + '-state-down');
	})
	.mouseup(function() {
		button.removeClass(tm + '-state-down');
	})
	.hover(
		function() {
			button
				.not('.' + tm + '-state-active')
				.not('.' + tm + '-state-disabled')
				.addClass(tm + '-state-hover');
		},
		function() {
			button
				.removeClass(tm + '-state-hover')
				.removeClass(tm + '-state-down');
		}
	);
	jQuery('#MyAgendaCalendar .fc-header-left').append(button);
});

Date.prototype.getMDay = function () {
    return (this.getDay() + 6) % 7;
}
Date.prototype.getISOYear = function () {
    var thu = new Date(this.getFullYear(), this.getMonth(), this.getDate() + 3 - this.getMDay());
    return thu.getFullYear();
}
Date.prototype.getISOWeek = function () {
    var onejan = new Date(this.getISOYear(), 0, 1);
    var wk = Math.ceil((((this - onejan) / 86400000) + onejan.getMDay() + 1) / 7);
    if (onejan.getMDay() > 3) wk--; return wk;
}

function CalendarLoading(isLoading, view) {
	if (isLoading) {
		var selectedDate = jQuery('#MyAgendaCalendar').fullCalendar('getDate');
		var dateString = '<span class="fc-weeknumber" style="float: left">' + ss.i18n._t('MyAgendaCalendar.WEEKNR', 'w') + selectedDate.getISOWeek() + '</span>';
		
		// For months
		//var weekdayElement = jQuery('<div class="fc-day-weeknumber">' + dateString + '</div>');
		//weekdayElement.insertAfter('#MyAgendaCalendar div.fc-view-month table.fc-border-separate:first tbody tr fc-first fc-day-number');
		
		// For weeks
		jQuery('#MyAgendaCalendar div.fc-view-agendaWeek table.fc-agenda-days th.fc-agenda-axis.fc-widget-header.fc-first:first').html(dateString);
		
		// For days
		jQuery('#MyAgendaCalendar div.fc-view-agendaDay table.fc-agenda-days th.fc-agenda-axis.fc-widget-header.fc-first:first').html(dateString);
		
		var loadingText = ss.i18n._t('MyAgendaCalendar.LOADING', 'Loading');
		var blockElement = jQuery('#MyAgendaCalendar .fc-view table.fc-border-separate tbody');
		blockElement.block({message: '<h2 style="padding-top: 5px; padding-bottom: 5px; background-color: transparent;"><img style="vertical-align: middle; margin-right: 20px" src="dataobject_manager/images/ajax-loader-white.gif" alt="' + loadingText + '..." />' + loadingText + '...</h2>',
													css: {'background-color': '#fff'}});
	}
	else {
		var blockElement = jQuery('#MyAgendaCalendar .fc-view table.fc-border-separate tbody');
		blockElement.unblock();

		// remove all qtip elements on loading finished, to prevent ghost tooltips
		jQuery('.qtip').remove();
	}
}