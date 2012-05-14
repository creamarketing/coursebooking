<?php

class Course extends DataObject {
	
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'
	);
	
	static $db = array(
		'Name' => 'Varchar(150)',	
		'RecDateStart' => 'Date',	
		'RecDateEnd' => 'Date',
		'SignupStart' => 'SS_Datetime',
		'SignupEnd' => 'SS_Datetime',
		'SignupExpiresNot' => 'Boolean',
		'CourseCode' => 'Varchar(20)',
		'CourseDescription' => 'Text',
		'CourseBooks' => 'Varchar(255)',
		'MinParticipators' => 'Int',
		'MaxParticipators' => 'Int',
		'CoursePrice' => 'Decimal(10,2)',
		'SubjectsFirst' => 'Int',
		'LanguagesFirst' => 'Int',
		'Status' => "Enum('Active, Passive', 'Active')",
		'Completed' => 'Boolean',
		'DesiredLessions' => 'Decimal(8,2)'
	);
	
	static $translatableFields = array(
		'Name',
		'CourseDescription'
	);
		
	static $has_one = array(
		'Term' 					=> 'Term',
		'CourseUnit' 			=> 'CourseUnit',
		'EducationArea'			=> 'EducationArea',
		'ExpenseAccount'		=> 'ExpenseAccount',
		'IncomeAccount'			=> 'IncomeAccount',
		'CourseType'			=> 'CourseType',
		'AgeGroup'				=> 'AgeGroup',
		'EducationType'			=> 'EducationType',
		'CourseResponsible'		=> 'CourseAdmin',
		'CourseMainClass'		=> 'CourseMainClass'
	);
			
	static $has_many = array(
		'CourseDates'			=> 'CourseDate',
		'CourseRequests'		=> 'CourseRequest'
	);
	
	static $many_many = array(
		'Languages'				=> 'CourseLanguage',
		'Subjects'				=> 'CourseSubject'
	);
	
	static $belongs_many_many = array(
		'GhostParticipators'	=> 'GhostParticipator'
	);
	
	static $summary_fields = array(
		'Name',
		'CourseUnit.Name',
	);
	
	static $defaults = array(
		'SignupExpiresNot' => true,
		'MinParticipators' => 0,
		'MaxParticipators' => 0,
		'CoursePrice' => '0.00',
		'Status' => 'Active'
	);
	
	static $searchable_fields = array(
		'Name' => 'PartialMatchFilter',
		'CourseUnit.Name' => 'PartialMatchFilter'
	);
	
	static $jqgrid_summary_fields = array(
		'Name',
		'Code',
		'CourseUnitName'
	);
	
	static $jqgrid_default_sort = "Name DESC";	
	
	public function canDuplicate($member = null) {
		return CourseBookingExtension::isAdmin();
	}
	
	/*static $searchable_fields = array(
		'Name' => 'PartialMatchFilter'
	);*/
		
	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	function getRequirementsForPopup() {	
		if ($this->isDOMAddForm()) {
			Requirements::javascript('coursebooking/javascript/AddCourseDialog.js');	
		} 
		else if ($this->isDOMDuplicateForm()) {
			Requirements::javascript('coursebooking/javascript/DuplicateCourseDialog.js');	
		}
		else {
			Requirements::javascript('coursebooking/javascript/EditCourseDialog.js');
		}

		Requirements::css('coursebooking/css/CourseDialog.css');
		
		$this->extend('getRequirementsForPopup');
	}
		
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet();
		
		if ($this->isDOMAddForm("Courses")) {
			$fields = $this->getAddFormFields ($includeDOMS);
			$fields->push(new HiddenField('closeAfterAdd', '', 'true'));
		} 
		else if ($this->isDOMDuplicateForm()) {
			$fields = $this->getDuplicateFormFields($includeDOMS);
			$fields->push(new HiddenField('closeAfterDuplicate', '', 'true'));
		}
		else {
			$fields = $this->getEditFormFields ($includeDOMS);
		}
				
		$this->extend('updateCMSFields', $fields);
		
		return $fields;	
	}
		
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}
	
	function Participators() {
		$participators = DataObject::get('Participator', "CourseRequest.CourseID = {$this->ID} AND (CourseRequest.Status = 'Notified' OR CourseRequest.Status = 'Completed')", '', 'LEFT JOIN CourseRequest ON CourseRequest.ParticipatorID = Participator.ID');
		if ($participators)
			return $participators;
		return new DataObjectSet();
	}
	
	function QueuedParticipators() {
		$participators = DataObject::get('Participator', "CourseRequest.CourseID = {$this->ID} AND CourseRequest.Status = 'InQueue'", '', 'LEFT JOIN CourseRequest ON CourseRequest.ParticipatorID = Participator.ID');
		if ($participators)
			return $participators;
		return new DataObjectSet();
	}	
	
	function Teachers() {
		$teachers = DataObject::get('Teacher', 'CourseDateLink.TeacherID = Teacher.ID', '', "LEFT JOIN CourseDate ON CourseDate.CourseID = {$this->ID} LEFT JOIN CourseDateLink ON CourseDateLink.CourseDateID = CourseDate.ID");
		if ($teachers)
			return $teachers;
		return new DataObjectSet();		
	}
	
	function getTeacherList() {
		$teacher_list = array();
		
		foreach($this->Teachers() as $teacher)
			array_push($teacher_list, $teacher->Name);
		
		asort($teacher_list);
		
		return implode('<br/>', $teacher_list);
	}
	
	function getNiceTeachers() {
		$teacher_list = array();
		
		foreach($this->Teachers() as $teacher)
			array_push($teacher_list, $teacher->Name);
		
		asort($teacher_list);
		
		return implode(', ', $teacher_list);
	}	
	
	function getSubjectList() {
		$subject_list = array();
		
		foreach($this->Subjects() as $subject)
			array_push($subject_list, $subject->Code . ' ' . $subject->Name);
		
		asort($subject_list);
		
		return implode('<br/>', $subject_list);
	}
	
	function getNiceSubjects() {
		$subject_list = array();
		
		foreach($this->Subjects() as $subject)
			array_push($subject_list, $subject->Name);
		
		asort($subject_list);
		
		return implode(', ', $subject_list);
	}	
	
	function getLanguageList() {
		$language_list = array();
		
		foreach($this->Languages() as $language) 
			array_push($language_list, $language->Code . ' ' . $language->Name);
		
		asort($language_list);
		
		return implode('<br/>', $language_list);
	}
	
	function getNiceLanguages() {
		$language_list = array();
		
		foreach($this->Languages() as $language) 
			array_push($language_list, $language->Name);
		
		asort($language_list);
		
		return implode(', ', $language_list);
	}	
	
	public function getCourseUnitName() {
		return $this->CourseUnit()->Name;
	}
	
	public function getTermName() {
		return $this->Term()->Name;
	}
	
	public function getCourseName() {
		return $this->Name;
	}	
	
	public function getTotalHours() {
		if (!count($this->CourseDates()))
			return 0;
		$lessions = 0.0;
		
		foreach ($this->CourseDates() as $date) {
			$lessions += (float)$date->Lessions;
		}
		
		return number_format(($lessions * 45.0) / 60, 0);
	}
	
	public function getTotalLessions() {
		if (!count($this->CourseDates()))
			return 0;
		$lessions = 0.0;
		
		foreach ($this->CourseDates() as $date) {
			$lessions += (float)$date->Lessions;
		}
		
		return number_format($lessions, 0);
	}
	
	public function getCourseStatus() {
		$active = true;
		if ($this->Term() && $this->Term()->Active == false)
			$active = false;
		
		if ($this->Status == 'Passive')
			$active = false;
		
		if ($active)
			return _t('Course.STATUS_ACTIVE', 'Active');
		
		return _t('Course.STATUS_PASSIVE', 'Passive');
	}
	
	public function getCourseRequestStatus() {
		$member = CourseBookingExtension::currentUser();
			
		if (!$member)
			return '';
		
		$courseRequests = $member->CourseRequests();
		if (!$courseRequests)
			return '';
		
		$courseRequest = $courseRequests->find('CourseID', $this->ID);
		if (!$courseRequest) 
			return '';
		
		return _t('CourseRequest.STATUS_' . strtoupper($courseRequest->Status), $courseRequest->Status);
	}
	
	public function getIsInQueue() {
		$member = CourseBookingExtension::currentUser();
			
		if (!$member)
			return false;
		
		if ($member && !($member instanceof Participator))
			return false;
		
		$courseRequest = $member->CourseRequests()->find('CourseID', $this->ID);
		if (!$courseRequest) 
			return false;
		
		if ($courseRequest->Status == 'InQueue')
			return true;
		
		return false;
	}
	
	public function getHasQueuedParticipators() {
		if ($this->QueuedParticipators()->Count())
			return true;
		return false;
	}
	
	protected function onBeforeDelete() {
		parent::onBeforeDelete();
		
		foreach ($this->CourseDates() as $courseDate) {
			$courseDate->delete();
		}
	}
	
	protected function onAfterWrite() {
		parent::onAfterWrite();
		
		$safeData = Convert::raw2sql($_POST);
		
		//Debug::log(var_export($safeData, TRUE));
		
		$writeCount = (int)Session::get('onAfterWriteCount' . $this->ID);
		$writeCount++;
		Session::set('onAfterWriteCount' . $this->ID, $writeCount);
		
		//Debug::log('onAfterWriteCount for ' . $this->ID . ' is ' . $writeCount);
		
		if ($writeCount == 2) {	
			
			if (isset($safeData['Rec']))
				$this->generateCourseDates($safeData);

			// Add subjects
			if (isset($safeData['Subjects']))
			{
				$subjectIDs = explode(',', $safeData['Subjects']);

				if (is_array($subjectIDs) && count($subjectIDs) > 0)
				{
					$existingSubjects = $this->Subjects();
					foreach($subjectIDs as $subjectID)
					{
						if (is_numeric($subjectID) && DataObject::get_by_id('CourseSubject', $subjectID))
							$existingSubjects->add($subjectID);
					}
				}
			}

			// Add languages
			if (isset($safeData['Languages']))
			{
				$languagesIDs = explode(',', $safeData['Languages']);

				if (is_array($languagesIDs) && count($languagesIDs) > 0)
				{
					$existingLanguages = $this->Languages();
					foreach($languagesIDs as $languageID)
					{
						if (is_numeric($languageID) && DataObject::get_by_id('CourseLanguage', $languageID))
							$existingLanguages->add($languageID);
					}
				}
			}
			
			// Increase next id on first subject
			if (isset($safeData['SubjectsFirst'])) {
				$subjectID = (int)$safeData['SubjectsFirst'];
				$firstSubject = DataObject::get_by_id('CourseSubject', $subjectID);

				$firstSubject->CourseCodeNextID = $firstSubject->CourseCodeNextID + 1;
				$firstSubject->write();
			}
		} 
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if ($this->ID != 0) {
			$safeData = Convert::raw2sql($_POST);
			
			$existingSubjects = $this->Subjects();
			$existingLanguages = $this->Languages();
					
			// Add subjects
			if (isset($safeData['Subjects']))
			{
				$subjectIDs = explode(',', $safeData['Subjects']);

				$existingSubjects->removeAll();
				
				if (is_array($subjectIDs) && count($subjectIDs) > 0)
				{
					foreach($subjectIDs as $subjectID)
					{
						if (is_numeric($subjectID) && DataObject::get_by_id('CourseSubject', $subjectID))
							$existingSubjects->add($subjectID);
					}
				}			
			}

			// Add languages
			if (isset($safeData['Languages']))
			{
				$languagesIDs = explode(',', $safeData['Languages']);

				$existingLanguages->removeAll();
				
				if (is_array($languagesIDs) && count($languagesIDs) > 0)
				{
					foreach($languagesIDs as $languageID)
					{
						if (is_numeric($languageID) && DataObject::get_by_id('CourseLanguage', $languageID))
							$existingLanguages->add($languageID);
					}
				} 
			}			
		} 
	}
	
	protected function validate() {
		
		$data = $_POST;
		
		$requiredFields = array(
			'TermID' => 'Term.SINGULARNAME',
			'CourseUnitID' => 'CourseUnit.SINGULARNAME',
			'EducationAreaID' => 'EducationArea.SINGULARNAME',
			'Subjects' => 'CourseSubject.PLURALNAME',
			'Languages' => 'CourseLanguage.PLURALNAME'
		);
		
		foreach ($requiredFields as $key => $value) {
			if (isset($data[$key]) && strlen($data[$key]) < 1) {
				return new ValidationResult(false, sprintf(_t('DialogDataObjectManager.FILLOUT', 'Please fill out %s'), _t($value, $value)));
			}
		}

		// Check course code
		$existingCourseByCode = DataObject::get_one('Course', 'CourseCode = ' . (int)$data['CourseCode'] . ' AND TermID = ' . (int)$data['TermID']);
		if (($existingCourseByCode && $existingCourseByCode->ID != $this->ID) || (int)$data['CourseCode'] == 0)
			return new ValidationResult(false, _t('Course.ERROR_EXISTINGCODE', 'A course with that coursecode already exists'));
		
		// Check participants
		if ((int)$data['MinParticipators'] <= 0 || (int)$data['MaxParticipators'] <= 0)
			return new ValidationResult(false, _t('Course.ERROR_PARTICIPATORS', 'Max/min participators must be greather than zero'));
		elseif ((int)$data['MinParticipators'] > (int)$data['MaxParticipators'])
			return new ValidationResult(false, _t('Course.ERROR_MAXPARTICIPATORS', 'The number of maximum participators must be greather than minimum'));
		
		// Check start/end dates
		try {
			$startDate = new Zend_Date($data['RecDateStart'], 'dd.MM.YYYY');
			$endDate = new Zend_Date($data['RecDateEnd'], 'dd.MM.YYYY');
			if ($startDate->compare($endDate) > 0)
				return new ValidationResult(false, _t('Course.ERROR_STARTDATE', 'Course start date must be before end date'));
		} 
		catch (Zend_Date_Exception $e) {
			return new ValidationResult(false, _t('Course.ERROR_COURSEDATEMISSING', 'Missing or invalid course dates'));
		}

		// Check signup dates
		try {
			$startDate = new Zend_Date($data['SignupStart']['date'] . ' ' . $data['SignupStart']['time'], 'dd.MM.YYYY HH:mm');
			$endDate = new Zend_Date($data['SignupEnd']['date'] . ' ' . $data['SignupEnd']['time'], 'dd.MM.YYYY HH:mm');
			if ($startDate->compare($endDate) > 0 && $data['SignupExpiresNot'] == 0)
				return new ValidationResult(false, _t('Course.ERROR_SIGNUPDATE', 'Signup start date must be before end date'));
		} 
		catch (Zend_Date_Exception $e) {
			return new ValidationResult(false, _t('Course.ERROR_SIGNUPMISSING', 'Missing or invalid signup dates'));
		}
		
		// Course dates
		$courseDates = false;
		for ($i=0;$i<7;$i++) {
			if ((isset($data['Rec']) && isset($data['Rec'][$i]) && isset($data['Rec'][$i]['Chk'])) || $this->ID != 0) {
				$courseDates = true;
				break;
			}
		}
		
		if (!$courseDates)
			return new ValidationResult(false, _t('Course.ERROR_NOCOURSEDATES', 'No course days selected'));
			
		return parent::validate();
	}
	
	public function getNiceCoursePrice() {
		return ($this->CoursePrice . ' €');
	}
	
	public function getParticipatorsSummary() {
		return $this->Participators()->Count() . ' / ' . $this->MaxParticipators . ' (min ' . $this->MinParticipators . ')';
	}
		
	public function getAddFormFields($includeDOMS, $duplicateForm = false) {
		$eBooking = new eBookingCommunicator();
				
		$fields = new FieldSet();
		$tabSet = new DialogTabSet('TabSet');
		
		// First tab
		$educationAreas = EducationArea::toDropdownList(); 
		$languagesDropdown = CourseLanguage::toDropdownList();
		$subjectsDropdown = CourseSubject::toDropdownList();
		$mainClassesDropdown = CourseMainClass::toDropdownList();

		$incomeAccounts_array = IncomeAccount::toDropdownList(); 
		$expenseAccounts_array = ExpenseAccount::toDropdownList(); 
		
		$status_array = singleton('Course')->dbObject('Status')->enumValues();
		// Add translations for status
		foreach($status_array as $key => &$value)
			$value = _t('Course.STATUS_' . strtoupper($value), $value);		

		$subjectsDropdownValue = '[initial]' . implode(',', array_keys($this->Subjects()->getIdList()));
		$languagesDropdownValue = '[initial]' . implode(',', array_keys($this->Languages()->getIdList()));
		
		$tabSet->push($tab1 = new Tab('Tab1', _t('AddCourseForm.TAB1', 'General'),
			$group1 = new FieldGroup(
				new AdvancedDropdownField(				
					'TermID', 
					_t('Term.SINGULARNAME', 'Term'), 
					$this->getArrayForObject('Term', 'DateStart DESC, DateEnd DESC', 'Active=1')
				),
				new AdvancedDropdownField(				
					'CourseUnitID', 
					_t('CourseUnit.SINGULARNAME', 'Unit'),
					$this->getArrayForObject('CourseUnit', 'Name ASC'),
					CreaDefaultSelectable::getDefaultSelected('CourseUnit')
				),								
				new AdvancedDropdownField(				
					'CourseResponsibleID', 
					_t('Course.COURSERESPONSIBLE', 'Course responsible'), 
					CourseAdmin::toDropdownList(),
					CourseBookingExtension::getAdminID()
				)
			),
			new HiddenField('SubjectsFirst', ''),				
			$group2 = new FieldGroup(		
				new AdvancedDropdownField(
					'Subjects', 
					_t('CourseSubject.PLURALNAME', 'Subjects'),
					$subjectsDropdown, 
					($duplicateForm ? $subjectsDropdownValue : ''),
					false,
					false,
					'selectCourseSubject',
					'showCourseSubjects'
				)
			),
			$group3 = new FieldGroup(
				new AdvancedDropdownField(
					'CourseMainClassID',
					_t('CourseMainClass.SINGULARNAME', 'Main class'),
					$mainClassesDropdown
				),
				new AdvancedDropdownField(				
					'EducationAreaID', 
					_t('EducationArea.SINGULARNAME', 'Area of education'), 
					$educationAreas
				)					
			),	
			$group4 = new FieldGroup(
				new AdvancedDropdownField(				
					'CourseTypeID', 
					_t('CourseType.SINGULARNAME', 'Course type'), 
					CourseType::toDropdownList(),
					CreaDefaultSelectable::getDefaultSelected('CourseType')
				),
				new AdvancedDropdownField(				
					'AgeGroupID', 
					_t('AgeGroup.SINGULARNAME', 'Age group'), 
					AgeGroup::toDropdownList(),
					CreaDefaultSelectable::getDefaultSelected('AgeGroup')
				),
				new AdvancedDropdownField(				
					'EducationTypeID', 
					_t('EducationType.SINGULARNAME', 'Education type'), 
					EducationType::toDropdownList(),
					CreaDefaultSelectable::getDefaultSelected('EducationType')
				)					
			),				
			$group5 = new FieldGroup(
				new TextField('CourseCode', _t('Course.COURSECODE', 'Course code')),
				new DropdownField('Status', _t('Course.STATUS', 'Course status'), $status_array),
				new CheckboxField('Completed', _t('Course.COMPLETED_CHECKBOX', 'Completed'))										
			),
			new HiddenField('LanguagesFirst', ''),
			$group6 = new FieldGroup(new AdvancedDropdownField(
					'Languages', 
					_t('CourseLanguage.PLURALNAME', 'Languages'),
					$languagesDropdown,
					($duplicateForm ? $languagesDropdownValue : ''),
					false,
					false,
					'selectCourseLanguage',
					'showCourseLanguages'						
			)),
			new TextField('Name', _t('Course.NAME', 'Course name'))
		));		

		$group1->addExtraClass('group1');
		$group2->addExtraClass('group2');
		$group3->addExtraClass('group3');
		$group4->addExtraClass('group4');
		$group5->addExtraClass('group5');
		$group6->addExtraClass('group6');
		
		// Second tab
		$tabSet->push(new Tab('Tab2', _t('AddCourseForm.TAB2', 'Additional info'),
			$group7 = new FieldGroup(new TextField('CourseBooks', _t('Course.COURSEBOOKS', 'Course books'))),
			$group8 = new FieldGroup(
				new NumericField('MinParticipators', _t('Course.MINPARTICIPATORS', 'Min participators')),
				new NumericField('MaxParticipators', _t('Course.MAXPARTICIPATORS', 'Max participators')),
				new NumericFieldEx('CoursePrice', _t('Course.PRICE', 'Price'))
			),
			$group9 = new FieldGroup(
				new AdvancedDropdownField(				
					'IncomeAccountID', 
					_t('IncomeAccount.SINGULARNAME', 'Income account'), 
					$incomeAccounts_array,
					CreaDefaultSelectable::getDefaultSelected('IncomeAccount')
				),
				new AdvancedDropdownField(				
					'ExpenseAccountID', 
					_t('ExpenseAccount.SINGULARNAME', 'Expense account'), 
					$expenseAccounts_array,
					CreaDefaultSelectable::getDefaultSelected('ExpenseAccount')
				)
			),
			new TextareaField('CourseDescription', _t('Course.DESCRIPTION'))
		));
		
		$group7->addExtraClass('group7');
		$group8->addExtraClass('group8');
		$group9->addExtraClass('group9');
		
		// Third tab

		$weekDaysi18n = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY');
		$weekDaysi18nDefaults = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		$tabSet->push($tab3 = new Tab('Tab3', _t('AddCourseForm.TAB3', 'Location and dates'),
			$grp = new FieldGroup(
				$startDate = new DateFieldEx('RecDateStart', _t('CourseDate.RECDATESTART', 'Course start')),
				$endDate = new DateFieldEx('RecDateEnd', _t('CourseDate.RECDATEEND', 'Course end'))
			),
			$signupStart = new DatetimeFieldEx('SignupStart', _t('CourseDate.SIGNUPSTART', 'Signup start')),
			$signupEnd = new DatetimeFieldEx('SignupEnd', _t('CourseDate.SIGNUPEND', 'Signup end')),
			new FieldGroup(new CheckboxField('SignupExpiresNot', _t('CourseDate.SIGNUPEND_CHECKBOX', 'Signup time never expires'), 1))
		));		

		// Hack when datefields are inside fieldgroup
		$grp->addExtraClass("date {'showcalendar':true,'dateFormat':'dd.mm.yy'}");
		$grp->addExtraClass('group10');
		
		$tab3->push(new NumericFieldEx('DesiredLessions', _t('Course.DESIRED_LESSIONS', 'Desired amount of lessions')));

		$teachersArray = Teacher::toDropdownList();
		$courseLocationsArray = $eBooking->getResourcesAsDropdownMap();
				
		$tab3->push($locationTeachersGroup = new FieldGroup(				
						new AdvancedDropdownField("MainLocationID", _t('CourseDate.LOCATIONNICE', 'Location'), $courseLocationsArray),
						new AdvancedDropdownField("MainTeacher", _t('Teacher.SINGULARNAME', 'Teacher') . ' #1', $teachersArray),
						new AdvancedDropdownField("MainTeacher2", _t('Teacher.SINGULARNAME', 'Teacher') . ' #2', $teachersArray)				
					)
				);
		
		$locationTeachersGroup->addExtraClass('location-teacher-group');

		/*
		$startDate->setConfig('dateformat', 'dd.MM.YYYY');
		$endDate->setConfig('dateformat', 'dd.MM.YYYY');
		
		$startDate->setConfig('showcalendar', 'true');
		$endDate->setConfig('showcalendar', 'true');
		
		$signupStart->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$signupEnd->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$signupStart->getTimeField()->setConfig('timeformat', 'HH.mm');
		$signupEnd->getTimeField()->setConfig('timeformat', 'HH.mm');
		
		$signupStart->getDateField()->setConfig('showcalendar', 'true');
		$signupEnd->getDateField()->setConfig('showcalendar', 'true');*/
		
		$tab3->push($datesGroup = new FieldGroup());
		$datesGroup->addExtraClass('weekday-selection');
		
		for ($i=0; $i<7; $i++) {
			$datesGroup->push($field_group = new FieldGroup(
				$tf_checkbox = new CheckboxField("Rec[$i][Chk]", _t("CourseDate.{$weekDaysi18n[$i]}", "{$weekDaysi18nDefaults[$i]}")),
				$weekday_row = new FieldGroup(
					$tf_start = new TimeFieldEx("Rec[$i][Time][Start]", _t('CourseDate.STARTTIME', 'Start')),
					$tf_end = new TimeFieldEx("Rec[$i][Time][End]", _t('CourseDate.ENDTIME', 'End')),
					$tf_lessions = new NumericFieldEx("Rec[$i][Lessions]", _t('CourseDate.LESSIONS', 'Lessions')),
					$tf_location = new AdvancedDropdownField("Rec[$i][Location]", _t('CourseDate.LOCATIONNICE', 'Location'), $courseLocationsArray),
					new LiteralField('DivClear1', '<div style="clear: both">&nbsp;</div>'),						
					$weekday_row_inner = new FieldGroup(
					$tf_teacher = new AdvancedDropdownField("Rec[$i][Teacher]", _t('Teacher.SINGULARNAME', 'Teacher') . ' #1', $teachersArray),
					$tf_teacher_lessions = new NumericFieldEx("Rec[$i][Teacher-Lessions]", _t('CourseDate.LESSIONS', 'Lessions') . ' #1'),
						new LiteralField('DivClear2', '<div style="clear: both">&nbsp;</div>'),						
					$tf_teacher2 = new AdvancedDropdownField("Rec[$i][Teacher2]", _t('Teacher.SINGULARNAME', 'Teacher') . ' #2', $teachersArray),
					$tf_teacher2_lessions = new NumericFieldEx("Rec[$i][Teacher2-Lessions]", _t('CourseDate.LESSIONS', 'Lessions') . ' #2'))
				)
			));
			
			$tf_start->setMaxLength(5);
			$tf_end->setMaxLength(5);
			$tf_lessions->setMaxLength(4);
			$tf_teacher_lessions->setMaxLength(4);
			$tf_teacher2_lessions->setMaxLength(4);
			
			/*
			$tf_start->setDisabled(true);
			$tf_end->setDisabled(true);
			$tf_lessions->setDisabled(true);
			$tf_location->setDisabled(true);
			$tf_teacher->setDisabled(true);
			$tf_teacher2->setDisabled(true);
			*/
			
			$tf_start->addExtraClass('weekday-selection-timefield');
			$tf_end->addExtraClass('weekday-selection-timefield');
			
			$tf_checkbox->addExtraClass('weekday-selection-checkbox');
			$field_group->addExtraClass('weekday-selection-field');
			$weekday_row->addExtraClass('weekday-row');
			$weekday_row_inner->addExtraClass('weekday-row-inner');
		}
	
		
		$tab3->push(new FieldGroup(new LiteralField('CheckBookingButton', '<button type="button" id="Form_AddCourseFormDialogTabs_CheckBookingButton">' . _t('CourseBooking.CHECKBOOKING', 'Check') . '</button><div id="Form_AddCourseFormDialogTabs_DateCheckAjaxLoader" style="display: inline; visibility: hidden;"><img style="width: 12px; height: 12px; margin-right: 10px" src="dataobject_manager/images/ajax-loader-white.gif" alt="Loading in progress..." />Loading...</div>'),
					new LiteralField('SummaryBox', '<div id="Form_AddCourseFormDialogTabs_SummaryBox" class="field"></div>')));
		
		// Add summary tab
		$tabSet->push($this->getAddSummaryTab());
		
		$fields->push($tabSet);	
		
		return $fields;
	}
	
	public function getDuplicateFormFields($includeDOMS) {
		$fields = $this->getAddFormFields($includeDOMS, true);
				
		return $fields;
	}
	
	public function getEditFormFields($includeDOMS) {
		$fields = new FieldSet();
		
		$educationAreas = EducationArea::toDropdownList(); 
		$languagesDropdown = CourseLanguage::toDropdownList();
		$subjectsDropdown = CourseSubject::toDropdownList();
		$mainClassesDropdown = CourseMainClass::toDropdownList();

		$incomeAccounts_array = IncomeAccount::toDropdownList(); 
		$expenseAccounts_array = ExpenseAccount::toDropdownList(); 
		
		$status_array = singleton('Course')->dbObject('Status')->enumValues();
		// Add translations for status
		foreach($status_array as $key => &$value)
			$value = _t('Course.STATUS_' . strtoupper($value), $value);				
		
		$subjectsDropdownValue = '[initial]' . implode(',', array_keys($this->Subjects()->getIdList()));
		$languagesDropdownValue = '[initial]' . implode(',', array_keys($this->Languages()->getIdList()));
		
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),			
			$group1 = new FieldGroup(
				new AdvancedDropdownField(				
					'TermID', 
					_t('Term.SINGULARNAME', 'Term'), 
					$this->getArrayForObject('Term', 'DateStart DESC, DateEnd DESC', 'Active=1')
				),
				new AdvancedDropdownField(				
					'CourseUnitID', 
					_t('CourseUnit.SINGULARNAME', 'Unit'),
					$this->getArrayForObject('CourseUnit', 'Name ASC'),
					CreaDefaultSelectable::getDefaultSelected('CourseUnit')
				),								
				new AdvancedDropdownField(				
					'CourseResponsibleID', 
					_t('Course.COURSERESPONSIBLE', 'Course responsible'), 
					CourseAdmin::toDropdownList(),
					CourseBookingExtension::getAdminID()
				)
			),
			new HiddenField('SubjectsFirst', ''),				
			$group2 = new FieldGroup(		
				new AdvancedDropdownField(
					'Subjects', 
					_t('CourseSubject.PLURALNAME', 'Subjects'),
					$subjectsDropdown, 
					$subjectsDropdownValue,
					false,
					false,
					'selectCourseSubject',
					'showCourseSubjects'
				)
			),
			$group3 = new FieldGroup(
				new AdvancedDropdownField(
					'CourseMainClassID',
					_t('CourseMainClass.SINGULARNAME', 'Main class'),
					$mainClassesDropdown
				),
				new AdvancedDropdownField(				
					'EducationAreaID', 
					_t('EducationArea.SINGULARNAME', 'Area of education'), 
					$educationAreas
				)					
			),	
			$group4 = new FieldGroup(
				new AdvancedDropdownField(				
					'CourseTypeID', 
					_t('CourseType.SINGULARNAME', 'Course type'), 
					CourseType::toDropdownList(),
					CreaDefaultSelectable::getDefaultSelected('CourseType')
				),
				new AdvancedDropdownField(				
					'AgeGroupID', 
					_t('AgeGroup.SINGULARNAME', 'Age group'), 
					AgeGroup::toDropdownList(),
					CreaDefaultSelectable::getDefaultSelected('AgeGroup')
				),
				new AdvancedDropdownField(				
					'EducationTypeID', 
					_t('EducationType.SINGULARNAME', 'Education type'), 
					EducationType::toDropdownList(),
					CreaDefaultSelectable::getDefaultSelected('EducationType')
				)					
			),				
			$group5 = new FieldGroup(
				new TextField('CourseCode', _t('Course.COURSECODE', 'Course code')),
				new DropdownField('Status', _t('Course.STATUS', 'Course status'), $status_array),
				new CheckboxField('Completed', _t('Course.COMPLETED_CHECKBOX', 'Completed'))										
			),
			new HiddenField('LanguagesFirst', ''),
			$group6 = new FieldGroup(new AdvancedDropdownField(
					'Languages', 
					_t('CourseLanguage.PLURALNAME', 'Languages'),
					$languagesDropdown,
					$languagesDropdownValue,
					false,
					false,
					'selectCourseLanguage',
					'showCourseLanguages'						
			)),
				
			new TextField('Name', _t('Course.NAME', 'Name')),
			new TextareaField('CourseDescription',  _t('Course.DESCRIPTION', 'Description')),
				
			$group7 = new FieldGroup(new TextField('CourseBooks', _t('Course.COURSEBOOKS', 'Course books'))),
			$group8 = new FieldGroup(
				new NumericField('MinParticipators', _t('Course.MINPARTICIPATORS', 'Min participators')),
				new NumericField('MaxParticipators', _t('Course.MAXPARTICIPATORS', 'Max participators')),
				new NumericFieldEx('CoursePrice', _t('Course.PRICE', 'Price'))
			),
			$group9 = new FieldGroup(
				new AdvancedDropdownField(				
					'IncomeAccountID', 
					_t('IncomeAccount.SINGULARNAME', 'Income account'), 
					$incomeAccounts_array,
					CreaDefaultSelectable::getDefaultSelected('IncomeAccount')
				),
				new AdvancedDropdownField(				
					'ExpenseAccountID', 
					_t('ExpenseAccount.SINGULARNAME', 'Expense account'), 
					$expenseAccounts_array,
					CreaDefaultSelectable::getDefaultSelected('ExpenseAccount')
				)
			),
			new LiteralField('BottomSpacer', '<div style="height: 80px">&nbsp;</div>')
		);		
		
		$group1->addExtraClass('group1');
		$group2->addExtraClass('group2');
		$group3->addExtraClass('group3');
		$group4->addExtraClass('group4');
		$group5->addExtraClass('group5');
		$group6->addExtraClass('group6');
		$group7->addExtraClass('group7');
		$group8->addExtraClass('group8');
		$group9->addExtraClass('group9');
			
		$weekDaysi18n = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY');
		$weekDaysi18nDefaults = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		
		$mainLocations = '';
		foreach ($this->getMainLocation()->Full as $location) {
			$mainLocations .= $location->Name . ', ' . $location->PostAddress . ', ' . $location->PostCode . ', ' . $location->PostOffice;
			if (!$location->Last())
				$mainLocations .= "\n";
		}
		$mainTimes = $this->getMainTimes()->Full;
		
		$tabRecurring = new Tab('Recurring', _t('CourseDate.RECURRING', 'Recurring'),			
			$grp = new FieldGroup(
				$startDate = new DateFieldEx('RecDateStart', _t('CourseDate.RECDATESTART', 'Course start')),
				$endDate = new DateFieldEx('RecDateEnd', _t('CourseDate.RECDATEEND', 'Course end'))
			),
			$signupStart = new DatetimeFieldEx('SignupStart', _t('CourseDate.SIGNUPSTART', 'Signup start')),
			$signupEnd = new DatetimeFieldEx('SignupEnd', _t('CourseDate.SIGNUPEND', 'Signup end')),
			new FieldGroup(new CheckboxField('SignupExpiresNot', _t('CourseDate.SIGNUPEND_CHECKBOX', 'Signup time never expires'), 1)),
			new NumericFieldEx('DesiredLessions', _t('Course.DESIRED_LESSIONS', 'Desired amount of lessions')),
			new ReadonlyField('TotalLessions', _t('Course.SUMMARY_TOTALLESSIONS', 'Total lessions'), $this->getTotalLessions()),
			new LiteralField('TotalLessionsWarning', '<div id="DialogDataObjectManager_Popup_DetailForm_TotalLessionsWarning" style="height: 30px; color: red;">' . _t('Course.SUMMARY_LESSIONS_MISSMATCH', 'Total lessions') . '</div>'),
			$mainLocation = new ReadonlyField('MainLocationText', _t('Course.MAINLOCATION', 'Main location'), $mainLocations),
			$mainTimes = new ReadonlyField('MainTimesText', _t('Course.MAINTIMES', 'Main times'), $mainTimes)
		);
		
		// Hack when datefields are inside fieldgroup
		$grp->addExtraClass("date {'showcalendar':true,'dateFormat':'dd.mm.yy'}");		
		
		/*
		$startDate->setConfig('dateformat', 'dd.MM.YYYY');
		$endDate->setConfig('dateformat', 'dd.MM.YYYY');
		
		$startDate->setConfig('showcalendar', 'true');
		$endDate->setConfig('showcalendar', 'true');
		
		$signupStart->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$signupEnd->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$signupStart->getTimeField()->setConfig('timeformat', 'HH.mm');
		$signupEnd->getTimeField()->setConfig('timeformat', 'HH.mm');	
		
		$signupStart->getDateField()->setConfig('showcalendar', 'true');
		$signupEnd->getDateField()->setConfig('showcalendar', 'true');	
		*/
				
		$dateDOM = new DialogHasManyDataObjectManager(
			$this, 
			'CourseDates', 
			'CourseDate', 
			array(
				'TimeNice' => _t('CourseDate.TIMENICE', 'Time'),
				'LocationNice' => _t('CourseDate.LOCATIONNICE', 'Location'),
				'Lessions' => _t('CourseDate.LESSIONS', 'Lessions'),
				'CancelledNice' => _t('CourseDate.CANCELLEDNICE', 'Cancelled'),
				'WeekDayNice' => _t('CourseDate.WEEKDAYNICE', 'Weekday'),
				'TeacherNice' => _t('CourseDate.TEACHERNICE', 'Teacher'),
				'PaymentMonthNice' => _t('CourseDate.PAYMENTMONTHNICE', 'PM'),
			),
			null,
			"(CourseID = {$this->ID} OR CourseID = 0)"
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
			'TimeNice' => 16,
			'LocationNice' => 16,
			'Lessions' => 13,
			'CancelledNice' => 13,
			'WeekDayNice' => 13,
			'TeacherNice' => 22,
			'PaymentMonthNice' => 7)
		);	
		
		$tabSetDates = 	new DialogTabSet('TabSet', _t('CourseDate.PLURALNAME', 'Course dates'), 
				$tabRecurring,
				new Tab('CourseDate', _t('CourseDate.SPECIFIEDDATES', 'Specified dates'),		
					$dateDOM,
					new LiteralField('Legend', '
						<div id="Legend">
							<ul>
								<li>
									<div class="legendColor" style="background-color: #FF8080;"></div>
									<label>' . _t('CourseDate.LABEL_CONFLICTING', 'Conflicting') . '</label>
								</li>
								<li>
									<div class="legendColor" style="background-color: #FFF080;"></div>
									<label>' . _t('CourseDate.LABEL_NOLESSIONS', 'No lessions') . '</label>
								</li>
							</ul>
						</div>')
				)
		);
		
		$tabParticipators = new Tab('ParticipatorsTab', _t('Participator.PLURALNAME', 'Participators'),		
			$participatorsDOM = new DialogDataObjectManager(
				$this, 
				'Participators', 
				'Participator', 
				null,
				null,
				"CourseRequest.CourseID = {$this->ID} AND (CourseRequest.Status = 'Notified' OR CourseRequest.Status = 'Completed')",
				null,
				"LEFT JOIN CourseRequest ON CourseRequest.ParticipatorID = Participator.ID"
			)		
		);
		
		if ($this->ID) {
			//$participatorsDOM->setOnlyRelated(true);
			$participatorsDOM->removePermission('add');
			$participatorsDOM->removePermission('delete');
		} 
		
		$tabRequests = new Tab('Requests', _t('CourseRequest.PLURALNAME', 'Requests'),
			$requestsDOM = new DialogHasManyDataObjectManager(
				$this,
				'CourseRequests',
				'CourseRequest',
				array(
					'ParticipatorName' => _t('Participator.NAME', 'Participator name'),
					'StatusNice' => _t('CourseRequest.STATUS', 'Course status'),
					'CreatedNice' => _t('CourseRequest.CREATED', 'Request created')
				),
				null,
				"(CourseID = {$this->ID} OR CourseID = 0)"
			)
		);
						
		if ($includeDOMS) {
			$fields->push (
				new DialogTabSet('TabSet',
					$tabGeneral,
					$tabSetDates,
					new Tab('TeachersTab', _t('Teacher.PLURALNAME', 'Teachers'),
						$teacherDOM = new DialogDataObjectManager(
							$this, 
							'Teachers', 
							'Teacher', 
							array(
								'Surname' => _t('Teacher.Surname', 'Surname'), 
								'FirstName' => _t('Teacher.FIRSTNAME', 'Firstname'),
								'Phone' => _t('Teacher.PHONE', 'Phone'),
								'Email' => _t('Teacher.EMAIL', 'Email')
							),
							null,
							"CourseDateLink.TeacherID = Teacher.ID",
							null,
							"LEFT JOIN CourseDate ON CourseDate.CourseID = {$this->ID} LEFT JOIN CourseDateLink ON CourseDateLink.CourseDateID = CourseDate.ID"
						)
					),
					$tabParticipators,
					$tabRequests
				)
			);
			
			$teacherDOM->setAddTitle(_t('Teacher.SINGULARNAME', 'Teacher'));
			$teacherDOM->setPluralTitle(_t('Teacher.PLURALNAME', 'Teachers'));
			if ($this->ID) {
				//$teacherDOM->setOnlyRelated(true);
				$teacherDOM->removePermission('add');
				$teacherDOM->removePermission('delete');
			}
			
		} else {
			$fields->push ( 
				new DialogTabSet('TabSet',
					$tabGeneral
				)
			);
		}
		
		return $fields;
	}
	
	protected function generateCourseDates($data) {					
		$eBooking = new eBookingCommunicator();
				
		// Course dates and times
		$weekDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		
		// Go trough repeating times
		for ($i=0;$i<7;$i++) {

			if (!isset($data['Rec']) || !isset($data['Rec'][$i]))
				continue;
			
			$rec = $data['Rec'][$i];
			
			// Check if this day is checked
			if (isset($rec['Chk'])) {			
				$startDate = strtotime($data['RecDateStart']);
				$endDate = strtotime($data['RecDateEnd']);
				
				$currentDate = strtotime('this ' . $weekDays[$i], $startDate);
				if ($currentDate < $startDate)
					$currentDate = strtotime('next ' . $weekDays[$i], $startDate);
				
				while($currentDate >= $startDate && $currentDate <= $endDate) // is within range
				{					
					$courseStart = date('d.m.Y', $currentDate) . ' ' . $rec['Time']['Start'];
					$courseEnd = date('d.m.Y', $currentDate) . ' ' . $rec['Time']['End'];
					
					$courseDateObject = new CourseDate();
					
					/*$conflict = CourseDate::checkForConflicting($courseStart, $courseEnd, $rec['Location'], $this->TermID, $eBooking);
					if ($conflict['conflict'] == true) {
						Debug::log("Created date but found conflict: " . $conflict['msg_plain']);
						$courseDateObject->Conflicting = true;
						$courseDateObject->ConflictingReason = $conflict['msg_plain'];
					}*/
					
					$courseDateObject->TimeStart = date('d.m.Y', $currentDate) . ' ' . $rec['Time']['Start'];
					$courseDateObject->TimeEnd = date('d.m.Y', $currentDate) . ' ' . $rec['Time']['End'];
					$courseDateObject->Lessions = (float)$rec['Lessions'];
					$courseDateObject->BookingID = (int)$eBooking->createBooking($courseStart, $courseEnd, $rec['Location']);
					$courseDateObject->ResourceID = (int)$rec['Location'];
					$courseDateObject->CourseID = (int)$this->ID;
					
					$courseDateObject->write();
					
					$teacher = DataObject::get_by_id('Teacher', (int)$rec['Teacher']);
					$teacher2 = DataObject::get_by_id('Teacher', (int)$rec['Teacher2']);
					
					if ($teacher) {
						$courseDateLink = new CourseDateLink();
						$courseDateLink->TeacherID = (int)$rec['Teacher'];
						$courseDateLink->TeacherHourTypeID = (int)$teacher->DefaultHourType()->ID;
						$courseDateLink->TeacherSalaryClassID = (int)$teacher->DefaultSalaryClass()->ID;
						$courseDateLink->CourseDateID = (int)$courseDateObject->ID;
						$courseDateLink->Lessions = (float)$rec['Teacher-Lessions'];
						$courseDateLink->PaymentMonth = date('n', $currentDate);
						$courseDateLink->write();
					}
					
					if ($teacher2) {
						$courseDateLink = new CourseDateLink();
						$courseDateLink->TeacherID = (int)$rec['Teacher2'];
						$courseDateLink->TeacherHourTypeID = (int)$teacher2->DefaultHourType()->ID;
						$courseDateLink->TeacherSalaryClassID = (int)$teacher2->DefaultSalaryClass()->ID;
						$courseDateLink->CourseDateID = (int)$courseDateObject->ID;
						$courseDateLink->Lessions = (float)$rec['Teacher2-Lessions'];
						$courseDateLink->PaymentMonth = date('n', $currentDate);
						$courseDateLink->write();
					}
																		
					$this->CourseDates()->add($courseDateObject->ID);
										
					$currentDate = strtotime('next ' . $weekDays[$i], $currentDate);
				}
			}
		}
	}
	
	public function getAddSummaryTab() {
		$tab = new Tab('Summary', _t('Course.SUMMARY', 'Summary'));

		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[Code]', _t('Course.COURSECODE', 'Course code') . ': ', ''),
					new ReadonlyField('Summary[Term]', _t('Term.SINGULARNAME', 'Term name') . ': ', ''),
					new ReadonlyField('Summary[CourseUnit]', _t('CourseUnit.SINGULARNAME', 'Course unit') . ': ', ''),
					new ReadonlyField('Summary[EducationArea]', _t('EducationArea.SINGULARNAME', 'Education area') . ': ', '')));
		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[Subjects]', _t('CourseSubject.PLURALNAME', 'Course subjects') . ': ', ''),
					new ReadonlyField('Summary[Languages]', _t('CourseLanguage.PLURALNAME', 'Course languages') . ': ', '')));
		$tab->push(new ReadonlyField('Summary[Name]', _t('Course.SINGULARNAME', 'Course name') . ': ', ''));
		$tab->push(new ReadonlyField('Summary[Desc]', _t('Course.DESCRIPTION', 'Course description') . ': ', ''));
		$tab->push(new ReadonlyField('Summary[CourseBooks]', _t('Course.COURSEBOOKS', 'Course books') . ': ', ''));
		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[MinParticipators]', _t('Course.MINPARTICIPATORS', 'Course min participators') . ': ', ''),
					new ReadonlyField('Summary[MaxParticipators]', _t('Course.MAXPARTICIPATORS', 'Course max participators') . ': ', ''),
					new ReadonlyField('Summary[CoursePrice]', _t('Course.PRICE', 'Course price') . ': ', '')));
		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[IncomeAccount]', _t('IncomeAccount.SINGULARNAME', 'Income account') . ': ', ''),
					new ReadonlyField('Summary[ExpenseAccount]', _t('ExpenseAccount.SINGULARNAME', 'Expense account') . ': ', '')));		
		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[CourseStart]', _t('CourseDate.RECDATESTART', 'Course start') . ': ', ''),
					new ReadonlyField('Summary[CourseEnd]', _t('CourseDate.RECDATEEND', 'Course start') . ': ', '')));
		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[SignupStart]', _t('Term.SIGNUPSTART', 'Signup start') . ': ', ''),
					new ReadonlyField('Summary[SignupEnd]', _t('Term.SIGNUPEND', 'signup start') . ': ', '')));
		$tab->push(new FieldGroup(
					new ReadonlyField('Summary[Location]', _t('CourseDate.LOCATIONNICE', 'Location') . ': ', ''),
					new ReadonlyField('Summary[Teacher]', _t('Teacher.SINGULARNAME', 'Teacher') . ' #1: ', ''),
					new ReadonlyField('Summary[Teacher2]', _t('Teacher.SINGULARNAME', 'Teacher') . ' #2: ', '')));
		
		return $tab;
	}
	
	public function getIssues() {
		foreach ($this->CourseDates() as $courseDate) {
			if ($courseDate->Conflicting)
					return true;
		}
		if ($this->DesiredLessions > 0 && $this->getTotalLessions() != $this->DesiredLessions)
			return true;
		return false;
	}
	
	public function getCMSFieldsForjqGrid() {
		return $this->getCMSFields(true);
	}
	
	public function getName() {
		$name = '';
		$locales = array();
		$selectedLanguages = $this->Languages();
		foreach ($selectedLanguages as $lang)
			$locales[] = $lang->Locale;
		
		if (!count($locales))
			return $this->getField('Name');
		
		// Course has a name in our current locale?
		if (in_array(i18n::get_locale(), $locales)) {
			return $this->getField('Name_' . i18n::get_locale());
		}
		
		// Do we have a first selected language?
		if ($this->LanguagesFirst > 0) {
			$language = DataObject::get_by_id('CourseLanguage', $this->LanguagesFirst);
			if ($language) {
				return $this->getField('Name_' . $language->Locale);
			}
		}
		
		// Otherwise return the first language
		$firstLanguage = $selectedLanguages->First();
		return $this->getField('Name_' . $firstLanguage->Locale);
	}

	public function getCourseDescription() {
		$name = '';
		$locales = array();
		$selectedLanguages = $this->Languages();
		foreach ($selectedLanguages as $lang)
			$locales[] = $lang->Locale;
		
		if (!count($locales))
			return $this->getField('CourseDescription');
		
		// Course has a name in our current locale?
		if (in_array(i18n::get_locale(), $locales)) {
			return $this->getField('CourseDescription_' . i18n::get_locale());
		}
		
		// Do we have a first selected language?
		if ($this->LanguagesFirst > 0) {
			$language = DataObject::get_by_id('CourseLanguage', $this->LanguagesFirst);
			if ($language) {
				return $this->getField('CourseDescription_' . $language->Locale);
			}
		}
		
		// Otherwise return the first language
		$firstLanguage = $selectedLanguages->First();
		return $this->getField('CourseDescription_' . $firstLanguage->Locale);
	}	
	
	public function getNameList() {
		$name = '';
		$locales = array();
		$selectedLanguages = $this->Languages();
		foreach ($selectedLanguages as $lang)
			$locales[] = $lang->Locale;
		
		foreach ($locales as $locale) {
			$locale_name = $this->getField('Name_' . $locale);
			if (!empty($name) && !empty($locale_name))
				$name .= '<br/>';
				
			$name .= $locale_name;
		}	
		
		if (empty($name))
			$name = $this->getField('Name');
		return $name;
	}

	public function getDescriptionList() {
		$description = '';
		$locales = array();
		$selectedLanguages = $this->Languages();
		foreach ($selectedLanguages as $lang)
			$locales[] = $lang->Locale;
		
		foreach ($locales as $locale) {
			$locale_desc = $this->getField('CourseDescription_' . $locale);
			if (!empty($description) && !empty($locale_desc))
				$description .= '<br/>';
				
			$description .= $locale_desc;
		}	
		
		if (empty($description))
			$description = $this->getField('CourseDescription');
		return $description;
	}	
	
	public function getDOMTitle() {
		$nameList = $this->NameList;
		$nameList = str_replace('<br/>', ' ', $nameList);
		return $this->CourseCode . ' - ' . $nameList;
	}
	
	public function getFull() {
		if ($this->FreeSpots == 0)
			return true;
		return false;
	}
	
	public function getFreeSpots() {
		$normals = $this->Participators()->Count();
		$ghosts = $this->GhostParticipators()->Count();
		
		if ($ghosts > 0) {
			foreach ($this->GhostParticipators() as $ghost) {
				if ($ghost->Alive == false)
					$ghosts--;
			}
		}
		
		$free_spots = $this->MaxParticipators - ($normals + $ghosts);
		
		if ($free_spots < 0)
			$free_spots = 0;
		
		return $free_spots;
	}
	
	public function getCurrentUserCourseRequest() {
		if ($currentMember = CourseBookingExtension::currentUser()) {
			if ($currentMember instanceof Participator && $currentRequest = $currentMember->CourseRequests()->find('CourseID', $this->ID))
				return $currentRequest;
		}
		return false;
	}
	
	public function getHasSignedUp() {
		if ($currentMember = CourseBookingExtension::currentUser()) {
			if ($currentMember instanceof Participator && $currentMember->CourseRequests()->find('CourseID', $this->ID))
				return true;
		}		
		
		if (strlen(Session::get('GhostHash')) == 0)
			return false;
		
		if ($this->GhostParticipators()->Count() == 0)
			return false;
		
		$ghost = DataObject::get_one('GhostParticipator', 'Hash = \'' . Session::get('GhostHash') . '\'');
		
		if ($ghost && $ghost->Alive && $ghost->Courses()->containsIDs(array($this->ID)))
			return true;
		
		return false;
	}
	
	public function getCanSignUp() {
		$signupStart = strtotime($this->SignupStart);
		$signupEnd = strtotime($this->SignupEnd);
		$expiresNot = $this->SignupExpiresNot;
		
		$hasSignedUp = $this->getHasSignedUp();
				
		$today = time();
		
		if ($signupStart <= $today && ($signupEnd >= $today || $expiresNot) && !$hasSignedUp && !CourseBookingExtension::isTeacher() && !CourseBookingExtension::isAdmin()) 
			return true;
		
		return false;
	}
	
	public function getIsConfirmedCourse() {
		if ($currentMember = CourseBookingExtension::currentUser()) {
			if ($currentMember instanceof Participator && $currentMember->CourseRequests()->find('CourseID', $this->ID))
				return true;
		}
		return false;
	}
	
	public function getIsNotCanceled() {
		if ($currentMember = CourseBookingExtension::currentUser()) {
			if ($currentMember instanceof Participator && $request = $currentMember->CourseRequests()->find('CourseID', $this->ID)) {
				if ($request->Status == 'Canceled')
					return false;
			}
		}
		return true;
	}
	
	public function getCanCancel() {
		if ($currentMember = CourseBookingExtension::currentUser()) {
			if ($currentMember instanceof Participator && $request = $currentMember->CourseRequests()->find('CourseID', $this->ID)) {
				if ($request->Status == 'Notified') {
					$query = new SQLQuery();
					$query->select('TimeStart');
					$query->from('CourseDate');
					$query->where('CourseID = ' . $this->ID);
					$query->orderby('TimeStart ASC');
					$query->limit('2');
					
					$result = $query->execute();
					if (!$result->numRecords()) {
						return true;
					}
					
					// If the course has less than 11 lessions, we can only cancel it one week before it starts
					if ($this->TotalLessions <= 10) {
						$firstTime = $result->first();
						$firstTimestamp = strtotime($firstTime['TimeStart']);
						$today = time();
						if ($firstTimestamp > $today) { // In the future 
							$diff = $firstTimestamp - $today;
							if ($diff >= 604800) // Over a week in the future
								return true;
							else
								return false;
						} else
							return false;
					}
				
					// Otherwise we can only cancel if before the second coursedate
					$firstTime = $result->first();
					if ($result->numRecords() > 1) {
						$secondTime = $result->next();
					}
					else
						$secondTime = $result->first();
					
					$secondTimestamp = strtotime($secondTime['TimeStart']);
					$today = time();
					if ($secondTimestamp > $today) {// In the future
						return true;
					}
					else
						return false; 
				}
			}
		}
		return true;
	}
	
	public function getMainLocationOffice() {
		return $this->getMainLocation()->Short->PostOffice;
	}
	
	public function getMainLocation() {
		$query = new SQLQuery();
		$query->select('ResourceID',
					   'COUNT(ResourceID) AS ResourceIDCount');
		$query->from('CourseDate');
		$query->where('CourseDate.CourseID = ' . $this->ID);
		$query->groupby('ResourceID');
		$query->orderby('ResourceIDCount DESC');
		
		$query_result = $query->execute();
		
		if ($query_result->numRecords()) {
			$first = $query_result->first();
			$resourceID = $first['ResourceID'];
			
			$eBookingCommunicator = new eBookingCommunicator(3600);
			$resource = $eBookingCommunicator->getResourceByID($resourceID);
						
			// First resource
			$resourceShort = new ArrayData(array(
					'Name' => ($resource && $resource->hasField('Name')) ? $resource->Name : '(unknown)',
					'PostAddress' => ($resource && $resource->hasField('PostAddress')) ? $resource->PostAddress : '(unknown)',
					'PostCode' => ($resource && $resource->hasField('PostCode')) ? $resource->PostCode : '(unknown)',
					'PostOffice' => ($resource && $resource->hasField('PostOffice')) ? $resource->PostOffice : '(unknown)',
			));
			
			// All resources we use
			$locations = new DataObjectSet();
			foreach($query_result as $location) {
				$resource = $eBookingCommunicator->getResourceByID($location['ResourceID']);
				$locationArray = new ArrayData(array(
					'Name' => ($resource && $resource->hasField('Name')) ? $resource->Name : '(unknown)',
					'PostAddress' => ($resource && $resource->hasField('PostAddress')) ? $resource->PostAddress : '(unknown)',
					'PostCode' => ($resource && $resource->hasField('PostCode')) ? $resource->PostCode : '(unknown)',
					'PostOffice' => ($resource && $resource->hasField('PostOffice')) ? $resource->PostOffice : '(unknown)',
				));				
				
				$locations->push($locationArray);
			}
			
			if ($locations->Count() == 0) {
				$locations->push($resourceShort);
			}
			
			$resourceFull = $locations;
			
			return new ArrayData(array(
				'Short' => $resourceShort,
				'Full' => $resourceFull,
				'ID' => $resourceID
			));
		}
		return new ArrayData(array(
				'Short' => new ArrayData(array('Name' => '', 'PostAddress' => '', 'PostCode' => '', 'PostOffice' => '')),
				'Full' => new DataObjectSet(new ArrayData(array('Name' => '', 'PostAddress' => '', 'PostCode' => '', 'PostOffice' => ''))),
				'ID' => 0
		));
	}

	public function getMainTimes() {
		$query = new SQLQuery();
		$query->select('TimeStart',
					   'TimeEnd',
					   'WEEKDAY(TimeStart) AS TimeStartWeekday',
					   'COUNT(TimeStart) AS WeekdayCount');
		$query->from('CourseDate');
		$query->where('CourseDate.CourseID = ' . $this->ID);
		$query->groupby('TimeStartWeekday');
		$query->orderby('WeekdayCount DESC');
		
		$query_result = $query->execute();
		
		if ($query_result->numRecords()) {
			$first = $query_result->first();
			
			$weekdays = array('' => '');
			$weekdays += array('0' => _t('CourseDate.MONDAY', 'Monday'));
			$weekdays += array('1' => _t('CourseDate.TUESDAY', 'Tuesday'));
			$weekdays += array('2' => _t('CourseDate.WEDNESDAY', 'Wednesday'));
			$weekdays += array('3' => _t('CourseDate.THURSDAY', 'Thursday'));
			$weekdays += array('4' => _t('CourseDate.FRIDAY', 'Friday'));
			$weekdays += array('5' => _t('CourseDate.SATURDAY', 'Saturday'));
			$weekdays += array('6' => _t('CourseDate.SUNDAY', 'Sunday'));			
						
			$weekdayIndex = $first['TimeStartWeekday'];
			$timeShortname = $weekdays[$weekdayIndex] . ' ' . date('H:i', strtotime($first['TimeStart'])) . '-' . date('H:i', strtotime($first['TimeEnd']));
						
			$times = array();
			foreach ($query_result as $time) {
				$weekdayIndex = $time['TimeStartWeekday'];
				$timeName = $weekdays[$weekdayIndex] . ' ' . date('H:i', strtotime($time['TimeStart'])) . '-' . date('H:i', strtotime($time['TimeEnd']));
				$times[] = $timeName;
			}
			if (count($times)) {	
				$timeFullname = implode(', ', $times);
			} 
			else {
				$timeFullname = $timeShortname;
			}
			
			return new ArrayData(array(
				'Short' => $timeShortname,
				'Full' => $timeFullname
			));
		}
		return new ArrayData(array(
				'Short' => '',
				'Full' => ''
		));
	}	
	
	static function toDropdownList() {
		$objects = DataObject::get('Course', '', 'CourseCode ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) 
				$list[$obj->ID] = $obj->CourseCode . ' ' . $obj->Name;
		
		return $list;
	}
	
}
?>