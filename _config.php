<?php 

// set default timezone to Finnish, to get correct times
date_default_timezone_set('Europe/Helsinki');

Object::add_extension('SS_Datetime', 'SS_DatetimeExtension');

Object::add_extension('ReportAdmin', 'ReportAdminDecorator');

DataObject::add_extension('Member', 'MemberPermanentLockable');

Director::addRules(100, array('signup/$Action' => 'SignupHandler'));

eBookingCommunicator::$eBookingUrl = '';
eBookingCommunicator::$eBookingLogin = '';
eBookingCommunicator::$eBookingPassword = '';

Object::add_extension('DataObjectSet', 'DOSPaginationExtension');

DataObject::add_extension('Member', 'IM_MemberDecorator');

Email::setAdminEmail('');

?>