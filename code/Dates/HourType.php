<?php

class HourType extends DataObject {
	
	static $extensions = array(
		'CreaDataObjectExtension',
		'TranslatableDataObject',
		'CreaDefaultSelectable',
		'PermissionExtension'	
	);
	
	static $translatableFields = array(
		'Name'
	);	
	
	static $db = array(
		'Code' => 'Varchar(4)',
		'Name' => 'Varchar(255)',
		'HasHours' => 'Boolean'
	);
	
	static $has_many = array(
		'Teachers' => 'Teacher',
		'CourseDateLinks' => 'CourseDateLink'
	);
	
	static $defaults = array(
	
	);
	
	static $summary_fields = array(
		'Code',
		'Name',
		'HasHoursNice'
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
		$mainTab->push(new TextField('Code', _t('HourType.CODE', 'Code')));
		$mainTab->push(new TextField('Name', _t('HourType.DESC', 'Name')));
		$mainTab->push(new CheckboxField('HasHours', _t('HourType.HASHOURS', 'Has hours')));

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
		$objects = DataObject::get('HourType', '', 'Code ASC');
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