<?php 
class CourseBookingExtension extends Extension {
	public function extraStatics() {
		
	} 
	
	public function getCensoredParticipators() {
		if (!self::isLoggedIn())
			return true;
		else if (self::isParticipator())
			return true;
		return false;
	}		
	
	public function getCourseBookingRequirements() {
		// jQuery and jQuery ui
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-form/jquery.form.js');
		Requirements::javascript('coursebooking/javascript/jquery-ui-1.8.6.custom.min.js');
		Requirements::css('coursebooking/css/smoothness/jquery-ui-1.8.6.custom.css');
		
		// javascript localization
		Requirements::javascript(SAPPHIRE_DIR . '/javascript/i18n.js');
		Requirements::add_i18n_javascript('coursebooking/javascript/lang');
		
		// qTip jQuery tooltip plugin
		Requirements::javascript('coursebooking/javascript/jquery.qtip-1.0.min.js');
			
//		Requirements::javascript('coursebooking/javascript/AdvancedDropdownField.js');
	}
	
	public static function isAdmin() {
		$member = Member::CurrentMember();
		if ($member) {
			return ($member->currentUser() && ($member->currentUser()->inGroup('administrators') || $member->currentUser()->inGroup('courseadmins')));
		} 
		
		return false;
	}
	
	public static function isTeacher() {
		$member = Member::CurrentMember();
		if ($member) {
			return ($member->currentUser() && $member->currentUser()->inGroup('teachers'));
		} 
		
		return false;
	}	
	
	public static function isParticipator() {
		$member = Member::CurrentMember();
		if ($member) {
			return ($member->currentUser() && $member->currentUser()->inGroup('participators'));
		} 
		
		return false;
	}	
	
	public static function getAdminID() {
		$member = Member::CurrentMember();
		if ($member && $member->currentUser() && $member->currentUser()->inGroup('courseadmins'))
				return $member->currentUserID();
		
		return null;
	}	
	
	public static function isLoggedIn() {
		$member = Member::CurrentMember();
		
		if ($member)
			return true;
		return false;
	}
	
	public static function currentUser() {
		$member = Member::CurrentMember();
		
		return $member;
	}
}