<?php

$installer = $this;

$installer->startSetup();

$installer->run("

            -- DROP TABLE IF EXISTS {$this->getTable('airhotels')};
            CREATE TABLE {$this->getTable('airhotels')} (
              `airhotels_id` int(11) unsigned NOT NULL auto_increment,
              `title` varchar(255) NOT NULL default '',
              `filename` varchar(255) NOT NULL default '',
              `content` text NOT NULL default '',
              `status` smallint(6) NOT NULL default '0',
              `created_time` datetime NULL,
              `update_time` datetime NULL,
              PRIMARY KEY (`airhotels_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


            CREATE TABLE {$this->getTable('airhotels_booking')} (
              `id` int(100) unsigned NOT NULL auto_increment,
              `entity_id` int(100) NOT NULL ,
              `customer_id` int(100) NOT NULL ,
              `fromdate` date NULL,
              `todate` date NULL,
              `accomodates` int(100) NOT NULL ,
             `host_fee` decimal(11,2) NOT NULL,
              `service_fee` decimal(11,2) NOT NULL,
              `subtotal` int(11) NOT NULL ,
              `order_id` int(100) NOT NULL ,
              `order_item_id` int(100) NOT NULL ,
              `order_status` smallint(6) NOT NULL default '0',
              `status` smallint(6) NOT NULL default '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


            CREATE TABLE IF NOT EXISTS {$this->getTable('airhotels_customer_inbox')} (
              `message_id` int(11) NOT NULL AUTO_INCREMENT,
              `sender_id` int(11) NOT NULL,
              `receiver_id` int(11) NOT NULL,
              `product_id` int(11) NOT NULL,
              `checkin` date NOT NULL,
              `checkout` date NOT NULL,
              `guest` smallint(6) NOT NULL,
              `message` text NOT NULL,
              `can_call` tinyint(4) NOT NULL,
              `timezone` text NOT NULL,
              `read_flag` tinyint(4) NOT NULL,
              `is_sender_delete` tinyint(4) NOT NULL,
              `is_receiver_delete` tinyint(4) NOT NULL,
              `is_reply` tinyint(1) NOT NULL,
              `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`message_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

            CREATE TABLE IF NOT EXISTS {$this->getTable('airhotels_customer_photo')}  (
              `customer_id` int(11) NOT NULL,
              `imagename` varchar(250) NOT NULL,
              `response_time` varchar(300) DEFAULT NULL,
              `more_host` text,
              `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`customer_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            CREATE TABLE IF NOT EXISTS {$this->getTable('airhotels_customer_reply')} (
              `reply_id` int(11) NOT NULL AUTO_INCREMENT,
              `message_id` int(11) NOT NULL,
              `customer_id` int(11) NOT NULL,
              `message` text NOT NULL,
              `is_delete` int(11) NOT NULL,
              `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`reply_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


    ");
            
    $installer->addAttribute('catalog_product', 'userid', array(
        'group' => 'Property Information',
        'label' => 'User ID',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'readonly' => 'readonly',
        'class' => 'validate-digits',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'propertytype', array(
        'group' => 'Property Information',
        'label' => 'Property Type',
        'type' => 'varchar',
        'input' => 'select',
        'default' => '',
        'class' => '',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'option' => array(
            'value' => array('Apartment' => array(0 => 'Apartment'), 'House' => array(0 => 'House'), 'Cottage' => array(0 => 'Cottage')),
            'order' => array('Apartment' => '0', 'House' => '1', 'Cottage' => '2')
        ),
        'visible_in_advanced_search' => true,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'accomodates', array(
        'group' => 'Property Information',
        'label' => 'Accomodates',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '30',
        'class' => 'validate-digits',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'privacy', array(
        'group' => 'Property Information',
        'label' => 'Room Type',
        'type' => 'varchar',
        'input' => 'select',
        'default' => '',
        'class' => '',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'option' => array(
            'value' => array('Private' => array(0 => 'Private'), 'Shared' => array(0 => 'Shared'), 'Public' => array(0 => 'Public')),
            'order' => array('Private' => '0', 'Shared' => '1', 'Public' => '2')
        ),
        'visible_in_advanced_search' => true,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'bedtype', array(
        'group' => 'Property Information',
        'label' => 'Bed Type',
        'type' => 'varchar',
        'input' => 'select',
        'default' => '',
        'class' => '',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'option' => array(
            'value' => array('Cushion' => array(0 => 'Cushion'), 'Real Bed' => array(0 => 'Real Bed'), 'Air Beds' => array(0 => 'Air Beds')),
            'order' => array('Cushion' => '0', 'Real Bed' => '1', 'Air Beds' => '2')
        ),
        'visible_in_advanced_search' => true,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'totalrooms', array(
        'group' => 'Property Information',
        'label' => 'Rooms Available',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '30',
        'class' => 'validate-digits',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
//    $installer->addAttribute('catalog_product', 'rent_from_date', array(
//            'backend'       => 'eav/entity_attribute_backend_datetime',
//            'source'        => '',
//            'group'		=> 'Property Information',
//            'label'         => 'Book From Date',
//            'input'         => 'date',
//            'class'         => '',
//            'type'          => 'datetime',
//            'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
//            'default_value' => '',
//            'visible'       => true,
//            'required'      => false,
//            'user_defined'  => false,
//            'default'       => '',
//            'apply_to'      => 'property',
//            'visible_on_front' => false
//    ));

//    $installer->addAttribute('catalog_product', 'rent_to_date', array(
//            'backend'       => 'eav/entity_attribute_backend_datetime',
//            'source'        => '',
//            'group'		=> 'Property Information',
//            'label'         => 'Book To Date',
//            'input'         => 'date',
//            'class'         => '',
//            'type'          => 'datetime',
//            'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
//            'default_value' => '',
//            'visible'       => true,
//            'required'      => false,
//            'user_defined'  => false,
//            'default'       => '',
//            'apply_to'      => 'property',
//            'visible_on_front' => false
//    ));

    $installer->addAttribute('catalog_product', 'state', array(
        'group' => 'Property Information',
        'label' => 'State',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'city', array(
        'group' => 'Property Information',
        'label' => 'City',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'country', array(
        'group' => 'Property Information',
        'label' => 'Country',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'hostemail', array(
        'group' => 'Property Information',
        'label' => 'Email',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'class' => 'validate-email',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'propertyadd', array(
        'group' => 'Property Information',
        'label' => 'Property Address',
        'type' => 'text',
        'input' => 'textarea',
        'default' => '',
        'class' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->updateAttribute('catalog_product', 'propertyadd', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
    $installer->updateAttribute('catalog_product', 'propertyadd', 'apply_to', 'property');

    $installer->addAttribute('catalog_product', 'houserule', array(
        'group' => 'Property Information',
        'label' => 'House rule',
        'type' => 'text',
        'input' => 'textarea',
        'default' => '',
        'class' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->updateAttribute('catalog_product', 'houserule', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
    $installer->updateAttribute('catalog_product', 'houserule', 'apply_to', 'property');

    $installer->addAttribute('catalog_product', 'property_website', array(
        'group' => 'Property Information',
        'label' => 'Property Website',
        'type' => 'varchar',
        'input' => 'text',
        'default' => '',
        'class' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->updateAttribute('catalog_product', 'property_website', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
    $installer->updateAttribute('catalog_product', 'property_website', 'apply_to', 'property');

    $installer->addAttribute('catalog_product', 'maplocation', array(
        'group' => 'Property Information',
        'label' => 'Map Location',
        'type' => 'text',
        'input' => 'textarea',
        'default' => '',
        'class' => '',
        'backend' => '',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'visible_in_advanced_search' => false,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->updateAttribute('catalog_product', 'maplocation', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
    $installer->updateAttribute('catalog_product', 'maplocation', 'apply_to', 'property');

    $installer->addAttribute('catalog_product', 'amenity', array(
        'group' => 'Property Information',
        'label' => 'Amenities',
        'type' => 'varchar',
        'input' => 'multiselect',
        'default' => '',
        'class' => '',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'option' => array(
            'value' => array('Smoking' => array(0 => 'Smoking',), 'Kitchen' => array(0 => 'Kitchen'), 'RoomService' => array(0 => 'Room Service')),
            'order' => array('Smoking' => '0', 'Kitchen' => '1', 'Roomservice' => '2')
        ),
        'visible_in_advanced_search' => true,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->updateAttribute('catalog_product', 'amenity', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
    $installer->updateAttribute('catalog_product', 'amenity', 'apply_to', 'property');
    $installer->addAttribute('catalog_product', 'cancelpolicy', array(
        'group' => 'Property Information',
        'label' => 'Cancellation Policy',
        'type' => 'varchar',
        'input' => 'select',
        'default' => '',
        'class' => '',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'option' => array(
            'value' => array('Flexible: Full refund 1 day prior to arrival, except fees' => array(0 => 'Flexible: Full refund 1 day prior to arrival, except fees'), 'Moderate: Full refund 5 days prior to arrival, except fees' => array(0 => 'Moderate: Full refund 5 days prior to arrival, except fees'), 'Strict: 50% refund up until 1 week prior to arrival, except fees' => array(0 => 'Strict: 50% refund up until 1 week prior to arrival, except fees')),
            'order' => array('Flexible: Full refund 1 day prior to arrival, except fees' => '0', 'Moderate: Full refund 5 days prior to arrival, except fees' => '1', 'Strict: 50% refund up until 1 week prior to arrival, except fees' => '2')
        ),
        'visible_in_advanced_search' => true,
        'unique' => false,
        'apply_to' => 'property',
    ));
    $installer->addAttribute('catalog_product', 'pets', array(
        'group' => 'Property Information',
        'label' => 'Pets On Premises',
        'type' => 'varchar',
        'input' => 'select',
        'default' => '',
        'class' => '',
        'backend' => 'eav/entity_attribute_backend_array',
        'frontend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => true,
        'user_defined' => false,
        'searchable' => true,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => true,
        'option' => array(
            'value' => array('Allowed' => array(0 => 'Allowed'), 'Not Allowed' => array(0 => 'Not Allowed')),
            'order' => array('Allowed' => '0', 'Not Allowed' => '1')
        ),
        'visible_in_advanced_search' => true,
        'unique' => false,
        'apply_to' => 'property',
    ));


    $installer->endSetup();