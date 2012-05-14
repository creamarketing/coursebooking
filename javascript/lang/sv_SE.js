if(typeof(ss) == 'undefined' || typeof(ss.i18n) == 'undefined') {
	if(typeof(console) != 'undefined') console.error('Class ss.i18n not defined');
} else {
	ss.i18n.addDictionary('sv_SE', {
		'TeacherHoursReports.CLOSE': 'Stäng',
		'TeacherHoursReports.SAVEASPDF': 'Spara som PDF',
		'TeacherHoursReports.PRINT': 'Skriv ut',
		'TeacherHoursReports.TITLE': 'Timrapport',
		
		'TeacherSalaryReports.LOCKALL': 'Lås utbetalningsmånader',
		'TeacherSalaryReports.UNLOCKALL': 'Lås upp utbetalningsmånader',
		'TeacherSalaryReports.TITLE': 'Lönerapport',
		
		'TeacherLaborContract.TITLE': 'Arbetsavtal',
		
		'CourseDataExport.TITLE': 'Kursdata',
		'CourseDataExport.SAVEASFILE': 'Spara som fil',
		
		'Account.TITLE_RESETPASSWORD': 'Lösenordsåterställning',
		'Account.SENDINGPASSWORDRESETLINK': 'Skickar lösenordsåterställningslänk',
		'Account.SENTPASSWORDRESETLINK': 'Lösenordsåterställningslänk har nu skickats till användarens epost-adress',
		'Account.ERRORSENDINGLINK': 'Kunde inte skicka lösenordsåterställningslänk till användarens epost-adress',
		'Account.TITLE_SENDACCOUNTINFO': 'Kontoinformation',
		'Account.SENDINGACCOUNTINFO': 'Skickar kontoinformation',
		'Account.SENTACCOUNTINFO': 'Kontoinformation har nu skickats till användarens epost-adress',
		'Account.ERRORSENDINGACCOUNTINFO': 'Kunde inte skicka kontoinformation till användarens epost-adress',

		'ParticipatorReports.TITLE': 'Deltagarrapport',
		'CourseReports.TITLE': 'Kursrapport',
		'TeacherReports.TITLE': 'Lärarrapport'
	});
}