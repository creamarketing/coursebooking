<?php

class MyCoursesPage extends Page {
}

class MyCoursesPage_Controller extends Page_Controller {
	static $extensions = array(
		'CreaDataObjectExtension',
		'CourseBookingExtension'
	);
	
	protected $unconfirmedCourses = null;
	protected $confirmedCourses = null;
	protected $teacherCourses = null;
	protected $sortColumn = '';
	protected $sortDir = '';
	
	public function init() {
		parent::init();
		
		//Validator::set_javascript_validation_handler('none');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-form/jquery.form.js');
		Requirements::javascript('coursebooking/javascript/jquery.numeric.js');
		
		Requirements::javascript('coursebooking/javascript/fullcalendar-1.5.1.js');
		Requirements::css('coursebooking/css/fullcalendar-1.5.1.css');
		Requirements::css('coursebooking/css/fullcalendar.print-1.5.1.css', 'print');
		
		Requirements::customScript("var courseDatesURL = '{$this->AbsoluteLink()}courseDatesFeed';");
				
		Requirements::javascript('coursebooking/javascript/jquery.qtip-1.0.min.js');
		Requirements::javascript('coursebooking/javascript/jquery.printElement.min.js');
		
		Requirements::javascript('dialog_dataobject_manager/javascript/jquery.blockUI.js');
		
		$unconfirmedCourses = $this->updateUnconfirmedCoursesData();
		$confirmedCourses = new DataObjectSet();
		$teacherCourses = new DataObjectSet();
		$currentUser = CourseBookingExtension::currentUser();
		
		if ($currentUser && $currentUser instanceof Participator) {
			$courseRequests = $currentUser->CourseRequests();
			if ($courseRequests) {
				foreach ($courseRequests as $request) {
					$confirmedCourses->push($request->Course());
				}
			}
		}
		else if ($currentUser && $currentUser instanceof Teacher) {
			$teacherCourses = $currentUser->Courses();
		}
		
		$customData['sort'] = 'CourseCode';
		$customData['sort_dir'] = 'ASC';

		if (isset($_GET['sort']) && isset($_GET['sort_dir'])) {
			$customData['sort'] = $_GET['sort'];
			$customData['sort_dir'] = $_GET['sort_dir'];

			switch ($customData['sort']) {
				case 'code': $customData['sort'] = 'CourseCode'; break;
				case 'name': $customData['sort'] = 'NameList'; break;
				case 'location': $customData['sort'] = 'MainLocationOffice'; break;
				case 'freespots': $customData['sort'] = 'FreeSpots'; break;
				case 'startdate': $customData['sort'] = 'RecDateStart'; break;
				case 'stopdate': $customData['sort'] = 'RecDateEnd'; break;
				default: $customData['sort'] = 'CourseCode'; break;
			}			

			if ($unconfirmedCourses->exists())
				$unconfirmedCourses->sort($customData['sort'], $customData['sort_dir']);
			if ($confirmedCourses->exists())
				$confirmedCourses->sort($customData['sort'], $customData['sort_dir']);
			if ($teacherCourses->exists())
				$teacherCourses->sort($customData['sort'], $customData['sort_dir']);
		}	
		
		$this->sortColumn = $customData['sort'];
		$this->sortDir = $customData['sort_dir'];
		
		$this->unconfirmedCourses = $unconfirmedCourses;
		$this->confirmedCourses = $confirmedCourses;
		$this->teacherCourses = $teacherCourses;
	}
	
	public function getSortDir() {
		return $this->sortDir;
	}	
	
	public function getTeacherCourses() {
		if ($this->teacherCourses !== null)
			return $this->teacherCourses;
		return new DataObjectSet();		
	}
	
	public function getConfirmedCourses() {
		if ($this->confirmedCourses !== null)
			return $this->confirmedCourses;
		return new DataObjectSet();
	}
	
	public function getUnconfirmedCourses() {
		if ($this->unconfirmedCourses !== null)
			return $this->unconfirmedCourses;
		return new DataObjectSet();
	}
	
