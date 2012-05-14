<?php

class InvoiceRow extends DataObject {
	static $extensions = array(
		'TranslatableDataObject',
		'PermissionExtension'
	);		
	
	static $db = array(
		'Code' => 'Varchar',
		'Name' => 'Varchar',
		'Amount' => 'Decimal(10,2)',
		'AmountUnit' => 'Varchar(10)',
		'UnitPrice' => 'Decimal(10,2)',
		'DiscountAmount' => 'Decimal(5,2)'
	);
	
	static $translatableFields = array(
		'Name',
		'AmountUnit'
	);
	
	static $has_one = array(
		'Invoice' => 'Invoice'
	);
	
	static $defaults = array(
		'AmountUnit' => 'qty'
	);
	
	public function getTotalCost() {
		return number_format($this->Amount * $this->UnitPrice * (1.0 - $this->DiscountAmount / 100.0), 2);
	}
	
	public function getNiceAmount() {
		return $this->Amount . ' ' . $this->AmountUnit;
	}
	
	public function getNiceTotalCost() {
		return $this->getTotalCost() . ' €';
	}	
	
	public function getCMSFields() {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		
		$mainTab->push(new TextField('Code', _t('InvoiceRow.CODE', 'Code')));
		$mainTab->push(new TextField('Name', _t('InvoiceRow.NAME', 'Name')));
		$mainTab->push(new NumericFieldEx('Amount', _t('InvoiceRow.AMOUNT', 'Amount')));
		$mainTab->push(new TextField('AmountUnit', _t('InvoiceRow.AMOUNTUNIT', 'AmountUnit')));
		$mainTab->push(new NumericFieldEx('UnitPrice', _t('InvoiceRow.UNITPRICE', 'Unit price')));
		$mainTab->push(new NumericFieldEx('DiscountAmount', _t('InvoiceRow.DISCOUNTAMOUNT', 'Discount amount') . ' (%)'));
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}
}

?>
