<?php

class CourseLanguage extends DataObject {
	
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'		
	);
	
	static $db = array(
		'Name' => 'Varchar(150)',		
		'Code' => 'Varchar(20)',
		'Locale' => 'Varchar(5)'
	);
	
	static $translatableFields = array(
		'Name'
	);

	static $belongs_many_many = array(
		"Courses" => "Course",
	);		
	
	static $defaults = array(
	
	);
	
	static $summary_fields = array(
		'Code',	
		'Name'
	);	
	
	static $default_sort = 'Code ASC';
	
	static $jqgrid_summary_fields = array(
		'Code' => array('sorttype' => 'int', 
						'edittype' => 'text',
						'editable' => true,
						'editoptions' => array('size' => 10)),
		'NamePretty' => array('edittype' => 'text',
						'editable' => true)
						//'editoptions' => array('size' => 10))
						//'formoptions' => array('label' => 'Test'))
	);
	
	static $jqgrid_default_sort = "Code DESC";
	
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
		$fields = new FieldSet($tabSet = new DialogTabSet('Main', $mainTab = new Tab('MainTab', _t('Object.MAIN', 'General'))));
		$mainTab->push(new TextField('Code', _t('CourseLanguage.CODE', 'Course code')));
		$mainTab->push(new TextField('Name', _t('CourseLanguage.NAME', 'Course language')));
		$mainTab->push(new TextField('Locale', _t('CourseLanguage.LOCALE', 'Locale')));
		
		if ($includeDOMS)
		{
			$tabSet->push($coursesTab = new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses')));
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
			}			
		}		
		
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	
	
	function getNamePretty() {
		return $this->getField('Name');
	}
	
	public function getCMSFieldsForjqGrid() {
		$fields = $this->getCMSFields(true);
		$fields->push(new jqGridManager($this, 'Testeri', 'Testing more more more', 'CourseLanguage'));
		return $fields;
	}
	
	static function toDropdownList() {
		$objects = DataObject::get('CourseLanguage', '', 'Code ASC');
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