<?php

class CourseRequest extends DataObject {
	
	static $extensions = array(
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Status' => "enum('Notified, InQueue, Rejected, Canceled, Completed', 'Notified')",
		'Comment' => 'Varchar(255)',
		'PayedAmount' => 'Decimal(10,2)',
		'PayedDate' => 'Date',
		'Attendance' => 'Int',
		'BillingPostAddress' => 'Varchar(255)',
		'BillingPostCode' => 'Int',
		'BillingPostOffice' => 'Varchar(100)',
		'ConfirmedDate' => 'SS_Datetime',
		'CancellationDate' => 'SS_Datetime'
	);
	
	static $translatableFields = array(	
	);

	static $has_one = array(
		'Participator'	=> 'Participator',
		'Course' => 'Course'
	);
	
	static $has_many = array(
		'Discounts' => 'CourseDiscount',
		'CourseRequestInvoices' => 'CourseRequestInvoice'
	);
	
	static $default_sort = 'Created ASC';
	
	static $defaults = array(
		'Attendance' => 0,
		'PayedAmount' => '0.00'
	);
	
	static $summary_fields = array(
		'Surname',
		'Firstname',
		'CourseName',
		'StatusNice',
		'Created'
	);
	/*
	static $searchable_fields = array(
		'Surname' => 'PartialMatchFilter',
		'Firstname' => 'PartialMatchFilter'
	);	
	*/
	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	function getRequirementsForPopup() {	
		Requirements::css('coursebooking/css/CourseRequestDialog.css');
		
		$this->extend('getRequirementsForPopup');
	}	
	
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		
		$status_array = singleton('CourseRequest')->dbObject('Status')->enumValues();
		// Add translations for status
		foreach($status_array as $key => &$value)
			$value = _t('CourseRequest.STATUS_' . strtoupper($value), $value);		
	
		if ($includeDOMS == true)
		{	
			$mainTab->push(new HeaderField('ParticipatorHeader', _t('Participator.PLURALNAME', 'Participators')));
			$mainTab->push($participatorsDOM = new DialogHasOneDataObjectManager(
					$this, 
					'Participator', 
					'Participator', 
					null
				));

			$mainTab->push(new HeaderField('CourseHeader', _t('Course.PLURALNAME', 'Courses')));
			$mainTab->push($coursesDOM = new DialogHasOneDataObjectManager(
					$this, 
					'Course', 
					'Course', 
					array( 
						'CourseCode' => _t('Course.COURSECODE', 'Course code'),
						'Name' => _t('Course.NAME', 'Course name'),
						'NiceCoursePrice' => _t('Course.PRICE', 'Course price'),
						'ParticipatorsSummary' => _t('Participator.PLURALNAME', 'Participators')
					)
				));
						
			$participatorsDOM->removePermission('delete');
			$participatorsDOM->setHasHeader(false);
			
			$coursesDOM->removePermission('add');
			$coursesDOM->removePermission('duplicate');
			$coursesDOM->removePermission('delete');
			$coursesDOM->setHasHeader(false);
			
			$mainTab->push(new HeaderField('CourseDiscountHeader', _t('CourseDiscount.PLURALNAME', 'Discounts')));
			$mainTab->push($discountsDOM = new DialogHasManyDataObjectManager(
					$this, 
					'Discounts', 
					'CourseDiscount', 
					array(
						'NiceDiscount' => _t('CourseDiscount.SINGULARNAME', 'Discount'),
						'Note' => _t('CourseDiscount.NOTE', 'Note')
					),
					null,
					"(CourseRequestID = 0 OR CourseRequestID = {$this->ID})"
				));
					
			// Invoices
			$tabSet->push($invoicesTab = new Tab('InvoicesTab', _t('Invoice.PLURALNAME', 'Invoices')));
			$invoicesTab->push($invoicesDOM = new DialogHasManyDataObjectManager(
					$this,
					'CourseRequestInvoices',
					'CourseRequestInvoice',
					array(
						'InvoiceNumber' => _t('Invoice.INVOICENUMBER', 'Number'),
						'ReferenceNumber' => _t('Invoice.REFERENCENUMBER', 'Reference number'),
						'NiceStatus' => _t('Invoice.STATUS', 'Status'),
						'NiceType' => _t('Invoice.TYPE', 'Type'),
						'NiceTotalCost' => _t('Invoice.TOTALCOST', 'Total')
					),
					null,
					"(CourseRequestID = 0 OR CourseRequestID = {$this->ID})"
			));
		}
		
