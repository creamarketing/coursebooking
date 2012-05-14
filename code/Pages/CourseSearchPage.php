<?php

class CourseSearchPage extends Page {
	
}

class CourseSearchPage_Controller extends Page_Controller {
	protected static $itemsPerPage = 10;
	
	static $extensions = array(
		'CreaDataObjectExtension',
		'CourseBookingExtension'
	);
	
	function quickSearchForm() {
		$fields = new FieldSet(new TextField('CourseCodeOrName', _t('CourseQuickSearch.CODEORNAME', 'Course code or name')));
		
		$actions = new FieldSet(new FormAction('performQuickSearch', _t('CourseQuickSearch.SEARCH', 'Search courses')),
								new FormAction('showAll', _t('CourseQuickSearch.SHOWALL', 'Show all courses'), null, null, 'showallbutton'));
		
		return new Form($this, 'quickSearchForm', $fields, $actions);
	}
	
	function performQuickSearch($data, $form) {	
		$courseCodeOrName = isset($data['CourseCodeOrName']) ? Convert::raw2sql($data['CourseCodeOrName']) : '';

		Session::clear('searchParameters');
		Session::save();
		
		Session::set('searchParameters', array(
			'quickSearch' => true,
			'CourseCodeOrName' => $courseCodeOrName
		));
		
		Director::redirect('showResults');
	}
	
	function showAll($data, $form) {
		Session::clear('searchParameters');
		Session::save();
		
		Session::set('searchParameters', array(
			'quickSearch' => true,
			'CourseCodeOrName' => ''
		));		
		
		Director::redirect('showResults');
	}
	
	function detailedSearchForm() {
		$languages = $this->getArrayForObject('CourseLanguage', 'Name ASC');
		$units = $this->getArrayForObject('CourseUnit', 'Name ASC');
		$subjects = $this->getArrayForObject('CourseSubject', 'Name ASC');
		$terms = $this->getArrayForObject('Term', 'Name ASC', 'Active = 1');
		
		$eBooking = new eBookingCommunicator();
		$locations = array('' => '');
		foreach ($eBooking->getResourcesAsDropdownMap('PostOffice') as $key => $value) {
			if (in_array($value, $locations) == false)
				$locations += array($value => $value);
		}
		
		$weekdays = array('' => '');
		$weekdays += array('1' => _t('CourseDate.MONDAY', 'Monday'));
		$weekdays += array('2' => _t('CourseDate.TUESDAY', 'Tuesday'));
		$weekdays += array('3' => _t('CourseDate.WEDNESDAY', 'Wednesday'));
		$weekdays += array('4' => _t('CourseDate.THURSDAY', 'Thursday'));
		$weekdays += array('5' => _t('CourseDate.FRIDAY', 'Friday'));
		$weekdays += array('6' => _t('CourseDate.SATURDAY', 'Saturday'));
		$weekdays += array('7' => _t('CourseDate.SUNDAY', 'Sunday'));
		
		$fields = new FieldSet(new AdvancedDropdownField('CourseLanguage', _t('CourseDetailedSearch.LANGUAGE', 'Language'), $languages),
							   /*new AdvancedDropdownField('CourseUnit', _t('CourseDetailedSearch.UNIT', 'Unit'), $units),*/
							   new AdvancedDropdownField('CourseSubject', _t('CourseDetailedSearch.SUBJECT', 'Subject'), $subjects),
							   new AdvancedDropdownField('CourseLocation', _t('CourseDetailedSearch.LOCATION', 'Location'), $locations),
							   /*new AdvancedDropdownField('CourseTerm', _t('CourseDetailedSearch.TERM', 'Term'), $terms),*/
							   new AdvancedDropdownField('CourseWeekday', _t('CourseDetailedSearch.WEEKDAY', 'Weekday'), $weekdays),
							   $freeSpotsCheckbox = new CheckboxField('ShowFreeSpotsOnly', _t('CourseDetailedSearch.FREESPOTSONLY', 'Show only courses with free spots'))/*,
							   $activeCoursesCheckbox = new CheckboxField('ShowActiveCoursesOnly', _t('CourseDetailedSearch.ACTIVEONLY', 'Show only ongoing courses'))*/);

		$freeSpotsCheckbox->setRightTitle(true);
		//$activeCoursesCheckbox->setRightTitle(true);
		
		$actions = new FieldSet(new FormAction('performDetailedSearch', _t('CourseDetailedSearch.SEARCH', 'Search courses')),
								new FormAction('showAll', _t('CourseDetailedSearch.SHOWALL', 'Show all courses'), null, null, 'showallbutton'));
		
		return new Form($this, 'detailedSearchForm', $fields, $actions);
	}
	
