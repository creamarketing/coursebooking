<?php

class CourseReports extends DataObject {
	public static function generateParticipatorsReport($courseID, $pdf) {
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Course'] = $course;
		
		if ($pdf) {
			$PDFfilename = 'CourseParticipatorsReport_' . $course->CourseCode . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('CourseReports')->renderWith('Reports/CourseParticipatorsReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('CourseReports')->renderWith('Reports/CourseParticipatorsReport', $customFields);		
	}
	
	public static function generateTeachersReport($courseID, $pdf) {
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Course'] = $course;
		
		if ($pdf) {
			$PDFfilename = 'CourseTeachersReport_' . $course->CourseCode . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('CourseReports')->renderWith('Reports/CourseTeachersReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('CourseReports')->renderWith('Reports/CourseTeachersReport', $customFields);		
	}	
	
	public static function generateCourseRequestsReport($courseID, $pdf) {
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Course'] = $course;
		
		if ($pdf) {
			$PDFfilename = 'CourseCourseRequestsReport_' . $course->CourseCode . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('CourseReports')->renderWith('Reports/CourseRequestsReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('CourseReports')->renderWith('Reports/CourseRequestsReport', $customFields);		
	}		
	
	public static function generateCourseDatesReport($courseID, $pdf) {
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Course'] = $course;
		
		if ($pdf) {
			$PDFfilename = 'CourseDatesReport_' . $course->CourseCode . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('CourseReports')->renderWith('Reports/CourseDatesReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('CourseReports')->renderWith('Reports/CourseDatesReport', $customFields);		
	}		
	
}

?>
