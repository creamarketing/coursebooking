<?php

global $lang;

if(array_key_exists('sv_SE', $lang) && is_array($lang['sv_SE'])) {
    $lang['sv_SE'] = array_merge($lang['en_US'], $lang['sv_SE']);
} else {
    $lang['sv_SE'] = $lang['en_US'];
}

$lang['sv_SE']['Pagination']['GO'] = 'Gå till';
$lang['sv_SE']['Pagination']['NEXT'] = 'Nästa';
$lang['sv_SE']['Pagination']['PREV'] = 'Föregående';

$lang['sv_SE']['CreaDefaultSelectable']['USEASDEFAULT'] = 'Använd som förhandsval';

$lang['sv_SE']['CourseBookingAdmin']['MENUTITLE'] = 'e-Kurs';
$lang['sv_SE']['CourseBookingAdmin']['WELCOMEMESSAGE'] = 'Välkommen till e-Kurs!<br />Vänligen välj önskad funktion till vänster.';

$lang['sv_SE']['CourseBookingAdmin']['OVERVIEW'] = 'Översikt';
$lang['sv_SE']['CourseBookingAdmin']['SHORTCUTS'] = 'Genvägar';

$lang['sv_SE']['CourseBookingAdmin']['SHOW_COURSES'] = 'Visa kurser';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_COURSELANGUAGES'] = 'Visa kursspråk';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_COURSEUNITS'] = 'Visa kursenheter';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_COURSESUBJECTS'] = 'Visa kursämnen';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_TERMS'] = 'Visa perioder';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_TEACHERS'] = 'Visa lärare';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_EDUCATIONAREAS'] = 'Visa utbildningsområden';
$lang['sv_SE']['CourseBookingAdmin']['SHOW_COURSEREQUESTS'] = 'Visa kursanmälningar';

$lang['sv_SE']['CourseBookingAdmin']['MANAGE'] = 'Hantering';
$lang['sv_SE']['CourseBookingAdmin']['MANAGE_COURSES'] = 'Hantera kurser';
$lang['sv_SE']['CourseBookingAdmin']['MANAGE_DATES'] = 'Hantera tidpunkter';
$lang['sv_SE']['CourseBookingAdmin']['MANAGE_PARTICIPATORS'] = 'Hantera deltagare';
$lang['sv_SE']['CourseBookingAdmin']['MANAGE_TEACHERS'] = 'Hantera lärare';
$lang['sv_SE']['CourseBookingAdmin']['MANAGE_REPORTS'] = 'Hantera rapporter';
$lang['sv_SE']['CourseBookingAdmin']['MANAGE_MISC'] = 'Hantera övriga';

$lang['sv_SE']['CourseBookingAdmin']['ADD_COURSE'] = 'Lägg till kurs';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSES'] = 'Redigera kurser';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSELANGUAGES'] = 'Redigera kursspråk';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSEUNITS'] = 'Redigera kursenheter';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSESUBJECTS'] = 'Redigera kursämnen';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_TERMS'] = 'Redigera perioder';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_TEACHERS'] = 'Redigera lärare';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_EDUCATIONAREAS'] = 'Redigera utbildningsområden';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSEREQUESTS'] = 'Redigera kursanmälningar';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSEPARTICIPATORS'] = 'Redigera kursdeltagare';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_SALARYCLASSES'] = 'Redigera löneklasser';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_HOURTYPES'] = 'Redigera timtyper';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSEDATES'] = 'Redigera kurstider';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_INCOMEACCOUNTS'] = 'Redigera inkomstkonton';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_EXPENSEACCOUNTS'] = 'Redigera konstnadskonton';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSETYPES'] = 'Redigera kurstyper';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_AGEGROUPS'] = 'Redigera åldersgrupper';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSEMAINCLASSES'] = 'Redigera huvudklasser';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_EDUCATIONTYPES'] = 'Redigera utbildningstyper';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_COURSEADMINS'] = 'Redigera kursadministratörer';
$lang['sv_SE']['CourseBookingAdmin']['TEACHERHOURSREPORTS'] = 'Timrapporter';
$lang['sv_SE']['CourseBookingAdmin']['TEACHERSALARYREPORTS'] = 'Lönerapporter';
$lang['sv_SE']['CourseBookingAdmin']['TEACHERLABORCONTRACT'] = 'Arbetsavtal';
$lang['sv_SE']['CourseBookingAdmin']['COURSEREPORTS'] = 'Kursrapporter';
$lang['sv_SE']['CourseBookingAdmin']['PARTICIPATORREPORTS'] = 'Deltagarrapporter';
$lang['sv_SE']['CourseBookingAdmin']['TEACHERREPORTS'] = 'Lärarrapporter';
$lang['sv_SE']['CourseBookingAdmin']['EDIT_EMPLOYERS'] = 'Redigera arbetsgivare';
$lang['sv_SE']['CourseBookingAdmin']['EXPORT_COURSEDATA'] = 'Exportera kursdata';

$lang['sv_SE']['CourseBooking']['NONESELECTED'] = '(Inget valt)';
$lang['sv_SE']['CourseBooking']['CREATECOURSEBUTTON'] = 'Skapa kurs';
$lang['sv_SE']['CourseBooking']['CHECKBOOKING'] = 'Kontrollera';
$lang['sv_SE']['Form']['FIELDISREQUIRED'] = '%s måste fyllas i';

$lang['sv_SE']['Object']['MAIN'] = 'Allmänt';
$lang['sv_SE']['Object']['YES'] = 'Ye';
$lang['sv_SE']['Object']['MAIN'] = 'Allmänt';