	function performDetailedSearch($data, $form) {
		$courseLanguageID = isset($data['CourseLanguage']) ? Convert::raw2sql($data['CourseLanguage']) : '';
		$courseUnitID = isset($data['CourseUnit']) ? Convert::raw2sql($data['CourseUnit']) : '';
		$courseSubjectID = isset($data['CourseSubject']) ? Convert::raw2sql($data['CourseSubject']) : '';
		$courseTermID = isset($data['CourseTerm']) ? Convert::raw2sql($data['CourseTerm']) : '';
		$courseWeekday = isset($data['CourseWeekday']) ? Convert::raw2sql($data['CourseWeekday']) : '';
		$courseLocation = isset($data['CourseLocation']) ? Convert::raw2sql($data['CourseLocation']) : '';
		$showFreeOnly = isset($data['ShowFreeSpotsOnly']) ? 1 : 0;
		$showActiveOnly = isset($data['ShowActiveCoursesOnly']) ? 1 : 0;
		
		Session::clear('searchParameters');
		Session::save();
		
		Session::set('searchParameters', array(
			'detailedSearch' => true,
			'CourseLanguage' => $courseLanguageID,
			'CourseUnit' => $courseUnitID,
			'CourseSubject' => $courseSubjectID,
			'CourseTerm' => $courseTermID,
			'CourseWeekday' => $courseWeekday,
			'CourseLocation' => $courseLocation,
			'ShowFreeOnly' => $showFreeOnly,
			'ShowActiveOnly' => $showActiveOnly
		));		
		
		Director::redirect('showResults');
	}	
	
	public function showResults() {
		$results = $this->updateSearchData();
		
		// Pagination
		if (!isset($_GET['start']) || !is_numeric($_GET['start']) || (int)$_GET['start'] < 1) 
			$_GET['start'] = 0;
		$SQL_start = (int)$_GET['start'];
		
		if ($results)
			$results->setPageLimits($SQL_start, self::$itemsPerPage, $results->Count());			
		
		$customData['sort'] = 'CourseCode';
		$customData['sort_dir'] = 'ASC';
		
		if (isset($_GET['sort']) && isset($_GET['sort_dir'])) {
			$customData['sort'] = $_GET['sort'];
			$customData['sort_dir'] = $_GET['sort_dir'];
			
			switch ($customData['sort']) {
				case 'code': $customData['sort'] = 'CourseCode'; break;
				case 'name': $customData['sort'] = 'NameList'; break;
				case 'location': $customData['sort'] = 'MainLocationOffice'; break;
				case 'freespots': $customData['sort'] = 'FreeSpots'; break;
				case 'startdate': $customData['sort'] = 'RecDateStart'; break;
				case 'stopdate': $customData['sort'] = 'RecDateEnd'; break;
				default: $customData['sort'] = 'CourseCode'; break;
			}			
			
			$results->sort($customData['sort'], $customData['sort_dir']);
		}	
		
		$customData['showResults'] = true;
		$customData['Courses'] = $results;
		
		if (count($results) == 0)
			$customData['nothingFound'] = true;
		
		return $this->renderWith(array('CourseSearchPage', 'Page'), $customData);
	}
	
