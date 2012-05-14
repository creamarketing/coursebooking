<?php

class SignupHandler extends Controller {

	public function preConfirm() {	
		$confirmAll = false;
		
		if (!isset($_POST['courseID']))
			$confirmAll = true;
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		if ($confirmAll) {
			$ghost = DataObject::get_one('GhostParticipator', 'Hash = \'' . Session::get('GhostHash') . '\'');
			
			if ($ghost && $ghost->Alive) {
				$courses = $ghost->Courses();
				
				if (!$courses->exists())
					return '';
				
				$resultHTML = '<div style="font-size: 14px">' . _t('SignupHandler.CONFIRMSIGNUPFOR', 'Confirm signup for the following courses') . ':</div><br/>';
				foreach ($courses as $course) {
					$resultHTML .= '<div style="font-size: 16px; font-weight: bold">' . $course->CourseCode . ' ' . $course->Name . '</div><br/>';		
				}
				
				$response = new SS_HTTPResponse($resultHTML);
				$response->addHeader("Content-type", "text/html");
		
				return $response;				
			} else {
				return 'Idle too long';
			}
		}
			
		return '';
	}	
	
	public function postConfirm() {
		$confirmAll = false;
		
		if (!isset($_POST['courseID']))
			$confirmAll = true;
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		if ($confirmAll) {
			if (strlen(Session::get('GhostHash')) > 0) {
				$ghost = DataObject::get_one('GhostParticipator', 'Hash = \'' . Session::get('GhostHash') . '\'');

				if ($ghost->exists() && $ghost->Alive) {			
					$ghost->LastEdited = date('Y-m-d h:i:s');
					$ghost->write();

					$currentUser = CourseBookingExtension::currentUser();
					
					if ($currentUser && $ghost->Courses()->Count() > 0) {
						foreach ($ghost->Courses() as $course) {
							$ghost->Courses()->remove($course->ID);
							
							$courseRequest = new CourseRequest();
							$courseRequest->ParticipatorID = $currentUser->ID;
							$courseRequest->CourseID = $course->ID;
							$courseRequest->Status = 'Notified';
							$courseRequest->write();
						}
					}
				} else {
					if ($ghost->exists() && $ghost->Alive == false)
						$ghost->delete();

					$ghost = new GhostParticipator();
					$ghost->Hash = md5(time());
					$ghost->write();

					Session::set('GhostHash', $ghost->Hash);				
				}
			} else {
				$ghost = new GhostParticipator();
				$ghost->Hash = md5(time());
				$ghost->write();

				Session::set('GhostHash', $ghost->Hash);
			}			
		}
		
		return '';
	}	
	
	public function preSignup() {	
		if (!isset($_POST['courseID']))
			return '';
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		$courseID = (int)$_POST['courseID'];
		
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course->exists())
			return '';

		$resultHTML = '<div style="font-size: 14px">' . _t('SignupHandler.SIGNUPFOR', 'Signup to the following course') . ':</div><br/>';
		$resultHTML .= '<div style="font-size: 16px; font-weight: bold">' . $course->CourseCode . ' ' . $course->Name . '</div><br/>';
		
		$resultHTML .= '<div style="font-size: 18px; font-weight: bold; display: inline-block; vertical-align: top;">' . _t('SignupHandler.STATUS', 'Status') . ': ';
		
		if ($course->Full) 
			$resultHTML .= '<div style="color: #FF0000; display: inline-block; vertical-align: top;">' . _t('SignupHandler.STATUS_NOFREESPOTSAVAILABLE', 'No free spots available, you will be put in queue') . '</div>';
		else
			$resultHTML .= '<div style="color: #00AA00; display: inline-block; vertical-align: top;">' . _t('SignupHandler.STATUS_FREESPOTSAVAILABLE', 'Free spots available') . '</div>';
		$resultHTML .= '</div><br/><br/>';
		$resultHTML .= '<div style="font-size: 14px">' . _t('SignupHandler.CONFIRMINFO', 'You must confirm this reserveration on the \'Your courses\'-page after you have reservered spots to the courses you want to participate in.') . '</div>';
				