$lang['sv_SE']['Course']['SINGULARNAME'] = 'Kurs';
$lang['sv_SE']['Course']['PLURALNAME'] = 'Kurser';
$lang['sv_SE']['Course']['NAME'] = 'Kursnamn';
$lang['sv_SE']['Course']['COURSECODE'] = 'Kurskod';
$lang['sv_SE']['Course']['DESCRIPTION'] = 'Kursbeskrivning';
$lang['sv_SE']['Course']['COURSEBOOKS'] = 'Kurslitteratur';
$lang['sv_SE']['Course']['MINPARTICIPATORS'] = 'Min. deltagare';
$lang['sv_SE']['Course']['MAXPARTICIPATORS'] = 'Max. deltagare';
$lang['sv_SE']['Course']['PARTICIPATORS'] = 'Deltagare';
$lang['sv_SE']['Course']['PRICE'] = 'Kursavgift';
$lang['sv_SE']['Course']['COURSE_NAME_SV'] = 'Kursnamn (svenska)';
$lang['sv_SE']['Course']['COURSE_NAME_FI'] = 'Kursnamn (finska)';
$lang['sv_SE']['Course']['COURSE_NAME_EN'] = 'Kursnamn (engelska)';
$lang['sv_SE']['Course']['SUMMARY'] = 'Sammanfattning';
$lang['sv_SE']['Course']['STATUS'] = 'Status';
$lang['sv_SE']['Course']['STATUS_ACTIVE'] = 'Aktiv';
$lang['sv_SE']['Course']['STATUS_PASSIVE'] = 'Passiv';
$lang['sv_SE']['Course']['COURSERESPONSIBLE'] = 'Ansvarsperson';
$lang['sv_SE']['Course']['ERROR_EXISTINGCODE'] = 'Kurskoden är används redan av en annan kurs';
$lang['sv_SE']['Course']['ERROR_PARTICIPATORS'] = 'Antalet kursdeltagare måste vara större än noll';
$lang['sv_SE']['Course']['ERROR_MAXPARTICIPATORS'] = 'Kursen max antal deltagare måste vara större än min antal deltagare';
$lang['sv_SE']['Course']['ERROR_STARTDATE'] = 'Kursens startdatum måste vara tidigare än slutdatum';
$lang['sv_SE']['Course']['ERROR_COURSEDATEMISSING'] = 'Kursens start/slut datum saknas eller är ogiltigt';
$lang['sv_SE']['Course']['ERROR_SIGNUPDATE'] = 'Kursens anmälnings startdatum måste vara tidigare än slutdatum';
$lang['sv_SE']['Course']['ERROR_SIGNUPMISSING'] = 'Kursens anmälnings start/slut datum saknas eller är ogiltigt';
$lang['sv_SE']['Course']['ERROR_NOCOURSEDATES'] = 'Inga kursdagar valda';
$lang['sv_SE']['Course']['MAINLOCATION'] = 'Huvudsaklig plats';
$lang['sv_SE']['Course']['MAINTIMES'] = 'Huvudsakliga tider';
$lang['sv_SE']['Course']['COMPLETED_CHECKBOX'] = 'Slutförd';
$lang['sv_SE']['Course']['DESIRED_LESSIONS'] = 'Önskat antal lektioner';
$lang['sv_SE']['Course']['SUMMARY_OF_DATES'] = 'Summering av tidpunkter';
$lang['sv_SE']['Course']['SUMMARY_TOTALLESSIONS'] = 'Totalt antal lektioner';
$lang['sv_SE']['Course']['SUMMARY_DESIREDLESSIONS'] = 'Önskat antal lektioner';
$lang['sv_SE']['Course']['SUMMARY_LESSIONS_MISSMATCH'] = 'Antal lektioner och önskat antal lektioner stämmer inte!';
$lang['sv_SE']['Course']['AMOUNTUNIT'] = 'st';
$lang['sv_SE']['Course']['LABEL_HASISSUES'] = 'Kursen saknar data';
$lang['sv_SE']['Course']['LABEL_PASSIVE'] = 'Kursen är passiv';

$lang['sv_SE']['CourseDate']['SINGULARNAME'] = 'Kurstid';
$lang['sv_SE']['CourseDate']['PLURALNAME'] = 'Kurstider';
$lang['sv_SE']['CourseDate']['SPECIFIC'] = 'Specifik tidpunkt';
$lang['sv_SE']['CourseDate']['TIMESTART'] = 'Tidpunkt start';
$lang['sv_SE']['CourseDate']['TIMEEND'] = 'Tidpunkt slut';
$lang['sv_SE']['CourseDate']['TIMENICE'] = 'Tidpunkt';
$lang['sv_SE']['CourseDate']['PAYMENTMONTH'] = 'Utb mån';
$lang['sv_SE']['CourseDate']['PAYMENTMONTHNICE'] = $lang['sv_SE']['CourseDate']['PAYMENTMONTH'];
$lang['sv_SE']['CourseDate']['TIMESTARTNICE'] = $lang['sv_SE']['CourseDate']['TIMESTART'];
$lang['sv_SE']['CourseDate']['TIMEENDNICE'] = $lang['sv_SE']['CourseDate']['TIMEEND'];
$lang['sv_SE']['CourseDate']['WEEKDAY'] = 'Veckodag';
$lang['sv_SE']['CourseDate']['WEEKDAYNICE'] = $lang['sv_SE']['CourseDate']['WEEKDAY'];
$lang['sv_SE']['CourseDate']['MONDAY'] = 'Måndag';
$lang['sv_SE']['CourseDate']['TUESDAY'] = 'Tisdag';
$lang['sv_SE']['CourseDate']['WEDNESDAY'] = 'Onsdag';
$lang['sv_SE']['CourseDate']['THURSDAY'] = 'Torsdag';
$lang['sv_SE']['CourseDate']['FRIDAY'] = 'Fredag';
$lang['sv_SE']['CourseDate']['SATURDAY'] = 'Lördag';
$lang['sv_SE']['CourseDate']['SUNDAY'] = 'Söndag';
$lang['sv_SE']['CourseDate']['COURSELENGTH'] = 'Kursen längd (hh:mm)';
$lang['sv_SE']['CourseDate']['CANCELLED'] = 'Inhiberad';
$lang['sv_SE']['CourseDate']['CANCELLEDNICE'] = $lang['sv_SE']['CourseDate']['CANCELLED'];
$lang['sv_SE']['CourseDate']['CANCELLED_YES'] = 'Ja';
$lang['sv_SE']['CourseDate']['CANCELLED_NO'] = 'Nej';
$lang['sv_SE']['CourseDate']['CANCELLATIONREASON'] = 'Orsak till inhibering';
$lang['sv_SE']['CourseDate']['TEACHERNICE'] = 'Lärare';
$lang['sv_SE']['CourseDate']['COURSENICE'] = 'Kurs';
$lang['sv_SE']['CourseDate']['RECURRING'] = 'Allmänt';
$lang['sv_SE']['CourseDate']['SPECIFIEDDATES'] = 'Alla tidpunkter';
$lang['sv_SE']['CourseDate']['RECDATESTART'] = 'Kurs startdatum';
$lang['sv_SE']['CourseDate']['RECDATEEND'] = 'Kurs slutdatum';
$lang['sv_SE']['CourseDate']['STARTTIME'] = 'Börjar';
$lang['sv_SE']['CourseDate']['ENDTIME'] = 'Slutar';
$lang['sv_SE']['CourseDate']['LESSIONS'] = 'Lektioner';
$lang['sv_SE']['CourseDate']['LOCATIONNICE'] = 'Plats';
$lang['sv_SE']['CourseDate']['SIGNUPSTART'] = 'Anmälningstid start';
$lang['sv_SE']['CourseDate']['SIGNUPEND'] = 'Anmälningstid slut';
$lang['sv_SE']['CourseDate']['SIGNUPEND_CHECKBOX'] = 'Anmälningstiden har inget slutdatum';
$lang['sv_SE']['CourseDate']['LABEL_CONFLICTING'] = 'Tidpunkten har konflikter';
$lang['sv_SE']['CourseDate']['LABEL_NOLESSIONS'] = 'Tidpunkten har inga lektioner';

$lang['sv_SE']['BlockedTermDate']['SINGULARNAME'] = 'Tidpunkt';
$lang['sv_SE']['BlockedTermDate']['PLURALNAME'] = 'Tidpunkter';
$lang['sv_SE']['BlockedTermDate']['TIMESTART'] = 'Börjar';
$lang['sv_SE']['BlockedTermDate']['TIMEEND'] = 'Slutar';
$lang['sv_SE']['BlockedTermDate']['TIMESTARTNICE'] = $lang['sv_SE']['BlockedTermDate']['TIMESTART'];
$lang['sv_SE']['BlockedTermDate']['TIMEENDNICE'] = $lang['sv_SE']['BlockedTermDate']['TIMEEND'];
$lang['sv_SE']['BlockedTermDate']['WEEKDAY'] = 'Veckodag';
$lang['sv_SE']['BlockedTermDate']['WEEKDAYNICE'] = $lang['sv_SE']['BlockedTermDate']['WEEKDAY'];
$lang['sv_SE']['BlockedTermDate']['REASON'] = 'Orsak';

$lang['sv_SE']['CourseLanguage']['SINGULARNAME'] = 'Kursspråk';
$lang['sv_SE']['CourseLanguage']['PLURALNAME'] = 'Kursspråk';
$lang['sv_SE']['CourseLanguage']['NAME'] = 'Namn';
$lang['sv_SE']['CourseLanguage']['CODE'] = 'Kod';

