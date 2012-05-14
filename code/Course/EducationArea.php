<?php

class EducationArea extends DataObject {
	
	static $extensions = array(
		'TranslatableDataObject',
		'CreaDataObjectExtension',
		'PermissionExtension'	
	);
	
	static $db = array(
		'Code' => 'Varchar(20)',		
		'Name' => 'Varchar(150)',		
	);
		
	static $translatableFields = array(
		'Name'
	);
	
	static $has_one = array(
		'ParentEducationArea' => 'EducationArea'
	);
	
	static $has_many = array(
		'Courses' => 'Course',
		'ChildEducationAreas' => 'EducationArea',
		'CourseSubjects' => 'CourseSubject'
	);		

	static $summary_fields = array(
		'Code',
		'Name'
	);
	
	static $default_sort = 'Code ASC';
	
	static $defaults = array(
	
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
		//Requirements::css('coursebooking/css/CourseRequestDialog.css');
		$this->extend('getRequirementsForPopup');
	}		
		
	function getCMSFields($includeDOMS = true) {
		$fields = new FieldSet();
	
		$available_parents = $this->getArrayForObject('EducationArea', 'Name ASC', "ID != {$this->ID}", 'Name');
		$available_parents = array('' => '');
		if ($categories = DataObject::get('EducationArea', "ID != {$this->ID}")) {
			foreach ($categories as $category) {
				$available_parents += array($category->ID => $category->Code . ' ' . $category->NiceName);
			}
			natsort($available_parents);
		}
		
		$tabGeneral = new Tab('General', _t('Object.MAIN', 'General'),		
			new NumericField('Code', _t('EducationArea.CODE', 'Code')),
			new TextField('Name', _t('EducationArea.NAME', 'Name')),
			$dropdown = new DropdownField('ParentEducationAreaID', _t('EducationArea.PARENTNAME', 'Parent name'), $available_parents, ''),
			$dom = new DialogHasManyDataObjectManager(
					$this, 
					'ChildEducationAreas', 
					'EducationArea', 
					array(
						'Code' => _t('EducationArea.CODE', 'Code'), 
						'NiceName' => _t('EducationArea.SINGULARNAME', 'Educationarea'),
						'Name' => _t('EducationArea.NAME', 'Name')
					), 
					null, 
					"((ParentEducationAreaID = 0 OR ParentEducationAreaID = '{$this->ID}') AND (ID != '{$this->ID}' AND ID != '{$this->ParentEducationAreaID}'))")
		);		

		$fields->push($tabSet = new DialogTabSet('TabSet', $tabGeneral));
	
		
		if ($includeDOMS)
		{
			/*$tabSet->push($coursesTab = new Tab('CoursesTab', _t('Course.PLURALNAME', 'Courses')));
			$coursesTab->push($coursesDOM = new DialogHasManyDataObjectManager(
								$this, 
								'Courses', 
								'Course', 
		  						array( 
									'CourseCode' => _t('Course.COURSECODE', 'Course code'),
									'NameList' => _t('Course.NAME', 'Name')
								)
			));*/
		}
			
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}	
	
	function getCMSFields_forConstruct() {
		$fields = $this->getCMSFields(false);
		return $fields;
	}	
	
	public function getNiceName() {
		if ($this->ParentEducationAreaID == 0)
			return $this->Name;
		else
			return $this->ParentEducationArea()->Name . ' » ' . $this->Name;
	}

	static function toDropdownList() {
		$objects = DataObject::get('EducationArea', '', 'Code ASC');
		$list = array('' => '');
		
		if (count($objects))
			foreach($objects as $obj) {
				$list[$obj->ID] = $obj->Code . ' ' . $obj->getNiceName();
			}	
		
		return $list;
	}
	
	public function getDOMTitle() {
		return $this->Name;
	}		
}

?>