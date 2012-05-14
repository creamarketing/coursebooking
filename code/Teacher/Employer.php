<?php

class Employer extends DataObject {
	
	static $extensions = array(
		'CreaDataObjectExtension',
		'CreaDefaultSelectable',
		'TranslatableDataObject',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Name' => 'Varchar(255)',
		'LaborAgreement' => 'Text',
		'LaborAgreementSigner' => 'Varchar(100)',
		'PostAddress' => 'Varchar(255)',
		'PostCode' => 'Int',
		'PostOffice' => 'Varchar(100)',
		'Phone' => 'Varchar(50)'
	);
	
	static $translatableFields = array(
		'LaborAgreement'
	);	
	
	static $has_many = array(
		'Eemployees' => 'Teacher'
	);
	
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}	
	
	function getRequirementsForPopup() {	
		//Requirements::css('coursebooking/css/CourseRequestDialog.css');
		$this->extend('getRequirementsForPopup');
	}		
	
	function getCMSFields() {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', 
				$mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))
				));
		
		$mainTab->push(new TextField('Name', _t('Employer.NAME', 'Name')));
		$mainTab->push(new TextField('PostAddress', _t('Employer.POSTADDRESS', 'Post address')));
		$mainTab->push(new FieldGroup(
				new TextField('PostCode', _t('Employer.POSTCODE', 'Post code')),	
				new TextField('PostOffice', _t('Employer.POSTOFFICE', 'Post office'))
			));
		$mainTab->push(new TextField('Phone', _t('Employer.PHONE', 'Phone')));
		$mainTab->push(new TextareaField('LaborAgreement', _t('Employer.LABORAGREEMENT', 'Labor agreement')));
		$mainTab->push(new TextField('LaborAgreementSigner', _t('Employer.LABORAGREEMENTSIGNER', 'Labor agreement signer')));
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}
	
	static function toDropdownList() {
		$objects = DataObject::get('Employer', '', 'Name ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				$list[$obj->ID] = $obj->Name;
			}	
		
		return $list;
	}	
}

?>
