<?php

global $lang;

if(array_key_exists('fi_FI', $lang) && is_array($lang['fi_FI'])) {
    $lang['fi_FI'] = array_merge($lang['en_US'], $lang['fi_FI']);
} else {
    $lang['fi_FI'] = $lang['en_US'];
}

$lang['fi_FI']['CourseBookingAdmin']['MENUTITLE'] = 'e-Kurssi';
$lang['fi_FI']['CourseBookingAdmin']['WELCOMEMESSAGE'] = 'Tervetuloa e-Kurssiin!<br /><br />Valitse haluamasi toiminto vasemmalla olevasta listasta.';

$lang['fi_FI']['CourseBookingAdmin']['MODULES'] = 'Moduulit';
$lang['fi_FI']['CourseBookingAdmin']['SHOWCOURSES'] = 'Näytä kurssit';
$lang['fi_FI']['CourseBookingAdmin']['SHOWSUBJECTS'] = 'Näytä kurssiaineet';
$lang['fi_FI']['CourseBookingAdmin']['SHOWEDUCATIONAREAS'] = 'Näytä koulutsalat';
$lang['fi_FI']['CourseBookingAdmin']['SHOWTEACHERS'] = 'Näytä opettajat';
$lang['fi_FI']['CourseBookingAdmin']['SHOWUNITS'] = 'e-Kurssi yksiköt';
$lang['fi_FI']['CourseBookingAdmin']['SHOWDEPARTMENTS'] = 'e-Kurssi osastot';
$lang['fi_FI']['CourseBookingAdmin']['EDITTERMS'] = 'Muokkaa lukuvuodet';
$lang['fi_FI']['CourseBookingAdmin']['MANAGEUSERS'] = 'Asiakashallinta';
$lang['fi_FI']['CourseBookingAdmin']['VIEWREPORTS'] = 'Raportit';

$lang['fi_FI']['CourseBookingExtension']['ACTIVETERMS'] = 'Aktiiviset lukuvuodet';

$lang['fi_FI']['Object']['MAIN'] = 'Yleistä';

$lang['fi_FI']['Course']['SINGULARNAME'] = 'Kurssi';
$lang['fi_FI']['Course']['PLURALNAME'] = 'Kurssit';
$lang['fi_FI']['Course']['NAME'] = 'Kurssinimi';

$lang['fi_FI']['CourseDate']['SINGULARNAME'] = 'Kurssin ajankohta';
$lang['fi_FI']['CourseDate']['PLURALNAME'] = 'Kurssin ajankohdat';
$lang['fi_FI']['CourseDate']['TIMESTART'] = 'Ajankohta aloitus';
$lang['fi_FI']['CourseDate']['TIMEEND'] = 'Ajankohta loppu';
$lang['fi_FI']['CourseDate']['WEEKDAYNICE'] = 'Viikonpäivä';

$lang['fi_FI']['CourseLanguage']['SINGULARNAME'] = 'Kurssin kieli';
$lang['fi_FI']['CourseLanguage']['PLURALNAME'] = 'Kurssin kielet';
$lang['fi_FI']['CourseLanguage']['NAME'] = 'Kielen nimi';

$lang['fi_FI']['Teacher']['SINGULARNAME'] = 'Opettaja';
$lang['fi_FI']['Teacher']['PLURALNAME'] = 'Opettajat';
$lang['fi_FI']['Teacher']['FIRSTNAME'] = 'Etunimi';
$lang['fi_FI']['Teacher']['LASTNAME'] = 'Sukunimi';

$lang['fi_FI']['Term']['SINGULARNAME'] = 'Lukuvuosi';
$lang['fi_FI']['Term']['PLURALNAME'] = 'Lukuvuodet';
$lang['fi_FI']['Term']['NAME'] = 'Lukovuoden nimi';
$lang['fi_FI']['Term']['DATESTART'] = 'Lukuvuosi alkaa';
$lang['fi_FI']['Term']['DATEEND'] = 'Lukuvuosi päättyy';
$lang['fi_FI']['Term']['ACTIVE'] = 'Aktiivi juuri nyt';

$lang['fi_FI']['CourseUnit']['SINGULARNAME'] = 'Yksikkö';
$lang['fi_FI']['CourseUnit']['PLURALNAME'] = 'Yksiköt';
$lang['fi_FI']['CourseUnit']['NAME'] = 'e-Kurssi yksikkö';

$lang['fi_FI']['CourseDepartment']['SINGULARNAME'] = 'Osasto';
$lang['fi_FI']['CourseDepartment']['PLURALNAME'] = 'Osastot';
$lang['fi_FI']['CourseDepartment']['NAME'] = 'e-Kurssi osasto';

$lang['fi_FI']['CourseSubject']['SINGULARNAME'] = 'Aine';
$lang['fi_FI']['CourseSubject']['PLURALNAME'] = 'Aineet';
$lang['fi_FI']['CourseSubject']['NAME'] = 'Aineen nimi';
$lang['fi_FI']['CourseSubject']['IDENTIFIER'] = 'Aineen numero / tunnus';

$lang['fi_FI']['EducationArea']['SINGULARNAME'] = 'Koulutusala';
$lang['fi_FI']['EducationArea']['PLURALNAME'] = 'Koulutusalat';
$lang['fi_FI']['EducationArea']['NAME'] = 'Koulutusalan nimi';
$lang['fi_FI']['EducationArea']['NUMBER'] = 'Numero / Tunnus';

$lang['fi_FI']['Participator']['SINGULARNAME'] = 'Osallistuja';
$lang['fi_FI']['Participator']['PLURALNAME'] = 'Osallistujat';

?>