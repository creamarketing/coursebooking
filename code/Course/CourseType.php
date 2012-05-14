<?php

class CourseType extends DataObject {
	
	static $extensions = array(
		'CreaDataObjectExtension',
		'CreaDefaultSelectable',
		'TranslatableDataObject',
		'PermissionExtension'	
	);
	
	static $translatableFields = array(
		'Name'
	);	
	
	static $db = array(
		'Code' => 'Varchar(20)',
		'Name' => 'Varchar(255)',
		'HasGovernmentFunding' => 'Boolean'
	);
	
	static $has_many = array(
		'Courses' => 'Course'
	);		
	
	static $defaults = array(
	
	);
	
	static $summary_fields = array(
		'Code',
		'Name',
		'HasGovernmentFunding'
	);
	
	static $default_sort = 'Code ASC';
	
	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
	    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	function getRequirementsForPopup() {	
		//Requirements::css('coursebooking/css/CourseRequestDialog.css');
		$this->extend('getRequirementsForPopup');
	}		
	
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		$mainTab->push(new TextField('Code', _t('CourseType.CODE', 'Code')));
		$mainTab->push(new TextField('Name', _t('CourseType.DESC', 'Name')));
		$mainTab->push(new CheckboxField('HasGovernmentFunding', _t('CourseType.HASGOVERNMENTFUNDING', 'Has government funding')));

		if ($includeDOMS)
		{
			/*$tabSet->push($coursesTab = new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses')));
			$coursesTab->push($coursesDOM = new DialogHasManyDataObjectManager(
								$this, 
								'Courses', 
								'Course', 
		  						array( 
									'CourseCode' => _t('Course.COURSECODE', 'Course code'),
									'NameList' => _t('Course.NAME', 'Name')
								)
			));*/
		}		
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	
	
	public function getHasHoursNice() {
		if ($this->HasHours) 
			return _t('CourseDate.CANCELLED_YES', 'Yes');
		
		return _t('CourseDate.CANCELLED_NO', 'No');
	}
	
	static function toDropdownList() {
		$objects = DataObject::get('CourseType', '', 'Code ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				$list[$obj->ID] = $obj->Code . ' ' . $obj->Name;
			}	
		
		return $list;
	}		
	
	public function getDOMTitle() {
		return $this->Name;
	}		
}
?>