	private function updateUnconfirmedCoursesData() {
		if (strlen(Session::get('GhostHash')) == 0)
			return new DataObjectSet();
		
		$ghost = DataObject::get_one('GhostParticipator', 'Hash = \'' . Session::get('GhostHash') . '\'');
		
		if ($ghost && $ghost->Alive) {		
			$currentUser= CourseBookingExtension::currentUser();
			if ($currentUser && $currentUser instanceof Participator) {
				$courseRequests = $currentUser->CourseRequests();
				if ($courseRequests) {
					foreach ($courseRequests as $request) {
						$ghost->Courses()->remove($request->Course()->ID);
					}
				}
			}			
			
			return $ghost->Courses();			
		}
		
		return new DataObjectSet();
	}
	
	public function ParticipatorLoginForm() {
		return new MemberLoginForm($this, 'ParticipatorLoginForm');		
	}
	
	public function ParticipatorRegistrationForm() {
		$gender_array = array('' => '');
		$gender_array += singleton('Participator')->dbObject('Gender')->enumValues();
		foreach($gender_array as $key => &$value)
			$value = _t('Participator.GENDER_' . strtoupper($value), $value);
		
		$natlang_array = array('' => '');
		$natlang_array += singleton('Participator')->dbObject('NativeLanguage')->enumValues();
		foreach($natlang_array as $key => &$value)
			$value = _t('Participator.NATIVELANGUAGE_' . strtoupper($value), $value);		

		$education_array = array('' => '');
		$education_array += singleton('Participator')->dbObject('Education')->enumValues();
		foreach($education_array as $key => &$value)
			$value = _t('Participator.EDUCATION_' . strtoupper($value), $value);

		$occupation_array = array('' => '');
		$occupation_array += singleton('Participator')->dbObject('Occupation')->enumValues();
		foreach($occupation_array as $key => &$value)
			$value = _t('Participator.OCCUPATION_' . strtoupper($value), $value);		
		
		$fields = new FieldSet(
			new LiteralField('LeftGroupDivStart', '<div class="leftGroup">'),
				new TextField('FirstName', _t('Participator.FIRSTNAME', 'FirstName') . '<em>*</em>'),
				new TextField('Surname', _t('Participator.SURNAME', 'Surname') . '<em>*</em>'),
				$personNumber = new TextField('PersonalNumber', _t('Participator.PERSONALNUMBER', 'Personal number') . '<em>*</em>'),
				new DropdownField('Gender', _t('Participator.GENDER', 'Gender') . '<em>*</em>', $gender_array),
				new DropdownField('NativeLanguage', _t('Participator.NATIVELANGUAGE', 'Native language') . '<em>*</em>', $natlang_array),
				new TextField('PostAddress', _t('Participator.POSTADDRESS', 'Post address') . '<em>*</em>'),
				$postCodeField = new NumericFieldEx('PostCode', _t('Participator.POSTCODE', 'Post code') . '<em>*</em>'),						
				new TextField('PostOffice', _t('Participator.POSTOFFICE', 'Post office') . '<em>*</em>'),
			new LiteralField('LeftGroupDivEnd', '</div>'),
			new LiteralField('RightGroupDivStart', '<div class="rightGroup">'),
				new TextField('Phone', _t('Participator.PHONE', 'Phone') . '<em>*</em>'),
				new EmailField('Email', _t('Participator.EMAIL', 'Email') . '<em>*</em>'),
				new TextField('Profession', _t('Participator.PROFESSION', 'Profession')),
				new DropdownField('Education', _t('Participator.EDUCATION', 'Education') . '<em>*</em>', $education_array),
				new DropdownField('Occupation', _t('Participator.OCCUPATION', 'Occupation') . '<em>*</em>', $occupation_array),
				$noteGroup = new FieldGroup(
					$note = new TextAreaField('NoteParticipator', _t('Participator.NOTE', 'Note'))
				),
				$passwordGroup = new FieldGroup(
					$password = new ConfirmedPasswordField('Password', _t('Member.PASSWORD', 'Password') . '<em>*</em>', null, null, false, _t('Member.CONFIRMPASSWORD', 'Confirm password') . '<em>*</em>')
				),
			new LiteralField('RightGroupDivEnd', '</div>')
		);
		
		$noteGroup->addExtraClass('notegroup');
		$note->setRows(2);
		
		$passwordGroup->addExtraClass('confirmedpassword');
		$password->setCanBeEmpty(false);
		$password->minLength = 6;
		
		$postCodeField->setMaxLength(5);
		$postCodeField->setDefaultZeroValue('');
		$personNumber->setMaxLength(11);		
		
		//$leftGroup->addExtraClass('leftGroup');
		//$rightGroup->addExtraClass('rightGroup');		
		
		$actions = new FieldSet(
			new FormAction('RegisterParticipator', _t('MyCoursesPage.REGISTER', 'Register'))
		);
		
		$requirements = new RequiredFields('FirstName', 'Surname', 'PersonalNumber', 'Gender', 'NativeLanguage', 'PostAddress', 'PostCode', 'PostOffice', 'Phone', 'Email', 'Education', 'Occupation');
		
		$form = new Form($this, 'ParticipatorRegistrationForm', $fields, $actions, $requirements);
		/*
		Requirements::customScript('
        jQuery(document).ready(function() {
            jQuery("#Form_ParticipatorRegistrationForm").validate({
                rules: {
                    Surname: "required",
                    Email: {
                        required: true,
                        email: true
                    },
                    Note: {
                        required: true,
                        minlength: 20
                    }
                },
                messages: {
                    Surname: "I would really love to know your name",
                    Email: "It is quite important that you give me your actual email address so I can send you messages",
                    Note: "What are you thinking? Tell me"
                }
            });
        });
		', 'ParticipatorRegistrationForm');
		
		*/
		
		return $form;
	}
	
