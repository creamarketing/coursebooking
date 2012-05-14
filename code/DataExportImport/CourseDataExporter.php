<?php

class CourseDataExporter extends DataObject {
	
	public static function generate($languageID = null, $termID = null, $file = false) {	
		if (!is_numeric($termID) || !is_numeric($languageID))
			return '';
		
		$term = DataObject::get_by_id('Term', $termID);
		$language = DataObject::get_by_id('CourseLanguage', $languageID);
		$courses = $term->Courses();
		
		$text = '';
		
		if ($courses) {
			foreach ($courses as $course) {
				if ($course->Languages()->containsIDs(array($languageID))) {
					if ($file) {
						$text .= $course->CourseCode . ' ' . $course->getField('Name_' . $language->Locale) . "\n\n";
					
						$text .= $course->MainLocationText . "\t" . $course->dbObject('RecDateStart')->Format('d.m') . '-' . $course->dbObject('RecDateEnd')->Format('d.m.Y');
						$text .= ', ' . $course->getTotalLessions() . ' ' . lcfirst(_t('CourseDate.LESSIONS', 'Lessions')) . "\t";
						$text .= $course->getNiceTeachers() . "\t";
						$text .= _t('Course.PRICE', 'Course price') . ' ' . $course->CoursePrice . " €\t";
						$text .= _t('Course.MAX', 'Max') . ' ' . $course->MaxParticipators . ' ' . _t('Course.STUDENTS_SHORT', 'stud') . ".\n\n";
					
						$text .= $course->getField('CourseDescription_' . $language->Locale);
					
						$text .= "\n\n";
					} else {
						$html_tab = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						$text .= '<strong>' . $course->CourseCode . ' ' . $course->getField('Name_' . $language->Locale) . "</strong><br/><br/>";
					
						$text .= $course->MainLocationText . $html_tab . $course->dbObject('RecDateStart')->Format('d.m') . '-' . $course->dbObject('RecDateEnd')->Format('d.m.Y');
						$text .= ', ' . $course->getTotalLessions() . ' ' . lcfirst(_t('CourseDate.LESSIONS', 'Lessions')) . $html_tab;
						$text .= $course->getNiceTeachers() . $html_tab;
						$text .= _t('Course.PRICE', 'Course price') . ' ' . $course->CoursePrice . " €{$html_tab}";
						$text .= _t('Course.MAX', 'Max') . ' ' . $course->MaxParticipators . ' ' . _t('Course.STUDENTS_SHORT', 'stud') . ".<br/><br/>";
					
						$text .= $course->getField('CourseDescription_' . $language->Locale);
					
						$text .= "<br/><br/>";						
					}
				}
			}
		}
		
		$response = new SS_HTTPResponse($text);
		if ($file) {
			$response->addHeader("Content-type", "text/plain");
			$response->addHeader("Content-Disposition", "attachment; filename=" . 'coursedata.txt');
		}
		else 
			$response->addHeader("Content-type", "text/html");
		return $response;		
	}
}

?>
