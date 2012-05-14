<?php

class CourseRequestInvoice extends Invoice {
	static $has_one = array(
		'CourseRequest' => 'CourseRequest',
		'CourseUnit' => 'CourseUnit'
	);
	
	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if (empty($this->InvoiceDueDate) && !empty($this->InvoiceDate)) {
			if ($this->Type == 'Reminder')
				$dueDays = $this->CourseUnit()->ReminderPaymentDays;
			else
				$dueDays = $this->CourseUnit()->PaymentDays;
			
			$this->InvoiceDueDate = date('Y-m-d H:i:s', strtotime($this->InvoiceDate) + ($dueDays * 60*60*24));
		}		
	}
}

?>