		$mainTab->push(new HeaderField('CourseRequestHeader', _t('CourseRequest.SINGULARNAME', 'Course request')));
		$mainTab->push(
			new FieldGroup(
				new DropdownField('Status', _t('CourseRequest.STATUS', 'Status'), $status_array),
				$attendanceField = new NumericField('Attendance', _t('CourseRequest.ATTENDANCE', 'Attendance')),
				new NumericField('PayedAmount', _t('CourseRequest.PAYEDAMOUNT', 'Amount payed') . ' (€)'),
				$payeddate = new DateFieldEx('PayedDate', _t('CourseRequest.PAYEDDATE', 'Payment date'))					
			)
		);
		$mainTab->push(new TextField('Comment', _t('CourseRequest.COMMENT', 'Comment')));
		$mainTab->push(new HeaderField('BillingAddress', _t('CourseRequest.BILLINGADDRESS', 'Billing address')));
		$mainTab->push(new FieldGroup(
						new TextField('BillingPostAddress', _t('Participator.POSTADDRESS', 'Post address')),
						$postCodeField = new NumericField('BillingPostCode', _t('Participator.POSTCODE', 'Post code')),	
						new TextField('BillingPostOffice', _t('Participator.POSTOFFICE', 'Post office'))));
		
		$attendanceField->setMaxLength(3);
		$postCodeField->setMaxLength(5);
		
		$invoicesDOM->setColumnWidths(array(
			'InvoiceNumber' => 15, 
			'ReferenceNumber' => 40,
			'NiceStatus' => 15,
			'NiceType' => 15,
			'NiceTotalCost' => 15
		));
		$invoicesDOM->addPermission('duplicate');
		
		if ($this->isDOMAddForm("Requests")) 
			$fields->push(new HiddenField('closeAfterAdd', '', 'true'));
		
