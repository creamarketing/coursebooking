<?php

class MemberPermanentLockable extends DataObjectDecorator {

	function canLogIn(&$result) {
		if ($this->owner->LockedPermanent) {
			$result->error(_t (
				'MemberPermanentLockable.ERRORLOCKEDOUT',
				'Your account has been permanently disabled.'
			));
		}
	}
	
	function extraStatics() {
		return array(
			'db' => array('LockedPermanent' => 'Boolean')
		);
	}
}

?>