$lang['sv_SE']['Teacher']['SINGULARNAME'] = 'Lärare';
$lang['sv_SE']['Teacher']['PLURALNAME'] = 'Lärare';
$lang['sv_SE']['Teacher']['CODE'] = 'Kod';
$lang['sv_SE']['Teacher']['NAME'] = 'Namn';
$lang['sv_SE']['Teacher']['FIRSTNAME'] = 'Förnamn';
$lang['sv_SE']['Teacher']['SURNAME'] = 'Efternamn';
$lang['sv_SE']['Teacher']['PERSONALNUMBER'] = 'Personsignum';
$lang['sv_SE']['Teacher']['TITLE'] = 'Titel';
$lang['sv_SE']['Teacher']['KNOWLEDGEAREA'] = 'Kunskapsområde';
$lang['sv_SE']['Teacher']['GENDER'] = 'Kön';
$lang['sv_SE']['Teacher']['GENDER_MALE'] = 'Man';
$lang['sv_SE']['Teacher']['GENDER_FEMALE'] = 'Kvinna';
$lang['sv_SE']['Teacher']['NATIVELANGUAGE'] = 'Modersmål';
$lang['sv_SE']['Teacher']['NATIVELANGUAGE_SWEDISH'] = 'Svenska';
$lang['sv_SE']['Teacher']['NATIVELANGUAGE_FINNISH'] = 'Finska';
$lang['sv_SE']['Teacher']['NATIVELANGUAGE_ENGLISH'] = 'Engelska';
$lang['sv_SE']['Teacher']['NATIVELANGUAGE_OTHER'] = 'Annat';
$lang['sv_SE']['Teacher']['POSTADDRESS'] = 'Näradress';
$lang['sv_SE']['Teacher']['POSTCODE'] = 'Postnummer';
$lang['sv_SE']['Teacher']['POSTOFFICE'] = 'Postanstalt';
$lang['sv_SE']['Teacher']['PHONE'] = 'Telefon';
$lang['sv_SE']['Teacher']['EMAIL'] = 'E-post';
$lang['sv_SE']['Teacher']['PROFESSION'] = 'Yrke';
$lang['sv_SE']['Teacher']['NOTE'] = 'Notering';
$lang['sv_SE']['Teacher']['BANKACCOUNTNUMBER'] = 'Bankkontonummer';
$lang['sv_SE']['Teacher']['HOURTYPE'] = 'Timtyp';
$lang['sv_SE']['Teacher']['SALARYCLASS'] = 'Löneklass';
$lang['sv_SE']['Teacher']['EMPLOYER'] = 'Arbetsgivare';
	
$lang['sv_SE']['Term']['SINGULARNAME'] = 'Period';
$lang['sv_SE']['Term']['PLURALNAME'] = 'Perioder';
$lang['sv_SE']['Term']['NAME'] = 'Namn';
$lang['sv_SE']['Term']['DATESTART'] = 'Börjar';
$lang['sv_SE']['Term']['DATEEND'] = 'Slutar';
$lang['sv_SE']['Term']['ACTIVE'] = 'Aktiv just nu';
$lang['sv_SE']['Term']['ACTIVENICE'] = $lang['sv_SE']['Term']['ACTIVE'];
$lang['sv_SE']['Term']['ACTIVE_YES'] = 'Ja';
$lang['sv_SE']['Term']['ACTIVE_NO'] = 'Nej';
$lang['sv_SE']['Term']['BLOCKED_DATES'] = 'Blockerade tidpunkter';
$lang['sv_SE']['Term']['SIGNUPSTART'] = 'Anmälningstid startar';
$lang['sv_SE']['Term']['SIGNUPEND'] = 'Anmälningstid slutar';
$lang['sv_SE']['Term']['SIGNUPENDNICE'] = $lang['sv_SE']['Term']['SIGNUPEND'];
$lang['sv_SE']['Term']['SIGNUPEND_CHECKBOX'] = 'Anmälningstiden har inget slutdatum';
$lang['sv_SE']['Term']['SIGNUPEND_NOEND'] = 'Non-stop';

$lang['sv_SE']['CourseUnit']['SINGULARNAME'] = 'Enhet';
$lang['sv_SE']['CourseUnit']['PLURALNAME'] = 'Enheter';
$lang['sv_SE']['CourseUnit']['NAME'] = 'Namn';
$lang['sv_SE']['CourseUnit']['CODE'] = 'Kod';
$lang['sv_SE']['CourseUnit']['BILLING'] = 'Fakturering';
$lang['sv_SE']['CourseUnit']['BILLINGNAME'] = 'Mottagare';
$lang['sv_SE']['CourseUnit']['POSTADDRESS'] = 'Näradress';
$lang['sv_SE']['CourseUnit']['POSTCODE'] = 'Postnummer';
$lang['sv_SE']['CourseUnit']['POSTOFFICE'] = 'Postanstalt';
$lang['sv_SE']['CourseUnit']['PHONE'] = 'Telefon';
$lang['sv_SE']['CourseUnit']['BUSINESSID'] = 'FO-nummer';
$lang['sv_SE']['CourseUnit']['LASTINVOICENUMBER'] = 'Senaste fakturans nummer';
$lang['sv_SE']['CourseUnit']['LASTREFERENCENUMBER'] = 'Senaste fakturans referensnummer';
$lang['sv_SE']['CourseUnit']['BILLINGTEXT'] = 'Faktura text';
$lang['sv_SE']['CourseUnit']['COUNTYNUMBER'] = 'Kommunnummer';
$lang['sv_SE']['CourseUnit']['PENALTYINTEREST'] = 'Förseningsränta';
$lang['sv_SE']['CourseUnit']['REMINDERFEE'] = 'Påminnelseavgift';
$lang['sv_SE']['CourseUnit']['INVOICEFEE'] = 'Fakturaavgift';
$lang['sv_SE']['CourseUnit']['PAYMENTDAYS'] = 'Betalningstid (dagar)';
$lang['sv_SE']['CourseUnit']['REMINDERPAYMENTDAYS'] = 'Påminnelse betalningstid (dagar)';
$lang['sv_SE']['CourseUnit']['REFERENCESTART'] = 'Referensnummerns början';
$lang['sv_SE']['CourseUnit']['REFERENCELENGTH'] = 'Referensnummerns längd';
$lang['sv_SE']['CourseUnit']['CANCELLATIONDAYS'] = 'Annulleringsdagar';
$lang['sv_SE']['CourseUnit']['CANCELLATIONFEE'] = 'Annulleringsavgift';

$lang['sv_SE']['HourType']['SINGULARNAME'] = 'Timtyp';
$lang['sv_SE']['HourType']['PLURALNAME'] = 'Timtyper';
$lang['sv_SE']['HourType']['NAME'] = 'Namn';
$lang['sv_SE']['HourType']['CODE'] = 'Kod';
$lang['sv_SE']['HourType']['HASHOURS'] = 'Har lärotimmar';
$lang['sv_SE']['HourType']['HASHOURSNICE'] = $lang['sv_SE']['HourType']['HASHOURS'];

$lang['sv_SE']['CourseSubject']['SINGULARNAME'] = 'Kursämne';
$lang['sv_SE']['CourseSubject']['PLURALNAME'] = 'Kursämnen';
$lang['sv_SE']['CourseSubject']['NAME'] = 'Namn';
$lang['sv_SE']['CourseSubject']['COURSECODENEXTID'] = 'Följande kurskod ID';
$lang['sv_SE']['CourseSubject']['CODE'] = 'Kod';

