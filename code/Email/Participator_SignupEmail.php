<?php

class Participator_SignupEmail extends Email {
	protected $from = '';  // setting a blank from address uses the site's default administrator email
	protected $subject = '';
	protected $body = '';
	protected $ss_template = 'AccountInformationEmail';

	function __construct() {
		parent::__construct();
		$this->subject = _t('AccountInformationEmail.SUBJECT', "Account information");
	}	
}

?>
