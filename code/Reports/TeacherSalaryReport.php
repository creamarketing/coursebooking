<?php

class TeacherSalaryReport extends DataObject {	
	public static function generateEmployeesReport($teacherID, $startDate, $endDate, $pdf) {
		if ($teacherID == 0)
			return self::generateAllEmployeesReport($startDate, $endDate, $pdf);
		else
			return self::generateEmployeeReport($teacherID, $startDate, $endDate, $pdf);
	}
	
	private static function grabData($startDate, $endDate, $specificTeacherID = null) {
		$rowCounter = 0;
		$expenseAccounts = DataObject::get('ExpenseAccount');
		
		if ($specificTeacherID !== null) {
			$teachers = new DataObjectSet();
			$teachers->push(DataObject::get_by_id('Teacher', (int)$specificTeacherID));
		}
		else 
			$teachers = DataObject::get('Teacher');
		
		$expenseAccountsDataset = new DataObjectSet();
		
		foreach ($expenseAccounts as $expenseAccount) {
			$teachersDataSet = new DataObjectSet();
			
			$expenseAccountLessions = 0;
			$expenseAccountHours = 0;
			$expenseAccountTotalSalary = 0;
		
			foreach ($teachers as $teacher) {
				$teacherTotalLessions = 0;
				$teacherTotalHours = 0;
				$teacherTotalSalary = 0;

				$teacherID = $teacher->ID;

				// Get all of our coursedates and group by PaymentMonth
				$query = new SQLQuery();
				$query->select(array('CourseDateLink.PaymentMonth'));
				$query->from('CourseDateLink');
				$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');				
				$query->leftJoin('Course', 'Course.ID = CourseDate.CourseID');
				$query->where("CourseDateLink.TeacherID = $teacherID AND Course.ExpenseAccountID = {$expenseAccount->ID}");
				if ($startDate !== null && $endDate !== null) {
					$startMonth = date('m', $startDate);
					$endMonth = date('m', $endDate);

					$query->where("CourseDateLink.PaymentMonth >= $startMonth AND CourseDateLink.PaymentMonth <= $endMonth");
				}
				elseif ($startDate !== null) {
					$startMonth = date('m', $startDate);
					$query->where("CourseDateLink.PaymentMonth >= $startMonth");
				}
				elseif ($endDate !== null) {
					$endMonth = date('m', $endDate);
					$query->where("CourseDateLink.PaymentMonth <= $endMonth");
				}			
				$query->groupby('PaymentMonth');
				$query_result_grouped = $query->execute();

				if ($query_result_grouped->numRecords() == 0) {
					continue;	
				}

				$dates_ht = new DataObjectSet();

				// Now go trough all our data in groups of payment month
				foreach($query_result_grouped as $row) {			
					$query = new SQLQuery();
					$query->select(array('CourseDate.TimeStart', 
										 'CourseDate.TimeEnd',
										 'CourseDateLink.PaymentMonth',
										 'MONTH(CourseDate.TimeStart) AS MonthNumber',
										 'CourseDateLink.TeacherHourTypeID',
										 'CourseDateLink.TeacherSalaryClassID'));
					$query->from('CourseDateLink');
					$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');
					$query->leftJoin('Course', 'Course.ID = CourseDate.CourseID');
					$query->where("CourseDateLink.TeacherID = $teacherID AND CourseDateLink.PaymentMonth = {$row['PaymentMonth']} AND Course.ExpenseAccountID = {$expenseAccount->ID}");
					$query->groupby('TeacherHourTypeID');
					$query_result_grouped_ht = $query->execute();

					foreach($query_result_grouped_ht as $ht_row) {
						$periodTotalLessions = 0;
						$periodTotalHours = 0;
						$periodTotalSalary = 0;
						$minDate = null;
						$maxDate = null;

						$dates = new DataObjectSet();

						$query = new SQLQuery();
						$query->select(array('CourseDate.TimeStart',
											'CourseDate.TimeEnd',
											'CourseDateLink.Lessions',
											'CourseDateLink.PaymentMonth',
											'CourseDateLink.TeacherHourTypeID',
											'CourseDateLink.Locked',
											'Course.ID AS CourseID',
											'MONTH(CourseDate.TimeStart) AS MonthNumber'));
						$query->from('CourseDateLink');
						$query->leftJoin('CourseDate', 'CourseDate.ID = CourseDateLink.CourseDateID');
						$query->leftJoin('Course', 'Course.ID = CourseDate.CourseID');
						$query->where("CourseDateLink.TeacherID = $teacherID AND CourseDateLink.PaymentMonth = {$row['PaymentMonth']} AND CourseDateLink.TeacherHourTypeID = {$ht_row['TeacherHourTypeID']} AND Course.ExpenseAccountID = {$expenseAccount->ID}");
						$query->orderby('TimeStart');
						$query_result = $query->execute();
						
						$lockedLinks = 0;

						foreach ($query_result as $date_row) {									
							/*$maxNameLength = 50;
							$course = DataObject::get_by_id('Course', $date_row['CourseID']);
							$courseName = $course->CourseCode . ' - ' . $course->Name;
							if (strlen($courseName) > $maxNameLength)
								$date_row['CourseName'] = substr($courseName, 0, $maxNameLength) . '...';
							else
								$date_row['CourseName'] = $courseName;*/

							if ($minDate === null || strtotime($date_row['TimeStart']) < $minDate)
								$minDate = strtotime($date_row['TimeStart']);
							if ($maxDate === null || strtotime($date_row['TimeEnd']) > $maxDate)
								$maxDate = strtotime($date_row['TimeStart']);

							$date_row['TimeStart'] = date('d.m.Y', strtotime($date_row['TimeStart']));
							$date_row['TimeEnd'] = date('d.m.Y', strtotime($date_row['TimeEnd']));				

							$hours = number_format(($date_row['Lessions'] * 45.0) / 60, 2);

							$periodTotalLessions += $date_row['Lessions'];
							$periodTotalHours += $hours;
							
							if ($date_row['Locked'])
								$lockedLinks++;
						}

						$hourType = DataObject::get_by_id('HourType', $ht_row['TeacherHourTypeID']);
						$hourTypeCode = $hourType ? $hourType->Code : '';
						$salaryClass = DataObject::get_by_id('TeacherSalaryClass', $ht_row['TeacherSalaryClassID']);
						$salaryHour = $salaryClass ? $salaryClass->SalaryHour : 0;
						$paymentMonth = $ht_row['PaymentMonth'];
						if ($hourType && $hourType->HasHours)
							$periodTotalSalary = $periodTotalHours * $salaryHour;
						else
							$periodTotalSalary = 0;

						$rowCounter++;

						$dates_ht->push(new ArrayData(array(
							'RowNumber' => $rowCounter,
							'Locked' => ($lockedLinks == $query_result->numRecords()) ? true : false,
							'PaymentMonth' => $paymentMonth,
							'HourType' => $hourTypeCode,
							'TimeStart' => date('d.m.Y', $minDate),
							'TimeEnd' => date('d.m.Y', $maxDate),
							'Lessions' => number_format($periodTotalLessions, 2),
							'Hours' => number_format($periodTotalHours, 2),
							'Salary' => number_format($periodTotalSalary, 2),
							'SalaryHour' => $salaryHour,
							'ExpenseAccount' => $expenseAccount,
							'TeacherName' => $teacher->Surname . ' ' . $teacher->FirstName
						)));

						$teacherTotalLessions += $periodTotalLessions;
						$teacherTotalHours += $periodTotalHours;
						$teacherTotalSalary += $periodTotalSalary;
					}
				}				
				
				$teachersDataSet->push(new ArrayData(array(
					'Dates' => $dates_ht, 
					'TotalLessions' => number_format($teacherTotalLessions, 2),
					'TotalHours' => number_format($teacherTotalHours, 2),
					'TotalSalary' => number_format($teacherTotalSalary, 2),
					'Surname' => $teacher->Surname,
					'FirstName' => $teacher->FirstName
				)));
				
				$expenseAccountLessions += $teacherTotalLessions;
				$expenseAccountHours += $teacherTotalHours;
				$expenseAccountTotalSalary += $teacherTotalSalary;					
			}
			
			if ($teachersDataSet->Count()) // Only include this expense account if we have data
				$expenseAccountsDataset->push(new ArrayData(array(
					'Teachers' => $teachersDataSet,
					'TotalLessions' => number_format($expenseAccountLessions, 2),
					'TotalHours' => number_format($expenseAccountHours, 2),
					'TotalSalary' => number_format($expenseAccountTotalSalary, 2),				
				)));
		}
		return $expenseAccountsDataset;
	}
	
	public static function generateAllEmployeesReport($startDate, $endDate, $pdf) {
		$customFields['Today'] = date('d.m.Y');
		$customFields['ExpenseAccounts'] = self::grabData($startDate, $endDate);
			
		if ($pdf) {
			$PDFfilename = 'SalaryReport_all_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherSalaryReport')->renderWith('Reports/TeacherAllEmployeesReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('TeacherSalaryReport')->renderWith('Reports/TeacherAllEmployeesReport', $customFields);
	}

	
	public static function generateEmployeeReport($teacherID, $startDate, $endDate, $pdf) {		
		$customFields['Today'] = date('d.m.Y');
		$customFields['ExpenseAccounts'] = self::grabData($startDate, $endDate, $teacherID);
			
		if ($pdf) {
			$PDFfilename = 'SalaryReport_single_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('TeacherSalaryReport')->renderWith('Reports/TeacherAllEmployeesReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('TeacherSalaryReport')->renderWith('Reports/TeacherAllEmployeesReport', $customFields);
	}
	
}

?>
