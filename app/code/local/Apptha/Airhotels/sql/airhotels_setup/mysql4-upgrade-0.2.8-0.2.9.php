<?php
$installer = $this;

$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->run("
insert into {$this->getTable('core_email_template')} (`template_id`, `template_code`, `template_text`, `template_styles`, `template_type`, `template_subject`, `template_sender_name`, `template_sender_email`, `added_at`, `modified_at`, `orig_template_code`, `orig_template_variables`) values('11','INBOX MESSAGE TEMPLATE','<h1>Message:</h1><br>\r\n<p>{{var message}}</p>\r\n<p>No of Guests :{{var no_of_guests}}</p>\r\n<p>Check In:{{var checkIn}} </p>\r\n<p>Check Out:{{var checkOut}}</p>\r\n<p>Timezone to call:{{var timeZone}}</p>\r\n<p>Telephone Number:{{var mobileNo}} </p>\r\n<a href=\"{{var reply}}\">Reply</a>',NULL,'2','Contact',NULL,NULL,'2014-06-20 11:28:46','2014-06-24 11:31:06',NULL,NULL);
");
$installer->endSetup();