	private function updateSearchData() {
		$results = new DataObjectSet();
		$searchParameters = Session::get('searchParameters');
			
		//$active_where = '(SignupStart <= CURDATE() AND (SignupEnd >= CURDATE() OR SignupExpiresNot = 1) AND Completed = 0 AND Status = \'Active\' AND (SELECT Term.Active FROM Term WHERE Term.ID = TermID) = 1)';
		$active_where = '(Completed = 0 AND Status = \'Active\' AND (SELECT Term.Active FROM Term WHERE Term.ID = TermID) = 1)';
		
		if (isset($searchParameters['quickSearch'])) {
			$courseCodeOrName = $searchParameters['CourseCodeOrName'];
			
			$where[] = 'CourseCode LIKE \'%' . $courseCodeOrName . '%\'';
			
			$languages = Translatable::get_allowed_locales();
			
			if (count($languages)) {
				foreach ($languages as $lang) {
					$where[] = 'Name_' . $lang . ' LIKE \'%' . $courseCodeOrName . '%\'';
				}
			}
			
			$results = DataObject::get('Course', '(' . implode(' OR ', $where) . ') AND ' . $active_where, 'CourseCode ASC');
		}
		elseif (isset($searchParameters['detailedSearch'])) {
			$where = array();
			$join = array();
			
			// Course language
			if (!empty($searchParameters['CourseLanguage'])) {
				$languageID = (int)$searchParameters['CourseLanguage'];
				
				$where[] = 'CourseLanguage.ID = ' . $languageID;
				$join[] = 'LEFT JOIN Course_Languages ON Course_Languages.CourseID = Course.ID LEFT JOIN CourseLanguage ON CourseLanguage.ID = Course_Languages.CourseLanguageID';
			}
			
			// Course unit
			if (!empty($searchParameters['CourseUnit'])) {
				$unitID = (int)$searchParameters['CourseUnit'];
				
				$where[] = 'CourseUnitID = ' . $unitID;
			}
			
			// Course subject
			if (!empty($searchParameters['CourseSubject'])) {
				$subjectID = (int)$searchParameters['CourseSubject'];
				
				$where[] = 'CourseSubject.ID = ' . $subjectID;
				$join[] = 'LEFT JOIN Course_Subjects ON Course_Subjects.CourseID = Course.ID LEFT JOIN CourseSubject ON CourseSubject.ID = Course_Subjects.CourseSubjectID';
			}			
			
			// Course term
			if (!empty($searchParameters['CourseTerm'])) {
				$termID = (int)$searchParameters['CourseTerm'];
				
				$where[] = 'TermID = ' . $termID;				
			}
			
			// Course weekday
			if (!empty($searchParameters['CourseWeekday'])) {
				$weekDay = (int)$searchParameters['CourseWeekday'] - 1;
				
				$where[] = 'WEEKDAY(CourseDate.TimeStart) = ' . $weekDay;
				$join[] = 'LEFT JOIN CourseDate ON CourseDate.CourseID = Course.ID';
				
				Debug::log("Wanting only courses that has dates on: " . $weekDay);
			}
			
			// Course location
			//if (!empty($searchParameters['CourseLocation'])) {
			//	$where[] = '(SELECT ResourceID FROM CourseDate WHERE CourseDate.CourseID = Course.ID AND ResourceID = ' . (int)$searchParameters['CourseLocation'] . ' LIMIT 1)';
			//}
			
			if ($searchParameters['ShowActiveOnly']) {
				$where[] = '(RecDateStart <= CURDATE() AND RecDateEnd >= CURDATE())';
			}
				
			if ($searchParameters['ShowFreeOnly']) {
				$where[] = '(SELECT COUNT(ID) AS ParticipatorCount FROM CourseRequest WHERE CourseRequest.CourseID = Course.ID AND CourseRequest.Status = \'Notified\') < Course.MaxParticipators';
			}
			
			// Combine search parameters and get the result
			$where[] = $active_where;
			$results = DataObject::get('Course', implode(' AND ', $where), 'CourseCode ASC', implode(' ', $join));
			
			// We must sort course location using php
			if (!empty($searchParameters['CourseLocation']) && count($results)) {
				$resultsByLocation = new DataObjectSet();
				foreach ($results as $course) {				
					if ($course->getMainLocation()->Short->PostOffice === $searchParameters['CourseLocation'])
						$resultsByLocation->push($course);
				}
				
				$results = $resultsByLocation;
			}
		}

		return $results;
	}
}

?>
