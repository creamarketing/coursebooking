<?php

require_once('Zend/Date.php');

class CourseBookingAdmin extends LeftAndMain {
	
	static $extensions = array(
		'CourseBookingExtension()',
		'CreaDataObjectExtension',
	);
	
	static $url_segment = 'coursebooking';
	
	static $menu_title = 'Course Booking';
	
	static $menu_priority = 3;
	
	public $view = 'default';
	
	static $allowed_views = array(
			'default',
			'addcourse',
			'addcourse2',
			'addcourse_finished',
			'editcourses',
			'editcourselanguages',
			'editcourseunits',
			'editcoursesubjects',	
			'editterms',
			'editteachers',
			'editeducationareas',
			'editcourserequests',
			'editparticipators',
			'editsalaryclasses',
			'edithourtypes',
			'editcoursedates',
			'editincomeaccounts',
			'editexpenseaccounts',
			'editcoursetypes',
			'editcoursemainclasses',
			'editagegroups',
			'editeducationtypes',
			'editcourseadmins',
			'teacherhoursreports',
			'teachersalaryreports',
			'teacherlaborcontract',
			'editemployers',
			'coursereports',
			'exportcoursedata',
			'participatorreports',
			'teacherreports'
	);	

	static $allowed_actions = array(		
			'AddCourseForm',
			'AddCourseFormDialogTabs',
			'EditCoursesForm',
			'EditTermsForm',
			'EditCourseLanguagesForm',
			'EditCourseUnitsForm',	
			'EditCourseSubjectsForm',	
			'EditTeachersForm',
			'EditEducationAreasForm',
			'EditCourseRequestsForm',
			'getSignupDatesFromTerm',
			'getDefaultsFromCourseSubject',	
			'getLocalesForCourseLanguages',
			'getTeacherInfo',
			'checkCourseDates',
			'EditParticipatorsForm',
			'EditSalaryClassesForm',
			'EditHourTypesForm',
			'EditCourseDatesForm',
			'EditIncomeAccountsForm',
			'EditExpenseAccountsForm',
			'EditCourseTypesForm',
			'EditCourseMainClassesForm',
			'EditAgeGroupsForm',
			'EditEducationTypesForm',
			'EditCourseAdminsForm',
			'TeacherHoursReportsForm',
			'TeacherSalaryReportsForm',
			'TeacherLaborContractForm',
			'EditEmployersForm',
			'getReport',
			'CourseReportsForm',
			'ExportCourseDataForm',
			'getExportData',
			'ParticipatorReportsForm',
			'sendPasswordResetLink',
			'sendAccountInformation',
			'lockPaymentMonth',
			'TeacherReportsForm',
			'getInvoicesForParticipator',
		
			'GetGridTestForm',
			'testim'
	);
	
	function defineMethods() {
		parent::defineMethods();
		foreach (self::$allowed_views as $view) {
			self::$allowed_actions[] = $view;
		}
	}

