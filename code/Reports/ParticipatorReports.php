<?php

class ParticipatorReports extends DataObject {
	public static function generateCoursesReport($participatorID, $termID, $pdf) {
		$participator = DataObject::get_by_id('Participator', $participatorID);
		if (!$participator)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Participator'] = $participator;
		if ($termID == 0)
			$customFields['Courses'] = $participator->Courses();
		else {
			$courses = $participator->Courses();
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
			$PDFfilename = 'ParticipatorCoursesReport_' . $participator->Surname . '_' . $participator->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('ParticipatorReports')->renderWith('Reports/ParticipatorCoursesReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('ParticipatorReports')->renderWith('Reports/ParticipatorCoursesReport', $customFields);		
	}
	
	public static function generateCourseRequestsReport($participatorID, $termID, $pdf) {
		$participator = DataObject::get_by_id('Participator', $participatorID);
		if (!$participator)
			return;
				
		$customFields['Today'] = date('d.m.Y');
		$customFields['Participator'] = $participator;
		if ($termID == 0)
			$customFields['CourseRequests'] = $participator->CourseRequests();
		else {
			$courseRequests = $participator->CourseRequests();
			if ($courseRequests) {
				$filteredCourseRequests = new DataObjectSet();
				foreach ($courseRequests as $courseRequest) {
					if ($courseRequest->Course() && $courseRequest->Course()->TermID == $termID)
						$filteredCourseRequests->push($courseRequest);
				}
				$courseRequests = $filteredCourseRequests;
			}
			$customFields['CourseRequests'] = $courseRequests;
		}
		
		if ($pdf) {
			$PDFfilename = 'ParticipatorCourseRequestsReport_' . $participator->Surname . '_' . $participator->FirstName . '_' . $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('ParticipatorReports')->renderWith('Reports/ParticipatorCourseRequestsReport', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('ParticipatorReports')->renderWith('Reports/ParticipatorCourseRequestsReport', $customFields);	
	}	
	
	public static function generateCourseRequestInvoice($participatorID, $invoiceID, $pdf) {
		$participator = DataObject::get_by_id('Participator', $participatorID);
		if (!$participator)
			return;
			
		$invoice = DataObject::get_by_id('CourseRequestInvoice', $invoiceID);
		if (!$invoice)
			return;
						
		$customFields['Today'] = date('d.m.Y');
		$customFields['Participator'] = $participator;
		$customFields['Invoice'] = $invoice;
		$customFields['CourseUnit'] = $invoice->CourseUnit();
		$customFields['CourseRequest'] = $invoice->CourseRequest();
		
		if ($pdf) {
			$PDFfilename = 'Participator_' . $participator->Surname . '_' . $participator->FirstName . '_Invoice_' . $invoice->InvoiceNumber . '_'. $customFields['Today'] . '.pdf';
			$customFields['DocumentType'] = 'pdf';
			return singleton('PDFRenditionService')->render(singleton('ParticipatorReports')->renderWith('Reports/CourseRequestInvoice', $customFields), 'browser', $PDFfilename);
		}

		$customFields['DocumentType'] = 'html';		
		return singleton('ParticipatorReports')->renderWith('Reports/CourseRequestInvoice', $customFields);		
	}
	
}

?>