	public function RegisterParticipator($data, $form) {
		// Important: don't allow setting the ID!
		if(isset($data['ID'])) {
			$form->sessionMessage(_t('MyCoursesPage.IDNOTALLOWED', 'Registration failed! (ID not allowed)'), 'bad');
			return $this->redirectBack();
		}
		
		// Important: escape all data before doing any queries!
		$sqlData = Convert::raw2sql($data);
		
		// Important: safety-check that there is no existing member with this email adress!
		if($member = DataObject::get_one("Member", "`Email` = '". $sqlData['Email'] . "'")) {
			$form->sessionMessage(_t('Member.VALIDATIONMEMBEREXISTS', 'A member already exists with the same email address'), 'bad');
			return $this->redirectBack();		
		}
		else {
			$participator = new Participator();
			$form->saveInto($participator);
			$participator->Locale = Translatable::get_current_locale();
			$participator->DateFormat = 'dd/MM/yyyy';
			$participator->TimeFormat = 'H:mm';
			$participator->RegistrationMethod = 'External';
			$participator->write();
			
			$participator->login();
		}
		
		$form->sessionMessage(_t('MyCoursesPage.REGISTRATIONSUCCESSFUL', 'Registration successful'), 'good');
		$this->redirectBack();
	}
	
	public function dologin($data, $form) {
		$loginForm = $this->ParticipatorLoginForm();
		
		if($loginForm->performLogin($data)) {
			//$form->sessionMessage(_t('MyCoursesPage.LOGINSUCCESSFUL', 'Login successful'), 'good');		
			$form->clearMessage();
			$this->redirectBack();
		}
		else {
			$form->sessionMessage(_t('MyCoursesPage.LOGINFAILED', 'Login failed'), 'bad');
			$this->redirectBack();
		}
	}	
	