		$response = new SS_HTTPResponse($resultHTML);
		$response->addHeader("Content-type", "text/html");
		
		return $response;
	}
	
	public function postSignup() {
		if (!isset($_POST['courseID']))
			return '';
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		$courseID = (int)$_POST['courseID'];
		
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course->exists())
			return '';
		
		if (strlen(Session::get('GhostHash')) > 0) {
			$ghost = DataObject::get_one('GhostParticipator', 'Hash = \'' . Session::get('GhostHash') . '\'');
			
			if ($ghost->exists() && $ghost->Alive) {			
				$ghost->LastEdited = date('Y-m-d h:i:s');
				$ghost->write();

				$ghost->Courses()->add($courseID);				
			} else {
				if ($ghost->exists() && $ghost->Alive == false)
					$ghost->delete();
				
				$ghost = new GhostParticipator();
				$ghost->Hash = md5(time());
				$ghost->write();
			
				$ghost->Courses()->add($courseID);				
				
				Session::set('GhostHash', $ghost->Hash);				
			}
		} else {
			$ghost = new GhostParticipator();
			$ghost->Hash = md5(time());
			$ghost->write();
			
			$ghost->Courses()->add($courseID);
			
			Session::set('GhostHash', $ghost->Hash);
		}
	}
	
	public function preSignupCancel() {	
		if (!isset($_POST['courseID']))
			return '';
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		$courseID = (int)$_POST['courseID'];
		
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course->exists())
			return '';

		$resultHTML = '<div style="font-size: 14px">' . _t('SignupHandler.CANCELFROM', 'Cancel the following course') . ':</div><br/>';
		$resultHTML .= '<div style="font-size: 16px; font-weight: bold">' . $course->CourseCode . ' ' . $course->Name . '</div><br/>';
		
		$resultHTML .= '<div style="font-size: 18px; font-weight: bold">' . _t('SignupHandler.STATUS', 'Status') . ': ';
		
		if ($course->Full && $course->Participators()->Count() >= $course->MaxParticipators)
			$resultHTML .= '<div style="color: #FF0000; display: inline-block">' . _t('SignupHandler.STATUS_QUEUED', 'In queue') . '</div>';
		else
			$resultHTML .= '<div style="color: #00AA00; display: inline-block">' . _t('SignupHandler.STATUS_RESERVEREDSPOT', 'Reserved spot') . '</div>';
		
		$resultHTML .= '</div>';
				
		$response = new SS_HTTPResponse($resultHTML);
		$response->addHeader("Content-type", "text/html");
		
		return $response;
	}
	
	public function postSignupCancel() {
		if (!isset($_POST['courseID']))
			return '';
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		$courseID = (int)$_POST['courseID'];
		
		$course = DataObject::get_by_id('Course', $courseID);
		if (!$course->exists())
			return '';
		
		if (strlen(Session::get('GhostHash')) > 0) {
			$ghost = DataObject::get_one('GhostParticipator', 'Hash = \'' . Session::get('GhostHash') . '\'');
			
			if ($ghost->exists() && $ghost->Alive) {			
				$ghost->LastEdited = date('Y-m-d h:i:s');
				$ghost->write();

				$ghost->Courses()->remove($courseID);				
			} else {
				if ($ghost->exists() && $ghost->Alive == false)
					$ghost->delete();
				
				$ghost = new GhostParticipator();
				$ghost->Hash = md5(time());
				$ghost->write();
						
				Session::set('GhostHash', $ghost->Hash);				
			}
		} else {
			$ghost = new GhostParticipator();
			$ghost->Hash = md5(time());
			$ghost->write();
						
			Session::set('GhostHash', $ghost->Hash);
		}
	}	
	
	public function preCourseCancel() {			
		if (!isset($_POST['courseID']))
			return '';
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		
		$courseID = (int)$_POST['courseID'];
		$currentUser = CourseBookingExtension::currentUser();

		if ($currentUser && $currentUser instanceof Participator) {
			$courseRequest = $currentUser->CourseRequests()->find('CourseID', $courseID);

			if (!$courseRequest)
				return '';
			
			$course = $courseRequest->Course();

			$query = new SQLQuery();
			$query->select('TimeStart');
			$query->from('CourseDate');
			$query->where('CourseID = ' . $courseID);
			$query->orderby('TimeStart ASC');
			$query->limit('2');
					
			$result = $query->execute();
			
			$cancelFee = 0;
			$today = time();
			
			$cancellationDays = $course->CourseUnit()->CancellationDays;
			$cancellationFee = $course->CourseUnit()->CancellationFee;
			
			if ($result->numRecords()) {
				// We can only cancel a course with 10 or less lessions for free, xxx days before it starts
				if ($course->TotalLessions <= 10) {
					$firstTime = $result->first();
					$firstTimestamp = strtotime($firstTime['TimeStart']);

					if ($firstTimestamp > $today) { // In the future
						$diff = $firstTimestamp - $today;
						if ($diff >= ($cancellationDays*60*60*24)) // In days
							$cancelFee = 0;
						else 
							$cancelFee = 2;
					}
				} // 1 week ahead no fee, before the second coursedate 50% fee, otherwise full courseprice
				else {
					$firstTime = $result->first();

					if ($result->numRecords() > 1) {
						$secondTime = $result->next();
					}
					else {
						$secondTime = $result->first();
					}

					$firstTimestamp = strtotime($firstTime['TimeStart']);
					$secondTimestamp = strtotime($secondTime['TimeStart']);

					if ($firstTimestamp > $today) {// First date is in the future
						$diff = $firstTimestamp - $today;
						if ($diff >= ($cancellationDays*60*60*24)) { // One week
							$cancelFee = 0;
						} else {
							$cancelFee = 1;
						}
					}
					else if ($secondTimestamp > $today) { // Second date is in the future
						$diff = $secondTimestamp - $today;
						if ($diff >= 0) {
							$cancelFee = 1;
						} 
					} 
					else {
						$cancelFee = 2;
					}
				}
			}			
			
			$resultHTML = '<div style="font-size: 14px">' . _t('SignupHandler.CANCELCOURSE', 'Cancel the following course') . ':</div><br/>';
			$resultHTML .= '<div style="font-size: 16px; font-weight: bold">' . $course->CourseCode . ' ' . $course->Name . '</div><br/>';
			if ($cancelFee == 0) 
				$resultHTML .= '<div style="font-size: 13px">' . sprintf(_t('SignupHandler.CANCELFEE_NOFEE', 'You will not be billed for this course if you cancel it (%s days before start).'), $cancellationDays) . '</div>';
			if ($cancelFee == 1) 
				$resultHTML .= '<div style="font-size: 13px">' . sprintf(_t('SignupHandler.CANCELFEE_SMALLFEE', 'You will be billed for 50% of the course price or a maximum fee of %s if you cancel this course.'), $cancellationFee . '€') . '</div>';
			$resultHTML .= '<br/><div style="font-size: 14px">' . _t('SignupHandler.WARNCANCELCOURSE', '<strong>NOTICE!</strong><br>You cannot signup again to this course again after you cancel it.') . '</div>';

			$response = new SS_HTTPResponse($resultHTML);
			$response->addHeader("Content-type", "text/html");

			return $response;				
		} else {
			Director::redirectBack();
		}
			
		return '';
	}	
	
	public function postCourseCancel() {
		if (!isset($_POST['courseID']))
			return '';
		
		if (isset($_POST['locale']))
			i18n::set_locale($_POST['locale']);
		

		$courseID = (int)$_POST['courseID'];
		$currentUser = CourseBookingExtension::currentUser();
					
		if ($currentUser && $currentUser instanceof Participator) {
			$courseRequest = $currentUser->CourseRequests()->find('CourseID', $courseID);
			
			if (!$courseRequest)
				return '';
			
			$courseRequest->Status = 'Canceled';
			$courseRequest->write();
		}
					
		return '';
	}	
	
}

?>
