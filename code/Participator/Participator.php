<?php

class Participator extends Member {
	
	static $extensions = array(
		//'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		//'Firstname' => 'Varchar(150)',
		//'Surname' => 'Varchar(150)',
		'PersonalNumber' => 'Varchar(20)',		
		'Gender' => "Enum('Male, Female', 'Male')",
		'NativeLanguage' => "Enum('Swedish, Finnish, English, Other', 'Swedish')",
		'PostAddress' => 'Varchar(255)',
		'PostCode' => 'Int',
		'PostOffice' => 'Varchar(100)',
		'Phone' => 'Varchar(50)',
		//'Email' => 'Varchar(255)',
		'Profession' => 'Varchar(50)',
		'Education' => "Enum('Basic, Low, High, Other', 'Other')",
		'Occupation' => "Enum('Employed, Unemployed, Student, Retired, Other', 'Other')",
		'NoteParticipator' => 'Varchar(255)',
		'NoteAdmin' => 'Varchar(255)',
		'RegistrationMethod' => "Enum('External, Internal', 'Internal')"
	);
	
	static $translatableFields = array(
		
	);
	
	static $has_one = array(

	);
	
	static $has_many = array(
		'CourseRequests' => 'CourseRequest'
	);
	
	static $defaults = array(
		'RegistrationMethod' => 'Internal'
	);

	static $summary_fields = array(
		'Surname',
		'FirstName',
		'Phone',
		'Email'		
	);
	
	static $default_sort = 'Surname ASC, FirstName ASC';
	
	protected $sendAccountInfo = false;
	protected $plainPassword = '';
	
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
	
	function Courses() {
		$courses = DataObject::get('Course', "CourseRequest.ParticipatorID = {$this->ID} AND (CourseRequest.Status = 'Notified' OR CourseRequest.Status = 'Completed')", null, "LEFT JOIN CourseRequest ON CourseRequest.CourseID = Course.ID");
		if ($courses)
			return $courses;
		return new DataObjectSet();
	}
	
	function getRequirementsForPopup() {	
		Requirements::css('coursebooking/css/ParticipatorDialog.css');
		Requirements::javascript('coursebooking/javascript/Account.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');		
		$this->extend('getRequirementsForPopup');
	}
	
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet();
		
		$gender_array = singleton('Participator')->dbObject('Gender')->enumValues();
		// Add translations for gender
		foreach($gender_array as $key => &$value)
			$value = _t('Participator.GENDER_' . strtoupper($value), $value);
		
		$natlang_array = singleton('Participator')->dbObject('NativeLanguage')->enumValues();
		// Add translations for native languages
		foreach($natlang_array as $key => &$value)
			$value = _t('Participator.NATIVELANGUAGE_' . strtoupper($value), $value);		

		$education_array = singleton('Participator')->dbObject('Education')->enumValues();
		// Add translations for educations
		foreach($education_array as $key => &$value)
			$value = _t('Participator.EDUCATION_' . strtoupper($value), $value);

		$occupation_array = singleton('Participator')->dbObject('Occupation')->enumValues();
		// Add translations for occupations
		foreach($occupation_array as $key => &$value)
			$value = _t('Participator.OCCUPATION_' . strtoupper($value), $value);