$lang['sv_SE']['EducationArea']['SINGULARNAME'] = 'Utbildningsområde';
$lang['sv_SE']['EducationArea']['PLURALNAME'] = 'Utbildningsområden';
$lang['sv_SE']['EducationArea']['NAME'] = 'Namn';
$lang['sv_SE']['EducationArea']['CODE'] = 'Kod';
$lang['sv_SE']['EducationArea']['PARENTNAME'] = 'Huvudområde';

$lang['sv_SE']['Participator']['SINGULARNAME'] = 'Deltagare';
$lang['sv_SE']['Participator']['PLURALNAME'] = 'Deltagare';
$lang['sv_SE']['Participator']['FIRSTNAME'] = 'Förnamn';
$lang['sv_SE']['Participator']['SURNAME'] = 'Efternamn';
$lang['sv_SE']['Participator']['ADDRESS'] = 'Adress';
$lang['sv_SE']['Participator']['NAME'] = 'Namn';
$lang['sv_SE']['Participator']['GENDER'] = 'Kön';
$lang['sv_SE']['Participator']['GENDER_MALE'] = 'Man';
$lang['sv_SE']['Participator']['GENDER_FEMALE'] = 'Kvinna';
$lang['sv_SE']['Participator']['NATIVELANGUAGE'] = 'Modersmål';
$lang['sv_SE']['Participator']['NATIVELANGUAGE_SWEDISH'] = 'Svenska';
$lang['sv_SE']['Participator']['NATIVELANGUAGE_FINNISH'] = 'Finska';
$lang['sv_SE']['Participator']['NATIVELANGUAGE_ENGLISH'] = 'Engelska';
$lang['sv_SE']['Participator']['NATIVELANGUAGE_OTHER'] = 'Annat';
$lang['sv_SE']['Participator']['EDUCATION'] = 'Utbildningsnivå';
$lang['sv_SE']['Participator']['EDUCATION_BASIC'] = '1 Grundnivå: grundskola, folkskola (slutförd)';
$lang['sv_SE']['Participator']['EDUCATION_LOW'] = '2 Andra stadiet: gymnasiet, yrkesskola och -institut';
$lang['sv_SE']['Participator']['EDUCATION_HIGH'] = '3 Högre utbildning: universitet, högskola, yrkeshögskola (slutförd)';
$lang['sv_SE']['Participator']['EDUCATION_OTHER'] = '4 Inga uppgifter (skolelever och barn under skolåldern)';
$lang['sv_SE']['Participator']['OCCUPATION'] = 'Huvudsaklig sysselsättning';
$lang['sv_SE']['Participator']['OCCUPATION_EMPLOYED'] = 'Sysselsatt';
$lang['sv_SE']['Participator']['OCCUPATION_UNEMPLOYED'] = 'Arbetslös';
$lang['sv_SE']['Participator']['OCCUPATION_STUDENT'] = 'Studerande';
$lang['sv_SE']['Participator']['OCCUPATION_RETIRED'] = 'Pensionär';
$lang['sv_SE']['Participator']['OCCUPATION_OTHER'] = 'Övriga (hemmafru, under skolåldern, värdpliktig)';
$lang['sv_SE']['Participator']['REGISTRATIONMETHOD'] = 'Registreringssätt';
$lang['sv_SE']['Participator']['REGISTRATIONMETHOD_EXTERNAL'] = 'Via web';
$lang['sv_SE']['Participator']['REGISTRATIONMETHOD_INTERNAL'] = 'Via e-Kurs';
$lang['sv_SE']['Participator']['NOTE_PARTICIPATOR'] = 'Notering (av deltagare)';
$lang['sv_SE']['Participator']['NOTE_ADMIN'] = 'Notering (av personal)';
$lang['sv_SE']['Participator']['NOTE'] = 'Notering';
$lang['sv_SE']['Participator']['PERSONALNUMBER'] = 'Personsignum';
$lang['sv_SE']['Participator']['POSTADDRESS'] = 'Näradress';
$lang['sv_SE']['Participator']['POSTCODE'] = 'Postnummer';
$lang['sv_SE']['Participator']['POSTOFFICE'] = 'Postanstalt';
$lang['sv_SE']['Participator']['PHONE'] = 'Telefon';
$lang['sv_SE']['Participator']['EMAIL'] = 'E-post';
$lang['sv_SE']['Participator']['PROFESSION'] = 'Yrke';

$lang['sv_SE']['CoursePerson']['SINGULARNAME'] = 'Ansvarsperson';
$lang['sv_SE']['CoursePerson']['PLURALNAME'] = 'Ansvarspersoner';
$lang['sv_SE']['CoursePerson']['FIRSTNAME'] = 'Förnamn';
$lang['sv_SE']['CoursePerson']['SURNAME'] = 'Efternamn';

$lang['sv_SE']['AddCourseForm']['TAB1'] = 'Allmän information';
$lang['sv_SE']['AddCourseForm']['TAB2'] = 'Ytterligare information';
$lang['sv_SE']['AddCourseForm']['TAB3'] = 'Plats och tidpunkter';

$lang['sv_SE']['CourseRequest']['SINGULARNAME'] = 'Kursanmälan';
$lang['sv_SE']['CourseRequest']['PLURALNAME'] = 'Kursanmälningar';
$lang['sv_SE']['CourseRequest']['STATUS'] = 'Status';
$lang['sv_SE']['CourseRequest']['STATUSNICE'] = 'Status';
$lang['sv_SE']['CourseRequest']['STATUS_NOTIFIED'] = 'Anmäld';
$lang['sv_SE']['CourseRequest']['STATUS_INQUEUE'] = 'I kö';
$lang['sv_SE']['CourseRequest']['STATUS_REJECTED'] = 'Förkastad';
$lang['sv_SE']['CourseRequest']['STATUS_CANCELED'] = 'Annullerad';
$lang['sv_SE']['CourseRequest']['STATUS_COMPLETED'] = 'Slutförd';
$lang['sv_SE']['CourseRequest']['COMMENT'] = 'Kommentar';
$lang['sv_SE']['CourseRequest']['PAYEDAMOUNT'] = 'Betalt belopp';
$lang['sv_SE']['CourseRequest']['PAYEDDATE'] = 'Betalningsdatum';
$lang['sv_SE']['CourseRequest']['ATTENDANCE'] = 'Närvaro';
$lang['sv_SE']['CourseRequest']['BILLINGADDRESS'] = 'Annan faktureringsadress';
$lang['sv_SE']['CourseRequest']['CREATED'] = 'Anmälningsdatum';
$lang['sv_SE']['CourseRequest']['CREATEDNICE'] = 'Anmälningsdatum';
$lang['sv_SE']['CourseRequest']['EDITED'] = 'Ändrad';
$lang['sv_SE']['CourseRequest']['EDITEDNICE'] = 'Ändrad';
$lang['sv_SE']['CourseRequest']['COURSENAME'] = $lang['sv_SE']['Course']['NAME'];
$lang['sv_SE']['CourseRequest']['SURNAME'] = $lang['sv_SE']['Participator']['SURNAME'];
$lang['sv_SE']['CourseRequest']['FIRSTNAME'] = $lang['sv_SE']['Participator']['FIRSTNAME'];
$lang['sv_SE']['CourseRequest']['ERROR_EXISTS'] = 'Den valda personen är redan anmäld till denna kurs';
$lang['sv_SE']['CourseRequest']['ERROR_NOCOURSE'] = 'Du måste välja en kurs';
$lang['sv_SE']['CourseRequest']['ERROR_NOPARTICIPATOR'] = 'Du måste välja en deltagare';

