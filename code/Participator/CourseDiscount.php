<?php

class CourseDiscount extends DataObject {
	static $extensions = array(
		'CreaDataObjectExtension',
		'PermissionExtension'
	);	
	
	static $db = array(
		'Amount' => 'Decimal(10,2)',
		'Type' => "Enum('Percent, Currency', 'Percent')",
		'Note' => 'Varchar(255)'
	);
	
	static $defaults = array(
		'Type' => 'Percent',
		'Amount' => 0
	);	
	
	static $has_one = array(
		'CourseRequest' => 'CourseRequest'
	);
	
	static $summary_fields = array(
		'Amount',
		'Type',
		'Note'
	);	
	
	public function getNiceDiscount() {
		if ($this->Type == 'Percent')
			return ($this->Amount . ' %');
		return ($this->Amount . ' €');
	}
	
	function getCMSFields() {
		$types = singleton('CourseDiscount')->dbObject('Type')->enumValues();
		foreach($types as $key => &$value)
			$value = _t('CourseDiscount.TYPE_' . strtoupper($value), $value);		
		
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		$mainTab->push(new FieldGroup(
				new NumericFieldEx('Amount', _t('CourseDiscount.AMOUNT', 'Amount')),
				new DropdownField('Type', _t('CourseDiscount.TYPE', 'Type'), $types)));
		$mainTab->push(new TextField('Note', _t('CourseDiscount.NOTE', 'Note')));
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}
}

?>