		$regmethod_array = singleton('Participator')->dbObject('RegistrationMethod')->enumValues();
		// Add translations for occupations
		foreach($regmethod_array as $key => &$value)
			$value = _t('Participator.REGISTRATIONMETHOD_' . strtoupper($value), $value);
		
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),
			new FieldGroup(
				new TextField('FirstName', _t('Participator.FIRSTNAME', 'FirstName')),
				new TextField('Surname', _t('Participator.SURNAME', 'Surname'))
			),
			new FieldGroup(
				$personNumber = new TextField('PersonalNumber', _t('Participator.PERSONALNUMBER', 'Personal number')),
				new DropdownField('Gender', _t('Participator.GENDER', 'Gender'), $gender_array),
				new DropdownField('NativeLanguage', _t('Participator.NATIVELANGUAGE', 'Native language'), $natlang_array)
			),
			new FieldGroup(
				new TextField('PostAddress', _t('Participator.POSTADDRESS', 'Post address')),
				$postCodeField = new NumericField('PostCode', _t('Participator.POSTCODE', 'Post code')),	
				new TextField('PostOffice', _t('Participator.POSTOFFICE', 'Post office'))
			),
			new FieldGroup(
				new TextField('Phone', _t('Participator.PHONE', 'Phone')),
				new EmailField('Email', _t('Participator.EMAIL', 'Email'))),
			new TextField('Profession', _t('Participator.PROFESSION', 'Profession')),
			new DropdownField('Education', _t('Participator.EDUCATION', 'Education'), $education_array),
			new DropdownField('Occupation', _t('Participator.OCCUPATION', 'Occupation'), $occupation_array),
			new TextField('NoteParticipator', _t('Participator.NOTE_PARTICIPATOR', 'Note (from participator)')),
			new TextField('NoteAdmin', _t('Participator.NOTE_ADMIN', 'Note (from admin)')),
			new DropdownField('RegistrationMethod', _t('Participator.REGISTRATIONMETHOD', 'Registration method'), $regmethod_array)
		);
		
		$postCodeField->setMaxLength(5);
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
					new Tab('RequestsTab', _t('CourseRequest.PLURALNAME', 'Requests'),
						$requestsDOM = new DialogHasManyDataObjectManager(
							$this, 
							'CourseRequests', 
							'CourseRequest',
							array( 
								//'ParticipatorName' => _t('Participator.NAME', 'Participator name'),
								'CourseName' => _t('Course.NAME', 'Course name'),
								'StatusNice' => _t('CourseRequest.STATUS', 'Course status'),
								'CreatedNice' => _t('CourseRequest.CREATED', 'Request created')
							),
							null,
							"(ParticipatorID = {$this->ID} OR ParticipatorID = 0)"
						)
					),						
					new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses'),
						$courseDOM = new DialogDataObjectManager(
							$this, 
							'Courses', 
							'Course', 
							array( 
								'CourseCode' => _t('Course.COURSECODE', 'Course code'),
								'NameList' => _t('Course.NAME', 'Course name')							
							),
							null,
							"CourseRequest.ParticipatorID = {$this->ID} AND (CourseRequest.Status = 'Notified' OR CourseRequest.Status = 'Completed')",
							null,
							"LEFT JOIN CourseRequest ON CourseRequest.CourseID = Course.ID"
						)
					)
				)
			);
			
			//$requestsDOM->setPermissions(array());
			
			if ($this->ID) {
				$courseDOM->removePermission('add');
				$courseDOM->removePermission('delete');
				$courseDOM->removePermission('duplicate');
			} else {
				$fields->removeByName('CoursesTab');
				$fields->removeByName('RequestsTab');
			}
		} else {
			$fields->push(
				new DialogTabSet('TabSet',
					$tabGeneral			
				)
			);
		}	
		
		if ($this->isDOMAddForm("Participators")) 
			$fields->push(new HiddenField('closeAfterAdd', '', 'true'));
		
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
	
	public function onBeforeWrite() {
		if ((!$this->ID && $this->Password) || $this->isChanged('Password'))
			$this->plainPassword = $this->Password;
		
		if ($this->ID == 0) {
			$this->sendAccountInfo = true;
		}
		
		parent::onBeforeWrite();
	}
	
	public function onAfterWrite() {
		parent::onAfterWrite();
		if (!$this->inGroup('participators')) {
			$memberGroup = DataObject::get_one('Group', "Code = 'participators'");
			if (!$memberGroup) {
				$memberGroup = new Group();
				$memberGroup->Code = 'participators';
				$memberGroup->Title = 'Participators';
				$memberGroup->write();
			}
			$memberGroup->Members()->add($this);
		}
		
		if ($this->sendAccountInfo && !empty($this->Email)) {
			// Only send email if we are live
			if(Director::isLive() || Email::mailer() instanceof TestMailer) {
				$this->sendSignupEmail();
			}
			$this->sendAccountInfo = false;
		}
	}	
	
	public function sendSignupEmail() {
		$password = $this->Password;
		if ($this->isChanged('Password')) {
			$password = $this->plainPassword;
		} else {
			$password = '**********';
		}

		$e = new Participator_SignupEmail();
		$e->populateTemplate(array(
			'FirstName' => $this->FirstName,
			'Surname' => $this->Surname,
			'Phone' => $this->Phone,
			'PostCode' => $this->PostCode,
			'PostAddress' => $this->PostAddress,
			'PostOffice' => $this->PostOffice,
			'Email' => $this->Email,
			'Password' => $password
		));
		$e->setTo($this->Email);
		$e->send();		
	}
	
	public function onBeforeDelete() {
		parent::onBeforeDelete();
		
		// Remove us from all courses
		if ($this->Courses()->Count() > 0) {
			foreach ($this->Courses() as $course) {
				$course->Participators()->remove($this->ID);
			}
		}
		
		// Remove all requests we have made
		if ($this->CourseRequests()->Count() > 0) {
			foreach ($this->CourseRequests() as $request) {
				$request->delete();
			}
		}
	}
	
	function validate() {
		
		$requiredFields = array(
			'FirstName' => 'Participator.FIRSTNAME',
			'Surname' => 'Participator.SURNAME',
			'PersonalNumber' => 'Participator.PERSONALNUMBER',
			'Email' => 'Participator.EMAIL',
			'Password' => 'Member.PASSWORD'
		);
		
		foreach ($requiredFields as $key => $value) {
			if (isset($_POST[$key]) && is_string($_POST[$key]) && strlen($_POST[$key]) < 1) {
				return new ValidationResult(false, sprintf(_t('DialogDataObjectManager.FILLOUT', 'Please fill out %s'), _t($value, $value)));
			}
		}
		
		return parent::validate();
	}
	
	public function getDOMTitle() {
		return $this->Name;
	}	

	public function getNiceGender() {
		$value = $this->Gender;
		return _t('Participator.GENDER_' . strtoupper($value), $value);
	}
	
	public function getNiceNativeLanguage() {
		$value = $this->NativeLanguage;
		return _t('Participator.NATIVELANGUAGE_' . strtoupper($value), $value);
	}
	
	public function getNiceEducation() {
		$value = $this->Education;
		return _t('Participator.EDUCATION_' . strtoupper($value), $value);
	}
	
	public function getNiceOccupation() {
		$value = $this->Occupation;
		return _t('Participator.OCCUPATION_' . strtoupper($value), $value);
	}
	
	public function getNiceRegistrationMethod() {
		$value = $this->RegistrationMethod;
		return _t('Participator.REGISTRATIONMETHOD_' . strtoupper($value), $value);
	}
	
	function getCode() {
		$fn = ucfirst($this->FirstName);
		$ln = ucfirst($this->Surname);
		
		$initials = empty($fn) ? '' : mb_substr($fn, 0, 1);
		$initials .= empty($ln) ? '' : mb_substr($ln, 0, 2);
		return $initials;
	}	
	
	static function toDropdownList() {
		$objects = DataObject::get('Participator');
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
}
?>