$lang['sv_SE']['TeacherSalaryClass']['SINGULARNAME'] = 'Löneklass';
$lang['sv_SE']['TeacherSalaryClass']['PLURALNAME'] = 'Löneklasser';
$lang['sv_SE']['TeacherSalaryClass']['NAME'] = 'Namn';
$lang['sv_SE']['TeacherSalaryClass']['ACTIVEFROM'] = 'I kraft fr o m';
$lang['sv_SE']['TeacherSalaryClass']['SALARYHOUR'] = 'Lön per lektion';
$lang['sv_SE']['TeacherSalaryClass']['NOTE'] = 'Kommentar';

$lang['sv_SE']['IncomeAccount']['SINGULARNAME'] = 'Inkomstkonto';
$lang['sv_SE']['IncomeAccount']['PLURALNAME'] = 'Inkomstkonton';
$lang['sv_SE']['IncomeAccount']['NAME'] = 'Namn';
$lang['sv_SE']['IncomeAccount']['ACCOUNT'] = 'Konto';
$lang['sv_SE']['IncomeAccount']['EXPENSEPLACE'] = 'Kostnadsställe';

$lang['sv_SE']['ExpenseAccount']['SINGULARNAME'] = 'Kostnadskonto';
$lang['sv_SE']['ExpenseAccount']['PLURALNAME'] = 'Kostnadskonton';
$lang['sv_SE']['ExpenseAccount']['NAME'] = 'Namn';
$lang['sv_SE']['ExpenseAccount']['ACCOUNT'] = 'Konto';
$lang['sv_SE']['ExpenseAccount']['EXPENSEPLACE'] = 'Kostnadsställe';

$lang['sv_SE']['CourseType']['SINGULARNAME'] = 'Kurstyp';
$lang['sv_SE']['CourseType']['PLURALNAME'] = 'Kurstyper';
$lang['sv_SE']['CourseType']['NAME'] = 'Namn';
$lang['sv_SE']['CourseType']['CODE'] = 'Kod';
$lang['sv_SE']['CourseType']['HASGOVERNMENTFUNDING'] = 'Har rätt till statsunderstöd';
$lang['sv_SE']['CourseType']['HASGOVERNMENTFUNDINGNICE'] = $lang['sv_SE']['CourseType']['HASGOVERNMENTFUNDING'];

$lang['sv_SE']['CourseMainClass']['SINGULARNAME'] = 'Huvudklass';
$lang['sv_SE']['CourseMainClass']['PLURALNAME'] = 'Huvudklasser';
$lang['sv_SE']['CourseMainClass']['NAME'] = 'Namn';
$lang['sv_SE']['CourseMainClass']['CODE'] = 'Kod';

$lang['sv_SE']['AgeGroup']['SINGULARNAME'] = 'Åldersgrupp';
$lang['sv_SE']['AgeGroup']['PLURALNAME'] = 'Åldersgrupper';
$lang['sv_SE']['AgeGroup']['NAME'] = 'Namn';
$lang['sv_SE']['AgeGroup']['CODE'] = 'Kod';

$lang['sv_SE']['EducationType']['SINGULARNAME'] = 'Utbildningstyp';
$lang['sv_SE']['EducationType']['PLURALNAME'] = 'Utbildningstyper';
$lang['sv_SE']['EducationType']['NAME'] = 'Namn';
$lang['sv_SE']['EducationType']['CODE'] = 'Kod';

$lang['sv_SE']['CourseAdmin']['SINGULARNAME'] = 'Kursadministratör';
$lang['sv_SE']['CourseAdmin']['PLURALNAME'] = 'Kursadministratörer';
$lang['sv_SE']['CourseAdmin']['CODE'] = 'Kod';
$lang['sv_SE']['CourseAdmin']['FIRSTNAME'] = 'Förnamn';
$lang['sv_SE']['CourseAdmin']['SURNAME'] = 'Efternamn';
$lang['sv_SE']['CourseAdmin']['PERSONALNUMBER'] = 'Personsignum';
$lang['sv_SE']['CourseAdmin']['TITLE'] = 'Titel';
$lang['sv_SE']['CourseAdmin']['GENDER'] = 'Kön';
$lang['sv_SE']['CourseAdmin']['GENDER_MALE'] = 'Man';
$lang['sv_SE']['CourseAdmin']['GENDER_FEMALE'] = 'Kvinna';
$lang['sv_SE']['CourseAdmin']['NATIVELANGUAGE'] = 'Modersmål';
$lang['sv_SE']['CourseAdmin']['NATIVELANGUAGE_SWEDISH'] = 'Svenska';
$lang['sv_SE']['CourseAdmin']['NATIVELANGUAGE_FINNISH'] = 'Finska';
$lang['sv_SE']['CourseAdmin']['NATIVELANGUAGE_ENGLISH'] = 'Engelska';
$lang['sv_SE']['CourseAdmin']['NATIVELANGUAGE_OTHER'] = 'Annat';
$lang['sv_SE']['CourseAdmin']['POSTADDRESS'] = 'Näradress';
$lang['sv_SE']['CourseAdmin']['POSTCODE'] = 'Postnummer';
$lang['sv_SE']['CourseAdmin']['POSTOFFICE'] = 'Postanstalt';
$lang['sv_SE']['CourseAdmin']['PHONE'] = 'Telefon';
$lang['sv_SE']['CourseAdmin']['EMAIL'] = 'E-post';
$lang['sv_SE']['CourseAdmin']['PROFESSION'] = 'Yrke';
$lang['sv_SE']['CourseAdmin']['NOTE'] = 'Notering';

$lang['sv_SE']['Employer']['SINGULARNAME'] = 'Arbetsgivare';
$lang['sv_SE']['Employer']['PLURALNAME'] = 'Arbetsgivare';
$lang['sv_SE']['Employer']['NAME'] = 'Namn';
$lang['sv_SE']['Employer']['POSTADDRESS'] = 'Näradress';
$lang['sv_SE']['Employer']['POSTCODE'] = 'Postnummer';
$lang['sv_SE']['Employer']['POSTOFFICE'] = 'Postanstalt';
$lang['sv_SE']['Employer']['PHONE'] = 'Telefon';
$lang['sv_SE']['Employer']['LABORAGREEMENT'] = 'Arbetsavtal';
$lang['sv_SE']['Employer']['LABORAGREEMENTSIGNER'] = 'Arbetsavtal signerare';

$lang['sv_SE']['TeacherHoursMonthlyReport']['TITLE'] = 'Månadstimmar rapport';
$lang['sv_SE']['TeacherHoursMonthlyReport']['MONTH'] = 'Månad';
$lang['sv_SE']['TeacherHoursMonthlyReport']['ALLHOURS'] = 'Månadsvis totalt';

$lang['sv_SE']['TeacherHoursWeeklyReport']['TITLE'] = 'Veckotimmar rapport';
$lang['sv_SE']['TeacherHoursWeeklyReport']['WEEK'] = 'Vecka';
$lang['sv_SE']['TeacherHoursWeeklyReport']['ALLHOURS'] = 'Veckovis totalt';

