<?php

class CourseDate extends DataObject {
	
	static $extensions = array(
		//'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'TimeStart' => 'Datetime',		
		'TimeEnd' => 'Datetime',
		'Lessions' => 'Decimal(4,2)',
		'BookingID' => 'Int',
		'ResourceID' => 'Int',
		'Conflicting' => 'Boolean',
		'ConflictingReason' => 'Varchar(255)',
	);
	
	static $translatableFields = array(	
	);

	static $has_one = array(
		'Course'	=> 'Course'
	);
	
	static $has_many = array(
		'CourseDateLinks' => 'CourseDateLink'
	);
		
	static $defaults = array(
		'Conflicting' => false,
		'Lessions' => '0.00',
		'ResourceID' => 0,
		'BookingID' => 0
	);
	
	static $summary_fields = array(
		'TimeNice',
		'WeekDayNice',		
		'Lessions',
		'LocationNice',
		'TeacherNice',
		'PaymentMonthNice',
		'CancelledNice',
		'CourseNice'
	);
	
	static $default_sort = 'TimeStart ASC';
	
	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	function getRequirementsForPopup() {	
		Requirements::css('coursebooking/css/CourseDate.css');
		Requirements::javascript('coursebooking/javascript/CourseDate.js');
		
		$this->extend('getRequirementsForPopup');
	}	
	
