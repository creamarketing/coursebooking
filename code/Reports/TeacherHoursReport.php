<?php

class TeacherHoursReport extends DataObject {
	
	public static function generateMonthlyReport($teacherID, $startDate, $endDate, $pdf) {		
		$teacher = DataObject::get_by_id('Teacher', $teacherID);

		$startDate = date('Y-m-d H:i:s', $startDate);
		$endDate = date('Y-m-d H:i:s', $endDate);

		// Get all of our coursedates
		$query = new SQLQuery();
		$query->select(array('CourseDate.TimeStart', 
							 'CourseDate.TimeEnd',
							 'MONTH(CourseDate.TimeStart) AS MonthNumber'));
		$query->from('CourseDateLink');
		$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');
		$query->where("TimeStart >= '$startDate' AND TimeEnd <= '$endDate' AND CourseDateLink.TeacherID = $teacherID");
		$query->groupby('MonthNumber');
		$query_result_grouped = $query->execute();
											
		$periodTotalHours = 0;
		$periodTotalLessions = 0;
		$sections = new DataObjectSet();		
		
		foreach($query_result_grouped as $row) {
			$query = new SQLQuery();
			$query->select(array('CourseDate.TimeStart', 
								'CourseDateLink.Lessions',
								'CourseDateLink.PaymentMonth',
								'Course.ID AS CourseID',
								'MONTH(CourseDate.TimeStart) AS MonthNumber'));
			$query->from('CourseDateLink');
			$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');
			$query->leftJoin('Course', 'Course.ID = CourseDate.CourseID');
			$query->where("TimeStart >= '$startDate' AND TimeEnd <= '$endDate' AND CourseDateLink.TeacherID = $teacherID AND MONTH(CourseDate.TimeStart) = {$row['MonthNumber']}");
			$query->orderby('TimeStart');
			$query_result = $query->execute();
		
			$dates = new DataObjectSet();
			$sectionTotalLessions = 0;
			$sectionTotalHours = 0;
			
			foreach ($query_result as $date_row) {						
				$maxNameLength = 50;
				$course = DataObject::get_by_id('Course', $date_row['CourseID']);
				$courseName = $course->CourseCode . ' - ' . $course->Name;
				if (strlen($courseName) > $maxNameLength)
					$date_row['CourseName'] = substr($courseName, 0, $maxNameLength) . '...';
				else
					$date_row['CourseName'] = $courseName;
			
				$date_row['TimeStart'] = date('d.m.Y', strtotime($date_row['TimeStart']));
				$date_row['Hours'] = number_format(($date_row['Lessions'] * 45.0) / 60, 2);
					
				$dates->push(new ArrayData(array(
						'Month' => $row['MonthNumber'],
						'CourseName' => $date_row['CourseName'],
						'TimeStart' => $date_row['TimeStart'],
						'Hours' => $date_row['Hours'],
						'Lessions' => $date_row['Lessions']
					)));
					
				$sectionTotalLessions += $date_row['Lessions'];
				$sectionTotalHours += $date_row['Hours'];
			}
			
			//$dates->sort('TimeStart');
						
			$periodTotalLessions += $sectionTotalLessions;
			$periodTotalHours += $sectionTotalHours;			
			
			$sections->push(new ArrayData(array(
				'Dates' => $dates, 
				'TotalLessions' => number_format($sectionTotalLessions, 2),
				'TotalHours' => number_format($sectionTotalHours, 2)
			)));
		}
		
		$customFields['Today'] = date('d.m.Y');
		$customFields['Teacher'] = $teacher;
		$customFields['Sections'] = $sections;
		$customFields['TotalHours'] = number_format($periodTotalHours, 2);
		$customFields['TotalLessions'] = number_format($periodTotalLessions, 2);
		
		if ($pdf) {
			$PDFfilename = 'HoursReport_' . $teacher->Surname . '_' . $teacher->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherHoursReport')->renderWith('Reports/TeacherHoursMonthlyReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('TeacherHoursReport')->renderWith('Reports/TeacherHoursMonthlyReport', $customFields);
	}	
	
	public static function generateWeeklyReport($teacherID, $startDate, $endDate, $pdf) {
		$teacher = DataObject::get_by_id('Teacher', $teacherID);

		$startDate = date('Y-m-d H:i:s', $startDate);
		$endDate = date('Y-m-d H:i:s', $endDate);

		// Get all of our coursedates
		$query = new SQLQuery();
		$query->select(array('CourseDate.TimeStart', 
							 'CourseDate.TimeEnd',
							 'WEEK(CourseDate.TimeStart) AS WeekNumber'));
		$query->from('CourseDateLink');
		$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');
		$query->where("TimeStart >= '$startDate' AND TimeEnd <= '$endDate' AND CourseDateLink.TeacherID = $teacherID");
		$query->groupby('WeekNumber');
		$query_result_grouped = $query->execute();
											
		$periodTotalHours = 0;
		$periodTotalLessions = 0;
		$sections = new DataObjectSet();		
		
		foreach($query_result_grouped as $row) {
			$query = new SQLQuery();
			$query->select(array('CourseDate.TimeStart', 
								'CourseDateLink.Lessions',
								'CourseDateLink.PaymentMonth',
								'Course.ID AS CourseID',
								'WEEK(CourseDate.TimeStart) AS WeekNumber'));
			$query->from('CourseDateLink');
			$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');
			$query->leftJoin('Course', 'Course.ID = CourseDate.CourseID');
			$query->where("TimeStart >= '$startDate' AND TimeEnd <= '$endDate' AND CourseDateLink.TeacherID = $teacherID AND WEEK(CourseDate.TimeStart) = {$row['WeekNumber']}");
			$query->orderby('TimeStart');
			$query_result = $query->execute();
		
			$dates = new DataObjectSet();
			$sectionTotalLessions = 0;
			$sectionTotalHours = 0;
			
			foreach ($query_result as $date_row) {						
				$maxNameLength = 50;
				$course = DataObject::get_by_id('Course', $date_row['CourseID']);
				$courseName = $course->CourseCode . ' - ' . $course->Name;
				if (strlen($courseName) > $maxNameLength)
					$date_row['CourseName'] = substr($courseName, 0, $maxNameLength) . '...';
				else
					$date_row['CourseName'] = $courseName;
			
				$date_row['TimeStart'] = date('d.m.Y', strtotime($date_row['TimeStart']));
				$date_row['Hours'] = number_format(($date_row['Lessions'] * 45.0) / 60, 2);
					
				$dates->push(new ArrayData(array(
						'Week' => $row['WeekNumber'],
						'CourseName' => $date_row['CourseName'],
						'TimeStart' => $date_row['TimeStart'],
						'Hours' => $date_row['Hours'],
						'Lessions' => $date_row['Lessions']
					)));
					
				$sectionTotalLessions += $date_row['Lessions'];
				$sectionTotalHours += $date_row['Hours'];
			}
			
			//$dates->sort('TimeStart');
						
			$periodTotalLessions += $sectionTotalLessions;
			$periodTotalHours += $sectionTotalHours;			
			
			$sections->push(new ArrayData(array(
				'Dates' => $dates, 
				'TotalLessions' => number_format($sectionTotalLessions, 2),
				'TotalHours' => number_format($sectionTotalHours, 2)
			)));
		}
		
		$customFields['Today'] = date('d.m.Y');
		$customFields['Teacher'] = $teacher;
		$customFields['Sections'] = $sections;
		$customFields['TotalHours'] = number_format($periodTotalHours, 2);
		$customFields['TotalLessions'] = number_format($periodTotalLessions, 2);
		
		if ($pdf) {
			$PDFfilename = 'HoursReport_' . $teacher->Surname . '_' . $teacher->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherHoursReport')->renderWith('Reports/TeacherHoursWeeklyReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';
		return singleton('TeacherHoursReport')->renderWith('Reports/TeacherHoursWeeklyReport', $customFields);		
	}
	
}

?>