$lang['sv_SE']['TeacherHoursReport']['HEADER'] = 'Timrapportering';
$lang['sv_SE']['TeacherHoursReport']['REPORTTYPE'] = 'Rapport typ';
$lang['sv_SE']['TeacherHoursReport']['BUTTON'] = 'Skapa rapport';
$lang['sv_SE']['TeacherHoursReport']['COURSE'] = 'Kurs';
$lang['sv_SE']['TeacherHoursReport']['DATE'] = 'Datum';
$lang['sv_SE']['TeacherHoursReport']['TIMES'] = 'Gånger';
$lang['sv_SE']['TeacherHoursReport']['LESSIONS'] = 'Lektioner';
$lang['sv_SE']['TeacherHoursReport']['HOURS'] = 'Timmar';
$lang['sv_SE']['TeacherHoursReport']['SIGNATURE'] = 'Signatur';
$lang['sv_SE']['TeacherHoursReport']['ALLHOURS'] = 'Totalt';

$lang['sv_SE']['TeacherSalaryReport']['HEADER'] = 'Lönerapporter';
$lang['sv_SE']['TeacherSalaryReport']['REPORTTYPE'] = 'Rapport typ';
$lang['sv_SE']['TeacherSalaryReport']['BUTTON'] = 'Skapa rapport';

$lang['sv_SE']['TeacherSalaryReportEmployment']['TITLE'] = 'Anställnings rapport';
$lang['sv_SE']['TeacherSalaryReportEmployment']['EMPLOYEES'] = 'Anställda';

$lang['sv_SE']['TeacherLaborContract']['HEADER'] = 'Arbetsavtal';
$lang['sv_SE']['TeacherLaborContract']['TITLE'] = $lang['sv_SE']['TeacherLaborContract']['HEADER'];
$lang['sv_SE']['TeacherLaborContract']['BUTTON'] = 'Skapa arbetsavtal';
$lang['sv_SE']['TeacherLaborContract']['SIGNATURE_LOCATION'] = 'Plats';
$lang['sv_SE']['TeacherLaborContract']['SIGNATURE_DATE'] = 'Datum';
$lang['sv_SE']['TeacherLaborContract']['SIGNATURE'] = 'Signatur';
$lang['sv_SE']['TeacherLaborContract']['EMPLOYMENTPERIOD'] = 'Anställningstid';
$lang['sv_SE']['TeacherLaborContract']['LABORAGREEMENTAPPLIED'] = 'Arbetsavtal som tillämpas';
$lang['sv_SE']['TeacherLaborContract']['HOURS'] = 'Timmar';
$lang['sv_SE']['TeacherLaborContract']['TOTAL'] = 'Totalt';

$lang['sv_SE']['CourseDateLink']['SINGULARNAME'] = 'Kurslärare';
$lang['sv_SE']['CourseDateLink']['PLURALNAME'] = 'Kurslärare';
$lang['sv_SE']['CourseDateLink']['LOCKED'] = 'Låst';

$lang['sv_SE']['TeacherAllEmployeesReport']['TITLE'] = 'Anställda lärares tim och lönerapport';
$lang['sv_SE']['TeacherAllEmployeesReport']['PAYMENTMONTH'] = 'UM';
$lang['sv_SE']['TeacherAllEmployeesReport']['HOURTYPE'] = 'TT';
$lang['sv_SE']['TeacherAllEmployeesReport']['TEACHER'] = 'Lärare';
$lang['sv_SE']['TeacherAllEmployeesReport']['DATESTART'] = 'Start datum';
$lang['sv_SE']['TeacherAllEmployeesReport']['DATEEND'] = 'Slut datum';
$lang['sv_SE']['TeacherAllEmployeesReport']['LESSIONS'] = 'Lektioner';
$lang['sv_SE']['TeacherAllEmployeesReport']['HOURS'] = 'Timmar';
$lang['sv_SE']['TeacherAllEmployeesReport']['TOTAL'] = 'Totalt';
$lang['sv_SE']['TeacherAllEmployeesReport']['SALARYHOUR'] = 'A-pris';
$lang['sv_SE']['TeacherAllEmployeesReport']['EXPENSEACCOUNT'] = 'Kostnadskonto';
$lang['sv_SE']['TeacherAllEmployeesReport']['EXPENSEACCOUNTSUM'] = 'Kostnadskonto summa';
$lang['sv_SE']['TeacherAllEmployeesReport']['TEACHERSUM'] = 'Lärare summa';

$lang['sv_SE']['Account']['USERACCOUNT'] = 'Användarkonto';
$lang['sv_SE']['Account']['PASSWORDRESET'] = 'Skicka lösenordsåterställningslänk';
$lang['sv_SE']['Account']['SENDACCOUNTINFO'] = 'Skicka kontoinformation';
$lang['sv_SE']['Account']['PERMLOCK'] = 'Låst';

$lang['sv_SE']['CourseDiscount']['SINGULARNAME'] = 'Rabatt';
$lang['sv_SE']['CourseDiscount']['PLURALNAME'] = 'Rabatter';
$lang['sv_SE']['CourseDiscount']['AMOUNT'] = 'Mängd';
$lang['sv_SE']['CourseDiscount']['TYPE'] = 'Typ';
$lang['sv_SE']['CourseDiscount']['TYPE_PERCENT'] = '% (procent)';
$lang['sv_SE']['CourseDiscount']['TYPE_CURRENCY'] = '€ (euro)';
$lang['sv_SE']['CourseDiscount']['NOTE'] = 'Notering';
$lang['sv_SE']['CourseDiscount']['NOCANCELFEE'] = 'Kursanmälan annullerades i tid, ingen avgift';
$lang['sv_SE']['CourseDiscount']['PERCENTCANCELFEE'] = 'Kursanmälan annullerades innan andra kurstillfället, 50% avgift';
$lang['sv_SE']['CourseDiscount']['MAXCANCELFEE'] = 'Kursanmälan annullerades innan andra kurstillfället, max %s avgift';
$lang['sv_SE']['CourseDiscount']['FULLFEE'] = 'Kursanmälan annullerades inte i tid, full kursavgift';

$lang['sv_SE']['CourseQuickSearch']['TITLE'] = 'Snabbsökning';
$lang['sv_SE']['CourseQuickSearch']['CODEORNAME'] = 'Kurskod eller kursnamn';
$lang['sv_SE']['CourseQuickSearch']['SEARCH'] = 'Sök kurser';
$lang['sv_SE']['CourseQuickSearch']['SHOWALL'] = 'Visa alla kurser';

$lang['sv_SE']['CourseDetailedSearch']['TITLE'] = 'Kurssökning';
$lang['sv_SE']['CourseDetailedSearch']['LANGUAGE'] = 'Kursspråk';
$lang['sv_SE']['CourseDetailedSearch']['UNIT'] = 'Avdelning';
$lang['sv_SE']['CourseDetailedSearch']['SUBJECT'] = 'Ämnesgrupp';
$lang['sv_SE']['CourseDetailedSearch']['TERM'] = 'Tidsinterval';
$lang['sv_SE']['CourseDetailedSearch']['LOCATION'] = 'Plats';
$lang['sv_SE']['CourseDetailedSearch']['WEEKDAY'] = 'Veckodag';
$lang['sv_SE']['CourseDetailedSearch']['FREESPOTSONLY'] = 'Visa endast kurser med lediga platser';
$lang['sv_SE']['CourseDetailedSearch']['ACTIVEONLY'] = 'Visa endast pågående kurser';
$lang['sv_SE']['CourseDetailedSearch']['SEARCH'] = $lang['sv_SE']['CourseQuickSearch']['SEARCH'];
$lang['sv_SE']['CourseDetailedSearch']['SHOWALL'] = $lang['sv_SE']['CourseQuickSearch']['SHOWALL'];

