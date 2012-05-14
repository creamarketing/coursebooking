<?php

class Term extends DataObject {
	
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Name' => 'Varchar(150)',		
		'DateStart' => 'Date',		
		'DateEnd' => 'Date',
		'SignupStart' => 'SS_Datetime',
		'SignupEnd' => 'SS_Datetime',
		'SignupExpiresNot' => 'Boolean',
		'Active' => 'Boolean',
	);
	
	static $translatableFields = array(
		'Name'
	);

	static $has_many = array(
		'Courses' => 'Course',
		'BlockedDates' => 'BlockedTermDate'
	);
	
	static $defaults = array(
		'SignupExpiresNot' => true
	);
		
	static $summary_fields = array(
		'Name',							
		'DateStart',							
		'DateEnd',
		'Active',
		'SignupStart',
		'SignupEndNice' => 'SignupEndNice'
	);	
	
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
			Requirements::javascript('coursebooking/javascript/AddTermDialog.js');	
		} 
		else {
			Requirements::javascript('coursebooking/javascript/EditTermDialog.js');
		}
		
		$this->extend('getRequirementsForPopup');
	}
  
	function getCMSFieldsForPopup() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}
	
	function getCMSFields($includeDOMS = true) {		
		$fields = new FieldSet();
			
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),		
			new TextField('Name', _t('Term.NAME', 'Term name')),
			$grp = new FieldGroup(
				$df_start = new DateField('DateStart', _t('Term.DATESTART', 'Term start')),
				$df_end = new DateField('DateEnd', _t('Term.DATEEND', 'Term end'))
			),
			$signup_start = new DatetimeField('SignupStart', _t('Term.SIGNUPSTART', 'Signup starts')),
			$signup_end = new DatetimeField('SignupEnd', _t('Term.SIGNUPEND', 'Signup ends')),
			new FieldGroup(new CheckboxField('SignupExpiresNot', _t('CourseDate.SIGNUPEND_CHECKBOX', 'Signup time never expires'))),
			new FieldGroup($checkbox = new CheckboxField('Active', _t('Term.ACTIVE', 'Active')))
		);
		
		$df_start->setConfig('dateformat', 'dd.MM.YYYY');
		$df_start->setConfig('showcalendar', 'true');
		
		$df_end->setConfig('dateformat', 'dd.MM.YYYY');
		$df_end->setConfig('showcalendar', 'true');

		// Hack when datefields are inside fieldgroup
		$grp->addExtraClass("date {'showcalendar':true,'dateFormat':'dd.mm.yy'}");
		
		$signup_start->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$signup_start->getDateField()->setConfig('showcalendar', 'true');
		$signup_start->getTimeField()->setConfig('timeformat', 'HH.mm');

		$signup_end->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$signup_end->getDateField()->setConfig('showcalendar', 'true');
		$signup_end->getTimeField()->setConfig('timeformat', 'HH.mm');
			
		if ($includeDOMS) {
			$fields->push(
				new DialogTabSet('TabSet',
					$tabGeneral,
					new Tab('BlockedTermDates', _t('Term.BLOCKED_DATES', 'Blocked dates'),
						$blockedDOM = new DialogHasManyDataObjectManager(
							$this,
							'BlockedDates',
							'BlockedTermDate',
							null,
							null,
							"(TermID = {$this->ID} OR TermID = 0)"
						)
					),
					new Tab('Courses', _t('Course.PLURALNAME', 'Courses'),
						$courseDOM = new DialogHasManyDataObjectManager(
							$this, 
							'Courses', 
							'Course', 
							array( 
								'CourseCode' => _t('Course.COURSECODE', 'Course code'),
								'NameList' => _t('Course.NAME', 'Name')
							),
							null,
							"(TermID = {$this->ID} OR TermID = 0)"
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

		$this->extend('updateCMSFields', $fields);
		return $fields;
	}	
	
	
	public function getActiveNice() {
		if ($this->Active)
				return _t('Term.ACTIVE_YES', 'Yes');
		
		return _t('Term.ACTIVE_NO', 'No');
	}
	
	public function getSignupStartNice() {
		return date('d.m.Y H:i', strtotime($this->SignupStart));
	}
	
	public function getSignupEndNice() {
		if ($this->SignupExpiresNot == true)
			return _t('Term.SIGNUPEND_NOEND', 'Non-stop');
		else
			return date('d.m.Y H:i', strtotime($this->SignupEnd));
	}
	
	public function getDateStartNice() {
		return date('d.m.Y', strtotime($this->DateStart));
	}	
	
	public function getDateEndNice() {
		return date('d.m.Y', strtotime($this->DateEnd));
	}
	
	public function getDOMTitle() {
		return $this->Name;
	}		
}
?>