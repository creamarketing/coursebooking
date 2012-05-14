<?php

class BankAccount extends DataObject {
	static $extensions = array(
		'CreaDataObjectExtension',
		'TranslatableDataObject',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Name' => 'Varchar',
		'AccountNumber' => 'Varchar',
		'IBAN' => 'Varchar',
		'BIC' => 'Varchar'
	);
	
	static $translatableFields = array(
		'Name'
	);
	
	static $has_one = array(
		'CourseUnit' => 'CourseUnit'
	);
	
	function getRequirementsForPopup() {	
		$this->extend('getRequirementsForPopup');
	}		
	
	function getCMSFields() {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		$mainTab->push(new TextField('Name', _t('BankAccount.NAME', 'Name')));
		$mainTab->push(new TextField('AccountNumber', _t('BankAccount.ACCOUNTNUMBER', 'Account number')));
		$mainTab->push(new TextField('IBAN', _t('BankAccount.IBAN', 'IBAN')));
		$mainTab->push(new TextField('BIC', _t('BankAccount.BIC', 'BIC')));
				
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
}

?>
