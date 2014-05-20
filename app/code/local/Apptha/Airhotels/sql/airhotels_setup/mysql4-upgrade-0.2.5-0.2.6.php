<?php
$installer = $this;

$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->addAttribute('catalog_product', 'property_app_fee', array(
        'group' => 'Property Information',
        'label' => 'Property App Fee',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'class' => 'validate-number',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));



$installer->endSetup();