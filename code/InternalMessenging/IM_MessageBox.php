<?php

class IM_MessageBox extends DataObject {
	static $db = array(
		'EmailNotification' => 'Boolean'
	);
	
	static $has_one = array(
		'Owner' => 'Member'
	);
	
	static $has_many = array(
		'Messages' => 'IM_Message'
	);
}

?>
