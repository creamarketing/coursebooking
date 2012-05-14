<?php
class CreaDefaultSelectable extends DataObjectDecorator {
	function extraStatics() {
		return array(
			'db' => array(
				'UseAsDefault' => 'Boolean'
			)
		);
	}
	
	public function updateCMSFields(FieldSet $fields) {
		$fields->push(new FieldGroup(new CheckboxField('UseAsDefault', _t('CreaDefaultSelectable.USEASDEFAULT', 'Use as default'), false)));
		return $fields;
	}
	
	public function onAfterWrite() {	
		if ($this->owner->isChanged('UseAsDefault', 2)) {
			// Clear others
			DB::query("UPDATE {$this->ownerBaseClass} SET UseAsDefault = 0 WHERE ID != {$this->owner->ID}");
			
			// Update ourself
			DB::query("UPDATE {$this->ownerBaseClass} SET UseAsDefault = {$this->owner->UseAsDefault} WHERE ID = {$this->owner->ID}");
		}
	}
	
	public static function getDefaultSelected($className) {
		$default = DataObject::get_one($className, 'UseAsDefault = 1');
		if ($default)
			return $default->ID;
		return null;
	}
}

?>
