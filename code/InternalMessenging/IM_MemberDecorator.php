<?php

class IM_MemberDecorator extends DataObjectDecorator {
	function extraStatics() {
		return array(
			'has_one' => array(
				'IM_Inbox' => 'IM_MessageBox',
				'IM_Sentbox' => 'IM_MessageBox'
			)
		);
	}
}

?>