	function getCMSFields($includeDOMS = true) {	
	
		$fields = new FieldSet();
	
		$courseArray = Course::toDropdownList();
		
		$tabGeneral = new Tab('General', _t('CourseDate.SPECIFIC', 'Specific'),			
			$dts = new DatetimeFieldEx('TimeStart', _t('CourseDate.TIMESTART', 'Course start')),
			$dte = new DatetimeFieldEx('TimeEnd', _t('CourseDate.TIMEEND', 'Course end')),
			$lessions = new NumericFieldEx('Lessions', _t('CourseDate.LESSIONS', 'Lessions')),
			$course = new AdvancedDropdownField("CourseID", _t('Course.SINGULARNAME', 'Course'), $courseArray)
		);
			
		$lessions->setMaxLength(4);
		$eBooking = new eBookingCommunicator();
		
		if ($this->ResourceID == 0) {
			$resource = $eBooking->getResourceByBookingID($this->BookingID);
			
			if ($resource && $resource->hasField('ID'))
				$this->ResourceID = $resource->ID;
		}
							
		$tabGeneral->push(new AdvancedDropdownField('ResourceID', _t('CourseDate.LOCATIONNICE', 'Location'), $eBooking->getResourcesAsDropdownMap()));

		$tabGeneral->push($linkDOM = new DialogHasManyDataObjectManager(
					$this,
					'CourseDateLinks',
					'CourseDateLink',
					array(
						'TeacherNice' => _t('Teacher.SINGULARNAME', 'Teacher'),
						'TeacherHourTypeNice' => _t('HourType.SINGULARNAME', 'Hourtype'),
						'TeacherSalaryClassNice' => _t('TeacherSalaryClass.SINGULARNAME', 'Salaryclass'),
						'Lessions' => _t('CourseDate.LESSIONS', 'Lessions'),
						'PaymentMonth' => _t('CourseDate.PAYMENTMONTH', 'Payment month'),
						'NiceLocked' => _t('CourseDateLink.LOCKED', 'Locked')
					),
					null,
					"(CourseDateID = 0 OR CourseDateID = {$this->ID})"
				));
		
		
		if ($this->Conflicting) {
			$tabGeneral->push(new LiteralField('ConflictingReason', '<span style="color: red">Konflikt med ' . $this->ConflictingReason . '</span>'));
		}
		
	
		$tabGeneral->push(new LiteralField('BottomSpacer', '<div style="height: 100px">&nbsp;</div>'));
				
		$fields->push(
			new DialogTabSet('TabSet',
				$tabGeneral
			)
		);
		
		if ($this->isDOMAddForm("CourseDate") || $this->isDOMAddForm("CourseDates")) {
			$fields->push(new HiddenField('closeAfterAdd', '', 'true'));
		}	
		
		$this->extend('updateCMSFields', $fields);
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	
		
	public function getWeekDay() {
		return date('w', strtotime($this->TimeStart));
	}
	
	public function getWeekDayNice() {	
		//setlocale(LC_TIME, i18n::get_locale().'.utf8');
		$unixtimestamp = strtotime($this->TimeStart);
		return _t('CourseDate.' . strtoupper(date('l', $unixtimestamp)), date('l', $unixtimestamp));
	}
	
	public function getTimeStartNice() {
		return date('d.m.Y H:i', strtotime($this->getField("TimeStart")));
	}
	
	public function getTimeEndNice() {
		return date('d.m.Y H:i', strtotime($this->getField("TimeEnd")));
	}	
	
	public function getTimeNice() {
		return date('d.m.Y', strtotime($this->getField("TimeEnd"))) . ' ' .
			   date('H:i', strtotime($this->getField("TimeStart"))) . '-' .
			   date('H:i', strtotime($this->getField("TimeEnd")));
	}
	
	public function getLocationNice() {
		if ($this->ResourceID == 0)
			return '(unknown)';
		
		$eBooking = new eBookingCommunicator();
		$resource = $eBooking->getResourceByID($this->ResourceID);
		
		return ($resource && $resource->hasField('Name')) ? $resource->Name : '(unknown)';
	}
	
	public function getLocation() {
		if ($this->ResourceID == 0) {
			return new ArrayData(array(
				'Name' => 'Unknown',
				'PostAddress' => '',
				'PostCode' => '',
				'PostOffice' => ''
			));
		}
		
		$eBooking = new eBookingCommunicator();
		$resource = $eBooking->getResourceByID($this->ResourceID);
		
		if ($resource && $resource->hasField('Name')) {
			return new ArrayData(array(
				'Name' => $resource->Name,
				'PostAddress' => $resource->PostAddress,
				'PostCode' => $resource->PostCode,
				'PostOffice' => $resource->PostOffice
			));			
		}
		
		return new ArrayData(array(
				'Name' => 'Unknown',
				'PostAddress' => '',
				'PostCode' => '',
				'PostOffice' => ''
		));
	}
		
	public function getCancelledNice() {
		if ($this->getCancelled() == true)
			return _t('CourseDate.CANCELLED_YES', 'Yes');
		
		return _t('CourseDate.CANCELLED_NO', 'No');
	}
	
	public function getCancelled() {
		$cancelled = true;
		
		$courseDateLinks = $this->CourseDateLinks();
		if ($courseDateLinks->exists()) {
			foreach ($courseDateLinks as $courseDateLink) {
				if ($courseDateLink->TeacherHourType()->HasHours == true)
					$cancelled = false;
			}
		}			
		
		return $cancelled;
	}
	
	public function getCourseNice() {
		if (!$this->Course()) return;

		$course = $this->Course();
		return $course->CourseCode . ' - ' . $course->NameList;
	}
	
	public function getTeacherNice() {
		$names = array();
		
		$courseDateLinks = $this->CourseDateLinks();
		if ($courseDateLinks->exists()) {
			foreach ($courseDateLinks as $courseDateLink) {
				$names[] = $courseDateLink->Teacher()->Name;
			}
		}	
		
		if (count($names) > 1)
			return $names[0] . ', ' . $names[1];
		else if (count($names) == 1)
			return $names[0];
		
		return '';
	}

	public function getPaymentMonthNice() {
		$months = array();
		
		$courseDateLinks = $this->CourseDateLinks();
		if ($courseDateLinks->exists()) {
			foreach ($courseDateLinks as $courseDateLink) {
				$months[] = $courseDateLink->PaymentMonth;
			}
		}		
		
		if (count($months) > 1)
			return $months[0] . ', ' . $months[1];
		else if (count($months) == 1)
			return $months[0];
		
		return '';
	}	
	
	protected function onBeforeDelete() {
		parent::onBeforeDelete();
		
		if ($this->BookingID != 0) {
			$eBooking = new eBookingCommunicator();
			$eBooking->removeBooking($this->BookingID);
		}
		
		// Remove course links
		if ($this->CourseDateLinks()->Count() > 0) {
			foreach ($this->CourseDateLinks() as $courseDateLink) {
				$courseDateLink->delete();
			}		
		}
	}
	
	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		
		$resourceID = isset($_POST['ResourceID']) ? (int)$_POST['ResourceID'] : $this->ResourceID;
		
		if ($this->ID != 0 || ($this->BookingID != 0 && $this->ID != 0)) {
			$eBooking = new eBookingCommunicator();
			
			$termID = $this->Course()->TermID;
			$cancelled = $this->getCancelled();
			
			$conflict = CourseDate::checkForConflicting($this->dbObject('TimeStart')->Format('d.m.Y H:i'),
														$this->dbObject('TimeEnd')->Format('d.m.Y H:i'),
														$resourceID, $termID, $eBooking, $this->BookingID);
			if ($conflict['conflict'] == true && $cancelled == false) {
				Debug::log("Saved date but found conflict: " . $conflict['msg_plain']);
				$this->Conflicting = true;
				$this->ConflictingReason = $conflict['msg_plain'];
			} else {
				Debug::log("Saved date and found no conflicts: " . $conflict['msg_plain']);
				$this->Conflicting = false;
				$this->ConflictingReason = $conflict['msg_plain'];
			}
			
			if ($this->BookingID != 0) {
				$eBooking->updateBooking($this->BookingID, $this->TimeStart, $this->TimeEnd, $resourceID, $cancelled);
			}
			elseif ($cancelled == false) {
				$this->BookingID = (int)$eBooking->createBooking($this->TimeStart, $this->TimeEnd, $resourceID);
			}
		}
	}
		
