<?php

class DateFieldEx extends DateField {
	public function __construct($name, $title = null, $value = null, $form = null, $rightTitle = null) {
		parent::__construct($name, $title, $value, $form, $rightTitle);
		
		$this->setConfig('showcalendar', true);
		$this->setConfig('dateformat', 'dd.MM.YYYY');
		
		$this->addExtraClass('date');
		
		$this->setMaxLength(10);
	}
	
	function Field() {
		Requirements::javascript('coursebooking/javascript/jquery.maskedinput-1.3.min.js');
		
		Requirements::block(SAPPHIRE_DIR . "/javascript/DateField.js");
		Requirements::javascript('coursebooking/javascript/ExtendedFields/DateFieldEx.js');
				
		$html = parent::Field();
		
		if ($this->getConfig('showcalendar') === false) {
		$customJS = <<<JS
		
		jQuery(document).ready(function() {
			jQuery.mask.definitions['d']='[0-3]';
			jQuery.mask.definitions['m']='[0-1]';
			jQuery.mask.definitions['M']='[0-2]';
			jQuery('#{$this->id()}').mask('d9.mM.9999', {
				completed: function() {
					var regex=/^([0-2][0-9]|3[01]).(0[0-9]|1[0-2]).[0-9]{4}$/;
					if (!regex.test(this.val())) {
						this.addClass('invalid-input-date');
					}
					else {
						this.removeClass('invalid-input-date');
					}
				}
			});
		});
		
JS;
			
		Requirements::customScript($customJS);
		}
		
		return $html;
	}
}

?>