		return $fields;
	}
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	
	
	public function getCourseName() {
		return $this->Course()->NameList;
	}
	
	public function getCourseCode() {
		return $this->Course()->CourseCode;
	}
	
	public function getParticipatorName() {
		return $this->Participator()->Surname . ' ' . $this->Participator()->FirstName;
	}
	
	public function getStatusNice() {
		return _t('CourseRequest.STATUS_' . strtoupper($this->getField("Status")), $this->getField("Status"));
	}
	
	public function getCreatedNice() {
		return date('d.m.Y H:i', strtotime($this->Created));
	}
	
	public function getEditedNice() {
		return date('d.m.Y H:i', strtotime($this->LastEdited));
	}
	
	public function getDiscountCoursePrice() {
		if ($this->CourseID == 0)
			return 0;
		
		$coursePrice = $this->Course()->CoursePrice;
		
		$percentDiscount = 0;
		$currencyDiscount = 0;
		
		foreach ($this->Discounts() as $discount) {
			if ($discount->Type == 'Percent')
				$percentDiscount += $discount->Amount;
			else
				$currencyDiscount += $discount->Amount;
		}
			
		// First apply percent discount
		$discountPercentAmount = $coursePrice * ($percentDiscount / 100.0);
		$coursePrice -= $discountPercentAmount;
		
		// Then currency
		$coursePrice -= $currencyDiscount;
		
		if ($coursePrice < 0)
			$coursePrice = 0;
		
		return $coursePrice;
	}
	
	public function getNiceDiscountCoursePrice() {
		return ($this->getDiscountCoursePrice() . ' €');
	}
	
	protected function updateRequestStatus() {
				
		$status = $this->getField('Status');

		$courseID = $this->Course()->ID;
		$courseParticipators = $this->Course()->Participators()->Count();
		$courseMaxParticipators = $this->Course()->MaxParticipators ? $this->Course()->MaxParticipators : 0;
		
		$changes = $this->getChangedFields(true, 2);
		
		$cancellationDays = $this->Course()->CourseUnit()->CancellationDays;
		$cancellationFee = $this->Course()->CourseUnit()->CancellationFee;
				
		if (isset($changes['Status'])) {
			if ($changes['Status']['before'] != 'Notified' && $changes['Status']['after'] == 'Notified') {
				if ($courseParticipators >= $courseMaxParticipators)
					$this->setField('Status', 'InQueue');
				else
					$this->ConfirmedDate = date('Y-m-d H:i:s');
			}
			
			if ($this->ID > 0 && $changes['Status']['before'] == 'Notified' && $changes['Status']['after'] == 'Canceled') {
				$this->CancellationDate = date('Y-m-d H:i:s');
				
				// Course is canceled, apply fees
				$query = new SQLQuery();
				$query->select('TimeStart');
				$query->from('CourseDate');
				$query->where('CourseID = ' . $courseID);
				$query->orderby('TimeStart ASC');
				$query->limit('2');

				$result = $query->execute();

				$cancelFee = 0;
				$today = time();
				
				if ($result->numRecords()) {
					// We can only cancel a course with 10 or less lessions for free, one week before it starts
					if ($this->Course()->TotalLessions <= 10) {
						$firstTime = $result->first();
						$firstTimestamp = strtotime($firstTime['TimeStart']);
						
						if ($firstTimestamp > $today) { // In the future
							$diff = $firstTimestamp - $today;
							if ($diff >= ($cancellationDays*60*60*24)) // In days
								$cancelFee = 0;
							else 
								$cancelFee = 2;
						}
					} // 1 week ahead no fee, before the second coursedate 50% fee, otherwise full courseprice
					else {
						$firstTime = $result->first();
						
						if ($result->numRecords() > 1) {
							$secondTime = $result->next();
						}
						else {
							$secondTime = $result->first();
						}
						
						$firstTimestamp = strtotime($firstTime['TimeStart']);
						$secondTimestamp = strtotime($secondTime['TimeStart']);
						
						if ($firstTimestamp > $today) {// First date is in the future
							$diff = $firstTimestamp - $today;
							if ($diff >= ($cancellationDays*60*60*24)) { // One week
								$cancelFee = 0;
							} else {
								$cancelFee = 1;
							}
						}
						else if ($secondTimestamp > $today) { // Second date is in the future
							$diff = $secondTimestamp - $today;
							if ($diff >= 0) {
								$cancelFee = 1;
							} 
						} 
						else {
							$cancelFee = 2;
						}
					}
				}

				if ($cancelFee == 0) {
					// Apply full discount
					$discount = new CourseDiscount();
					$discount->Amount = 100;
					$discount->Type = 'Percent';
					$discount->CourseRequestID = $this->ID;
					$discount->Note = _t('CourseDiscount.NOCANCELFEE', 'Course canceled, notified in time, no fee');
					$discount->write();
					$this->Discounts()->add($discount->ID);
				}
				else if ($cancelFee == 1) {
					// Apply full discount
					$discount = new CourseDiscount();
					$discount->Amount = 50;
					$discount->Type = 'Percent';
					$discount->CourseRequestID = $this->ID;
					$discount->Note = _t('CourseDiscount.PERCENTCANCELFEE', 'Course canceled, before second coursedate, 50% fee');
					$discount->write();
					$this->Discounts()->add($discount);
					
					$discountedPrice = $this->getDiscountCoursePrice();
					if ($discountedPrice > $cancellationFee) {
						$diff = $discountedPrice - $cancellationFee;
						
						$discount = new CourseDiscount();
						$discount->Amount = $diff;
						$discount->Type = 'Currency';
						$discount->CourseRequestID = $this->ID;
						$discount->Note = sprintf(_t('CourseDiscount.MAXCANCELFEE', 'Course canceled, before second coursedate, max %s fee'), $cancellationFee . '€');
						$discount->write();					
						$this->Discounts()->add($discount);
					}
				}
				else {
					// Apply full discount
					$discount = new CourseDiscount();
					$discount->Amount = 0;
					$discount->Type = 'Currency';
					$discount->CourseRequestID = $this->ID;
					$discount->Note = _t('CourseDiscount.FULLFEE', 'Course canceled too late, full fee');
					$discount->write();					
					$this->Discounts()->add($discount);
				}
			}
		}
	}
	
	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		
		// First write? Fix course and participator ID
		if ($this->ID == 0 && $this->CourseID == 0 && $this->ParticipatorID == 0) {			
			$this->CourseID = (int)(isset($_POST['Course'][0]) ? $_POST['Course'][0] : 0);
			$this->ParticipatorID = (int)(isset($_POST['Participator'][0]) ? $_POST['Participator'][0] : 0);
		}
		
		$this->updateRequestStatus();
	}
	
	protected function validate() {
		$courseID = $this->getField('CourseID');
		$participatorID = $this->getField('ParticipatorID');
		
		if ($this->ID == 0 && $courseID == 0 && $participatorID == 0) {
			$courseID = (int)(isset($_POST['Course'][0]) ? $_POST['Course'][0] : 0);
			$participatorID = (int)(isset($_POST['Participator'][0]) ? $_POST['Participator'][0] : 0);
		}
		
		if ($courseID == 0) 
			return new ValidationResult(false, _t('CourseRequest.ERROR_NOCOURSE', 'You must select a course'));
		elseif ($participatorID == 0)
			return new ValidationResult(false, _t('CourseRequest.ERROR_NOPARTICIPATOR', 'You must select a participator'));
			
		$existing = DataObject::get_one("CourseRequest", "CourseID = $courseID AND ParticipatorID = $participatorID ANd ID != {$this->ID}");
		if ($existing) {
			return new ValidationResult(false, _t('CourseRequest.ERROR_EXISTS', 'The selected participator is already signed up for that course'));
		}
				
		return parent::validate();
	}
	
	public function getSurname() {
		return $this->Participator()->Surname;
	}
	
	public function getFirstname() {
		return $this->Participator()->FirstName;
	}
	
	public function onAfterWrite() {
		parent::onAfterWrite();

		$this->updateInvoice();
		
	}
	
	protected function updateInvoice() {
		$requestInvoice = null;
		$invoices = $this->CourseRequestInvoices();
		if ($invoices) { // Modify only system generated ones
			foreach ($invoices as $invoice) {
				if ($invoice->Type == 'Dynamic') {
					$requestInvoice = $invoice;
					break;
				}
			}
		}
		
		if ($requestInvoice === null) {
			return;
		} 
		
		// Update courseunit
		$requestInvoice->CourseUnitID = $this->Course()->CourseUnitID;
		
		// Update invoice number if empty
		if ($requestInvoice->InvoiceNumber == 0) {
			$requestInvoice->InvoiceNumber = $this->Course()->CourseUnit()->LastInvoiceNumber + 1;
			$this->Course()->CourseUnit()->LastInvoiceNumber = $requestInvoice->InvoiceNumber;
			$this->Course()->CourseUnit()->write();
		}
		
		// Update reference number if empty
		if (empty($requestInvoice->ReferenceNumber)) {
			$refStart = $this->Course()->CourseUnit()->ReferenceStart;
			$refLength = $this->Course()->CourseUnit()->ReferenceLength;
			$lastRef = $this->Course()->CourseUnit()->LastReferenceNumber + 1;
			$requestInvoice->ReferenceNumber = Invoice::generateReferenceNumber(str_pad((int) $lastRef, $refLength-strlen($refStart)-1,"0", STR_PAD_LEFT), str_pad((int) $refStart, $refLength-1,"0", STR_PAD_RIGHT), true);			
			
			$this->Course()->CourseUnit()->LastReferenceNumber = $lastRef;
			$this->Course()->CourseUnit()->write();			
		}
		
		if (empty($requestInvoice->InvoiceDate)) {
			$requestInvoice->InvoiceDate = date('Y-m-d H:i:s');
		}
		
		// Clear old invoice rows
		if ($invoiceRows = $requestInvoice->InvoiceRows()) {
			foreach ($invoiceRows as $invoiceRow)
				$invoiceRow->delete();
		}

		// Add new rows
		$coursePrice = new InvoiceRow();
		$coursePrice->Code = $this->Course()->CourseCode;
		$coursePrice->Amount = 1;		
		$storedLocale = i18n::get_locale();
		foreach (Translatable::get_allowed_locales() as $allowedLocale) {
			i18n::set_locale($allowedLocale);
			$coursePrice->setField('AmountUnit_' . $allowedLocale,  _t('Course.AMOUNTUNIT', 'qty'));	
			$coursePrice->setField('Name_' . $allowedLocale, $this->Course()->Name);
		}
		i18n::set_locale($storedLocale);	
		$coursePrice->UnitPrice = $this->Course()->CoursePrice;
		$coursePrice->DiscountAmount = number_format((1.0 - ($this->getDiscountCoursePrice() / $this->Course()->CoursePrice)) * 100, 2);
		$coursePrice->write();
		
		$requestInvoice->InvoiceRows()->add($coursePrice);
			
		$requestInvoice->write();		
	}
}

?>