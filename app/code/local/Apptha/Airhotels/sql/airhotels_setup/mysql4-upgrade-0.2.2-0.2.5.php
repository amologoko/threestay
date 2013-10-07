<?php
$installer = $this;

$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->run("
ALTER TABLE  {$this->getTable('airhotels_customer_inbox')} ADD `mobileNo` varchar(20) NOT NULL, ADD `sender_read` tinyint NOT NULL, ADD `receiver_read` tinyint NOT NULL;
");

$installer->updateAttribute('catalog_product', 'price', 'apply_to', 'property');

$installer->addAttribute('catalog_product', 'banner', array(
        'group' => 'Property Information',
        'label' => 'Property Display in Homepage Banner',
       'type' => 'int',
        'input' => 'select',
        'default' => '',
        'backend' => '',
        'frontend' => '',
        'source' => 'eav/entity_attribute_source_boolean',
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
       'sort_order'        => 30
    ));

$installer->addAttribute('catalog_product', 'propertyapproved', array(
        'group' => 'Property Information',
        'label' => 'Property Approved',
        'type' => 'int',
        'input' => 'select',
        'default' => '',
        'backend' => '',
        'frontend' => '',
        'source' => 'eav/entity_attribute_source_boolean',
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
       'sort_order'        => 35
    ));

$installer->endSetup();