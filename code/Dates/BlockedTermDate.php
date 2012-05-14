<?php

class BlockedTermDate extends DataObject {
	
	static $extensions = array(
		//'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'TimeStart' => 'Datetime',		
		'TimeEnd' => 'Datetime',
		'Reason' => 'Varchar(255)'
	);
	
	static $translatableFields = array(	
	);

	static $has_one = array(
		'Term'	=> 'Term'
	);
	
	static $defaults = array(
	
	);
	
	static $summary_fields = array(
		'TimeStartNice',
		'TimeEndNice',
		'Reason'
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
		$this->extend('getRequirementsForPopup');
	}		
	
	function getCMSFields() {	
	
		$fields = new FieldSet();
	
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),			
			$dts = new DatetimeFieldEx('TimeStart', _t('BlockedTermDate.TIMESTART', 'Start')),
			$dte = new DatetimeFieldEx('TimeEnd', _t('BlockedTermDate.TIMEEND', 'End')),
			$reason = new TextField('Reason', _t('BlockedTermDate.REASON', 'Reason'))
		);
		
		$dte->getTimeField()->setValue('23.59');
		
		$fields->push(
			new DialogTabSet('TabSet',
				$tabGeneral					
			)
		);
		
		$this->extend('updateCMSFields', $fields);
		return $fields;
	}	
	
	public function getTimeStartNice() {
		return date('d.m.Y H:i', strtotime($this->getField("TimeStart")));
	}
	
	public function getTimeEndNice() {
		return date('d.m.Y H:i', strtotime($this->getField("TimeEnd")));
	}	
}

?>