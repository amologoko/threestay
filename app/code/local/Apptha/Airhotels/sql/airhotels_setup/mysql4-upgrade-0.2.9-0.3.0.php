<?php
$installer = $this;

$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->run("
  ALTER TABLE `bitnami_magento`.`airhotels_customer_inbox`
  ADD COLUMN `message_type` VARCHAR (255) NULL AFTER `receiver_read`,
  ADD COLUMN `order_id` INT (11) NULL AFTER `message_type` ;
");
$installer->endSetup();