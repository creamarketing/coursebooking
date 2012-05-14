<?php

class TeacherReports extends DataObject {
	public static function generateCoursesReport($teacherID, $termID, $pdf) {
		$teacher = DataObject::get_by_id('Teacher', $teacherID);
		if (!$teacher)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Teacher'] = $teacher;
		if ($termID == 0)
			$customFields['Courses'] = $teacher->Courses();
		else {
			$courses = $teacher->Courses();
			if ($courses) {
				$filteredCourses = new DataObjectSet();
				foreach ($courses as $course) {
					if ($course->TermID == $termID)
						$filteredCourses->push($course);
				}
				$courses = $filteredCourses;
			}
			$customFields['Courses'] = $courses;
		}
		
		if ($pdf) {
			$PDFfilename = 'TeacherCoursesReport_' . $teacher->Surname . '_' . $teacher->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherReports')->renderWith('Reports/TeacherCoursesReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('TeacherReports')->renderWith('Reports/TeacherCoursesReport', $customFields);		
	}
	
	public static function generateAgendaReport($teacherID, $week, $pdf) {
		$teacher = DataObject::get_by_id('Teacher', $teacherID);
		if (!$teacher || $week == 0)
			return;
		
		$year = date('Y');
		
		$customFields['Today'] = date('d.m.Y');
		$customFields['Week'] = new ArrayData(array(
			'Number' => $week,
			'Start' => date('d.m.Y', strtotime("$year-W$week-1")),
			'End' => date('d.m.Y', strtotime("$year-W$week-7"))
		));
		$customFields['Teacher'] = $teacher;
		$customFields['WeekDay'] = array();
		
		for ($i=1;$i<8;$i++) {
			$customFields['WeekDay'][$i] = new DataObjectSet();
		}
		
		$courseDates = DataObject::get('CourseDate', "CourseDateLink.CourseDateID = CourseDate.ID AND WEEK(CourseDate.TimeStart) = $week", null, "LEFT JOIN CourseDateLink ON CourseDateLink.TeacherID = $teacherID");
		
		if ($courseDates && $courseDates->Count()) {
			foreach($courseDates as $courseDate) {
				$weekDay = date('N', strtotime($courseDate->TimeStart));
				$customFields['WeekDay'][$weekDay]->push($courseDate);
			}
		}
		
		foreach ($customFields['WeekDay'] as $weekDay) {
			$weekDay->sort('TimeStart ASC');
		}
		
		if ($pdf) {
			$PDFfilename = 'TeacherAgendaReport_' . $teacher->Surname . '_' . $teacher->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherReports')->renderWith('Reports/TeacherAgendaReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('TeacherReports')->renderWith('Reports/TeacherAgendaReport', $customFields);		
	}
}

?>
