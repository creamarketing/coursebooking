<?php

class TeacherSalaryClass extends DataObject {
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'CreaDefaultSelectable',
		'PermissionExtension'	
	);
	
	static $translatableFields = array(
		'Name',
		'Note'
	);
	
	static $db = array(
		'Name' => 'Varchar(100)',
		'ActiveFrom' => 'SS_Datetime',
		'SalaryHour' => 'Decimal(5,2)',
		'Note' => 'Varchar(255)'
	);
	
	static $summary_fields = array(
		'Name',
		'ActiveFrom',	
		'SalaryHour',
		'Note'		
	);
	
	static $has_many = array(
		'Teachers' => 'Teacher',
		'CourseDateLinks' => 'CourseDateLink'
	);
	
	static $defaults = array(
		'SalaryHour' => '0.00'
	);
	
	static $default_sort = 'Name ASC, ActiveFrom ASC';

	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		$mainTab->push(new TextField('Name', _t('TeacherSalaryClass.NAME', 'Name')));
		$mainTab->push($activeFrom = new DatetimeField('ActiveFrom', _t('TeacherSalaryClass.ACTIVEFROM', 'Active from')));
		$mainTab->push($salaryHour = new NumericFieldEx('SalaryHour', _t('TeacherSalaryClass.SALARYHOUR', 'Salary per lession')));
		$mainTab->push(new TextField('Note', _t('TeacherSalaryClass.NOTE', 'comment')));
		
		$activeFrom->getDateField()->setConfig('dateformat', 'dd.MM.YYYY');
		$activeFrom->getTimeField()->setConfig('timeformat', 'HH:mm');
		$activeFrom->getDateField()->setConfig('showcalendar', 'true');

		$salaryHour->setMaxLength(6);
		
		if ($includeDOMS)
		{
			/*$tabSet->push($teachersTab = new Tab('TeachersTab', _t('Teacher.PLURALNAME', 'Teachers')));
			$teachersTab->push($teachersDOM = new DialogHasManyDataObjectManager(
								$this, 
								'Teachers',
								'Teacher', 
		  						array( 
									'Code' => _t('Teacher.CODE', 'Code'),
									'Surname' => _t('Teacher.SURNAME', 'Surname'),
									'Firstname' => _t('Teacher.FIRSTNAME', 'Firstname')
								)
			));*/
			
			/*if ($this->ID) {
				$teachersDOM->setOnlyRelated(true);
			}*/
		}		
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	
	
	
	static function toDropdownList() {
		$objects = DataObject::get('TeacherSalaryClass', '', 'Name ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				$list[$obj->ID] = $obj->Name . ' ' . $obj->SalaryHour . '€';
			}	
		
		return $list;
	}	
	
	public function getDOMTitle() {
		return $this->Name;
	}		
}

?>
