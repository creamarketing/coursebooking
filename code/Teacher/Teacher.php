<?php

class Teacher extends Member {
	
	static $extensions = array(
		//'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		//'Code' => 'Varchar(20)',
		//'Firstname' => 'Varchar(150)',
		//'Surname' => 'Varchar(150)',
		'PersonalNumber' => 'Varchar(20)',
		'Title' => 'Varchar(150)',
		'KnowledgeArea' => 'Varchar(255)',
		'Gender' => "Enum('Male, Female', 'Male')",
		'NativeLanguage' => "Enum('Swedish, Finnish, English, Other', 'Swedish')",
		'PostAddress' => 'Varchar(255)',
		'PostCode' => 'Int',
		'PostOffice' => 'Varchar(100)',
		'Phone' => 'Varchar(50)',
		//'Email' => 'Varchar(255)',
		'Profession' => 'Varchar(50)',
		'Note' => 'Varchar(255)',
		'BankAccountNumber' => 'Varchar(255)'
	);
	
	static $translatableFields = array(
		
	);
	
	static $has_one = array(
		'Employer' => 'Employer',
		'DefaultHourType' => 'HourType',
		'DefaultSalaryClass' => 'TeacherSalaryClass'
	);
	
	static $has_many = array(
		'CourseDateLinks' => 'CourseDateLink'
	);
		
	static $summary_fields = array(
		'Surname',	
		'FirstName',	
		'KnowledgeArea',
		'Phone',
		'Email'
	);
	
	static $default_sort = 'Surname ASC, FirstName ASC';
	
	static $defaults = array(
	
	);
	
	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	public function populateDefaults() {
		parent::populateDefaults();
		
		$this->Password = substr(md5('crea' . time()), 0, 8);
	}	
	
	function getRequirementsForPopup() {	
		Requirements::css('coursebooking/css/TeacherDialog.css');
		Requirements::javascript('coursebooking/javascript/Account.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');		
		$this->extend('getRequirementsForPopup');
	}	
	
	function Courses() {
		$courses = DataObject::get('Course', 'Course.ID = CourseDate.CourseID',	null, "LEFT JOIN CourseDateLink ON CourseDateLink.TeacherID = {$this->ID} LEFT JOIN CourseDate ON CourseDate.ID = CourseDateLink.CourseDateID");
		if ($courses)
			return $courses;
		return new DataObjectSet();
	}
	
	public function getNiceGender() {
		$value = $this->Gender;
		return _t('Teacher.GENDER_' . strtoupper($value), $value);
	}
	
	public function getNiceNativeLanguage() {
		$value = $this->NativeLanguage;
		return _t('Teacher.NATIVELANGUAGE_' . strtoupper($value), $value);
	}	
	
	function getTitle() {
		return $this->getField('Title');
	}
	
	function getCode() {
		$fn = ucfirst($this->FirstName);
		$ln = ucfirst($this->Surname);
		
		$initials = empty($fn) ? '' : mb_substr($fn, 0, 1);
		$initials .= empty($ln) ? '' : mb_substr($ln, 0, 2);
		return $initials;
	}
	
