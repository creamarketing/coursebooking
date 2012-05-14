<?php

class ReportAdminDecorator extends LeftAndMainDecorator {
	
	function init() {
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript(SAPPHIRE_DIR . '/javascript/jquery_improvements.js');	
		Requirements::javascript('resourcebooking/javascript/jquery-ui-1.8.6.custom.min.js');
		Requirements::css('resourcebooking/css/smoothness/jquery-ui-1.8.6.custom.css');
		Requirements::javascript(THIRDPARTY_DIR . "/jquery-metadata/jquery.metadata.js");
		Requirements::javascript(SAPPHIRE_DIR . "/javascript/DateField.js");
		Requirements::css('resourcebooking/css/ResourceBookingReport.css');
	}
	
}
?>