	public static function checkForConflicting($startDate, $endDate, $resourceID, $termID = null, $communicator = null, $bookingID = null) {
		
		//Debug::log("check collision for $startDate - $endDate ## $resourceID ## $termID");
			
		$mt = microtime(true);
		
		$eBooking = null;
		
		if ($communicator !== null)
			$eBooking = $communicator;
		else
			$eBooking = new eBookingCommunicator();

		$explodedStart = explode(' ', $startDate);
		$explodedEnd = explode(' ', $endDate);
		
		$courseDate = array(
						"Start" => $startDate,
						"End" => $endDate,
						"StartTime" => $explodedStart[1],
						"EndTime" => $explodedEnd[1],
						'ConflictingBookingTimespan' => '',
						'ConflictingBlockedTimespan' => '',
						"ConflictsWithBooking" => false,
						"ConflictsWithAvailability" => false,
						'ConflictsWithBlockedDates' => false);
						
		// Check dates to see if they are bookable	
		$retries = 0;
		while ($retries < 3) {
			$resource = $eBooking->getResourceByID($resourceID);
			$resourceBookings = $eBooking->getBookingsByResourceID($resourceID);
			
			if ($resource !== null && $resourceBookings !== null)
				break;
			else 
				$retries++;
		}
		
		if ($retries >= 3) {
			throw new Exception('Unable to get resources from e-Booking!');
		}
		
		//Debug::log("resource: " . var_export($resource, TRUE));
		//Debug::log("resourceBookings: " . var_export($resourceBookings, TRUE));
		
		// Check booking availablility
		$courseStart = strtotime($courseDate['Start']);
		$courseEnd = strtotime($courseDate['End']);
		
		if ($resource) {
			$resourceStart = strtotime($explodedStart[0] . ' ' . $resource->AvailableFrom);
			$resourceEnd = strtotime($explodedEnd[0] . ' ' . $resource->AvailableTo);
		
			// Compares a date or datepart with the existing one. Returns -1 if earlier, 0 if equal and 1 if later.
			//Debug::log("comparing resource dates ({$courseStart->toString()} -> {$courseEnd->toString()}) with ({$resourceStart->toString()} -> {$resourceEnd->toString()})");
			
			if ($courseStart >= $resourceStart && $courseStart <= $resourceEnd &&
				$courseEnd >= $resourceStart && $courseEnd <= $resourceEnd && $courseDate['ConflictsWithAvailability'] == false) {
				// OK!
				$courseDate['ConflictsWithAvailability'] = false;
			} else
				$courseDate['ConflictsWithAvailability'] = true;
		}
		
		// Check with other bookings
		if ($resourceBookings && $resourceBookings->Count()) {
			foreach ($resourceBookings as $booking) {
				
				$bookingStart = strtotime($booking->Start);
				$bookingEnd = strtotime($booking->End);
				
				$conflicting = ($bookingStart < $courseEnd && $bookingEnd > $courseStart && $booking->Status == 'Accepted') ? true : false; // may start at end or wise-versa

				if ($conflicting || $courseDate['ConflictsWithBooking']) {
					if ($bookingID === null || ($bookingID !== null && $bookingID != $booking->ID)) {
						// not ok
						if ($conflicting)
							$courseDate['ConflictingBookingTimespan'] = date('H:i', $bookingStart) . ' - ' . date('H:i', $bookingEnd);

						$courseDate['ConflictsWithBooking'] = true;
					} 
				} else
					$courseDate['ConflictsWithBooking'] = false;	

			}
		}
		
		// Check with blocked term dates

		$blockedTermDates = array();
		if ($termID != null && $termID != 0)
			$blockedTermDates = DataObject::get('BlockedTermDate', 'TermID = ' . $termID);

		if (!empty($blockedTermDates)) {
			foreach ($blockedTermDates as $blockedDate) {
				$blockedStart = strtotime($blockedDate->TimeStart);
				$blockedEnd = strtotime($blockedDate->TimeEnd);

				$conflicting = ($blockedStart < $courseEnd && $blockedEnd > $courseStart) ? true : false; // may start at end or wise-versa

				if ($conflicting || $courseDate['ConflictsWithBlockedDates']) {
					// not ok
					if ($conflicting)
						$courseDate['ConflictingBlockedTimespan'] = date('d.m.Y H:i'. $blockedStart) . ' - ' . date('d.m.Y H:i', $blockedEnd);

					$courseDate['ConflictsWithBlockedDates'] = true;
				} else
					$courseDate['ConflictsWithBlockedDates'] = false;
			}		
		}

		// Output the date to summary
		$i18n_weekday = _t('CourseDate.' . strtoupper(date('l', $courseStart)), date('l', $courseStart));
		$prettyDate = $i18n_weekday . ' ' . date('d.m.Y H:i', $courseStart) . ' - ' . date('H:i', $courseEnd) . ' - ' . ($resource->hasField('Name') ? $resource->Name : $resourceID);
		$result = array('conflict' => false, 'msg_plain' => '', 'msg_html' => "");
		
		if ($courseDate['ConflictsWithBooking']) {
			$prettyBookingTime = $courseDate['ConflictingBookingTimespan']; 			
			$result['conflict'] = true;
			$result['msg_plain'] = $prettyDate . ' (bokning ' . $prettyBookingTime . ')';
			$result['msg_html'] = '<span style="color: red">' . $result['msg_plain']. '</span>';
		}			
		elseif ($courseDate['ConflictsWithBlockedDates']) {
			$prettyBlockedTime = $courseDate['ConflictingBlockedTimespan']; 			
			$result['conflict'] = true;
			$result['msg_plain'] = $prettyDate . ' (otillgängligt ' . $prettyBlockedTime . ')';
			$result['msg_html'] = '<span style="color: red">' . $result['msg_plain']. '</span>';		
		}
		elseif ($courseDate['ConflictsWithAvailability']) {
			$prettyResourceTime = date('H:i', $resourceStart) . ' - ' . date('H:i', $resourceEnd);
			$result['conflict'] = true;
			$result['msg_plain'] = $prettyDate . ' (endast tillgängligt ' . $prettyResourceTime . ')';
			$result['msg_html'] = '<span style="color: red">' . $result['msg_plain']. '</span>';		
		}
		else {
			$result['conflict'] = false;
			$result['msg_plain'] = $prettyDate;
			$result['msg_html'] = '<span style="color: black">' . $result['msg_plain']. '</span>';					
		}
		
		$diff = microtime(true) - $mt;
		Debug::log("CourseDate::checkForConflicting() took " . $diff);		
		
		return $result;
	}
	
	public function getDOMTitle() {
		return $this->TimeNice;
	}		
}
?>