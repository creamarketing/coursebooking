<?php

class Invoice extends DataObject {
	static $extensions = array(
		'TranslatableDataObject',
		'PermissionExtension'
	);		
	
	static $db = array(
		'ReferenceNumber' => 'Varchar',
		'InvoiceDueDate' => 'Date',
		'InvoiceDate' => 'Date',
		'InvoiceNumber' => 'Int',
		'Status' => "Enum('Draft,Sent,Payed', 'Draft')",
		'Type' => "Enum('Dynamic,Original,Reminder', 'Original')"
	);
	
	static $translatableFields = array(
		
	);
		
	static $has_many = array(
		'InvoiceRows' => 'InvoiceRow'
	);
	
	public function getNiceStatus() {
		return _t('Invoice.STATUS_' . strtoupper($this->Status), $this->Status);
	}
	
	public function getNiceType() {
		return _t('Invoice.TYPE_' . strtoupper($this->Type), $this->Type);
	}	
	
	public function getCMSFields() {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		
		$mainTab->push(new TextField('ReferenceNumber', _t('Invoice.REFERENCENUMBER', 'Reference number')));
		$mainTab->push(new DateFieldEx('InvoiceDate', _t('Invoice.INVOICEDATE', 'Invoice date')));		
		$mainTab->push(new DateFieldEx('InvoiceDueDate', _t('Invoice.INVOICEDUEDATE', 'Invoice due date')));
		$mainTab->push(new NumericFieldEx('InvoiceNumber', _t('Invoice.INVOICENUMBER', 'Invoice number')));
		
		$status_array = singleton('Invoice')->dbObject('Status')->enumValues();
		foreach($status_array as $key => &$value)
			$value = _t('Invoice.STATUS_' . strtoupper($value), $value);				
		
		$type_array = singleton('Invoice')->dbObject('Type')->enumValues();
		foreach($status_array as $key => &$value)
			$value = _t('Invoice.TYPE_' . strtoupper($value), $value);						
		
		$mainTab->push(new DropdownField('Status', _t('Invoice.STATUS', 'Status'), $status_array));
		$mainTab->push(new DropdownField('Type', _t('Invoice.TYPE', 'Type'), $type_array));
		
		$mainTab->push(
			$rowsDOM = new DialogHasManyDataObjectManager(
				$this,
				'InvoiceRows',
				'InvoiceRow',
				array(
					'Code' => _t('InvoiceRow.CODE', 'Code'),
					'Name' => _t('InvoiceRow.NAME', 'Name'),
					'NiceTotalCost' => _t('InvoiceRow.TOTALCOST', 'Total cost')
				),
				null,
				"InvoiceID = 0 OR InvoiceID = {$this->ID}"
			)
		);
				
		$rowsDOM->setColumnWidths(array(
			'Code' => 15,
			'Name' => 70,
			'NiceTotalCost' => 15
		));
				
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}
	
	public function getTotalCost() {
		$rows = $this->InvoiceRows();
		$cost = 0.0;
		
		
		if ($rows) {
			foreach ($rows as $row)
				$cost += $row->TotalCost;
		}
		
		return number_format($cost, 2);
	}
	
	public function getNiceTotalCost() {
		return $this->getTotalCost() . ' €';
	}
	
	protected function onBeforeDelete() {
		parent::onBeforeDelete();

		$invoiceRows = $this->InvoiceRows();
		
		if ($invoiceRows) {
			foreach ($invoiceRows as $invoiceRow) {
				$invoiceRow->delete();
			}
		}
	}
	
	public static function generateReferenceNumber($id,$start="",$checkdigit=true) {
		# finnish refnr
		$number = "".($start + $id).""; 
		#$number = $id; 
		$sum=0; 
		$add="7";

		//$number = $id;
		for ($i=strlen($number)-1;$i>=0;$i--) {
			$sum=($sum+($number[$i]*$add));
			if ($add=="7") $add="3";
			elseif ($add=="3") $add="1";
			elseif ($add=="1") $add="7";
			//echo ($number[$i]*$add)."<br>";
		}

		$diff = (ceil($sum/10)*10)-(int)$sum;
		if ($diff==10) $diff = "0";
		if ($checkdigit) {
			//$number = "$id$diff";					
			$number = "$number$diff";					
		} 

		/*
		else {
			$number = "$id";
		}
		*/

		$formated="";
		$j=0;
		for ($i=strlen($number)-1;$i>=0;$i--) {
			$formated=$number[$i].$formated;
			$j++;
			if ($j=="5") {
				$formated =" ".$formated;	
				$j=0;
			}
		}				
		return $formated;   	      
		# finnish refnr end
		#$root="".($start + $id)."";
		#$root=$id;
		$root=$formated;				
		// RF00 == 271500
		$rf00=271500;

		$number = "".$root.$rf00."";

		$modulo=fmod($number,97);

		$controlNr=98-$modulo;
		$rfNr='RF'.$controlNr."".$root;	

		return $rfNr;
	}
}

?>
