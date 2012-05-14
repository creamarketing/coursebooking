<?php

class CourseUnit extends DataObject {
	
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'CreaDefaultSelectable',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Name' => 'Varchar(150)',
		'BillingName' => 'Text',
		'BillingText' => 'Text',
		'BillingPostAddress' => 'Varchar',
		'BillingPostCode' => 'Int',
		'BillingPostOffice' => 'Varchar',
		'Phone' => 'Varchar',
		'BusinessID' => 'Varchar',
		'LastInvoiceNumber' => 'Int',
		'LastReferenceNumber' => 'Int',
		'PaymentDays' => 'Int',
		'ReminderPaymentDays' => 'Int',
		'PenaltyInterest' => 'Decimal(5,2)',
		'ReminderFee' => 'Decimal(6,2)',
		'InvoiceFee' => 'Decimal(6,2)',
		'CancellationFee' => 'Decimal(6,2)',
		'CancellationDays' => 'Int',
		'ReferenceStart' => 'Int',
		'ReferenceLength' => 'Int',
		'CountyNumber' => 'Int'
	);
	
	static $translatableFields = array(
		'Name',
		'BillingText'
	);

	static $has_many = array(
		'Courses' => 'Course',
		'BankAccounts' => 'BankAccount',
		'CourseRequestInvoices' => 'CourseRequestInvoice'
	);
	
	static $default_sort = 'Name ASC';
	
	static $defaults = array(
		'LastInvoiceNumber' => 0,
		'LastReferenceNumber' => 0,
		'PaymentDays' => 14,
		'ReminderPaymentDays' => 7,
		'PenaltyInterest' => 0.0,
		'ReminderFee' => 0.0,
		'InvoiceFee' => 0.0,
		'ReferenceStart' => 69,
		'ReferenceLength' => 14,
		'CancellationFee' => 10,
		'CancellationDays' => 7
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
		Requirements::css('coursebooking/css/CourseUnit.css');
		
		$this->extend('getRequirementsForPopup');
	}		
	
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		$mainTab->push(new TextField('Name', _t('CourseUnit.NAME', 'Course unit')));
		
		if ($includeDOMS)
		{
			$tabSet->push($billingTab = new Tab('BillingTab', _t('CourseUnit.BILLING', 'Billing')));
			$tabSet->push($accountsTab = new Tab('AccountsTab', _t('BankAccount.PLURALNAME', 'Bank accounts')));
			$tabSet->push($coursesTab = new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses')));

			$billingTab->push(new TextareaField('BillingName', _t('CourseUnit.BILLINGNAME', 'Recipient name')));
			$billingTab->push($group1 = new FieldGroup(
				new TextField('BillingPostAddress', _t('CourseUnit.POSTADDRESS', 'Post address')),
				$postCode = new TextField('BillingPostCode', _t('CourseUnit.POSTCODE', 'Post code')),	
				new TextField('BillingPostOffice', _t('CourseUnit.POSTOFFICE', 'Post office'))
			));
			$billingTab->push($group2 = new FieldGroup(
				new TextField('Phone', _t('CourseUnit.PHONE', 'Phone')),
				new TextField('BusinessID', _t('CourseUnit.BUSINESSID', 'Business ID')),
				new NumericFieldEx('CountyNumber', _t('CourseUnit.COUNTYNUMBER', 'County number'))
			));		
			$billingTab->push($group6 = new FieldGroup(
				new NumericFieldEx('PenaltyInterest', _t('CourseUnit.PENALTYINTEREST', 'Penalty interest')),
				new NumericFieldEx('ReminderFee', _t('CourseUnit.REMINDERFEE', 'Reminder fee')),
				new NumericFieldEx('InvoiceFee', _t('CourseUnit.INVOICEFEE', 'Invoice fee'))
			));		
			$billingTab->push($group3 = new FieldGroup(
				new NumericFieldEx('PaymentDays', _t('CourseUnit.PAYMENTDAYS', 'Payment days')),
				new NumericFieldEx('ReminderPaymentDays', _t('CourseUnit.REMINDERPAYMENTDAYS', 'Reminder payment days'))
			));
			$billingTab->push($group4 = new FieldGroup(
				new NumericFieldEx('ReferenceStart', _t('CourseUnit.REFERENCESTART', 'Reference start')),
				new NumericFieldEx('ReferenceLength', _t('CourseUnit.REFERENCELENGTH', 'Reference length'))
			));
			$billingTab->push($group5 = new FieldGroup(
				new NumericFieldEx('CancellationDays', _t('CourseUnit.CANCELLATIONDAYS', 'Cancellation days')),
				new NumericFieldEx('CancellationFee', _t('CourseUnit.CANCELLATIONFEE', 'Cancellation fee'))
			));
			
			$billingTab->push(new TextareaField('BillingText', _t('CourseUnit.BILLINGTEXT', 'Billing text')));
			
			$billingTab->push(new NumericFieldEx('LastInvoiceNumber', _t('CourseUnit.LASTINVOICENUMBER', 'Last invoice number')));
			$billingTab->push(new NumericFieldEx('LastReferenceNumber', _t('CourseUnit.LASTREFERENCENUMBER', 'Last reference number')));
			
			$postCode->setMaxLength(5);
			$group1->addExtraClass('group1');
			$group2->addExtraClass('group2');
			$group3->addExtraClass('group3');
			$group4->addExtraClass('group4');
			$group5->addExtraClass('group5');
			$group6->addExtraClass('group6');
			
			$accountsTab->push($bankAccountsDOM = new DialogHasManyDataObjectManager(
								$this,
								'BankAccounts',
								'BankAccount',
								array(
									'Name' => _t('BankAccount.NAME', 'Name'),
									'AccountNumber' => _t('BankAccount.ACCOUNTNUMBER', 'Account number'),
									'IBAN' => _t('BankAccount.IBAN', 'IBAN'),
									'BIC' => _t('BankAccount.BIC', 'BIC')
								),
								null,
								"(CourseUnitID = {$this->ID} OR CourseUnitID = 0)"
			));			
			
			$coursesTab->push($coursesDOM = new DialogHasManyDataObjectManager(
								$this, 
								'Courses', 
								'Course', 
		  						array( 
									'CourseCode' => _t('Course.COURSECODE', 'Course code'),
									'NameList' => _t('Course.NAME', 'Name')
								),
								null,
								"(CourseUnitID = {$this->ID} OR CourseUnitID = 0)"
			));
		}		
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	

	public function getDOMTitle() {
		return $this->Name;
	}		
	
	static function toDropdownList() {
		$objects = DataObject::get('CourseUnit', '', 'Name ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				$list[$obj->ID] = $obj->Name;
			}	
		
		return $list;
	}	
}
?>