	public function ProfileForm() {
		$gender_array = singleton('Participator')->dbObject('Gender')->enumValues();
		foreach($gender_array as $key => &$value)
			$value = _t('Participator.GENDER_' . strtoupper($value), $value);
		
		$natlang_array = singleton('Participator')->dbObject('NativeLanguage')->enumValues();
		foreach($natlang_array as $key => &$value)
			$value = _t('Participator.NATIVELANGUAGE_' . strtoupper($value), $value);		

		$education_array = singleton('Participator')->dbObject('Education')->enumValues();
		foreach($education_array as $key => &$value)
			$value = _t('Participator.EDUCATION_' . strtoupper($value), $value);

		$occupation_array = singleton('Participator')->dbObject('Occupation')->enumValues();
		foreach($occupation_array as $key => &$value)
			$value = _t('Participator.OCCUPATION_' . strtoupper($value), $value);		
		
		$fields = new FieldSet(
			new HeaderField('Profile', _t('MyCoursesPage.PROFILE_TITLE', 'Profile'), 3),
			new LiteralField('LeftGroupDivStart', '<div class="leftGroup">'),
				new TextField('FirstName', _t('Participator.FIRSTNAME', 'FirstName')),
				new TextField('Surname', _t('Participator.SURNAME', 'Surname')),
				$personNumber = new TextField('PersonalNumber', _t('Participator.PERSONALNUMBER', 'Personal number')),
				new DropdownField('Gender', _t('Participator.GENDER', 'Gender'), $gender_array),
				new DropdownField('NativeLanguage', _t('Participator.NATIVELANGUAGE', 'Native language'), $natlang_array),
				new TextField('PostAddress', _t('Participator.POSTADDRESS', 'Post address')),
				$postCodeField = new NumericFieldEx('PostCode', _t('Participator.POSTCODE', 'Post code')),						
				new TextField('PostOffice', _t('Participator.POSTOFFICE', 'Post office')),
			new LiteralField('LeftGroupDivEnd', '</div>'),
			new LiteralField('RightGroupDivStart', '<div class="rightGroup">'),
				new TextField('Phone', _t('Participator.PHONE', 'Phone')),
				new EmailField('Email', _t('Participator.EMAIL', 'Email')),
				new TextField('Profession', _t('Participator.PROFESSION', 'Profession')),
				$noteGroup = new FieldGroup(
					$note = new TextAreaField('NoteParticipator', _t('Participator.NOTE', 'Note'))
				),
				$passwordGroup = new FieldGroup(
					$password = new ConfirmedPasswordField('NewPassword', null, null, null, false)
				),
			new LiteralField('RightGroupDivEnd', '</div>')
		);
		
		if (CourseBookingExtension::isParticipator()) {
			$fields->insertAfter(new DropdownField('Education', _t('Participator.EDUCATION', 'Education'), $education_array), 'Profession');
			$fields->insertAfter(new DropdownField('Occupation', _t('Participator.OCCUPATION', 'Occupation'), $occupation_array), 'Education');
		}
		else if (CourseBookingExtension::isTeacher()) {
			$fields->insertAfter(new TextField('Title', _t('Teacher.TITLE', 'Title')), 'Profession');
			$fields->insertAfter(new TextField('BankAccountNumber', _t('Teacher.BANKACCOUNTNUMBER', 'Bank account number')), 'Title');
		}
		
		$noteGroup->addExtraClass('notegroup');
		$note->setRows(2);
		
		$passwordGroup->addExtraClass('confirmedpassword');
		$password->setCanBeEmpty(true);		
		$password->minLength = 6;
		
		$postCodeField->setMaxLength(5);
		$postCodeField->setDefaultZeroValue('');
		$personNumber->setMaxLength(11);		
		
		$actions = new FieldSet(
			new FormAction('UpdateProfile', _t('MyCoursesPage.PROFILE_BUTTONUPDATE', 'Update profile'))
		);
		
		$requirements = new RequiredFields('FirstName', 'Surname', 'PersonalNumber', 'Gender', 'NativeLanguage', 'PostAddress', 'PostCode', 'PostOffice', 'Phone', 'Email');
		
		if (CourseBookingExtension::isParticipator()) {
			$requirements->addRequiredField('Education');
			$requirements->addRequiredField('Occupation');
		}
		else if (CourseBookingExtension::isTeacher()) {
			
		}		
		
		$form = new Form($this, 'ProfileForm', $fields, $actions, $requirements);
		$form->loadDataFrom(CourseBookingExtension::currentUser());
		
		return $form;
	}
		
