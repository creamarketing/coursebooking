if(typeof(ss) == 'undefined' || typeof(ss.i18n) == 'undefined') {
	if(typeof(console) != 'undefined') console.error('Class ss.i18n not defined');
} else {
	ss.i18n.addDictionary('sv_SE', {
		'SignupDialog.TITLE': 'Platsreservering',
		'SignupDialog.ACCEPT': 'Reservera',
		'SignupDialog.CANCEL': 'Avbryt',
		
		'SignupCancelDialog.TITLE': 'Platsannullering',
		'SignupCancelDialog.ACCEPT': 'Annullera',
		'SignupCancelDialog.CANCEL': 'Avbryt',		
		
		'ConfirmDialog.TITLE': 'Kursanmälnings bekräftelse',
		'ConfirmDialog.ACCEPT': 'Bekräfta',
		'ConfirmDialog.CANCEL': 'Avbryt',

		'CancelDialog.TITLE': 'Kursanmälnings annullering',
		'CancelDialog.ACCEPT': 'Annullera',
		'CancelDialog.CANCEL': 'Avbryt',
		
		'MyAgendaCalendar.LOADING': 'Laddar',
		'MyAgendaCalendar.PRINT': 'skriv ut',
		'MyAgendaCalendar.TODAY': 'idag',
		'MyAgendaCalendar.MONTH': 'månad',
		'MyAgendaCalendar.WEEK': 'vecka',
		'MyAgendaCalendar.DAY': 'dag',
		'MyAgendaCalendar.ALLDAY': 'hela dagen',
		'MyAgendaCalendar.MONTHNAMES': ['Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December'],
		'MyAgendaCalendar.MONTHNAMESSHORT': ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
		'MyAgendaCalendar.DAYNAMES': ['Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag'],
		'MyAgendaCalendar.DAYNAMESSHORT': ['Sö', 'Må', 'Ti', 'On', 'To', 'Fre', 'Lö'],
		'MyAgendaCalendar.WEEKNR': 'v'
	});
}