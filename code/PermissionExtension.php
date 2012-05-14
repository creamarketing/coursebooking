<?php

class PermissionExtension extends DataObjectDecorator {
	
	public function canView($member = null) {
		return CourseBookingExtension::isAdmin();
	}

	public function canCreate($member = null) {
		return CourseBookingExtension::isAdmin();// ? true : false;
	}
	
	public function canEdit($member = null) {
		return CourseBookingExtension::isAdmin();
	}

	public function canDelete($member = null) {
		return CourseBookingExtension::isAdmin();
	}
	
	public function can($perm, $member = null) {
		if ($perm === 'duplicate')
			return false;
	}
}

?>