	public function UpdateProfile($data, $form) {
		$member = CourseBookingExtension::currentUser();
		$form->sessionMessage(_t('MyCoursesPage.PROFILE_UPDATEBAD', 'An error occured while updating your profile'), 'bad');
		
		try {		
			// Important: don't allow setting the ID!
			if(isset($data['ID'])) {
				throw new Exception('Update failed! (ID not allowed)');
			}				
			
			if($existing_member = DataObject::get_one("Member", "`Email` = '". Convert::raw2sql($data['Email']) . "' AND Member.ID != {$member->ID}")) {
				$form->sessionMessage(_t('Member.VALIDATIONMEMBEREXISTS', 'A member already exists with the same email address'), 'bad');
				throw new Exception();
			}
			
			$form->saveInto($member);
			
			if (strlen($data['NewPassword']['_Password']) > 6 && strlen($data['NewPassword']['_ConfirmPassword']) > 6 && $data['NewPassword']['_Password'] === $data['NewPassword']['_ConfirmPassword']) {
				$member->Password = $data['NewPassword']['_Password'];
			}
			
			$member->write();
			
			$form->sessionMessage(_t('MyCoursesPage.PROFILE_UPDATEGOOD', 'Your profile has been updated successfully'), 'good');
		}
		catch (Exception $e) {
			
		}	

		$this->redirectBack();
	}
	
	public function UpdateCourseRequest() {
		if (!CourseBookingExtension::isLoggedIn()) {
			$response = new SS_HTTPResponse('You must be logged in', 401);
			return $response;
		}
		
		$courseRequestID = isset($_POST['CourseRequestID']) ? (int)$_POST['CourseRequestID'] : 0;
		$courseRequest = DataObject::get_by_id('CourseRequest', $courseRequestID);
		
		if (!$courseRequest || $courseRequest->ParticipatorID != CourseBookingExtension::currentUser()->ID) {
			$response = new SS_HTTPResponse('Forged requests are forbidden', 403);
			return $response;			
		}
		
		$postAddress = isset($_POST['PostAddress']) ? $_POST['PostAddress'] : '';
		$postCode = isset($_POST['PostCode']) ? (int)$_POST['PostCode'] : 0;
		$postOffice = isset($_POST['PostOffice']) ? $_POST['PostOffice'] : '';
		
		$courseRequest->BillingPostAddress = $postAddress;
		$courseRequest->BillingPostCode = $postCode;
		$courseRequest->BillingPostOffice = $postOffice;
		$courseRequest->write();
		
		$html = _t('MyCoursesPage.SAVED', 'Saved');
		$response = new SS_HTTPResponse($html);
		return $response;
	}	
	
	public function SendTeacherMessage() {
		if (!CourseBookingExtension::isLoggedIn()) {
			$response = new SS_HTTPResponse('You must be logged in', 401);
			return $response;
		}		
		
		$courseID = isset($_POST['CourseID']) ? (int)$_POST['CourseID'] : 0;
		$course = DataObject::get_by_id('Course', $courseID);
		
		if (!$course || !$course->getIsConfirmedCourse()) {
			$response = new SS_HTTPResponse('Forged requests are forbidden', 403);
			return $response;			
		}
		
		if ($course->Teachers()->Count()) {
			foreach ($course->Teachers() as $teacher) {
				$message = new IM_Message();
				$message->FromID = CourseBookingExtension::currentUser()->ID;
				$message->ToID = $teacher->ID;
				$message->Subject = $_POST['IM_Subject'];
				$message->Body = $_POST['IM_Body'];
				$message->send();
			}
		}
		
		$html = _t('MyCoursesPage.SENT', 'Sent');
		$response = new SS_HTTPResponse($html);
		return $response;		
	}

	public function SendParticipatorsMessage() {
		if (!CourseBookingExtension::isLoggedIn()) {
			$response = new SS_HTTPResponse('You must be logged in', 401);
			return $response;
		}		
		
		$courseID = isset($_POST['CourseID']) ? (int)$_POST['CourseID'] : 0;
		$course = DataObject::get_by_id('Course', $courseID);
		
		$teachers = $course->Teachers();
		
		if (!$course || !$teachers || !$teachers->find('ID', CourseBookingExtension::currentUser()->ID)) {
			$response = new SS_HTTPResponse('Forged requests are forbidden', 403);
			return $response;			
		}
		
		if ($course->Participators()->Count()) {
			$message = new IM_Message();
			$message->FromID = CourseBookingExtension::currentUser()->ID;
			$message->ToID = $course->ID;
			$message->Subject = $_POST['IM_Subject'];
			$message->Body = $_POST['IM_Body'];
			$message->RecipientType = 'Course';
			$message->send();
		}
		
		$html = _t('MyCoursesPage.SENT', 'Sent');
		$response = new SS_HTTPResponse($html);
		return $response;		
	}	
	