	function canView($member = null) {
		if(!$member) {
			if (!Member::currentUser()) {
				return false;
			}
			else {
				$member = Member::currentUser();
			}
		}
		
		if ($member->inGroup('courseadmin')) {
			return true;
		}	else {
			return parent::canView($member);
		}
	}
	
	
	public function init() {	
		parent::init();		
		$this->getCourseBookingRequirements();			
		
		// Booking Calendar admin stuff
		Requirements::javascript('coursebooking/javascript/CourseBookingAdmin.js');
		Requirements::css('coursebooking/css/CourseBookingAdmin.css');
		
		// extra css to make it look the same as on public page
		Requirements::css('themes/blackcandy/css/layout.css');
		Requirements::css('themes/blackcandy/css/form.css');
		
		Requirements::block(THIRDPARTY_DIR . '/jquery-ui/jquery-ui-1.8rc3.custom.js');
		Requirements::block(THIRDPARTY_DIR . '/jquery-ui/jquery.ui.dialog.js');
		Requirements::block(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui-1.8rc3.custom.css');

		
		// set view based on url parameter
		$urlAction = $this->urlParams['Action'];
		if (in_array($urlAction, self::$allowed_views)) {
			$this->view = $urlAction;
		}
		else {
			$this->view = 'default';
		}
					
		// TODO: Needed? Doubt it..
		if (($urlAction != 'EditCoursesForm' && $urlAction != 'EditUsersForm') || Director::is_ajax()) {
			// block some cms css here, to make calendar look like on public page
			//Requirements::block('cms/css/typography.css');
			//Requirements::block('sapphire/css/Form.css');
		}

		$member = Member::CurrentMember();
		if ($member) {
			if (in_array($member->Locale, Translatable::get_allowed_locales())) {
				Translatable::set_current_locale(Member::CurrentMember()->Locale);
			}
		}
	}
	
	public function checkCourseDates() {
		
		$safeData = Convert::raw2sql($_GET);
				
		// Course dates and times
		$generatedDates = array();
		$locationIDs = array();
		
		$weekDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$weekInSeconds = 3600*24*7;
			
		$totalLessions = 0;
		
		// Go trough repeating times
		for ($i=0;$i<7;$i++) {
			if (!isset($safeData['Rec']) || !isset($safeData['Rec'][$i]))
				continue;
			
			$rec = $safeData['Rec'][$i];
			
			// Check if this day is checked
			if (isset($rec['Chk'])) {		
				$startDate = strtotime($safeData['RecDateStart'] . ' 00:00');
				$endDate = strtotime($safeData['RecDateEnd'] . ' 23:59');
				
				$currentDate = strtotime('this ' . $weekDays[$i], $startDate);
				if ($currentDate < $startDate)
					$currentDate = strtotime('next ' . $weekDays[$i], $startDate);
				
				while($currentDate >= $startDate && $currentDate <= $endDate) // is within range
				{		
					array_push($generatedDates, array(
						"Start" => date('d.m.Y', $currentDate) . ' ' . $rec['Time']['Start'],
						"End" => date('d.m.Y', $currentDate) . ' ' . $rec['Time']['End'],
						"UnixDate" => $currentDate,
						"StartTime" => $rec['Time']['Start'],
						"EndTime" => $rec['Time']['End'],
						"Location" => $rec['Location'],
						"Teacher" => $rec['Teacher'],
						'ConflictingBookingTimespan' => '',
						'ConflictingBlockedTimespan' => '',
						"ConflictsWithBooking" => false,
						"ConflictsWithAvailability" => false,
						'ConflictsWithBlockedDates' => false
					));
						
					if (!in_array($rec['Location'], $locationIDs))
						array_push($locationIDs, $rec['Location']);
					
					$totalLessions += $rec['Lessions'];
						
					$currentDate += $weekInSeconds;
				}
			}
		}		
				
		$eBooking = new eBookingCommunicator();

		// Check dates to see if they are bookable

		$summaryDates = array();
			
		foreach ($generatedDates as &$courseDate) {
			$conflictResult = CourseDate::checkForConflicting($courseDate['Start'], $courseDate['End'], $courseDate['Location'], (isset($safeData['TermID']) ? (int)$safeData['TermID'] : null), $eBooking);
					
			array_push($summaryDates, $conflictResult['msg_html']);
		}
		
		// remove reference from $coursedate !!
		unset($courseDate);
		
		$desiredLessions = (float)$safeData['DesiredLessions'];
		
		$resultHTML = '<h4>' . _t('Course.SUMMARY_OF_DATES', 'Summary of dates') . '</h4>';
		$resultHTML .= _t('Course.SUMMARY_TOTALLESSIONS' , 'Total lessions') . ': ' . $totalLessions . '<br>' . _t('Course.SUMMARY_DESIREDLESSIONS', 'Desired lessions') . ': ' . $desiredLessions . '<br/><br/>';
		if ($desiredLessions > 0 && $desiredLessions != $totalLessions)
			$resultHTML .= '<span style="color: red">' . _t('Course.SUMMARY_LESSIONS_MISSMATCH', 'The number of lessions does not match the number of desired lesssions!') . '</span><br/><br/>';
		$resultHTML .= implode('<br/>', $summaryDates);
	
		$response = new SS_HTTPResponse($resultHTML);
		$response->addHeader("Content-type", "text/html");
		
		return $response;		
	}
	
	public function getInvoicesForParticipator() {
		$participatorID = isset($_GET['participatorID']) ? (int)$_GET['participatorID'] : 0;
		
		$participator = DataObject::get_by_id('Participator', $participatorID);
		$result = array();
		
		if ($participator) {
			$requests = $participator->CourseRequests();
			
			if ($requests) {
				foreach ($requests as $request) {
					$invoices = $request->CourseRequestInvoices();
					if ($invoices) {
						foreach ($invoices as $invoice) {
							$result[] = array('ID' => $invoice->ID, 'Number' => $invoice->InvoiceNumber);
						}
					}
				}
			}
		}

		$response = new SS_HTTPResponse(json_encode($result));
		$response->addHeader("Content-type", "application/json");
		return $response;		
	}	
	
	public function getTeacherInfo() {
		$teacherID = isset($_GET['teacherID']) ? (int)$_GET['teacherID'] : 0;
		
		$teacher = DataObject::get_by_id('Teacher', $teacherID);
		$result = array();
		
		if ($teacher) {
			$hourTypeText = $teacher->DefaultHourType()->Name;
			$hourTypeCode = $teacher->DefaultHourType()->Code;			
			$hourTypeValue = $teacher->DefaultHourTypeID;

			$salaryText = $teacher->DefaultSalaryClass()->Name;
			$salaryValue = $teacher->DefaultSalaryClassID;			
			
			$result['hourtype']['text'] = $hourTypeText;
			$result['hourtype']['code'] = $hourTypeCode;;
			$result['hourtype']['value'] = $hourTypeValue;
			
			$result['salary']['text'] = $salaryText;
			$result['salary']['value'] = $salaryValue;			
		}

		$response = new SS_HTTPResponse(json_encode($result));
		$response->addHeader("Content-type", "application/json");
		return $response;		
	}
	
	public function sendPasswordResetLink() {
		if (!isset($_GET['accountType']) || !isset($_GET['accountID']) || !self::isAdmin())
			return;
		
		$user = DataObject::get_by_id($_GET['accountType'], (int)$_GET['accountID']);
		if ($user) {
			$user->generateAutologinHash();
			$user->sendInfo('forgotPassword', array('PasswordResetLink' => Security::getPasswordResetLink($user->AutoLoginHash)));
		}
					
		$response = new SS_HTTPResponse('');
		return $response;						
	}

	public function sendAccountInformation() {
		if (!isset($_GET['accountType']) || !isset($_GET['accountID']) || !self::isAdmin())
			return;
		
		$user = DataObject::get_by_id($_GET['accountType'], (int)$_GET['accountID']);
		if ($user) {
			$e = new AccountInformationEmail();
			$e->populateTemplate(array(
				'FirstName' => $user->FirstName,
				'Surname' => $user->Surname,
				'Phone' => $user->Phone,
				'PostCode' => $user->PostCode,
				'PostAddress' => $user->PostAddress,
				'PostOffice' => $user->PostOffice,
				'Email' => $user->Email
			));
			$e->setTo($user->Email);
			$e->send();	
			
			$response = new SS_HTTPResponse('');
			return $response;						
		}
					
		$response = new SS_HTTPResponse('');
		return $response;						
	}	
	
	public function getSignupDatesFromTerm() {
		$termID = Convert::raw2sql($_GET['termID']);
		
		$termID = is_numeric($termID) ? $termID : 0;
				
		$termDates = array('Start' => array('Date' => '', 'Time' => 'time'),
						   'End' => array('Date' => 'date', 'Time' => 'time', 'Enabled' => true));
		
		$term = DataObject::get_by_id('Term', $termID);
		
		if ($term) {
			$date = strtotime($term->SignupStart);
			$termDates['Start']['Date'] = date('d.m.Y', $date);
			$termDates['Start']['Time'] = date('H:i', $date);
			
			$date = strtotime($term->SignupEnd);
			$termDates['End']['Date'] = date('d.m.Y', $date);
			$termDates['End']['Time'] = date('H:i', $date);
			
			$termDates['End']['Enabled'] = $term->SignupExpiresNot ? false : true;
		}
				
		$response = new SS_HTTPResponse(json_encode($termDates));
		$response->addHeader("Content-type", "application/json");
		return $response;	
	}
	
	public function getDefaultsFromCourseSubject() {
		$subjectID = (int)$_GET['subjectID'];
		$termID = (int)$_GET['termID'];

		$data = array();
		
		$subject = DataObject::get_by_id('CourseSubject', $subjectID);
		if ($subject) {
			$largestCourseNumber = DataObject::get('Course', 'TermID = ' . $termID . ' AND CourseSubject.ID = ' . $subjectID, 'CourseCode DESC', 'LEFT JOIN Course_Subjects ON Course_Subjects.CourseID = Course.ID LEFT JOIN CourseSubject ON CourseSubject.ID = Course_Subjects.CourseSubjectID', 1);
			$nextID = 1;
			
			if ($largestCourseNumber && $largestCourseNumber->Count()) {
				$courseCode = $largestCourseNumber->First()->CourseCode;
				$lastID = (int)substr($courseCode, strlen($courseCode) - 2, 2);
				$nextID = $lastID + 1;
			}

			$subjectNumber = $subject->Code;
			
			$text = str_pad((int) $subjectNumber, 4,"0", STR_PAD_LEFT);
			$text .= str_pad((int) $nextID, 2,"0", STR_PAD_LEFT);
			
			$data['CourseCodeNextID'] = $text;
			$data['CourseMainClass']['ID'] = $subject->CourseMainClassID;
			if ($subject->CourseMainClass()->exists())
				$data['CourseMainClass']['Text'] = $subject->CourseMainClass()->Code . ' ' . $subject->CourseMainClass()->Name;
			else
				$data['CourseMainClass']['Text'] = '';
			$data['EducationArea']['ID'] = $subject->EducationAreaID;
			if ($subject->EducationArea()->exists())
				$data['EducationArea']['Text'] = $subject->EducationArea()->Code . ' ' . $subject->EducationArea()->NiceName;
			else
				$data['EducationArea']['Text'] = '';			
		}
		
		$response = new SS_HTTPResponse(json_encode($data));
		$response->addHeader("Content-type", "application/json");
		return $response;			
	}	

	public function getLocalesForCourseLanguages() {
		$languages = explode(',', $_GET['languages']);
		$text = '';
		$locales = array();
		
		foreach($languages as $lang) {
			$langObj = DataObject::get_by_id('CourseLanguage', (int)$lang);
			if ($langObj)
				$locales[] = $langObj->Locale;
		}
		
		if (count($locales) > 1)
			$text = implode(',', $locales);
		elseif (count($locales) == 1)
			$text = $locales[0];
		
		$response = new SS_HTTPResponse($text);
		return $response;			
	}
	
	public function EditCoursesForm() {	
		if (self::isAdmin()) {		
			$fields = new FieldSet(				
				new HeaderField('CoursesHeader', _t('Course.PLURALNAME', 'Courses')),
				$courseDOM = new DialogDataObjectManager(
					$this, 
					'Courses', 
					'Course', 
					array( 
						/*'CourseUnit.Name' => _t('CourseUnit.SINGULARNAME', 'Course unit'),*/
						'Term.Name' => _t('Term.SINGULARNAME', 'Term'),
						'CourseCode' => _t('Course.COURSECODE', 'Course code'),
						'NameList' => _t('Course.NAME', 'Name'),
						'SubjectList' => _t('CourseSubject.PLURALNAME', 'Course subject'), 
						'LanguageList' => _t('CourseLanguage.PLURALNAME', 'Course language'),
						'TeacherList' => _t('Teacher.PLURALNAME', 'Teacher')
					),
					null,
					'Term.Active = 1',
					$sort = 'Created DESC',
					$join = 'LEFT JOIN Term ON Course.TermID = Term.ID ' . 
							'LEFT JOIN CourseUnit ON Course.CourseUnitID = CourseUnit.ID'
				)		
			);
				
			//$courseDOM->setFilter('CourseID', _t('Course.SINGULARNAME', 'Course'), 	$this->getArrayForObject('Course', 'Name ASC'));
			$courseDOM->setAddTitle(_t('Course.SINGULARNAME', 'Course'));
			
			$terms = DataObject::get('Term', null, 'DateStart DESC');
			if ($terms) 
				$terms = $terms->map('ID', 'Name');
			else
				$terms = array();
			$courseDOM->setFilter('TermID', _t('Term.SINGULARNAME', 'Term'), $terms);

			//$courseDOM->setPermissions( array('add', 'edit', 'duplicate', 'delete') );	
			//$courseDOM->addPermission('duplicate');
			
			$courseDOM->setHighlightConditions(array(
				array(
					"rule" => '$Status == "Passive"',
					"class" => 'course-passive'
				),				
				array(
					"rule" => '$Issues == 1',
					"class" => 'course-issues',
					"exclusive" => true
				)
			));
			
			//$courseDOM->removePermission('add');
			$actions = new FieldSet();
									
			return new Form($this, "EditCoursesForm", $fields, $actions);
		}	
	}
			
	public function EditTermsForm() {	
			$fields = new FieldSet(				
				new HeaderField('TermHeader', _t('Term.PLURALNAME', 'Terms')),
				$termsDOM = new DialogDataObjectManager(
					$this, 
					'Terms', 
					'Term',
					array(
						'Name' => 'Name',
						'DateStartNice' => 'DateStart',
						'DateEndNice' => 'DateEnd',
						'ActiveNice' => 'Active',
						'SignupStartNice' => 'SignupStart',
						'SignupEndNice' => 'SignupEnd'
					)
				)
			);
				
			$actions = new FieldSet();
			
			/*$termsDOM->setFieldCasting(array(
				'Active' => 'Boolean->Nice',
				'SignupStart' => 'SS_Datetime->Nice24Finland'
			));*/
									
			return new Form($this, "EditTermsForm", $fields, $actions);
	}	
		
	public function EditCourseLanguagesForm() {
		$fields = new FieldSet(
				new HeaderField('LanguageHeader', _t('CourseLanguage.PLURALNAME', 'Languages')),
				$languageDOM = new DialogDataObjectManager(
					$this,
					'Languages',
					'CourseLanguage'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseLanguagesForm", $fields, $actions);
	}	
		
	public function EditTeachersForm() {
		$fields = new FieldSet(
				new HeaderField('TeacherHeader', _t('Teacher.PLURALNAME', 'Teachers')),
				$teachersDOM = new DialogDataObjectManager(
					$this,
					'Teachers',
					'Teacher'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditTeachersForm", $fields, $actions);
	}
		
	public function EditCourseUnitsForm() {
		$fields = new FieldSet(
				new HeaderField('UnitsHeader', _t('CourseUnit.PLURALNAME', 'Units')),
				$unitsDOM = new DialogDataObjectManager(
					$this,
					'Units',
					'CourseUnit'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseUnitsForm", $fields, $actions);
	}	
	
	public function EditHourTypesForm() {
		$fields = new FieldSet(
				new HeaderField('HoursHeader', _t('HourType.PLURALNAME', 'Hourtypes')),
				$unitsDOM = new DialogDataObjectManager(
					$this,
					'HourTypes',
					'HourType'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditHourTypesForm", $fields, $actions);
	}		
	
	public function EditCourseTypesForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('CourseType.PLURALNAME', 'Course types')),
				$unitsDOM = new DialogDataObjectManager(
					$this,
					'CourseTypes',
					'CourseType'
				)
		);
		
		$unitsDOM->setFieldCasting(array(
			'HasGovernmentFunding' => 'Boolean->Nice'
		));		
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseTypesForm", $fields, $actions);
	}		
	
	public function EditAgeGroupsForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('AgeGroup.PLURALNAME', 'Age groups')),
				$unitsDOM = new DialogDataObjectManager(
					$this,
					'AgeGroups',
					'AgeGroup'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditAgeGroupsForm", $fields, $actions);
	}		

	public function EditCourseMainClassesForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('CourseMainClass.PLURALNAME', 'Course main class')),
				$dom = new DialogDataObjectManager(
					$this,
					'CourseMainClasses',
					'CourseMainClass'
				)
		);
				
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseMainClassesForm", $fields, $actions);
	}			
	
	public function EditEducationTypesForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('EducationType.PLURALNAME', 'Education types')),
				$unitsDOM = new DialogDataObjectManager(
					$this,
					'EducationTypes',
					'EducationType'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditEducationTypesForm", $fields, $actions);
	}		

	public function EditIncomeAccountsForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('IncomeAccount.PLURALNAME', 'Income accounts')),
				$accountsDOM = new DialogDataObjectManager(
					$this,
					'IncomeAccounts',
					'IncomeAccount'
				)
		);
		
	
		$actions = new FieldSet();
		
		return new Form($this, "EditIncomeAccountsForm", $fields, $actions);
	}		
	
	public function EditExpenseAccountsForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('ExpenseAccount.PLURALNAME', 'Expense accounts')),
				$accountsDOM = new DialogDataObjectManager(
					$this,
					'ExpenseAccounts',
					'ExpenseAccount'
				)
		);
		
	
		$actions = new FieldSet();
		
		return new Form($this, "EditExpenseAccountsForm", $fields, $actions);
	}		
	
	public function EditCourseSubjectsForm() {
		$fields = new FieldSet(
				new HeaderField('SubjectsHeader', _t('CourseSubject.PLURALNAME', 'Subjects')),
				$subjectsDOM = new DialogDataObjectManager(
					$this,
					'Subjects',
					'CourseSubject'
				)
		);
		
		//$subjectsDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseSubjectsForm", $fields, $actions);
	}	
	
	public function EditEducationAreasForm() {
		$fields = new FieldSet(
				new HeaderField('EducationAreasHeader', _t('EducationArea.PLURALNAME', 'Educationareas')),
				$educationAreasForm = new DialogDataObjectManager(
					$this,
					'Educationareas',
					'EducationArea',
					array(
						'Code' => _t('EducationArea.CODE', 'Code'),						
						'NiceName' => _t('EducationArea.SINGULARNAME', 'Educationarea'),
						'Name' => _t('EducationArea.NAME', 'Name'),
						'ParentEducationArea.Name' => _t('EducationArea.PARENTNAME', 'Parent name')
					),
					null,
					null,
					null,
					"LEFT JOIN EducationArea AS ParentEducationArea ON ParentEducationArea.ID = EducationArea.ParentEducationAreaID"
				)
		);
		
		//$subjectsDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditEducationAreasForm", $fields, $actions);
	}
	
	public function EditCourseRequestsForm() {
		$fields = new FieldSet(
				new HeaderField('RequestsHeader', _t('CourseRequest.PLURALNAME', 'Requests')),
				$requestssDOM = new DialogDataObjectManager(
					$this,
					'Requests',
					'CourseRequest',
					array(
						'Surname' => _t('Participator.SURNAME', 'Surname'),
						'Firstname' => _t('Participator.FIRSTNAME', 'Firstname'),
						'CourseName' => _t('Course.NAME', 'CourseName'),
						'StatusNice' => _t('CourseRequest.STATUS', 'Status'),
						'NiceDiscountCoursePrice' => _t('Course.PRICE', 'Course price'),
						'Created' => _t('CourseRequest.CREATED', 'Created'),
						'LastEdited' => _t('CourseRequest.EDITED', 'Changed'),
					)		
				)
		);

		$requestssDOM->setFieldCasting(array(
			'Created' => 'SS_Datetime->Nice24Finland'
		));
		
		//$requestssDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseRequestsForm", $fields, $actions);
	}			
	
	public function GetGridTestForm() {
		$fields = new FieldSet(new jqGridManager($this, 'test', 'Testing', 'CourseLanguage'), 
							   new jqGridManager($this, 'test2', 'Testing more', 'Course'));
		
		return new Form($this, 'GetGridTestForm', $fields, new FieldSet());
	}
	
	public function EditParticipatorsForm() {
		$fields = new FieldSet(
				new HeaderField('ParticipatorsHeader', _t('Participator.PLURALNAME', 'Participators')),
				$partsDOM = new DialogDataObjectManager(
					$this,
					'Participators',
					'Participator'
				)
		);
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditParticipatorsForm", $fields, $actions);
	}
	
	public function EditSalaryClassesForm() {
		$fields = new FieldSet(
				new HeaderField('SalaryClassesHeader', _t('TeacherSalaryClass.PLURALNAME', 'Salary classes')),
				$partsDOM = new DialogDataObjectManager(
					$this,
					'TeacherSalaryClasses',
					'TeacherSalaryClass'
				)
		);
		
		$partsDOM->setFieldCasting(array(
			'ActiveFrom' => 'SS_Datetime->Nice24Finland'
		));		
		
		$partsDOM->setFieldFormatting(array(
			'SalaryHour' => '$SalaryHour €'
		));
		
		//$languageDOM->setPermissions(array());
		
		$actions = new FieldSet();
		
		return new Form($this, "EditSalaryClassesForm", $fields, $actions);
	}	
	
	public function EditCourseDatesForm() {
		$fields = new FieldSet(
				new HeaderField('CourseDatesHeader', _t('CourseDate.PLURALNAME', 'Coursedates')),
				$dateDOM = new DialogDataObjectManager(
					$this,
					'CourseDates',
					'CourseDate'
				)
		);
		
		$dateDOM->setHighlightConditions(array(
			array(
				"rule" => '$Cancelled == 1',
				"class" => 'coursedate-cancelled'
			),
			array(
				"rule" => '$Conflicting == 1',
				"class" => 'coursedate-conflicting',
				"exclusive" => true
			)
		));
		
		$dateDOM->setColumnWidths(array(
			'TimeNice' => 15,
			'LocationNice' => 15,
			'Lessions' => 12,
			'CancelledNice' => 12,
			'WeekDayNice' => 12,
			'TeacherNice' => 15,
			'PaymentMonthNice' => 6,
			'CourseNice' => 13)
		);		
				
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseDatesForm", $fields, $actions);
	}		
	
	public function EditCourseAdminsForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('CourseAdmin.PLURALNAME', 'Courseadmins')),
				$courseadminsDOM = new DialogDataObjectManager(
					$this,
					'CourseAdmins',
					'CourseAdmin'
				)
		);
				
		$actions = new FieldSet();
		
		return new Form($this, "EditCourseAdminsForm", $fields, $actions);
	}
	
	public function TeacherHoursReportsForm() {	
			
		Requirements::javascript('coursebooking/javascript/TeacherHoursReports.js');
		Requirements::css('coursebooking/css/TeacherHoursReports.css');
			
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	
		
		$languageDropdownArray = array();
		foreach (Translatable::get_allowed_locales() as $locale) {
			$languageDropdownArray += array($locale => i18n::$common_locales[$locale][0]);
		}
		$languageDropdownField = new DropdownField('Locale', _t('Report.LANGUAGE', 'Language'), $languageDropdownArray, i18n::get_locale());
			
		$fields = new FieldSet(
				new HeaderField('Header', _t('TeacherHoursReport.HEADER', 'Hour reports')),
				new AdvancedDropdownField('Teacher', _t('Teacher.SINGULARNAME', 'Teacher'), Teacher::toDropdownList()),
				new DateFieldEx('StartDate', _t('CourseDate.TIMESTART', 'From')),
				new DateFieldEx('EndDate', _t('CourseDate.TIMEEND', 'To')),
				new DropdownField('ReportType', _t('TeacherHoursReport.REPORTTYPE', 'Report type'), 
						array(	'hours-monthly' => _t('TeacherHoursMonthlyReport.MONTH', 'Month'), 
								'hours-weekly' => _t('TeacherHoursWeeklyReport.WEEK', 'Week'))
				),
				$languageDropdownField,
				new LiteralField('GenerateReportButton', '<button type="button" id="GenerateReportButton">' . _t('TeacherHoursReport.BUTTON', 'Generate report') . '</button>')
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "TeacherHoursReportsForm", $fields, $actions);
	}
	
	public function TeacherSalaryReportsForm() {	
		
		Requirements::javascript('coursebooking/javascript/TeacherSalaryReports.js');
		Requirements::css('coursebooking/css/TeacherSalaryReports.css');
		
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	

		$languageDropdownArray = array();
		foreach (Translatable::get_allowed_locales() as $locale) {
			$languageDropdownArray += array($locale => i18n::$common_locales[$locale][0]);
		}
		$languageDropdownField = new DropdownField('Locale', _t('Report.LANGUAGE', 'Language'), $languageDropdownArray, i18n::get_locale());		
		
		$fields = new FieldSet(
				new HeaderField('Header', _t('TeacherSalaryReport.HEADER', 'Salary reports')),
				new AdvancedDropdownField('Teacher', _t('Teacher.SINGULARNAME', 'Teacher'), Teacher::toDropdownList()),
				$startMonth = new DateFieldEx('StartDate', _t('CourseDate.TIMESTART', 'From')),
				$endMonth = new DateFieldEx('EndDate', _t('CourseDate.TIMEEND', 'To')),
				new DropdownField('ReportType', _t('TeacherSalaryReport.REPORTTYPE', 'Report type'), 
						array(	'salary-employees' => _t('TeacherSalaryReportEmployment.EMPLOYEES', 'Employees')
							 )
				),
				$languageDropdownField,
				new LiteralField('GenerateReportButton', '<button type="button" id="GenerateReportButton">' . _t('TeacherSalaryReport.BUTTON', 'Generate report') . '</button>')
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "TeacherSalaryReportsForm", $fields, $actions);
	}	
	
	public function lockPaymentMonth() {
		if (!self::isAdmin()) return;
		
		$safeData = Convert::raw2sql($_GET);	
		
		$startDate = empty($safeData['StartDate']) ? null : strtotime($safeData['StartDate']);
		$endDate = empty($safeData['EndDate']) ? null : strtotime($safeData['EndDate']);
		$teacherID = empty($safeData['Teacher']) ? 0 : (int)$safeData['Teacher'];
		$lock = empty($safeData['Lock']) ? false : (int)$safeData['Lock'];

		$where = array();
		
		if ($startDate !== null && $endDate !== null) {
			$startMonth = date('m', $startDate);
			$endMonth = date('m', $endDate);
			$where[] = "(PaymentMonth >= $startMonth AND PaymentMonth <= $endMonth)";
		}
		elseif ($startDate !== null) {
			$startMonth = date('m', $startDate);
			$where[] = "PaymentMonth >= $startMonth";
		}
		elseif ($endDate !== null) {
			$endMonth = date('m', $endDate);
			$where[] = "PaymentMonth <= $endMonth";
		}			
		
		if ($teacherID)
			$where[] = "TeacherID = $teacherID";
		
		$courseDateLinks = DataObject::get('CourseDateLink', implode(' AND ', $where));
		
		if ($courseDateLinks) {
			foreach ($courseDateLinks as $link) {
				$link->Locked = $lock;
				$link->write();
			}
		}
		
		$response = new SS_HTTPResponse('Unknown report');
		$response->addHeader("Content-type", "text/html");
		return $response;
	}			
	
	public function getReport() {
		if (!self::isAdmin()) return;
		
		$safeData = Convert::raw2sql($_GET);
		
		$pdf = (isset($safeData['PDF']) && $safeData['PDF'] === 'true') ? true : false;
		$type = isset($safeData['ReportType']) ? $safeData['ReportType'] : '';
		$locale = isset($safeData['Locale']) ? $safeData['Locale'] : '';
		
		if (in_array($locale, Translatable::get_allowed_locales()))
			i18n::set_locale($locale);
				
		$startDate = empty($safeData['StartDate']) ? null : strtotime($safeData['StartDate']);
		$endDate = empty($safeData['EndDate']) ? null : strtotime($safeData['EndDate']);
		$teacherID = empty($safeData['Teacher']) ? 0 : (int)$safeData['Teacher'];
		
		Requirements::clear();
					
		if ($type == 'hours-monthly')
			return TeacherHoursReport::generateMonthlyReport($teacherID, $startDate, $endDate, $pdf);
		else if ($type == 'hours-weekly')
			return TeacherHoursReport::generateWeeklyReport($teacherID, $startDate, $endDate, $pdf);
		else if ($type == 'salary-employees')
			return TeacherSalaryReport::generateEmployeesReport($teacherID, $startDate, $endDate, $pdf);
		else if ($type == 'teacher-labor-contract')
			return TeacherLaborContract::generateLaborContract($teacherID, $pdf);
		else if ($type == 'participator-reports') {
			$type = isset($safeData['ParticipatorReportType']) ? $safeData['ParticipatorReportType'] : '';
			$participatorID = empty($safeData['Participator']) ? 0 : (int)$safeData['Participator'];
			$termID = empty($safeData['Term']) ? 0 : (int)$safeData['Term'];
			$invoiceID = empty($safeData['InvoiceNumber']) ? 0 : (int)$safeData['InvoiceNumber'];
			
			if ($type == 'courses') {
				return ParticipatorReports::generateCoursesReport($participatorID, $termID, $pdf);
			}
			else if ($type == 'courserequests') {
				return ParticipatorReports::generateCourseRequestsReport($participatorID, $termID, $pdf);
			}
			else if ($type == 'courserequest-invoice') {
				return ParticipatorReports::generateCourseRequestInvoice($participatorID, $invoiceID, $pdf);
			}
		}
		else if ($type == 'teacher-reports') {
			$type = isset($safeData['TeacherReportType']) ? $safeData['TeacherReportType'] : '';
			$teacherID = empty($safeData['Teacher']) ? 0 : (int)$safeData['Teacher'];
			$termID = empty($safeData['Term']) ? 0 : (int)$safeData['Term'];
			$week = empty($safeData['Week']) ? 0 : (int)$safeData['Week'];
			
			if ($type == 'courses') {
				return TeacherReports::generateCoursesReport($teacherID, $termID, $pdf);
			} 
			else if ($type == 'agenda') {				
				return TeacherReports::generateAgendaReport($teacherID, $week, $pdf);
			}
		}
		else if ($type == 'course-reports') {		
			$type = isset($safeData['CourseReportType']) ? $safeData['CourseReportType'] : '';
			$courseID = empty($safeData['Course']) ? 0 : (int)$safeData['Course'];
			
			if ($type == 'participators') {
				return CourseReports::generateParticipatorsReport($courseID, $pdf);
			}
			else if ($type == 'teachers') {
				return CourseReports::generateTeachersReport($courseID, $pdf);
			}
			else if ($type == 'course-requests') {
				return CourseReports::generateCourseRequestsReport($courseID, $pdf);
			}
			else if ($type == 'course-dates') {
				return CourseReports::generateCourseDatesReport($courseID, $pdf);
			}			
		}
		
		$response = new SS_HTTPResponse('Unknown report');
		$response->addHeader("Content-type", "text/html");
		return $response;
	}	
	
	public function TeacherLaborContractForm() {	
		
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/smoothness/jquery.ui.all.css');
		
		Requirements::javascript('coursebooking/javascript/TeacherLaborContract.js');
		Requirements::css('coursebooking/css/TeacherLaborContract.css');
		
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	
		
		$languageDropdownArray = array();
		foreach (Translatable::get_allowed_locales() as $locale) {
			$languageDropdownArray += array($locale => i18n::$common_locales[$locale][0]);
		}
		$languageDropdownField = new DropdownField('Locale', _t('Report.LANGUAGE', 'Language'), $languageDropdownArray, i18n::get_locale());		
		
		$fields = new FieldSet(
				new HeaderField('Header', _t('TeacherLaborContract.HEADER', 'Labor contract')),
				new AdvancedDropdownField('Teacher', _t('Teacher.SINGULARNAME', 'Teacher'), Teacher::toDropdownList()),
				$languageDropdownField,
				new LiteralField('GenerateReportButton', '<button type="button" id="GenerateReportButton">' . _t('TeacherLaborContract.BUTTON', 'Generate contract') . '</button>'),
				new HiddenField('ReportType', '', 'teacher-labor-contract')
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "TeacherLaborContractForm", $fields, $actions);
	}		
	
	public function EditEmployersForm() {
		$fields = new FieldSet(
				new HeaderField('Header', _t('Employer.PLURALNAME', 'Employers')),
				$employersDOM = new DialogDataObjectManager(
					$this,
					'Employers',
					'Employer'
				)
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "EditEmployersForm", $fields, $actions);
	}			
	
	public function CourseReportsForm() {	
		
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/smoothness/jquery.ui.all.css');
		
		Requirements::javascript('coursebooking/javascript/CourseReports.js');
		Requirements::css('coursebooking/css/CourseReports.css');
		
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	
		
		$reportTypes = array('participators' => _t('CourseReports.REPORTTYPE_PARTICIPATORS', 'Participators'));
		$reportTypes += array('teachers' => _t('CourseReports.REPORTTYPE_TEACHERS', 'Teachers'));
		$reportTypes += array('course-requests' => _t('CourseReports.REPORTTYPE_COURSEREQUESTS', 'Course requests'));
		$reportTypes += array('course-dates' => _t('CourseReports.REPORTTYPE_COURSEDATES', 'Course dates'));
		
		$courses = DataObject::get('Course');
		$courseList = array('' => '');
		
		if ($courses) {
			foreach ($courses as $course) {
				$courseList += array($course->ID => $course->DOMTitle);
			}
		}
		
		$languageDropdownArray = array();
		foreach (Translatable::get_allowed_locales() as $locale) {
			$languageDropdownArray += array($locale => i18n::$common_locales[$locale][0]);
		}
		$languageDropdownField = new DropdownField('Locale', _t('Report.LANGUAGE', 'Language'), $languageDropdownArray, i18n::get_locale());		
					
		$fields = new FieldSet(
				new HeaderField('Header', _t('CourseReports.HEADER', 'Course reports')),
				new AdvancedDropdownField('Course', _t('Course.SINGULARNAME', 'Course'), $courseList),
				new DropdownField('CourseReportType', _t('TeacherSalaryReport.REPORTTYPE', 'Report type'), $reportTypes),
				$languageDropdownField,
				new LiteralField('GenerateReportButton', '<button type="button" id="GenerateReportButton">' . _t('TeacherHoursReport.BUTTON', 'Generate report') . '</button>'),
				new HiddenField('ReportType', '', 'course-reports')
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "CourseReportsForm", $fields, $actions);
	}
	
	public function ParticipatorReportsForm() {	
		
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/smoothness/jquery.ui.all.css');
		
		Requirements::javascript('coursebooking/javascript/ParticipatorReports.js');
		Requirements::css('coursebooking/css/ParticipatorReports.css');
		
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	
		
		$languageDropdownArray = array();
		foreach (Translatable::get_allowed_locales() as $locale) {
			$languageDropdownArray += array($locale => i18n::$common_locales[$locale][0]);
		}
		$languageDropdownField = new DropdownField('Locale', _t('Report.LANGUAGE', 'Language'), $languageDropdownArray, i18n::get_locale());		
		
		$reportTypes = array('courses' => _t('ParticipatorReports.REPORTTYPE_COURSES', 'Courses'));
		$reportTypes += array('courserequests' => _t('ParticipatorReports.REPORTTYPE_COURSEREQUESTS', 'Course requests'));
		$reportTypes += array('courserequest-invoice' => _t('ParticipatorReports.REPORTTYPE_COUSEREQUESTINVOICE', 'Invoice'));
		
		$terms = $this->getArrayForObject('Term', 'Name ASC');
		if (count($terms) > 1) {
			$keys = array_keys($terms);
			$firstTerm = $keys[1];
		}
		else
			$firstTerm = '';
		
		$fields = new FieldSet(
				new HeaderField('Header', _t('ParticipatorReports.HEADER', 'Participator reports')),
				new AdvancedDropdownField('Participator', _t('Participator.SINGULARNAME', 'Participator'), Participator::toDropdownList()),
				new DropdownField('ParticipatorReportType', _t('TeacherSalaryReport.REPORTTYPE', 'Report type'), $reportTypes),				
				new AdvancedDropdownField('Term', _t('Term.SINGULARNAME', 'Term'), $terms, $firstTerm),
				new DropdownField('InvoiceNumber', _t('Invoice.INVOICENUMBER', 'Invoice number')),
				$languageDropdownField,
				new LiteralField('GenerateReportButton', '<button type="button" id="GenerateReportButton">' . _t('TeacherHoursReport.BUTTON', 'Generate report') . '</button>'),
				new HiddenField('ReportType', '', 'participator-reports')
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "ParticipatorReportsForm", $fields, $actions);
	}	

	public function TeacherReportsForm() {	
		
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/smoothness/jquery.ui.all.css');
		
		Requirements::javascript('coursebooking/javascript/TeacherReports.js');
		Requirements::css('coursebooking/css/TeacherReports.css');
		
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	
		
		$languageDropdownArray = array();
		foreach (Translatable::get_allowed_locales() as $locale) {
			$languageDropdownArray += array($locale => i18n::$common_locales[$locale][0]);
		}
		$languageDropdownField = new DropdownField('Locale', _t('Report.LANGUAGE', 'Language'), $languageDropdownArray, i18n::get_locale());		
		
		$reportTypes = array('courses' => _t('TeacherReports.REPORTTYPE_COURSES', 'Courses'));
		$reportTypes += array('agenda' => _t('TeacherReports.REPORTTYPE_AGENDA', 'Agenda'));
		
		$terms = $this->getArrayForObject('Term', 'Name ASC');
		if (count($terms) > 1) {
			$keys = array_keys($terms);
			$firstTerm = $keys[1];
		}
		else
			$firstTerm = '';
		
		$week = array();
		for ($i=1;$i<53;$i++) {
			$week += array($i => $i);
		}
		
		$currentWeek = date('W');
		
		$fields = new FieldSet(
				new HeaderField('Header', _t('TeacherReports.HEADER', 'Participator reports')),
				new AdvancedDropdownField('Teacher', _t('Teacher.SINGULARNAME', 'Teacher'), Teacher::toDropdownList()),
				new DropdownField('TeacherReportType', _t('TeacherSalaryReport.REPORTTYPE', 'Report type'), $reportTypes),				
				new AdvancedDropdownField('Term', _t('Term.SINGULARNAME', 'Term'), $terms, $firstTerm),
				new DropdownField('Week', _t('TeacherReports.WEEK', 'Week'), $week, $currentWeek),
				$languageDropdownField,
				new LiteralField('GenerateReportButton', '<button type="button" id="GenerateReportButton">' . _t('TeacherHoursReport.BUTTON', 'Generate report') . '</button>'),
				new HiddenField('ReportType', '', 'teacher-reports')
		);
		
		$actions = new FieldSet();
					
		return new Form($this, "TeacherReportsForm", $fields, $actions);
	}		
	
	public function ExportCourseDataForm() {	
		
		Requirements::css('sapphire/thirdparty/jquery-ui-themes/smoothness/jquery.ui.all.css');
		
		Requirements::javascript('coursebooking/javascript/CourseDataExport.js');
		Requirements::css('coursebooking/css/CourseDataExport.css');
		
		// javascript localization
		Requirements::javascript('sapphire/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');	
		
		$languages = $this->getArrayForObject('CourseLanguage', 'Name ASC');		
		$terms = $this->getArrayForObject('Term', 'Name ASC', 'Active = 1');
		
		$currLang = DataObject::get_one('CourseLanguage', 'Locale = \'' . i18n::get_locale() . '\'');
		
		$fields = new FieldSet(
				new HeaderField('Header', _t('CourseDataExport.HEADER', 'Course data export')),
				new AdvancedDropdownField('CourseLanguage', _t('CourseDetailedSearch.LANGUAGE', 'Language'), $languages, ($currLang ? $currLang->ID : '')),
				new AdvancedDropdownField('CourseTerm', _t('CourseDetailedSearch.TERM', 'Term'), $terms),
				new LiteralField('ExportDataButton', '<button type="button" id="ExportDataButton">' . _t('CourseDataExport.BUTTON', 'Export data') . '</button>'),
				new HiddenField('ExportType', '', 'course-data')
		);
		
		$actions = new FieldSet();
		
		return new Form($this, "ExportCourseDataForm", $fields, $actions);
	}	
	
	public function getExportData() {
		if (!self::isAdmin()) return;
		
		$safeData = Convert::raw2sql($_GET);
		
		$file = (isset($safeData['file']) && $safeData['file'] === 'true') ? true : false;
		$language = isset($safeData['CourseLanguage']) ? $safeData['CourseLanguage'] : null;
		$term = isset($safeData['CourseTerm']) ? $safeData['CourseTerm'] : null;
		$type = isset($safeData['ExportType']) ? $safeData['ExportType'] : '';
				
		Requirements::clear();
				
		if ($type == 'course-data') {
			return CourseDataExporter::generate($language, $term, $file);
		}
		
		$response = new SS_HTTPResponse('Unknown data');
		$response->addHeader("Content-type", "text/html");
		return $response;
	}
	
	public function testim() {
		$message = new IM_Message();
		$message->ToID = 25;
		$message->FromID = 23;
		$message->Subject = 'Testar ' . date('H:i');
		$message->Body = 'Jag testar mina IM:s..';

		$message->send();
		
		Debug::log('Sender has the following message in his inbox: ');
		$from = DataObject::get_by_id('Member', $message->FromID);
		
		if ($from->IM_Inbox()->exists())
			foreach ($$from->IM_Inbox()->Messages() as $message) {
				Debug::log($message->Subject . ' ### ' . $message->Body);
			}
		
		Debug::log('Sender has the following message in his sentbox: ');
		if ($from->IM_Sentbox()->exists())
			foreach ($from->IM_Sentbox()->Messages() as $message) {
				Debug::log($message->Subject . ' ### ' . $message->Body);
			}		
		
		Debug::log('Recipient has the following message in his inbox: ');
		$to = DataObject::get_by_id('Member',  $message->ToID);
		
		if ($to->IM_Inbox()->exists())
			foreach ($to->IM_Inbox()->Messages() as $message) {
				Debug::log($message->Subject . ' ### ' . $message->Body);
			}		
		
		Debug::log('Recipient has the following message in his sentbox: ');
		$to = DataObject::get_by_id('Member',  $message->ToID);
		
		if ($to->IM_Sentbox()->exists())
			foreach ($to->IM_Sentbox()->Messages() as $message) {
				Debug::log($message->Subject . ' ### ' . $message->Body);
			}				
	}
}
