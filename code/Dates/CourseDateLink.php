<?php

class CourseDateLink extends DataObject {
	
	static $extensions = array(
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);	
	
	static $db = array(
		'Lessions' => 'Decimal(4,2)',
		'PaymentMonth' => "Enum('1,2,3,4,5,6,7,8,9,10,11,12','1')",
		'Locked' => 'Boolean'
	);
	
	static $has_one = array(
		'CourseDate' => 'CourseDate',
		'Teacher' => 'Teacher',
		'TeacherHourType' => 'HourType',
		'TeacherSalaryClass' => 'TeacherSalaryClass'
	);
	
	static $defaults = array(
		'Locked' => false
	);
	
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}	

	public function getTeacherNice() {
		return $this->Teacher()->Name;
	}

	public function getTeacherHourTypeNice() {
		return $this->TeacherHourType()->Name;
	}

	public function getTeacherSalaryClassNice() {
		return $this->TeacherSalaryClass()->Name;
	}	
	
	public function getTimeStartNice() {
		return $this->CourseDate()->TimeStartNice;
	}
	
	public function getTimeEndNice() {
		return $this->CourseDate()->TimeEndNice;
	}	
	
	public function getTimeNice() {
		return date('d.m.Y', strtotime($this->CourseDate()->getField("TimeEnd"))) . '<br/>' .
			   date('H:i', strtotime($this->CourseDate()->getField("TimeStart"))) . '-' .
			   date('H:i', strtotime($this->CourseDate()->getField("TimeEnd")));
	}	
	
	public function getCourseName() {
		return $this->CourseDate()->Course()->Name;
	}
	
	public function getCourseCode() {
		return $this->CourseDate()->Course()->CourseCode;
	}
		
	function getRequirementsForPopup() {
		Requirements::javascript('coursebooking/javascript/CourseDateLink.js');
		$this->extend('getRequirementsForPopup');
	}	
	
	function getCMSFields() {
		$fields = new FieldSet();
		
		$fields->push(
			new DialogTabSet('TabSet',
				$tabGeneral = new Tab('General', _t('CourseDate.SPECIFIC', 'Specific'))
			)
		);				
		
		$teachersArray = Teacher::toDropdownList();
		$hourTypesArray = HourType::toDropdownList();
		$salaryClassArray = TeacherSalaryClass::toDropdownList();
		$paymentMonths_array = singleton('CourseDateLink')->dbObject('PaymentMonth')->enumValues();
		
		$tabGeneral->push(new AdvancedDropdownField("TeacherID", _t('Teacher.SINGULARNAME', 'Teacher'), $teachersArray));
		$tabGeneral->push(new AdvancedDropdownField("TeacherHourTypeID", _t('HourType.SINGULARNAME', 'Hourtype'), $hourTypesArray));
		$tabGeneral->push(new AdvancedDropdownField("TeacherSalaryClassID", _t('TeacherSalaryClass.SINGULARNAME', 'Salary class'), $salaryClassArray));
		$tabGeneral->push($grp = new FieldGroup($lessions = new NumericFieldEx("Lessions", _t('CourseDate.LESSIONS', 'Lessions')),
										 $paymentMonth = new DropdownField("PaymentMonth", _t('CourseDate.PAYMENTMONTH', 'Payment month'), $paymentMonths_array)));

		$lessions->setMaxLength(4);
		
		if (CourseBookingExtension::isAdmin()) {
			$grp->push($locked = new CheckboxField("Locked", _t('CourseDateLink.LOCKED', 'Locked')));
		}
		
		if ($this->isDOMAddForm("CourseDateLinks")) {
			$fields->push(new HiddenField('closeAfterAdd', '', 'true'));			
		}
		
		return $fields;
	}	
	
	function getNiceLocked() {
		return $this->dbObject('Locked')->Nice();
	}
}

?>