	public function courseDatesFeed() {
		$start = isset($_GET['start']) ? (int)$_GET['start'] : null;
		$end = isset($_GET['end']) ? (int)$_GET['end'] : null;
		
		$events = array();
		
		$startEndFilter = '';
		if ($start !== null && $end !== null) {
			$startEndFilter = 'AND (TimeStart >= \'' . date('Y-m-d 00:00:00', $start) . '\' AND ' . 'TimeEnd <= \'' . date('Y-m-d 23:59:59', $end) . '\')';
		}
		else if ($start !== null && $end === null) {
			$startEndFilter = 'AND TimeStart >= \'' . date('Y-m-d 00:00:00', $start) . '\'';
		}
		else if ($start === null && $end !== null) {
			$startEndFilter = 'AND TimeEnd <= \'' . date('Y-m-d 23:59:59', $end) . '\'';
		}		
		
		$user = CourseBookingExtension::currentUser();
		if ($user && $user instanceof Participator) {
			$participatorID = $user->ID;
			
			$courseDates = DataObject::get(	'CourseDate', 
											'CourseDate.CourseID = CourseRequest.CourseID ' . $startEndFilter, 
											'', 
											"LEFT JOIN CourseRequest ON (CourseRequest.ParticipatorID = $participatorID AND (Status = 'Notified' OR Status = 'Completed')) ");
			
			if ($courseDates && $courseDates->Count()) {
				foreach ($courseDates as $courseDate) {
					$description = $courseDate->Course()->CourseDescription;
					$location = $courseDate->Location;
					$locationAddress = $location->PostAddress. ', ' . $location->PostCode . ', ' . $location->PostOffice;
					
					$events[] = array(
						'id' => $courseDate->ID,
						'title' => $courseDate->Course()->Name,
						'description' => $description,
						'location' => array(
							'name' => $location->Name,
							'address' => $locationAddress
						),
						'start' => strftime("%Y-%m-%dT%H:%M:%S", strtotime($courseDate->TimeStart)),
						'end' => strftime("%Y-%m-%dT%H:%M:%S", strtotime($courseDate->TimeEnd)),
						'editable' => false,
						'allDay' => false
					);
				}
			}	
		}
		else if ($user && $user instanceof Teacher) {
			$teacherID = $user->ID;
			$courseDates = DataObject::get('CourseDate', 
											'CourseDateLink.CourseDateID = CourseDate.ID ' . $startEndFilter,
											'', 
											"LEFT JOIN CourseDateLink ON CourseDateLink.TeacherID = $teacherID");

			if ($courseDates && $courseDates->Count()) {
				foreach ($courseDates as $courseDate) {
					$description = $courseDate->Course()->CourseDescription;
					$location = $courseDate->Location;
					$locationAddress = $location->PostAddress. ', ' . $location->PostCode . ', ' . $location->PostOffice;
					
					$events[] = array(
						'id' => $courseDate->ID,
						'title' => $courseDate->Course()->Name,
						'description' => $description,
						'location' => array(
							'name' => $location->Name,
							'address' => $locationAddress
						),
						'start' => strftime("%Y-%m-%dT%H:%M:%S", strtotime($courseDate->TimeStart)),
						'end' => strftime("%Y-%m-%dT%H:%M:%S", strtotime($courseDate->TimeEnd)),
						'editable' => false,
						'allDay' => false
					);
				}
			}			
		}
		
		$response = new SS_HTTPResponse(json_encode($events));
		$response->addHeader("Content-type", "application/json");
		return $response;		
	}
}

?>
