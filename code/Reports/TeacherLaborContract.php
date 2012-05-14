<?php

class TeacherLaborContract extends DataObject {
	
	public static function generateLaborContract($teacherID, $pdf) {
		$teacher = DataObject::get_by_id('Teacher', $teacherID);
		
		$customFields['Today'] = date('d.m.Y');
		$customFields['Teacher'] = $teacher;
		
		$coursesDataSet = new DataObjectSet();
		$totalHours = 0;
		
		$periodStart = null;
		$periodEnd = null;
		
		$courses = $teacher->Courses();
		foreach ($courses as $course) {
			$startDate = $course->RecDateStart;
			$endDate = $course->RecDateEnd;
			
			if ($periodStart === null || $periodStart > $startDate)
				$periodStart = $startDate;
			if ($periodEnd === null || $periodEnd < $endDate)
				$periodEnd = $endDate;
			
			$courseDates = $course->CourseDates();
			$courseHours = 0;
			$courseLessions = 0;

			foreach ($courseDates as $courseDate) {
				foreach ($courseDate->CourseDateLinks() as $courseDateLink) {
					if ($courseDateLink->TeacherID != $teacherID) 
						continue;
				
					$lessions = $courseDateLink->Lessions;
				
					$courseLessions += $lessions;
					$courseHours += number_format(($lessions * 45.0) / 60, 2);
				}
			}
			
			$explodedStartDate = explode('-', $startDate);
			$explodedEndDate = explode('-', $endDate);			
			
			$maxNameLength = 50;
			$courseName = $course->Name;
			if (strlen($courseName) > $maxNameLength)
				$courseName = substr($courseName, 0, $maxNameLength) . '...';
			
			$coursesDataSet->push(new ArrayData(array(
						'StartDate'	=> implode('.', array($explodedStartDate[2], $explodedStartDate[1], $explodedStartDate[0])),
						'EndDate'	=> implode('.', array($explodedEndDate[2], $explodedEndDate[1], $explodedEndDate[0])),
						'Hours'		=> $courseHours,
						'MinParticipators' => $course->MinParticipators,
						'CourseCode' => $course->CourseCode,
						'Name' => $courseName,
						'Type' => $course->CourseType()->Code,
						'MainLocation' => $course->MainLocation,
						'Lessions' => $courseLessions,
						'Hours' => $courseHours
					)));
			
			$totalHours += $courseHours;
		}
		
		$explodedStartDate = explode('-', $periodStart);
		$explodedEndDate = explode('-', $periodEnd);
		
		if ($periodStart !== null && $periodEnd !== null)
			$customFields['EmploymentPeriod'] = implode('.', array($explodedStartDate[2], $explodedStartDate[1], $explodedStartDate[0])) . ' - ' . implode('.', array($explodedEndDate[2], $explodedEndDate[1], $explodedEndDate[0]));
		$customFields['Courses'] = $coursesDataSet;
		$customFields['TotalHours'] = $totalHours;
		
		if ($pdf) {
			$PDFfilename = 'LaborContract_' . $teacher->Surname . '_' . $teacher->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherLaborContract')->renderWith('Reports/TeacherLaborContract', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('TeacherLaborContract')->renderWith('Reports/TeacherLaborContract', $customFields);
	}
}

?>
