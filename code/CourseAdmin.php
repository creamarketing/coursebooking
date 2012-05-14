<?php

class CourseAdmin extends Member {
	static $extensions = array(
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'PersonalNumber' => 'Varchar(20)',
		'Title' => 'Varchar(150)',
		'Gender' => "Enum('Male, Female', 'Male')",
		'NativeLanguage' => "Enum('Swedish, Finnish, English, Other', 'Swedish')",
		'PostAddress' => 'Varchar(255)',
		'PostCode' => 'Int',
		'PostOffice' => 'Varchar(100)',
		'Phone' => 'Varchar(50)',
		'Profession' => 'Varchar(50)',
		'Note' => 'Varchar(255)'
	);
	
	static $translatableFields = array(
		
	);
		
	static $has_many = array(
		'Courses' => 'Course'
	);
		
	static $summary_fields = array(
		'Surname',	
		'FirstName',	
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
		Requirements::css('coursebooking/css/CourseAdminDialog.css');
		Requirements::javascript('coursebooking/javascript/Account.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');
		$this->extend('getRequirementsForPopup');
	}	
	
	function getCMSFields($includeDOMS = true) {		
		$fields = new FieldSet();
		
		$gender_array = singleton('CourseAdmin')->dbObject('Gender')->enumValues();
		// Add translations for gender
		foreach($gender_array as $key => &$value)
			$value = _t('CourseAdmin.GENDER_' . strtoupper($value), $value);
		
		$natlang_array = singleton('CourseAdmin')->dbObject('NativeLanguage')->enumValues();
		// Add translations for native languages
		foreach($natlang_array as $key => &$value)
			$value = _t('CourseAdmin.NATIVELANGUAGE_' . strtoupper($value), $value);		
			
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),
			new FieldGroup(
				new TextField('FirstName', _t('CourseAdmin.FIRSTNAME', 'FirstName')),
				new TextField('Surname', _t('CourseAdmin.SURNAME', 'Surname'))
			),
			new FieldGroup(
				$personNumber = new TextField('PersonalNumber', _t('CourseAdmin.PERSONALNUMBER', 'Personal number')),
				new DropdownField('Gender', _t('CourseAdmin.GENDER', 'Gender'), $gender_array),
				new DropdownField('NativeLanguage', _t('CourseAdmin.NATIVELANGUAGE', 'Native language'), $natlang_array)	
			),
			new FieldGroup(
				new TextField('PostAddress', _t('Teacher.POSTADDRESS', 'Post address')),
				$postCode = new NumericField('PostCode', _t('Teacher.POSTCODE', 'Post code')),	
				new TextField('PostOffice', _t('Teacher.POSTOFFICE', 'Post office'))
			),
			new FieldGroup(
				new TextField('Phone', _t('Teacher.PHONE', 'Phone')),
				new EmailField('Email', _t('Teacher.EMAIL', 'Email'))),
			new FieldGroup(
				new TextField('Title', _t('CourseAdmin.TITLE', 'Title')),
				new TextField('Profession', _t('Teacher.PROFESSION', 'Profession'))
			),				
			new TextField('Note', _t('Teacher.NOTE', 'Note'))
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
					new Tab('Courses', _t('Course.PLURALNAME', 'Courses'),
						$courseDOM = new DialogHasManyDataObjectManager(
							$this, 
							'Courses', 
							'Course', 
							array( 
								'CourseCode' => _t('Course.COURSECODE', 'Course code'),
								'Name' => _t('Course.NAME', 'Course name')							
							)
						)
					)
				)
			);
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
		if (!$this->inGroup('courseadmins')) {
			$memberGroup = DataObject::get_one('Group', "Code = 'courseadmins'");
			if (!$memberGroup) {
				$memberGroup = new Group();
				$memberGroup->Code = 'courseadmins';
				$memberGroup->Title = 'Course administrators';
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
	
	static function toDropdownList() {
		$objects = DataObject::get('CourseAdmin');
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
	
	public function getDOMTitle() {
		return $this->Name;
	}	

}
?>