$lang['sv_SE']['CourseSearchResults']['SEARCHRESULTS'] = 'Sökresultat';
$lang['sv_SE']['CourseSearchResults']['NOTHINGFOUND'] = 'Inga träffar';
$lang['sv_SE']['CourseSearchResults']['COURSECODE'] = 'Kurskod';
$lang['sv_SE']['CourseSearchResults']['COURSENAME'] = 'Kursnamn';
$lang['sv_SE']['CourseSearchResults']['COURSELOCATION'] = 'Plats';
$lang['sv_SE']['CourseSearchResults']['CORRSEFREESPOTS'] = 'Lediga platser';
$lang['sv_SE']['CourseSearchResults']['COURSESTART'] = 'Börjar';
$lang['sv_SE']['CourseSearchResults']['COURSESTOP'] = 'Slutar';
$lang['sv_SE']['CourseSearchResults']['COURSELANGUAGES'] = 'Kursens språk';
$lang['sv_SE']['CourseSearchResults']['COURSETEACHERS'] = 'Lärare';
$lang['sv_SE']['CourseSearchResults']['COURSEDATES'] = 'Tidpunkt';
$lang['sv_SE']['CourseSearchResults']['SIGNUPDATES'] = 'Anmälningstid';
$lang['sv_SE']['CourseSearchResults']['HOURS'] = 'timmar';
$lang['sv_SE']['CourseSearchResults']['LESSIONS'] = 'lektioner';
$lang['sv_SE']['CourseSearchResults']['COURSESUBJECTS'] = 'Ämnesgrupper';
$lang['sv_SE']['CourseSearchResults']['COURSEDESCRIPTION'] = 'Beskrivning';
$lang['sv_SE']['CourseSearchResults']['COURSEBOOKS'] = 'Kurslitteratur';
$lang['sv_SE']['CourseSearchResults']['COURSEPRICE'] = 'Pris';
$lang['sv_SE']['CourseSearchResults']['SIGNUPBUTTON'] = 'Reservera plats';
$lang['sv_SE']['CourseSearchResults']['CANCELBUTTON'] = 'Annullanera';
$lang['sv_SE']['CourseSearchResults']['YES'] = 'Ja';
$lang['sv_SE']['CourseSearchResults']['NO'] = 'Nej';

$lang['sv_SE']['SignupHandler']['STATUS'] = 'Status';
$lang['sv_SE']['SignupHandler']['STATUS_ACCEPTED'] = 'Godkänd';
$lang['sv_SE']['SignupHandler']['STATUS_QUEUED'] = 'I kö';
$lang['sv_SE']['SignupHandler']['STATUS_RESERVEREDSPOT'] = 'Obekräftad kursplats';
$lang['sv_SE']['SignupHandler']['STATUS_FREESPOTSAVAILABLE'] = 'Lediga platser finns,<br/>du kan göra en platsreservation.';
$lang['sv_SE']['SignupHandler']['STATUS_NOFREESPOTSAVAILABLE'] = 'Inga lediga platser,<br/>du kommer att sättas i kö.';
$lang['sv_SE']['SignupHandler']['SIGNUPFOR'] = 'Reservera plats till följande kurs';
$lang['sv_SE']['SignupHandler']['CANCELFROM'] = 'Annullera platsen från följande kurs';
$lang['sv_SE']['SignupHandler']['CONFIRMSIGNUPFOR'] = 'Bekräfta anmälning till följande kurser';
$lang['sv_SE']['SignupHandler']['CANCELCOURSE'] = 'Annullera kursanmälning till följande kurs';
$lang['sv_SE']['SignupHandler']['WARNCANCELCOURSE'] = '<strong>OBS!</strong><br/>Du kan inte anmäla dig pånytt till en kurs, vars kursplats du har annullerat!';
$lang['sv_SE']['SignupHandler']['CONFIRMINFO'] = '<strong>OBS!</strong><br/>Du måste bekräfta reservationen under \'Mina kurser\'-sidan efter att du har reserverat plats till de kurser som du vill gå på.';
$lang['sv_SE']['SignupHandler']['CANCELFEE_NOFEE'] = 'Du kan ännu annullera din plats avgiftsfritt (%s dagar före kursstart).';
$lang['sv_SE']['SignupHandler']['CANCELFEE_SMALLFEE'] = 'Du kan annullera din plats men en annulleringsavgift på 50% av kurspriset eller max %s kommer att tillämpas.';

$lang['sv_SE']['MyCoursesPage']['TITLE'] = 'Mina kurser';
$lang['sv_SE']['MyCoursesPage']['MYAGENDA_TITLE'] = 'Min läsordning';
$lang['sv_SE']['MyCoursesPage']['UNCONFIRMED_TITLE'] = 'Obekräftade kursanmälningar';
$lang['sv_SE']['MyCoursesPage']['CONFIRMED_TITLE'] = 'Bekräftade kursanmälningar';
$lang['sv_SE']['MyCoursesPage']['PROFILE_TITLE'] = 'Profil';
$lang['sv_SE']['MyCoursesPage']['PROFILE_BUTTONUPDATE'] = 'Uppdatera profil';
$lang['sv_SE']['MyCoursesPage']['CONFIRM_COURSES'] = 'Bekräfta alla kurser';
$lang['sv_SE']['MyCoursesPage']['LOGIN'] = 'Logga in';
$lang['sv_SE']['MyCoursesPage']['LOGINFAILED'] = 'Inloggning misslyckades, kontrollera användarnamn och lösenordet';
$lang['sv_SE']['MyCoursesPage']['REGISTER'] = 'Registrera';
$lang['sv_SE']['MyCoursesPage']['NOUNCONFIRMED'] = 'Inga obekräftade kursanmälningar';
$lang['sv_SE']['MyCoursesPage']['NOCONFIRMED'] = 'Inga bekräftade kursanmälningar';
$lang['sv_SE']['MyCoursesPage']['PROFILE_UPDATEGOOD'] = 'Updatering av din profil lyckades';
$lang['sv_SE']['MyCoursesPage']['PROFILE_UPDATEBAD'] = 'Updatering av din profil misslyckades';
$lang['sv_SE']['MyCoursesPage']['TIMELEFTTOCONFIRM'] = 'Tid kvar att bekräfta anmälningarna';
$lang['sv_SE']['MyCoursesPage']['SAVE'] = 'Spara';
$lang['sv_SE']['MyCoursesPage']['SAVED'] = 'Sparat';
$lang['sv_SE']['MyCoursesPage']['SAVING'] = 'Sparar';
$lang['sv_SE']['MyCoursesPage']['TEACHER_COURSES'] = 'Kurser';
$lang['sv_SE']['MyCoursesPage']['TEACHER_NOCOURSES'] = 'Inga kurser';
$lang['sv_SE']['MyCoursesPage']['PARTICIPATORLIST'] = 'Deltagarlista';
$lang['sv_SE']['MyCoursesPage']['PARTICIPATORLIST_PRINT'] = 'Skriv ut';
$lang['sv_SE']['MyCoursesPage']['SEND'] = 'Skicka';
$lang['sv_SE']['MyCoursesPage']['SENT'] = 'Skickat';
$lang['sv_SE']['MyCoursesPage']['SENDING'] = 'Skickar';
$lang['sv_SE']['MyCoursesPage']['SENDTEACHERTOMESSAGE'] = 'Skicka meddelande till lärare';
$lang['sv_SE']['MyCoursesPage']['SENDPARTICIPATORSMESSAGE'] = 'Skicka meddelande till deltagarna';

