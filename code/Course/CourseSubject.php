<?php

class CourseSubject extends DataObject {
	
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Code' 	=> 'Varchar(20)',
		'Name' 	=> 'Varchar(150)'
	);
	
	static $translatableFields = array(
		'Name'
	);

	static $summary_fields = array(
		'Code',
		'Name',
	);

	static $belongs_many_many = array(
		"Courses" => "Course",
	);	
	
	static $has_one = array(
		'EducationArea' => 'EducationArea',
		'CourseMainClass' => 'CourseMainClass'
	);

	static $default_sort = 'Code ASC';
	
	/*
	 * Fixing translation for fields automatically
	 */
	function fieldLabels($includerelations = true) { 
    $labels = parent::fieldLabels($includerelations);
		$this->extend('fieldLabels', $labels);		
		return $labels;
	}
	
	function getRequirementsForPopup() {	
		//Requirements::css('coursebooking/css/CourseRequestDialog.css');
		$this->extend('getRequirementsForPopup');
	}		
	
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', 
				$mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))
				));
		
		$educationAreas = EducationArea::toDropdownList();
		$courseMainClasses = CourseMainClass::toDropdownList();	
		
		$mainTab->push(new TextField('Code', _t('CourseSubject.CODE', 'Code')));
		$mainTab->push(new TextField('Name', _t('CourseSubject.NAME', 'Name of subject')));
		$mainTab->push(new AdvancedDropdownField('EducationAreaID', _t('EducationArea.SINGULARNAME', 'Education area'), $educationAreas));
		$mainTab->push(new AdvancedDropdownField('CourseMainClassID', _t('CourseMainClass.SINGULARNAME', 'Course main class'), $courseMainClasses));		
						
		if ($includeDOMS)
		{
			/*$tabSet->push($coursesTab = new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses')));
			$coursesTab->push($coursesDOM = new DialogManyManyDataObjectManager(
								$this, 
								'Courses', 
								'Course', 
		  						array( 
									'CourseCode' => _t('Course.COURSECODE', 'Course code'),
									'NameList' => _t('Course.NAME', 'Name')
								)
			));
			
			if ($this->ID) {
				$coursesDOM->setOnlyRelated(true);
			}*/			
		}
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}		
	
	static function toDropdownList() {
		$objects = DataObject::get('CourseSubject', '', 'Code ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				$list[$obj->ID] = $obj->Code . ' ' . $obj->Name;
			}	
		
		return $list;
	}	

	public function getDOMTitle() {
		return $this->Name;
	}		
}
?>