	function getCMSFields($includeDOMS = true) {		
		$fields = new FieldSet();
		
		$gender_array = singleton('Teacher')->dbObject('Gender')->enumValues();
		// Add translations for gender
		foreach($gender_array as $key => &$value)
			$value = _t('Teacher.GENDER_' . strtoupper($value), $value);
		
		$natlang_array = singleton('Teacher')->dbObject('NativeLanguage')->enumValues();
		// Add translations for native languages
		foreach($natlang_array as $key => &$value)
			$value = _t('Teacher.NATIVELANGUAGE_' . strtoupper($value), $value);		
	
		$hourtype_array = HourType::toDropdownList();
		$salaryclass_array = TeacherSalaryClass::toDropdownList();
			
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),
			new FieldGroup(
				new TextField('FirstName', _t('Teacher.FIRSTNAME', 'FirstName')),
				new TextField('Surname', _t('Teacher.SURNAME', 'Surname'))
			),
			new FieldGroup(
				$personNumber = new TextField('PersonalNumber', _t('Teacher.PERSONALNUMBER', 'Personal number')),
				new DropdownField('Gender', _t('Teacher.GENDER', 'Gender'), $gender_array),
				new DropdownField('NativeLanguage', _t('Teacher.NATIVELANGUAGE', 'Native language'), $natlang_array)	
			),
			new FieldGroup(
				new TextField('PostAddress', _t('Teacher.POSTADDRESS', 'Post address')),					
				$postCode = new NumericField('PostCode', _t('Teacher.POSTCODE', 'Post code')),	
				new TextField('PostOffice', _t('Teacher.POSTOFFICE', 'Post office'))
			),
			new FieldGroup(
				new TextField('Title', _t('Teacher.TITLE', 'Title')),
				new TextField('Profession', _t('Teacher.PROFESSION', 'Profession'))
			),				
			new TextField('KnowledgeArea', _t('Teacher.KNOWLEDGEAREA', 'Knowledge area')),
			new FieldGroup(
				new TextField('Phone', _t('Teacher.PHONE', 'Phone')),
				new EmailField('Email', _t('Teacher.EMAIL', 'Email'))),
			new TextField('Note', _t('Teacher.NOTE', 'Note')), 
			new TextField('BankAccountNumber', _t('Teacher.BANKACCOUNTNUMBER', 'Bank account number')),
			new DropdownField('DefaultHourTypeID', _t('Teacher.HOURTYPE', 'Hourtype'), $hourtype_array, CreaDefaultSelectable::getDefaultSelected('HourType')),
			new DropdownField('DefaultSalaryClassID', _t('Teacher.SALARYCLASS', 'Salaryclass'), $salaryclass_array, CreaDefaultSelectable::getDefaultSelected('TeacherSalaryClass')),
			new DropdownField('EmployerID', _t('Teacher.EMPLOYER', 'Employer'), Employer::toDropdownList(), CreaDefaultSelectable::getDefaultSelected('Employer'))
		);
		
		$postCode->setMaxLength(5);
		$personNumber->setMaxLength(11);
		
		if ($includeDOMS) {
			$fields->push(
				new DialogTabSet('TabSet',
					$tabGeneral,
					new Tab('UserAccount', _t('Account.USERACCOUNT', 'User account'),
						new FieldGroup(
							new PasswordField('Password', _t('Member.PASSWORD', 'Password')),
							new CheckboxField('LockedPermanent', _t('Account.PERMLOCK', 'Locked'))
						),
						new LiteralField('ResetPassword', '<button type="button" id="ResetPasswordLink">' . _t('Account.PASSWORDRESET', 'Reset and send password') .  '</button><div id="ResetPasswordDialog"></div>'),
						new LiteralField('SendAccountInfo', '<button type="button" id="SendAccountInfo">' . _t('Account.SENDACCOUNTINFO', 'Send account info') .  '</button><div id="SendAccountInfoDialog"></div>')
					),
					new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses'),
						$courseDOM = new DialogDataObjectManager(
							$this, 
							'Courses',
							'Course', 
							array( 
								'CourseCode' => _t('Course.COURSECODE', 'Course code'),
								'Name' => _t('Course.NAME', 'Course name')							
							),
							null,
							"Course.ID = CourseDate.CourseID",
							null,
							"LEFT JOIN CourseDateLink ON CourseDateLink.TeacherID = {$this->ID} LEFT JOIN CourseDate ON CourseDate.ID = CourseDateLink.CourseDateID"
						)
					),
					new Tab('DatesTab', _t('CourseDate.PLURALNAME', 'Dates'),
						$datesDOM = new DialogDataObjectManager(
							$this, 
							'CourseDateLinks', 
							'CourseDateLink',
							array( 
								'CourseCode' => _t('Course.COURSECODE', 'Course code'),
								'CourseName' => _t('Course.NAME', 'Course name'),
								'TimeNice' => _t('CourseDate.TIMENICE', 'Time'),
								'Lessions' => _t('CourseDate.LESSIONS', 'Lessions'),
								'PaymentMonth' => _t('CourseDate.PAYMENTMONTH', 'Payment month')
							),
							null,
							"TeacherID = {$this->ID}"
						)
					)/*,
					new Tab('SalaryClasses', _t('TeacherSalaryClass.PLURALNAME', 'Salary classes'),
						$salaryClassesDOM = new DialogManyManyDataObjectManager(
							$this, 
							'SalaryClasses', 
							'TeacherSalaryClass'
						)
					)*/								
				)
			);
							
			$datesDOM->setPluralTitle(_t('CourseDate.PLURALNAME', 'Course dates'));
			
			if ($this->ID) {
				$courseDOM->removePermission('add');
				$courseDOM->removePermission('duplicate');
				$courseDOM->removePermission('delete');
				
				$datesDOM->removePermission('add');
				
				$datesDOM->setHighlightConditions(array(
					array(
						"rule" => '$CourseDate()->Cancelled == 1',
						"class" => 'coursedate-cancelled'
					),
					array(
						"rule" => '$CourseDate()->Conflicting == 1',
						"class" => 'coursedate-conflicting',
						"exclusive" => true
					)
				));				
			} else {
				$fields->removeByName('CoursesTab');
				$fields->removeByName('DatesTab');
			}
		} else {
			$fields->push(
				new DialogTabSet('TabSet',
					$tabGeneral			
				)
			);
		}	

		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}
	
	public function getName() {
		return $this->FirstName . ' ' . $this->Surname;
	}
	
	function getValidator() {
		return null;
	}	
	
	public function onAfterWrite() {
		parent::onAfterWrite();
		if (!$this->inGroup('teachers')) {
			$memberGroup = DataObject::get_one('Group', "Code = 'teachers'");
			if (!$memberGroup) {
				$memberGroup = new Group();
				$memberGroup->Code = 'teachers';
				$memberGroup->Title = 'Teachers';
				$memberGroup->write();
			}
			$memberGroup->Members()->add($this);
		}
	}		
	
	function validate() {
		
		$requiredFields = array(
			'FirstName' => 'Teacher.FIRSTNAME',
			'Surname' => 'Teacher.SURNAME',
			'PersonalNumber' => 'Teacher.PERSONALNUMBER',
			'Email' => 'Participator.EMAIL',
			'Password' => 'Member.PASSWORD'
		);
		
		foreach ($requiredFields as $key => $value) {
			if (isset($_POST[$key]) && strlen($_POST[$key])  < 1) {
				return new ValidationResult(false, sprintf(_t('DialogDataObjectManager.FILLOUT', 'Please fill out %s'), _t($value, $value)));
			}
		}
		
		return parent::validate();
	}	
	
	public function onBeforeDelete() {
		parent::onBeforeDelete();
				
		// Remove course links
		if ($this->CourseDateLinks()->Count() > 0) {
			foreach ($this->CourseDateLinks() as $courseDateLink) {
				$courseDateLink->delete();
			}		
		}
	}	
	
	static function toDropdownList() {
		$objects = DataObject::get('Teacher');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				if (strlen($obj->Code) > 0)
					$list[$obj->ID] = $obj->Code . ' ' . $obj->Name;
				else
					$list[$obj->ID] = $obj->Name;
			}	
		
		return $list;
	}
	
	public function getDOMTitle() {
		return $this->Name;
	}
}
?>