<?php

class IM_Message extends DataObject {
	static $db = array(
		'FromID' => 'Int',
		'ToID' => 'Int',
		'Subject' => 'Varchar',
		'Body' => 'Text',
		'RecipientType' => "Enum('Member,Course', 'Member')"
	);
		
	static $defaults = array(
		'RecipientType' => 'Member'
	);
	
	static $has_one = array(
		'MessageBox' => 'IM_MessageBox'
	);
	
	public function send($saveToSentbox = true) {			
		$fromUser = DataObject::get_by_id('Member', (int)$this->FromID);
		$toUser = DataObject::get_by_id('Member', (int)$this->ToID);
			
		if ($fromUser && $toUser) {
			// Create a sentbox if FROM doesn't have one
			if ($fromUser->IM_Sentbox()->exists() != true) {
				$messageBox = new IM_MessageBox();
				$messageBox->OwnerID = $fromUser->ID;
				$messageBox->write();
				
				$fromUser->IM_SentboxID = $messageBox->ID;
				$fromUser->write();				
			} 
			
			// Create a inbox if TO doesn't have one
			if ($toUser->IM_Inbox()->exists() != true) {
				$messageBox = new IM_MessageBox();
				$messageBox->OwnerID = $toUser->ID;
				$messageBox->write();
				
				$toUser->IM_InboxID = $messageBox->ID;
				$toUser->write();
			} 			

			// Create the mail for FROM (the sender of this message)
			if ($saveToSentbox) {
				$message = new IM_Message();
				$message->FromID = $this->FromID;
				$message->ToID = $this->ToID;
				$message->Subject = $this->Subject;
				$message->Body = $this->Body;
				$message->MessageBoxID = $fromUser->IM_SentboxID;
				$message->RecipientType = $this->RecipientType;
				$message->write();			
			}
			
			if ($this->RecipientType == 'Member') {
				// Create the mail for TO (the recipient of this message)
				$message = new IM_Message();
				$message->FromID = $this->FromID;
				$message->ToID = $this->ToID;
				$message->Subject = $this->Subject;
				$message->Body = $this->Body;
				$message->MessageBoxID = $toUser->IM_InboxID;
				$message->write();
			}
			else if ($this->RecipientType == 'Course') {
				// Send to course participators
				$course = DataObject::get_by_id('Course', $this->ToID);
				if ($course) {
					$participators = $course->Participators();
					if ($participators) {
						foreach ($participators as $participator) {
							$message = new IM_Message();
							$message->FromID = $this->FromID;
							$message->ToID = $participator->ID;
							$message->Subject = $this->Subject;
							$message->Body = $this->Body;
							$message->send(false);
						}
					}
				}
			}
		}	
	}
}

?>
