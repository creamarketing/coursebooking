<?php

class SS_DatetimeExtension extends Extension {
	public function Nice24Finland() {
		return date('d.m.Y H:i', strtotime($this->getOwner()->value));
	}
}

?>
