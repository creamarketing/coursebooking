<?php

class GhostParticipator extends DataObject {
	static $db = array(
		'TTL' => 'Int',
		'Hash' => 'Varchar(32)'
	);
	
	static $has_one = array(
		'Participator' => 'Participator'
	);
	
	static $many_many = array(
		'Courses' => 'Course'
	);
	
	static $defaults = array(
		'TTL' => '900'	// Time to live
	);
	
	function getAlive() {
		$lastEdited = strtotime($this->getField('LastEdited'));
		
		if ($lastEdited + $this->TTL < time()) 
			return false;
		
		return true;		
	}
	
	function getLifeLeft() {
		$lastEdited = strtotime($this->getField('LastEdited'));
		
		return ($lastEdited + $this->TTL) - time();
	}
}
?>
