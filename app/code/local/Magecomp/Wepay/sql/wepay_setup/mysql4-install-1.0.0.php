<?php

$installer = $this;

$installer->startSetup();
$installer->addAttribute(
    'customer',
    'wepay_account_id',
    array(
        'type'	 => 'int',
        'label'		=> 'Wepay account id',
        'visible'   => true,
		'required'	=> false
    )
);
$installer->addAttribute(
    'customer',
    'wepay_access_token',
    array(
        'type'	 => 'varchar',
        'label'		=> 'Wepay access token',
        'visible'   => true,
		'required'	=> false
    )
);
$installer->endSetup();