$lang['sv_SE']['Profile']['LOGGEDINAS'] = 'Du är inloggad som';
$lang['sv_SE']['Profile']['NOTLOGGEDIN'] = 'Du är inte inloggad';
$lang['sv_SE']['Profile']['LOGIN'] = 'Logga in';
$lang['sv_SE']['Profile']['LOGOUT'] = 'Logga ut';

$lang['sv_SE']['CourseDataExport']['HEADER'] = 'Exportera kursdata';
$lang['sv_SE']['CourseDataExport']['BUTTON'] = 'Exportera';

$lang['sv_SE']['ParticipatorCoursesReport']['TITLE'] = 'Deltagare - Kursrapport';
$lang['sv_SE']['ParticipatorCourseRequestsReport']['TITLE'] = 'Deltagare - Kursanmälningsrapport';
$lang['sv_SE']['ParticipatorReports']['REPORTTYPE_COURSES'] = 'Kurser';
$lang['sv_SE']['ParticipatorReports']['REPORTTYPE_COURSEREQUESTS'] = 'Kursanmälningar';
$lang['sv_SE']['ParticipatorReports']['REPORTTYPE_COUSEREQUESTINVOICE'] = 'Faktura';
$lang['sv_SE']['ParticipatorReports']['HEADER'] = 'Deltagarrapporter';

$lang['sv_SE']['CourseReports']['HEADER'] = 'Kursrapporter';
$lang['sv_SE']['CourseReports']['REPORTTYPE_PARTICIPATORS'] = 'Deltagare';
$lang['sv_SE']['CourseReports']['REPORTTYPE_TEACHERS'] = 'Lärare';
$lang['sv_SE']['CourseReports']['REPORTTYPE_COURSEREQUESTS'] = 'Kursanmälningar';
$lang['sv_SE']['CourseReports']['REPORTTYPE_COURSEDATES'] = 'Tidpunkter';

$lang['sv_SE']['CourseParticipatorsReport']['TITLE'] = 'Kurs - Deltagarrapport';
$lang['sv_SE']['CourseTeachersReport']['TITLE'] = 'Kurs - Lärarrapport';
$lang['sv_SE']['CourseRequestsReport']['TITLE'] = 'Kurs - Kursanmälningsrapport';
$lang['sv_SE']['CourseDatesReport']['TITLE'] = 'Kurs - Tidpunkter';

$lang['sv_SE']['TeacherReports']['REPORTTYPE_COURSES'] = 'Kurser';
$lang['sv_SE']['TeacherReports']['REPORTTYPE_AGENDA'] = 'Läsordning';
$lang['sv_SE']['TeacherReports']['HEADER'] = 'Lärarrapporter';
$lang['sv_SE']['TeacherReports']['WEEK'] = 'Vecka';

$lang['sv_SE']['TeacherCoursesReport']['TITLE'] = 'Lärare - Kursrapport';
$lang['sv_SE']['TeacherAgendaReport']['TITLE'] = 'Lärare - Läsordning';

$lang['sv_SE']['ParticipatorSignupEmail']['GREETING'] = 'Välkommen';
$lang['sv_SE']['ParticipatorSignupEmail']['SUBJECT'] = 'Tack för att du blivit medlem';
$lang['sv_SE']['ParticipatorSignupEmail']['EMAILSIGNUPINTRO1'] = 'Tack för att du har blivit medlem, dina uppgifter är nu registrerade hos oss.';
$lang['sv_SE']['ParticipatorSignupEmail']['EMAILSIGNUPINTRO2'] = 'Du kan logga in till websidan med uppgifterna nedan';
$lang['sv_SE']['ParticipatorSignupEmail']['CONTACTINFO'] = 'Kontakt information';

$lang['sv_SE']['AccountInformationEmail']['GREETING'] = 'Hej';
$lang['sv_SE']['AccountInformationEmail']['SUBJECT'] = 'Konto information';
$lang['sv_SE']['AccountInformationEmail']['INTRO1'] = 'Du kan logga in till websidan med uppgifterna nedan';
$lang['sv_SE']['AccountInformationEmail']['CONTACTINFO'] = 'Kontakt information';

$lang['sv_SE']['IM_Message']['SUBJECT'] = 'Ämne';
$lang['sv_SE']['IM_Message']['BODY'] = 'Meddelande';

$lang['sv_SE']['BankAccount']['SINGULARNAME'] = 'Bankkonto';
$lang['sv_SE']['BankAccount']['PLURALNAME'] = 'Bankkonton';
$lang['sv_SE']['BankAccount']['NAME'] = 'Namn';
$lang['sv_SE']['BankAccount']['ACCOUNTNUMBER'] = 'Kontonummer';
$lang['sv_SE']['BankAccount']['IBAN'] = 'IBAN';
$lang['sv_SE']['BankAccount']['BIC'] = 'BIC';

$lang['sv_SE']['Invoice']['SINGULARNAME'] = 'Faktura';
$lang['sv_SE']['Invoice']['PLURALNAME'] = 'Fakturor';
$lang['sv_SE']['Invoice']['INVOICENUMBER'] = 'Nummer';
$lang['sv_SE']['Invoice']['REFERENCENUMBER'] = 'Referensnummer';
$lang['sv_SE']['Invoice']['INVOICEDATE'] = 'Datum';
$lang['sv_SE']['Invoice']['INVOICEDUEDATE'] = 'Förfallodag';
$lang['sv_SE']['Invoice']['PHONE'] = 'Tel';
$lang['sv_SE']['Invoice']['STATUS'] = 'Status';
$lang['sv_SE']['Invoice']['STATUS_DRAFT'] = 'Förslag';
$lang['sv_SE']['Invoice']['STATUS_SENT'] = 'Skickad';
$lang['sv_SE']['Invoice']['STATUS_PAYED'] = 'Betald';
$lang['sv_SE']['Invoice']['TYPE'] = 'Typ';
$lang['sv_SE']['Invoice']['TYPE_DYNAMIC'] = 'Dynamisk';
$lang['sv_SE']['Invoice']['TYPE_ORIGINAL'] = 'Original';
$lang['sv_SE']['Invoice']['TYPE_REMINDER'] = 'Påminnelse';
$lang['sv_SE']['Invoice']['TOTALCOST'] = 'Totalt';
$lang['sv_SE']['Invoice']['RECIPIENT'] = 'Mottagare';
$lang['sv_SE']['Invoice']['PAYER'] = 'Betalare';

$lang['sv_SE']['InvoiceRow']['SINGULARNAME'] = 'Fakturarad';
$lang['sv_SE']['InvoiceRow']['PLURALNAME'] = 'Fakturarader';
$lang['sv_SE']['InvoiceRow']['CODE'] = 'Artikel nr';
$lang['sv_SE']['InvoiceRow']['NAME'] = 'Artikel';
$lang['sv_SE']['InvoiceRow']['AMOUNT'] = 'Antal';
$lang['sv_SE']['InvoiceRow']['AMOUNTUNIT'] = 'Enhet';
$lang['sv_SE']['InvoiceRow']['UNITPRICE'] = 'Enhetspris';
$lang['sv_SE']['InvoiceRow']['TOTALCOST'] = 'Belopp';
$lang['sv_SE']['InvoiceRow']['DISCOUNTAMOUNT'] = 'Rabatt';

$lang['sv_SE']['CourseRequestInvoice']['SINGULARNAME'] = $lang['sv_SE']['Invoice']['SINGULARNAME'];
$lang['sv_SE']['CourseRequestInvoice']['PLURALNAME'] = $lang['sv_SE']['Invoice']['PLURALNAME'];

$lang['sv_SE']['Report']['LANGUAGE'] = 'Språk';

?>
